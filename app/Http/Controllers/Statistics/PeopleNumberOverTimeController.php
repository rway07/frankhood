<?php

namespace App\Http\Controllers\Statistics;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB as DB;
use Illuminate\View\View as View;

/**
 *
 */
class PeopleNumberOverTimeController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        return view(
            'statistics.index',
            [
                'title' => 'NUMERO SOCI DECEDUTI, REVOCATI E NUOVI NEL CORSO DEGLI ANNI',
                'section' => 'peoplenumber'
            ]
        );
    }


    /**
     * @return JsonResponse
     */
    public function data(): JsonResponse
    {
        $beginYear = 2015;
        $beginDate = '2016-01-01';

        $data = DB::select(
            "
                with all_years as (
                    select strftime('%Y', death_date) as year,
                           count(*) as deceased_number,
                           0 as enrolled_number,
                           0 as revocated_number
                    from customers
                    where death_date >= ?
                    group by strftime('%Y', death_date)

                    union all

                    select enrollment_year as year,
                           0 as deceased_number,
                           count(*) as enrolled_number,
                           0 as revocated_number
                    from customers
                    where enrollment_year > ?
                    group by enrollment_year

                    union all

                    select strftime('%Y', customers.revocation_date) as year,
                           0 as deceased_number,
                           0 as enrolled_number,
                           count(*) as revocated_number
                    from customers
                    where revocation_date != '' and revocation_date >= ?
                    group by year
                )
                select year,
                       sum(deceased_number) as deceased_number,
                       sum(enrolled_number) as enrolled_number,
                       sum(revocated_number) as revocated_number
                from all_years
                group by year
                order by year desc;",
            [
                $beginDate,
                $beginYear,
                $beginDate
            ]
        );

        $view = view(
            'statistics.peoplenumber',
            [
                'data' => $data
            ]
        )->render();

        return response()->json([
            'data' => [
                'view' => $view,
            ]
        ]);
    }
}
