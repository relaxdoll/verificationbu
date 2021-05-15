<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('One Tracking') }}</title>

    <link rel="shortcut icon" href="/img/oneibis.png" type="image/x-icon">

    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport'/>
    <!--     Fonts and icons     -->
{{--    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,600,700,800"/>--}}
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons"/>
{{--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">--}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css">
<!-- CSS Files -->
    <link href="{{ asset('material') }}/css/material-dashboard.css?v=2.1.1" rel="stylesheet"/>
<!-- CSS Just for demo purpose, don't include it in your project -->
    {{--<link href="{{ asset('material') }}/demo/demo.css" rel="stylesheet"/>--}}
{{--    <link href="/css/black-dashboard.css" rel="stylesheet"/>--}}
{{--        <link href="/css/syncfusion.css" rel="stylesheet"/>--}}
{{--        <link href="/css/theme.css" rel="stylesheet"/>--}}
    <link href="/css/loader.css" rel="stylesheet"/>
    <link href="{{ asset('black') }}/css/nucleo-icons.css" rel="stylesheet"/>
    <link href="{{ asset('black') }}/css/eec-icons.css" rel="stylesheet"/>

    @yield('style')
    <style>

    </style>
</head>
<body>
<div id="asset">
    <div id="" :class="$store.getters.getNavbarState">
        @auth()
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <loading></loading>
            @include('layouts.page_templates.auth')
        @endauth

        @guest()
            @include('layouts.page_templates.guest')
        @endguest
    </div>
</div>
{{--        @include('layouts.settings')--}}

<!--   Core JS Files   -->
{{--<script src="/js/before.js"></script>--}}
@stack('js')
<script src="/js/after.js"></script>
</body>
</html>
