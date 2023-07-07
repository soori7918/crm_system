<?php

namespace App\Classes;

use App\Models\FactorPayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class EditFactorPayment
{
    public $factor_id;
    public $session_name;
    public $content;
    public function __construct($id)
    {
        $this->factor_id = $id;
        $this->session_name = "EditFactorPayment$id";
        $this->content = session()->get($this->session_name, collect([]));
    }

    public function save()
    {
        session()->put($this->session_name, $this->content);
    }


    public function addByItem(FactorPayment $item)
    {
        $item = new PaymentItem(
            $item->price,
            $item->type,
            $item->date,
            $item->is_done,
            $item->id
        );
        $this->content->push($item);
        $this->save();
    }
    public function addByRequest($request)
    {
        $item = new PaymentItem(
            $request->price, 
            $request->type,
            $request->date ? Carbon::createFromTimestampMs($request->date) : Carbon::now(),
            $request->is_done
        );
        $this->content->push($item);
        $this->save();
    }
    public function editByRequest($request)
    {
        $item= $this->content->where('rowId',  $request->edit_rowId)->first();
        $item->price = $request->edit_price ;
        $item->type = $request->edit_type;
        $item->date = $request->edit_date ? Carbon::createFromTimestampMs($request->edit_date) : $item->date;
        $item->is_done = $request->edit_is_done;
        $this->save();
    }
 
    public function remove($rowId)
    {
        $this->content = $this->content->where('rowId', '<>', $rowId);
        $this->save();
    }


    public function getContent()
    {
        return $this->content->sortBy('order');
    }

    public function empty()
    {
        $this->content = collect([]);
        $this->save();
    }

    public function destroy()
    {
        session()->forget($this->session_name);
    }

    public function sync()
    {
        foreach($this->content as $item)
        {
            $item->sync($this->factor_id);
        }
        $payment_item_ids = $this->content->pluck('payment_id')->toArray();
        FactorPayment::whereNotIn('id',$payment_item_ids)->delete();
    }
}
