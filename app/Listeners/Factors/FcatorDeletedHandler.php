<?php

namespace App\Listeners\Factors;

use App\Events\Factors\FactorDeleted;
use App\Events\WalletAmountChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class FcatorDeletedHandler
{
    
    /**
     * Handle the event.
     *
     * @param  FactorDeleted  $event
     * @return void
     */
    public function handle(FactorDeleted $event)
    {
        $old_value = $event->old_value;
        $type = $old_value->type;
        $old_value = $old_value->wallet;

        foreach($old_value->payments as $payment)
        {
            if($payment->is_done == true)
            {
                $wallet_amount = $old_value->wallet->amount;
                if($type == 'input')
                {
                    $old_value->wallet->update([
                        'amount' => $wallet_amount - $payment->price,
                    ]);
                    event(new WalletAmountChanged($old_value, $old_value->wallet, $payment->price,'input','پرداخت فاکتور حذف شد'));

                }
                if($type == 'output')
                {
                    $old_value->wallet->update([
                        'amount' => $wallet_amount + $payment->price,
                    ]);
                    event(new WalletAmountChanged($old_value, $old_value->wallet, $payment->price,'output','پرداخت فاکتور حذف شد'));

                }
            }
        }
    }
}
