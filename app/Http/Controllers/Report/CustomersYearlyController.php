<?php

namespace App\Http\Controllers\Report;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomersYearlyController extends Controller
{
    /**
     * Restituisce la vista per selezionare l'anno in cui visualizzare la lista dei soci correnti
     *
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $years = DB::select(
            "select distinct year
            from receipts
            order by year desc;"
        );

        return view('report/customersYearly/index', ['years' => $years]);
    }

    /**
     * Resituisce la lista dei soci correnti in base all'anno
     * Questa è la lista di default. La lista soci comprende tutti i soci e ogni volta ripete il gruppo familiare.
     * La lista originale era ordinata per cognome, e questa fa lo stesso.
     *
     * @param $year
     * @return \BladeView|false|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function listDataExtended($year, $late)
    {
        $endDate = $year . '-12-31';
        $list = [];
        $counter = 0;

        // Ottengo la lista dei membri
        // Mostro - nascondo i morosi
        if ($late == true) {
            $data = DB::select(
                "select distinct customers.id, first_name, last_name, alias, birth_date, death_date, revocation_date,
                    phone, mobile_phone,
                    (case when receipts.customers_id is null then 'False' else 'True' end) as head,
                    cr.year, 0 as late
                from customers
                 left outer JOIN  receipts on customers.id = receipts.customers_id
                join customers_receipts cr on customers.id = cr.customers_id
                where enrollment_year <= ?
                  and ((death_date = '') or (death_date > ?))
                  and ((revocation_date = '') or (revocation_date > ?))
                  and cr.year = ?
                union all
                select id, first_name, last_name, alias, birth_date, death_date, revocation_date,
                       phone, mobile_phone, 0,  0, 'True' as late
                from customers
                where id not in (
                    select customers_id
                    from customers_receipts
                    where year = ?
                )  and enrollment_year <= ?
                  and ((death_date = '') or (death_date > ?))
                  and ((revocation_date = '') or (revocation_date > ?))
                order by late asc, last_name asc",
                [
                    $year,
                    $endDate,
                    $endDate,
                    $year,
                    $year,
                    $year,
                    $endDate,
                    $endDate
                ]
            );
        } else {
            $data = DB::select(
                "select distinct customers.id, first_name, last_name, alias, birth_date, death_date, revocation_date,
                    phone, mobile_phone,
                    (case when receipts.customers_id is null then 'False' else 'True' end) as head,
                    cr.year, 0 as late
                from customers
                 left outer JOIN  receipts on customers.id = receipts.customers_id
                join customers_receipts cr on customers.id = cr.customers_id
                where enrollment_year <= ?
                  and ((death_date = '') or (death_date > ?))
                  and ((revocation_date = '') or (revocation_date > ?))
                  and cr.year = ?
                union all
                select id, first_name, last_name, alias, birth_date, death_date, revocation_date,
                       phone, mobile_phone, 0, 0, 0 as late
                from customers
                where id not in (
                    select customers_id
                    from customers_receipts
                    where year = ?
                )  and enrollment_year <= ?
                  and ((death_date = '') or (death_date > ?))
                  and ((revocation_date = '') or (revocation_date > ?))
                order by last_name asc",
                [
                    $year,
                    $endDate,
                    $endDate,
                    $year,
                    $year,
                    $year,
                    $endDate,
                    $endDate
                ]
            );
        }

        // for each customer
        foreach ($data as $i => $d) {
            // get the last receipt for the current customer
            $receipt = DB::select(
                "SELECT year, number
                FROM customers_receipts
                WHERE customers_id = ? AND year =
                  (SELECT max(year)
                  FROM customers_receipts
                  WHERE customers_id = ? and year <= ?);",
                [
                    $d->id,
                    $d->id,
                    $year
                ]
            );

            // If there is a receipt
            if (!empty($receipt)) {
                // Obtain the group for the current customer
                $group = DB::select(
                    "SELECT customers.id AS id, (first_name || ' ' || last_name) AS name,
                        alias, birth_date
                    FROM customers_receipts
                    JOIN customers ON customers_id = customers.id
                    WHERE year = ? AND number = ?
                    and enrollment_year <= ?
                    and ((death_date = '') or (death_date > ?))
                    and ((revocation_date = '') or (revocation_date > ?))
                    order by birth_date asc, gender desc",
                    [
                        $receipt[0]->year,
                        $receipt[0]->number,
                        $year,
                        $endDate,
                        $endDate
                    ]
                );
            }
            array_push($list, ['main' => $d, 'group' => $group]);
        }

        return view(
            'report/customersYearly/list',
            [
                'years' => $year,
                'data' => $list,
                'counter' => count($data),
                'extended' => true
            ]
        );
    }
}
