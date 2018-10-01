<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Blog') }}</title>

    <link href="{{ URL::asset('css/app.css') }}" rel="stylesheet">

    @yield('css')
</head>
<body>
    <div id="app">
        @include('inc.navbar')
        @yield('content')
{{--        @include('inc.footer')--}}
    </div>

    <script type="text/javascript" src="{{ URL::asset('js/app.js') }}"></script>
    @yield('js')
</body>
</html>
