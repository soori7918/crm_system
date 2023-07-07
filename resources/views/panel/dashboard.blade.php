@extends('panel.layouts.master')

@section('head')
    <style>
        #chart-container  {
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }
        #chart-container canvas {
            width: 200px !important;
            height: 200px !important;
        }
    </style>
@endsection

@section('content')

<div class="content-wrapper">
    
    <div class="content-header">
        <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-12">
            <h1 class="m-0 text-dark">پیشخوان</h1>
            </div>
        </div>
        </div>
    </div>

    <div class="content">
        <div class="row no-gutters">
            <div class="col-12 p-2 col-lg-6">
                <div class="row">
                    @can('view factors')
                        <div class="col-6">
                            <div class="small-box bg-success">
                                <div class="inner fa-num">
                                    <h3>{{$month_income}}</h3>
                                    <p>درآمد 30 روز اخیر</p>
                                </div>
                                <div class="icon">
                                    <i class="fal fa-wallet"></i>
                                </div>
                            </div>
                        </div>
                    @endcan
    
                    @can('view factors') 
                        <div class="col-6">
                            <div class="small-box bg-danger">
                                <div class="inner fa-num">
                                    <h3>{{$month_cost}}</h3>
                                    <p>هزینه 30 روز اخیر</p>
                                </div>
                                <div class="icon">
                                    <i class="fal fa-wallet"></i>
                                </div>
                            </div>
                        </div>
                    @endcan
    
                    @can('view productChanges')
                        <div class="col-6">
                            <div class="small-box bg-primary">
                                <div class="inner fa-num">
                                    <h3>{{$month_incoming_products}}</h3> 
                                    <p>محصولات ورودی 30 روز اخیر</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-box" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    @endcan
    
                    @can('view productChanges')
                        <div class="col-6">
                            <div class="small-box bg-warning">
                                <div class="inner fa-num">
                                    <h3>{{$month_outgoing_products}}</h3> 
                                    <p>محصولات خروجی 30 روز اخیر</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-box" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    @endcan
    
                </div>
            </div>
            <div class="col-12 p-2 col-lg-6">
                @can('view wallets')
                    <div class="bg-white rounded shadow-sm shadow-hover mb-3 p-3">
                        <div class="pb-2 mb-3 border-bottom">
                            <strong>نسبت درآمد به هزینه</strong>
                        </div>
                        <div class="row">
                            <div class="col-5" >
                                <form action="" id="wallet_sort" class="d-flex h-100 flex-column justify-content-between" method="get">
                                    <input type="hidden" name="darmad1" id="daramad1">
                                    <input type="hidden" name="hazine1" id="hazine1">

                                    <input type="hidden" name="darmad2" id="daramad2">
                                    <input type="hidden" name="hazine2" id="hazine2">

                                    <input type="hidden"  id="week_input" value="{{$input_factor_week}}">
                                    <input type="hidden"  id="week_output" value="{{$output_factor_week}}">

                                    <div>
                                        <strong>مبلغ کل :</strong>
                                        <span id="kol_price" class="fa-num">{{$kol_price}} تومان</span>
                                    </div>
                                    <div>
                                        <strong>درآمد :</strong>
                                        <span id="daramad" class="fa-num">{{$input_factor_week}} تومان</span>
                                    </div>
                                    <div>
                                        <strong>هزینه :</strong>
                                        <span id="hazine" class="fa-num">{{$output_factor_week}} تومان</span>
                                    </div>
                                    <select name="wallet_by_date" id="wallet_by_date" class="form-control">
                                        <option value="week">هفته گذشته</option>
                                        <option value="month">ماه گذشته</option>
                                    </select>
                                </form>
                            </div> 
                            
                            <div class="col-7" id="chart-container">
                                <canvas class="chart-canvas-empty d-none" style="height: 150px; width: 150px"></canvas>
                            </div>
                        </div>
                    </div>
                @endcan
            </div>
            @can('view productChanges')
                <div class="col-12 col-lg-6 mb-3 p-2">
                    <div class="bg-white h-100 rounded shadow-sm shadow-hover p-3">
                        <div class="pb-2 mb-3 border-bottom">
                            <strong>خلاصه گزارش انبار</strong>
                        </div>
                        <div class="d-flex justify-content-between py-2">
                            <div>تعداد کل کالاها</div><span class="fa-num strong"> {{ $inventory_info['all'] }} </span>
                        </div>
                        <div class="d-flex justify-content-between py-2">
                            <div>تعداد کالاهای اجاره</div><span class="fa-num strong"> {{ $inventory_info['return'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2">
                            <div>تعداد کالاهای موجود در انبار  </div><span class="fa-num strong">{{ $inventory_info['instock'] }}</span>
                        </div>
                    </div>
                </div>
            @endcan
    
            @can('view products')
                @if($out_of_stock_products->count())
                    <div class="col-12 col-lg-6 py-2 px-2 mb-3" >
                        <div class="p-3 bg-white h-100 shadow-sm rounded-sm">
                            <h4 class="text-secondary mb-4">لیست محصولات ناموجود</h4>
                            <table class="table table-sm">
                                <thead>
                                    <th>#</th>
                                    <th>تصویر</th>
                                    <th>نام محصول</th>
                                    <th>موجودی</th>
                                </thead>
                                <tbody>
                                    @foreach ($out_of_stock_products as $product)
                                    <tr>
                                        <td>{{$product->id}}</td>
                                        <td  class="text-center align-items-center py-2">
                                            <img src="{{ getImageSrc($product->getImage() , 'avatar') }}" style="width: 20px ; height:20px" class="rounded-circle" alt="">    
                                        </td>
                                        <td> <a href="{{route('panel.products.show', $product)}}">{{$product->name}}</a> </td>
                                        <td><span class="fa-num">{{$product->amount}}</span></td>
                                    </tr>
                                    @endforeach
                                </tbody> 
                            </table>
                        </div>
                    </div>
                @endif
            @endcan
            
            @can('view factors')
                @if ($last_factors->count())
                    <div class="col-12 col-lg-6 py-2 px-2 mb-3" >
                        <div class="p-3 bg-white h-100 shadow-sm rounded-sm" >
                            <h4 class="text-secondary mb-4">لیست آخرین فاکتور های ثبت شده</h4>
                            <table class="table table-sm">
                                <thead>
                                    <th class="text-center py-2">شماره فاکتور</th>
                                    <th class="text-center py-2">نوع فاکتور</th>
                                    <th class="text-center py-2">تاریخ ثبت</th>
                                </thead>
                                <tbody>
                                    @foreach ($last_factors as $key => $factor)
                                    <tr>
                                        <td  class="text-center py-2"> 
                                            <span class="fa-num">
                                                <a href="{{route('panel.factors.show', $factor)}}">{{ $factor->code }}</a>
                                            </span>
                                        </td>
                                        <td  class="text-center py-2"> {{$factor->getTypeTitle()}}</td>
                                        <td  class="text-center py-2"> {{jd($factor->created_at ,'Y/m/d' )}}</td>
                                    </tr>
                                    @endforeach
                                </tbody> 
                            </table>
                        
                        </div>
                    </div>
                @endif
            @endcan

            @can('view factors')
                @if ($non_payment_factors->count())
                    <div class="col-12 col-lg-6 py-2 px-2 mb-3" >
                        <div class="p-3 bg-white h-100 shadow-sm rounded-sm">
                            <h4 class="text-secondary mb-4">لیست فاکتور های پرداخت نشده</h4>
                            <table class="table table-sm">
                                <thead>
                                    <th class="text-center py-2">شماره فاکتور</th>
                                    <th class="text-center py-2">مشتری</th>
                                    <th class="text-center py-2">نوع فاکتور</th>
                                    <th class="text-center py-2">تاریخ فاکتور</th>
                                </thead>
                                <tbody>
                                    @foreach ($non_payment_factors as $key => $factor)
                                    
                                    <tr>
                                        <td  class="text-center py-2"> 
                                            <span class="fa-num">
                                                <a href="{{route('panel.factors.show', $factor)}}">{{ $factor->code }}</a>
                                            </span>
                                        </td>
                                        <td  class="text-center py-2"><a href="{{route('panel.customers.show', $factor->customer_id)}}" class="text-decoration-none text-black">{{$factor->getCustomerName()}}</a></td>
                                        <td  class="text-center py-2"> {{$factor->getTypeTitle()}}</td>
                                        <td  class="text-center py-2"> {{jd($factor->date ,'Y/m/d' )}}</td>
                                    </tr>
                                    @endforeach
                                </tbody> 
                            </table>
                        
                        </div>
                    </div>
                @endif
            @endcan

            @can('view factor payments')
                @if ($last_payments->count())
                    <div class="col-12 col-lg-6 py-2 px-2 mb-3" >
                        <div class="p-3 bg-white h-100 shadow-sm rounded-sm">
                            <h4 class="text-secondary mb-4">لیست آخرین پرداختی های انجام شده</h4>
                            <table class="table table-sm">
                                <thead>
                                    <th class="text-center py-2">شماره فاکتور</th>
                                    <th class="text-center py-2">نوع فاکتور</th>
                                    <th class="text-center py-2">نوع پرداخت</th>
                                    <th class="text-center py-2">وضعیت</th>
                                    <th class="text-center py-2">مبلغ پرداختی</th>
                                    <th class="text-center py-2">تاریخ ثبت</th>
                                </thead>
                                <tbody>
                                    @foreach ($last_payments as $key => $payment)
                                    <tr>
                                        <td  class="text-center py-2"> 
                                            <span class="fa-num">
                                                <a href="{{route('panel.factors.show', $factor)}}">{{ $payment->factor_id }}</a>
                                            </span>
                                        </td>
                                        <td  class="text-center py-2"> 
                                            <span class="fa-num">
                                                <a href="{{route('panel.factors.show', $factor)}}">{{ $payment->factor->getTypeTitle() }}</a>
                                            </span>
                                        </td>
                                        <td  class="text-center py-2">{{ $payment->getTypeTitle() }}</td>
                                        <td  class="text-center py-2">
                                            @if($item->is_done)
                                                <span class='badge badge-success'>پرداخت شده</span>
                                            @else
                                                <span class='badge badge-danger'>پرداخت نشده</span>
                                            @endif
                                        </td>
                                        <td  class="text-center py-2"><span class="fa-num">{{ number_format($payment->price) . 'تومان' }}</span> </td>
                                        <td  class="text-center py-2"> {{ jd($payment->created_at ,'Y/m/d' ) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody> 
                            </table>
            
                        </div>
                    </div>
                @endif
            @endcan
            @can('view factor payments')
                @if ($last_unpaid_payments->count())
                    <div class="col-12 col-lg-6 py-2 px-2 mb-3" >
                        <div class="p-3 bg-white h-100 shadow-sm rounded-sm">
                            <h4 class="text-secondary mb-4">لیست اخرین پرداختی های انجام نشده</h4>
                            <table class="table table-sm">
                                <thead>
                                    <th class="text-center py-2">شماره فاکتور</th>
                                    <th class="text-center py-2">نوع فاکتور</th>
                                    <th class="text-center py-2">وضعیت</th>
                                    <th class="text-center py-2">نوع پرداخت</th>
                                    <th class="text-center py-2">مبلغ پرداختی</th>
                                    <th class="text-center py-2">تاریخ ثبت</th>
                                </thead>
                                <tbody>
                                    @foreach ($last_unpaid_payments as $key => $payment)
                                    <tr>
                                        <td class="text-center py-2"> 
                                            <span class="fa-num">
                                                <a href="{{route('panel.factors.show', $factor)}}">{{ $payment->factor->code }}</a>
                                            </span>
                                        </td>
                                        <td class="text-center py-2"> 
                                            <span class="fa-num">
                                                <a href="{{route('panel.factors.show', $factor)}}">{{ $payment->factor->getTypeTitle() }}</a>
                                            </span>
                                        </td>
                                        <td class="text-center py-2">{{ $payment->getTypeTitle()}}</td>
                                        <td class="text-center py-2">
                                            @if($item->is_done)
                                                <span class='badge badge-success'>پرداخت شده</span>
                                            @else
                                                <span class='badge badge-danger'>پرداخت نشده</span>
                                            @endif
                                        </td>
                                        <td class="text-center py-2">
                                            <span class="fa-num">{{ $payment->getPrice() }}</span>
                                        </td>
                                        <td class="text-center py-2">{{jd($payment->created_at ,'Y/m/d' )}}</td>
                                    </tr>
                                    @endforeach
                                </tbody> 
                            </table>
            
                        </div>
                    </div>
                @endif
            @endcan

            
            @can('view factors')
                @if ($last_product_changes->count())
                    <div class="col-12 col-lg-6 py-2 px-2 mb-3" >
                        <div class="p-3 bg-white h-100 shadow-sm rounded-sm" >
                            <h4 class="text-secondary mb-4">لیست آخرین سند ورود و خروج</h4>
                            <table class="table table-sm">
                                <thead>
                                    <th class="text-center py-2">عنوان سند</th>
                                    <th class="text-center py-2">شماره فاکتور</th>
                                    <th class="text-center py-2">نوع سند</th>
                                    <th class="text-center py-2">تاریخ ثبت</th>
                                </thead>
                                <tbody>
                                    @foreach ($last_product_changes as $key => $product_change)
                                    <tr>
                                        <td  class="text-center py-2">{{ $product_change->title }}</td>
                                        <td  class="text-center py-2"> 
                                            <span class="fa-num">
                                                <a href="{{route('panel.product_changes.show', $product_change)}}">{{ $product_change->code }}</a>
                                            </span>
                                        </td>
                                        <td  class="text-center py-2"> {{$product_change->getTypeTitle()}}</td>
                                        <td  class="text-center py-2"> {{jd($product_change->created_at ,'Y/m/d' )}}</td>
                                    </tr>
                                    @endforeach
                                </tbody> 
                            </table>
                        
                        </div>
                    </div>
                @endif
            @endcan

            @can('view factors')
                @if ($none_return_products->count())
                    <div class="col-12 col-lg-6 py-2 px-2 mb-3" >
                        <div class="p-3 bg-white h-100 shadow-sm rounded-sm" >
                            <h4 class="text-secondary mb-4">لیست آخرین کالاهای اجاره</h4>
                            <table class="table table-sm">
                                <thead>
                                    <th class="text-center py-2">عنوان سند</th>
                                    <th class="text-center py-2">شماره سند</th>
                                    <th class="text-center py-2">نوع سند</th>
                                    <th class="text-center py-2">تاریخ ثبت</th>
                                </thead>
                                <tbody>
                                    @foreach ($none_return_products as $key => $product_change)
                                    <tr>
                                        <td  class="text-center py-2">{{ $product_change->title }}</td>
                                        <td  class="text-center py-2"> 
                                            <span class="fa-num">
                                                <a href="{{ $product_change->getShowRoute() }}" class="btn btn-link">{{ $product_change->code }}</a>
                                            </span>
                                        </td>
                                        <td  class="text-center py-2"> {{$product_change->getTypeTitle()}}</td>
                                        <td  class="text-center py-2"> {{jd($product_change->created_at ,'Y/m/d' )}}</td>
                                    </tr>
                                    @endforeach
                                </tbody> 
                            </table>
                        </div>
                    </div>
                @endif
            @endcan

        </div>
    </div>
    
    </div>
    
@endsection


@section('scripts')
<script src="{{asset('js/chart.js')}}"></script>

    <script>

        $('body').on('change','#wallet_by_date', function() {
            $.post("{{ route('panel.changeWalletChart') }}",{
                _token: "{{ csrf_token() }}",
                wallet_by_date: $( "#wallet_by_date" ).val(),
            }).done(response => {

                $('#daramad1').val(response.input_factor_week)
                $('#hazine1').val(response.output_factor_week)

                $('#daramad2').val(response.input_factor_month)
                $('#hazine2').val(response.output_factor_month)

                $('#daramad').html(response.input_factor_week)
                $('#hazine').html(response.output_factor_week)

                $('#daramad').html(response.input_factor_month)
                $('#hazine').html(response.output_factor_month)

                var data1 = $('#daramad1').val();
                var data2 = $('#hazine1').val();
                var data3 = $('#daramad2').val();
                var data4 = $('#hazine2').val();
                drawChart(data1, data2, data3, data4);
            }).fail(error => {

                $('#daramad').html(error)
            })
        });


        $(document).ready(function() {
            var data1 = $('#week_input').val();
            var data2 = $('#week_output').val();
            drawChart(data1, data2);
        });

        function drawChart(data1,data2, data3 = null, data4 = null)
        {
            var ctx = $('.new-chart').remove();
            var ctx = $('.chart-canvas-empty').clone();
            if(ctx) {
                $(ctx).removeClass('chart-canvas-empty d-none').addClass('new-chart');
                $('#chart-container').append(ctx);
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: [
                            'درامد',
                            'هزینه',
                        ],              
                        datasets: [{
                            label: 'نمودار موجودی',
                            data: [data1 , data2,data3 , data4],
                            backgroundColor: [
                            '#38c172',
                            '#e3342f',
                            ],
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        }
       
    </script>

    

@endsection