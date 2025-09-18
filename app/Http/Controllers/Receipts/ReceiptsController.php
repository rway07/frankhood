<?php

declare(strict_types=1);

namespace App\Http\Controllers\Receipts;

use App\Events\ReceiptDeleted;
use App\Events\ReceiptSaved;
use App\Events\ReceiptUpdated;
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
        $currentYear = $years[0];
        $paymentTypes = PaymentTypes::all();

        return view(
            'receipts/list',
            [
                'years' => $years,
                'currentYear' => $currentYear,
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
     * @param $request
     * @return array
     * @throws Exception
     */
    private function validateStore($request): array
    {
        $validatedData = $request->validationData();
        $guardian = new ReceiptDataValidator();

        // Ottengo anno e quota in base all'ID della tariffa
        $rateData = DataFetcher::getRateData($validatedData['rates']);

        // Array con gli ID dei componenti del gruppo familiare
        $groupIds = explode(',', $validatedData['people']);

        if ($guardian->checkCustomersInExistingReceipt($rateData->year, $groupIds)) {
            throw new Exception(
                'Uno o più persone del gruppo familire risultano presenti in una ricevuta esistente.
                    Impossibile continuare.'
            );
        }

        // Grandezza gruppo familiare
        $numPeople = count($groupIds);

        // Determino se sono state usate le quote alternative
        if ($validatedData['quota_type'] == 0) {
            $customQuotaEnabled = false;
        } else {
            $customQuotaEnabled = true;
        }

        /**
         * Controllo integrità
         * Si controlla se il totale della ricevuta che arriva dal frontend corrisponde alla somma
         * [numero_persone] * [quota] oppure sommatoria [persona] * [quota custom]
         */
        $expectedTotal = 0;
        if (!$customQuotaEnabled) {
            $expectedTotal = $numPeople * $rateData->quota;
        } else {
            foreach ($groupIds as $id) {
                $expectedTotal += $validatedData['quotas-' . $id];
            }
        }

        if ($validatedData['total'] != $expectedTotal) {
            throw new Exception(
                'Errore integrità! Il totale della fattura non corrisponde
                    con al prodotto [Numero di Persone] * [Quota]'
            );
        }

        $validatedData['year'] = $rateData->year;
        $validatedData['quota'] = $rateData->quota;
        $validatedData['custom_quotas'] = $customQuotaEnabled;
        $validatedData['num_people'] = $numPeople;
        $validatedData['group_ids'] = $groupIds;

        return $validatedData;
    }

    /**
     * @param $validatedData
     * @return array
     */
    private function saveReceipt($validatedData): array
    {
        // Ottengo il primo numero di ricevuta disponibile
        $receiptNumber = DataFetcher::getAvailableReceiptNumber($validatedData['year']);

        DB::table('receipts')
            ->insert(
                [
                    'number' => $receiptNumber,
                    'year' => $validatedData['year'],
                    'date' => $validatedData['issue-date'],
                    'customers_id' => $validatedData['recipient'],
                    'payment_type_id' => $validatedData['payment_type'],
                    'rates_id' => $validatedData['rates'],
                    'total' => $validatedData['total'],
                    'custom_quotas' => $validatedData['custom_quotas'],
                    'num_people' => $validatedData['num_people']
                ]
            );

        // Aggiungo le voci dei singoli soci del gruppo nella tabella [customer_receipts]
        foreach ($validatedData['group_ids'] as $customerId) {
            if ($validatedData['custom_quotas']) {
                DB::table('customers_receipts')
                    ->insert(
                        [
                            'customers_id' => $customerId,
                            'number' => $receiptNumber,
                            'year' => $validatedData['year'],
                            'quota' => $validatedData['quotas-' . $customerId]
                        ]
                    );
            } else {
                DB::table('customers_receipts')
                    ->insert(
                        [
                            'customers_id' => $customerId,
                            'number' => $receiptNumber,
                            'year' => $validatedData['year'],
                            'quota' => $validatedData['quota']
                        ]
                    );
            }
        }

        return [
            'year' => $validatedData['year'],
            'number' => $receiptNumber,
            'date' => $validatedData['issue-date'],
            'paymentType' => $validatedData['payment_type'],
            'paymentTypeChanged' => false
        ];
    }

    /**
     * @param $receiptNumber
     * @param $receiptYear
     * @param $validatedData
     * @return array
     */
    private function updateReceipt($receiptNumber, $receiptYear, $validatedData): array
    {
        $currentPaymentData = DB::select(
            'select payment_type_id
            from receipts
            where year = ? and number = ?;',
            [
                $receiptYear,
                $receiptNumber
            ]
        );

        if ($currentPaymentData[0]->payment_type_id != $validatedData['payment_type']) {
            $paymentTypeChanged = true;
        } else {
            $paymentTypeChanged = false;
        }

        if ($validatedData['quota_type'] == config('custom.normalQuota')) {
            $customQuotas = false;
        } else {
            $customQuotas = true;
        }

        $groupIds = explode(',', $validatedData['people']);
        $numPeople = count($groupIds);

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
        foreach ($groupIds as $id) {
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

        return [
            'year' => $receiptYear,
            'number' => $receiptNumber,
            'date' => $validatedData['issue-date'],
            'paymentType' => $validatedData['payment_type'],
            'paymentTypeChanged' => $paymentTypeChanged
        ];
    }

    /**
     * @param $receiptNumber
     * @param $receiptYear
     * @return array
     */
    private function deleteReceipt($receiptNumber, $receiptYear): array
    {
        $receiptData = DB::select(
            'select year, number, date, payment_type_id
                from receipts
                where year = ? and number = ?;',
            [
                $receiptYear,
                $receiptNumber
            ]
        );

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

        return [
            'year' => $receiptYear,
            'number' => $receiptNumber,
            'date' => $receiptData[0]->date,
            'paymentType' => $receiptData[0]->payment_type_id,
            'paymentTypeChanged' => false
        ];
    }

    /**
     * @param ReceiptRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(ReceiptRequest $request): JsonResponse
    {
        try {
            $validatedData = $this->validateStore($request);

            DB::beginTransaction();
            $newReceipt = $this->saveReceipt($validatedData);
            DB::commit();

            event(new ReceiptSaved($newReceipt));
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
                    'number' => $newReceipt['number'],
                    'year' => $newReceipt['year'],
                    'message' => 'Ricevuta ' . $newReceipt['number'] . '/' . $newReceipt['year'] . ' emessa'
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

            DB::beginTransaction();
            $updatedReceipt = $this->updateReceipt($receiptNumber, $receiptYear, $validatedData);
            DB::commit();

            event(new ReceiptUpdated($updatedReceipt));
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
            $deletedReceipt = $this->deleteReceipt($receiptNumber, $receiptYear);
            DB::commit();

            event(new ReceiptDeleted($deletedReceipt));
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
