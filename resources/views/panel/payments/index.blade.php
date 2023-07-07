@extends('panel.layouts.master')

@section('content')

<div class="content-wrapper">

    
    <div class="content-header">
        <div class="container-fluid px-4">
            <div class="row mb-2 d-flex flex-wrap justify-content-between">
               
                <h1 class="m-0 text-dark">مدیریت پرداخت ها</h1>
               
                <div>
                    <a href="{{route('panel.dashboard')}}" class="btn btn-sm btn-secondary p-2">بازگشت</a>
                </div>
            </div>
        </div>
    </div>







    <div class="content">
        <div class="container-fluid">
           <div class="card shadow-sm">
           
            @include('components.messages')
                  <div class="col-12 mt-2">
                        <form action="" method="get">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <select name="type" id="type" class="form-control">
                                            <option value="">انتخاب وضعیت</option>
                                            <option value="naghdi">نقدی</option>
                                            <option value="check">چک</option>
                                            <option value="aghsat">اقساط</option>
                                        </select>
                                    </div>
                                </div>
                            
                                <div class="col-lg-4">
                                    <button type="submit" class="btn  btn-success ">جستجو</button>
                                    <a href="{{ route('panel.manage_payments.index') }}" class="btn btn-secondary mr-2">
                                        نمایش همه
                                    </a>
                                </div>
                            </div>
                        </form>
                  </div>
               <div class="col-12">
                   @if($payments->count() > 0)
                    <table class="table table-sm">
                        <thead class="thead-light">
                        <tr>
                            <th class="text-center py-2">#</th>
                            <th class="text-center py-2">شماره فاکتور</th>
                            <th class="text-center py-2">نوع فاکتور</th>
                            <th class="text-center py-2">مشتری</th>
                            <th class="text-center py-2">نوع پرداخت</th>
                            <th class="text-center py-2">وضعیت</th>
                            <th class="text-center py-2">تاریخ ثبت</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($payments as  $key => $payment)
                                <tr>
                                    <td  class="text-center py-2">{{ ++$key}}</td>
                                    <td  class="text-center py-2 fa-num">
                                        <a href="{{route('panel.factors.show', $payment->factor_id)}}">
                                            {{$payment->factor->code}}
                                        </a>
                                    </td>
                                    <td  class="text-center py-2">{{$payment->factor->getTypeTitle()}}</td>
                                    <td  class="text-center py-2">
                                        @if ($payment->customer_id)
                                            <a href="{{route('panel.customers.show', $payment->factor->customer_id)}}" class="text-decoration-none text-black">{{$payment->factor->getCustomerName()}}</a>
                                        @else
                                            ---
                                        @endif
                                        </td>
                                    <td  class="text-center py-2"> {{$payment->getTypeTitle()}}</td>
                                    <td  class="text-center py-2"> 
                                        @if($payment->is_done)
                                            <span class='badge badge-success'>پرداخت شده</span>
                                        @else
                                            <span class='badge badge-danger'>پرداخت نشده</span>
                                        @endif
                                    </td>
                                    <td  class="text-center py-2"> {{jd($payment->date ,'Y/m/d' )}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                        @include('components.empty')
                    @endif
               </div>

               {{ $payments->links() }}

           </div>
        </div>
    </div>

</div>

@endsection

@section('scripts')
    <script>
        $("#type").change(function() {
            this.form.submit();
        });
    </script>
@endsection