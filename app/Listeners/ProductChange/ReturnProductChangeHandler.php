<?php

namespace App\Listeners\ProductChange;

use App\Events\ProductChange\ReturnProductChangeCreated;

class ReturnProductChangeHandler
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
     * @param  ReturnProductChange  $event
     * @return void
     */
    public function handle(ReturnProductChangeCreated $event)
    {
        $new_value = $event->new_value;
        $type = $new_value->type;

        foreach($new_value->items as $item)
        {
            $product = $item->product;
                if($type == 'return')
                {
                    if($new_value->is_done == false)
                    {
                        $product->update([
                            'return_amount' => $product->return_amount + $item->amount,
                        ]);

                    }else{
                        $product->update([
                            'return_amount' => $product->return_amount - $item->amount,
                        ]);
                    }
                }
                if($type == 'gharz')
                {
                    if($new_value->is_done == false)
                    {
                        $product->update([
                            'return_amount' => $product->return_amount + $item->amount,
                        ]);
                    }else{
                        $product->update([
                            'return_amount' => $product->return_amount - $item->amount,
                        ]);
                    }
                }
        }
    



    }
}
