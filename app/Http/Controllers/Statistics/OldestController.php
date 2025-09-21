<?php

namespace App\Http\Controllers\Statistics;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB as DB;
use Illuminate\View\View as View;

/**
 *
 */
class OldestController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        return view(
            'statistics.index',
            [
                'title' => 'SOCI PIU\' ANZIANI',
                'section' => 'oldest'
            ]
        );
    }

    /**
     * @return JsonResponse
     */
    public function data(): JsonResponse
    {
        $currentYear = date('Y');

        $data = DB::select(
            "select row_number() over (order by birth_date) as row_number,
                    first_name, last_name, birth_date,
                    ? - strftime('%Y', birth_date) as age
            from customers
            where death_date = '' and revocation_date = ''
            order by birth_date
            limit 10",
            [
                $currentYear
            ]
        );

        $view = view(
            'statistics.oldest',
            [
                'data' => $data,
            ]
        )->render();

        return response()->json([
            'data' => [
                'view' => $view
            ]
        ]);
    }
}
