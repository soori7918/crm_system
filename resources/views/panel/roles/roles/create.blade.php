@extends('panel.layouts.master')

@section('content')

<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
                <h1 class="m-0 text-secondary">افزودن نقش جدید</h1>
                <a class="btn btn-secondary" href="{{ route('panel.roles.index') }}">
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
                    <form method="POST" action="{{ route('panel.roles.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label for="name" class="col-sm-4 text-sm-left col-form-label text-nowrap">نام نقش</label>
                            <div class="col-sm-5">
                                <input
                                    type="text"
                                    class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                    id="name"
                                    name="name"
                                    required
                                    value="{{ old('name') }}"
                                    oninvalid="this.setCustomValidity('نام نقش را وارد کنید')"
                                    oninput="setCustomValidity('')"
                                >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="title" class="col-sm-4 text-sm-left col-form-label text-nowrap">عنوان نقش</label>
                            <div class="col-sm-5">
                                <input
                                    type="text"
                                    class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
                                    id="title"
                                    name="title"
                                    required
                                    value="{{ old('title') }}"
                                    oninvalid="this.setCustomValidity('عنوان نقش را وارد کنید')"
                                    oninput="setCustomValidity('')"
                                >
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-5 offset-sm-4">
                                <button type="submit" class="btn btn-primary">افزودن نقش</button>
                                <a class="btn btn-secondary" href="{{ route('panel.roles.index') }}">
                                    بازگشت
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection