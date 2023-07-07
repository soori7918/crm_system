<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Factor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $this->authorize('view customers');
        $customers = Customer::orderBy('created_at', 'desc');
        if (request('search')) {
            $customers = $customers->where(function ($query) use ($request) {
                $query->where('name', 'LIKE', "%$request->search%")
                    ->orWhere('phone1', 'LIKE', "%$request->search%")
                    ->orWhere('phone2', 'LIKE', "%$request->search%")
                    ->orWhere('mobile', 'LIKE', "%$request->search%");
            });
        }
        $customers = $customers->paginate();
        $customers->appends(request()->query());
        return view('panel.customers.index')->with([
            'customers' => $customers
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $this->authorize('create customer');

        return view('panel.customers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $this->authorize('create customer');
        $request->validate([
            'name' => 'string|max:255|nullable',
            'mobile' => 'required|regex:/^09\d{9}$/|unique:customers',
            'phone1' => 'nullable|numeric',
            'phone2' => 'nullable|numeric',
            'address' => 'nullable|max:3000',
            'description' => 'nullable|max:3000',
        ], [], [
            'name' => 'نام و نام خانوادگی',
            'mobile' => 'شماره تماس',
            'phone1' => 'شماره همراه',
            'phone2' => 'شماره همراه',
            'address' => 'آدرس',
            'description' => 'توضیحات',

        ]);


        Customer::create([
            'name' => $request->name,
            'mobile' => $request->mobile,
            'phone1' => $request->phone1,
            'phone2' => $request->phone2,
            'address' => $request->address,
            'description' => $request->description,
            'created_by' => Auth::id(),

        ]);

        return \redirect()->route('panel.customers.index')->with([
            'success' => 'مشتری جدید با موفقیت ثبت شد'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        // $this->authorize('view customer');

        return view('panel.customers.show')->with([
            'customer' => $customer
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        // $this->authorize('edit customer');

        return view('panel.customers.edit')->with([
            'customer' => $customer
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        // $this->authorize('edit customer');

        $request->validate([
            'name' => 'string|max:255|nullable',
            'mobile' => 'required|regex:/^09\d{9}$/',
            'phone1' => 'nullable|numeric',
            'phone2' => 'nullable|numeric',
            'address' => 'nullable|max:3000',
            'description' => 'nullable|max:3000',
        ], [], [
            'name' => 'نام و نام خانوادگی',
            'mobile' => 'شماره تماس',
            'phone1' => 'شماره همراه',
            'phone2' => 'شماره همراه',
            'address' => 'آدرس',
            'description' => 'توضیحات',

        ]);


        $customer->update([
            'name' => $request->name,
            'mobile' => $request->mobile,
            'phone1' => $request->phone1,
            'phone2' => $request->phone2,
            'address' => $request->address,
            'description' => $request->description,
            'updated_by' => Auth::id(),

        ]);

        return \redirect()->route('panel.customers.index')->with([
            'success' => 'اطلاعات مشتری مورد نظر با موفقیت به روز رسانی شد'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        // $this->authorize('delete customer');

        $customer->delete();
        return redirect()->route('panel.customers.index')->with([
            'success' => 'مشتری مورد نظر حذف شد'
        ]);
    }

    public function reports(Request $request)
    {
        $customer_count = Customer::count();
        $customer_today = Customer::whereBetween('created_at', [Carbon::today()->addDays(-1), Carbon::today()->addDays(1)])->count();
        $customer_week = Customer::whereBetween('created_at', [Carbon::today()->addDays(-7), Carbon::today()->addDays(7)])->count();
        $customer_month = Customer::whereBetween('created_at', [Carbon::today()->addDays(-30), Carbon::today()->addDays(30)])->count();
        
        $factors = Factor::whereHas('payments', function ($q) {
            $q->where('is_done', false);
        })->get();;
        
        return view('panel.customers.reports')->with([
            'factors' => $factors,
            'customer_today' => $customer_today,
            'customer_week' => $customer_week,
            'customer_month' => $customer_month,
            'customer_count' => $customer_count,
        ]);
    }
}
