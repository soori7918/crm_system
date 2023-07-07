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
                    <td>{{ $item['product_name']}}</td>
                    <td>
                        <div>
                            {{-- <button  data-id="{{ $item['rowId'] }}" class="btn btn-sm btn-light btn-increase-return" type="button"  >
                                <i class="fal fa-plus"></i>
                            </button> --}}
                            <span >{{ $item['amount']}}</span>
                            <input type="hidden" name="product_item" id="product_item" value="{{ $item['product_id']}}">
                            {{-- <button  data-id="{{ $item['rowId'] }}" class="btn btn-sm btn-light btn-decrease-return" type="button">
                                <i class="fal fa-minus"></i>
                            </button> --}}
                        </div>
                    </td>
                    <td>
                        {{ $item['description'] }}
                    </td>
                    <td>
                        {{jd($item['return_date'] ,'Y/m/d' )}}
                    </td>
                    <td>
                        @if ($item['is_done'] == true)
                        <span class="badge badge-success">برگشت داده شده</span>   
                        @else     
                        <span class="badge badge-danger">برگشت داده نشده</span>                       
                        @endif
                    </td>
                    <td>
                        <button data-rowid="{{ $item['rowId'] }}" class="btn btn-sm btn-danger btn-remove-return" type="button"><i class="fal fa-times"></i></button>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="7" class="text-left">
                    <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#returnProductChange">
                        افزودن آیتم <i class="fa fa-plus"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
