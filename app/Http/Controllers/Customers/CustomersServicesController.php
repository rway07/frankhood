<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\Customers;
use App\Util\CustomersDataValidator;
use App\Util\ReceiptDataValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as DB;

/**
 * Class CustomersServicesController
 * @package App\Http\Controllers
 */
class CustomersServicesController extends Controller
{
    /**
     * @param $text
     * @param $year
     * @param $exclude
     * @return array
     */
    private function namesQuery($text, $year, $exclude): array
    {
        if ($exclude == 0) {
            $data = DB::select(
                "select distinct customers.id, first_name, last_name, alias,
                strftime('%d/%m/%Y', birth_date) as birth_date
            from customers
            left outer join customers_receipts on customers_id = customers.id
            where revocation_date = '' and death_date = '' and enrollment_year <= {$year}
                  and (first_name like '%{$text}%' or last_name like '%{$text}%' or alias like '%{$text}%')
              and customers.id not in (
              select customers_id
              from customers_receipts
              where year >= {$year});"
            );
        } else {
            $data = DB::select(
                "select distinct customers.id, first_name, last_name, alias,
                        strftime('%d/%m/%Y', birth_date) as birth_date
                from customers
                left outer join customers_receipts on customers_id = customers.id
                where revocation_date = '' and death_date = '' and enrollment_year <= {$year}
                  and (first_name like '%{$text}%' or last_name like '%{$text}%' or alias like '%{$text}%')
                and customers.id not in (
                    select customers_id
                    from customers_receipts
                    where year >= {$year}
                    except
                    select c.id
                    from customers c
                    join customers_receipts cr on cr.customers_id = c.id
                    where cr.year = {$year} and cr.number = {$exclude}
                );"
            );
        }

        return $data;
    }

    /**
     * @param $idCustomer
     * @return array
     */
    private function receiptsQuery($idCustomer): array
    {
        return DB::select(
            "select receipts.date, receipts.number, receipts.year
                from customers_receipts
                join receipts on customers_receipts.number = receipts.number and customers_receipts.year = receipts.year
                where customers_receipts.customers_id = {$idCustomer}
                order by receipts.date desc
                limit 1;"
        );
    }

    /**
     * @param Request $request
     * @param $year
     * @return JsonResponse
     */
    public function customersNamesPerYear(Request $request, $year, $exclude): JsonResponse
    {
        $validator = new ReceiptDataValidator();
        if (!$validator->checkYear($year)) {
            return response()->json(
                [
                    "error" => ["message" => $validator->getReturnMessage()]
                ]
            );
        }

        if (!is_numeric($exclude)) {
            return response()->json(
                [
                    'error' => ['message' => 'exclude must be numeric value']
                ]
            );
        }

        if ($exclude != 0) {
            if (!$validator->checkReceiptNumber($year, $exclude)) {
                return response()->json(
                    [
                        'error' => ['message' => $validator->getReturnMessage()]
                    ]
                );
            }
        }

        $text = $request->input('name');
        $names = $this->namesQuery($text, $year, $exclude);

        return response()->json(
            [
                "data" => ["names" => $names]
            ]
        );
    }

    /**
     * Ottiene i dati per la richiesta AJAX generata durante la creazione di una nuova ricevuta.
     * Per ogni membro di un gruppo familiare vengono richiesti i dati.
     *
     * @param $idCustomer
     *
     * @return JsonResponse
     */
    public function recipient($idCustomer): JsonResponse
    {
        $validator = new CustomersDataValidator();
        if (!$validator->checkCustomerID($idCustomer)) {
            return response()->json(
                [
                    "error" => ["message" => $validator->getReturnMessage()]
                ]
            );
        }

        $recipient = Customers::where('id', '=', $idCustomer)
            ->select(['id', 'first_name', 'last_name', 'alias', 'birth_date'])
            ->get();

        (count($recipient) > 0) ? $recipient = $recipient[0] : $recipient = [];

        return response()->json(
            [
                "data" => ["recipient" => $recipient]
            ]
        );
    }

    /**
     * @param $idCustomer
     * @return JsonResponse
     */
    public function customerInfo($idCustomer): JsonResponse
    {
        $validator = new CustomersDataValidator();
        if (!$validator->checkCustomerID($idCustomer)) {
            return response()->json(
                [
                    "error" => ["message" => $validator->getReturnMessage()]
                ]
            );
        }

        $customersData =  DB::select(
            'select *
            from customers
            where id = ?;',
            [
                $idCustomer
            ]
        );

        return response()->json(
            [
                "data" => ["info" => $customersData]
            ]
        );
    }

    /**
     * Restituisce il gruppo familiare di un socio
     *
     * @param $idCustomer
     * @param $year
     * @param $edit
     * @return JsonResponse
     */
    public function getGroup($idCustomer, $year, $edit): JsonResponse
    {
        $final = [];
        $validator = new CustomersDataValidator();

        // Converto $edit da string a boolean
        $editFlag = filter_var($edit, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        // Sempre controllo dei parametri!!
        if (!$validator->checkYear($year)) {
            return response()->json(
                [
                    "error" => ["message" => $validator->getReturnMessage()]
                ]
            );
        }

        if (!$validator->checkCustomerID($idCustomer)) {
            return response()->json(
                [
                    "error" => ["message" => $validator->getReturnMessage()]
                ]
            );
        }

        if (!is_bool($editFlag)) {
            return response()->json(
                [
                    "error" => ["message" => "Ask for guru meditation: edit is not boolean."]
                ]
            );
        }

        $beginDate = $year . '-01-01';

        // Ottengo la lista delle ricevute per il socio corrente
        $receipts = $this->receiptsQuery($idCustomer);

        // Se il socio ha già delle ricevute, procedo nell'ottenere il gruppo familiare
        if (!empty($receipts)) {
            // Ottengo tutti i membri del gruppo familiare basandomi su quello dell'ultimo anno
            $customers = DB::select(
                "SELECT customers.id, customers.first_name, customers.last_name
                    FROM customers
                    JOIN customers_receipts ON customers_receipts.customers_id = customers.id
                    WHERE customers_receipts.number = ?
                      AND customers_receipts.year = ?
                    AND customers.id NOT IN (
                      SELECT id
                      FROM customers
                      WHERE death_date >= ?
                    ) AND customers.id IN (
                      SELECT id
                      FROM customers
                      WHERE death_date = ''
                    ) AND customers.id NOT IN (
                      SELECT id
                      FROM customers
                      WHERE revocation_date >= ?
                    ) AND customers.id IN (
                      SELECT id
                      FROM customers
                      WHERE revocation_date = ''
                    ) AND enrollment_year <= ?
                    order by birth_date;",
                [
                    $receipts[0]->number,
                    $receipts[0]->year,
                    $beginDate,
                    $beginDate,
                    $year
                ]
            );

            // Se sono in modalità [Edit], non controllo se i membri del gruppo familiare
            // hanno già una ricevuta per l'anno corrente
            // Serve nel caso un membro del gruppo abbia fatto una ricevuta per conto suo o in un altro gruppo
            if ($editFlag) {
                foreach ($customers as $c) {
                    $final[] = [
                        'id' => $c->id,
                        'first_name' => $c->first_name,
                        'last_name' => $c->last_name,
                        'late' => false
                    ];
                }
            } else {
                // Per ogni socio
                foreach ($customers as $index => $c) {
                    // Controllo se il socio ha già una ricevuta nell'anno corrente
                    $data = DB::select(
                        "select id, year
                            from customers_receipts
                            where customers_id = ?
                            order by year desc
                            limit 1;",
                        [
                            $c->id
                        ]
                    );

                    // Aggiungo il socio senza ricevuta per l'anno corrente
                    $previousYear = $year - 1;
                    // La condizione è che il socio abbia una ricevuta per l'anno precedente o prima ancora
                    if ($data[0]->year <= $previousYear) {
                        $final[] = [
                            'id' => $c->id,
                            'first_name' => $c->first_name,
                            'last_name' => $c->last_name,
                            'late' => false
                        ];

                        // Nel caso di ricevuta più vecchia dell'anno precedente a quello corrente,
                        // si ha un socio moroso
                        if ($data[0]->year < $previousYear) {
                            $final[$index]['late'] = true;
                        }
                    }
                }
            }
        } else {
            // Caso in cui il socio è stato appena iscritto e non ha ancora un ricevuta
            // E' possibile il caso in cui sia stato iscritto ma comunque senza ricevuta dopo un anno
            // Improbabile salvo il caso di un operatore incompetente
            // Nessuna ricevuta precedente, ottengo i dati del socio
            $data = DB::select(
                'select id, first_name, last_name, enrollment_year
                    from customers
                    where id = ?',
                [
                    $idCustomer
                ]
            );

            $final[] = [
                'id' => $data[0]->id,
                'first_name' => $data[0]->first_name,
                'last_name' => $data[0]->last_name,
                'late' => false
            ];

            // Controllo quanto tempo è passato dall'anno di iscrizione
            // Se l'anno di iscrizione è precedente all'anno corrente, il socio è moroso
            if ($year > $data[0]->enrollment_year) {
                $final[0]['late'] = true;
            }
        }

        return response()->json(
            [
                "data" => ["groups" => $final]
            ]
        );
    }
}
