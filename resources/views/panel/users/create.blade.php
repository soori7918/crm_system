@extends('panel.layouts.master')

@section('content')

<div class="content-wrapper">

    
    
    <div class="content-header">
        <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-12">
            <h1 class="m-0 text-dark">افزودن کاربر جدید</h1>
            </div>
        </div>

        

        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
           <div class="card shadow-sm">
            
               <div class="py-4 px-4 col-lg-10">
                @include('components.messages')

                   <form action="{{route('panel.users.store')}}" method="post" enctype="multipart/form-data" >
                    @csrf
                            <div class="row">
                                <div class="col-lg-6 form-group">
                                    <label for="name">نام و نام خانوادگی</label>
                                    <input type="text" name="name" class="form-control " value="{{old('name')}}" id="name" placeholder="نام و نام خانوادگی ">
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label for="mobile">شماره تماس</label>
                                    <input type="text" name="mobile" class="form-control " id="mobile" value="{{old('mobile')}}" placeholder="شماره تماس را وارد کنید ">
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label for="email">ایمیل </label>
                                    <input type="text" name="email" class="form-control " id="email" value="{{old('email')}}" placeholder="ایمیل را وارد کنید ">
                                </div>
                               
                               
                                <div class="col-lg-6 form-group">
                                    <label for="password">رمز عبور </label>
                                    <input type="password" name="password" class="form-control " id="password" value="{{old('password')}}" placeholder="***" >
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label for="password_confirmation">تکرار رمز </label>
                                    <input type="password" name="password_confirmation" class="form-control " id="password_confirmation" value="{{old('password_confirmation')}}"  placeholder="***">
                                </div>
                               
                                <div class="col-lg-12 form-group">
                                    <label for="address">آدرس </label>
                                    <textarea name="address" id="address" class="form-control" cols="30" placeholder="آدرس را وارد کنید" rows="10">{{old('address')}}</textarea>
                                </div>
                                <div class="col-lg-12 form-group p-2">
                                    <input type="checkbox" class="is_active" name="is_active" id="is_active">
                                    <label for="is_active">کاربر فعال </label>
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