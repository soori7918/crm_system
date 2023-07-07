    <table class="table table-bordered table-sm table-stripped" style="width: 100%">
        <thead class="bg-light">
            <tr>
                <th>#</th>
                <th>عنوان محصول</th>
                <th>تعداد</th>
                <th>توضیحات</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($items ?: [] as $key => $item)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $item->product_name}}</td>
                    <td>
                        <div>
                            <button  data-route="{{ route('panel.inventory.productChanges.return.increase', ['productChange' => $product_change->id, 'rowId' => $item->rowId]  ) }}"   class="btn btn-sm btn-light btn-increase" type="button"  >
                                <i class="fal fa-plus"></i>
                            </button>
                            <span >{{ $item->amount}}</span>
                            <input type="hidden" name="product_item" id="product_item" value="{{ $item->product_id}}">
                            <button data-route="{{ route('panel.inventory.productChanges.return.decrease',['productChange' => $product_change->id, 'rowId' => $item->rowId ] ) }}" data-id="{{ $item->rowId }}" class="btn btn-sm btn-light btn-decrease" type="button">
                                <i class="fal fa-minus"></i>
                            </button>
                        </div>
                    </td>
                    <td>
                        {{ $item->description }}
                    </td>
                    <td>
                        <button data-rowid="{{ $item->rowId }}" data-route="{{ route('panel.inventory.productChanges.return.remove',['productChange' => $product_change->id, 'rowId' => $item->rowId]) }}" class="btn btn-sm btn-danger btn-remove-item" type="button"><i class="fal fa-times"></i></button>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="5" class="text-left">
                    <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#createProductChange">
                        افزودن محصول <i class="fa fa-plus"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
