<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 *
 */
class ReceiptDeleted extends ReceiptEvent
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

        if ($receiptData['paymentType'] == config('custom.cash')) {
            $this->invalidate = true;
        } else {
            $this->invalidate = false;
        }
    }
}
