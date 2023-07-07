@extends('panel.layouts.master')

@section('content')

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid px-4">
            <div class="row mb-2 d-flex flex-wrap justify-content-between">
                <h1 class="m-0 text-dark">مدیرتی صندوق ها </h1>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
           <div class="card shadow-sm p-2">
                @include('components.messages')
                <form action="" method="get">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <input type="text" name="search" placeholder="جستجو براساس نام" class="form-control ml-2">
                                </div>
                            </div>
                        
                            <div class="col-lg-4">
                                <button type="submit" class="btn  btn-success ">جستجو</button>
                                <a href="{{ route('panel.wallets.index') }}" class="btn btn-secondary mr-2">
                                    نمایش همه
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
           </div>

            <a href="{{route('panel.wallets.create')}}" class="text-decoration-none" data-toggle="modal" data-target="#createWallet">
                <div class="d-flex flex-column bg-white w-100 align-items-center rounded-sm justify-content-center m-auto py-5" style="border: 2px dashed black">
                    <div style="font-size:30px"><i  class="text-primary fa fa-plus ml-2"></i> افزودن صندوق جدید</div>
                </div>
            </a>

           <div class="row mt-4">
                @if($wallets->count() > 0)
                    @foreach ($wallets as $key => $wallet)
                            <div class="col-12 col-lg-6">
                                <div class="bg-white rounded shadow-sm shadow-hover mb-3 p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="d-flex flex-row align-items-center justify-content-between text-dark mb-2">
                                                <strong>{{$wallet->title}} </strong>
                                                <br>
                                                <div class="d-flex flex-row align-items-center justify-content-end">
                                                    <a href="{{route('panel.wallets.show' , $wallet->id)}}" class="btn btn-sm btn-info mr-2"><i class="fa fa-eye"></i></a>
                                                    <a href="{{route('panel.wallets.edit' , $wallet->id)}}" class="btn btn-sm btn-success mr-2 "><i class="fa fa-edit"></i></a>
                                                    <form action="{{route('panel.wallets.destroy' , $wallet->id)}}" method="post">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit"  
                                                        class="btn btn-sm btn-danger mr-2"
                                                        onclick="return confirm('آیا مایل به حذف هستید؟')"  title="حذف"
                                                        ><i class="fa fa-trash"></i></button>
                                                    </form>
                                                </div>
                                            </div>
                                            <small class="text-muted ">{{ $wallet->description }}</small> 
                                            <div class="d-flex flex-row align-items-center justify-content-between text-dark mb-2">
                                                <strong>تغییرات</strong>
                                            </div>
                                            <div class="text-muted ">
                                                <small>امروز <span class="text-success fa-num">{{number_format($wallet->todayAmountIncrease())}} تومان</span> / <span class="text-danger fa-num">{{ number_format($wallet->todayAmountDecrease())}} تومان</span></small><br>
                                                <small>یک هفته پیش  <span class="text-success fa-num">{{number_format($wallet->weekAmountIncrease())}} تومان</span> / <span class="text-danger fa-num">{{ number_format($wallet->weekAmountDecrease())}} تومان</span></small></small> <br>
                                                <small>یک ماه پیش <span class="text-success fa-num">{{number_format($wallet->monthAmountIncrease())}} تومان</span> / <span class="text-danger fa-num">{{ number_format($wallet->monthAmountDecrease())}} تومان</span></small></small> 
                                                <input  type="hidden" name="" id="addmonth{{$wallet->id}}" value="{{$wallet->monthAmountIncrease()}}">
                                                <input type="hidden" name="" id="decmonth{{$wallet->id}}" value="{{$wallet->monthAmountDecrease()}}">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="d-f lex flex-row align-items-center justify-content-between text-dark mb-2">
                                                <canvas id="{{$wallet->id}}"></canvas>
                                            </div>
                                            <div class="d-flex align-items-end justify-content-between py-2">
                                                <div class="d-flex flex-row align-items-center justify-content-end">
                                                   <strong class="fa-num"> موجودی صندوق {{number_format($wallet->amount)}} تومان</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    @endforeach
               @else
                    @include('components.empty')
                @endif
           </div>

        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="createWallet" tabindex="-1" role="dialog" aria-labelledby="createWalletTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">افزودن صندوق </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="col-12 col-lg-12">
                <form action="{{route('panel.wallets.store')}}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="title">عنوان</label>
                        <input type="text" class="form-control" name="title" id="title">
                    </div>
                    <div class="form-group">
                        <label for="description">توضیحات</label>
                        <textarea name="description" id="description" cols="30" class="form-control" rows="5"></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success">ثبت صندوق</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                    </div>
                </form>
            </div>
        </div>
      </div>
    </div>
</div>

@endsection


@section('scripts')
    <script src="{{asset('js/chart.js')}}"></script>
    <script>
        @foreach ($wallets as $wallet)
            var ctx = document.getElementById('{{$wallet->id}}');
            var data1 = $('#addmonth{{$wallet->id}}').val();
            console.log(data1);
            var data2 = $('#decmonth{{$wallet->id}}').val();

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
        @endforeach
    </script>
@endsection