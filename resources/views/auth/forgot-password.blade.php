@extends('layouts.master')

@section('content')
    <div class="bdy d-flex align-items-center justify-content-center">
        <div class="hover-bdy"></div>
        <div class="px-4 py-4 bg-white shadow-sm rounded-sm" style="z-index: 10001">
            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="py-2 text-center">
                    <strong style="font-size: 18px">یادآوری رمز عبور</strong>
                </div>
                <div class="py-2">
                    <div class=" form-group">
                        <label for="email">نام کاربری</label>
                        <div class="input-group">
                            <input type="text" name="email" class="form-control" id="email" value="{{old('email')}}" placeholder="نام کاربری خود را وارد نمایید">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-at"></i></span>
                            </div>
                        </div>
                    </div>
                   
                    <div class="form-group">
                        <button type="submit" class="btn btn-success w-100">ورود</button>
                    </div>
                </div>
            </form>
           
        </div>
    </div>

@endsection


