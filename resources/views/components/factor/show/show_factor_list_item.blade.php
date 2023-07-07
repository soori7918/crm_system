@if($items ?? false)
    <table class="table table-bordered" >
        <thead>
            <tr>
                <th>کد</th>
                <th>عنوان</th>
                <th>تعداد</th>
                <th>قیمت </th>
                <th>توضیحات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as  $key => $item)
                <tr>
                <tr>
                    <td><span class="fa-num">{{ $item->id }}</span></td> 
                    <td>{{ $item->title }}</td> 
                    <td> 
                         <span class="fa-num">{{ $item->amount}}</span>
                    </td>  
                     <td><span class="fa-num">{{ number_format($item->price)}} تومان</span></td>
                     <td>{{ $item->description}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
