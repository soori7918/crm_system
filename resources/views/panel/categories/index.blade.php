@extends('panel.layouts.master')

@section('content')

<div class="content-wrapper">
    
    <div class="content-header">
        <div class="container-fluid px-4">
            <div class="row mb-2 d-flex flex-wrap justify-content-between">
                <h1 class="m-0 text-dark">مدیریت دسته بندی </h1>
                <div>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createCategory">
                        افزودن دسته بندی جدید
                      </button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="content">
        <div class="container-fluid">
            @include('components.messages')
           <div class="card shadow-sm">
                <form action="" method="get">
                    <div class="col-12 py-2">
                        <div class="row">
                            <div class="col-12 col-lg-3">
                                <input type="text" name="search" placeholder="جستجو براساس نام" class="form-control ml-2">
                            </div>
                           
                            <div class="col-12 col-lg-4">
                                <button type="submit" class="btn btn-success ">جستجو</button>
                                <a href="{{ route('panel.inventory.categories.index') }}" class="btn btn-secondary mr-2">
                                    نمایش همه
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
               <div class="col-12">
                   @if($categories->count() > 0)
                    <table class="table table-sm">
                        <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>نام دسته بندی</th>
                            <th> دسته والد</th>
                            <th>ترتیب نمایش</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                                <tr>
                                    <td>{{{$category->id}}}</td>
                                    <td>
                                        <a href="{{ $category->getRoute() }}" class="text-dark" target="_blank">
                                            {{str_repeat('—',$category->level)}}
                                           {{$category->name}}
                                        </a>
                                    </td>
                                    <td>{{ $category->getParent()  }} </td>
                                    <td>{{$category->order}}</td>
                                   
                                    <td>
                                        <form method="post" action="{{route('panel.inventory.categories.destroy' , $category)}}">
                                            @csrf
                                            @method('delete')
                                             <button type="button" class="btn btn-sm btn-success btn-edit" data-toggle="modal" 
                                                data-id="{{$category->id}}"    
                                                data-attr="{{ route('panel.inventory.categories.edit', $category->id) }}" 
                                                data-attr-update="{{ route('panel.inventory.categories.update', $category->id) }}" 
                                                data-target="#editModal">
                                               ویرایش
                                            </button>

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


<!-- Modal -->
<div class="modal fade" id="createCategory" tabindex="-1" role="dialog" aria-labelledby="createCategoryTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">افزودن دسته بندی</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form action="{{route('panel.inventory.categories.store')}}" method="post" enctype="multipart/form-data" >
                @csrf
                <div class="row">
                     <div class="col-12 col-lg-6 form-group">
                    <label for="onvan">عنوان دسته بندی</label>
                    <input type="text" name="name" id="onvan" class="form-control " value="{{old('name')}}"  placeholder="عنوان دسته بندی را وارد نمایید">
                </div>
                <div class="col-12 col-lg-6 form-group">
                    <label for="tartib">ترتیب نمایش</label>
                    <input type="text" name="order" class="form-control " id="tartib"  value="{{old('order')}}" placeholder="ترتیب نمایش را وارد کنید ">
                </div>
                <div class="col-12 col-lg-12 form-group">
                    <label for="valed">دسته والد </label>
                    <select name="parent_id" data-placeholder='انتخاب دسته بندی والد' id="valed" class="form-control selectpicker" >
                        <option value="">بدون والد</option>
                        @include('panel.categories.childrenOption',[
                            'categories' => $categories,
                            'selected_categories' => [old('parent')]
                        ])
                    </select>
                </div>
                <div class="col-12 col-lg-12 form-group">
                    <button class="btn btn-success w-100" type="submit">ثبت </button>
                </div>
                </div>
               
            </form>
        </div>
      </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">ویرایش دسته بندی</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          
            <form class="modal-form" method="post"  enctype="multipart/form-data" >
                @csrf
                @method('put')
                <div class="row">
                    <div class="col-12 col-lg-6 form-group">
                        <label for="name">عنوان دسته بندی</label>
                        <input type="text" name="name" class="form-control "  id="edit_name" placeholder="عنوان دسته بندی را وارد کنید">
                    </div>
                    <div class="col-12 col-lg-6 form-group">
                        <label for="order">ترتیب نمایش</label>
                        <input type="text" name="order" class="form-control " id="edit_order" placeholder=" ترتیب نمایش را وارد کنید ">
                    </div>
                    <div class="col-12 col-lg-12 form-group">
                        <label for="parent_id">دسته والد </label>
                        <select name="parent_id" data-placeholder='انتخاب دسته بندی والد' class="form-control selectpicker" id="edit_parent_id">
                            <option value="">بدون والد</option>
                            @include('panel.categories.childrenOption',[
                                'categories' => $categories,
                                'selected_categories' => [old('parent') ]
                            ])
                        </select>
                    </div>
                    <div class="col-12 col-lg-12 form-group">
                        <button class="btn btn-success w-100" type="submit">به روز رسانی </button>
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
        $('body').on('click' , '.btn-edit', function(){
            let id = $(this).attr("data-id") ;
            let href = $(this).attr('data-attr');
            let update = $(this).attr('data-attr-update');
            $.ajax({
                url: href,
                method: "get",
            }).done(function(response) {
                // //Setting input values
                let category = response.category['id'];
                $("#edit_name").val(response.category['name']);
                $("#edit_order").val(response.category['order']);
                $("#edit_parent_id").val(response.category['parent_id']);
                // //Setting submit url
                $(".modal-form").attr("action", update)
                updateSelectPicker();

            });


        });

        function updateSelectPicker()
        {
            $('.selectpicker').selectpicker('refresh');
        }
    </script>
@endsection