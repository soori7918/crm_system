@extends('panel.layouts.master')

@section('content')

<div class="content-wrapper">

    
    <div class="content-header">
        <div class="container-fluid px-4">
            <div class="row mb-2 d-flex flex-wrap justify-content-between">
               
                <h1 class="m-0 text-dark">مدیریت کاربران </h1>
               
                <div>
                    <a href="{{route('panel.users.create')}}" class="btn btn-sm btn-primary p-2">افزودن کاربر جدید</a>
                    <a href="{{route('panel.dashboard')}}" class="btn btn-sm btn-secondary p-2">بازگشت</a>
                </div>
            </div>
        </div>
    </div>







    <div class="content">
        <div class="container-fluid">
           <div class="card shadow-sm">
           
            @include('components.messages')
                <form action="" method="get">
                  <div class="col-12">
                      <div class="row py-2">
                          <div class="col-lg-3">
                              <input type="text" name="search" placeholder="جستجو براساس نام ،شماره تماس" class="form-control ml-2">
                          </div>
                          <div class="col-lg-2">
                              <select name="status" id="status" class="form-control ml-2">
                                  <option value="a">فعال</option>
                                  <option value="d">غیر فعال</option>
                              </select>
                          </div>
                          <div class="col-lg-4">
                              <button type="submit" class="btn  btn-success ">جستجو</button>
                              <a href="{{ route('panel.users.index') }}" class="btn btn-secondary mr-2">
                                  نمایش همه
                              </a>
                          </div>
  
                      </div>
                  </div>
                </form>
               <div class="col-12">
                   @if($users->count() > 0)
                    <table class="table table-sm">
                        <thead class="thead-light">
                        <tr>
                            <th class="text-center py-2">#</th>
                            <th class="text-center py-2">نام و نام خانوادگی</th>
                            <th class="text-center py-2">شماره تماس</th>
                            <th class="text-center py-2"> ایمیل</th>
                            <th class="text-center py-2">وضعیت</th>
                            <th class="text-center py-2"></th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td  class="text-center py-2">{{{$user->id}}}</td>
                                    <td  class="text-center py-2">{{$user->name ?: '---'}}</td>
                                    <td  class="text-center py-2">{{$user->mobile}}</td>
                                    <td  class="text-center py-2">{{$user->email ?: '---'}}</td>
                                    <td  class="text-center py-2">
                                        <a href="{{route('panel.users.changeActive' , $user)}}">
                                            @if($user->is_active)
                                                <span class="badge badge-success">فعال</span>
                                            @else
                                                <span class="badge badge-danger">غیرفعال</span>
                                            @endif
                                        </a>
                                    </td>
                                    <td  class="text-center py-2">
                                        <form method="post" action="{{route('panel.users.destroy' , $user)}}">
                                            @csrf
                                            @method('delete')
                                            <a href="{{route('panel.users.show', $user)}}" class="btn btn-sm btn-info">مشاهده</a>
                                            <a href="{{route('panel.users.edit' , $user)}}" class="btn btn-sm btn-success">ویرایش</a>
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