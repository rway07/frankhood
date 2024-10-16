<?php

namespace App\Http\Controllers\Closure;

use App\Http\Controllers\Controller;
use App\Util\DataFetcher;
use App\Util\DataValidator as Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * Class Yearly Controller
 *
 * Resituisce vista e dati della chiusura annuale
 */
class YearlyController extends Controller
{
    /**
     * Restituisce la vista principale con la selezione dell'anno per la chiusura.
     *
     * @return View
     */
    public function index(): View
    {
        // Ottengo gli anni in cui sono state effettuate delle ricevute
        $years = DataFetcher::getYears();

        return view(
            'closure.yearly.index',
            [
                'years' => $years,
            ]
        );
    }

    /**
     * Ottengo i dati della chiusura e restituisco la vista con i dati in tabella
     *
     * @param $year
     * @return JsonResponse
     */
    public function listData($year): JsonResponse
    {
        // FIXME Funzione troppo lunga?
        $validator = new Validator();

        // Controllo anno
        if (!$validator->checkYear($year)) {
            return response()->json(
                [
                    "error" => ["message" => $validator->getReturnMessage()]
                ]
            );
        }

        $beginDate = $year . '-01-01';
        $middleDate = $year . '-09-08';
        $endDate = $year . '-12-31';

        // Ci saranno un bel po' di query
        // Let's goooooooooooo
        // Numero di persone e totali distinguendo tra totale, regolari e con quote alternative
        $customers = DB::select(
            "select sum(num_people) as numPeople,
                   sum(total) as amount,
                   sum(case when custom_quotas = false then num_people else 0 end) as numRegular,
                   sum(case when custom_quotas = false then total else 0 end) as amountRegular,
                   sum(case when custom_quotas = true then num_people else 0 end) as numAlt,
                   sum(case when custom_quotas = true then total else 0 end) as amountAlt
                from receipts
                where year = ?",
            [
                $year
            ]
        );

        // Quota e costo del funerale per l'anno selezionato
        $rates = DB::select(
            'select quota, funeral_cost
                from rates
                where year = ?;',
            [
                $year
            ]
        );

        // Nuovi soci per l'anno selezionato
        $newCustomers = DB::select(
            "select count(customers_receipts.id) as numPeople,
                    sum(quota) as amount,
                    count(case when custom_quotas = false then customers_receipts.id end) as numRegular,
                    sum(case when custom_quotas = false then quota end) as amountRegular,
                    count(case when custom_quotas = true then customers_receipts.id end) as numAlt,
                    sum(case when custom_quotas = true then quota end) as amountAlt
                from receipts
                join customers_receipts on customers_receipts.year = receipts.year
                    and customers_receipts.number = receipts.number
                    and receipts.year = ?
                join customers c on customers_receipts.customers_id = c.id
                where enrollment_year = ?;",
            [
                $year,
                $year
            ]
        );

        // Soci morosi per l'anno selezionato
        $lateCustomers = DB::select(
            "select count(id) as numPeople
                from customers
                where id not in (
                    select customers_id
                    from customers_receipts
                    where year = ?
                ) and enrollment_year <= ?
                and ((death_date = '') or (death_date > ?))
                and ((revocation_date = '') or (revocation_date > ?));",
            [
                $year,
                $year,
                $endDate,
                $endDate,
            ]
        );

        // Soci deceduti per l'anno selezionato prima della festa patronale
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

        // Soci deceduti per l'anno selezionato dopo la festa patronale
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

        // Soci revocati per l'anno selezionato
        $revocatedCustomers = DB::select(
            'select count(id) as numPeople
                from  customers
                where revocation_date between ? and ?;',
            [
                $beginDate,
                $endDate,
            ]
        );

        // Spese per l'anno selezionato
        $expenses = DB::select(
            'select sum(amount) as total
                from expenses
                where date between ? and ?;',
            [
                $beginDate,
                $endDate,
            ]
        );

        // Offerte per l'anno selezionato
        $offers = DB::select(
            'select sum(amount) as total
                from offers
                where date between ? and ?;',
            [
                $beginDate,
                $endDate,
            ]
        );

        // FIXME Ma non ho già ottenuto questo dato????
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

        $view =  view(
            'closure.yearly.list',
            [
                'year' => $year,
                'rates' => $rates[0],
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
                'expenses' => ($expenses[0]->total != null) ? $expenses[0]->total : 0,
                'offers' => ($offers[0]->total != null) ? $offers[0]->total : 0
            ]
        )->render();

        return response()->json(
            [
                'data' => [
                    'view' => $view,
                    'year' => $year
                ]
            ]
        );
    }
}
