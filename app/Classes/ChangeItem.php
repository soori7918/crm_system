<?php

namespace App\Classes;

use App\Models\ProductChangeItem;
use Illuminate\Support\Str;


class ChangeItem {
    public $rowId;
    public $product_id;
    public $product_name;
    public $order;
    public $amount;
    public $description;
    public $item_id;

    public function __construct($product_id, $product_name ,$amount, $description, $item_id =null) 
    {
        $this->rowId = Str::random(20);
        $this->product_id = $product_id ;
        $this->product_name = $product_name ;
        $this->amount = $amount;
        $this->order = time();
        $this->description = $description;
        $this->item_id = $item_id;
    }

    public function sync($product_change_id)
    {
        if(!$this->item_id) {
            $change_item = ProductChangeItem::create([
                'product_id' => $this->product_id,
                'amount' => $this->amount,
                'doc_id' => $product_change_id,
                'description' => $this->description,
                ]);
                $this->item_id = $change_item->id;
        } else {
            ProductChangeItem::where('id',$this->item_id)->update([
                'amount' => $this->amount
            ]);
        }
    }
}


