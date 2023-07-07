@extends('panel.layouts.master')

@section('content')

<div class="content-wrapper">

    
    
    <div class="content-header">
        <div class="container-fluid px-4">
            <div class="row mb-2 d-flex flex-wrap justify-content-between">
                <h1 class="m-0 text-dark">افزودن مشتری </h1>
                <div>
                    <a href="{{route('panel.customers.index')}}" class="btn btn-sm btn-secondary p-2">بازگشت</a>
                </div>
            </div>
        </div>
    </div>


    <div class="content">
        <div class="container-fluid">
           <div class="card shadow-sm">
            
               <div class="py-4 px-4 col-lg-12">
                @include('components.messages')

                   <form action="{{route('panel.customers.store')}}" method="post" enctype="multipart/form-data" >
                        @csrf
                        <div class="row">
                            <div class="col-lg-3 form-group">
                                <label for="name">نام و نام خانوادگی</label>
                                <input type="text" name="name" class="form-control " value="{{old('name')}}" id="name" placeholder="نام و نام خانوادگی ">
                            </div>
                            <div class="col-lg-3 form-group">
                                <label for="mobile">شماره تماس</label>
                                <input type="text" name="mobile" class="form-control " id="mobile" value="{{old('mobile')}}" placeholder="شماره تماس را وارد کنید ">
                            </div>
                            <div class="col-lg-3 form-group">
                                <label for="phone1">شماره تلفن </label>
                                <input type="text" name="phone1" class="form-control " id="phone1" value="{{old('phone1')}}" placeholder="شماره همراه را وارد کنید ">
                            </div>
                            <div class="col-lg-3 form-group">
                                <label for="phone2">شماره تلفن </label>
                                <input type="text" name="phone2" class="form-control " id="phone2" value="{{old('phone2')}}" placeholder="شماره همراه را وارد کنید ">
                            </div>
                            
                            <div class="col-lg-12 form-group">
                                <label for="address">آدرس </label>
                                <textarea name="address" id="address" class="form-control" cols="30" placeholder="آدرس را وارد کنید" rows="3">{{old('address')}}</textarea>
                            </div>
                            <div class="col-lg-12 form-group">
                                <label for="description">توضیحات </label>
                                <textarea name="description" id="description" class="form-control" cols="30" placeholder="توضیحات را وارد کنید" rows="3">{{old('description')}}</textarea>
                            </div>
                            
                            <div class="col-lg-12">
                                <button class="btn btn-success" type="submit">ثبت نام</button>
                            </div>
                        </div>
                    </form>
                  
               </div>
           </div>
        </div>
    </div>

</div>

@endsection


@section('scripts')
    <script>
        $('.is_active').prop('indeterminate', true)

    </script>
@endsection