<?php

namespace App\Listeners\ProductChange;

use App\Events\ProductChange\ProductChangeUpdated;
use Illuminate\Support\Facades\Log;

class ProductChangeUpdatedHandler
{
  
    /**
     * Handle the event.
     *
     * @param  ProductChangeUpdated  $event
     * @return void
     */
    public function handle(ProductChangeUpdated $event)
    {
        $new_value = $event->new_value;
        $old_value = $event->old_value;
        $type = $new_value->type;

        Log::info($old_value->items);
        Log::info($new_value->items);
        
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
        }

        
        foreach($old_value->items as $item)
        {
            $product = $item->product;
                if($type == 'enter')
                {
                    $product->update([
                        'amount' => $product->amount - $item->amount,
                    ]);
                }
                if($type == 'exit')
                {
                   $product->update([
                        'amount' => $product->amount + $item->amount,
                    ]);
                }
        }

    }
}
