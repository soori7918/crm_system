@extends('panel.layouts.master')

@section('content')

<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
                <h1 class="m-0 text-secondary">ویرایش دسترسی</h1>
                <a class="btn btn-secondary" href="{{ route('panel.permissions.index') }}">
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
                    <form method="POST" action="{{ route('panel.permissions.update',$permission) }}" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <div class="form-group row">
                            <label for="name" class="col-sm-4 text-sm-left col-form-label text-nowrap">نام دسترسی</label>
                            <div class="col-sm-5">
                                <input
                                    type="text"
                                    class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                    id="name"
                                    name="name"
                                    required
                                    value="{{ old('name') ?: $permission->name }}"
                                    oninvalid="this.setCustomValidity('نام دسترسی را وارد کنید')"
                                    oninput="setCustomValidity('')"
                                >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="title" class="col-sm-4 text-sm-left col-form-label text-nowrap">عنوان دسترسی</label>
                            <div class="col-sm-5">
                                <input
                                    type="text"
                                    class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
                                    id="title"
                                    name="title"
                                    required
                                    value="{{ old('title') ?: $permission->title }}"
                                    oninvalid="this.setCustomValidity('عنوان دسترسی را وارد کنید')"
                                    oninput="setCustomValidity('')"
                                >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="title" class="col-sm-4 text-sm-left col-form-label text-nowrap">گروه دسترسی</label>
                            <div class="col-sm-5">
                                <select name="group" id="groups" class="form-control selectpicker" data-placeholder="انتخاب گروه">
                                    @foreach($groups as $group)
                                        <option value="{{ $group->id }}" {{ old('group') ? (old('group') == $group->id ? 'selected' : '') : ($permission->group_id == $group->id ? 'selected' : '') }}>
                                            {{ $group->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-5 offset-sm-4">
                                <button type="submit" class="btn btn-primary">ذخیره تغییرات</button>
                                <a class="btn btn-secondary" href="{{ route('panel.permissions.index') }}">
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