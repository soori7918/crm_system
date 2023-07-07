<?php

namespace App\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class CreateCostFactorAddItem
{
    public function __construct($data = null)
    {
        if ($data) {
            $this->content = $data;
        } else {
            $this->content = \session()->get('CreateCostFactorAddItem', []);
        }
    }


    public function add(Request $request)
    {
        $content = $this->getContent();
       
        $content->push([
            'rowId' => Str::random(20),
            'product_id' => $request->product_id,
            'title' => $request->title,
            'price' =>  $request->price ?: null,
            'order' => time(),
            'amount' => $request->amount ?: null,
            'description' => $request->description ?: null,
        ]);
        
        \session()->put('CreateCostFactorAddItem', $content->toArray());
    }




    public function remove($rowId)
    {
        $content = $this->getContent();
        $content = $content->where('rowId', '<>', $rowId);
        session()->put('CreateCostFactorAddItem', $content->toArray());
    }

    public function increase($id, $amount)
    {
        if ($id) {
            $content = $this->getContent();
            $item = $content->where('rowId', $id)->first();
            $item['amount'] += $amount;
            $content = $content->where('rowId', '<>', $id);
            $content->push($item);
            session()->put('CreateCostFactorAddItem', $content->toArray());
        }
    }
    public function decrease($id, $amount)
    {
        if ($id) {
            $content = $this->getContent();
            $item = $content->where('rowId', $id)->first();
            $item['amount'] -= $amount;
            $content = $content->where('rowId', '<>', $id);
            if ($item['amount'] > 0) {
                $content->push($item);
            }
            session()->put('CreateCostFactorAddItem', $content->toArray());
        }
    }
    public function getContent()
    {
        $content = collect(\session()->get('CreateCostFactorAddItem', []));
        return $content->sortBy('order');
    }
}


