@extends('panel.layouts.master')
@section('head')
<link href="{{ asset('/css/persian-datepicker.css') }}" rel="stylesheet">
<link href="{{asset('/css/bootstrap-select.min.css')}}" rel="stylesheet"> 
@endsection
@section('content')

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid px-4">
            <div class="row mb-2 d-flex flex-wrap justify-content-between">
                <h1 class="m-0 text-dark">گزارش صندوق ها </h1>
                <div>
                    <a href="{{route('panel.dashboard')}}" class="btn btn-sm btn-secondary p-2">بازگشت</a>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
           <div class="card shadow-sm">
            <div class="col-12 mt-2">
                <form action="" method="get">
                    <div class="row">
                        <div class="col-12 col-lg-3">
                            <div class="form-group">
                                <select name="wallets" id="wallets" class="form-control">
                                    <option value="">انتخاب صندوق</option>
                                    @foreach ($wallets as $wallet)
                                        <option value="{{$wallet->id}}" {{ $wallet->id == request()->get('wallets') ? 'selected' : ''}}>{{$wallet->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-lg-3">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control"  autocomplete="off" id="start_date_picker" placeholder="از تاریخ"  >
                                    <input type="hidden" class="form-control" id="start_date" name="start_date" > 
                                    <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar" aria-hidden="true"></i>
                                    </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-3">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control"  autocomplete="off" id="end_date_picker" placeholder="تا تاریخ" >
                                    <input type="hidden" class="form-control" id="end_date" name="end_date" > 
                                    <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar" aria-hidden="true"></i>
                                    </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-3">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <select name="type_factor" id="type_factor" class="form-control">
                                        @foreach (App\Models\Factor::$types as $key => $type)
                                            <option value="{{$key}}" {{ $key == request()->get('type_factor') ? 'selected' : ''}}>{{$type}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-3">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <select name="sort_by" id="sort_by" class="form-control">
                                    <option value="price" {{ 'price' == request()->get('sort_by') ? 'selected' : ''}} >مبلغ</option>
                                    <option value="date" {{ 'date' == request()->get('sort_by') ? 'selected' : ''}}>تاریخ</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-3">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <select name="sort_by_type" id="sort_by_type" class="form-control">
                                    <option value="asc" {{ 'asc' == request()->get('sort_by_type') ? 'selected' : ''}}>صعودی</option>
                                    <option value="desc" {{ 'desc' == request()->get('sort_by_type') ? 'selected' : ''}}>نزولی</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4">
                            <div class="form-gorup">
                                <button type="submit" class="btn btn-success mr-2">فیلتر کردن</button>
                                <a href="{{route('panel.wallets.reports')}}" class="btn btn-secondary mr-2">نمایش همه</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-12">
                <table class="table table-bordred ">
                    <tr>
                        <td>مبلغ</td>
                        <td>صندوق</td>
                        <td>تاریخ</td>
                        <td>شماره فاکتور</td>
                        <td>وضعیت پرداخت</td>
                        <td>نوع پرداخت</td>
                    </tr>
                    @foreach ($wallet_changes as $wallet_change)
                        <tr>
                            <td><span class="fa-num">{{number_format($wallet_change->price)}}</span> تومان </td>
                            <td>{{$wallet_change->wallets->title}}</td>
                            <td>{{jd($wallet_change->created_at , 'Y/m/d')}}</td>
                            <td> <a href="{{route('panel.factors.show' , $wallet_change->factors->id)}}"><span class="fa-num">{{$wallet_change->factors->code}}</span></a> </td>
                            <td> 
                                @foreach ($wallet_change->factors->payments as $payment)
                                    @if($payment->is_done)
                                        <span class='badge badge-success'>پرداخت شده</span>
                                    @else
                                        <span class='badge badge-danger'>پرداخت نشده</span>
                                    @endif
                                @endforeach 
                            </td>
                            <td> 
                                @foreach ($wallet_change->factors->payments as $payment)
                                    {{$payment->getTypeTitle()}}
                                @endforeach 
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
           </div>
        </div>
    </div>
</div>

@endsection


@section('scripts')
    <script>
        $('.selectpicker').selectpicker()
    </script>

    <script src="{{asset('/js/bootstrap-select.min.js')}}"></script>
    <script src="{{ asset('/js/persian-date.min.js') }}"></script>
    <script src="{{ asset('/js/persian-datepicker.min.js') }}"></script>
    
    <script>
        $(document).ready(function () {
            
            var start_date;
            var end_date;
            $('body').on('change','#start_date_picker',function() {
                if($(this).val() == '') {
                    $('#start_date').val('');
                }
            });

            $('body').on('change','#end_date_picker',function() {
                if($(this).val() == '') {
                    $('#end_date').val('');
                }
            });
        
        });

        $(document).ready(function () {
                start_date = $('#start_date_picker').persianDatepicker({
                    format: 'YYYY/MM/DD',
                    altField: '#start_date',
                    observer: false,
                    initialValue: false,
                    onSelect: startDateSelect

                });
        
                end_date = $('#end_date_picker').persianDatepicker({
                    format: 'YYYY/MM/DD',
                    altField: '#end_date',
                    observer: false,
                    initialValue: false,
        
                });
 

                const selected_start_date = $('#start_date').val();
                if(selected_start_date) {
                    start_date.setDate(parseFloat(selected_start_date));
                }
                const selected_end_date = $('#end_date').val();
                if(selected_end_date) {
                    end_date.setDate(parseFloat(selected_end_date));
                }

        });

        function startDateSelect(unix) {
            $('#start_date').val(unix);
            start_date.setDate(unix);
            start_date.hide();
        }
        function endDateSelect(unix) {
            $('#end_date').val(unix);
            end_date.setDate(unix);
            end_date.hide();
        }
    </script>

@endsection