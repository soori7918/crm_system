@extends('panel.layouts.master')

@section('content')

<div class="content-wrapper">

    
    <div class="content-header">
        <div class="container-fluid px-4">
            <div class="row mb-2 d-flex flex-wrap justify-content-between">
               
                <h3 class="m-0 text-dark">مشاهده فاکتور {{$factor->getTypeTitle()}} برای 

                    {{$factor->customer ? $factor->customer->name : '---' }}
                
                </h3>
               
                <div>
                    <a href="{{route('panel.factors.index')}}" class="btn btn-sm btn-secondary p-2">بازگشت</a>
                </div>
            </div>
        </div>
    </div>


    <div class="content">
        <div class="container-fluid">
            <div class="bg-white py-4 px-4">
                @include('panel.factors.tab')
            </div>
            <div class="card shadow-sm text-center">
                <div class="py-2">
                    <h2 class="fa-num">فاکتور {{$factor->getTypeTitle()}} با کد {{$factor->code}} </h2>
                </div>
                
                <div class="row">
                    <div class="col-6">
                            <div class="p-4">
                                <table class="table w-100 table-bordered table-sm">
                                    <tr>
                                        <th class="text-center" colspan="2">اطلاعات فاکتور</th>
                                    </tr>
                                    <tr>
                                        <th class="text-right">کد</th>
                                        <td class="text-center"> <span class="fa-num">{{$factor->id}}</span> </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right">نوع فاکتور</th>
                                        <td class="text-center"> {{$factor->getTypeTitle()}} </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right">ایجاد شده توسط</th>
                                        <td class="text-center"> {{ $factor->getCreatorName() }} </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right">به روز رسانی شده توسط</th>
                                        <td class="text-center"> {{ $factor->getUserUpdatedName() }} </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right">مقدار تخفیف</th>
                                        <td class="text-center"><span class="fa-num">{{ $factor->discount ?: '---' }}</span> تومان </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right">تاریخ ثبت</th>
                                        <td class="text-center"><span class="fa-num">{{ jd($factor->date , "Y/m/d") }}</span> </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right">تاریخ ایجاد</th>
                                        <td class="text-center"><span class="fa-num">{{ jd($factor->created_at , "Y/m/d") }}</span> </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right">تاریخ ویرایش</th>
                                        <td class="text-center"><span class="fa-num">{{ jd($factor->updated_at , "Y/m/d") }}</span> </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right">توضیحات</th>
                                        <td class="text-right"><span class="fa-num">{{ $factor->description }}</span> </td>
                                    </tr>
                                    
                                </table>
                            </div>
                    </div>
        
                    <div class="col-6">
                            <div class="p-4">
                                <table class="table w-100 table-bordered table-sm h-100">
                                    <tr>
                                        <th class="text-center " colspan="2">اطلاعات مشتری</th>
                                    </tr>
                                    @if ($factor->customer_id)
                                    <tr>
                                        <th class="text-right">نام مشتری</th>
                                        <td class="text-center"> <a class="text-decoration-none" href="{{route('panel.customers.show',$factor->customer_id)}}">{{$factor->getCustomerName()}} </a></td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th class="text-right">شماره تماس</th>
                                        <td class="text-center"> <span class="fa-num">{{ $factor->mobile ?: '---' }} </span></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right">آدرس</th>
                                        <td class="text-center"> {{ $factor->address ?: '---'}} </td>
                                    </tr>
                                </table>
                            </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">

                    <div class="p-4">
                        <h3>مشاهده اقلام فاکتور</h3>
                    @include('components.factor.show.show_factor_list_item')
                    </div>

                    <div class="p-4">
                        <h3>مشاهده پرداختی های فاکتور</h3>
                    @include('components.factor.show.show_payment_factor')
                    </div>

            </div>

        </div>
    </div>
    

</div>

@endsection