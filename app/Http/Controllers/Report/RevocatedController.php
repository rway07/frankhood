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
class RevocatedController extends Controller
{
    /**
     * Restituisce la vista per la selezione dell'anno in cui ricercare i soci revocati
     *
     * @return View
     */
    public function index(): View
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
     * Restituisce la vista con la lista dei soci revocati
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

        return response()->json(
            [
                'data' => [
                    'year' => $year,
                    'num_revocated' => count($revocated),
                    'view' => $view
                ]
            ]
        );
    }
}
