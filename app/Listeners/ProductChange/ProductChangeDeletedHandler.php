<?php

namespace App\Listeners\ProductChange;

use App\Events\ProductChange\ProductChangeDeleted;
use App\Models\Product;
use App\Models\ProductChangeItem;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ProductChangeDeletedHandler
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ProductChangeDeleted  $event
     * @return void
     */
    public function handle(ProductChangeDeleted $event)
    {
        $old_value = $event->old_value;
        $type = $old_value->type;
        
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
