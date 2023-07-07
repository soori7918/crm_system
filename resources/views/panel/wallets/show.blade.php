@extends('panel.layouts.master')

@section('content')

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid px-4">
            <div class="row mb-2 d-flex flex-wrap justify-content-between">
                <h1 class="m-0 text-dark">ویرایش صندوق {{$wallet->title}} </h1>
                <div>
                    <a href="{{route('panel.dashboard')}}" class="btn btn-sm btn-secondary p-2">بازگشت</a>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
           <div class="card shadow-sm">
               <div class="d-flex flex-row align-items-center justify-content-between text-dark p-2">
                    <div class="col-6">
                       <strong>{{$wallet->title}} </strong>
                       <br>
                        <div class="text-muted py-2">
                            {{$wallet->description}}
                        </div>
                        <div class="text-muted ">
                            <small>امروز <span class="text-success fa-num">{{number_format($wallet->todayAmountIncrease())}} تومان</span> / <span class="text-danger fa-num">{{ number_format($wallet->todayAmountDecrease())}} تومان</span></small><br>
                            <small>یک هفته پیش  <span class="text-success fa-num">{{number_format($wallet->weekAmountIncrease())}} تومان</span> / <span class="text-danger fa-num">{{ number_format($wallet->weekAmountDecrease())}} تومان</span></small></small> <br>
                            <small>یک ماه پیش <span class="text-success fa-num">{{number_format($wallet->monthAmountIncrease())}} تومان</span> / <span class="text-danger fa-num">{{ number_format($wallet->monthAmountDecrease())}} تومان</span></small></small> 
                            <input  type="hidden" name="" id="addmonth" value="{{$wallet->monthAmountIncrease()}}">
                            <input type="hidden" name="" id="decmonth" value="{{$wallet->monthAmountDecrease()}}">
                        </div>
                    </div>
                   <div class="col-6">
                       <strong>نمودار موجودی صندوق</strong>
                       <div class="d-flex flex-row align-items-center justify-content-between text-dark mb-2" >
                        <canvas id="chart" style="max-height: 300px"></canvas>
                        </div>
                    <div class="d-flex align-items-end justify-content-between py-2">
                        <div class="d-flex flex-row align-items-center justify-content-end">
                           <strong class="fa-num"> موجودی صندوق {{number_format($wallet->amount)}} تومان</strong>
                        </div>
                    </div>
                   </div>
               </div>
           </div>

           <div class="card shadow-sm p-2">
               مشاهده تغییرات
                <div class="d-flex flex-row align-items-center justify-content-between text-dark p-2">
                    <br>
                    <table class="table table-bordered  table-striped table-sm" >
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>مبلغ</th>
                                <th>وضعیت پرداخت</th>
                                <th>توضیحات</th>
                                <th>تاریخ</th>
                                <th>وضعیت پرداخت</th>
                                <th>فاکتور</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($wallet->walletChanges as $key=>$wallet_change)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td><span class="fa-num">{{number_format($wallet_change->price)}}</span></td>
                                    <td>
                                        @foreach($wallet_change->factors->payments as $payment)
                                            @if($payment->is_done)
                                                <span class='badge badge-success'>پرداخت شده</span>
                                            @else
                                                <span class='badge badge-danger'>پرداخت نشده</span>
                                            @endif
                                        @endforeach
                                    </td>                                    <td>{{$wallet_change->description}}</td>
                                    <td>{{jd($wallet_change->created_at)}}</td>
                                    <td>
                                        @foreach($wallet_change->factors->payments as $payment)
                                            {{$payment->getTypeTitle()}}
                                        @endforeach
                                    </td>
                                    
                                    <td>
                                        <a href="{{route('panel.factors.show',$wallet_change->factors->id)}}">{{$wallet_change->factors->id}}</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
           </div>

          
        </div>
    </div>

</div>

@endsection

@section('scripts')
    <script src="{{asset('js/chart.js')}}"></script>
    <script>
            var ctx = document.getElementById('chart');
            var data1 = $('#addmonth').val();
            console.log(data1);
            var data2 = $('#decmonth').val();

            var myChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: [
                        'درامد',
                        'هزینه',
                    ],              
                    datasets: [{
                        label: 'نمودار موجودی',
                        data: [data1 , data2],
                        backgroundColor: [
                        'rgb(42,128,0)',
                        'rgb(230,0,76)',
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
    </script>
@endsection