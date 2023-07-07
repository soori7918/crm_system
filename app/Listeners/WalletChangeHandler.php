<?php

namespace App\Listeners;

use App\Events\WalletAmountChanged;
use App\Models\WalletChange;

class WalletChangeHandler
{

    public function handle(WalletAmountChanged $event)
    {
        WalletChange::create([
            'factor_id' => $event->factor_id,
            'wallet_id' => $event->wallet_id,
            'price' => $event->price,
            'type' => $event->type,
            'description' => $event->description,
        ]);
    }
}
