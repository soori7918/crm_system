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
                <h1 class="m-0 text-dark">لیست اسناد ورود و خروج کالا </h1>
                <div>
                    <a href="{{route('panel.inventory.productChanges.enter.create')}}" class="btn btn-sm btn-primary p-2">ورود کالا</a>
                    <a href="{{route('panel.inventory.productChanges.exit.create')}}" class="btn btn-sm btn-info p-2">خروج کالا</a>
                    <a href="{{route('panel.inventory.productChanges.return.create')}}" class="btn btn-sm btn-warning p-2">خروج موقت کالا</a>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
           <div class="card shadow-sm">
            @include('components.messages')
            <form action="" method="get">
                <div class="col-12 mt-2">
                    <div class="row">
                        <div class="col-lg-3">
                            <input type="text" name="search" placeholder="عنوان سند،شماره سند،مشتری" class="form-control ml-2">
                        </div>
                        <div class="col-lg-3">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control"  autocomplete="off" id="start_date_picker" placeholder="از تاریخ">
                                <input type="hidden" class="form-control" id="start_date" name="start_date"> 
                                <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar" aria-hidden="true"></i>
                                </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control"  autocomplete="off" id="end_date_picker" placeholder="تا تاریخ">
                                <input type="hidden" class="form-control" id="end_date" name="end_date"> 
                                <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar" aria-hidden="true"></i>
                                </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="input-group mb-3">
                                <select name="type" id="type" class="form-control">
                                    <option value="">نوع تاریخ</option>
                                    <option value="enter_date">تاریخ ورود</option>
                                    <option value="exit_date">تاریخ خروج</option>
                                    <option value="return_date">تاریخ برگشت</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <button type="submit" class="btn  btn-success ">جستجو</button>
                            <a href="{{ route('panel.inventory.productChanges.index') }}" class="btn btn-secondary mr-2">
                                نمایش همه
                            </a>
                        </div>
    
                    </div>
                </div>
            </form>
            <div class="col-12 py-2">
                @if($product_changes->count() > 0)
                <table class="table table-sm">
                    <thead class="thead-light">
                    <tr>
                        <th class="text-center py-2">#</th>
                        <th class="text-center py-2">شماره سند</th>
                        <th class="text-center py-2">عنوان سند</th>
                        <th class="text-center py-2">مشتری</th>
                        <th class="text-center py-2">نوع سند</th>
                        <th class="text-center py-2">تاریخ ایجاد</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($product_changes as  $key => $product_change)
                            <tr>
                                <td  class="text-center py-2">{{ ++$key}}</td>
                                <td  class="text-center py-2 fa-num">{{$product_change->code}}</td>
                                <td  class="text-center py-2">{{$product_change->title}}</td>
                                <td  class="text-center py-2"> {{$product_change->getCustomerName()}}</td>
                                <td  class="text-center py-2"> {{$product_change->getTypeTitle()}}</td>
                                <td  class="text-center py-2"> {{ $product_change->created_at ? jd($product_change->created_at ,'Y/m/d') : '---' }}</td>
                                <td  class="text-center py-2">
                                    <a href="{{ $product_change->getShowRoute() }}" class="btn btn-sm btn-info">مشاهده</a>
                                    <a href="{{ $product_change->getEditRoute() }}" class="btn btn-sm btn-success">ویرایش</a>
                                    <form method="post" class="d-inline-block" action="{{route('panel.inventory.productChanges.destroy' , $product_change)}}">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-danger btn-sm" 
                                            onclick="return confirm('آیا مایل به حذف هستید؟')"  title="حذف"
                                        >حذف</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    @include('components.empty')
                @endif
            </div>

            {{ $product_changes->links() }}
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