<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>{{\App\AppConfig::GENERAL_PRODUCT_NAME }}</title>
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
			<link rel="stylesheet" href="{{ asset('assets/css/custom_style.css') }}">
		<!-- jQuery -->
		<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
		<!--Jquery Boostrap-->
		<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
		<!-- jquery-validation -->
		<script src="{{ asset('assets/jquery-validation/jquery.validate.min.js') }}"></script>
		<!--Custom Js-->
		<script src="{{ asset('assets/custom/js/installation.js') }}"></script>
		<!--Custom validator-->
		<script src="{{ asset('assets/custom/js/custom_validator.js') }}"></script>
		<script type="text/javascript">
			  var base_url="{{URL('')}}/";
		  </script>
	</head>
	<body>
		  <div class="container">
              <div class="row">
				@yield('header')
				@yield('content')
              </div>
            </div>	
			@yield('footer')
	</body>
</html>