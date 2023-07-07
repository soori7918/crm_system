    <table class="table table-bordered table-sm table-stripped">
        <thead class="bg-light">
            <tr>
                <th>ردیف</th>
                <th>مبلغ</th>
                <th>نوع</th>
                <th>تاریخ </th>
                <th>وضعیت </th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments ?: [] as  $key => $item)
                <tr>
                    <td><span class="fa-num">{{ $key+1 }}</span></td> 
                    <td>
                        <input type="hidden" id="price" data-price="{{$item->price}}" value="{{ number_format($item->price) }}">
                        <span class="fa-num">{{number_format($item->price)}} تومان</span>
                    </td> 
                    <td> 
                        <input type="hidden" id="type" data-type="{{$item->type}}" />
                        {{ App\Models\FactorPayment::staticGettypeTitle($item->type) }}
                    </td>
                    
                    <td>
                        <input type="hidden"  id="date" data-date="{{$item->date}}" >
                        {{jd($item->date ,'Y/m/d' )}}
                    </td>
                    <td> 
                        <input type="hidden"  id="is_done" data-is-done="{{$item->is_done}}">
                        @if($item->is_done)
                        <span class='badge badge-success'>تایید شده</span>
                        @else
                            <span class='badge badge-danger'>تایید نشده</span>
                        @endif
                    </td>  
                    <td>
                        <button data-paymentid="{{ $item->rowId }}" data-delete-route="{{ route('panel.EditFactor.removePayment',['factor' => $factor->id, 'rowId' => $item->rowId]) }}"  class="btn btn-sm btn-danger btn-remove-payment" type="button"> حذف </button>

                        <button type="button" data-paymentid="{{ $item->rowId }}" class="btn btn-sm btn-success btn-edit" data-toggle="modal" data-target="#editModal">
                            ویرایش
                        </button>
                        
                        
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="6" style="text-align: left">
                    <button type="button" class="btn btn-outline-secondary btn-edit" data-toggle="modal" data-target="#addPayment">
                        افزودن 
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
