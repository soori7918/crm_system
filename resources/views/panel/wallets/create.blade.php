@extends('panel.layouts.master')

@section('content')

<div class="content-wrapper">

    
    <div class="content-header">
        <div class="container-fluid px-4">
            <div class="row mb-2 d-flex flex-wrap justify-content-between">
               
                <h1 class="m-0 text-dark">افزودن  کیف پول </h1>
               
                <div>
                    <a href="{{route('panel.dashboard')}}" class="btn btn-sm btn-secondary p-2">بازگشت</a>
                </div>
            </div>
        </div>
    </div>







    <div class="content">
        <div class="container-fluid">
           <div class="card shadow-sm">
           
            @include('components.messages')
            <div class="col-12 col-lg-5 m-5 p-5 bg-light">
                <form action="{{route('panel.wallets.store')}}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="title">عنوان</label>
                        <input type="text" class="form-control" name="title" id="title">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success w-100">ثبت </button>
                    </div>
                </form>
            </div>
           </div>
        </div>
    </div>

</div>

@endsection