<?php

namespace App\Util;

use Exception;
use Illuminate\Support\Facades\DB as DB;

/**
 *
 */
class DataFetcher
{
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
    public static function getRateData($idRate)
    {
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

    /**
     * @param $date
     * @return float
     */
    public static function getDeliveryCashTotal($date): float
    {
        // TODO Aggiungere offerte e spese
        $beginDate = date('Y', strtotime($date)) . '-01-01';
        $receiptsTotal = DB::select(
            'select sum(total) as total
            from receipts
            where receipts.payment_type_id = 1
                and date between ? and ?;',
            [
                $beginDate,
                $date
            ]
        );

        $deliveriesTotal = DB::select(
            'select sum(amount) as total
            from deliveries
            where date between ? and ?;',
            [
                $beginDate,
                $date
            ]
        );

        $receiptsTotal = (count($receiptsTotal) > 0) ? $receiptsTotal[0]->total : 0;
        $deliveriesTotal = (count($deliveriesTotal) > 0) ? $deliveriesTotal[0]->total : 0;

        return ($receiptsTotal - $deliveriesTotal);
    }

    /**
     * @return string
     */
    public static function getLastDeliveryDate(): string
    {
        $lastDate = DB::select(
            'select date
            from deliveries
            order by date desc
            limit 1'
        );

        return (count($lastDate) > 0) ? $lastDate[0]->date : '';
    }

    /**
     * @param $year
     * @return array
     */
    public static function getDeliveriesData($year): array
    {
        $beginDate = $year . '-01-01';
        $endDate = $year . '-12-31';

        return DB::select(
            'select *
            from deliveries
            where date between ? and ?',
            [
                $beginDate,
                $endDate
            ]
        );
    }

    /**
     * @param $year
     * @return int
     */
    public static function getDeliveriesTotalAmount($year): int
    {
        $beginDate = $year . '-01-01';
        $endDate = $year . '-12-31';

        $totalAmount = DB::select(
            'select sum(amount) as total_amount
            from deliveries
            where date between ? and ?;',
            [
                $beginDate,
                $endDate
            ]
        );

        return ($totalAmount[0]->total_amount != null) ? $totalAmount[0]->total_amount : 0;
    }
}
