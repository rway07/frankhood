<?php

namespace App\Http\Controllers\Deliveries;

use App\Http\Controllers\Controller;
use App\Util\DataFetcher;
use App\Util\DataValidator;
use Illuminate\Http\JsonResponse as JsonResponse;

/**
 *
 */
class DeliveriesDataController extends Controller
{
    /**
     * @param $limitDate
     * @return JsonResponse
     */
    public function getTotal($limitDate): JsonResponse
    {
        $validator = new DataValidator();

        if (!$validator->checkDate($limitDate)) {
            return response()->json(
                ['error' => ['message' => $validator->getReturnMessage()]]
            );
        }

        $total = DataFetcher::getDeliveryCashTotal($limitDate);

        return response()->json(
            [
                'data' => [
                    'total' => $total
                ]
            ]
        );
    }
}
