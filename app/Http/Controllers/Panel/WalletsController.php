<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Factor;
use App\Models\Wallet;
use App\Models\WalletChange;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class WalletsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view wallets');

        $wallets = Wallet::orderBy('created_at', 'desc');

        if(!empty($request->search)){
            $wallets = $wallets->where('title', 'like' , "%$request->search%");
        }
        $wallets = $wallets->paginate();
        $wallets->appends($request->query());

        return view('panel.wallets.index')->with([
            'wallets' => $wallets,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create wallet');

        return view('panel.wallets.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create wallet');

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|max:1000',
        ], [], [
            'title' => 'عنوان',
            'description' => 'توضیحات',
        ]);

        Wallet::create([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return \redirect()->route('panel.wallets.index')->with([
            'success' => 'با موفقیت ثبت شد'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Wallet $wallet)
    {
        $this->authorize('view wallets');

        return view('panel.wallets.show')->with([
            'wallet' => $wallet
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Wallet $wallet)
    {
        $this->authorize('edit wallet');

        return view('panel.wallets.edit')->with([
            'wallet' => $wallet
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Wallet $wallet)
    {
        $this->authorize('edit wallet');

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|max:1000',
        ], [], [
            'title' => 'عنوان',
            'description' => 'توضیحات',
        ]);

        $wallet->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return \redirect()->route('panel.wallets.index')->with([
            'success' => 'تغییرات با موفقیت ثبت شد'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wallet $wallet)
    {
        $this->authorize('delete wallet');

        if($wallet->amount == null )
        {
            $wallet->delete();
        }else{
            return redirect()->route('panel.wallets.index')->with([
                'danger' => 'صندوق دارای موجودی است متاسفانه نمیتوان حدف کرد'
            ]);
        }
        return redirect()->route('panel.wallets.index')->with([
            'success' => 'با موفقیت حذف شد'
        ]);
    }
    
    public function showReport(Request $request)
    {
       
        $wallets = Wallet::all();

        $wallet_changes = WalletChange::where('wallet_id' , '!=' , null);
        
         if($request->wallets)
        {
           $wallet_changes = $wallet_changes->where('wallet_id' , $request->wallets);
        }
        if($request->start_date)
        {
           $wallet_changes = $wallet_changes->where('created_at' , '>', $request->start_date ? Carbon::createFromTimestampMs($request->start_date): '');
        }
        if($request->end_date)
        {
           $wallet_changes = $wallet_changes->where('created_at' , '<', $request->end_date ?  Carbon::createFromTimestampMs($request->end_date): '' );
        }
        if($request->type_factor)
        {
           $wallet_changes = $wallet_changes->where('type' ,  $request->type_factor);
        }
        if($request->sort_by && $request->sort_by_type)
        {
            switch($request->sort_by)
            {
                case 'price':
                    {
                        $wallet_changes = $wallet_changes->orderBy('price' , $request->sort_by_type);
                    }
                case 'date':
                    {
                        $wallet_changes = $wallet_changes->orderBy('created_at' ,$request->sort_by_type);

                    }default:{
                        $wallet_changes = $wallet_changes->orderBy('created_at' ,'asc');

                    }
            }

        }

        $wallet_changes = $wallet_changes->paginate();
        $wallet_changes->appends($request->query());



        

        return view('panel.wallets.report')->with([
            'wallets' => $wallets,
            'wallet_changes' => $wallet_changes
        ]);
    }
}
