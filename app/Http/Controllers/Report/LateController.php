<?php
declare(strict_types=1);

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Util\DataFetcher;
use App\Util\DataValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB as DB;
use Illuminate\View\View as View;

/**
 *
 */
class LateController extends Controller
{
    /**
     * Restituisce la vista per la selezione dell'anno in cui effettuare la ricerca dei soci morosi
     *
     * @return View
     */
    public function index(): View
    {
        $years = DataFetcher::getYears();

        return view(
            'report/common',
            [
                'title' => 'LISTA SOCI MOROSI PER L\'ANNO',
                'script_prefix' => 'late',
                'years' => $years
            ]
        );
    }

    /**
     * Resituisce la lista dei soci morosi
     *
     * @param $year
     * @return JsonResponse
     */
    public function listData($year): JsonResponse
    {
        $validator = new DataValidator();

        if (!$validator->checkYear($year)) {
            return response()->json(
                ['error' => ['message' => $validator->getReturnMessage()]]
            );
        }

        $endDate = $year . '-12-31';

        $late = DB::select(
            "select id, first_name, last_name, alias, birth_date, enrollment_year, mobile_phone, phone
            from customers
            where id not in (
                select customers_id
                from customers_receipts
                where year = ?
            )  and enrollment_year < ?
            and ((death_date = '') or (death_date > ?))
            and ((revocation_date = '') or (revocation_date > ?))
            order by last_name;",
            [
                $year,
                $year,
                $endDate,
                $endDate
            ]
        );

        $last = [];
        foreach ($late as $l) {
            $currentLast = DB::select(
                "select max(receipts.year) as year, receipts.number, date
            from customers_receipts
            join customers on customers_receipts.customers_id = customers.id
            join receipts on receipts.year = customers_receipts.year and receipts.number = customers_receipts.number
            join rates on rates.id =  receipts.rates_id
            where customers_receipts.customers_id = ?;",
                [
                    $l->id
                ]
            );

            $last[] = ['number' => $currentLast[0]->number, 'year' => $currentLast[0]->year];
        }

        $view = view(
            'report/late/list',
            [
                'late' => $late,
                'last' => $last,
            ]
        )->render();

        return response()->json(
            ['data' => [
                'year' => $year,
                'num_late' => count($late),
                'view' => $view
            ]]
        );
    }
}
