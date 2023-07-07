<?php

namespace App\Classes;

use App\Models\ProductChangeItem;
use Carbon\Carbon;
use Illuminate\Support\Str;


class ReturnChangeItem {
    public $rowId;
    public $product_id;
    public $product_name;
    public $order;
    public $amount;
    public $description;
    public $item_id;
    public $return_date;
    public $is_done;

    public function __construct($product_id, $product_name ,$amount, $description,$return_date,$is_done, $item_id =null) 
    {
        $this->rowId = Str::random(20);
        $this->product_id = $product_id ;
        $this->product_name = $product_name ;
        $this->amount = $amount;
        $this->order = time();
        $this->description = $description;
        $this->item_id = $item_id;
        $this->return_date = $return_date;
        $this->is_done = $is_done;
    }

    public function sync($product_change_id)
    {
        if(!$this->item_id) {
            $change_item = ProductChangeItem::create([
                'product_id' => $this->product_id,
                'amount' => $this->amount,
                'doc_id' => $product_change_id,
                'description' => $this->description,
                'return_date' => $this->return_date,
                'is_done' => $this->is_done,
                ]);
                $this->item_id = $change_item->id;
        } else {
            ProductChangeItem::where('id',$this->item_id)->update([
                'amount' => $this->amount
            ]);
        }
    }
}


