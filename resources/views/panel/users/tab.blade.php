<ul class="nav nav-tabs">
    <li class="nav-item">
      <a class="nav-link {{checkActive(['panel.users.show']) ? 'active' : '' }}" href="{{route('panel.users.show' , $user)}}">مشاهده اطلاعات</a>
    </li>
    {{-- <li class="nav-item">
      <a class="nav-link {{checkActive(['panel.users.showAssignRole']) ? 'active' : ''}}" href="{{route('panel.users.showAssignRole' , $user)}}">دسترسی ها</a>
    </li> --}}
    <li class="nav-item">
      <a class="nav-link {{checkActive(['panel.users.edit']) ? 'active' : ''}}" href="{{route('panel.users.edit' , $user)}}">ویرایش اطلاعات</a>
    </li>
</ul>
