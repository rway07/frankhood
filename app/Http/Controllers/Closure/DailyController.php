<?php

namespace App\Http\Controllers\Closure;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

class DailyController extends Controller
{
    /**
     * Resituisce la vista per la selezione dell'anno in cui effettuare le chiusure giornaliere.
     *
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $years = DB::select(
            'select distinct year
            from customers_receipts
            order by year desc;'
        );

        return view('closure/daily/index', ['years' => $years]);
    }

    /**
     * Restituisce la vista con le chiusure giornaliere.
     *
     * @param Request $request
     *
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function listData($year)
    {
        $beginDate = $year . '-01-01';
        $endDate = $year . '-12-31';

        $data = DB::select(
            'select date,
                   sum(num_people) as num_total,
                   sum(case when payment_type_id = 1 then num_people else 0 end) as num_cash,
                   sum(case when payment_type_id = 2 then num_people else 0 end) as num_bank,
                   sum(case when payment_type_id = 1 then total else 0 end) as total_cash,
                   sum(case when payment_type_id = 2 then total else 0 end) as total_bank,
                   sum(total) as total
            from receipts
            join payment_types on receipts.payment_type_id = payment_types.id
            where date between ? and ?
              and receipts.year = ?
            group by date;',
            [
                $beginDate,
                $endDate,
                $year,
            ]
        );

        $final = DB::select(
            'select sum(num_people) as people_total, sum(total) as total
            from receipts
            where date between ? and ?
              and year = ?;',
            [
                $beginDate,
                $endDate,
                $year,
            ]
        );

        return view(
            'closure.daily.list',
            [
                'years' => $year,
                'data' => $data,
                'final' => $final[0],
            ]
        );
    }
}
