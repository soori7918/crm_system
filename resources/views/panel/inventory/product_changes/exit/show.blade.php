@extends('panel.layouts.master')

@section('content')

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="d-flex flex-wrap justify-content-between align-items-center py-2 mb-2">
                <h1 class="m-0 text-dark">مشاهده سند {{$product_change->getTypeTitle()}}</h1>
                <a class="btn btn-secondary" href="{{route('panel.inventory.productChanges.index')}}">بازگشت </a>
    
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="bg-white py-4 px-4">
                @include('panel.inventory.product_changes.tab')
            </div>
            @include('components.messages')
            <div class="row">
                <div class="col-12 col-lg-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h3 class="m-0 py-2 text-center fa-num">سند {{$product_change->getTypeTitle()}} با کد {{$product_change->code}}</h3>

                            <table class="table table-bordered   table-sm">
                                <tr>
                                    <td>تلفن</td>
                                    <td>ثبت شده توسط</td>
                                    @if($product_change->updated_by != null)
                                        <td>به روز رسانی توسط</td>
                                    @endif
                                    <td>مشتری</td>
                                </tr>
                                <tr>
                                    <td>تلفن فروشگاه</td>
                                    <td> 
                                        <a class="text-dark text-decoration-none" href="{{route('panel.users.show' ,['user' =>  $product_change->created_by])}}">{{$product_change->getCreatorName()}}</a>
                                    </td>
                                    @if($product_change->updated_by != null)
                                        <td>
                                            <a class="text-dark text-decoration-none" href="{{route('panel.users.show' ,['user' =>  $product_change->updated_by])}}">{{$product_change->getEditorName()}}</a>
                                        </td>
                                    @endif
                                    <td>
                                        @if ($product_change->customer_id )
                                            <a class="text-dark text-decoration-none" href="{{route('panel.customers.show' ,['customer' =>  $product_change->customer_id])}}">{{$product_change->getCustomerName()}}</a>
                                        @endif
                                    </td>
                                </tr>
                            </table>

                            <hr>
                              
                         
                        
                                <div class="py-2 d-flex flex-wrap justify-content-between align-items-center">
                                    <strong>تاریخ خروج</strong> 
                                    <span>{{jd($product_change->exit_date ,  'Y/m/d')}}</span>
                                </div> 
                                <div class="py-2 d-flex flex-wrap justify-content-between align-items-center">
                                    <strong>تاریخ برگشت</strong> 
                                    <span>{{jd($product_change->return_date ,  'Y/m/d')}}</span>
                                </div>
                              
                           
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-center">
                                <h3>لیست اقلام </h3>
                                <hr>
                            </div>
                            <table class="table table-bordered ">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>محصول</th>
                                        <th>تعداد</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($product_change->items as $key => $item)
                                        <tr>
                                            <td>{{$key++}}</td>
                                            <td>{{$item->product->name}}</td>
                                            <td>{{$item->amount}}</td>
                                        </tr> 
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>  
                </div>
            </div>

        </div> 
    </div>
    
</div>

    
@endsection