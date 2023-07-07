<ul class="nav nav-pills nav-fill">
    <li class="nav-item">
      <a class="nav-link 
      {{ checkActive(["panel.factors.show",$factor->id]) ? 'active' : ''  }}" 
      aria-current="page" 
      href="{{route('panel.factors.show',$factor->id)}}">مشاهده فاکتور</a>
    </li>
    <li class="nav-item">
      <a class="nav-link 
      {{checkActive(["panel.factors.history",$factor->id]) ? 'active' : ''}}" 
      href="{{route('panel.factors.history',$factor->id)}}">مشاهده تاریخچه فاکتور</a>
    </li>
   
  </ul>