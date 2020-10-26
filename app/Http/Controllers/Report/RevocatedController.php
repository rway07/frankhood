<?php

namespace App\Http\Controllers\Report;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RevocatedController extends Controller
{
    /**
     * Restituisce la vista per la selezione dell'anno in cui ricercare i soci revocati
     *
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $years = DB::select(
            "select distinct strftime('%Y', revocation_date) as year
            from customers
            where revocation_date != ''
            order by revocation_date desc;"
        );

        return view('report/revocated/index', ['years' => $years]);
    }

    /**
     * @param $year
     * @return array
     */
    public function listData($year)
    {
        $beginDate = $year . '-01-01';
        $endDate = $year . '-12-31';

        $revocated = DB::select(
            "select id, first_name, last_name, birth_date
            from customers
            where revocation_date BETWEEN ? and ?
            order by last_name, first_name asc;",
            [
                $beginDate,
                $endDate
            ]
        );

        $view = view(
            'report/revocated/list',
            [
                'revocated' => $revocated,
            ]
        )->render();

        return [
                'year' => $year,
                'num_revocated' => count($revocated),
                'view' => $view
            ];
    }
}
