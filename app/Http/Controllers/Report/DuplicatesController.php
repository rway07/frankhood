<?php
declare(strict_types=1);

namespace App\Http\Controllers\Report;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB as DB;
use Illuminate\View\View as View;

/**
 *
 */
class DuplicatesController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        $list = [];

        $years = DB::select(
            'select distinct year
            from receipts
            where year > 2015
            order by year desc;'
        );

        foreach ($years as $y) {
            $dup = DB::select(
                'select customers_id, alias, count(*) as count
                from customers_receipts cr
                join customers c on cr.customers_id = c.id
                where year = ?
                group by customers_id
                having count > 1',
                [
                    $y->year
                ]
            );

            if (count($dup) > 0) {
                $list[] = ['year' => $y->year, 'dup' => $dup];
            }
        }

        return view('report/duplicates/index', ['list' => $list]);
    }
}
