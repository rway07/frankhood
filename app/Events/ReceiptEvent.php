<?php
declare(strict_types=1);

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 *
 */
class ReceiptEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public $year;
    public $number;
    public $date;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($year, $number, $date)
    {
        $this->year = $year;
        $this->number = $number;
        $this->date = $date;
    }
}
