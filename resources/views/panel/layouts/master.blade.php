<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  {{-- <link rel="icon" type="image/png" href="{{ getImageSrc(getOption('site_information.favicon')) }}"> --}}

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js" src_type="url" ></script>

    {{-- <title>{{ getOption('site_information.website_name',config('settings.website_name')) .' | ' . 'پنل مدیریت' }}</title> --}}

  <!-- Styles -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    <link href="{{ asset('css/persianfonts.css') }}" rel="stylesheet">
    @yield('head')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fad fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="" class="nav-link text-nowrap">مشاهده وبسایت</a>
      </li>
    </ul>

    <!-- SEARCH FORM -->
    <form class="form-inline mr-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="جستجو..." aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fad fa-search"></i>
          </button>
        </div>
      </div>
    </form>

    <!-- Right navbar links -->
    <ul class="navbar-nav mr-auto">
	<li class="nav-item">
		<a class="nav-link text-danger" title="خروج" href="{{ route('logout') }}"
			onclick="event.preventDefault();
						document.getElementById('logout-form').submit();">
			<i class="fad fa-power-off"></i>
		</a>

		<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
			@csrf
		</form>
	</li>
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#"><i
            class="fad fa-th-large"></i></a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  @include('panel.layouts.sidebar')

  @yield('content')

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
	<!-- Control sidebar content goes here -->
	<div class="p-3">
		@yield('sidebar')
	</div>
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="float-left d-none d-sm-inline">
      <a class="" href="http://gparvin.co.ir" target="_blank">gparvin.co.ir</a>
    </div>
    <!-- Default to the left -->
    طراحی و پیاده سازی شده توسط <strong><a href="http://gparvin.co.ir">شرکت گلستان پردازش آروین</a></strong>
  </footer>
</div>
{{-- 
<div class="snackbar dynamic shadow bg-success">
    <span class="p-3 d-inline-block">
    </span>
    <span class="closeSnackbar d-inline-block p-3">
        <i class="fad fa-times"></i>
    </span>
</div> --}}

<!-- ./wrapper -->
    @yield('bottom')
    @stack('bottom')

    <!-- Scripts -->
    <script src="{{ asset('js/admin.js') }}"></script>
    <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.js"></script>

    @yield('scripts')
    @stack('scripts')

</body>
</html>
