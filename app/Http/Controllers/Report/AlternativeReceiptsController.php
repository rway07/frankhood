<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Util\DataValidator;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB as DB;

/**
 *
 */
class AlternativeReceiptsController extends Controller
{
    /**
     * Restituisce la vista con la lista delle ricevute con quote alternative
     *
     * @return View
     */
    public function index(): View
    {
        $years = DB::select(
            "select distinct year
            from receipts
            where custom_quotas = true
            order by year desc;"
        );

        return view(
            'report/common',
            [
                'title' => 'LISTA RICEVUTE CON QUOTE ALTERNATIVE PER L\'ANNO',
                'script_prefix' => 'alternatives',
                'years' => $years
            ]
        );
    }

    /**
     * Restituisce la vista con i dati delle ricevute per anno
     *
     * @param $year
     * @return JsonResponse
     */
    public function listData($year): JsonResponse
    {
        $validator = new DataValidator();
        if (!$validator->checkYear($year)) {
            return response()->json(
                ['error' => ['message' => $validator->getReturnMessage()]]
            );
        }

        $data = DB::select(
            'select number, date, total, c.last_name, num_people, c.first_name, rt.quota
                from receipts r
                join customers c on r.customers_id = c.id
                join rates rt on r.rates_id = rt.id
                where custom_quotas = true and r.year = ?;',
            [
                $year
            ]
        );

        $view =  view(
            'report/alternatives/list',
            [
                'data' => $data
            ]
        )->render();


        return response()->json(
            [
                'data' => [
                    'year' => $year,
                    'number' => count($data),
                    'view' => $view
                ]
            ]
        );
    }
}
