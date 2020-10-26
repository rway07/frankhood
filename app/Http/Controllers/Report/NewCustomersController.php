<?php

namespace App\Http\Controllers\Report;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NewCustomersController extends Controller
{
    /**
     * Restituisce la vista per la selezione dell'anno in cui ricercare i nuovi soci iscritti
     *
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $years = DB::select(
            "select distinct enrollment_year as year
            from customers
            order by enrollment_year desc;"
        );

        return view('report/new/index', ['years' => $years]);
    }

    /**
     * @param $year
     * @return array
     */
    public function listData($year)
    {
        $newCustomers = DB::select(
            "select first_name, last_name, birth_date, quota, cr.number
            from customers c
            join customers_receipts cr on c.id = cr.customers_id
            where enrollment_year = ? and cr.year = ?
            order by birth_date asc;",
            [
                $year,
                $year
            ]
        );

        $view =  view(
            'report/new/list',
            [
                'new_customers' => $newCustomers,
            ]
        )->render();

        return [
            'year' => $year,
            'num_new' => count($newCustomers),
            'view' => $view
        ];
    }
}
