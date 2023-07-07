<?php

namespace App\Classes;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class EditFactorItem
{
    public $factor_id;
    public $session_name;
    public function __construct($data = null , $id)
    {
        $this->factor_id = $id;
        $this->session_name = "EditFactorItem$id";
        if ($data) {
            $this->content = $data;
        } else {
            $this->content = session()->get($this->session_name, []);
        }
    }


    public function add($item)
    {
        $content = $this->getContent();
        $content->push([
            'rowId' => Str::random(20),
            'product_id' => $item['product_id'],
            'title' => $item['title'],
            'price' =>  $item['price'] ?: null,
            'order' => time(),
            'amount' => $item['amount'] ?: null,
            'description' => $item['description'] ?: null,
        ]);
        
        session()->put($this->session_name, $content->toArray());
    }


    public function addByRequest(Request $request)
    {
        $content = $this->getContent();
        $content->push([
            'rowId' => Str::random(20),
            'product_id' => $request->get('product_id'),
            'title' => $request->get('title'),
            'price' =>  $request->get('price') ?: null,
            'order' => time(),
            'amount' => $request->get('amount') ?: null,
            'description' => $request->get('description') ?: null,
        ]);
        session()->put($this->session_name, $content->toArray());
    }




    public function remove($code)
    {
        $content = $this->getContent();
        $content = $content->where('rowId', '<>', $code);
        session()->put($this->session_name, $content->toArray());
    }

    public function increase($rowId, $amount)
    {
        $content = $this->getContent();
        $item = $content->where('rowId', $rowId)->first();
        $item['amount'] += $amount;
        $content = $content->where('rowId', '<>', $rowId);
        $content->push($item);
        session()->put($this->session_name, $content->toArray());
    }
    public function decrease($rowId, $amount)
    {
        $content = $this->getContent();
        $item = $content->where('rowId', $rowId)->first();
        $item['amount'] -= $amount;
        $content = $content->where('rowId', '<>', $rowId);
        $content->push($item);
        session()->put($this->session_name, $content->toArray());
    }

    public function getContent()
    {
        $content = collect(session()->get($this->session_name, []));
        return $content->sortBy('order');
    }

    public function destroy()
    {
        session()->forget($this->session_name);
    }
}


