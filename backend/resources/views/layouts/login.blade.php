<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />

	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, user-scalable=0" />
	<meta name="keywords" content="Ключевые слова" />
	<meta name="description" content="Описание сайта" />
	<link rel="shortcut icon" href="storage/images/favicon.ico" />

    <!-- Fonts -->
{{--    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}" />--}}
{{--    <link rel="stylesheet" type="text/css" href="{{ asset('css/fonts.css') }}" />--}}

    <link rel="stylesheet" type="text/css" href="{{ asset('fonts/font-awesome/css/font-awesome.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-4.3.1.min.css') }}" />

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" />

    <title>{{ config('app.firstName', 'Laravel') }}</title>
</head>
<body>
    <div id="app"></div>

    <!-- Scripts -->
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-4.3.1.min.js') }}"></script>

    <script src="{{ asset('js/app.js') }}" defer></script>

</body>
</html>
