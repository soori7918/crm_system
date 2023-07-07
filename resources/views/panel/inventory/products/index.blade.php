@extends('panel.layouts.master')

@section('content')

<div class="content-wrapper">

    
    <div class="content-header">
        <div class="container-fluid px-4">
            <div class="row mb-2 d-flex flex-wrap justify-content-between">
               
                <h1 class="m-0 text-dark">مدیریت محصولات </h1>
               
                <div>
                    <a href="{{route('panel.inventory.products.create')}}" class="btn btn-sm btn-primary p-2">افزودن محصول جدید</a>
                    <a href="{{route('panel.dashboard')}}" class="btn btn-sm btn-secondary p-2">بازگشت</a>
                </div>
            </div>
        </div>
    </div>







    <div class="content">
        <div class="container-fluid">
           <div class="card shadow-sm">
           
            @include('components.messages')
                <div class="col-12">
                    <form action="" method="get">
                        <div class="row mt-2">
                            <div class="col-lg-3">
                                <input type="text" name="search" placeholder="جستجو براساس نام" class="form-control ml-2">
                            </div>
                        
                            <div class="col-lg-4">
                                <button type="submit" class="btn  btn-success ">جستجو</button>
                                <a href="{{ route('panel.inventory.products.index') }}" class="btn btn-secondary mr-2">
                                    نمایش همه
                                </a>
                            </div>

                        </div>
                    </form>
                </div>
               <div class="col-12 py-2">
                   @if($products->count() > 0)
                    <table class="table table-sm">
                        <thead class="thead-light">
                        <tr>
                            <th class="text-center py-2">#</th>
                            <th class="text-center py-2">تصویر</th>
                            <th class="text-center py-2">نام محصول</th>
                            <th class="text-center py-2">قیمت محصول</th>
                            <th class="text-center py-2">هزینه اجاره</th>
                            <th class="text-center py-2">تعداد</th>
                            <th class="text-center py-2"> دسته بندی</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $key => $product)
                                <tr>
                                    <td  class="text-center align-items-center py-2">{{++$key}}</td>
                                    <td  class="text-center align-items-center py-2">
                                        <img src="{{ getImageSrc($product->getImage() , 'avatar') }}" class="rounded-circle" alt="">    
                                    </td>
                                    <td  class="text-center align-items-center py-2"><strong>{{$product->name}}</strong></td>
                                    <td  class="text-center align-items-center py-2 "> <span  class="fa-num">{{number_format($product->sale_price)}}</span> تومان  </td>
                                    <td  class="text-center align-items-center py-2 "> <span  class="fa-num">{{number_format($product->rent_price)}}</span> تومان </td>
                                    <td  class="text-center align-items-center py-2 "> <span  class="fa-num">{{number_format($product->amount)}}</span> </td>
                                    <td  class="text-center align-items-center py-2 ">
                                        @foreach ($product->categories as $category)
                                            <span class="badge badge-light m-1">{{$category->name}}</span>
                                        @endforeach
                                    </td>
                                   
                                    <td  class="text-center align-items-center py-2">
                                        <form method="post" action="{{route('panel.inventory.products.destroy' , $product)}}">
                                            @csrf
                                            @method('delete')
                                            <a href="{{route('panel.inventory.products.show', $product)}}" class="btn btn-sm btn-info">مشاهده</a>
                                            <a href="{{route('panel.inventory.products.edit' , $product)}}" class="btn btn-sm btn-success">ویرایش</a>
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
               
           </div>
        </div>
    </div>

</div>

@endsection