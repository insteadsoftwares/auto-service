<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- <link rel="icon" href="<%= BASE_URL %>favicon.ico"> -->

  <title>Auto Service</title>
  
  <!-- Favicon -->
  <link rel="shortcut icon" href="{{ asset('images/logo/favicon.png') }}">
  
  @if (request()->is('admin*'))
	<!-- Splash Screen/Loader Styles -->
	<link rel="stylesheet" type="text/css" href="{{ asset(mix('css/loader.css')) }}" />

	<!-- Styles -->
	<link rel="stylesheet" href="{{ asset(mix('css/app.css')) }}">

	<!-- Font -->
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400&display=swap"
		rel="stylesheet">
  @else
	<!-- CSS -->
	<link rel="stylesheet" href="{{ asset('loader.css') }}">
	<link href="{{ asset('homePage/css/bootstrap.min.css') }}" rel="stylesheet">
	<link href="{{ asset('homePage/css/font-awesome.min.css') }}" rel="stylesheet">
	<link href="{{ asset('homePage/css/line-awesome.min.css') }}" rel="stylesheet">
	<link href="{{ asset('homePage/css/animate.css') }}" rel="stylesheet">
	<link href="{{ asset('homePage/css/barfiller.css') }}" rel="stylesheet">
	<link href="{{ asset('homePage/css/magnific-popup.css') }}" rel="stylesheet">
	<link href="{{ asset('homePage/css/flaticon.css') }}" rel="stylesheet">
	<link href="{{ asset('homePage/css/owl.carousel.css') }}" rel="stylesheet">
	<link href="{{ asset('homePage/css/style.css') }}" rel="stylesheet">
	<link href="{{ asset('homePage/css/responsive.css') }}" rel="stylesheet">
	<!-- jQuery -->
	{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> --}}
	<script src="{{ asset('homePage/js/jquery-1.12.4.min.js') }}"></script>

	<!-- JS plugins -->
	<script src="{{ asset('homePage/js/popper.min.js') }}"></script>
	<script src="{{ asset('homePage/js/bootstrap.min.js') }}"></script>
	<script src="{{ asset('homePage/js/wow.min.js') }}"></script>
	<script src="{{ asset('homePage/js/jquery.waypoints.min.js') }}"></script>
	<script src="{{ asset('homePage/js/jquery.counterup.min.js') }}"></script>
	<script src="{{ asset('homePage/js/owl.carousel.min.js') }}"></script>
	<script src="{{ asset('homePage/js/isotope-3.0.6-min.js') }}"></script>
	<script src="{{ asset('homePage/js/magnific-popup.min.js') }}"></script>
	<script src="{{ asset('homePage/js/jquery.sticky.js') }}"></script>
	<script src="{{ asset('homePage/js/jquery.barfiller.js') }}"></script>
	<script src="https://accounts.google.com/gsi/client" async defer></script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBocZSjbROyYaHW7i_NrdNxtCyV5UpFXl4&libraries=places" defer></script>
	{{-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAXlsiAoJrtvoxasPxnF3VhTrBxzlUmToc&libraries=places" defer></script> --}}
  @endif
</head>

<body>
  @if (request()->is('admin*'))
	<div id="loading-bg">
		<div class="loading-logo">
		<img src="{{ asset('logo.png') }}" alt="Logo" />
		</div>
		<div class="loading">
		<div class="effect-1 effects"></div>
		<div class="effect-2 effects"></div>
		<div class="effect-3 effects"></div>
		</div>
	</div>
  @endif
  <div id="app">
  </div>

  <script src="{{ asset(mix('js/app.js')) }}"></script>

</body>

</html>
