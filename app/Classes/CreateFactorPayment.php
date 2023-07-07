<?php

namespace App\Classes;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CreateFactorPayment
{
    public function __construct($data = null)
    {
        if ($data) {
            $this->content = $data;
        } else {
            $this->content = \session()->get('CreateFactorPayment', []);
        }
    }


    public function add(Request $request)
    {
        $content = $this->getContent();
        $content->push([
            'rowId' => Str::random(20),
            'price' => $request->price,
            'type' =>  $request->type,
            'order' => time(),
            'date' => $request->register_date ? Carbon::createFromTimestampMs($request->register_date) : Carbon::now(),
            'is_done' => $request->is_done 
        ]);
        session()->put('CreateFactorPayment', $content->toArray());
    }
   

    public function remove($rowId)
    {
            $content = $this->getContent();
            $content = $content->where('rowId', '<>', $rowId);
            session()->put('CreateFactorPayment', $content->toArray());
    }


    public function getContent()
    {
        $content = collect(session()->get('CreateFactorPayment', []));
        return $content->sortBy('order');
    }
}


