<?php

namespace App\Http\Controllers\Rates;

use App\Http\Controllers\Controller;

class FuneralExceptionsServicesController extends Controller
{
    /**
     * @param $year
     * @return array|null
     */
    public function deadCustomers($year)
    {
        $data = null;
        if (is_numeric($year)) {
            $beginDate = $year . '-01-01';
            $endDate = $year . '-12-31';

            $data = DB::select(
                'select id, first_name, last_name, death_date
                from customers
                where death_date between ? and ?;',
                [
                    $beginDate,
                    $endDate
                ]
            );
        }

        return $data;
    }
}
