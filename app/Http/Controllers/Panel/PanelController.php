<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Factor;
use App\Models\User;
use App\Models\FactorPayment;
use App\Models\ProductChange;
use App\Models\Customer;
use App\Models\ProductChangeItem;
use App\Models\ReturnItem;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB as FacadesDB;

class PanelController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = Auth::user();

        $month_income = null;
        $month_cost = null;
        $month_incoming_products = null;
        $month_outgoing_products = null;

        if($user->can('view factors')) {

            $month_income = FactorPayment::where('is_done',true)
                ->where('date','>',Carbon::today()->addDays(-30))
                ->whereHas('factor', function($q) {
                    $q->where('type','input');
                })->sum('price');

            $month_cost = FactorPayment::where('is_done',true)
                ->where('date','>',Carbon::today()->addDays(-30))
                ->whereHas('factor', function($q) {
                    $q->where('type','output');
                })->sum('price');
        }

        if($user->can('view productChanges')) {

            $month_incoming_products = ReturnItem::where('is_done',true)
                ->where('return_date','>',Carbon::today()->addDays(-30))
                ->sum('amount');

            $month_incoming_products += ProductChangeItem::whereHas('document',function($q) {
                    $q->whereIn('type',['enter'])->where('enter_date','>',Carbon::today()->addDays(-30));
                })
                ->sum('amount');

            $month_outgoing_products = ProductChangeItem::whereHas('document',function($q) {
                    $q->whereIn('type',['exit','return'])->where('exit_date','>',Carbon::today()->addDays(-30));
                })
                ->sum('amount');
        }


        $input_factor = '';
        $input_factor_month = '';
        $input_factor_week = '';
        $output_factor = '';
        $output_factor_week = '';
        $output_factor_month = '';

        $out_of_stock_products = Product::where('amount', 0)->orderBy('created_at','asc')->take(10)->get();

        $none_return_products = ProductChange::where('type', 'return')->whereHas('return_items', function ($q) {
            $q->where('is_done', false);
        })->get();

        $last_factors = Factor::orderby('created_at', 'desc')->take(10)->get();

        $non_payment_factors = Factor::whereHas('payments', function ($q) {
            $q->where('is_done', false);
        })->get();

        $last_payments = FactorPayment::orderBy('created_at', 'desc')->where('is_done', true)->take(10)->get();
        $last_unpaid_payments = FactorPayment::orderBy('created_at', 'desc')->where('is_done', false)->take(10)->get();
        $last_product_changes = ProductChange::orderBy('created_at', 'desc')->take(10)->get();

        $sum_price_wallets = Wallet::all()->sum('amount');


        $kol_price = FactorPayment::where('is_done', true)->sum('price');



        if ($request->wallet_by_date) {
            switch ($request->wallet_by_date) {
                case 'week': {
                        $input_factor_week = Factor::where('type', 'input')->withCount(['payments as price' => function ($query) {
                            $query->select(DB::raw('sum(price)'))->where('is_done', true)
                                ->whereBetween('created_at', [Carbon::today()->addDays(-7), Carbon::today()->addDays(7)]);
                        }])->get();
                        $output_factor_week = Factor::where('type', 'output')->withCount(['payments as price' => function ($query) {
                            $query->select(DB::raw('sum(price)'))->where('is_done', true)
                                ->whereBetween('created_at', [Carbon::today()->addDays(-7), Carbon::today()->addDays(7)]);
                        }])->get();
                    }
                    break;
                case 'month': {
                        $input_factor_month = Factor::where('type', 'input')->withCount(['payments as price' => function ($query) {
                            $query->select(DB::raw('sum(price)'))->where('is_done', true)
                                ->whereBetween('created_at', [Carbon::today()->addDays(-30), Carbon::today()->addDays(30)]);
                        }])->get();
                        $output_factor_month = Factor::where('type', 'output')->withCount(['payments as price' => function ($query) {
                            $query->select(DB::raw('sum(price)'))->where('is_done', true)
                                ->whereBetween('created_at', [Carbon::today()->addDays(-30), Carbon::today()->addDays(30)]);
                        }])->get();
                    }
                    break;
                default:
                    $input_factor = Factor::where('type', 'input')->withCount(['payments as price' => function ($query) {
                        $query->select(DB::raw('sum(price)'))->where('is_done', true);
                    }])->get();
                    $output_factor = Factor::where('type', 'output')->withCount(['payments as price' => function ($query) {
                        $query->select(DB::raw('sum(price)'))->where('is_done', true);
                    }])->get();
            }
        }


        $input_factor_week = Factor::where('type', 'input')->withCount(['payments as price' => function ($query) {
            $query->select(DB::raw('sum(price)'))->where('is_done', true)
            ->whereBetween('created_at', [Carbon::today()->addDays(-7), Carbon::today()->addDays(7)]);
        }])->get();
        $input_factor_week = $input_factor_week->sum('price');
        $output_factor_week = Factor::where('type', 'output')->withCount(['payments as price' => function ($query) {
            $query->select(DB::raw('sum(price)'))->where('is_done', true)
                ->whereBetween('created_at', [Carbon::today()->addDays(-7), Carbon::today()->addDays(7)]);
        }])->get();
        $output_factor_week = $output_factor_week->sum('price');


        $inventory_info = [
            'all' => Product::sum('amount'),
            'return' => Product::sum('return_amount'),
        ];
        $inventory_info['instock'] = $inventory_info['all'] - $inventory_info['return'];

        return view('panel.dashboard')->with([
            'month_income' => $month_income,
            'month_cost' => $month_cost,
            'month_incoming_products' => $month_incoming_products,
            'month_outgoing_products' => $month_outgoing_products,

            'out_of_stock_products' => $out_of_stock_products,
            'none_return_products' => $none_return_products,
            'last_factors' => $last_factors,
            'non_payment_factors' => $non_payment_factors,
            'last_payments' => $last_payments,
            'last_unpaid_payments' => $last_unpaid_payments,
            'last_product_changes' => $last_product_changes,
            'input_factor' => $input_factor,
            'input_factor_week' => $input_factor_week,
            'input_factor_month' => $input_factor_month,
            'output_factor' => $output_factor,
            'output_factor_week' => $output_factor_week,
            'output_factor_month' => $output_factor_month,
            'kol_price' => $kol_price,
            'inventory_info' => $inventory_info,
        ]);
    }

    public function changeWalletChart(Request $request)
    {
        if ($request->wallet_by_date) {
            switch ($request->wallet_by_date) {
                case 'week': {
                        $input_factor_week = Factor::where('type', 'input')->withCount(['payments as price' => function ($query) {
                            $query->select(DB::raw('sum(price)'))->where('is_done', true)
                            ->whereBetween('created_at', [Carbon::today()->addDays(-7), Carbon::today()->addDays(7)]);
                        }])->get();
                        $input_factor_week = $input_factor_week->sum('price');
                        $output_factor_week = Factor::where('type', 'output')->withCount(['payments as price' => function ($query) {
                            $query->select(DB::raw('sum(price)'))->where('is_done', true)
                                ->whereBetween('created_at', [Carbon::today()->addDays(-7), Carbon::today()->addDays(7)]);
                        }])->get();
                        $output_factor_week = $output_factor_week->sum('price');
                        return response([
                            'input_factor_week' => $input_factor_week,
                            'output_factor_week' => $output_factor_week
                        ]);
                    }
                    break;
                case 'month': {
                        $input_factor_month = Factor::where('type', 'input')->withCount(['payments as price' => function ($query) {
                            $query->select(DB::raw('sum(price)'))->where('is_done', true)
                                ->whereBetween('created_at', [Carbon::today()->addDays(-30), Carbon::today()->addDays(30)]);
                        }])->get();
                        $output_factor_month = Factor::where('type', 'output')->withCount(['payments as price' => function ($query) {
                            $query->select(DB::raw('sum(price)'))->where('is_done', true)
                                ->whereBetween('created_at', [Carbon::today()->addDays(-30), Carbon::today()->addDays(30)]);
                        }])->get();
                        $input_factor_month = $input_factor_month->sum('price');
                        $output_factor_month = $output_factor_month->sum('price');
                        return response([
                            'input_factor_month' => $input_factor_month,
                            'output_factor_month' => $output_factor_month
                        ]);
                    }
                    break;
                default:
                    $input_factor = Factor::where('type', 'input')->withCount(['payments as price' => function ($query) {
                        $query->select(DB::raw('sum(price)'))->where('is_done', true);
                    }])->get();
                    $output_factor = Factor::where('type', 'output')->withCount(['payments as price' => function ($query) {
                        $query->select(FacadesDB::raw('sum(price)'))->where('is_done', true);
                    }])->get();
            }
        }
    }
}
