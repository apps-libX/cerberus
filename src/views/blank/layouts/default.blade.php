<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title> 
			@section('title') 
			@show 
		</title>

		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<!-- Normalize.css - https://github.com/necolas/normalize.css -->
		<link href="{{ asset('css/normalize.css') }}" rel="stylesheet">

		<!-- Cerberus Blank Theme CSS -->
		<link href="{{ asset('css/cerberus-blank-theme.css') }}" rel="stylesheet">

		<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

	</head>

	<body>
		<div id="container">

			<!-- Navbar --> 
			<nav id="cerberus-navbar">
				<div class="cerberus-navbar-header">
		        	<h1><a class="cerberus-nav" href="{{ URL::route('home') }}">Cerberus</a></h1>
		        </div>
		        <ul id="cerberus-navbar-right">
		           	@if (Carbuncle::check() && Carbuncle::getUser()->hasAccess('admin'))
						<li {!! (Request::is('users*') ? 'class="active"' : '') !!}><a href="{{ URL::to('/users') }}">Users</a></li>
						<li {!! (Request::is('groups*') ? 'class="active"' : '') !!}><a href="{{ URL::to('/groups') }}">Groups</a></li>
					@endif
		            @if (Carbuncle::check())
    				<li {!! (Request::is('profile') ? 'class="active"' : '') !!}><a href="{{ route('cerberus.profile.show') }}">{{ Carbuncle::getUser()->email }}</a></li>
    				<li><a href="{{ route('cerberus.logout') }}">Logout</a></li>
    				@else
    				<li {!! (Request::is('login') ? 'class="active"' : '') !!}><a href="{{ route('cerberus.login') }}">Login</a></li>
    				<li {!! (Request::is('users/create') ? 'class="active"' : '') !!}><a href="{{ route('cerberus.register.form') }}">Register</a></li>
    				@endif
		        </ul>
			</nav>
			<!-- ./ navbar -->

			<!-- Container -->
			<div class="cerberus-content">
				<!-- Notifications -->
				@include('Cerberus::layouts/notifications')
				<!-- ./ notifications -->

				<!-- Content -->
				@yield('content')
				<!-- ./ content -->
			</div>

		</div>
		<!-- ./ container -->

		<!-- Javascripts
		================================================== -->
		<script src="{{ asset('packages/einherjars/cerberus/js/jquery-2.1.3.min.js') }}"></script>
		<script src="{{ asset('packages/einherjars/cerberus/js/restfulizer.js') }}"></script>
		<!-- Thanks to Zizaco for the Restfulizer script.  http://zizaco.net  -->
	</body>
</html>
