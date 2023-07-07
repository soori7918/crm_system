<?php

namespace App\Events\Factors;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Factor;


class FactorHistoryLog
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $factor;
    public $factor_items;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Factor $factor,$factor_items)
    {
        $this->factor = $factor ;
        $this->factor_items = $factor_items ;
    }

   
}
