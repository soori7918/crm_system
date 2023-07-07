<?php

namespace App\Http\Controllers\Panel\Inventory\ProductChanges;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\ProductChange;
use App\Http\Controllers\Controller;
use App\Events\ProductChange\ProductChangeDeleted;
use App\Models\Customer;
use App\Models\Log;

class ProductChangeController extends Controller
{
    
    public function index(Request $request)
    {
        $this->authorize('create productChange');

        $product_changes = ProductChange::orderBy('created_at', 'desc');
        if($request->search)
        {
            $product_changes = $product_changes->whereHas('customers' , function($q) use($request){
                $q->where('name','Like',"%$request->search%");
            })->orWhere('title' ,'like' ,"%$request->search%")
            ->orWhere('code',$request->search);
        }

        if($request->type)
        {
            if($request->start_date)
            {
               $product_changes = $product_changes->where($request->type , '>=', $request->start_date ? Carbon::createFromTimestampMs($request->start_date): '');
            }
            if($request->end_date)
            {
               $product_changes = $product_changes->where($request->type , '<=', $request->end_date ?  Carbon::createFromTimestampMs($request->end_date): '' );
            }

        }

        $product_changes = $product_changes->paginate();
        $product_changes->appends($request->query());
        return view('panel.inventory.product_changes.index')->with([
            'product_changes' => $product_changes,
        ]);
    }

    
    public function destroy(ProductChange $product_change)
    {
        $this->authorize('delete productChange');

        $product_change->load('items');
        event(new ProductChangeDeleted(null, $product_change , auth()->id()));
        $product_change->items()->delete();
        $product_change->delete();
        return \redirect()->back()->with([
            'success' => 'فاکتور مورد نظر با موفقیت حذف شد'
        ]);
    }


    
    public function history(ProductChange $product_change)
    {
        $logs = $product_change->logs()->paginate(10);

        return view('panel.inventory.product_changes.history')->with([
            'product_change' => $product_change,
            'logs' => $logs
        ]);
    }


     public  function deleteHistory(ProductChange $product_change,Log $history)
    {
        $this->authorize('delete report');

        $history->delete();
        return redirect()->back()->with([
            'success' => 'حذف شد'
        ]);
    }


     public function report(Request $request)
    {
        $this->authorize('view reports');

        $reports = ProductChange::orderBy('created_at','desc');
        $customers = Customer::all();
        if($request->type)
        {
            $reports = $reports->where('type' , $request->type);
        }
        if($request->start_date)
        {
           $reports = $reports->where('created_at' , '>=', $request->start_date ? Carbon::createFromTimestampMs($request->start_date): '');
        }
        if($request->end_date)
        {
           $reports = $reports->where('created_at' , '<=', $request->end_date ?  Carbon::createFromTimestampMs($request->end_date): '' );
        }
        if($request->name)
        {
           $reports = $reports->where('customer_id' , $request->name);
        }

      

        $reports = $reports->paginate();
        $reports->appends($request->query());
        return view('panel.inventory.product_changes.reports')->with([
            'reports' => $reports,
            'customers' => $customers
        ]);
    }




}
