@extends('panel.layouts.master')

@section('content')

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid px-4">
            <div class="row mb-2 d-flex flex-wrap justify-content-between">
                <h1 class="m-0 text-dark">گزارشات مشتریان </h1> 
                    <div>
                        <a href="{{route('panel.customers.index')}}" class="btn btn-sm btn-secondary p-2">بازگشت</a>
                    </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
 
                    <div class="d-flex bg-white w-100 rounded-sm shadow-sm justify-content-between p-2">
                        <div>
                            <input type="hidden" name="" id="rooz" value="{{$customer_today}}">
                            <input type="hidden" name="" id="hafte" value="{{$customer_week}}">
                            <input type="hidden" name="" id="mah" value="{{$customer_month}}">
                            <input type="hidden" name="" id="all" value="{{$customer_count}}">

                            <div class="py-2">
                                <strong>تعداد کاربران ثبت شده در امروز </strong> <span class="fa-num">{{$customer_today}}</span>
                            </div>
                            <div class="py-2">
                                <strong>تعداد کاربران جدید در این هفته </strong> <span class="fa-num">{{$customer_week}}</span>
                            </div>
                            <div class="py-2">
                                <strong>تعداد کاربران جدید در این ماه </strong> <span class="fa-num">{{$customer_month}}</span>
                            </div>
                            <div class="py-2">
                                <strong>تعداد کل مشتریان </strong> <span class="fa-num">{{$customer_count}}</span> 
                            </div>
                        </div>
                        <div>
                            <canvas id="chart" style="max-height: 300px"></canvas>
                        </div>
                    </div>
               
                <div class="bg-white shadow-sm m-2 align-items-center W-100  text-dark p-2">
                    <div class="py-2">
                        <strong>لیست کاربران بدهکار</strong> 
                        <hr>
                    </div>    


                   @if($factors->count() > 0)
                    <table class="table table-sm w-100">
                        <thead class="thead-light">
                        <tr>
                            <th class="text-center py-2">شماره فاکتور</th>
                            <th class="text-center py-2">نام و نام خانوادگی</th>
                            <th class="text-center py-2">شماره همراه</th>
                            <th class="text-center py-2"> شماره تلفن</th>
                            <th class="text-center py-2">شماره تلفن</th>
                            <th class="text-center py-2">مبلغ</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($factors as $factor)
                                <tr>
                                    <td  class="text-center py-2">
                                        <a href="{{route('panel.factors.show',$factor->id)}}" target="_blank">
                                            {{$factor->id}}
                                        </a>
                                    </td>
                                    <td  class="text-center py-2">
                                        <a href="{{route('panel.customers.show' ,$factor->customer_id )}}" target="_blank">
                                            {{$factor->getCustomerName()}}
                                        </a>
                                    </td>
                                    <td  class="text-center py-2">{{ ($factor->customer->mobile ?? false) ?: '---'}}</td>
                                    <td  class="text-center py-2">{{ $factor->phone1 ?: '---' }}</td>
                                    <td  class="text-center py-2">{{ $factor->phone2 ?: '---' }}</td> 
                                    <td  class="text-center py-2">
                                        @foreach ($factor->payments as $payment)
                                            <span class="fa-num">{{number_format($payment->price)}} تومان</span>
                                        @endforeach
                                    </td> 
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                        @include('components.empty')
                    @endif

               </div>
        </div>
    </div>

</div>

@endsection

@section('scripts')
    <script src="{{asset('js/chart.js')}}"></script>
    <script>
            var ctx = document.getElementById('chart');
            let rooz = $('#rooz').val();
            let hafte = $('#hafte').val();
            let mah = $('#mah').val();
            let all = $('#all').val();
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: [
                        'ماه',
                        'هفته',
                        'روز',
                        'همه',
                    ],              
                    datasets: [{
                        label: 'مشاهده مشتریان',
                        data: [rooz , hafte , mah , all],
                        backgroundColor: [
                            'rgb(119,136,153)',
                            'rgb(176,196,222)',
                            'rgb(230,230,250)',
                            'rgb(128,212,255)',
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