@extends('panel.layouts.master')

@section('content')

<div class="content-wrapper">

    
    
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-12">
                <h1 class="m-0 text-dark">ویرایش اطلاعات  {{$user->name}}</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
           <div class="card shadow-sm p-2">
                {{-- @include('panel.users.tab') --}}
                @include('components.messages')
                <form action="{{route('panel.users.update', $user)}}" method="post" enctype="multipart/form-data" >
                        @csrf
                        @method('put')
                        <div class="row">
                            

                            <div class="col-lg-12 p-4">
                                <div class="row">
                                    <div class="col-lg-6 form-group">
                                        <label for="name">نام و نام خانوادگی</label>
                                        <input type="text" name="name" class="form-control " id="name" value="{{old('user') ?: $user->name}}" >
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <label for="mobile">شماره تماس</label>
                                        <input type="text" name="mobile" class="form-control " id="mobile" value="{{ old('mobile') ?: $user->mobile}}" >
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <label for="email">ایمیل </label>
                                        <input type="text" name="email" class="form-control " id="email" value="{{old('email') ?: $user->email}}" >
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <label for="password">رمز عبور </label>
                                        <input type="password" name="password" class="form-control " id="password" value="{{old('password') }}" >
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <label for="password_confirmation">تکرار رمز </label>
                                        <input type="password" name="password_confirmation" class="form-control " id="password_confirmation" value="{{old('password_confirmation') }}" >
                                    </div>
                                  
                                    <div class="col-lg-12 form-group">
                                        <label for="address">آدرس </label>
                                        <textarea name="address" id="address" class="form-control" cols="30" rows="10">{{old('address') ?: $user->address}}</textarea>
                                    </div>
                                    <div class="col-lg-12 form-group p-2">
                                        <input type="checkbox" class="is_active" name="is_active" {{$user->is_active == true ? 'checked' : ''}} id="is_active">
                                        <label for="is_active">کاربر فعال </label>
                                    </div>
                                    <div class="col-lg-12">
                                        <button class="btn btn-success" type="submit">ویرایش اطلاعات</button>
                                        <a href="{{route('panel.users.index')}}" class="btn btn-secondary" type="submit">بازگشت</a>
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