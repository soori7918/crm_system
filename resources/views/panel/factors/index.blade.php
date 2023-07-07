@extends('panel.layouts.master')
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid px-4">
            <div class="row mb-2 d-flex flex-wrap justify-content-between">
               
                <h1 class="m-0 text-dark">مدیریت فاکتور ها</h1>
               
                <div>
                    <a href="{{route('panel.cost_factors.create')}}" class="btn btn-sm btn-primary p-2">فاکتور هزینه </a>
                    <a href="{{route('panel.factors.create')}}" class="btn btn-sm btn-warning p-2">فاکتور درآمد </a>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
           <div class="card shadow-sm">
            @include('components.messages')
            <form action="" method="get">
                <div class="col-12">
                    <div class="row py-2">
                        <div class="col-lg-3">
                            <input type="text" name="search" placeholder="جستجو براساس نام" class="form-control ml-2">
                        </div>
                        <div class="col-lg-4">
                            <button type="submit" class="btn  btn-success ">جستجو</button>
                            <a href="{{ route('panel.factors.index') }}" class="btn btn-secondary mr-2">
                                نمایش همه
                            </a>
                        </div>
                    </div>
                </div>
            </form>
            <div class="col-12">
                @if($factors->count() > 0)
                    <table class="table table-sm">
                        <thead class="thead-light">
                        <tr>
                            <th class="text-center py-2">#</th>
                            <th class="text-center py-2">مشتری</th>
                            <th class="text-center py-2">نوع فاکتور</th>
                            <th class="text-center py-2">تاریخ فاکتور</th>
                            <th class="text-center py-2">تاریخ ثبت</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($factors as  $key => $factor)
                                <tr>
                                    <td  class="text-center py-2">{{ ++$key}}</td>
                                    <td  class="text-center py-2">
                                        @if ($factor->customer_id)
                                            <a href="{{route('panel.customers.show', $factor->customer_id)}}" class="text-decoration-none text-black">{{$factor->getCustomerName()}}</a>
                                        @else  
                                            --- 
                                        @endif
                                    </td>
                                    <td  class="text-center py-2"> {{$factor->getTypeTitle()}}</td>
                                    <td  class="text-center py-2"> {{jd($factor->date ,'Y/m/d' )}}</td>
                                    <td  class="text-center py-2"> {{jd($factor->created_at ,'Y/m/d' )}}</td>
                                    <td  class="text-center py-2">
                                        <form method="post" action="{{route('panel.factors.destroy' , $factor)}}">
                                            @csrf
                                            @method('delete')
                                            <a href="{{route('panel.factors.show', $factor)}}" class="btn btn-sm btn-info">مشاهده</a>
                                            <a href="{{route('panel.factors.edit' , $factor)}}" class="btn btn-sm btn-success">ویرایش</a>
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
            {{ $factors->links() }}
           </div>
        </div>
    </div>
</div>
@endsection