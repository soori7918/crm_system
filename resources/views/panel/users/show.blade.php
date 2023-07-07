@extends('panel.layouts.master')

@section('content')

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
                <h1 class="m-0 text-secondary">مشاهده اطلاعات</h1>
                <a class="btn btn-secondary" href="{{ route('panel.users.index') }}">
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
                    <div class="row">
                        <div class="col-12 col-md-8">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <td>شناسه</td>
                                        <th>{{ $user->id }}</th>
                                    </tr>
                                    <tr>
                                        <td>نام</td>
                                        <th>{{ $user->name }}</th>
                                    </tr>
                                    <tr>
                                        <td>ایمیل</td>
                                        <th>{{ $user->email ?: '---' }}</th>
                                    </tr>
                                    <tr>
                                        <td>موبایل</td>
                                        <th class="fa-num">{{ $user->mobile ?: '---' }}</th>
                                    </tr>
                                    <tr>
                                        <td>وضعیت</td>
                                        <th>
                                            <span class="badge badge-{{ $user->is_active ? 'success' : 'danger' }}">
                                                {{ $user->is_active ? 'فعال' : 'غیرفعال' }}
                                            </span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>آدرس</td>
                                        <th>
                                            {{ $user->address  }}
                                        </th>
                                    </tr>
                                </table>
                            </div>

                            <a class="btn btn-primary" href="{{ route('panel.users.edit',$user) }}">
                                ویرایش کاربر
                            </a>
                           
                            <form class="d-inline" method="post" action="{{ route('panel.users.destroy', $user) }}">
                                @csrf
                                @method('delete')
                                <button class="btn btn-danger"
                                        type="submit"
                                        onclick="return confirm('آیا مطمئن هستید؟')" title="حذف">
                                    حذف
                                </button>
                            </form>
                        </div>
                       
                    </div>
                    
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-lg-6 d-flex flex-column">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="text-secondary">نقش های کاربر</h4>
                            <hr/>
                            <form method="post" action="{{ route('panel.users.addRole',$user) }}">
                                @csrf
                                <div class="row">
                                    <div class="col-12 col-lg-6 form-group">
                                        <select class="form-control selectpicker" name="role" title="انتخاب نقش">
                                            @foreach($roles as $role)
                                                <option value="{{ $role->name }}">{{ $role->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-lg-6 form-group">
                                        <button class="btn btn-success" type="submit">افزودن</button>
                                    </div>
                                </div>
                            </form>
                            @if($user->roles()->count())
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>عنوان نقش</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($user->roles as $role)
                                            <tr>
                                                <td>{{ $role->title }}</td>
                                                <td>
                                                    <a class="btn btn-sm btn-danger"
                                                       href="{{ route('panel.users.removeRole',[$user,$role]) }}"
                                                       onclick="return confirm('آیا مطمئن هستید؟')">
                                                        حذف
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                @include('components.empty')
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6 d-flex flex-column">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="m-0 text-secondary">دسترسی های کاربر</h4>
                            <hr/>
                            <form method="post" action="{{ route('panel.users.addPermission',$user) }}">
                                @csrf
                                <div class="row">
                                    <div class="col-12 col-lg-6 form-group">
                                        <select class="form-control selectpicker" name="permission" title="انتخاب دسترسی">
                                            @foreach($permissions as $permission)
                                                <option value="{{ $permission->name }}">{{ $permission->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-lg-6 form-group">
                                        <button class="btn btn-success" type="submit">افزودن</button>
                                    </div>
                                </div>
                            </form>
                            @if($user->permissions()->count())
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>عنوان دسترسی</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($user->permissions as $permission)
                                            <tr>
                                                <td>{{ $permission->title }}</td>
                                                <td>
                                                    <a class="btn btn-sm btn-danger"
                                                       href="{{ route('panel.users.revokePermission',[$user,$permission]) }}"
                                                       onclick="return confirm('آیا مطمئن هستید؟')"
                                                    >
                                                        حذف
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                @include('components.empty')
                            @endif
                        </div>
                    </div>
                </div>
            </div>


            
        </div> {{-- end of container-fluid --}}
    </div>
    
</div>

    
@endsection