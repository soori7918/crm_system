@extends('panel.layouts.master')

@section('content')

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
                <h1 class="m-0 text-secondary">مشاهده اطلاعات</h1>
                <a class="btn btn-secondary" href="{{ route('panel.customers.index') }}">
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
                                        <th>{{ $customer->id }}</th>
                                    </tr>
                                    <tr>
                                        <td>نام</td>
                                        <th>{{ $customer->name }}</th>
                                    </tr>
                                    <tr>
                                        <td>موبایل</td>
                                        <th class="fa-num">{{ $customer->mobile ?: '---' }}</th>
                                    </tr>
                                    <tr>
                                        <td>شماره تلفن</td>
                                        <th class="fa-num">{{ $customer->phone1 ?: '---' }}</th>
                                    </tr>
                                    <tr>
                                        <td>شماره تلفن</td>
                                        <th class="fa-num">{{ $customer->phone2 ?: '---' }}</th>
                                    </tr>
                                    <tr>
                                        <td>آدرس</td>
                                        <th class="fa-num">{{ $customer->address ?: '---' }}</th>
                                    </tr>
                                    <tr>
                                        <td>توضیحات</td>
                                        <th class="fa-num">{{ $customer->description ?: '---' }}</th>
                                    </tr>
                                   
                                </table>
                            </div>

                            <a class="btn btn-primary" href="{{ route('panel.customers.edit',$customer) }}">
                                ویرایش اطاعات مشتری
                            </a>
                           
                            <form class="d-inline" method="post" action="{{ route('panel.customers.destroy', $customer) }}">
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

           

            
        </div> {{-- end of container-fluid --}}
    </div>
    
</div>

    
@endsection