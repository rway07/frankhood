<?php
declare(strict_types=1);

namespace App\Http\Controllers\Statistics;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View as View;
use Illuminate\Support\Facades\DB as DB;
use DateTime;

/**
 *
 */
class PeriodsController extends Controller
{
    public function index(): View
    {
        return view(
            'statistics.index-period',
            [
                'title' => 'DATI RICEVUTE PER PERIODO NEGLI ANNI',
                'section' => 'periods'
            ]
        );
    }

    /**
     * @param $date
     * @return JsonResponse
     */
    public function data($date): JsonResponse
    {
        $cutoffDate = new DateTime($date);
        $monthAndDay = $cutoffDate->format('m-d');

        $data = DB::select(
            "select year,
                   sum(num_people) as num_total,
                   count(number) as num_receipts,
                   sum(case when payment_type_id = 1 then num_people else 0 end) as num_cash,
                   sum(case when payment_type_id = 2 then num_people else 0 end) as num_bank,
                   count(case when payment_type_id = 1 then 1 end) as num_cash_receipts,
                   count(case when payment_type_id = 2 then 1 end) as num_bank_receipts,
                   sum(case when payment_type_id = 1 then total else 0 end) as total_cash,
                   sum(case when payment_type_id = 2 then total else 0 end) as total_bank,
                   sum(total) as total
            from receipts
             join payment_types on receipts.payment_type_id = payment_types.id
            where year > 2015 and strftime('%m-%d', date) between '01-01' and ?
            group by year
            ",
            [
                $monthAndDay
            ]
        );

        $view = view(
            'statistics.periods',
            [
                'data' => $data,
            ]
        )->render();

        return response()->json(
            [
                'data' => [
                    'data' => $data,
                    'view' => $view,
                ]
            ]
        );
    }
}
