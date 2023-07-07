@extends('panel.layouts.master')

@section('content')

<div class="content-wrapper">


    <div class="content-header">
        <div class="container-fluid">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
                <h1 class="m-0 text-secondary">مدیریت نقش ها</h1>
                <a class="btn btn-primary" href="{{ route('panel.roles.create') }}">
                    افزودن نقش جدید
                </a>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            @include('panel.roles.tab')
            @include('components.messages')
            
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover m-0 text-center text-nowrap">
                            <thead class="bg-light">
                                <tr>
                                    <th class="p-2">#</th>
                                    <th class="p-2">نام نقش</th>
                                    <th class="p-2">عنوان نقش</th>
                                    <th class="p-2"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $role)
                                    <tr>
                                        <td>{{ $role->id }}</td>
                                        <td>{{ $role->name }}</td>
                                        <td>{{ $role->title }}</td>
                                        <td class="p-2">
                                            <a class="btn btn-sm btn-primary" href="{{ route('panel.roles.edit',$role) }}" title="ویرایش">
                                                ویرایش
                                            </a>
                                            <form class="d-inline" method="post" action="{{ route('panel.roles.destroy',$role) }}">
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
