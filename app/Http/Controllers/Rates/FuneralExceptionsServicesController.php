<?php

namespace App\Http\Controllers\Rates;

use App\Http\Controllers\Controller;
use App\Util\DataValidator;
use Illuminate\Http\JsonResponse as JsonResponse;
use Illuminate\Support\Facades\DB as DB;

/**
 *
 */
class FuneralExceptionsServicesController extends Controller
{
    /**
     * @param $year
     * @return JsonResponse
     */
    public function deadCustomers($year): JsonResponse
    {
        $validator = new DataValidator();

        if (!$validator->checkYear($year)) {
            return response()->json(
                [
                    'error' => ['message' => $validator->getReturnMessage()]
                ]
            );
        }

        $beginDate = $year . '-01-01';
        $endDate = $year . '-12-31';

        $customers =  DB::select(
            "select id, first_name, last_name, death_date
                from customers
                where death_date between ? and ?;",
            [
                $beginDate,
                $endDate
            ]
        );

        return response()->json(
            [
                'data' => ['customers' => $customers]
            ]
        );
    }
}
