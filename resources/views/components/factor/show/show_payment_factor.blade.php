@if($payments ?? false)
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ردیف</th>
                <th>مبلغ</th>
                <th>نوع</th>
                <th>تاریخ </th>
                <th>وضعیت </th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as  $key => $item)
                <tr>
                    <td><span class="fa-num">{{ $item->id}}</span></td> 
                    <td><span class="fa-num">{{ number_format($item->price) }} تومان</span></td> 
                    <td> <span >{{  $item->getTypeTitle() }}</span></td>  
                    <td>
                        {{jd($item->date ,'Y/m/d' )}}
                    </td>
                    <td>
                        @if($item->is_done)
                            <span class='badge badge-success'>پرداخت شده</span>
                        @else
                            <span class='badge badge-danger'>پرداخت نشده</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
