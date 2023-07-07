<?php

namespace App\Http\Controllers\Panel;

use App\Classes\EditProductChangeCart;
use App\Classes\EditReturnProductChange;
use App\Classes\ExitProductChange;
use App\Classes\InputProductChange;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\ProductChange;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Events\ProductChange\ProductChangeCreated;
use App\Events\ProductChange\ProductChangeDeleted;
use App\Events\ProductChange\ProductChangeUpdated;
use App\Models\Log;
use Illuminate\Database\Eloquent\Collection;

class ProductChangeController extends Controller
{
   
    // public function index(Request $request)
    // {
    //     $product_changes = ProductChange::orderBy('created_at', 'desc');
    //     if($request->search)
    //     {
    //         $product_changes = $product_changes->whereHas('customers' , function($q) use($request){
    //             $q->where('name','Like',"%$request->search%");
    //         })->orWhere('title' ,'like' ,"%$request->search%")
    //         ->orWhere('code',$request->search);
    //     }

    //     if($request->type)
    //     {
    //         if($request->start_date)
    //         {
    //            $product_changes = $product_changes->where($request->type , '>=', $request->start_date ? Carbon::createFromTimestampMs($request->start_date): '');
    //         }
    //         if($request->end_date)
    //         {
    //            $product_changes = $product_changes->where($request->type , '<=', $request->end_date ?  Carbon::createFromTimestampMs($request->end_date): '' );
    //         }

    //     }

    //     $product_changes = $product_changes->paginate();
    //     $product_changes->appends($request->query());
    //     return view('panel.product_changes.index')->with([
    //         'product_changes' => $product_changes,
    //     ]);
    // }

   
    // public function create()
    // {
    //     $counter = ProductChange::all()->count();
    //     $items = Session::get('InputProductChange');

    //     $users = User::all();
    //     $customers = Customer::all();
    //     $products = Product::all();
    //     return view('panel.product_changes.create')->with([
    //         'users' => $users,
    //         'customers' => $customers,
    //         'products' => $products,
    //         'items' => $items,
    //         'counter' => $counter
    //     ]);
    // } 
    
   
    
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'code' => 'required|numeric|min:0',
    //         'title' => 'required|string|max:255',
    //         'customer_id' => 'nullable|exists:customers,id',
    //         'mobile' => 'nullable|numeric|regex:/^09\d{9}$/',
    //         'address' => 'nullable|max:2000',
    //         'amount' => 'nullable|numeric',
    //         'enter_date' => 'nullable|digits:13',
    //         'description' => 'nullable|max:3000',
    //     ], [], [
    //         'code' => 'کد',
    //         'title' =>  'عنوان',
    //         'customer_id' => 'مشتری',
    //         'mobile' => 'شماره تماس',
    //         'address' => 'آدرس',
    //         'amount' => 'مقدار محصول',
    //         'enter_date' => 'تاریخ سند',
    //         'description' => 'توضیحات',
    //     ]);
    //     $product_change = ProductChange::Create([
    //         'code' => $request->code,
    //         'title' => $request->title,
    //         'type' => 'enter',
    //         'customer_id' => $request->customer_id,
    //         'mobile' => $request->mobile,
    //         'address' => $request->address,
    //         'enter_date' => $request->enter_date ? Carbon::createFromTimestampMs($request->enter_date) : Carbon::now(),
    //         'created_by' => auth()->user()->id,
    //         'description' => $request->description,
    //     ]);
    //     $items = [];
    //     $content = Session::get('InputProductChange');

    //     foreach ($content  as $item) {
    //         $items[] = [
    //             'product_id' => $item['product_id'],
    //             'amount' => $item['amount'],
    //             'description' => $item['description'],
    //             'doc_id' => $product_change->id,
    //         ];
    //     }
    //     foreach ($items as $product_change_item) {
    //         $product_change->items()->create($product_change_item);
    //     }

    //     $product_change->load('items');
        
    //     event(new ProductChangeCreated($product_change, null, auth()->id()));
    //     session()->put('InputProductChange',[]); 
    //     return redirect()->route('panel.product_changes.index')->with([
    //         'success' => 'با موفقیت ثبت شد'
    //     ]);
    // }
    
   
    
    // public function show(ProductChange $product_change)
    // {
    //     return view('panel.product_changes.show')->with([
    //         'product_change' => $product_change,
    //     ]);
    // }

   
    // public function edit(ProductChange $product_change)
    // {
      
    //     $items_session = new EditProductChangeCart($product_change->id);
    //     $items_session->empty();
    //     foreach ($product_change->items as $item) {
    //         $items_session->addByItem($item);
    //     }
    //     $items = $items_session->getContent();

    //     $return_items = $product_change->return_items;

    //     $users = User::all();
    //     $customers = Customer::all();
    //     $products = Product::all();

    //     return view('panel.product_changes.edit')->with([
    //         'product_change' => $product_change,
    //         'products' => $products,
    //         'customers' => $customers,
    //         'users' => $users,
    //         'items' => $items,
    //         'return_items' => $return_items
    //     ]);
    // }

    
    // public function update(Request $request, ProductChange $product_change)
    // {
    //     $request->validate([
    //         'title' => 'required|string|max:255',
    //         'customer_id' => 'required|exists:customers,id',
    //         'mobile' => 'required|numeric|regex:/^09\d{9}$/',
    //         'address' => 'required|max:2000',
    //         'register_at' => 'nullable',
    //         'amount' => 'nullable|numeric',
    //         'enter_date' => 'nullable|digits:13',
    //         'exit_date' => 'nullable|digits:13',
    //         'return_date' => 'nullable|digits:13',
    //         'description' => 'nullable|max:3000',
    //     ], [], [
    //         'title' =>  'عنوان',
    //         'customer_id' => 'مشتری',
    //         'mobile' => 'شماره تماس',
    //         'address' => 'آدرس',
    //         'register_at' => 'تاریخ ثبت',
    //         'amount' => 'مقدار محصول',
    //         'enter_date' => 'تاریخ ورود',
    //         'exit_date' => 'تاریخ',
    //         'return_date' => 'تاریخ برگشت',
    //         'description' => 'توضیحات',
    //     ]);

    //     $old_value = clone $product_change;
    //     // Log::info($old_value);
    //     $old_value->load('items');

        
    //     $product_change->update([
    //         'code' => $request->code,
    //         'title' => $request->title,
    //         'customer_id' => $request->customer_id,
    //         'mobile' => $request->mobile,
    //         'address' => $request->address,
    //         'enter_date' => $request->enter_date ? Carbon::createFromTimestampMs($request->enter_date) : $product_change->enter_date,
    //         'exit_date' => $request->exit_date ? Carbon::createFromTimestampMs($request->exit_date) : $product_change->exit_date,
    //         'return_date' => $request->return_at ? Carbon::createFromTimestampMs($request->return_at) : $product_change->return_date,
    //         'return_date' => $request->return_date ? Carbon::createFromTimestampMs($request->return_date) : $product_change->return_date,
    //         'updated_by' =>  auth()->user()->id,
    //         'description' => $request->description,
    //     ]);

    //     $items_session = new EditProductChangeCart($product_change->id);
    //     $items_session->sync();
    //     $product_change->load('items');
    //     // Log::info($product_change->load('items'));

    //     event(new ProductChangeUpdated($product_change, $old_value , auth()->id()));
    //     $items_session->destroy();


    //     return redirect()->route('panel.product_changes.index')->with([
    //         'success' => 'تغییرات شما با موفقیت ثبت شد'
    //     ]);
    // }

   
    // public function destroy(ProductChange $product_change)
    // {
    //     $product_change->load('items');
    //     event(new ProductChangeDeleted(null, $product_change , auth()->id()));
    //     $product_change->items()->delete();
    //     $product_change->delete();
    //     return \redirect()->back()->with([
    //         'success' => 'فاکتور مورد نظر با موفقیت حذف شد'
    //     ]);
    // }


    // public function add(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'amount' => 'required|numeric|min:1',
    //         'description' => 'nullable|max:2000',
    //     ], [], [
    //         'amount' => 'تعداد',
    //         'description' => 'توضیحات',
    //     ]);
    //     if ($validator->fails()) {
    //         return response([
    //             'success' => false,
    //             'message' => $validator->errors()->first()
    //         ], 422);
    //     }
    //     $cart = new InputProductChange(); 
    //     $cart->add($request);
    //     $items = $cart->getContent();
    //     return view('components.productChange.cartList', [
    //         'items' => $items
    //     ])->render();
    // }
    // public function increase(Request $request)
    // {
    //     $cart = new InputProductChange();
    //     $cart->increase($request->rowId, 1);
    //     $items = $cart->getContent();
    //     return view('components.productChange.cartList', [
    //         'items' => $items
    //     ])->render();
    // }

    // public function decrease(Request $request)
    // {
    //     $cart = new InputProductChange();
    //     $cart->decrease($request->rowId, 1);
    //     return view('components.productChange.cartList', [
    //         'items' => $cart->getContent()
    //     ])->render();
    // }
    // public function remove(Request $request)
    // {
    //     $cart = new InputProductChange();
    //     $cart->remove($request->rowId);
    //     $items = $cart->getContent();
    //     return view('components.productChange.cartList', [
    //         'items' => $items
    //     ])->render();
    // }


  

    // public function report(Request $request)
    // {

    //     $reports = ProductChange::orderBy('created_at','desc');
    //     $customers = Customer::all();
    //     if($request->type)
    //     {
    //         $reports = $reports->where('type' , $request->type);
    //     }
    //     if($request->start_date)
    //     {
    //        $reports = $reports->where('created_at' , '>=', $request->start_date ? Carbon::createFromTimestampMs($request->start_date): '');
    //     }
    //     if($request->end_date)
    //     {
    //        $reports = $reports->where('created_at' , '<=', $request->end_date ?  Carbon::createFromTimestampMs($request->end_date): '' );
    //     }
    //     if($request->name)
    //     {
    //        $reports = $reports->where('customer_id' , $request->name);
    //     }

      

    //     $reports = $reports->paginate();
    //     $reports->appends($request->query());
    //     return view('panel.product_changes.reports.index')->with([
    //         'reports' => $reports,
    //         'customers' => $customers
    //     ]);
    // }


    // public function addEditProductChange(Request $request,ProductChange $product_change)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'amount' => 'required|numeric|min:1',
    //         'description' => 'nullable|max:2000',
    //         'return_date' => 'nullable',
    //     ], [], [
    //         'amount' => 'تعداد',
    //         'description' => 'توضیحات',
    //         'return_date' => 'تاریخ برگشت',
    //     ]);
    
    //     $product = Product::where('id',$request->product_id)->first();
    
    //     if($request->is_done == false)
    //     {
    //         $product->update([
    //             'return_amount' => $product->return_amount + $request->amount,
    //         ]);
    //     }else{
    //         $product->update([
    //             'return_amount' => $product->return_amount - $request->amount,
    //         ]);
    //     }

    //     $product_change->return_items()->create([
    //         'product_id' => $request->product_id,
    //         'doc_id' => $request->doc_id,
    //         'amount' => $request->amount,
    //         'description' => $request->description,
    //         'return_date' => $request->return_date ? Carbon::createFromTimestampMs($request->return_date) : $request->return_date,
    //         'is_done' => $request->is_done ? true : false,
    //     ]);

    //     return redirect()->back()->with('success' , 'به روز رسانی انجام شد');
    // }
    // public function returnEditProductChange(Request $request,ProductChange $product_change)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'amount' => 'required|numeric|min:1',
    //         'description' => 'nullable|max:2000',
    //         'return_date' => 'nullable',
    //     ], [], [
    //         'amount' => 'تعداد',
    //         'description' => 'توضیحات',
    //         'return_date' => 'تاریخ برگشت',
    //     ]);
    //     if ($validator->fails()) {
    //         return response([
    //             'success' => false,
    //             'message' => $validator->errors()->first()
    //         ], 422);
    //     }
    //     $item = $product_change->items->where('id',$request->item_id)->first();
    //     $item->update([
    //         'amount' => $request->amount,
    //         'description' => $request->description,
    //         'return_date' => $request->return_edit ? Carbon::createFromTimestampMs($request->return_edit)  :  $item->return_date , 
    //         'is_done' => $request->is_done ? true : false , 
    //     ]);

    //     if($request->is_done == true)
    //     {
    //         $a=$item->amount;
    //     }

    //     return \redirect()->back()->with([
    //         'success' => 'ثبت شد'
    //     ]);
    // }

    // public function increaseEditProductChange(ProductChange $product_change ,$rowId)
    // {
    //     $cart = new EditProductChangeCart($product_change->id);
    //     $cart->increase($rowId, 1);
    //     $items = $cart->getContent();
    //     return view('components.productChange.cartListItem', [
    //         'items' => $items,
    //         'product_change' => $product_change
    //     ])->render();
    // }

    // public function decreaseEditProductChange(ProductChange $product_change ,$rowId)
    // {
    //     $cart = new EditProductChangeCart($product_change->id);
    //     $cart->decrease($rowId, 1);
    //     $items = $cart->getContent();
    //     return view('components.productChange.cartListItem', [
    //         'items' => $items,
    //         'product_change' => $product_change
    //     ])->render();
    // }

    // public function removeEditProductChange(ProductChange $product_change , $rowId)
    // {
    //     $cart = new EditProductChangeCart($product_change->id);
    //     $cart->remove($rowId);
    //     $items = $cart->getContent($rowId);
    //     return view('components.productChange.cartListItem', [
    //         'items' => $items,
    //         'product_change' => $product_change
    //     ])->render();

    // }


    // public function history(ProductChange $product_change)
    // {
    //     $logs = $product_change->logs()->paginate(10);

    //     return view('panel.product_changes.history')->with([
    //         'product_change' => $product_change,
    //         'logs' => $logs
    //     ]);
    // }

    // public  function delete_reports(Log $log)
    // {
    //     $log->delete();
    //     return \redirect()->back()->with([
    //         'success' => 'حذف شد'
    //     ]);
    // }


    
    public function getCustomer(Request $request)
    {
        $customer = Customer::where('id' , $request->customer_id)->get();
        return response($customer , 200);
    }

}
