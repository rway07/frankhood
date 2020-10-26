<?php

namespace App\Http\Controllers\Closure;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

class YearlyController extends Controller
{
    /**
     * Restituisce la vista di selezione anno per la chiusura annuale.
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

        return view(
            'closure.yearly.index',
            [
                'years' => $years,
            ]
        );
    }

    /**
     * Restituisce la vista principale della chiusura annuale.
     *
     * @param Request $request
     *
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function listData($year)
    {
        $beginDate = $year . '-01-01';
        $middleDate = $year . '-09-08';
        $endDate = $year . '-12-31';

        // Ci saranno un bel po' di query
        $customers = DB::select(
            'select sum(num_people) as numPeople,
                   sum(total) as amount
            from receipts
            where year = ?',
            [
                $year,
            ]
        );

        $newCustomers = DB::select(
            'select count(customers_receipts.id) as numPeople, sum(quota) as amount
            from receipts
            join customers_receipts on customers_receipts.year = receipts.year
                and customers_receipts.number = receipts.number
                and receipts.year = ?
            join customers c on customers_receipts.customers_id = c.id
            where enrollment_year = ?;',
            [
                $year,
                $year,
            ]
        );

        $lateCustomers = DB::select(
            "select count(id) as numPeople
            from customers
            where id not in (
                select customers_id
                from customers_receipts
                where year = ?
            )  and enrollment_year <= ?
            and ((death_date = '') or (death_date > ?))
            and ((revocation_date = '') or (revocation_date > ?));",
            [
                $year,
                $year,
                $endDate,
                $endDate,
            ]
        );

        $deceasedCustomers = DB::select(
            'select count(case when death_date between ? and ? then 1 end) as total,
                   count(case when (death_date between ? and ?) then 1 end) as pre,
                   count(case when death_date between ? and ? then 1 end) as post
            from customers c;',
            [
                $beginDate,
                $endDate,
                $beginDate,
                $middleDate,
                $middleDate,
                $endDate,
            ]
        );

        $postDeceased = DB::select(
            'select count(case when death_date between ? and ? then 1 end) as total
            from customers c
            join customers_receipts cr on c.id = cr.customers_id
            where year = ?;',
            [
                $beginDate,
                $endDate,
                $year,
            ]
        );

        $revocatedCustomers = DB::select(
            'select count(id) as numPeople
            from  customers
            where revocation_date between ? and ?;',
            [
                $beginDate,
                $endDate,
            ]
        );

        $expenses = DB::select(
            'select sum(amount) as total
            from expenses
            where date between ? and ?;',
            [
                $beginDate,
                $endDate,
            ]
        );

        $offers = DB::select(
            'select sum(amount) as total
            from offers
            where date between ? and ?;',
            [
                $beginDate,
                $endDate,
            ]
        );

        $funeralCost = DB::select(
            'select funeral_cost
            from rates
            where year = ?;',
            [
                $year,
            ]
        );

        $exceptions = DB::select(
            'select count(case when (death_date between ? and ?) then 1 end) as pre,
                  count(case when death_date between ? and ? then 1 end) as post,
                   count(case when death_date between ? and ? then 1 end) as total,
                   sum(case when (death_date between ? and ?) then cost else 0 end) as pre_cost,
                  sum(case when death_date between ? and ? then cost else 0 end) as post_cost,
                   sum(case when death_date between ? and ? then cost else 0 end) as total_cost
            from funerals_cost_exceptions f
            join customers c on f.customer_id = c.id;',
            [
                $beginDate,
                $middleDate,
                $middleDate,
                $endDate,
                $beginDate,
                $endDate,
                $beginDate,
                $middleDate,
                $middleDate,
                $endDate,
                $beginDate,
                $endDate,
            ]
        );

        $preDeceasedNumber = $deceasedCustomers[0]->total - $postDeceased[0]->total;
        $totalFuneral = $exceptions[0]->total_cost +
            (($deceasedCustomers[0]->total - $exceptions[0]->total) * $funeralCost[0]->funeral_cost);

        $preTotalFuneral = $exceptions[0]->pre_cost +
            (($preDeceasedNumber - $exceptions[0]->pre) * $funeralCost[0]->funeral_cost);

        $postTotalFuneral = $exceptions[0]->post_cost +
            (($postDeceased[0]->total - $exceptions[0]->post) * $funeralCost[0]->funeral_cost);

        return view(
            'closure.yearly.list',
            [
                'year' => $year,
                'customers' => $customers[0],
                'newCustomers' => $newCustomers[0],
                'lateCustomers' => $lateCustomers[0],
                'deceasedCustomers' => $deceasedCustomers[0],
                'preDeceasedNumber' => $preDeceasedNumber,
                'postDeceasedNumber' => $postDeceased[0]->total,
                'exceptions' => $exceptions[0],
                'totalFuneral' => $totalFuneral,
                'preTotalFuneral' => $preTotalFuneral,
                'postTotalFuneral' => $postTotalFuneral,
                'revocatedCustomers' => $revocatedCustomers[0],
                'expenses' => (null != $expenses[0]->total) ? $expenses[0]->total : 0,
                'offers' => (null != $offers[0]->total) ? $offers[0]->total : 0,
            ]
        );
    }
}
