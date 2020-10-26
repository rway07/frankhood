<?php

namespace App\Http\Controllers\Report;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DeceasedController extends Controller
{
    /**
     * Restituisce i dati per la vista riguardante i soci deceduti
     *
     * @return mixed
     */
    public function index()
    {
        $years = DB::select(
            "select distinct strftime('%Y', death_date) as year
            from customers
            where death_date != ''
            order by death_date desc;"
        );

        return view('report/deceased/index', ['years' => $years]);
    }

    /**
     * @param $year
     * @return array
     */
    public function listData($year)
    {
        $beginDate = $year . '-01-01';
        $endDate = $year . '-12-31';

        $deceasedCustomers = DB::select(
            "select c.id, first_name, last_name, birth_date, death_date, enrollment_year,
                   (case when cost is not null then cost else 0 end) as cost
            from customers c
            left outer join funerals_cost_exceptions fce on c.id = fce.customer_id
            where death_date between ? and ?
            order by death_date asc;",
            [
                $beginDate,
                $endDate
            ]
        );

        $numPeople = DB::select(
            'select count(case when death_date between ? and ? then 1 end) as total
            from customers c;',
            [
                $beginDate,
                $endDate
            ]
        );

        $funeralCost = DB::select(
            'select funeral_cost
            from rates
            where year = ?;',
            [
                $year
            ]
        );

        $exceptions = DB::select(
            'select
                   count(case when death_date between ? and ? then 1 end) as total,
                   sum(case when death_date between ? and ? then cost else 0 end) as total_cost
            from funerals_cost_exceptions f
            join customers c on f.customer_id = c.id;',
            [
                $beginDate,
                $endDate,
                $beginDate,
                $endDate
            ]
        );

        $totalFuneral = $exceptions[0]->total_cost +
            (($numPeople[0]->total - $exceptions[0]->total) * $funeralCost[0]->funeral_cost);

        $view = view(
            'report/deceased/list',
            [
                'deceased' => $deceasedCustomers,
                'funeralCost' => $funeralCost[0]->funeral_cost,
                'total' => $totalFuneral,
            ]
        )->render();

        return [
                'year' => $year,
                'num_deceased' => count($deceasedCustomers),
                'view' => $view
            ];
    }
}
