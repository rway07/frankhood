<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use DB;

class StatisticsController extends Controller
{
    /**
     * @return \BladeView|false|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index()
    {
        return view('report.statistics.index');
    }

    /**
     * @return array
     */
    public function listOldestCustomers()
    {
        $data = DB::select(
            "select first_name, last_name, birth_date
            from customers
            where death_date = '' and revocation_date = ''
            order by birth_date asc
            limit 5"
        );

        return $data;
    }

    /**
     * @param $beginYear
     * @param $endYear
     * @return array
     */
    public function listDeceasedOverTime($beginYear, $endYear)
    {
        $beginDate = $beginYear . '-01-01';
        $endDate = $endYear . '-12-31';

        $data = DB::select(
            "select strftime('%Y', death_date) as year,
                count(case when death_date between ? and ? then 1 end) as total
            from customers c
            group by strftime('%Y', death_date)
            having death_date > ?
            order by year desc;",
            [
                $beginDate,
                $endDate,
                $beginDate
            ]
        );

        return $data;
    }
}
