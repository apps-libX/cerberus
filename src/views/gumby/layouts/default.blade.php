<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title> 
			@section('title') 
			@show 
		</title>

		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<link rel="stylesheet" href="{{ asset('packages/onderdelen/cerberus/css/gumby.css') }}">
		<link rel="stylesheet" href="{{ asset('packages/onderdelen/cerberus/css/style.css') }}">

		<!-- Modernizr -->
		<script src="{{ asset('packages/onderdelen/cerberus/js/vendor/modernizr-2.6.2.min.js') }}"></script>

	</head>

	<body>
		

		<!-- Navbar -->
		<nav id="nav1" class="navbar">
		    <div class="row">

		      	<a class="toggle" gumby-trigger="#nav1 > ul" href="#"><i class="icon-menu"></i></a>
		        <h1 class="four columns logo">
		          <a href="#">
		            <img src="packages/onderdelen/cerberus/img/gumby_mainlogo.png" gumby-retina />
		          </a>
		        </h1>

		        <ul class="eight columns">
		           @if ( ! Sentry::check() )
       					<li {!! (Request::is('login') ? 'class="active"' : '') !!}><a href="{{ route('cerberus.login') }}">Log In</a></li>
       					<li {!! (Request::is('register') ? 'class="active"' : '') !!}><a href="{{ route('cerberus.register.form') }}">Register</a></li>
       				@else 
       					@if (Sentry::getUser()->hasAccess('admin'))
       						<li {!! (Request::is('users*') ? 'class="active"' : '') !!}><a href="{{ action('\\Cerberus\Controllers\UserController@index') }}">Users</a></li>
       						<li {!! (Request::is('groups*') ? 'class="active"' : '') !!}><a href="{{ action('\\Cerberus\Controllers\GroupController@index') }}">Groups</a></li>
       					@endif
       					<li {!! (Request::is('profile') ? 'class="active"' : '') !!}><a href="{{ route('cerberus.profile.show') }}">{{ Sentry::getUser()->email }}</a></li>
       					<li><a href="{{ route('cerberus.logout') }}">Logout</a></li>
       				@endif 
		        </ul>
				<!-- End Main Nav -->
		    </div>
		  </nav>
		<!-- ./ navbar -->

		<!-- Container -->
		<div class="container">
			<!-- Notifications -->
			@include('Cerberus::layouts/notifications')
			<!-- ./ notifications -->

			<!-- Content -->
			@yield('content')
			<!-- ./ content -->
		</div>

		<!-- ./ container -->

		<!-- Javascripts
		================================================== -->
		<script src="{{ asset('packages/onderdelen/cerberus/js/libs/jquery-2.0.2.min.js') }}"></script>
		<script src="{{ asset('packages/onderdelen/cerberus/js/libs/gumby.min.js') }}"></script>
		<script src="{{ asset('packages/onderdelen/cerberus/js/restfulizer.js') }}"></script>
		<!-- Thanks to Zizaco for the Restfulizer script.  http://zizaco.net  -->
	</body>
</html>
