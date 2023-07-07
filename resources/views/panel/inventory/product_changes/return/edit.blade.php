@extends('panel.layouts.master')
@section('head')
<link href="{{ asset('/css/persian-datepicker.css') }}" rel="stylesheet">
<link href="{{asset('/css/bootstrap-select.min.css')}}" rel="stylesheet"> 
<link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css" rel="stylesheet"> 

@endsection
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="d-flex flex-wrap justify-content-between align-items-center py-2 mb-2">
                    <h2 class="m-0 text-dark">ویرایش سند {{$product_change->getTypeTitle()}}</h2>
                    <a class="btn btn-secondary" href="{{route('panel.inventory.productChanges.index')}}">بازگشت </a>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="container-fluid">
                <div class="card shadow-sm">
                    <div class="py-4 px-4 col-lg-12">
                        @include('components.messages')
                        <div class="col-12 col-sm-12 col-md-12">
                            <form method="POST" action="{{ route('panel.inventory.productChanges.return.update' , ['return' => $product_change->id ]) }}" enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="row">
                                    <div class="form-group col-12 col-sm-3">
                                        <label for="code" >شماره سند </label>
                                        <input
                                            type="number"
                                            class="form-control {{ $errors->has('code') ? 'is-invalid' : '' }}"
                                            id="code"
                                            name="code"
                                            value="{{$product_change->code}}"
                                        >
                                        <input type="hidden" name="product_id" id="product_change_id" value="{{$product_change->id}}">
                                    </div>
                            
                                    <div class="form-group col-12 col-sm-3">
                                        <label for="type" >نوع فاکتور</label>
                                        <select name="type" id="type" class="form-control">
                                            <option value="return">خروج موقت</option>
                                            <option value="gharz">قرض</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-12 col-sm-3">
                                        <label for="exit_date_picker" >تاریخ خروج </label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control"  autocomplete="off" id="exit_date_picker" value="{{jd($product_change->return_date , 'Y/m/d')}}">
                                            <input type="hidden" class="form-control" id="exit_date" name="exit_at"> 
                                            <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar" aria-hidden="true"></i>
                                            </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-12 col-sm-3">
                                        <label for="customer_id" >مشتری</label>
                                            <select name="customer_id" id="customer_id" class="form-control selectpicker" data-live-search="true">
                                                <option value="">انتخاب نمایید</option>
                                                @foreach ($customers as $customer)
                                                    <option value="{{$customer->id}}" {{$product_change->customer_id == $customer->id ? 'selected' : ''}}>{{$customer->name}}</option>
                                                @endforeach
                                            </select>
                                    </div>
                                    
                                    <div class="form-group col-12 col-sm-3">
                                        <label for="mobile" >تلفن</label>
                                        <input type="text" name="mobile" class="form-control" id="mobile"  value="{{ old('mobile') ?: $product_change->mobile}}">
                                    </div>
    
                             
                                   
                                  
                                    <div class="form-group col-12 col-sm-9">
                                        <label for="title" >عنوان </label>
                                        <input
                                            type="text"
                                            class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
                                            id="title"
                                            name="title"
                                            required
                                            value="{{ old('title') ?: $product_change->title}} "
                                            placeholder=" عنوان را وارد کنید"
                                        >
                                    </div>
                                    <div class="form-group col-12 col-sm-12">
                                        <label for="address" >آدرس</label>
                                        <textarea name="address" id="address" class="form-control" cols="30" rows="1">{{ old('address') ?: $product_change->address}}</textarea>
                                    </div>
                                    
                                    <div id="productChangeItems"  style="width: 80% ; margin:auto ; margin-top:30px">
                                        @include('components.productChange.return.cartListItem')
                                    </div>
                                   

                                    @if ($product_change->type == "return")
                                        <div id="productChangReturnItems"  style="width: 80% ; margin:auto ; margin-top:30px">
                                            @include('components.productChange.return.returnListItem')
                                        </div>
                                    @endif

                                    <div class="form-group col-12 col-sm-12 ">
                                        <label for="description">توضیحات </label>
                                        <textarea name="description" class="form-control" id="description" cols="30" rows="1">{!! old('description') ?: $product_change->description !!}</textarea>
                                    </div>
                                  
                                    <div class="form-group col-12 col-sm-12">
                                        <button type="submit" class="btn btn-success px-5">ثبت</button>
                                    </div>
        
                                </div>
                        
                               


                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="createProductChange" tabindex="-1" role="dialog" aria-labelledby="createProductChangeTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">افزودن محصول سند</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <form id="addItemForm" action="{{route('panel.inventory.productChanges.return.addItemList' ,[ 'productChange' => $product_change->id])}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-lg-12">
                            <div class="form-group">
                                <select id="product_id" name="product_id" class="form-control selectpicker">
                                    @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}">{{$product->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-lg-12">
                            <div class="form-group">
                            <input type="number" placeholder="تعداد" class="form-control" name="amount" id="amount">
                            </div>
                        </div>
                        <div class="col-12 col-lg-12">
                            <div class="form-group">
                            <textarea name="description" id="description" cols="30" class="form-control" rows="5">{{old('description')}}</textarea>
                            </div>
                        </div>
                        <div class="col-12 col-lg-12 d-felx justify-content-between align-items-center">
                            <button class="btn btn-success btn-add" type="submit">افزودن </button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                        </div>
                    </div>  
                    </form>
            </div>
            
          </div>
        </div>
    </div>


    <div class="modal fade" id="returnItems" tabindex="-1" role="dialog" aria-labelledby="returnItemsTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">افزودن اقلام سند</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <form action="{{route('panel.inventory.productChanges.return.addReturnItem' ,['productChange' => $product_change] )}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <select id="product_id" name="product_id" class="form-control selectpicker">
                                    @foreach ($items as $item)
                                    <option value="{{ $item->product_id }}"  >{{$item->product_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-6 ">
                            <div class="form-group">
                            <input type="number" placeholder="تعداد" class="form-control" name="amount" id="amount">
                            </div>
                        </div>
                        <div class="col-6 ">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control"  placeholder="تاریخ برگشت" autocomplete="off" id="return_date_picker">
                                <input type="hidden" class="form-control" id="return_date" name="return_date"> 
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 ">
                            <div class="form-group">
                            <textarea name="description" id="description" cols="30" class="form-control" rows="5">{{old('description')}}</textarea>
                            </div>
                        </div>
                       
                        <div class="col-12 ">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_done"  id="is_done">
                                <label class="form-check-label" for="is_done">
                                  برگشت داده شد
                                </label>
                            </div>
                        </div>
                        <div class="col-12 ">
                            <button class="btn btn-success btn-add" type="submit">افزودن </button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                        </div>
                    </div>  
                </form>
            </div>
            
          </div>
        </div>
    </div>


    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <form action="{{route('panel.inventory.productChanges.return.updateReturnItem' ,['productChange' => $product_change->id ] )}}" method="post">
                    @csrf
                    <input type="hidden" name="item_id" id="item_id" >
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <select id="product_id_return" name="product_id" class="form-control selectpicker">
                                    @foreach ($products as $product)
                                    <option value="{{ $product->id }}" >{{$product->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-6 ">
                            <div class="form-group">
                            <input type="number" placeholder="تعداد" class="form-control" name="amount" id="amount_edit">
                            </div>
                        </div>
                        <div class="col-6 ">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control"  placeholder="تاریخ برگشت" autocomplete="off" id="return_edit_picker">
                                <input type="hidden" class="form-control" id="return_edit" name="return_edit"> 
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 ">
                            <div class="form-group">
                            <textarea name="description" id="description_edit" cols="30" class="form-control" rows="5">{{old('description')}}</textarea>
                            </div>
                        </div>
                        <div class="col-12 py-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_done"  id="is_done">
                                <label class="form-check-label" for="is_done">
                                  برگشت داده شد
                                </label>
                            </div>
                        </div>
                        <div class="col-12 ">
                            <button class="btn btn-success btn-add" type="submit">افزودن </button>
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
        var return_date;
        var exit_date;
        var return_date;
        var return_edit;
    
        $(document).ready(function () {
            enter_date = $('#enter_date_picker').persianDatepicker({
                format: 'YYYY/MM/DD',
                altField: '#enter_date',
                observer: false,
                initialValue: false,
            });
            exit_date = $('#exit_date_picker').persianDatepicker({
                format: 'YYYY/MM/DD',
                altField: '#exit_date',
                observer: false,
                initialValue: false,
            });
    
            expire_date = $('#return_date_picker').persianDatepicker({
                format: 'YYYY/MM/DD',
                altField: '#return_date',
                observer: false,
                initialValue: false,
    
            });
            return_edit = $('#return_edit_picker').persianDatepicker({
                format: 'YYYY/MM/DD',
                altField: '#return_edit',
                observer: false,
                initialValue: false,
    
            });


 

        @if(old('exit_date'))
            exit_date.setDate(parseFloat("{{ old('exit_date') }}"));
        @endif

        @if(old('return_date'))
            return_date.setDate(parseFloat("{{ old('return_date') }}"));
        @endif

        @if(old('return_edit'))
                return_edit.setDate(parseFloat("{{ old('return_edit') }}"));
            @endif


        });

        $('body').on('submit','#addItemForm', function(e) {
            e.preventDefault();
        }).on('click','.btn-increase', function() {
            $.get($(this).data('route')).done(response => {
                $('#productChangeItems').html(response)
            }).fail(error => {
                console.log(error)
            });
        }).on('click','.btn-decrease', function() {
            $.get($(this).data('route')).done(response => {
               $('#productChangeItems').html(response)
            }).fail(error => {
                console.log(error)
            });
        }).on('click','.btn-remove-item', function() {
            $.get($(this).data('route')).done(response => {
                $('#productChangeItems').html(response)
            }).done(response => {
                $('#productChangeItems').html(response)
                $.toast({
                    heading: 'حذف آیتم',
                    text: 'آیتم مورد نظر حذف شد',
                    icon: 'error',
                })
            }).fail(error => {
                $('#productChangeItems').html(error)
            });
        }).on('click','.btn-edit',function(event){
            var amount = $(this).parents('tr').find('.amount').text()
            var rowId =  $(this).parents('tr').attr('data-row-id')
            var id =  $(this).parents('tr').find('.id').attr('data-id')
            var info =  $(this).parents('tr').find('.info').text()
            var date =  $(this).parents('tr').find('.date').attr('data-date')
            var is_done =  $(this).parents('tr').find('.is_done').attr('data-done')
            var item_id =  $(this).parents('tr').find('.item_id').attr('data-item')
    
            $("#product_id_return").val(id);
            $("#amount_edit").val(amount);
            $("#description_edit").val(info);
            $("#is_done").attr('checked',is_done);
            $("#item_id").val(item_id);
            $("#return_edit_picker").val(date);

            updateSelectPicker();
        }).on('submit','#returnItemForm', function(e) {
            e.preventDefault();
        });

        function updateSelectPicker()
        {
            $('.selectpicker').selectpicker('refresh');
        }

        $('#addItemForm').ajaxForm({
            success: function(response, textStatus, xhr, form) {
                console.log(response)
                $('#productChangeItems').html(response)
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


        $('#returnItemForm').ajaxForm({
            success: function(response, textStatus, xhr, form) {
                console.log(response)
                $('#productChangeItems').html(response)
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

    
@endsection