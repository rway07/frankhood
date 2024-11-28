<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 *
 */
class ReceiptUpdated extends ReceiptEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public bool $invalidate;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($receiptData)
    {
        parent::__construct($receiptData);

        $this->invalidate =
            $receiptData['paymentTypeChanged'] || ($receiptData['paymentType'] == config('custom.cash'));
    }
}
