<?php

namespace App\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\Product;
use Carbon\Carbon;

class ReturnProductChangeCart
{
    public function __construct($data = null)
    {
        if ($data) {
            $this->content = $data;
        } else {
           $this->content = session()->get('ReturnProductChangeCart', []);
        }
    }
    public function add(Request $request)
    {
        $content = $this->getContent();
        $product = Product::where('id', $request->product_id)->firstOrFail();
        $content->push([
            'rowId' => Str::random(20),
            'product_id' => $product->id,
            'product_name' => $product->name,
            'order' => time(),
            'amount' => $request->amount,
            'return_date' => $request->return_date ? Carbon::createFromTimestampMs($request->return_date) : Carbon::now(),
            'description' => $request->description ?: null,
            'is_done' => $request->is_done ? true : false,
        ]);
    
        session()->put('ReturnProductChangeCart', $content->toArray());
    }



    public function increase($rowId, $amount)
    {
        $content = $this->getContent();
        $item = $content->where('rowId', $rowId)->first();
        $item['amount'] += $amount;
        $content = $content->where('rowId', '<>', $rowId);
        $content->push($item);
        session()->put('ReturnProductChangeCart', $content->toArray());
    }


    public function decrease($rowId, $amount)
    {
        $content = $this->getContent();
        $item = $content->where('rowId', $rowId)->first();
        $item['amount'] -= $amount;
        $content = $content->where('rowId', '<>', $rowId);
        if ($item['amount'] > 0) {
            $content->push($item);
        }
        session()->put('ReturnProductChangeCart', $content->toArray());
    }

    public function remove($rowId)
    {
        $content = $this->getContent();
        $content = $content->where('rowId', '<>', $rowId);
        session()->put('ReturnProductChangeCart', $content->toArray());
    }

    public function getContent()
    {
        $content = collect(session()->get('ReturnProductChangeCart', []));
        return $content->sortBy('order');
    }
}
