@extends('panel.layouts.master')

@section('content')

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid px-4">
            <div class="row mb-2 d-flex flex-wrap justify-content-between">
                <h1 class="m-0 text-dark">ویرایش اطلاعات  {{$customer->name}}</h1>
                <div>
                    <a href="{{route('panel.customers.index')}}" class="btn btn-sm btn-secondary p-2">بازگشت</a>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
           <div class="card shadow-sm p-2">
                @include('components.messages')
                <form action="{{route('panel.customers.update', $customer)}}" method="post" enctype="multipart/form-data" >
                        @csrf
                        @method('put')
                        <div class="row">
                            <div class="col-lg-12 p-4">
                                <div class="row">
                                    <div class="col-lg-3 form-group">
                                        <label for="name">نام و نام خانوادگی</label>
                                        <input type="text" name="name" class="form-control " id="name" value="{{old('customer') ?: $customer->name}}" >
                                    </div>
                                    <div class="col-lg-3 form-group">
                                        <label for="mobile">شماره تماس</label>
                                        <input type="text" name="mobile" class="form-control " id="mobile" value="{{ old('mobile') ?: $customer->mobile}}" >
                                    </div>
                                    <div class="col-lg-3 form-group">
                                        <label for="phone1">شماره تلفن</label>
                                        <input type="text" name="phone1" class="form-control " id="phone1" value="{{ old('phone1') ?: $customer->phone1}}" >
                                    </div>
                                    <div class="col-lg-3 form-group">
                                        <label for="phone2">شماره تلفن</label>
                                        <input type="text" name="phone2" class="form-control " id="phone2" value="{{ old('phone2') ?: $customer->phone1}}" >
                                    </div>
                                    
                                    <div class="col-lg-12 form-group">
                                        <label for="address">آدرس </label>
                                        <textarea name="address" id="address" class="form-control" cols="30" rows="3">{{old('address') ?: $customer->address}}</textarea>
                                    </div>
                                    <div class="col-lg-12 form-group">
                                        <label for="address">توضیحات </label>
                                        <textarea name="address" id="address" class="form-control" cols="30" rows="3">{{old('description') ?: $customer->description}}</textarea>
                                    </div>
                                   
                                    <div class="col-lg-12">
                                        <button class="btn btn-success" type="submit">ویرایش اطلاعات</button>
                                        <a href="{{route('panel.customers.index')}}" class="btn btn-secondary" type="submit">بازگشت</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
           </div>
        </div>
    </div>

</div>

@endsection