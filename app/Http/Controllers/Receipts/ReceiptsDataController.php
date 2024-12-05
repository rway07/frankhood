<?php
declare(strict_types=1);

namespace App\Http\Controllers\Receipts;

use App\Http\Controllers\Controller;
use App\Models\Receipts;
use App\Util\ReceiptDataValidator;
use Exception;
use Illuminate\Support\Facades\DB as DB;
use Yajra\DataTables\DataTables;

/**
 *
 */
class ReceiptsDataController extends Controller
{
    /**
     * @param $year
     * @param $type
     * @return mixed
     * @throws Exception
     */
    private function getData($year, $type)
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

        /**
         * Per qualche motivo, in questa versione delle Dataables è necessario aggiungere
         * un filterColumn() per ogni campo concatenato. La query generata aggiunge
         * il campo alias alla tabella di partenza, e nela clausola WHERE questo non viene
         * riconosciuto.
         */
        return DataTables::of($receipts)
            ->editColumn('total', '{{$total}}€')
            ->editColumn('date', '{{ date("d/m/Y", strtotime($date)) }}')
            ->filterColumn('receipt_number', function ($query, $keyword) {
                $query->whereRaw('(receipts.number || \'/\' || receipts.year) like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('name', function ($query, $keyword) {
                $query->whereRaw('(customers.first_name || \' \' || customers.last_name) like ?', ["%{$keyword}%"]);
            })
            ->addColumn('Info', function ($entry) {
                return view(
                    'receipts.buttons.info',
                    [
                        'receiptNumber' => $entry->number,
                        'receiptYear' => $entry->year
                    ]
                );
            })
            ->addColumn('Stampa', function ($entry) {
                return view(
                    'receipts.buttons.print',
                    [
                        'receiptNumber' => $entry->number,
                        'receiptYear' => $entry->year
                    ]
                );
            })
            ->addColumn('Modifica', function ($entry) {
                return view(
                    'common.edit',
                    [
                        'subject' => 'receipts',
                        'idSubject' => $entry->number . '/' . $entry->year
                    ]
                );
            })
            ->addColumn('Elimina', function ($entry) {
                return view(
                    'receipts.buttons.delete',
                    [
                        'idSubject' => $entry->year . '-' . $entry->number
                    ]
                );
            })
            ->rawColumns(['Info', 'Stampa', 'Modifica', 'Elimina'])
            ->make(true);
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
        $validator = new ReceiptDataValidator();

        if (!$validator->checkYear($year)) {
            return response()->json(
                [
                    'draw' => 0,
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                    'error' => $validator->getReturnMessage()
                ]
            );
        }

        if (!$validator->checkPaymentMethod($type)) {
            return response()->json(
                [
                    'draw' => 0,
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                    'error' => $validator->getReturnMessage()
                ]
            );
        }

        return $this->getData($year, $type);
    }
}
