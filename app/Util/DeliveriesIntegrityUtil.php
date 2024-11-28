<?php
declare(strict_types=1);

namespace App\Util;

use Exception;
use Illuminate\Support\Facades\DB as DB;
use Illuminate\Support\Facades\Log;

/**
 *
 */
class DeliveriesIntegrityUtil
{
    /**
     * @param $deliveryData
     * @return void
     * @throws Exception
     */
    public static function integrityCheck($deliveryData): void
    {
        // TODO rework exceptions
        if ($deliveryData['amount'] == 0) {
            throw new Exception('La cifra della consegna non può essere zero.');
        }

        $lastDate = DataFetcher::getLastDeliveryDate();

        if (strtotime($lastDate) > strtotime($deliveryData['date'])) {
            throw new Exception(
                'La data della consegna inserita non può essere precedente a l\'ultima salvata '
            );
        }

        $total = DataFetcher::getDeliveryCashTotal($deliveryData['date']);

        if ($total != $deliveryData['total']) {
            throw new Exception(
                'Il totale reale non corrisponde con quello ricevuto.'
            );
        }

        if ($deliveryData['amount'] > $total) {
            throw new Exception(
                'La cifra della consegna non può essere maggiore del totale in cassa'
            );
        }

        $remaining = $total - $deliveryData['amount'];
        if ($remaining != $deliveryData['remaining']) {
            throw new Exception();
        }
    }

    /**
     * @param $receiptDate
     * @return void
     */
    public static function invalidateDelivery($receiptDate): void
    {
        $lastDeliveryData = DB::select(
            'select date
                from deliveries
                order by date desc
                limit 1;',
        );

        if (count($lastDeliveryData) == 0) {
            Log::debug('No delivery data, nothing to check');
            return;
        }

        if ($receiptDate < $lastDeliveryData[0]->date) {
            $rows = DB::delete(
                'delete from deliveries
                where date between ? and ?;',
                [
                    $receiptDate,
                    $lastDeliveryData[0]->date
                ]
            );

            if ($rows == 0) {
                Log::debug('No lines affected during deliveries invalidate check');
            }
        }
    }
}
