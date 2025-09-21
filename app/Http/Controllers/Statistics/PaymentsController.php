<?php

namespace App\Http\Controllers\Statistics;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View as View;
use Illuminate\Support\Facades\DB as DB;

/**
 *
 */
class PaymentsController extends Controller
{
    private int $beginYear = 2015;

    public function index(): View
    {
        return view(
            'statistics.index',
            [
                'title' => 'TIPOLOGIA PAGAMENTI NEL CORSO DEGLI ANNI',
                'section' => 'payments',
            ]
        );
    }

    /**
     * @return JsonResponse
     */
    public function data(): JsonResponse
    {
        $data = DB::select(
            "select year,
               sum(num_people) as people,
               sum(case when payment_type_id = 1 then num_people else 0 end) as people_cash,
               sum(case when payment_type_id = 2 then num_people else 0 end) as people_bank,
               sum(total) as total,
               sum(case when payment_type_id = 1 then total else 0 end) as total_cash,
               sum(case when payment_type_id = 2 then total else 0 end) as total_bank
        from receipts
        where year > ?
        group by year
        order by year desc;",
            [
                $this->beginYear
            ]
        );

        $view = view(
            'statistics.payments',
            [
                'data' => $data,
            ]
        )->render();

        return response()->json(
            [
                'data' => [
                    'view' => $view
                ]
            ]
        );
    }
}
