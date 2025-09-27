<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View as View;
use Illuminate\Support\Facades\DB as DB;

/**
 *
 */
class PrioriController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        return view('report.priori.index');
    }

    /**
     * @return JsonResponse
     */
    public function getPriori(): JsonResponse
    {
        $data = DB::select(
            'select id, first_name, last_name, election_year, votes, total_votes
                    from customers
                    left join priori on customers.id = priori.customer_id
                    where priorato = true
                    order by election_year desc'
        );

        $view = view(
            'report.priori.data',
            [
                'priori' => $data,
            ]
        )->render();

        return response()->json([
            'data' => [
                'data' => $data,
                'view' => $view
            ]
        ]);
    }
}
