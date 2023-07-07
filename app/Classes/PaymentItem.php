<?php

namespace App\Classes;

use App\Models\FactorPayment;
use Illuminate\Support\Str;


class PaymentItem {
    public $rowId;
    public $price;
    public $type;
    public $order;
    public $date;
    public $is_done;
    public $payment_id;

    public function __construct($price, $type, $date, $is_done, $payment_id = null) 
    {
        $this->rowId = Str::random(20);
        $this->price = $price;
        $this->type = $type;
        $this->order = time();
        $this->date = $date;
        $this->is_done = $is_done;
        $this->payment_id = $payment_id ;
    }

    public function sync($factor_id)
    {
        if(!$this->payment_id) {
            $payment = FactorPayment::create([
                'factor_id' => $factor_id,
                'price' => $this->price,
                'type' => $this->type,
                'date' => $this->date,
                'is_done' => $this->is_done,
            ]);
            $this->payment_id = $payment->id;
        }
    }
}


