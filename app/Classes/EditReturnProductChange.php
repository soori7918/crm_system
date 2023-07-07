<?php

namespace App\Classes;

use App\Models\Product;
use App\Models\ProductChangeItem;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EditReturnProductChange
{
    public $product_change_id;
    public $session_name;
    public $content;
    public function __construct($id)
    {
        $this->product_change_id = $id;
        $this->session_name = "EditReturnProductChange$id";
        $this->content = session()->get($this->session_name, collect([]));
    }

    public function save()
    {
        session()->put($this->session_name, $this->content);
    }

    public function addByItem(ProductChangeItem $item)
    {
        $product = Product::where('id' , $item->product_id)->first();
        $item = new ReturnChangeItem(
            $item->product_id,
            $item->product_name = $product->name,
            $item->amount,
            $item->description, 
            $item->return_date, 
            $item->is_done, 
            $item->id 
        );
        $this->content->push($item);
        $this->save();
    }


    public function addByRequest(Request $request)
    {   
        $product = Product::where('id' , $request->product_id)->first();
        $item = new ChangeItem(
            $request->product_id, 
            $product->name,
            $request->amount,
            $request->description,
        );
        $this->content->push($item);
        $this->save();

    }


    // public function addByRequest(Request $request)
    // {   
    //     $product = Product::where('id' , $request->product_id)->first();
    //     $item = new ReturnChangeItem(
    //         $request->product_id, 
    //         $product->name,
    //         $request->amount,
    //         $request->description,
    //         $request->return_date ? Carbon::createFromTimestampMs($request->date) : Carbon::now(),
    //         $request->is_done,
    //     );
    //     $this->content->push($item);
    //     $this->save();

    // }

    public function increase($rowId, $amount)
    {
        $item = $this->content->where('rowId', $rowId)->first();
        $item->amount += $amount;
        $content = $this->content->where('rowId', '<>', $rowId);
        $content->push($item);
        $this->save();

    }


    public function decrease($rowId, $amount)
    {
        $item = $this->content->where('rowId', $rowId)->first();
        $item->amount -= $amount;
        $content = $this->content->where('rowId', '<>', $rowId);
        if ($item->amount > 0) {
            $content->push($item);
        }
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

    public function destroy()
    {
        session()->forget($this->session_name);

    }

    public function empty()
    {
        $this->content = collect([]);
        $this->save();
    }

    public function sync()
    {
        foreach($this->content as $item)
        {
            $item->sync($this->product_change_id);
        }
        $this->content = collect($this->content);
        $product_change_item_ids = $this->content->pluck('item_id')->toArray();
        ProductChangeItem::whereNotIn('id',$product_change_item_ids)->delete();
    }


}
