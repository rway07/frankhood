<?php
declare(strict_types=1);

namespace App\Http\Controllers\Closure;

use App\Http\Controllers\Controller;
use App\Util\DataFetcher;
use App\Util\DataValidator as Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View as View;

/**
 * Class DailyController
 *
 * Questa classe si occupa di restituire viste e dati per la chiusura giornaliera
 *
 */
class DailyController extends Controller
{
    /**
     *  Resituisce la vista iniziale nel quale sono disponibili gli anni in cui è possibile effetturare
     *  la chiusura
     *
     * @return View
     */
    public function index(): View
    {
        // Ottengo gli anni in cui sono state effettuate delle ricevute
        $years = DataFetcher::getYears();

        return view('closure/daily/index', ['years' => $years]);
    }

    /**
     * Ottiene i dati della chiusura in base all'anno e restituisce la vista richiesta
     *
     * @param $year
     * @return JsonResponse
     */
    public function listData($year): JsonResponse
    {
        // FIXME Funzione troppo lunga?
        $validate = new Validator();

        // Controllo anno
        if (!$validate->checkYear($year)) {
            return response()->json(
                [
                    "error" => ["message" => $validate->getReturnMessage()]
                ]
            );
        }

        // Per le query, serve avere la data iniziale e quella finale
        $beginDate = $year . '-01-01';
        $endDate = $year . '-12-31';

        // Dati delle chiusure divisi per data
        $data = DB::select(
            'select date,
                   count(year) as num_receipts,
                   sum(num_people) as num_total,
                   sum(case when payment_type_id = 1 then num_people else 0 end) as num_cash,
                   sum(case when payment_type_id = 2 then num_people else 0 end) as num_bank,
                   count(case when payment_type_id = 1 then 1 end) as num_cash_receipts,
                   count(case when payment_type_id = 2 then 1 end) as num_bank_receipts,
                   sum(case when payment_type_id = 1 then total else 0 end) as total_cash,
                   sum(case when payment_type_id = 2 then total else 0 end) as total_bank,
                   sum(total) as total
                from receipts
                join payment_types on receipts.payment_type_id = payment_types.id
                where date between ? and ? and receipts.year = ?
                group by date;',
            [
                $beginDate,
                $endDate,
                $year,
            ]
        );

        // Somma totale
        $final = DB::select(
            "select sum(num_people) as people,
                    count(year) as num_receipts,
                    sum(case when payment_type_id = 1 then num_people else 0 end) as people_cash,
                    sum(case when payment_type_id = 2 then num_people else 0 end) as people_bank,
                    count(case when payment_type_id = 1 then 1 end) as num_cash_receipts,
                    count(case when payment_type_id = 2 then 1 end) as num_bank_receipts,
                    sum(total) as total,
                    sum(case when payment_type_id = 1 then total else 0 end) as total_cash,
                    sum(case when payment_type_id = 2 then total else 0 end) as total_bank
                from receipts
                where date between ? and ? and year = ?;",
            [
                $beginDate,
                $endDate,
                $year
            ]
        );

        // Dati parziali delle offerte
        $offersData = DB::select(
            'select date, sum(amount) as total
                from offers
                where date between ? and ?
                group by date',
            [
                $beginDate,
                $endDate
            ]
        );

        // Somma finale delle offerte
        $offersFinal = DB::select(
            'select sum(amount) as total
                from offers
                where date between ? and ?',
            [
                $beginDate,
                $endDate
            ]
        );

        // Dati parziali delle spese
        $expensesData = DB::select(
            'select date, sum(amount) as total
                from expenses
                where date between ? and ?
                group by date',
            [
                $beginDate,
                $endDate
            ]
        );

        // Somma finale delle spese
        $expensesFinal = DB::select(
            'select sum(amount) as total
                from expenses
                where date between ? and ?',
            [
                $beginDate,
                $endDate
            ]
        );

        // Totale finale [ricevute] + [offerte] + [spese]
        $summary = $final[0]->total + $offersFinal[0]->total - $expensesFinal[0]->total;

        $deliveries = DataFetcher::getDeliveriesData($year);
        $deliveriesTotal = DataFetcher::getDeliveriesTotalAmount($year);

        $view = view(
            'closure.daily.list',
            [
                'years' => $year,
                'data' => $data,
                'final' => $final[0],
                'offersData' => $offersData,
                'offersFinal' => $offersFinal[0],
                'expensesData' => $expensesData,
                'expensesFinal' => $expensesFinal[0],
                'summary' => $summary,
                'deliveries' => $deliveries,
                'deliveriesTotal' => $deliveriesTotal,
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
