<?php

namespace App\Listeners\ProductChange;

use App\Events\ProductChange\ProductChangeCreated;
use App\Models\Product;

class ProductChangeCreatedHandler
{
    

    /**
     * Handle the event.
     *
     * @param  ProductChangeCreated  $event
     * @return void
     */
    public function handle(ProductChangeCreated $event)
    {
        $new_value = $event->new_value;
        $type = $new_value->type;


        foreach($new_value->items as $item)
        {
            $product = $item->product;
                if($type == 'enter')
                {
                    $product->update([
                        'amount' => $product->amount + $item->amount,
                    ]);
                }
                if($type == 'exit')
                {
                    $product->update([
                        'amount' => $product->amount - $item->amount,
                    ]);
                }
                if($type == 'return')
                {
                    $product->update([
                        'return_amount' => $product->return_amount + $item->amount,
                    ]);
                }
                if($type == 'gharz')
                {
                    $product->update([
                        'return_amount' => $product->return_amount + $item->amount,
                    ]);
                }
        }
    
     
    }
}
