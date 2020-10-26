<?php

namespace App\Http\Controllers\Receipts;

use App\Models\Customers;
use App\Models\Rates;
use App\Models\Receipts;
use App\Models\PaymentTypes;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as DB;
use Exception;
use Illuminate\Support\Facades\Log;
use yajra\Datatables\Datatables;

/**
 * Class ReceiptsController
 * @package App\Http\Controllers
 */
class ReceiptsController extends Controller
{
    /**
     * Restituisce la vista con la liste delle ricevute
     *
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $years = DB::select(
            'select distinct year
            from receipts
            order by year desc;'
        );

        $types = DB::select(
            'select distinct id, description
            from payment_types;'
        );

        return view(
            'receipts/list',
            [
                'years' => $years,
                'types' => $types
            ]
        );
    }

    /**
     * Redirect alla lista delle ricevute con esito dell'operazione precedente
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function done(Request $request)
    {
        $status = $request->status;

        $years = DB::select(
            'select distinct year
            from receipts
            order by year desc;'
        );

        $types = DB::select(
            'select distinct id, description
            from payment_types;'
        );

        return view(
            'receipts/list',
            [
                'status' => $status,
                'years' => $years,
                'types' => $types
            ]
        );
    }

    /**
     * Restituisce la vista per la creazione di una nuova ricevuta
     *
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $customers = Customers::orderBy('last_name', 'asc')->get();
        $rates = Rates::orderBy('year', 'desc')->get();
        $date = date('Y-m-d');
        $paymentTypes = PaymentTypes::all();

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
     * Dati per la datatable con la lista delle ricevute
     *
     * @param $year
     * @param $type
     * @return mixed
     * @throws Exception
     */
    public function data($year, $type)
    {
        $receipts = Receipts::join('rates', 'receipts.rates_id', '=', 'rates.id')
            ->join('customers', 'receipts.customers_id', '=', 'customers.id')
            ->join('payment_types', 'payment_types.id', '=', 'receipts.payment_type_id')
            ->select([
                'receipts.number',
                DB::raw('(receipts.number || \'/\' || receipts.year) as receipt_number'),
                'receipts.date',
                DB::raw('(customers.first_name || \' \' || customers.last_name) as name'),
                'receipts.customers_id',
                'rates.year',
                'payment_types.id',
                'payment_types.description',
                'receipts.total'
            ])
            ->orderBy('receipts.year', 'desc')
            ->orderBy('receipts.number', 'desc');

        if ($year != 0) {
            $receipts = $receipts->where('receipts.year', '=', $year);
        }

        if ($type != 0) {
            $receipts = $receipts->where('payment_types.id', '=', $type);
        }

        return Datatables::of($receipts)
            ->editColumn('total', '{{$total}}€')
            ->editColumn('date', '{{ strftime("%d/%m/%Y", strtotime($date)) }}')
            ->addColumn('Info', function ($val) {
                return "<button type='button' class='btn btn-primary btn-sm'
                    onclick='info(". $val->number . ", " . $val->year . ")'>
                                    <i class='fa fa-btn fa-info'> </i> Info</button>";
            })
            ->addColumn('Stampa', function ($val) {
                return "<button type='button' class='btn btn-warning btn-sm'
                onclick='print(". $val->number . ", " . $val->year . ")'>
                <i class='fa fa-btn fa-print'> </i> Stampa</button>";
            })
            ->addColumn('Modifica', function ($val) {
                return "<button type='button' class='btn btn-info btn-sm'
                    onclick='edit(" . $val->number . ", " . $val->year . ")'>
                    <i class='fa fa-btn fa-edit'> </i> Modifica</button>";
            })
            ->addColumn('Elimina', function ($val) {
                return "<button id='" . $val->number . "_" . $val->year . "' type='button' class='btn btn-danger btn-sm'
                onclick='destroy(" . $val->number . ", " . $val->year . ")' csrf_token='"
                . csrf_token() . "'> <i class='fa fa-btn fa-trash'> </i> Elimina</button>";
            })
            ->rawColumns(['Info', 'Stampa', 'Modifica', 'Elimina'])
            ->make(true);
    }

    /**
     * Salva una ricevuta
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|string
     * @throws Exception
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'issue_date' => 'required|date',
            'rates' => 'required|integer',
            'recipient' => 'required|integer',
            'total' => 'required|integer'
        ]);

        try {
            DB::beginTransaction();

            // Ottengo anno e quota in base all'ID della tariffa
            $year = DB::select(
                "select year, quota
                    from rates
                    where id = ?;",
                [
                    $request->rates
                ]
            );

            // Determino se sono state usate le quote alternative
            if ($request->quota_type == 0) {
                $customQuotas = false;
            } else {
                $customQuotas = true;
            }

            // Array con gli ID dei componenti del gruppo familiare
            $ids = explode(',', $request->people);
            // Grandezza gruppo familiare
            $numPeople = count($ids);

            /**
             * Controllo integritò
             * Si controlla se il totale della ricevuta che arriva dal frontend corrisponde alla somma
             * [numero_persone] * [quota] oppure sommatoria [persona] * [quota custom]
             */
            $totalCheck = 0;
            if ($customQuotas == false) {
                $totalCheck = $numPeople * $year[0]->quota;
            } else {
                foreach ($ids as $id) {
                    $totalCheck += $$request->{"quotas-" . $id};
                }
            }

            if ($request->total != $totalCheck) {
                throw new Exception(
                    'Errore integrità! Il totale della fattura non corrisponde
                    con al prodotto [Numero di Persone] * [Quota]'
                );
            }

            // Ottengo il primo numero di ricevuta disponibile
            $number = DB::select(
                "select max(number) as number
                    from receipts
                    where year = ?;",
                [
                    $year[0]->year
                ]
            );

            if ($number[0]->number != null) {
                $number[0]->number++;
            } else {
                $number[0]->number = 1;
            }

            // Salvo i dati della ricevuta
            DB::table('receipts')
                ->insert(
                    [
                        'number' => $number[0]->number,
                        'year' => $year[0]->year,
                        'date' => $request->issue_date,
                        'customers_id' => $request->recipient,
                        'payment_type_id' => $request->payment_type,
                        'rates_id' => $request->rates,
                        'total' => $request->total,
                        'custom_quotas' => $customQuotas,
                        'num_people' => $numPeople
                    ]
                );

            // Aggiungo le voci dei singoli soci del gruppo nella tabella [customer_receipts]
            foreach ($ids as $id) {
                if ($customQuotas == true) {
                    DB::table('customers_receipts')
                        ->insert(
                            [
                                'customers_id' => $id,
                                'number' => $number[0]->number,
                                'year' => $year[0]->year,
                                'quota' => $request->{"quotas-" . $id},
                            ]
                        );
                } else {
                    DB::table('customers_receipts')
                        ->insert(
                            [
                                'customers_id' => $id,
                                'number' => $number[0]->number,
                                'year' => $year[0]->year,
                                'quota' => $year[0]->quota,
                            ]
                        );
                }
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }

        return response()->json(
            [
                'number' => $number[0]->number,
                'year' => $year[0]->year
            ]
        );
    }

    /**
     * Apre la vista di modifica di una ricevuta
     *
     * @param $receiptNumber
     * @param $receiptYear
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @internal param $receiptId
     */
    public function edit($receiptNumber, $receiptYear)
    {
        $receipts = Receipts::where(
            [
                ['receipts.number', '=', $receiptNumber],
                ['receipts.year', '=', $receiptYear]
            ]
        )
        ->join('customers', 'receipts.customers_id', '=', 'customers.id')
        ->select(['receipts.*', 'customers.first_name', 'customers.last_name'])
        ->get()
        ->first();

        $customers = DB::select(
            "select customers.*
            from customers
            join customers_receipts on customers_receipts.customers_id = customers.id
            where customers_receipts.number = ? and customers_receipts.year = ?;",
            [
                $receiptNumber,
                $receiptYear
            ]
        );
        $rates = Rates::orderBy('year', 'desc')->get();
        $paymentTypes = PaymentTypes::all();

        return view(
            'receipts/create',
            [
                'customers' => $customers,
                'receipts' => $receipts,
                'rates' => $rates,
                'types' => $paymentTypes
            ]
        );
    }

    /**
     * Aggiorna la ricevuta selezionata
     *
     * @param Request $request
     * @param $receiptNumber
     * @param $receiptYear
     * @return \Illuminate\Http\JsonResponse|string
     * @throws Exception
     */
    public function update(Request $request, $receiptNumber, $receiptYear)
    {
        $this->validate($request, [
            'issue_date' => 'required|date',
            'rates' => 'required|integer',
            'recipient' => 'required|integer',
            'total' => 'required|integer'
        ]);

        try {
            DB::beginTransaction();
            if ($request->quota_type == 0) {
                $customQuotas = false;
            } else {
                $customQuotas = true;
            }

            $ids = explode(',', $request->people);
            $numPeople = count($ids);

            DB::table('receipts')
                ->where(
                    [
                        ['number', '=' ,$receiptNumber],
                        ['year', '=', $receiptYear]
                    ]
                )
                ->update(
                    [
                        'date' => $request->issue_date,
                        'customers_id' => $request->recipient,
                        'payment_type_id' => $request->payment_type,
                        'rates_id' => $request->rates,
                        'total' => $request->total,
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
                if ($customQuotas == true) {
                    DB::table('customers_receipts')
                        ->insert(
                            [
                                'customers_id' => $id,
                                'number' => $receiptNumber,
                                'year' => $receiptYear,
                                'quota' => $request->{"quotas-" . $id},
                            ]
                        );
                } else {
                    DB::table('customers_receipts')
                        ->insert(
                            [
                                'customers_id' => $id,
                                'number' => $receiptNumber,
                                'year' => $receiptYear,
                                'quota' => $request->quota,
                            ]
                        );
                }
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }

        return response()->json(
            [
                'number' => $receiptNumber,
                'year' => $receiptYear
            ]
        );
    }

    /**
     * Elimina la ricevuta selezionata
     *
     * @param $receiptNumber
     * @param $receiptYear
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */
    public function destroy($receiptNumber, $receiptYear)
    {
        try {
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
                [
                    'status' => 'error',
                    'code' => $e->getCode(),
                    'error_message' => $e->getMessage(),
                    'line' => $e->getLine(),
                    'message' => 'Errore nella transazione, impossibile eliminare'
                ]
            );
        }

        return response()->json(['status' => 'OK', 'message' => 'OK']);
    }
}
