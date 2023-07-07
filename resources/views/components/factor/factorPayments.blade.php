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
                    <td><span class="fa-num">{{ number_format($item['price']) }} تومان</span></td> 
                    <td> 
                        {{ App\Models\FactorPayment::staticGettypeTitle($item['type']) }}
                    </td>
                    
                    <td>
                        {{jd($item['date'] ,'Y/m/d' )}}
                    </td>
                    <td> 
                        @if($item['is_done'] == true)
                           <span class='badge badge-success'>تایید شده</span>
                        @endif
                        @if($item['is_done'] == false)
                            <span class='badge badge-danger'>تایید نشده</span>
                        @endif
        
                    </td>  
                    <td>
                        <button data-paymentid="{{ $item['rowId'] }}"   class="btn btn-sm btn-danger btn-remove-payment" type="button"> حذف </button>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="6" style="text-align: left">
                    <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#addPayment">
                        افزودن 
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
