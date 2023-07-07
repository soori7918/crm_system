<div class="card card-body p-2">
    <ul class="nav nav-pills nav-fill">
        <li class="nav-item">
            <a class="nav-link {{ checkActive(['panel.roles.index']) ? 'active' : '' }}" href="{{ route('panel.roles.index') }}">مدیریت نقش ها</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ checkActive(['panel.permissionGroups.index']) ? 'active' : '' }}" href="{{ route('panel.permissionGroups.index') }}">گروه های دسترسی</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ checkActive(['panel.permissions.index']) ? 'active' : '' }}" href="{{ route('panel.permissions.index') }}">مدیریت دسترسی ها</a>
        </li>
    </ul>
</div>