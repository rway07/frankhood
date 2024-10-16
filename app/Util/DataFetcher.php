<?php

namespace App\Util;

use Exception;
use Illuminate\Support\Facades\DB as DB;

/**
 *
 */
class DataFetcher {
    /**
     * @return array
     */
    public static function getYears(): array
    {
        return DB::select(
            'select distinct year
            from customers_receipts
            order by year desc;'
        );
    }

    /**
     * @param $year
     * @return int
     */
    public static function getAvailableReceiptNumber($year): int
    {
        $number = 1;
        $maxNumber = DB::select(
            "select max(number) as number
                    from receipts
                    where year = ?;",
            [
                $year
            ]
        );

        if (count($maxNumber) > 0) {
            $number = ++$maxNumber[0]->number;
        }

        return $number;
    }

    /**
     * @param $idRate
     * @return mixed
     * @throws Exception
     */
    public static function getRateData($idRate) {
        $rateData = DB::select(
            "select year, quota
                    from rates
                    where id = {$idRate};",
        );

        if (count($rateData) == 0) {
            throw new Exception("Rate not found");
        }

        return $rateData[0];
    }
}
