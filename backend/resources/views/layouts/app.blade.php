<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, user-scalable=0" />
    <meta name="description" content="PLIZI: Социальная сеть" />
    <link rel="shortcut icon" href="storage/images/favicon.ico" />


{{--    <link rel="stylesheet" type="text/css" href="{{ asset('fonts/font-awesome/css/font-awesome.min.css') }}" />--}}
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-4.3.1.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('font-awesome/css/all.css') }}" />

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" />

    <title>{{ config('app.name', 'Laravel') }}</title>
    <script>
        var apiURL = '{{ config('app.api_url') }}';
        var wsUrl = "{{ config('app.ws_url') }}";
    </script>
</head>
<body>
<div id="app"></div>

<!-- Scripts -->
<script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('js/bootstrap-4.3.1.min.js') }}"></script>

<script src="{{ asset('js/autobahn.min.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
