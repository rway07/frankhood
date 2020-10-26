<?php

namespace App\Http\Controllers\Receipts;

use App\Models\Rates;
use App\Models\Receipts;
use App\Http\Controllers\Controller;
use DB;
use App;

/**
 * Class ReceiptsServicesController
 * @package App\Http\Controllers\Receipts
 */
class ReceiptsServicesController extends Controller
{
    /**
     * Stampa la ricevuta passata per parametro
     *
     * @param $receiptNumber
     * @param $receiptYear
     * @return mixed
     * @internal param $receiptId
     */
    public function printReceipt($receiptNumber, $receiptYear)
    {
        $receipts = Receipts::where(
            [
                ['receipts.number', '=', $receiptNumber],
                ['receipts.year', '=', $receiptYear]
            ]
        )
        ->join('customers', 'customers_id', '=', 'customers.id')
        ->join('rates', 'rates_id', '=', 'rates.id')
        ->join('payment_types', 'payment_types.id', '=', 'receipts.payment_type_id')
        ->select(
            [
                'receipts.number',
                'receipts.year',
                'date',
                'total',
                'quota',
                'payment_types.description',
                'customers.first_name',
                'customers.last_name'
            ]
        )
        ->get()
        ->first();

        $data = DB::select(
            "select customers.first_name, customers.last_name, customers.birth_date, quota
            from customers
            join customers_receipts on customers_receipts.customers_id = customers.id
            where customers_receipts.number = ? and customers_receipts.year = ?;",
            [
                $receiptNumber,
                $receiptYear
            ]
        );

        $pdf = App::make('snappy.pdf.wrapper');
        $pdf->loadView('receipts/util/print', ['receipts' => $receipts, 'data' => $data]);

        return $pdf->inline();
    }

    /**
     * Restituisce la quota pagata dal socio desiderato
     *
     * @param $idCustomer
     * @param $number
     * @param $year
     * @return array
     */
    public function customerQuota($idCustomer, $number, $year)
    {
        $quota = DB::select(
            "select customers_id, quota
            from customers_receipts
            where number = ? and year = ? and customers_id = ?;",
            [
                $number,
                $year,
                $idCustomer
            ]
        );

        return $quota;
    }

    /**
     * Restiuisce una vista con le informazioni sulla ricevuta selezionata
     *
     * @param $receiptNumber
     * @param $receiptsYear
     * @return array
     */
    public function info($receiptNumber, $receiptsYear)
    {
        $receipt = Receipts::where([
            ['receipts.number', '=', $receiptNumber],
            ['receipts.year', '=', $receiptsYear]
        ])
        ->join('rates', 'rates_id', '=', 'rates.id')
        ->join('customers', 'customers_id', '=', 'customers.id')
        ->join('payment_types', 'payment_types.id', '=', 'receipts.payment_type_id')
        ->select(
            [
                'receipts.*',
                'customers.first_name',
                'customers.last_name',
                'rates.year',
                'rates.quota',
                'payment_types.description'
            ]
        )
        ->get()
        ->first();

        $customers = DB::table('customers_receipts')
            ->where([
                ['customers_receipts.number', '=', $receiptNumber],
                ['customers_receipts.year', '=', $receiptsYear]
            ])
            ->join('customers', 'customers_receipts.customers_id', '=', 'customers.id')
            ->select('first_name', 'last_name', 'quota')
            ->get();

        //return view("receipts/util/info", ['receipt' => $receipt, 'customers' => $customers]);
        return [
            'receipt' => $receipt,
            'customers' => $customers
        ];
    }

    /**
     *
     *
     * @param $idCustomer
     * @param $year
     * @return mixed
     * @internal param $receiptId
     */
    public function years($idCustomer, $year)
    {
        $yearId = Rates::where('year', '=', $year)->select('id')->get()->first();

        $years = DB::select(
            "select receipts.rates_id
            from customers_receipts
            join receipts on receipts.number = customers_receipts.number and receipts.year = customers_receipts.year
            where customers_receipts.customers_id = ? and receipts.rates_id = ?;",
            [
                $idCustomer,
                $yearId->id
            ]
        );
        return $years;
    }
}
