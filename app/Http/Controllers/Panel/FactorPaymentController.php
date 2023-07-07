<?php

namespace App\Http\Controllers\panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FactorPayment;
use App\Models\Factor;
use Exception;

class FactorPaymentController extends Controller
{
    public function taeedPayment(Request $request , $id )
    {    
        $factor_payment = FactorPayment::findOrFail($id);
        $factor = $factor_payment->factor;
        if($factor->type == 'input')
        { 
            if($factor_payment->is_done == false)
            {
                $wallet_amount = $factor->wallets->amount;
                $wallet_amount += $factor_payment->price;
                $factor->wallets->update([
                    'amount' => $wallet_amount,
                    ]);
                $factor_payment->update([
                    'is_done' => true
                ]);
                return \redirect()->back()->with([
                    'success' => 'با موفقیت ثبت گردید'
                ]);
            }
            if($factor_payment->is_done == true)
            {
                $wallet_amount = $factor->wallets->amount;
                $wallet_amount -= $factor_payment->price;
                $factor->wallets->update([
                    'amount' => $wallet_amount,
                ]);
                $factor_payment->update([
                    'is_done' => false
                ]);
                return \redirect()->back()->with([
                    'success' => 'با موفقیت ثبت گردید'
                ]);
            }
        }
        if($factor->type == 'output')
        { 
            if($factor_payment->is_done == false)
            {
                $wallet_amount = $factor->wallets->amount;
                $wallet_amount -= $factor_payment->price;
                $factor->wallets->update([
                    'amount' => $wallet_amount,
                    ]);
                $factor_payment->update([
                    'is_done' => true
                ]);
                return \redirect()->back()->with([
                    'success' => 'با موفقیت ثبت گردید'
                ]);
            }
            if($factor_payment->is_done == true)
            {
                $wallet_amount = $factor->wallets->amount;
                $wallet_amount += $factor_payment->price;
                $factor->wallets->update([
                    'amount' => $wallet_amount,
                ]);
                $factor_payment->update([
                    'is_done' => false
                ]);
                return \redirect()->back()->with([
                    'success' => 'با موفقیت ثبت گردید'
                ]);
            }
        }
        // event(new PaymentCo nfirmation($factor , $factor->payments));
    }

}
