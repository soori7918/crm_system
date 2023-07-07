<?php

namespace App\Listeners\Factors;

use App\Events\Factors\FactorUpdated;
use App\Events\WalletAmountChanged;
use Illuminate\Support\Facades\Log;

class FcatorUpdatedHandler
{
   
    /**
     * Handle the event.
     *
     * @param  FactorUpdated  $event
     * @return void
     */
    public function handle(FactorUpdated $event)
    {
        $new_value = $event->new_value;
        $old_value = $event->old_value;
        $type = $new_value->type;

        $old_wallet = $old_value->wallet;
        $new_wallet = $new_value->wallet;

        if($old_wallet->id != $new_wallet->id) {
            $old_wallet_changed_price = 0;
            foreach($old_value->payments as $payment)
            {
                if($payment->is_done == true)
                {
                    if($type == 'input')
                    {
                        $old_wallet->update([
                            'amount' => $old_wallet->amount - $payment->price,
                        ]);
                        $old_wallet_changed_price -= $payment->price;
                    }
                    if($type == 'output')
                    {
                        $old_wallet->update([
                            'amount' => $old_wallet->amount + $payment->price,
                        ]);
                        $old_wallet_changed_price += $payment->price;
                    }
                }
            }
            if($old_wallet_changed_price != 0) {
                $old_wallet_changed_price > 0 ?
                    event(new WalletAmountChanged($old_value, $old_wallet, $old_wallet_changed_price, 'input','اصلاح مبلغ صندوق بعد از تغییر صندوق فاکتور'))
                    :
                    event(new WalletAmountChanged($old_value, $old_wallet, $old_wallet_changed_price*-1, 'output','اصلاح مبلغ صندوق بعد از تغییر صندوق فاکتور'));
            }

            foreach($new_value->payments as $payment)
            {
                if($payment->is_done == true)
                {
                    if($type == 'input')
                    {
                        $new_wallet->update([
                            'amount' => $new_wallet->amount + $payment->price,
                        ]);    
                        event(new WalletAmountChanged($new_value, $new_wallet, $payment->price, 'input','ثبت پرداختی برای فاکتور'));
                    }
                    if($type == 'output')
                    {
                        $new_wallet->update([
                            'amount' => $new_wallet->amount - $payment->price,
                        ]);
                        event(new WalletAmountChanged($new_value, $new_wallet, $payment->price, 'output','ثبت پرداختی برای فاکتور'));
                    }
                }
            }


        } else {
            $deleted_payments = $old_value->payments->whereNotIn('id',$new_value->payments->pluck('id')->toArray());
            $new_payments = $new_value->payments->whereNotIn('id',$old_value->payments->pluck('id')->toArray());
            $exists_payments = $new_value->payments->whereIn('id',$old_value->payments->pluck('id')->toArray());

            Log::info('deleted_payments');
            Log::info($deleted_payments->toArray());
            Log::info('new_payments');
            Log::info($new_payments->toArray());
            Log::info('exists_payments');
            Log::info($exists_payments->toArray());
            
            foreach($deleted_payments as $payment)
            {
                if($payment->is_done == true)
                {
                    if($type == 'input')
                    {
                        $new_wallet->update([
                            'amount' => $new_wallet->amount - $payment->price,
                        ]);
                        event(new WalletAmountChanged($new_value, $new_wallet, $payment->price, 'output','حذف پرداختی از فاکتور'));
                    }
                    if($type == 'output')
                    {
                        $new_wallet->update([
                            'amount' => $new_wallet->amount + $payment->price,
                        ]);
                        event(new WalletAmountChanged($new_value, $new_wallet, $payment->price, 'input','حذف پرداختی از فاکتور'));
                    }
                }
            }
            
            foreach($new_payments as $payment)
            {
                if($payment->is_done == true)
                {
                    if($type == 'input')
                    {
                        $new_wallet->update([
                            'amount' => $new_wallet->amount + $payment->price,
                        ]);
                        event(new WalletAmountChanged($new_value, $new_wallet, $payment->price, 'input','افزودن پرداختی به فاکتور'));
                    }
                    if($type == 'output')
                    {
                        $new_wallet->update([
                            'amount' => $new_wallet->amount - $payment->price,
                        ]);
                        event(new WalletAmountChanged($new_value, $new_wallet, $payment->price, 'output','افزودن پرداختی به فاکتور'));
                    }
                }
            }
            
            foreach($exists_payments as $payment)
            {
                $new_payment = $payment;
                $old_paymnet = $old_value->payments->where('id',$payment->id)->first();
                if($old_paymnet){
                    if($new_payment->is_done == true && $old_paymnet->is_done == false) {
                        if($type == 'input')
                        {
                            $new_wallet->update([
                                'amount' => $new_wallet->amount + $new_payment->price,
                            ]);
                            event(new WalletAmountChanged($new_value, $new_wallet, $new_payment->price, 'input','پرداختی فاکتور تایید شد'));
                        }
                        if($type == 'output')
                        {
                            $new_wallet->update([
                                'amount' => $new_wallet->amount - $new_payment->price,
                            ]);
                            event(new WalletAmountChanged($new_value, $new_wallet, $new_payment->price, 'output','پرداختی فاکتور تایید شد'));
                        }
                    }
                    
                    if($new_payment->is_done == false && $old_paymnet->is_done == true) {
                        if($type == 'input')
                        {
                            $new_wallet->update([
                                'amount' => $new_wallet->amount - $old_paymnet->price,
                            ]);
                            event(new WalletAmountChanged($new_value, $new_wallet, $old_paymnet->price, 'output','پرداختی فاکتور لغو شد'));
                        }
                        if($type == 'output')
                        {
                            $new_wallet->update([
                                'amount' => $new_wallet->amount + $old_paymnet->price,
                            ]);
                            event(new WalletAmountChanged($new_value, $new_wallet, $old_paymnet->price, 'input','پرداختی فاکتور لغو شد'));
                        }
                    }

                    if(($new_payment->is_done == $old_paymnet->is_done) && ($new_payment->price != $old_paymnet->price)) {
                        if($type == 'input')
                        {
                            $new_wallet->update([
                                'amount' => $new_wallet->amount + $new_payment->price - $old_paymnet->price,
                            ]);
                            event(new WalletAmountChanged(
                                $new_value, 
                                $new_wallet, 
                                abs($new_payment->price - $old_paymnet->price),
                                $new_payment->price > $old_paymnet->price ? 'input' : 'output' ,
                                'پرداختی فاکتور ویرایش شد'
                            ));
                        }
                        if($type == 'output')
                        {
                            $new_wallet->update([
                                'amount' => $new_wallet->amount - $new_payment->price + $old_paymnet->price,
                            ]);
                            event(new WalletAmountChanged(
                                $new_value, 
                                $new_wallet, 
                                abs($new_payment->price - $old_paymnet->price),
                                $new_payment->price > $old_paymnet->price ? 'output' : 'input' ,
                                'پرداختی فاکتور ویرایش شد'
                            ));
                        }

                    }


                }
            }

        }

       
    }
}
