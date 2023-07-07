<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
	<!-- Brand Logo -->
	{{--<a href="{{ route('panel.dashboard') }}" class="brand-link">
		<img src="{{ getImageSrc(getOption('site_information.favicon','images/admin-logo.png')) }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3">
		 <span class="brand-text font-weight-light">
				{{ getOption('site_information.website_name',config('settings.website_name')) }}
		</span>
	</a> --}}

	<!-- Sidebar -->
	<div class="sidebar do-nicescroll">

		<!-- Sidebar Menu -->
		<nav class="mt-2">
			<ul class="nav d-block nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
				<li class="nav-item">
					<a href="{{ route('panel.dashboard') }}" class="nav-link {{ checkActive(['panel.dashboard']) ? 'active' : '' }}">
						<p>پیشخوان</p>
						<i class="nav-icon fad fa-tachometer-alt"></i>
					</a>
				</li>


					@can('view users') 
						<li class="nav-item">
							<a href="{{route('panel.users.index')}}" class="nav-link {{ checkActive([
								'panel/users*',
								]) ? 'active' : '' }}">
								<p>مدیریت کاربران</p>
								<i class="nav-icon fad fa-users"></i>
							</a>
						</li>
					@endcan  
					@can('view permissions')
						<li class="nav-item">
							<a href="{{route('panel.roles.index')}}" class="nav-link {{ checkActive([
								'panel/roles*',
								'panel/permission*',
								'panel/groups*',
								]) ? 'active' : '' }}">
								<p>مدیریت دسترسی ها</p>
								<i class="nav-icon fad fa-users"></i>
							</a>
						</li>
					@endcan
				
{{-- 
				@canany(['view products','view  category']) 
					<li class="nav-item has-treeview {{ checkActive([
						'panel/products*',
						'panel/categories*',
						'panel/product_changes*',
						'panel/reports*',
						]) ? 'menu-open' : '' }}">
						<a href="#" class="nav-link {{ checkActive([
								'panel/products*',
								'panel/categories*',
								'panel/product_changes*',
								'panel/reports*',
								]) ? 'active' : '' }}">
							<p>
								<i class="left fad fa-angle-left"></i>
								انبار داری
							</p>
							<i class="nav-icon fa fa-list-alt"></i>
						</a>
						<ul class="nav nav-treeview p-0">
							<li class="nav-item">
								<a class="nav-link {{ checkActive([
								'panel/categories',
								'panel/categories/*',
								]) ? 'active' : '' }}" href="{{ route('panel.categories.index') }}">
									دسته بندی محصولات
									<i class="nav-icon fa fa-list-alt"></i>
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link {{ checkActive([
								'panel/products',
								'panel/products/*',
								]) ? 'active' : '' }}" href="{{ route('panel.products.index') }}">
									مدیریت محصولات
									<i class="nav-icon fad fa-bullhorn"></i>
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link {{ checkActive([
								'panel/product_changes',
								'panel/product_changes/*',
								]) ? 'active' : '' }}" href="{{ route('panel.product_changes.index') }}">
									مدیریت انبار
									<i class="nav-icon fad fa-bullhorn"></i>
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link {{ checkActive([
								'panel/reports',
								'panel/reports*',
								]) ? 'active' : '' }}" href="{{ route('panel.reports') }}">
									گزارشات
									<i class="nav-icon fad fa-bullhorn"></i>
								</a>
							</li>
						</ul>
					</li>
				@endcan --}}
				

				@canany(['view products','view  category']) 
					<li class="nav-item has-treeview {{ checkActive(['panel/inventory*']) ? 'menu-open' : '' }}">
						<a href="#" class="nav-link {{ checkActive(['panel/inventory*']) ? 'active' : '' }}">
							<p>
								<i class="left fad fa-angle-left"></i>
								انبار داری
							</p>
							<i class="nav-icon fa fa-list-alt"></i>
						</a>
						<ul class="nav nav-treeview p-0">
							<li class="nav-item">
								<a class="nav-link {{ checkActive(['panel/inventory/categories*']) ? 'active' : '' }}" href="{{ route('panel.inventory.categories.index') }}">
									دسته بندی محصولات
									<i class="nav-icon fa fa-list-alt"></i>
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link {{ checkActive(['panel/inventory/products*']) ? 'active' : '' }}" href="{{ route('panel.inventory.products.index') }}">
									مدیریت محصولات
									<i class="nav-icon fad fa-bullhorn"></i>
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link {{ checkActive(['panel/inventory/product-changes*']) ? 'active' : '' }}" href="{{ route('panel.inventory.productChanges.index') }}">
									مدیریت انبار
									<i class="nav-icon fad fa-bullhorn"></i>
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link {{ checkActive(['panel/inventory/reports*']) ? 'active' : '' }}" href="{{ route('panel.inventory.reports') }}">
									گزارشات
									<i class="nav-icon fad fa-bullhorn"></i>
								</a>
							</li>
						</ul>
					</li>
				@endcan
				
				@canany(['view customers']) 
					<li class="nav-item has-treeview {{ checkActive([
						'panel/customers*',
						'panel/customer/reports*',
						]) ? 'menu-open' : '' }}">
						<a href="#" class="nav-link {{ checkActive([
								'panel/customers*',
								'panel/customer/reports*',
								]) ? 'active' : '' }}">
							<p>
								<i class="left fad fa-angle-left"></i>
								مدیریت ارتباط با مشتری
							</p>
							<i class="nav-icon fa fa-list-alt"></i>
						</a>
						<ul class="nav nav-treeview p-0">
							<li class="nav-item">
								<a class="nav-link {{ checkActive([
								'panel/customers',
								'panel/customers/*',
								]) ? 'active' : '' }}" href="{{ route('panel.customers.index') }}">
									مدیریت مشتریان
									<i class="nav-icon fa fa-list-alt"></i>
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link {{ checkActive([
								'panel/customer/reports',
								'panel/customer/reports*',
								]) ? 'active' : '' }}" href="{{ route('panel.customerReports') }}">
									گزارشات
									<i class="nav-icon fad fa-bullhorn"></i>
								</a>
							</li>
						</ul> 
					</li>
				@endcan


				{{-- @canany(['view accounting'])  --}}
					<li class="nav-item has-treeview {{ checkActive([
						'panel/factors*',
						'panel/cost_factors*',
						'panel/manage_payments*',
						'panel/wallets_report*',
						'panel/wallets*',
						]) ? 'menu-open' : '' }}">
						<a href="#" class="nav-link {{ checkActive([
								'panel/factors*',
								'panel/cost_factors*',
								'panel/manage_payments*',
								'panel/wallets_report*',
								'panel/wallets*',
								]) ? 'active' : '' }}">
							<p>
								<i class="left fad fa-angle-left"></i>
								حسابداری
							</p>
							<i class="nav-icon fa fa-list-alt"></i>
						</a>
						<ul class="nav nav-treeview p-0">
							<li class="nav-item">
								<a class="nav-link {{ checkActive([
								'panel/wallets',
								'panel/wallets/*',
								]) ? 'active' : '' }}" href="{{ route('panel.wallets.index') }}">
									مدیریت صندوق ها
									<i class="nav-icon fa fa-list-alt"></i>
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link {{ checkActive([
								'panel/factors',
								'panel/factors/*',
								'panel/cost_factors',
								'panel/cost_factors/*',
								]) ? 'active' : '' }}" href="{{ route('panel.factors.index') }}">
									مدیریت فاکتور ها
									<i class="nav-icon fa fa-list-alt"></i>
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link {{ checkActive([
								'panel/manage_payments',
								'panel/manage_payments/*',
								]) ? 'active' : '' }}" href="{{route('panel.manage_payments.index')}}">
									مدیریت پرداخت ها
									<i class="nav-icon fa fa-list-alt"></i>
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link {{ checkActive([
								'panel/wallets_report/reports',
								'panel/wallets_report/reports/*',
								]) ? 'active' : '' }}" href="{{route('panel.wallets.reports')}}">
									گزارشات
									<i class="nav-icon fad fa-bullhorn"></i>
								</a>
							</li>
						</ul>
					</li>
				{{-- @endcan --}}

				

				</ul>
			</nav>
			<!-- /.sidebar-menu -->
		</div>
		<!-- /.sidebar -->
	</aside>
