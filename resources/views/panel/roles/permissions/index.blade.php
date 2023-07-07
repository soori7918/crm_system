@extends('panel.layouts.master')

@section('content')

<div class="content-wrapper">
    
    <div class="content-header">
        <div class="container-fluid">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <h1 class="m-0 text-secondary">مدیریت دسترسی ها</h1>
                <a class="btn btn-primary" href="{{ route('panel.permissions.create') }}">
                    افزودن دسترسی جدید
                </a>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            @include('panel.roles.tab')
            @include('components.messages')
            <div class="card">
                <div class="card-body">
                    <form method="get">
                        <div class="row">
                            <div class="col-12 col-lg-3 form-group">
                                <input type="text"
                                       id="title"
                                       name="title"
                                       class="form-control"
                                       value="{{ request('title') }}"
                                       placeholder="نام و یا عنوان دسترسی">
                            </div>
                            <div class="col-12 col-lg-3 form-group">
                                <select name="group" id="group" class="form-control selectpicker" title="انتخاب گروه">
                                    <option value="">نمایش همه</option>
                                    @foreach($groups as $group)
                                        <option value="{{ $group->id }}" {{ request('group') == $group->id ? 'selected' : '' }}>{{ $group->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-3 form-group">
                                <button class="btn btn-success" type="submit">جستجو</button>
                                <a href="{{ route('panel.permissions.index') }}" class="btn btn-secondary">
                                    نمایش همه
                                </a>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-hover m-0 text-center text-nowrap">
                            <thead class="bg-light">
                                <tr>
                                    <th class="p-2">#</th>
                                    <th class="p-2">نام دسترسی</th>
                                    <th class="p-2">عنوان دسترسی</th>
                                    <th class="p-2">گروه</th>
                                    <th class="p-2"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($permissions as $key => $permission)
                                    <tr>
                                        <td>{{ $permissions->firstItem() + $key }}</td>
                                        <td>{{ $permission->name }}</td>
                                        <td>{{ $permission->title }}</td>
                                        <td>{{ $permission->group->title ?? '' }}</td>
                                        <td class="p-2">
                                            <a class="btn btn-sm btn-primary" href="{{ route('panel.permissions.edit',$permission) }}" title="ویرایش">
                                                ویرایش
                                            </a>
                                            <form class="d-inline" method="post" action="{{ route('panel.permissions.destroy',$permission) }}">
                                                @csrf
                                                @method('delete')
                                                <button class="btn btn-sm btn-danger" type="submit"
                                                        onclick="return confirm('آیا مطمئن هستید؟')" title="حذف">
                                                    حذف
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $permissions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    </div>
    
@endsection
