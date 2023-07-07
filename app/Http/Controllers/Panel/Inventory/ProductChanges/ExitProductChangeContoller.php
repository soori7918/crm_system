<?php

namespace App\Http\Controllers\Panel\Inventory\ProductChanges;

use App\Classes\EditProductChangeCart;
use App\Classes\ExitProductChange;
use App\Events\ProductChange\ProductChangeCreated;
use App\Events\ProductChange\ProductChangeUpdated;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductChange;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ExitProductChangeContoller extends Controller
{
    public function show(ProductChange $exit)
    {
        $this->authorize('view productChanges');
        return view('panel.inventory.product_changes.exit.show')->with([
            'product_change' => $exit,
        ]);
    }
    
    public function create()
    {
        $this->authorize('create productChange');

        $items = Session::get('ExitProductChange');
        $users = User::all();
        $customers = Customer::all();
        $products = Product::all();
        return view('panel.inventory.product_changes.exit.create')->with([
            'users' => $users,
            'customers' => $customers,
            'products' => $products,
            'items' => $items,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create productChange');

        $request->validate([
            'code' => 'numeric|min:0',
            'title' => 'required|string|max:255',
            'customer_id' => 'nullable|exists:customers,id',
            'mobile' => 'nullable|numeric|regex:/^09\d{9}$/',
            'address' => 'nullable|max:2000',
            'register_at' => 'nullable',
            'amount' => 'nullable|numeric',
            'exit_date' => 'nullable|digits:13',
            // 'return_date' => 'nullable|digits:13',
            'description' => 'nullable|max:3000',
        ], [], [
            'code' => 'کد',
            'title' =>  'عنوان',
            'amount' => 'مقدار محصول',
            'customer_id' => 'مشتری',
            'exit_date' => '',
            'register_at' => 'تاریخ ثبت',
            // 'return_date' => 'تاریخ برگشت',
            'description' => 'توضیحات',
        ]);

        $product_change = ProductChange::Create([
            'code' => $request->code,
            'title' => $request->title,
            'type' => 'exit',
            'customer_id' => $request->customer_id,
            'mobile' => $request->mobile,
            'address' => $request->address,
            'exit_date' => $request->exit_date ? Carbon::createFromTimestampMs($request->exit_date) : Carbon::now(),
            // 'return_date' => $request->return_date ? Carbon::createFromTimestampMs($request->return_date) : Carbon::now(),
            'created_by' => auth()->user()->id,
            'description' => $request->description,
        ]);

        $items = [];
        $content = Session::get('ExitProductChange');
        foreach ($content as $item) {
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

        Session(['ExitProductChange' => []]);

        return redirect()->route('panel.inventory.productChanges.index')->with([
            'success' => 'با موفقیت ثبت شد'
        ]);
    }

    public function edit($exit)
    {
        $this->authorize('edit productChange');
        $productChange = ProductChange::find($exit);

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

        return view('panel.inventory.product_changes.exit.edit')->with([
            'product_change' => $productChange,
            'products' => $products,
            'customers' => $customers,
            'users' => $users,
            'items' => $items,
            'return_items' => $return_items
        ]);
    }
    
    public function update(Request $request,  $exit)
    {
        $this->authorize('edit productChange');

        $product_change = ProductChange::find($exit);
        $request->validate([
            'title' => 'required|string|max:255',
            'customer_id' => 'nullable|exists:customers,id',
            'mobile' => 'nullable|numeric|regex:/^09\d{9}$/',
            'address' => 'nullable|max:2000',
            'register_at' => 'nullable',
            'amount' => 'nullable|numeric',
            'exit_date' => 'nullable|digits:13',
            // 'return_date' => 'nullable|digits:13',
            'description' => 'nullable|max:3000',
        ], [], [
            'title' =>  'عنوان',
            'customer_id' => 'مشتری',
            'mobile' => 'شماره تماس',
            'address' => 'آدرس',
            'register_at' => 'تاریخ ثبت',
            'amount' => 'مقدار محصول',
            'exit_date' => 'تاریخ',
            // 'return_date' => 'تاریخ برگشت',
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
            'exit_date' => $request->exit_date ? Carbon::createFromTimestampMs($request->exit_date) : $product_change->exit_date,
            // 'return_date' => $request->return_at ? Carbon::createFromTimestampMs($request->return_at) : $product_change->return_date,
            // 'return_date' => $request->return_date ? Carbon::createFromTimestampMs($request->return_date) : $product_change->return_date,
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

        $cart = new ExitProductChange(); 
        $cart->add($request);
        $items = $cart->getContent();
        return view('components.productChange.exit.cartList', [
            'items' => $items
        ])->render();
    }

    public function increaseItemAmount(Request $request)
    {
        $cart = new ExitProductChange();
        $cart->increase($request->rowId, 1);
        $items = $cart->getContent();
        return view('components.productChange.exit.cartList', [
            'items' => $items
        ])->render();
    }

    public function decreaseItemAmount(Request $request)
    {
        $cart = new ExitProductChange();
        $cart->decrease($request->rowId, 1);
        return view('components.productChange.exit.cartList', [
            'items' => $cart->getContent()
        ])->render();
    }

    public function removeItem(Request $request)
    {
        $cart = new ExitProductChange();
        $cart->remove($request->rowId);
        $items = $cart->getContent();
        return view('components.productChange.exit.cartList', [
            'items' => $items
        ])->render();
    }


    
    public function increase(ProductChange $productChange ,$rowId)
    {
        $cart = new EditProductChangeCart($productChange->id);
        $cart->increase($rowId, 1);
        $items = $cart->getContent();
        return view('components.productChange.exit.cartListItem', [
            'items' => $items,
            'product_change' => $productChange
        ])->render();
    }

    public function decrease(ProductChange $productChange ,$rowId)
    {
        $cart = new EditProductChangeCart($productChange->id);
        $cart->decrease($rowId, 1);
        $items = $cart->getContent();
        return view('components.productChange.exit.cartListItem', [
            'items' => $items,
            'product_change' => $productChange
        ])->render();
    }

    public function remove(ProductChange $productChange , $rowId)
    {
        $cart = new EditProductChangeCart($productChange->id);
        $cart->remove($rowId);
        $items = $cart->getContent($rowId);
        return view('components.productChange.exit.cartListItem', [
            'items' => $items,
            'product_change' => $productChange
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
        return view('components.productChange.exit.cartListItem', [
            'items' => $items,
            'product_change' => $productChange,
        ])->render();
    }

}
