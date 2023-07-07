<?php

namespace App\Listeners\Factors;

use App\Events\Factors\FactorCreated;
use App\Events\WalletAmountChanged;

class FcatorCreatedHandler
{
   
    /**
     * Handle the event.
     *
     * @param  FactorCreated  $event
     * @return void
     */
    public function handle(FactorCreated $event)
    {
        $new_value = $event->new_value;
        $type = $new_value->type;
        foreach($new_value->payments as $payment)
        {
            $wallet_amount = $new_value->wallet->amount;

            if($payment->is_done == true)
            {
                if($type == 'input')
                {
                    $new_value->wallet->update([
                        'amount' => $wallet_amount + $payment->price,
                    ]);
                    event(new WalletAmountChanged($new_value, $new_value->wallet, $payment->price,'input','پرداخت فاکتور جدید ثبت شد'));
                }
                if($type == 'output')
                {
                    $new_value->wallet->update([
                        'amount' => $wallet_amount - $payment->price,
                    ]);
                    event(new WalletAmountChanged($new_value, $new_value->wallet, $payment->price,'output','پرداخت فاکتور جدید ثبت شد'));
                }
            }
        }
   
    }
}
