@extends('panel.layouts.master')

@section('content')

<div class="content-wrapper">
   
    <div class="content-header">
        <div class="container-fluid px-4">
            <div class="row mb-2 d-flex flex-wrap justify-content-between">
                <h1 class="m-0 text-dark">مدیریت مشتریان </h1>
                <div>
                    <a href="{{route('panel.customers.create')}}" class="btn btn-sm btn-primary p-2">افزودن مشتری جدید</a>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
           <div class="card shadow-sm">
           
            @include('components.messages')
                <form action="" method="get">
                  <div class="col-12 py-2">
                      <div class="row ">
                          <div class="col-lg-3">
                              <input type="text" name="search" placeholder="جستجو براساس نام ،شماره تماس" class="form-control ml-2">
                          </div>
                          <div class="col-lg-4">
                              <button type="submit" class="btn  btn-success ">جستجو</button>
                              <a href="{{ route('panel.customers.index') }}" class="btn btn-secondary mr-2">
                                  نمایش همه
                              </a>
                          </div>
                      </div>
                  </div>
                </form>
               <div class="col-12">
                   @if($customers->count() > 0)
                    <table class="table table-sm">
                        <thead class="thead-light">
                        <tr>
                            <th class="text-center py-2">#</th>
                            <th class="text-center py-2">نام و نام خانوادگی</th>
                            <th class="text-center py-2">شماره همراه</th>
                            <th class="text-center py-2"> شماره تلفن</th>
                            <th class="text-center py-2">شماره تلفن</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($customers as $customer)
                                <tr>
                                    <td  class="text-center py-2">{{{$customer->id}}}</td>
                                    <td  class="text-center py-2">{{$customer->name ?: '---'}}</td>
                                    <td  class="text-center py-2 fa-num">{{$customer->mobile ?: '---'}}</td>
                                    <td  class="text-center py-2 fa-num">{{$customer->phone1 ?: '---' }}</td>
                                    <td  class="text-center py-2 fa-num">{{$customer->phone2 ?: '---'}}</td>
                                    
                                    <td  class="text-center py-2">
                                        <form method="post" action="{{route('panel.customers.destroy' , $customer)}}">
                                            @csrf
                                            @method('delete')
                                            <a href="{{route('panel.customers.show', $customer)}}" class="btn btn-sm btn-info">مشاهده</a>
                                            <a href="{{route('panel.customers.edit' , $customer)}}" class="btn btn-sm btn-success">ویرایش</a>
                                            <button type="submit" class="btn btn-danger btn-sm" 
                                            onclick="return confirm('آیا مایل به حذف هستید؟')"  title="حذف"
                                            >حذف</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                       
                    </table>
                    @else
                        @include('components.empty')
                    @endif
               </div>
           </div>
        </div>
    </div>

</div>

@endsection