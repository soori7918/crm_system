<?php

namespace App\Http\Controllers\Panel;

use App\Classes\ExitProductChange;
use App\Events\ProductChange\ProductChangeCreated;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductChange;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ExitDocumentController extends Controller
{
    public function create()
    {
        $items = Session::get('ExitProductChange');
        $users = User::all();
        $customers = Customer::all();
        $products = Product::all();
        return view('panel.exit_document.create')->with([
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
            'type' => 'exit',
            'customer_id' => $request->customer_id,
            'mobile' => $request->mobile,
            'address' => $request->address,
            'exit_date' => $request->exit_date ? Carbon::createFromTimestampMs($request->exit_date) : Carbon::now(),
            'return_date' => $request->return_date ? Carbon::createFromTimestampMs($request->return_date) : Carbon::now(),
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

        return redirect()->route('panel.product_changes.index')->with([
            'success' => 'با موفقیت ثبت شد'
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
        $cart = new ExitProductChange(); 
        $cart->add($request);
        $items = $cart->getContent();
        return view('components.productChange.cartList', [
            'items' => $items
        ])->render();
    }
    public function increase(Request $request)
    {
        $cart = new ExitProductChange();
        $cart->increase($request->rowId, 1);
        $items = $cart->getContent();
        return view('components.productChange.cartList', [
            'items' => $items
        ])->render();
    }

    public function decrease(Request $request)
    {
        $cart = new ExitProductChange();
        $cart->decrease($request->rowId, 1);
        return view('components.productChange.cartList', [
            'items' => $cart->getContent()
        ])->render();
    }
    public function remove(Request $request)
    {
        $cart = new ExitProductChange();
        $cart->remove($request->rowId);
        $items = $cart->getContent();
        return view('components.productChange.cartList', [
            'items' => $items
        ])->render();
    }

}
