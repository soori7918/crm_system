@extends('panel.layouts.master')

@section('content')

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
                <h1 class="m-0 text-secondary">مشاهده جزئیات {{$product->name}}</h1>
                <a class="btn btn-secondary" href="{{ route('panel.products.index') }}">
                    بازگشت
                </a>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            @include('components.messages')
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-8">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <td>شناسه</td>
                                        <th>{{ $product->id }}</th>
                                    </tr>
                                    <tr>
                                        <td>نام</td>
                                        <th>{{ $product->name }}</th>
                                    </tr>
                                    <tr>
                                        <td>قیمت محصول</td>
                                        <th><span class="fa-num">{{ $product->sale_price }} تومان </span></th>
                                    </tr>
                                    <tr>
                                        <td>هزینه اجاره</td>
                                        <th><span class="fa-num">{{ $product->rent_price }} تومان </span></th>
                                    </tr>
                                    <tr>
                                        <td>تعداد </td>
                                        <th><span class="fa-num">{{ $product->amount ?: '---'}} </span></th>
                                    </tr>
                                    <tr>
                                        <td>دسته بندی محصول</td>
                                        <th>
                                            @foreach ($product->categories as $product_category)
                                               <span class="badge badge-light"> {{$product_category->name}}</span>
                                            @endforeach
                                        </th>
                                    </tr>
                                 
                                   
                                    <tr>
                                        <td>توضیحات</td>
                                        <th>
                                            {{ $product->description  }}
                                        </th>
                                    </tr>
                                </table>
                            </div>

                            <a class="btn btn-primary" href="{{ route('panel.products.edit',$product) }}">
                                ویرایش محصول
                            </a>
                           
                            <form class="d-inline" method="post" action="{{ route('panel.products.destroy', $product) }}">
                                @csrf
                                @method('delete')
                                <button class="btn btn-danger"
                                        type="submit"
                                        onclick="return confirm('آیا مطمئن هستید؟')" title="حذف">
                                    حذف
                                </button>
                            </form>
                        </div>
                       
                    </div>
                    
                </div>
            </div>

          

            
        </div> {{-- end of container-fluid --}}
    </div>
    
</div>

    
@endsection