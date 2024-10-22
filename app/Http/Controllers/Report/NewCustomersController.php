<?php
declare(strict_types=1);

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Util\DataValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB as DB;
use Illuminate\View\View as View;

/**
 *
 */
class NewCustomersController extends Controller
{
    /**
     * Restituisce la vista per la selezione dell'anno in cui ricercare i nuovi soci iscritti
     *
     * @return View
     */
    public function index(): View
    {
        $years = DB::select(
            "select distinct enrollment_year as year
            from customers
            order by enrollment_year desc;"
        );

        return view(
            'report/common',
            [
                'title' => 'LISTA NUOVI SOCI PER L\'ANNO',
                'script_prefix' => 'new',
                'years' => $years
            ]
        );
    }

    /**
     * Restituisce la vista con la lista dei nuovi soci
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

        $newCustomers = DB::select(
            "select first_name, last_name, birth_date, quota, cr.number, phone, mobile_phone
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

        return response()->json(
            ['data' => [
                'year' => $year,
                'num_new' => count($newCustomers),
                'view' => $view
            ]]
        );
    }
}
