<?php

namespace App\Http\Controllers\Customers;

use App;
use App\Models\Customers;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

/**
 * Class CustomersServicesController
 * @package App\Http\Controllers
 */
class CustomersServicesController extends Controller
{
    /**
     * @param Request $request
     * @param $year
     * @return \Illuminate\Http\JsonResponse
     */
    public function names(Request $request, $year)
    {
        $text = $request->input('q');
        $final = DB::select(
            "select distinct customers.id, first_name, last_name, alias,
                strftime('%d/%m/%Y', birth_date) as birth_date
            from customers
            left outer join customers_receipts on customers_id = customers.id
            where revocation_date = '' and death_date = '' and enrollment_year <= ?
                  and (first_name like '%{$text}%' or last_name like '%{$text}%' or alias like '%{$text}%')
              and customers.id not in (
              select customers_id
              from customers_receipts
              where year = ?);",
            [
                $year,
                $year
            ]
        );

        return response()->json(
            [
                'data' => $final
            ]
        );
    }

    /**
     * Ottiene i dati per la richiesta AJAX generata durante la creazione di una nuova ricevuta.
     * Per ogni membro di un gruppo familiare vengono richiesti i dati.
     *
     * @param $idCustomer
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function recipient($idCustomer)
    {
        $recipient = Customers::where('id', '=', $idCustomer)
            ->select(['id', 'first_name', 'last_name', 'alias', 'birth_date'])
            ->get();

        return $recipient;
    }

    /**
     * Restituisce il gruppo familiare di un socio
     *
     * @param $idCustomer
     * @param $year
     * @param $edit
     * @return array
     */
    public function getGroup($idCustomer, $year, $edit)
    {
        $beginDate = $year . '-01-01';
        $final = [];

        $receipts = DB::select(
            "select receipts.date, receipts.number, receipts.year
            from customers_receipts
            join receipts on customers_receipts.number = receipts.number and customers_receipts.year = receipts.year
            where customers_receipts.customers_id = ?
            order by receipts.date desc;",
            [
                $idCustomer
            ]
        );

        if (!empty($receipts)) {
            $customers = DB::select(
                "SELECT customers.id, customers.first_name, customers.last_name
                FROM customers
                JOIN customers_receipts ON customers_receipts.customers_id = customers.id
                WHERE customers_receipts.number = ? AND customers_receipts.year = ?
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
                )AND enrollment_year <= ?;",
                [
                    $receipts[0]->number,
                    $receipts[0]->year,
                    $beginDate,
                    $beginDate,
                    $year
                ]
            );

            if (strcmp($edit, 'true') == 0) {
                $final = $customers;
            } else {
                foreach ($customers as $c) {
                    $data = DB::select(
                        "SELECT id
                        FROM customers_receipts
                        WHERE customers_id = ? AND year = ?;",
                        [
                            $c->id,
                            $year
                        ]
                    );

                    if (empty($data) == true) {
                        $final[] = ['id' => $c->id, 'first_name' => $c->first_name, 'last_name' => $c->last_name];
                    }
                }
            }
        } else {
            $data = DB::select(
                'select id, first_name, last_name
                from customers
                where id = ?',
                [
                    $idCustomer
                ]
            );

            $final[] = ['id' => $data[0]->id, 'first_name' => $data[0]->first_name, 'last_name' => $data[0]->last_name];
        }

        return $final;
    }
}
