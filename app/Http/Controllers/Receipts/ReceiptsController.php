<?php

declare(strict_types=1);

namespace App\Http\Controllers\Receipts;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReceiptRequest;
use App\Models\Customers;
use App\Models\PaymentTypes;
use App\Models\Rates;
use App\Models\Receipts;
use App\Util\DataFetcher;
use App\Util\ReceiptDataValidator;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect as Redirect;
use Illuminate\View\View as View;
use Throwable;

/**
 * Class ReceiptsController
 * @package App\Http\Controllers
 */
class ReceiptsController extends Controller
{
    /**
     * Restituisce la vista con la liste delle ricevute
     *
     * @return View
     */
    public function index(): View
    {
        $years = DataFetcher::getYears();
        $paymentTypes = PaymentTypes::all();

        return view(
            'receipts/list',
            [
                'years' => $years,
                'types' => $paymentTypes
            ]
        );
    }

    /**
     * Redirect alla lista delle ricevute con esito dell'operazione precedente
     *
     * @param ReceiptRequest $request
     * @return RedirectResponse
     */
    public function done(Request $request): RedirectResponse
    {
        $status = $request->status;

        return Redirect::to('receipts/index')->with('status', $status);
    }

    /**
     * Restituisce la vista per la creazione di una nuova ricevuta
     *
     * @return View
     */
    public function create(): View
    {
        $customers = Customers::orderBy('last_name', 'asc')->get();
        $rates = Rates::orderBy('year', 'desc')->get();
        $paymentTypes = PaymentTypes::all();
        $date = date('Y-m-d');

        return view(
            "receipts/create",
            [
                'customers' => $customers,
                'rates' => $rates,
                'date' => $date,
                'types' => $paymentTypes
            ]
        );
    }

    /**
     * Apre la vista di modifica di una ricevuta
     *
     * @param $receiptNumber
     * @param $receiptYear
     * @return RedirectResponse | View
     */
    public function edit($receiptNumber, $receiptYear)
    {
        $validator = new ReceiptDataValidator();

        if (!$validator->checkReceiptNumber($receiptYear, $receiptNumber)) {
            return Redirect::to('receipts/index')->withErrors($validator->getReturnMessage());
        }

        $receipts = Receipts::where(
            [
                ['receipts.number', '=', $receiptNumber],
                ['receipts.year', '=', $receiptYear]
            ]
        )->join('customers', 'receipts.customers_id', '=', 'customers.id')
            ->select(['receipts.*', 'customers.first_name', 'customers.last_name'])
            ->get()
            ->first();

        /*$customers = DB::select(
            "select customers.*
            from customers
            join customers_receipts on customers_receipts.customers_id = customers.id
            where customers_receipts.number = ? and customers_receipts.year = ?;",
            [
                $receiptNumber,
                $receiptYear
            ]
        );*/

        $rates = Rates::orderBy('year', 'desc')->get();
        $paymentTypes = PaymentTypes::all();

        return view(
            'receipts/create',
            [
                'receipts' => $receipts,
                'rates' => $rates,
                'types' => $paymentTypes
            ]
        );
    }

    /**
     * @param ReceiptRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(ReceiptRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validationData();
            $guardian = new ReceiptDataValidator();

            // Ottengo anno e quota in base all'ID della tariffa
            $rateData = DataFetcher::getRateData($validatedData['rates']);

            // Array con gli ID dei componenti del gruppo familiare
            $ids = explode(',', $validatedData['people']);

            if ($guardian->checkCustomersInExistingReceipt($rateData->year, $ids)) {
                throw new Exception(
                    'Uno o più persone del gruppo familire risultano presenti in una ricevuta esistente.
                    Impossibile continuare.'
                );
            }

            // Grandezza gruppo familiare
            $numPeople = count($ids);

            // Determino se sono state usate le quote alternative
            if ($validatedData['quota_type'] == 0) {
                $customQuotas = false;
            } else {
                $customQuotas = true;
            }

            /**
             * FIXME Move me
             * Controllo integritò
             * Si controlla se il totale della ricevuta che arriva dal frontend corrisponde alla somma
             * [numero_persone] * [quota] oppure sommatoria [persona] * [quota custom]
             */
            $totalCheck = 0;
            if (!$customQuotas) {
                $totalCheck = $numPeople * $rateData->quota;
            } else {
                foreach ($ids as $id) {
                    $totalCheck += $validatedData['quotas-' . $id];
                }
            }

            if ($request['total'] != $totalCheck) {
                throw new Exception(
                    'Errore integrità! Il totale della fattura non corrisponde
                    con al prodotto [Numero di Persone] * [Quota]'
                );
            }

            DB::beginTransaction();
            // Ottengo il primo numero di ricevuta disponibile
            $receiptNumber = DataFetcher::getAvailableReceiptNumber($rateData->year);

            // Salvo i dati della ricevuta
            DB::table('receipts')
                ->insert(
                    [
                        'number' => $receiptNumber,
                        'year' => $rateData->year,
                        'date' => $validatedData['issue-date'],
                        'customers_id' => $validatedData['recipient'],
                        'payment_type_id' => $validatedData['payment_type'],
                        'rates_id' => $validatedData['rates'],
                        'total' => $validatedData['total'],
                        'custom_quotas' => $customQuotas,
                        'num_people' => $numPeople
                    ]
                );

            // Aggiungo le voci dei singoli soci del gruppo nella tabella [customer_receipts]
            foreach ($ids as $id) {
                if ($customQuotas) {
                    DB::table('customers_receipts')
                        ->insert(
                            [
                                'customers_id' => $id,
                                'number' => $receiptNumber,
                                'year' => $rateData->year,
                                'quota' => $validatedData['quotas-' . $id]
                            ]
                        );
                } else {
                    DB::table('customers_receipts')
                        ->insert(
                            [
                                'customers_id' => $id,
                                'number' => $receiptNumber,
                                'year' => $rateData->year,
                                'quota' => $rateData->quota,
                            ]
                        );
                }
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(
                ['error' => ['message' => $e->getMessage()]]
            );
        }

        return response()->json(
            [
                'data' => [
                    'success' => true,
                    'number' => $receiptNumber,
                    'year' => $rateData->year,
                    'message' => 'Ricevuta ' . $receiptNumber . '/' . $rateData->year . ' emessa'
                ]
            ]
        );
    }

    /**
     * @param ReceiptRequest $request
     * @param $receiptNumber
     * @param $receiptYear
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(ReceiptRequest $request, $receiptNumber, $receiptYear): JsonResponse
    {
        try {
            $guardian = new ReceiptDataValidator();

            if (!$guardian->checkReceiptNumber($receiptYear, $receiptNumber)) {
                return response()->json(
                    ['error' => ['message' => $guardian->getReturnMessage()]]
                );
            }

            $validatedData = $request->validationData();

            if ($validatedData['quota_type'] == 0) {
                $customQuotas = false;
            } else {
                $customQuotas = true;
            }

            $ids = explode(',', $validatedData['people']);
            $numPeople = count($ids);

            DB::beginTransaction();
            DB::table('receipts')
                ->where(
                    [
                        ['number', '=' ,$receiptNumber],
                        ['year', '=', $receiptYear]
                    ]
                )
                ->update(
                    [
                        'date' => $validatedData['issue-date'],
                        'customers_id' => $validatedData['recipient'],
                        'payment_type_id' => $validatedData['payment_type'],
                        'rates_id' => $validatedData['rates'],
                        'total' => $validatedData['total'],
                        'custom_quotas' => $customQuotas,
                        'num_people' => $numPeople
                    ]
                );

            // Remove the previous receipts-customers association
            DB::table('customers_receipts')
                ->where(
                    [
                        ['number', '=' ,$receiptNumber],
                        ['year', '=', $receiptYear]
                    ]
                )
                ->delete();

            // Create the new receipt-customer association
            foreach ($ids as $id) {
                $quota = $validatedData['quota'];
                if ($customQuotas) {
                    $quota = $validatedData['quotas-' . $id];
                }

                DB::table('customers_receipts')
                    ->insert(
                        [
                            'customers_id' => $id,
                            'number' => $receiptNumber,
                            'year' => $receiptYear,
                            'quota' => $quota,
                        ]
                    );
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(
                ['error' => ['message' => $e->getMessage()]]
            );
        }

        return response()->json(
            [
                'data' => [
                    'success' => true,
                    'number' => $receiptNumber,
                    'year' => $receiptYear,
                    'message' => 'Ricevuta ' . $receiptNumber . '/' . $receiptYear . ' aggiornata'
                ]
            ]
        );
    }

    /**
     * Elimina una ricevuta
     *
     * @param $receiptNumber
     * @param $receiptYear
     * @return JsonResponse
     * @throws Throwable
     */
    public function destroy($receiptNumber, $receiptYear): JsonResponse
    {
        try {
            $validator = new ReceiptDataValidator();

            if (!$validator->checkReceiptNumber($receiptYear, $receiptNumber)) {
                return response()->json(
                    ['error' => ['message' => $validator->getReturnMessage()]]
                );
            }

            DB::beginTransaction();

            $rows = DB::delete(
                "delete from receipts
                    where number = ? and year = ?;",
                [
                    $receiptNumber,
                    $receiptYear
                ]
            );
            Log::debug('Deleting receipts data. Rows affected: ' . $rows);

            $rows = DB::delete(
                "delete from customers_receipts
                    where number = ? and year = ?;",
                [
                    $receiptNumber,
                    $receiptYear
                ]
            );
            Log::debug('Deleting customes_receipts data. Rows affected: ' . $rows);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(
                ['error' => ['message' => $e->getMessage()]]
            );
        }

        return response()->json(
            [
                'data' => [
                    'message' => 'Ricevuta ' . $receiptNumber . '/' . $receiptYear . ' eliminata'
                ]
            ]
        );
    }
}
