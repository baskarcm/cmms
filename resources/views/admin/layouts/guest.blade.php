
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />

	<title>{{ config('app.name', 'DEMS') }} @isset($title) :: {{ $title }} @endisset</title>
	
	<meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
	<link rel="icon" href="{{ asset('admin/assets/img/favicon.ico') }}" type="image/x-icon"/>
	
	
	<!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

	<!-- Fonts and icons -->
	<script src="{{ asset('admin/assets/js/plugin/webfont/webfont.min.js') }}"></script>
	<script>
		WebFont.load({
			google: {"families":["Lato:300,400,700,900"]},
			custom: {"families":["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"], urls: ['{{ asset('admin/assets/css/fonts.min.css') }}']},
			active: function() {
				sessionStorage.fonts = true;
			}
		});
	</script>
	
	<!-- CSS Files -->
	<link rel="stylesheet" href="{{ asset('css/app.css') }}">
	<link rel="stylesheet" href="{{ asset('admin/assets/css/atlantis.css') }}">
	<link rel="stylesheet" href="{{ asset('common/css/style.css') }}">
	@stack("css")
</head>
<body class="login">

	@yield("content")
	
	<script src="{{ asset('js/app.js') }}"></script>
	<script src="{{ asset('admin/assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js') }}"></script>
	<script src="{{ asset('admin/assets/js/atlantis.js') }}"></script>
	<script src="{{ asset('common/js/script.js') }}"></script>
	<script src="{{ asset('common/vendor/validate/jquery.validate.min.js') }}"></script>
	<script src="{{ asset('common/vendor/validate/additional-methods.js') }}"></script>
	<!-- Bootstrap Notify -->
	<script src="{{ asset('admin/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js') }}"></script>

	@stack("js")
</body>
</html>