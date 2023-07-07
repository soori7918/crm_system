<?php

namespace App\Http\Controllers\Panel;

use App\Classes\CreateFactorItem;
use App\Classes\CreateFactorPayment;
use App\Classes\EditFactorItem;
use App\Classes\EditFactorPayment;
use App\Events\Factors\FactorCreated;
use App\Events\Factors\FactorDeleted;
use App\Events\Factors\FactorUpdated;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Factor;
use App\Models\Log;
use App\Models\Wallet;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class FactorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view factors');

        $factors = Factor::orderby('created_at', 'desc')->paginate();
        return view('panel.factors.index')->with([
            'factors' => $factors
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create factor');

        $items = Session::get('CreateFactorItem');
        $payments = Session::get('CreateFactorPayment');
        $products = Product::all();
        $customers = Customer::all();
        $counter = Factor::count();
        $wallets = Wallet::all();
        return \view('panel.factors.create')->with([
            'customers' => $customers,
            'products' => $products,
            'counter' => $counter,
            'wallets' => $wallets,
            'items' => $items,
            'payments' => $payments
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
            'type' => 'input',
            'title' => 'input',
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);
       
        //create factor item for item
        $factor_items = [];
        $items = session()->get('CreateFactorItem');

        foreach ($items ?: [] as $item) {
            $factor_items[] = [
                'product_id' => $item['product_id'],
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
        $payments =session()->get('CreateFactorPayment');
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

        $factor->load('items' , 'payments');
        event(new FactorCreated($factor,null,auth()->id()));

        Session(['CreateFactorItem' => []]);
        Session(['CreateFactorPayment' => []]);

        return redirect()->route('panel.factors.index')->with([
            'success' => 'فاکتور مورد نظر با موفقیت ایجاد شد'
        ]);      
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Factor $factor)
    {
        $this->authorize('view factors');

        return view('panel.factors.show')->with([
            'factor' => $factor,
            'payments' => $factor->payments,
            'items' => $factor->items
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,Factor $factor)
    {
        $this->authorize('edit factor');

        $items_session = new EditFactorItem(null,$factor->id);
        $items_session->destroy();
        foreach ($factor->items as $item) {
            $items_session->add($item);
        }
        $items = $items_session->getContent();

        //payments
        $payments_session = new EditFactorPayment($factor->id);
        $payments_session->empty();
        foreach ($factor->payments as $item) {
            $payments_session->addByItem($item);
        }

        $customers = Customer::all();
        $wallets = Wallet::all();
        $products = Product::all();

        return view('panel.factors.edit')->with([
            'factor' => $factor,
            'items' => $items,
            'payments' => $payments_session->getContent(),
            'customers' => $customers,
            'wallets' => $wallets,
            'products' => $products
        ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Factor $factor)
    {
        $this->authorize('edit factor');

        $request->validate([
            'enter_date' => 'nullable|digits:13',
            'title' => 'required|string|max:255',
            'customer_id' => 'required|numeric|exists:customers,id',
            'mobile' => 'required|numeric|regex:/^09\d{9}$/',
            'address' => 'required|max:3000',
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

        $old_value = clone $factor;
        $old_value->load('payments');

        $factor->update([
            'code' => $request->code,
            'date' => $request->enter_date ? Carbon::createFromTimestampMs($request->enter_date) : null,
            'customer_id' => $request->customer_id,
            'mobile' => $request->mobile,
            'address' => $request->address,
            'description' => $request->description,
            'wallet_id' => $request->wallet_id,
            'discount' => $request->discount,
            'type' => $request->type,
            'title' => $request->title,
            'created_by' => $factor->user_id,
            'updated_by' => auth()->user()->id,
        ]);

        // create factor item for product
        $items_session = new EditFactorItem(null, $factor->id);
        $items = $items_session->getContent();
        foreach ($items ?: [] as $item) {
            $factor->items()->create([
                'title' => $item['title'],
                'product_id' => $item['product_id'],
                'price' => $item['price'],
                'amount' => $item['amount'],
                'description' => $item['description'],
            ]);
        }

        // //payments
        
        $payments_session = new EditFactorPayment($factor->id);
        $payments_session->sync();
        $factor->load('payments');


        event(new FactorUpdated($factor, $old_value , auth()->id()));
       

        $items_session->destroy();
        $payments_session->destroy();

        return redirect()->route('panel.factors.index')->with([
            'success' => 'تغییرات شما با موفقیت ثبت شد'
        ]);   
      

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Factor $factor)
    {
        $this->authorize('edit factor');

        $factor->load('items');
        $factor->load('payments');
        event(new FactorDeleted(null, $factor , auth()->id()));
        $factor->items()->delete();
        $factor->payments()->delete();
        $factor->delete();
        return redirect()->back()->with([
            'success' => 'اطلاعات شما با موفقیت حذف گرید'
        ]);        
    }

    public function addItem(Request $request)
    {
      
        $validator = Validator::make($request->all(), [
                'product_id' => 'nullable|exists:products,id',
                'title' => 'required|string|max:255',
                'amount' => 'nullable|numeric',
                'price' => 'nullable|numeric',
                'description' => 'nullable|max:3000',
            ], [], [
                'product_id' => 'محصول',
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
        $cart = new CreateFactorItem();
        $cart->add($request);

        $items = $cart->getContent();
        
        return view('components.factor.factorItems', [
            'items' => $items
        ])->render();      
        
    }

    public function removeItem(Request $request)
    {
        $cart = new CreateFactorItem();
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
        $cart = new CreateFactorPayment();
        $cart->add($request);
        $payments = $cart->getContent();
        return view('components.factor.factorPayments', [
            'payments' => $payments
        ])->render();      
        
    }

    public function removePayment(Request $request)
    {
        $cart = new CreateFactorPayment();
        $cart->remove($request->rowId);
        $payments = $cart->getContent();
        return view('components.factor.factorPayments', [
            'payments' => $payments
        ])->render();
    }

    public function EditFactorIncrease(Factor $factor, $rowId)
    {   
        $cart = new EditFactorItem(null , $factor->id);
        $cart->increase($rowId,1);
        $content = $cart->getContent();
        return view('components.factor.factorListItem', [
            'items' => $content,
            'factor' => $factor
        ])->render();
    }

    public function EditFactorDecrease(Factor $factor,$rowId)
    {
        $cart = new EditFactorItem(null , $factor->id);
        $cart->decrease($rowId,1);
        $content = $cart->getContent();
        return view('components.factor.factorListItem', [
            'items' => $content,
            'factor' => $factor
        ])->render();
    }

    public function EditAddItem(Request $request, Factor $factor)
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
        $cart = new EditFactorItem(null,$factor->id);
        $cart->addByRequest($request);

        $items = $cart->getContent();
        
        return view('components.factor.factorListItem', [
            'items' => $items,
            'factor' => $factor
        ])->render();      
        
    }

    public function EditRemoveItem( Factor $factor , $rowId)
    {
        $cart = new EditFactorItem(null,$factor->id);
        $cart->remove($rowId);
        $items = $cart->getContent();
        return view('components.factor.factorListItem', [
            'items' => $items,
            'factor' => $factor
        ])->render();
    }

    public function EditAddPayment(Request $request,Factor $factor)
    {
        $validator = Validator::make($request->all(), [
                'amount' => 'nullable|numeric',
                'price' => 'nullable|numeric',
                'description' => 'nullable|max:3000',
            ], [], [
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
        $cart = new EditFactorPayment($factor->id);
        $cart->addByRequest($request);
        $payments = $cart->getContent();
        return view('components.factor.factorPayment', [
            'payments' => $payments,
            'factor' => $factor
        ])->render();  

    }
    public function EditPayment(Request $request,Factor $factor)
    {
        $validator = Validator::make($request->all(), [
                'amount' => 'nullable|numeric',
                'price' => 'nullable|numeric',
                'description' => 'nullable|max:3000',
            ], [], [
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

        $cart = new EditFactorPayment($factor->id);
        // $cart->find($request->rowId);
        $cart->editByRequest($request);
        $payments = $cart->getContent();
        return view('components.factor.factorPayment', [
            'payments' => $payments,
            'factor' => $factor
        ])->render();  

    }
    
    public function EditRemovePayment(Factor $factor,$rowId)
    {
        $cart = new EditFactorPayment($factor->id);
        $cart->remove($rowId);
        $payments = $cart->getContent();
        return view('components.factor.factorPayment', [
            'payments' => $payments,
            'factor' => $factor
        ])->render();
    }

    public function increase(Request $request)
    {
        $cart = new CreateFactorItem();
        $cart->increase($request->rowId, 1);
        $items = $cart->getContent();
        return view('components.factor.factorItems', [
            'items' => $items
        ])->render();
    }
 
    public function decrease(Request $request)
    {
        $cart = new CreateFactorItem();
        $cart->decrease($request->rowId, 1);
        return view('components.factor.factorItems', [
            'items' => $cart->getContent()
        ])->render();
    }

  
    public function history(Factor $factor)
    {
        $logs = $factor->logs()->paginate(10);
        return view('panel.factors.history')->with([
            'factor' => $factor,
            'logs' => $logs
        ]);
    }

    public  function delete_reports(Log $log)
    {
        $log->delete();
        return \redirect()->back()->with([
            'success' => 'حذف شد'
        ]);
    }
   
}
