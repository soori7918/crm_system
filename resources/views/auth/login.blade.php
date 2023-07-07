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
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="py-2 text-center">
                    <strong style="font-size: 18px">ورود به حساب کاربری</strong>
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
                        <label for="password">رمز عبور</label>
                        <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control" placeholder="***">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><i class="fa fa-key"></i></span>
                        </div>
                    </div>
                    </div>
                    <div class="form-group">
                            <input type="checkbox" id="remember_me" name="remember" />
                            <label class="ml-2 text-sm text-gray-600" for="remember_me">مرا به خاطر بسپار</label>
                    </div>
                    <div class="form-group">
                    <button type="submit" class="btn btn-success w-100">ورود</button>
                    </div>
                </div>
            </form>
            <div class="text-secondary py-2">
                <a href="{{route('password.request')}}" target="_blank" rel="noopener noreferrer" class="text-decoration-none">
                    رمز عبور خود را فراموش کرده ام ؟
                </a> </div>
        </div>
    </div>

@endsection


