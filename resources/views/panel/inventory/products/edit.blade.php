@extends('panel.layouts.master')

@section('content')

<div class="content-wrapper">

    
    
    <div class="content-header">
        <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-12">
            <h1 class="m-0 text-dark">افزودن محصول جدید</h1>
            </div>
        </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            @include('components.messages')
           <div class="col-12">
                <form method="POST" action="{{ route('panel.products.update' , $product) }}" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="row">
                        <div class="col-12 col-sm-7 col-md-9">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label for="name" class="col-sm-2 text-sm-left col-form-label text-nowrap">نام محصول</label>
                                        <div class="col-sm-10">
                                            <input
                                                    type="text"
                                                    class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                                    id="name"
                                                    name="name"
                                                    required
                                                    value="{{ old('name') ?: $product->name}}"
                                                   placeholder="نام محصول را وارد کنید"
                                            >
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="sale_price" class="col-sm-2 text-sm-left col-form-label text-nowrap">قیمت محصول</label>
                                        <div class="col-sm-10">
                                            <input
                                                    type="text"
                                                    class="form-control {{ $errors->has('sale_price') ? 'is-invalid' : '' }}"
                                                    id="sale_price"
                                                    name="sale_price"
                                                    required
                                                    value="{{ old('sale_price') ?: $product->sale_price }}"
                                                   placeholder="قیمت محصول را وارد کنید"                                            >
                                        </div>
                                    </div>  
                                    <div class="form-group row">
                                        <label for="rent_price" class="col-sm-2 text-sm-left col-form-label text-nowrap">قیمت اجاره</label>
                                        <div class="col-sm-10">
                                            <input
                                                    type="text"
                                                    class="form-control {{ $errors->has('rent_price') ? 'is-invalid' : '' }}"
                                                    id="rent_price"
                                                    name="rent_price"
                                                    required
                                                    value="{{ old('rent_price') ?: $product->rent_price }}"
                                                   placeholder="قیمت اجاره را وارد کنید"                                            >
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="category_id" class="col-sm-2 text-sm-left col-form-label text-nowrap">دسته بندی</label>
                                        <div class="col-sm-10">
                                            <select name="category_id[]" data-placeholder='انتخاب دسته بندی'
                                                data-live-search="true"
                                                multiple
                                                class="form-control selectpicker"
                                                id="category_id">
                                            @include('panel.products.product_category',[
                                                'categories' => $categories,
                                                'selected_categories' => $selected_categories,
                                            ])
                                        </select>
                                       
                                        </div>
                                    </div>
                                   
                                   
                                    <div class="form-group row">
                                        <label for="description" class="col-sm-2 text-sm-left col-form-label">توضیحات </label>
                                        <div class="col-sm-10">
                                            <textarea name="description" class="form-control" id="description" cols="30" rows="10">{!! old('description') ?: $product->description !!}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-12 col-sm-5 col-md-3 d-flex flex-column">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="image" class="d-block">
                                            @if($product->image)
                                            <img id="image_preview" class="img-fluid rounded mb-3" style="width:100%"
                                                 src="{{ getImageSrc($product->image) }}" />
                                            <img id="no_preview" class="img-fluid d-none rounded mb-3" style="width:100%"
                                                 src="{{ getImageSrc($product->image) }}" />
                                        @else
                                            <img id="image_preview" class="img-fluid d-none rounded mb-3" style="width:100%" src="#" />
                                            <div id="no_preview" class="border py-4 rounded mb-3 text-gray-300 text-center">
                                                <i class="fad fa-image" style="font-size:90px;"></i>
                                                <h4 class="mt-2">تصویر شاخص</h4>
                                            </div>
                                        @endif
                                            <input type='file' class="d-none" name="image" id="image" />
                                        </label>
                                    </div>
                                    <hr/>
                                   
                                    
                                   

                                    <button type="submit" class="btn btn-primary">ذخیره</button>
                                    <a class="btn btn-secondary" href="{{ route('panel.products.index') }}">
                                        بازگشت
                                    </a>
                                </div>
                            </div>

                          
                        </div>
                    </div>
                </form>
               </div>
           </div>
        </div>
    </div>

</div>

@endsection


@section('scripts')
    <script>
     
        $('.selectpicker').selectpicker()

    </script>

<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#image_preview').attr('src', e.target.result).removeClass('d-none');
                $('#no_preview').addClass('d-none');
            }

            reader.readAsDataURL(input.files[0]);
        } else {
            $('#image_preview').addClass('d-none');
            $('#no_preview').removeClass('d-none');
        }
    }

    $("#image").change(function() {
        readURL(this);
    });

    $("#addImage_input").change(function() {
        if (this.files && this.files[0]) {
            $('#add_image_form').submit();
        }
    });
</script>
@endsection