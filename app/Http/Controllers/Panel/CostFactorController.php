<?php

namespace App\Http\Controllers\panel;

use App\Classes\CreateCostFactorAddItem;
use App\Classes\CreateCostFactorPayment;
use App\Events\Factors\FactorCreated;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Product;
use App\Models\Factor;
use App\Models\Wallet;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class CostFactorController extends Controller
{
    
    
    public function create()
    {
        $this->authorize('create factor');
        $factors = Session::get('CreateCostFactorAddProduct');
        $items = Session::get('CreateCostFactorAddItem');
        $payments = Session::get('CreateCostFactorPayment');
        $products = Product::all();
        $customers = Customer::all();
        $counter = Factor::count();
        $wallets = Wallet::all();
        return view('panel.costFactors.create')->with([
            'customers' => $customers,
            'products' => $products,
            'counter' => $counter,
            'wallets' => $wallets,
            'factors' => $factors,
            'items' => $items,
            'payments' => $payments
        ]);
        
    }

    
    public function store(Request $request)
    {
        $this->authorize('create factor');

        $request->validate([
            'enter_date' => 'required',
            'customer_id' => 'nullable|numeric|exists:customers,id',
            'mobile' => 'nullable|numeric|regex:/^09\d{9}$/',
            'address' => 'nullable|max:3000',
            'description' => 'nullable|max:2000',
            'wallet_id' => 'required|numeric|exists:wallets,id',
            'discount' => 'nullable|numeric'
        ],[],[
            'enter_date' => 'تاریخ ثبت',
            'customer_id' => 'مشتری',
            'mobile' => 'شماره تماس',
            'address' => 'آدرس',
            'description' => 'توضیحات',
            'wallet_id' => 'صندوق',
            'discount' => 'تخفیف'
        ]);

        //create factor
        $factor = Factor::create([
            'code' => $request->code,
            'date' => $request->enter_date ? Carbon::createFromTimestampMs($request->enter_date) : Carbon::now(),
            'customer_id' => $request->customer_id,
            'mobile' => $request->mobile,
            'address' => $request->address,
            'description' => $request->description,
            'wallet_id' => $request->wallet_id,
            'discount' => $request->discount,
            'type' => 'output',
            'title' => 'output',
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);
        //create factor item for product
        $product_items = [];
        $products = session()->get('CreateCostFactorAddProduct');

        foreach ($products ?: [] as $item) {
            $product_items[] = [
                'title' => 'sabt',
                'model_id' => $item['product_id'],
                'model_type' => $item['model_type'],
                'price' => $item['price'],
                'amount' => $item['amount'],
                'description' => $item['description'],
            ];
        }
        foreach ($product_items ?: [] as $product_item) {
            $factor->items()->create($product_item);
        }
        //create factor item for item
        $factor_items = [];
        $items = session()->get('CreateCostFactorAddItem');

        foreach ($items ?: [] as $item) {
            $factor_items[] = [
                'title' => $item['title'],
                'price' => $item['price'],
                'amount' => $item['amount'],
                'description' => $item['description'],
            ];
        }

        foreach ($factor_items ?: [] as $factor_item) {
            $factor->items()->create($factor_item);
        }
        // create factor payment item
        $factor_payments = [];
        $payments =session()->get('CreateCostFactorPayment');
        foreach ($payments ?: [] as $factor_payment) {
            $factor_payments[] = [
                'price' => $factor_payment['price'],
                'type' => $factor_payment['type'],
                'date' => $factor_payment['date'],
                'is_done' => $factor_payment['is_done'],
            ];
        }
        foreach ($factor_payments ?: [] as $factor_payment) {
            $factor->payments()->create($factor_payment);
        }
        $factor->load('items');
        $factor->load('payments');
        event(new FactorCreated($factor,null,auth()->id()));


        Session(['CreateCostFactorAddProduct' => []]);
        Session(['CreateCostFactorAddItem' => []]);
        Session(['CreateCostFactorPayment' => []]);

        return redirect()->route('panel.factors.index')->with([
            'success' => 'فاکتور مورد نظر با موفقیت ایجاد شد'
        ]);      


    }


    public function addItem(Request $request)
    {
      
        $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'amount' => 'nullable|numeric',
                'price' => 'nullable|numeric',
                'description' => 'nullable|max:3000',
            ], [], [
                'title' => 'عنوان',
                'price' => 'قیمت',
                'amount' => 'تعداد',
                'description' => 'توضیحات'
            
        ]);

        if ($validator->fails()) {
            return response([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }
        $cart = new CreateCostFactorAddItem();
        $cart->add($request);
        $items = $cart->getContent();
        return view('components.factor.factorListItem', [
            'items' => $items
        ])->render();      
        
    }


    public function removeItem(Request $request)
    {
        $cart = new CreateCostFactorAddItem();
        $cart->remove($request->rowId);
        $items = $cart->getContent();
        return view('components.factor.factorItems', [
            'items' => $items
        ])->render();
    }



    public function addPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
                'price' => 'required|numeric|min:0',
                'type' => 'required|string|max:255',
                'register_date' => 'required',
                'is_done' => 'required',
            ], [], [
                'price' => 'مبلغ',
                'type' => 'نوع پرداخت',
                'register_date' => 'تاریخ پرداخت',
                'is_done' => 'تاریخ پرداخت',
        ]);

        if ($validator->fails()) {
            return response([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }
        $cart = new CreateCostFactorPayment();
        $cart->add($request);

        $payments = $cart->getContent();
      
        return view('components.factor.factorPayments', [
            'payments' => $payments
        ])->render();      
        
    }

    public function removePayment(Request $request)
    {
        $cart = new CreateCostFactorPayment();
        $cart->remove($request->rowId);
        $payments = $cart->getContent();
        return view('components.factor.factorPayment', [
            'payments' => $payments
        ])->render();
    }

    public function increase(Request $request)
    {
        $cart = new CreateCostFactorAddItem();
        $cart->increase($request->rowId, 1);
        $content = $cart->getContent();
        return view('components.factor.factorItems', [
            'items' => $content
        ])->render();
    }
 
    public function decrease(Request $request)
    {
        $cart = new CreateCostFactorAddItem();
        $cart->decrease($request->rowId, 1);
        return view('components.factor.factorItems', [
            'items' => $cart->getContent()
        ])->render();
    }
}
