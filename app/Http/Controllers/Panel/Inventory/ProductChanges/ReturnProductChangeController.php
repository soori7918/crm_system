<?php

namespace App\Http\Controllers\Panel\Inventory\ProductChanges;

use App\Classes\EditReturnProductChange;
use App\Classes\ReturnProductChange;
use App\Classes\ReturnProductChangeCart;
use App\Events\ProductChange\ProductChangeUpdated;
use App\Events\ProductChange\ReturnProductChangeCreated;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductChange;
use App\Models\ReturnItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ReturnProductChangeController extends Controller
{
    public function create()
    {
        $items = Session::get('ReturnProductChange');
        $users = User::all();
        $customers = Customer::all();
        $products = Product::all();
        return view('panel.inventory.product_changes.return.create')->with([
            'users' => $users,
            'customers' => $customers,
            'products' => $products,
            'items' => $items,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'numeric|min:0',
            'title' => 'required|string|max:255',
            'customer_id' => 'nullable|exists:customers,id',
            'mobile' => 'nullable|numeric|regex:/^09\d{9}$/',
            'address' => 'nullable|max:2000',
            'amount' => 'nullable|numeric',
            'return_date' => 'nullable|digits:13',
            'description' => 'nullable|max:3000',
        ], [], [
            'code' => 'کد',
            'title' =>  'عنوان',
            'amount' => 'مقدار محصول',
            'mobile' => 'شماره تماس ',
            'address' => 'آدرس',
            'customer_id' => 'مشتری',
            'return_date' => 'تاریخ برگشت',
            'description' => 'توضیحات',
        ]);

        $product_change = ProductChange::Create([
            'code' => $request->code,
            'title' => $request->title,
            'type' => $request->type,
            'customer_id' => $request->customer_id,
            'mobile' => $request->mobile,
            'address' => $request->address,
            'return_date' => $request->return_date ? Carbon::createFromTimestampMs($request->return_date) : Carbon::now(),
            'created_by' => auth()->user()->id,
            'description' => $request->description,
        ]);

        $items = [];
        $content = Session::get('ReturnProductChange');

        foreach ($content  as $item) {
            $items[] = [
                'product_id' => $item['product_id'],
                'amount' => $item['amount'],
                'description' => $item['description'],
                'doc_id' => $product_change->id,
            ];
        }

        foreach ($items as $product_change_item) {
            $product_change->items()->create($product_change_item);
        }

        $product_change->load('items');

        event(new ReturnProductChangeCreated($product_change, null, auth()->id()));
        session(['ReturnProductChange' => []]); 

        return redirect()->route('panel.inventory.productChanges.index')->with([
            'success' => 'با موفقیت ثبت شد'
        ]);

    }


    public function show(ProductChange $return)
    {
        return view('panel.inventory.product_changes.return.show')->with([
            'product_change' => $return,
        ]);
    }
    

    public function edit($return)
    {
        $product_change = ProductChange::find($return);
        $items_session = new EditReturnProductChange($product_change->id);
        $items_session->empty();
        foreach ($product_change->items as $item) {
            $items_session->addByItem($item);
        }
        $items = $items_session->getContent();

        $return_items = $product_change->return_items;

        $users = User::all();
        $customers = Customer::all();
        $products = Product::all();

        return view('panel.inventory.product_changes.return.edit')->with([
            'product_change' => $product_change,
            'products' => $products,
            'customers' => $customers,
            'users' => $users,
            'items' => $items,
            'return_items' => $return_items
        ]);
    }


    public function update(Request $request, $return)
    {
        $product_change = ProductChange::find($return);
        $request->validate([
            'title' => 'required|string|max:255',
            'customer_id' => 'nullable|exists:customers,id',
            'mobile' => 'nullable|numeric|regex:/^09\d{9}$/',
            'address' => 'nullable|max:2000',
            'register_at' => 'nullable',
            'amount' => 'nullable|numeric',
            'return_date' => 'nullable|digits:13',
            'description' => 'nullable|max:3000',
        ], [], [
            'title' =>  'عنوان',
            'customer_id' => 'مشتری',
            'mobile' => 'شماره تماس',
            'address' => 'آدرس',
            'register_at' => 'تاریخ ثبت',
            'amount' => 'مقدار محصول',
            'return_date' => 'تاریخ برگشت',
            'description' => 'توضیحات',
        ]);

        $old_value = clone $product_change;
        $old_value->load('items');

        
        $product_change->update([
            'code' => $request->code,
            'title' => $request->title,
            'customer_id' => $request->customer_id,
            'mobile' => $request->mobile,
            'address' => $request->address,
            // 'return_date' => $request->return_at ? Carbon::createFromTimestampMs($request->return_at) : $product_change->return_date,
            'return_date' => $request->return_date ? Carbon::createFromTimestampMs($request->return_date) : $product_change->return_date,
            'updated_by' =>  auth()->user()->id,
            'description' => $request->description,
        ]);

        $items_session = new EditReturnProductChange($product_change->id);
        $items_session->sync();
        $product_change->load('items');

        event(new ProductChangeUpdated($product_change, $old_value , auth()->id()));
        $items_session->destroy();


        return redirect()->route('panel.inventory.productChanges.index')->with([
            'success' => 'تغییرات شما با موفقیت ثبت شد'
        ]);
    }


    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|max:2000',
        ], [], [
            'amount' => 'تعداد',
            'description' => 'توضیحات',
        ]);
        if ($validator->fails()) {
            return response([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }
        $cart = new ReturnProductChange($request->product_id); 
        $cart->add($request);
        $items = $cart->getContent();
        return view('components.productChange.return.cartList', [
            'items' => $items,
        ])->render();
    }


    public function increaseItemAmount(Request $request)
    {
        $cart = new ReturnProductChange();
        $cart->increase($request->rowId, 1);
        $items = $cart->getContent();
        return view('components.productChange.return.cartList', [
            'items' => $items
        ])->render();
    }

    public function decreaseItemAmount(Request $request)
    {
        $cart = new ReturnProductChange();
        $cart->decrease($request->rowId, 1);
        return view('components.productChange.return.cartList', [
            'items' => $cart->getContent()
        ])->render();
    }

    public function removeItem(Request $request)
    {
        $cart = new ReturnProductChange();
        $cart->remove($request->rowId);
        $items = $cart->getContent();
        return view('components.productChange.return.cartList', [
            'items' => $items
        ])->render();
    }


   
    // public function updateReturnItem(Request $request,ProductChange $product_change)
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

    public function removeReturnItem(ProductChange $productChange ,ReturnItem $returnItem)
    {
        $product = $returnItem->product;
        $product->update([
            'return_amount' => $product->return_amount - $returnItem->amount
        ]);

        $returnItem->delete();
        return \redirect()->back()->with([
            'success' => 'آیتم انتخابی حذف شد'
        ]);
    }



    public function addItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|max:2000',
        ], [], [
            'amount' => 'تعداد',
            'description' => 'توضیحات',
        ]);
        if ($validator->fails()) {
            return response([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }
        $cart = new EditReturnProductChange($request->product_id); 
        $cart->addByRequest($request);
        $items = $cart->getContent();
        return view('components.productChange.return.cartList', [
            'items' => $items,
        ])->render();
    }


    public function addItemList(Request $request,ProductChange $productChange)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|max:2000',
        ], [], [
            'amount' => 'تعداد',
            'description' => 'توضیحات',
        ]);
        if ($validator->fails()) {
            return response([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }
        $cart = new EditReturnProductChange($productChange->id); 
        $cart->addByRequest($request);
        $items = $cart->getContent();
        return view('components.productChange.return.cartListItem', [
            'product_change' => $productChange,
            'items' => $items,
        ])->render();
    }



    public function increase(ProductChange $productChange ,$rowId)
    {
        $cart = new EditReturnProductChange($productChange->id);
        $cart->increase($rowId, 1);
        $items = $cart->getContent();
        return view('components.productChange.return.cartListItem', [
            'items' => $items,
            'product_change' => $productChange
        ])->render();
    }

    public function decrease(ProductChange $productChange ,$rowId)
    {
        $cart = new EditReturnProductChange($productChange->id);
        $cart->decrease($rowId, 1);
        $items = $cart->getContent();
        return view('components.productChange.return.cartListItem', [
            'items' => $items,
            'product_change' => $productChange
        ])->render();
    }

    public function remove(ProductChange $productChange , $rowId)
    {
        $cart = new EditReturnProductChange($productChange->id);
        $cart->remove($rowId);
        $items = $cart->getContent($rowId);
        return view('components.productChange.return.cartListItem', [
            'items' => $items,
            'product_change' => $productChange
        ])->render();

    }

    public function addReturnItem(Request $request,ProductChange $productChange)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|max:2000',
            'return_date' => 'nullable',
        ], [], [
            'amount' => 'تعداد',
            'description' => 'توضیحات',
            'return_date' => 'تاریخ برگشت',
        ]);

        $item=$productChange->items->where('product_id',$request->product_id)->first();
        $sum_return_amount= $productChange->return_items->where('product_id' ,$request->product_id)->sum('amount');

        if($request->return_date < $productChange->exit_date)
        {
            return redirect()->back()->with([
                'danger'=> 'تاریخ برگشت نباید از تاریخ خروج سند کوجکتر باشد'
            ]);
        }
      
        if($request->amount > $item->amount)
        {
            return redirect()->back()->with([
                'danger'=> 'تعداد نباید از تعداد برگشتی بیشتر باشد'
            ]);
        }


        if($sum_return_amount > $item->amount)
        {
            return redirect()->back()->with([
                'danger'=> 'کالاها تمام شد'
            ]);
        }



        $product = Product::where('id',$request->product_id)->first();

        
        if($request->is_done == false)
        {
            $product->update([
                'return_amount' => $product->return_amount + $request->amount,
            ]);
        }else{
            $product->update([
                'return_amount' => $product->return_amount - $request->amount,
            ]);
        }

        $productChange->return_items()->create([
            'product_id' => $request->product_id,
            'doc_id' => $productChange->id,
            'amount' => $request->amount,
            'description' => $request->description,
            'return_date' => $request->return_date ? Carbon::createFromTimestampMs($request->return_date) : $request->return_date,
            'is_done' => $request->is_done ? true : false,
        ]);

        return redirect()->back()->with('success' , 'به روز رسانی انجام شد');


    }



    public function updateReturnItem(Request $request,ProductChange $productChange)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|max:2000',
            'return_date' => 'nullable',
        ], [], [
            'amount' => 'تعداد',
            'description' => 'توضیحات',
            'return_date' => 'تاریخ برگشت',
        ]);
       
        $item = $productChange->return_items->where('id',$request->item_id)->first();
        $sum_return_amount= $productChange->return_items->where('product_id' ,$request->product_id)->sum('amount');

        if($request->return_date < $productChange->exit_date)
        {
            return redirect()->back()->with([
                'danger'=> 'تاریخ برگشت نباید از تاریخ خروج سند کوجکتر باشد'
            ]);
        }

        if($sum_return_amount > $item->amount)
        {
            return redirect()->back()->with([
                'danger'=> 'کالاها تمام شد'
            ]);
        }

        $item->update([
            'amount' => $request->amount,
            'description' => $request->description,
            'return_date' => $request->return_edit ? Carbon::createFromTimestampMs($request->return_edit)  :  $item->return_date , 
            'is_done' => $request->is_done ? true : false , 
        ]);

        $product = Product::where('id',$request->product_id)->first();

        if($request->is_done == false)
        {
            $product->update([
                'return_amount' => $product->return_amount + $request->amount,
            ]);
        }else{
            $product->update([
                'return_amount' => $product->return_amount - $request->amount,
            ]);
        }

        return \redirect()->back()->with([
            'success' => 'ثبت شد'
        ]);
    }

    

    
}
