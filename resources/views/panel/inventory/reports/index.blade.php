@extends('panel.layouts.master')
@section('head')
<link href="{{ asset('/css/persian-datepicker.css') }}" rel="stylesheet">
<link href="{{asset('/css/bootstrap-select.min.css')}}" rel="stylesheet"> 
@endsection
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="d-flex flex-wrap justify-content-between align-items-center py-2 mb-2">
                    <h2 class="m-0 text-dark">گزارش انبار </h2>
                    <a class="btn btn-secondary" href="{{route('panel.dashboard')}}">بازگشت </a>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="container-fluid">
                <div class="card shadow-sm">
                        @include('components.messages')
                        <div class="col-12 mt-2">
                            <form action="" method="get">
                                <div class="row">
                                    <div class="col-12 col-lg-3 py-1">
                                        <select name="type" id="type" class="form-control">
                                            <option value="">انتخاب سند</option>
                                            @foreach (App\Models\ProductChange::$types as $key => $type)
                                                <option value="{{$key}}" {{ $type == request()->get('type') ? 'selected' : ''}}>{{$type}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-lg-3 py-1">
                                        <div class="input-group">
                                            <input type="text" class="form-control"  autocomplete="off" id="start_date_picker" placeholder="از تاریخ"  >
                                            <input type="hidden" class="form-control" id="start_date" name="start_date" > 
                                            <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar" aria-hidden="true"></i>
                                            </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-3 py-1">
                                        <div class="input-group">
                                            <input type="text" class="form-control"  autocomplete="off" id="end_date_picker" placeholder="تا تاریخ" >
                                            <input type="hidden" class="form-control" id="end_date" name="end_date" > 
                                            <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar" aria-hidden="true"></i>
                                            </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-lg-3 py-1">
                                        <div class="input-group ">
                                            <select name="customer" id="customer" class="form-control selectpicker" data-live-search="true">
                                                @foreach ($customers as $customer)
                                                    <option value="{{$customer->id}}">{{$customer->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                
                                    <div class="col-12 col-lg-3 py-1">
                                        <div class="form-gorup">
                                            <button type="submit" class="btn btn-success mr-2">فیلتر کردن</button>
                                            <a href="{{route('panel.reports')}}" class="btn btn-secondary mr-2">نمایش همه</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                    <div class="col-lg-12">
                        @if($reports->count() > 0)
                        <table class="table table-sm">
                            <thead class="thead-light">
                            <tr>
                                <th class="text-center py-2">#</th>
                                <th class="text-center py-2">ایجاد کننده سند</th>
                                <th class="text-center py-2">عنوان سند</th>
                                <th class="text-center py-2">ویرایش کننده سند</th>
                                <th class="text-center py-2">مشتری</th>
                                <th class="text-center py-2">نوع سند</th>
                                <th class="text-center py-2">تاریخ ورود</th>
                                <th class="text-center py-2">تاریخ خروج</th>
                                <th class="text-center py-2">تاریخ برگشت </th>
                                <th class="text-center py-2">تاریخ ایجاد </th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($reports as  $key => $report)
                                    <tr>
                                        <td  class="text-center py-2">{{ ++$key}}</td>
                                        <td  class="text-center py-2">{{$report->getCreatorName()}}</td>
                                        <td  class="text-center py-2"> 
                                            <a href="{{route('panel.product_changes.show',$report->id)}}">
                                                {{$report->code}}
                                            </a>
                                    </td>
                                        <td  class="text-center py-2"> {{$report->getEditorName()}}</td>
                                        <td  class="text-center py-2"> {{$report->getCustomerName()}}</td>
                                        <td  class="text-center py-2"> {{$report->getTypeTitle()}}</td>
                                        <td  class="text-center py-2"> {{ $report->enter_date ? jd($report->enter_date ,'Y/m/d') : '---'}}</td>
                                        <td  class="text-center py-2"> {{ $report->exit_date ? jd($report->exit_date ,'Y/m/d') : '---'  }}</td>
                                        <td  class="text-center py-2"> {{ $report->return_date ? jd($report->return_date ,'Y/m/d') : '---' }}</td>
                                        <td  class="text-center py-2"> {{ $report->created_at ? jd($report->created_at ,'Y/m/d') : '---' }}</td>
                                        
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