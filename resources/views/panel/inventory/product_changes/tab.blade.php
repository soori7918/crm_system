<ul class="nav nav-pills nav-fill">
    <li class="nav-item">
      <a class="nav-link 
      {{ checkActive(["panel.inventory.productChanges.show",$product_change->id]) ? 'active' : ''  }}" 
      aria-current="page" 
      href="{{$product_change->getShowRoute()}}">مشاهده سند</a>
    </li>
    <li class="nav-item">
      <a class="nav-link 
      {{checkActive(["panel.inventory.productChanges.history",$product_change->id]) ? 'active' : ''}}" 
      href="{{route('panel.inventory.productChanges.history',$product_change->id)}}">مشاهده تاریخچه سند</a>
    </li>
   
  </ul>