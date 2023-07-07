@extends('panel.layouts.master')

@section('content')

<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
                <h1 class="m-0 text-secondary">ویرایش نقش</h1>
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
                    <form method="POST" action="{{ route('panel.roles.update',$role) }}" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <div class="row">
                            <div class="col-12 col-lg-6 form-group">
                                <label for="name">نام نقش</label>
                                <input
                                    type="text"
                                    class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                    id="name"
                                    name="name"
                                    required
                                    value="{{ old('name') ?: $role->name }}"
                                    oninvalid="this.setCustomValidity('نام نقش را وارد کنید')"
                                    oninput="setCustomValidity('')"
                                >
                            </div>
                            <div class="col-12 col-lg-6 form-group">
                                <label for="title">عنوان نقش</label>
                                <input
                                    type="text"
                                    class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
                                    id="title"
                                    name="title"
                                    required
                                    value="{{ old('title') ?: $role->title }}"
                                    oninvalid="this.setCustomValidity('عنوان نقش را وارد کنید')"
                                    oninput="setCustomValidity('')"
                                >
                            </div>
                        </div>
                        <hr/>

                        <div class="d-flex flex-wrap mb-3">
                            @foreach($groups as $group)
                                <div class="bg-light p-2 m-2 rounded flex-grow-1 border">
                                    <div class="border-bottom pb-2 mb-3">
                                        <label class="m-0 d-flex align-items-center">
                                            <input data-group=".group-{{ $group->id }}" type="checkbox" class="ml-2 group-check">
                                            <strong>{{ $group->title }}</strong>
                                        </label>
                                    </div>
                                    @foreach($group->permissions as $permission)
                                        <label class="d-flex align-items-center form-check-label">
                                            <input name="permissions[]"
                                                   value="{{ $permission->id }}"
                                                   {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}
                                                   type="checkbox"
                                                   class="ml-2 group-{{ $group->id }}">
                                            <span>{{ $permission->title }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>

                        <button type="submit" class="btn btn-primary">ذخیره تغییرات</button>
                        <a class="btn btn-secondary" href="{{ route('panel.roles.index') }}">
                            بازگشت
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script>
        $('body').on('change','.group-check',function(){
            $($(this).data('group')).prop('checked',this.checked)
        });
    </script>
@endsection