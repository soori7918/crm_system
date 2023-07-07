<?php

namespace App\Http\Controllers\Panel;

use App\Classes\ReturnProductChange;
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

class ProductChangeReturnController extends Controller
{
    public function create()
    {
        $items = Session::get('InputProductChange');

        $return_items = Session::get('ReturnProductChange');
        $users = User::all();
        $customers = Customer::all();
        $products = Product::all();
        return view('panel.productChangeReturn.create')->with([
            'users' => $users,
            'customers' => $customers,
            'products' => $products,
            'items' => $items,
            'return_items' => $return_items,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'numeric|min:0',
            'title' => 'required|string|max:255',
            'customer_id' => 'required|exists:customers,id',
            'mobile' => 'required|numeric|regex:/^09\d{9}$/',
            'address' => 'required|max:2000',
            'register_at' => 'nullable',
            'amount' => 'nullable|numeric',
            'exit_date' => 'nullable\digits:13',
            'return_date' => 'nullable|digits:13',
            'description' => 'nullable|max:3000',
        ], [], [
            'code' => 'کد',
            'title' =>  'عنوان',
            'amount' => 'مقدار محصول',
            'customer_id' => 'مشتری',
            'exit_date' => '',
            'register_at' => 'تاریخ ثبت',
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
            'exit_date' => $request->exit_date ? Carbon::createFromTimestampMs($request->exit_date) : Carbon::now(),
            'return_date' => $request->return_date ? Carbon::createFromTimestampMs($request->return_date) : Carbon::now(),
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

        event(new ReturnProductChangeCreated($product_change, null, auth()->id()));
        session(['InputProductChange' => []]); 

        return redirect()->route('panel.product_changes.index')->with([
            'success' => 'با موفقیت ثبت شد'
        ]);

    }


    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|max:2000',
            'return_date' => 'nullable',
        ], [], [
            'amount' => 'تعداد',
            'description' => 'توضیحات',
            'return_date' => 'تاریخ برگشت',
        ]);
        if ($validator->fails()) {
            return response([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }
        $cart = new ReturnProductChange(); 
        $cart->add($request);
        $return_items = $cart->getContent();
        return view('components.productChange.returnList', [
            'return_items' => $return_items
        ])->render();
    }


    public function increase(Request $request)
    {
        $cart = new ReturnProductChange();
        $cart->increase($request->rowId, 1);
        $return_items = $cart->getContent();
        return view('components.productChange.returnList', [
            'return_items' => $return_items
        ])->render();
    }

    public function decrease(Request $request)
    {
        $cart = new ReturnProductChange();
        $cart->decrease($request->rowId, 1);
        return view('components.productChange.returnList', [
            'return_items' => $cart->getContent()
        ])->render();
    }
    public function remove(Request $request)
    {
        $cart = new ReturnProductChange();
        $cart->remove($request->rowId);
        $return_items = $cart->getContent();
        return view('components.productChange.returnList', [
            'return_items' => $return_items
        ])->render();
    }

    public function delete_item()
    {
        return "hiiiii";
    }

    public function removeReturnItem(ProductChange $productChange,ReturnItem $return)
    {
        
    }

}
