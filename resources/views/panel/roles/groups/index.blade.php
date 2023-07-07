@extends('panel.layouts.master')

@section('content')

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <h1 class="m-0 text-secondary">مدیریت گروه های دسترسی</h1>
                <a class="btn btn-primary" href="{{ route('panel.permissionGroups.create') }}">
                    افزودن گروه جدید
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
                    <div class="table-responsive">
                        <table class="table table-hover m-0 text-center text-nowrap">
                            <thead class="bg-light">
                                <tr>
                                    <th class="p-2">#</th>
                                    <th class="p-2">نام گروه</th>
                                    <th class="p-2">عنوان گروه</th>
                                    <th class="p-2"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($groups as $group)
                                    <tr>
                                        <td>{{ $group->id }}</td>
                                        <td>{{ $group->name }}</td>
                                        <td>{{ $group->title }}</td>
                                        <td class="p-2">
                                            <a class="btn btn-sm btn-primary" href="{{ route('panel.permissionGroups.edit',$group) }}" title="ویرایش">
                                                ویرایش
                                            </a>
                                            <form class="d-inline" method="post" action="{{ route('panel.permissionGroups.destroy',$group) }}">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    </div>
    
@endsection
