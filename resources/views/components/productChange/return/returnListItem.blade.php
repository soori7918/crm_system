    <table class="table table-bordered table-sm table-stripped" style="width: 100%">
        <thead class="bg-light">
            <tr>
                <th>#</th>
                <th>عنوان محصول</th>
                <th>تعداد</th>
                <th>توضیحات</th>
                <th>تاریخ برگشت</th>
                <th>وضعیت</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($return_items ?: [] as $key => $item)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>
                        <span class="item_id" data-item="{{ $item->id }}"></span>

                        <span class="id" data-id="{{ $item->product_id }}"></span>
                        <span>{{ $item->product->name}}</span>
                    </td>
                    <td>
                        <div>
                            <span class="amount">{{ $item->amount}}</span>
                        </div>
                    </td>
                    <td>
                       <span class="info">{{ $item->description }}</span> 
                    </td>
                    <td>
                       <span class="date" data-date="{{ jd($item->return_date) }}">
                            {{jd($item->return_date ,'Y/m/d' )}}
                        </span> 
                    </td>
                    <td>
                        <span class="is_done" data-done="{{ $item->is_done }}"></span>
                        @if ($item->is_done == true )
                            <span class="badge badge-success">برگشت داده شد</span>   
                        @else  
                            <span class="badge badge-danger">برگشت داده نشد</span>   
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('panel.inventory.productChanges.return.removeReturnItem' , ['productChange' => $product_change->id ,'returnItem' => $item->id])}}" method="post">
                            @csrf
                            @method('delete')
                            <button class="btn btn-sm btn-danger" type="submit"
                                onclick="return confirm('آیا مایل به حذف هستید؟')"  title="حذف"
                                >
                                <i class="fal fa-times"></i></button>
                            <button type="button" class="btn btn-sm btn-success btn-edit" data-rowid="{{ $item->rowId }}" data-toggle="modal" data-target="#editModal">
                                <i class="fal fa-edit"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="7" class="text-left">
                    <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#returnItems">
                        افزودن آیتم <i class="fa fa-plus"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
