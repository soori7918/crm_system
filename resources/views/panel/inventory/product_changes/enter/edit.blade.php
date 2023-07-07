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
                            <form method="POST" action="{{ route('panel.inventory.productChanges.enter.update' , ['enter' => $product_change->id]) }}" enctype="multipart/form-data">
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
                                        <label for="enter_date_picker" >تاریخ ورود </label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control"  autocomplete="off" id="enter_date_picker" value="{{jd($product_change->enter_date , 'Y/m/d')}}">
                                            <input type="hidden" class="form-control" id="enter_date" name="enter_date" > 
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
    
                             
                                   
                                  
                                    <div class="form-group col-12 col-sm-6">
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
                                    <div class="form-group col-12 col-sm-6">
                                        <label for="address" >آدرس</label>
                                        <textarea name="address" id="address" class="form-control" cols="30" rows="1">{{ old('address') ?: $product_change->address}}</textarea>
                                    </div>
                                    
                                    <div id="productChangeItems"  style="width: 80% ; margin:auto ; margin-top:30px">
                                        @include('components.productChange.cartListItem')
                                    </div>
                                   

                                  

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
              <h5 class="modal-title" id="exampleModalLongTitle">افزودن اقلام سند</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <form id="addItemForm" action="{{route('panel.inventory.productChanges.enter.add' , $product_change->id)}}" method="post">
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
        var date;
    
        $(document).ready(function () {
            enter_date = $('#enter_date_picker').persianDatepicker({
                format: 'YYYY/MM/DD',
                altField: '#enter_date',
                observer: false,
                initialValue: false,
            });
            
    
        @if(old('enter_date'))
            enter_date.setDate(parseFloat("{{ old('enter_date') }}"));
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
            $.get($(this).data('route1')).done(response => {
               $('#productChangeItems').html(response)
            }).fail(error => {
                console.log(error)
            });
        }).on('click','.btn-remove-item', function() {
            $.get($(this).data('delete-route')).done(response => {
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


    
        
    </script>

    
@endsection