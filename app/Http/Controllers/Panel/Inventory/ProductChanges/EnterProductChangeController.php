<?php

namespace App\Http\Controllers\Panel\Inventory\ProductChanges;

use App\Classes\EditProductChangeCart;
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
use App\Events\ProductChange\ProductChangeUpdated;

class EnterProductChangeController extends Controller
{
    
    public function create()
    {
        $this->authorize('create productChange');

        $counter = ProductChange::all()->count();
        $items = Session::get('InputProductChange');

        $users = User::all();
        $customers = Customer::all();
        $products = Product::all();
        return view('panel.inventory.product_changes.enter.create')->with([
            'users' => $users,
            'customers' => $customers,
            'products' => $products,
            'items' => $items,
            'counter' => $counter
        ]);
    } 
    
    public function store(Request $request)
    {
        $this->authorize('create productChange');

        $request->validate([
            'code' => 'required|numeric|min:0',
            'title' => 'required|string|max:255',
            'customer_id' => 'nullable|exists:customers,id',
            'mobile' => 'nullable|numeric|regex:/^09\d{9}$/',
            'address' => 'nullable|max:2000',
            'amount' => 'nullable|numeric',
            'enter_date' => 'nullable|digits:13',
            'description' => 'nullable|max:3000',
        ], [], [
            'code' => 'کد',
            'title' =>  'عنوان',
            'customer_id' => 'مشتری',
            'mobile' => 'شماره تماس',
            'address' => 'آدرس',
            'amount' => 'مقدار محصول',
            'enter_date' => 'تاریخ سند',
            'description' => 'توضیحات',
        ]);
        $product_change = ProductChange::Create([
            'code' => $request->code,
            'title' => $request->title,
            'type' => 'enter',
            'customer_id' => $request->customer_id,
            'mobile' => $request->mobile,
            'address' => $request->address,
            'enter_date' => $request->enter_date ? Carbon::createFromTimestampMs($request->enter_date) : Carbon::now(),
            'created_by' => auth()->user()->id,
            'description' => $request->description,
        ]);
        $items = [];
        $content = Session::get('InputProductChange');

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
        
        event(new ProductChangeCreated($product_change, null, auth()->id()));
        session()->put('InputProductChange',[]); 
        return redirect()->route('panel.inventory.productChanges.index')->with([
            'success' => 'با موفقیت ثبت شد'
        ]);
    }
    
    public function show(ProductChange $enter)
    {
        $this->authorize('view productChanges');

        return view('panel.inventory.product_changes.enter.show')->with([
            'product_change' => $enter,
        ]);
    }

    public function edit($enter)
    {
        $this->authorize('edit productChange');
        $productChange = ProductChange::find($enter);
        $items_session = new EditProductChangeCart($productChange->id);
        $items_session->empty();
        foreach ($productChange->items as $item) {
            $items_session->addByItem($item);
        }
        $items = $items_session->getContent();

        $return_items = $productChange->return_items;

        $users = User::all();
        $customers = Customer::all();
        $products = Product::all();

        return view('panel.inventory.product_changes.enter.edit')->with([
            'product_change' => $productChange,
            'products' => $products,
            'customers' => $customers,
            'users' => $users,
            'items' => $items,
            'return_items' => $return_items
        ]);
    }

    public function update(Request $request,$enter)
    {
        $this->authorize('edit productChange');

        $product_change = ProductChange::find($enter);
        $request->validate([
            'title' => 'required|string|max:255',
            'customer_id' => 'nullable|exists:customers,id',
            'mobile' => 'nullable|numeric|regex:/^09\d{9}$/',
            'address' => 'nullable|max:2000',
            'register_at' => 'nullable',
            'amount' => 'nullable|numeric',
            'enter_date' => 'nullable',
            'description' => 'nullable|max:3000',
        ], [], [
            'title' =>  'عنوان',
            'customer_id' => 'مشتری',
            'mobile' => 'شماره تماس',
            'address' => 'آدرس',
            'register_at' => 'تاریخ ثبت',
            'amount' => 'مقدار محصول',
            'enter_date' => 'تاریخ ورود',
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
            'enter_date' => $request->enter_date ? Carbon::createFromTimestampMs($request->enter_date) : $product_change->enter_date,
            'updated_by' =>  auth()->user()->id,
            'description' => $request->description,
        ]);

        $items_session = new EditProductChangeCart($product_change->id);
        $items_session->sync();
        $product_change->load('items');

        event(new ProductChangeUpdated($product_change, $old_value , auth()->id()));
        $items_session->destroy();


        return redirect()->route('panel.inventory.productChanges.index')->with([
            'success' => 'تغییرات شما با موفقیت ثبت شد'
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
        $cart = new InputProductChange(); 
        $cart->add($request);
        $items = $cart->getContent();
        return view('components.productChange.cartList', [
            'items' => $items
        ])->render();
    }

    public function add(Request $request,ProductChange $productChange)
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
        $cart = new EditProductChangeCart($productChange->id); 
        $cart->addByRequest($request);
        $items = $cart->getContent();
        return view('components.productChange.cartListItem', [
            'items' => $items,
            'product_change' => $productChange,
        ])->render();
    }
   

    public function increaseItemAmount(Request $request)
    {
        $cart = new InputProductChange();
        $cart->increase($request->rowId, 1);
        $items = $cart->getContent();
        return view('components.productChange.cartList', [
            'items' => $items
        ])->render();
    }

    public function decreaseItemAmount(Request $request)
    {
        $cart = new InputProductChange();
        $cart->decrease($request->rowId, 1);
        return view('components.productChange.cartList', [
            'items' => $cart->getContent()
        ])->render();
    }

    public function removeItem(Request $request)
    {
        $cart = new InputProductChange();
        $cart->remove($request->rowId);
        $items = $cart->getContent();
        return view('components.productChange.cartList', [
            'items' => $items
        ])->render();
    }
  

    public function increase(ProductChange $productChange,$rowId)
    {
        $cart = new EditProductChangeCart($productChange->id);
        $cart->increase($rowId, 1);
        $items = $cart->getContent();
        return view('components.productChange.cartListItem', [
            'items' => $items,
            'product_change' => $productChange
        ])->render();
    }

    public function decrease(ProductChange $productChange ,$rowId)
    {
        $cart = new EditProductChangeCart($productChange->id);
        $cart->decrease($rowId, 1);
        $items = $cart->getContent();
        return view('components.productChange.cartListItem', [
            'items' => $items,
            'product_change' => $productChange
        ])->render();
    }

    public function remove(ProductChange $productChange , $rowId)
    {

        $cart = new EditProductChangeCart($productChange->id);
        $cart->remove($rowId);
        $items = $cart->getContent($rowId);
        return view('components.productChange.cartListItem', [
            'items' => $items,
            'product_change' => $productChange
        ])->render();

    }



}
