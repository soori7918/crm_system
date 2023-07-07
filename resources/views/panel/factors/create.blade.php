@extends('panel.layouts.master')
@section('head')
    <link href="{{ asset('/css/persian-datepicker.css') }}" rel="stylesheet">
    <link href="{{asset('/css/bootstrap-select.min.css')}}" rel="stylesheet"> 
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css" rel="stylesheet"> 
    <style>
        .jq-toast-single * {
            font-family: 'IRANSans-web' !important;
        }
    </style>
@endsection
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="d-flex flex-wrap justify-content-between align-items-center py-2 mb-2">
                    <h2 class="m-0 text-dark">ثبت فاکتور درآمد</h2>
                    <a class="btn btn-secondary" href="{{route('panel.factors.index')}}">بازگشت </a>
        
                </div>
            </div>
        </div>
        <div class="content">
            <div class="container-fluid">
                


                <div class="card shadow-sm">
                    <div class="py-4 px-4 col-lg-12">
                        @include('components.messages')
                        <div class="col-12 col-sm-12 col-md-12">
                            <form method="POST" action="{{ route('panel.factors.store') }}" enctype="multipart/form-data">
                                @csrf
                                    <div class="row">
                                        <div class="form-group col-12 col-sm-3">
                                            <label for="code" >شماره فاکتور</label>
                                            <input type="text" name="code" class="form-control"  id="code">
                                        </div>
                                        <div class="form-group col-12 col-sm-3">
                                            <label for="enter_date_picker" >تاریخ ثبت </label>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control"  autocomplete="off" id="enter_date_picker">
                                                <input type="hidden" class="form-control" id="enter_date" name="enter_date"> 
                                                <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar" aria-hidden="true"></i>
                                                </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-12 col-sm-3">
                                            <label for="customer_id" >مشتری</label>
                                                <select name="customer_id" id="customer_id" class="form-control">
                                                    <option value="">انتخاب نمایید</option>
                                                    @foreach ($customers as $customer)
                                                        <option value="{{$customer->id}}">{{$customer->name}}</option>
                                                    @endforeach
                                                </select>
                                        </div>
                                        
                                        <div class="form-group col-12 col-sm-3">
                                            <label for="customer_id" >تلفن</label>
                                            <input type="text" name="mobile" id="mobile" placeholder="شماره تماس را وارد نمایید" class="form-control">
                                        </div>
                                        <div class="form-group col-12 col-sm-12">
                                            <label for="customer_id" >آدرس</label>
                                            <textarea name="address" id="address" cols="30" class="form-control" placeholder="آدرس را وارد نمایید" rows="1"></textarea>
                                        </div>
                                        <div class="col-12 col-lg-12">
                                            <div id="factorItems" >
                                                @include('components.factor.factorItems')
                                            </div>
                                        </div>
                                    </div>
                            
                                    <div class="row">
                                        <div class="col-12 col-lg-12">
                                            <div id="Paymentitem" >
                                                @include('components.factor.factorPayments')
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 ">
                                            <div class="d-flex flex-wrap justify-content-between">
                                                <span class="p-2"><strong>مبلغ کل</strong> </span>
                                                <span class="p-2">.... تومان</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="d-flex flex-wrap justify-content-between">
                                                <span class="p-2"> <strong>پرداخت شده</strong> </span>
                                                <span class="p-2"> .... تومان</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="d-flex flex-wrap justify-content-between">
                                                <span class="p-2"> <strong>تخفیف</strong> </span>
                                                <span><input type="text" name="discount" id="discount" class="form-control" placeholder="مقدار تخفیف را وارد کنید" value="{{old('discount')}}"></span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="d-flex flex-wrap justify-content-between">
                                                <span class="p-2"> <strong>مبلغ باقی مانده</strong> </span>
                                                <span class="p-2">.... تومان</span>
                                            </div>
                                        </div>
                                        <div class="col-12 py-4">
                                          <label for="description">توضیحات</label>
                                          <textarea name="description" id="description" placeholder="توضیحات را وارد نمایید" class="form-control" cols="30" rows="2"></textarea>
                                        </div>
                                        <div class="col-12 py-2">
                                          <label for="wallet_id">صندوق</label>
                                          <select name="wallet_id" id="wallet_id" class="form-control">
                                              @foreach ($wallets as $wallet)
                                                <option value="{{$wallet->id}}">{{$wallet->title}}</option>
                                              @endforeach
                                          </select>
                                        </div>
                                    </div>

                                    <div class="form-group col-12 col-sm-12 m-4">
                                        <button type="submit" class="btn btn-success px-5">ثبت</button>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>





    <div class="modal fade" id="addProduct" tabindex="-1" role="dialog" aria-labelledby="addProductTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">افزودن اقلام فاکتور</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">
                <form id="addItemForm" method="POST" action="{{ route('panel.CreateFactor.addItem') }}">
                    @csrf
                    <div class="row">
                        <div class="col-12 form-group">
                            <select name="product_id" class="form-control selectpicker">
                                <option value="">انتخاب کنید</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" 
                                        data-rentprice="{{ $product->rent_price }}" 
                                        data-saleprice="{{ $product->sale_price }}" 
                                        data-name="{{ $product->name }}">{{$product->name}}</option>
                                @endforeach
                            </select>
                            <div class="row d-none mt-2 px-2 fa-num" id="productHint">
                                <div class="col-6">
                                    <small>مبلغ اجاره: <span class="text-muted" id="productRentPrice"></span></small>
                                </div>
                                <div class="col-6">
                                    <small>مبلغ فروش: <span class="text-muted" id="productSalePrice"></span></small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12 form-group">
                            <input type="text" required placeholder="عنوان" 
                                class="form-control" 
                                name="title">
                        </div>
                        <div class="col-6 form-group">
                            <input type="number" required placeholder="تعداد" 
                                class="form-control" name="amount">
                        </div>
                        <div class="col-6 form-group">
                            <input type="number" required placeholder="مبلغ" 
                                class="form-control" name="price">
                        </div>
                        <div class="col-12 form-group">
                            <textarea name="description" class="form-control" placeholder="توضیحات"></textarea>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-success" type="submit">افزودن</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                        </div>
                    </div>  
                </form>
            </div>

          </div>
        </div>
    </div>


    <div class="modal fade" id="addPayment" tabindex="-1" role="dialog" aria-labelledby="addPaymentTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">افزودن پرداختی</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">
                <form id="addPaymentForm" action="{{route('panel.CreateFactor.addPayment')}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-6 form-group">
                            <input type="text" name="price"  class="form-control" placeholder="مبلغ">
                        </div>
                        <div class="col-6 form-group">
                            <select name="type"  class="form-control">
                                @foreach(App\Models\factorPayment::$types as $key=>$value)
                                    <option value="{{$key}}">{{$value}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6 form-group">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control"  placeholder="تاریخ پرداخت" autocomplete="off" id="register_date_picker">
                                <input type="hidden" class="form-control" id="register_date" name="register_date"> 
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 form-group">
                            <select name="is_done" class="form-control">
                                    <option value="1">پرداخت شده</option>
                                    <option value="0">در انتظار پرداخت</option>
                            </select>
                        </div>
                        <div class="col-12 form-group">
                            <button class="btn btn-success" type="submit">افزودن</button>
                            {{-- <button class="btn btn-success btn-add-Payment" type="button"> افزودن </button> --}}
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                        </div>
                    </div>
                </form>  
            </div>

          </div>
        </div>
    </div>



@endsection


@section('scripts')
    <script>
        $('.selectpicker').selectpicker()
    </script>
    <script src="{{asset('/js/bootstrap-select.min.js')}}"></script>
    <script src="{{ asset('/js/persian-date.min.js') }}"></script>
    <script src="{{ asset('/js/persian-datepicker.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js" integrity="sha384-qlmct0AOBiA2VPZkMY3+2WqkHtIQ9lSdAsAn5RUJD/3vA5MKDgSGcdmIv4ycVxyn" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js" ></script>

    <script>
        $(document).ready(function () {
            
            var date;
            var register_date;
        
            $(document).ready(function () {
                enter_date = $('#enter_date_picker').persianDatepicker({
                    format: 'YYYY/MM/DD',
                    altField: '#enter_date',
                    observer: false,
                    initialValue: false,
                });
        
                register_date = $('#register_date_picker').persianDatepicker({
                    format: 'YYYY/MM/DD',
                    altField: '#register_date',
                    observer: false,
                    initialValue: false,
        
                });
                
        
            @if(old('enter_date'))
                enter_date.setDate(parseFloat("{{ old('enter_date') }}"));
            @endif
            @if(old('register_date'))
                register_date.setDate(parseFloat("{{ old('register_date') }}"));
            @endif
            });
        });
        
    </script>

    <script>
        $('body').on('click','.btn-remove-item', function() {
            $.post("{{ route('panel.CreateFactor.removeItem') }}",{
                _token: "{{ csrf_token() }}",
                rowId: $(this).data('rowid'),
            }).done(response => {
                $('#factorItems').html(response)
                $.toast({
                    heading: 'حذف آیتم',
                    text: 'آیتم مورد نظر حذف شد',
                    icon: 'error',
                })
            }).fail(error => {
                $('#factorItems').html(error)
            });
        }).on('click','.btn-remove-payment', function() {
            $.post("{{ route('panel.CreateFactor.removePayment') }}",{
                _token: "{{ csrf_token() }}",
                rowId: $(this).data('paymentid'),
            }).done(response => {
                $('#Paymentitem').html(response)
                $.toast({
                    heading: 'حذف پرداخت',
                    text: 'پرداختی مورد نظر حذف شد',
                    icon: 'error',
                })
            }).fail(error => {
                $('#Paymentitem').html(error)
            });
        }).on('click','.btn-increase', function() {
            $.get("{{ route('panel.createFactor.increase') }}",{
                rowId: $(this).attr("data-id"),
            }).done(response => {
                $('#factorItems').html(response)
            }).fail(error => {
                console.log(error)
            });
        }).on('click','.btn-decrease', function() {
            $.get("{{ route('panel.createFactor.decrease') }}",{
                rowId: $(this).attr("data-id"),
            }).done(response => {
                $('#factorItems').html(response)
            }).fail(error => {
                console.log(error)
            });
        }).on('submit','#addItemForm', function(e) {
            e.preventDefault();
        }).on('change','select[name="product_id"]', function() {
            let selected = $('select[name="product_id"] :selected');
            if(!!selected.val()) {
                $('#productHint').removeClass('d-none');
                $('#productRentPrice').text(selected.attr('data-rentprice'));
                $('#productSalePrice').text(selected.attr('data-saleprice'));
                $('#addItemForm input[name="title"]').val(selected.attr('data-name'));

            } else {
                $('#productHint').addClass('d-none');
                $('#productRentPrice').text('');
                $('#productSalePrice').text('');
                $('#addItemForm input[name="title"]').val('');
            }
        }).on('submit','#addPaymentForm', function(e) {
            e.preventDefault();
        });
        
        $('#addItemForm').ajaxForm({
            success: function(response, textStatus, xhr, form) {
                console.log(response)
                $('#factorItems').html(response)
                $.toast({
                    heading: 'تایید',
                    text: 'آیتم مورد نظر اضافه شد',
                    icon: 'success',
                })
            },
            error: function(xhr, textStatus, errorThrown) {
                let res = xhr.responseJSON;
                $.toast({
                    heading: 'خطا',
                    text: res.message,
                    icon: 'error',
                })
            },
        });
        $('#addPaymentForm').ajaxForm({
            success: function(response, textStatus, xhr, form) {
                console.log(response)
                $('#Paymentitem').html(response)
                $.toast({
                    heading: 'تایید',
                    text: 'آیتم مورد نظر اضافه شد',
                    icon: 'success',
                })
            },
            error: function(xhr, textStatus, errorThrown) {
                let res = xhr.responseJSON;
                $.toast({
                    heading: 'خطا',
                    text: res.message,
                    icon: 'error',
                })
            },
        });
            
    </script>
   

   <script>
        $('body').on('change','#customer_id', function() {
            $.post("{{ route('panel.getCustomer') }}",{
                _token: "{{ csrf_token() }}",
                customer_id: $( "#customer_id" ).val(),
            }).done(response => {
               $('#mobile').val(response[0]['mobile'])
               $('#address').val(response[0]['address'])


            }).fail(error => {
                console.log(error)
            })
        })

   </script>

@endsection