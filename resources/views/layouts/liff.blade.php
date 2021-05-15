<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ getenv('APP_NAME') }}</title>

    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport'/>
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css">

    <link href="{{ mix('/material/css/material-dashboard-liff.css') }}" rel="stylesheet"/>
{{--    <link href="/fonts/liff/css/style.css" rel="stylesheet"/>--}}

    @yield('style')
</head>
<body>
<div id="asset">

    @yield('content')

</div>

{{--@include('footer')--}}

<script src="{{ mix('/material/js/liff.js') }}"></script>
@yield('js', View::make('vue'))
@stack('pjs')

</body>
</html>
