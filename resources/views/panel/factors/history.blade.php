@extends('panel.layouts.master')

@section('content')

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="d-flex flex-wrap justify-content-between align-items-center py-2 mb-2">
                <h1 class="m-0 text-dark">مشاهده سند {{$factor->getTypeTitle()}}</h1>
                <a class="btn btn-secondary" href="{{route('panel.factors.index')}}">بازگشت </a>
    
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="bg-white py-4 px-4">
                @include('panel.factors.tab')
            </div>
            <div class="row">
                <div class="col-12 col-lg-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h3 class="m-0 py-4 text-center fa-num">تاریخچه فاکتور {{$factor->getTypeTitle()}} با کد {{$factor->code}}</h3>
                            @include('components.messages')

                            @if ($factor->logs->count()>0)
                                <table class="table table-bordered table-stripped">
                                    <thead>
                                        <tr>
                                            <th>عنوان</th>
                                            <th>توسط</th>
                                            <th>تاریخ</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($logs as $log)
                                            <tr>
                                                <td>{{ $log->title }}</td>
                                                <td>{{ $log->getCreatorName() }}</td>
                                                <td>{{ jd($log->created_at) }}</td>
                                                <td>
                                                    <form action="{{route('panel.factors.delete_reports',$log->id)}}" method="post">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit"   onclick="return confirm('آیا مایل به حذف هستید؟')"  class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
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
                        {{ $logs->links() }}

                    </div>
                </div>
            </div>

         

        </div> 
    </div>
    
</div>

    
@endsection