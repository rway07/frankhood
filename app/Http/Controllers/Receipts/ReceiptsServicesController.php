<?php
declare(strict_types=1);

namespace App\Http\Controllers\Receipts;

use App\Http\Controllers\Controller;
use App\Models\Rates;
use App\Models\Receipts;
use App\Util\CustomersDataValidator;
use App\Util\ReceiptDataValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App as App;
use Illuminate\Support\Facades\DB as DB;
use Illuminate\Support\Facades\Redirect as Redirect;

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
     */
    public function printReceipt($receiptNumber, $receiptYear)
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
     * @return JsonResponse
     */
    public function customerQuota($idCustomer, $number, $year): JsonResponse
    {
        $validator = new CustomersDataValidator();
        $receiptValidator = new ReceiptDataValidator();

        if (!$receiptValidator->checkReceiptNumber($year, $number)) {
            return response()->json(
                ['error' => ['message' => $receiptValidator->getReturnMessage()]]
            );
        }

        if (is_numeric($idCustomer) && $idCustomer > 0) {
            if (!$validator->checkCustomerID($idCustomer)) {
                return response()->json(
                    ['error' => ['message' => $validator->getReturnMessage()]]
                );
            }
        }

        if ($idCustomer == 0) {
            $data = DB::select(
                "select customers_id, quota
                from customers_receipts
                where number = {$number} and year = {$year};"
            );

            if (count($data) == 0) {
                $data = [];
            }
        } else {
            $data = DB::select(
                "select customers_id, quota
            from customers_receipts
            where number = ? and year = ? and customers_id = ?;",
                [
                    $number,
                    $year,
                    $idCustomer
                ]
            );

            (count($data) > 0) ? $data = $data[0] : $data = [];
        }



        return response()->json(
            [
                'data' => $data
            ]
        );
    }

    /**
     * Restiuisce una vista con le informazioni sulla ricevuta selezionata
     *
     * @param $receiptNumber
     * @param $receiptYear
     * @return JsonResponse
     */
    public function info($receiptNumber, $receiptYear): JsonResponse
    {
        $validator = new ReceiptDataValidator();

        if (!$validator->checkReceiptNumber($receiptYear, $receiptNumber)) {
            return response()->json(
                ['error' => ['message' => $validator->getReturnMessage()]]
            );
        }

        $receipt = Receipts::where([
            ['receipts.number', '=', $receiptNumber],
            ['receipts.year', '=', $receiptYear]
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
                ['customers_receipts.year', '=', $receiptYear]
            ])
            ->join('customers', 'customers_receipts.customers_id', '=', 'customers.id')
            ->select('first_name', 'last_name', 'quota')
            ->get();

        return response()->json(
            [
                'data' => [
                    'receipt' => $receipt,
                    'customers' => $customers
                ]
            ]
        );
    }

    /**
     * Controlla se il socio ha una ricevuta per l'anno selezionato
     *
     * @param $idCustomer
     * @param $year
     * @return JsonResponse
     */
    public function years($idCustomer, $year): JsonResponse
    {
        $validator = new CustomersDataValidator();

        if (!$validator->checkYear($year)) {
            return response()->json(
                ['error' => ['message' => $validator->getReturnMessage()]]
            );
        }

        if (!$validator->checkCustomerID($idCustomer)) {
            return response()->json(
                ['error' => ['message' => $validator->getReturnMessage()]]
            );
        }

        $idYear = Rates::where('year', '=', $year)->select('id')->get()->first();

        $data = DB::select(
            "select receipts.rates_id
            from customers_receipts
            join receipts on receipts.number = customers_receipts.number and receipts.year = customers_receipts.year
            where customers_receipts.customers_id = ? and receipts.rates_id = ?;",
            [
                $idCustomer,
                $idYear->id
            ]
        );

        (count($data) > 0) ? $data = $data[0] : $data = [];

        return response()->json(
            [
                'data' => $data
            ]
        );
    }
}
