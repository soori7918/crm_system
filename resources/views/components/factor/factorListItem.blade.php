{{-- @if($items ?? false) --}}
    <table class="table table-bordered table-sm table-stripped" >
        <thead class="bg-light">
            <tr>
                <th>#</th>
                <th>عنوان</th>
                <th>تعداد</th>
                <th>قیمت </th>
                <th>توضیحات</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($items ?: [] as  $key => $item)
                <tr>
                <tr>
                    <td><span class="fa-num">{{ $key+1 }}</span></td> 
                    <td>{{ $item['title']}}</td> 
                    <td> 
                        <button data-route="{{ route('panel.EditFactor.increase',['factor' => $factor->id, 'rowId' => $item['rowId']]) }}" class="btn btn-sm btn-light btn-increase" type="button"  >
                            <i class="fal fa-plus"></i>
                        </button>
                         <span class="fa-num">{{ $item['amount']}}</span>
                         <button data-route1="{{ route('panel.EditFactor.decrease',['factor' => $factor->id, 'rowId' => $item['rowId']]) }}"  class="btn btn-sm btn-light btn-decrease" type="button">
                            <i class="fal fa-minus"></i>
                        </button>
                    </td>  
                     <td><span class="fa-num">{{ number_format($item['price'])}} تومان</span></td>
                     <td>{{ $item['description']}}</td>
                    <td>
                        <button data-rowid="{{ $item['rowId'] }}" data-delete-route="{{ route('panel.EditFactor.removeItem',['factor' => $factor->id, 'rowId' => $item['rowId']]) }}" class="btn btn-sm btn-danger btn-remove-item" type="button"><i class="fal fa-times"></i></button>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="6" class="text-left">
                    <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#addProduct">
                        افزودن
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
{{-- @endif --}}
