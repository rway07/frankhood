<?php

namespace App\Listeners;

use App\Events\ReceiptDeleted;
use App\Events\ReceiptSaved;
use App\Events\ReceiptUpdated;
use App\Util\DeliveriesIntegrityUtil;
use Illuminate\Support\Facades\Log;

/**
 *
 */
class ReceiptEventSubscriber
{
    /**
     * @param $event
     * @return void
     */
    public function handleNewReceipt($event)
    {
        Log::info('Ricevuta ' . $event->number . '/' . $event->year . ' salvata');
        DeliveriesIntegrityUtil::invalidateDelivery($event->date);
    }

    /**
     * @param $event
     * @return void
     */
    public function handleUpdatedReceipt($event)
    {
        Log::info('Ricevuta ' . $event->number . '/' . $event->year . ' aggiornata');
        DeliveriesIntegrityUtil::invalidateDelivery($event->date);
    }

    /**
     * @param $event
     * @return void
     */
    public function handleDeletedReceipt($event)
    {
        Log::info('Ricevuta ' . $event->number . '/' . $event->year . ' eliminata');
        DeliveriesIntegrityUtil::invalidateDelivery($event->date);
    }

    /**
     * @param $events
     * @return string[]
     */
    public function subscribe($events)
    {
        return [
            ReceiptSaved::class => 'handleNewReceipt',
            ReceiptUpdated::class => 'handleUpdatedReceipt',
            ReceiptDeleted::class => 'handleDeletedReceipt'
        ];
    }
}
