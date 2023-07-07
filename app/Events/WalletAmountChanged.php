<?php

namespace App\Events;

use App\Models\Factor;
use App\Models\Wallet;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WalletAmountChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $factor_id;
    public $wallet_id;
    public $price;
    public $type;
    public $description;
   
    public function __construct(Factor $factor, Wallet $wallet, $price, $type, $description)
    {
        $this->factor_id = $factor->id;
        $this->wallet_id = $wallet->id;
        $this->price = $price;
        $this->type = $type;
        $this->description = $description;


    }

    
}
