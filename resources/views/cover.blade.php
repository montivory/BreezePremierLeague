<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/logo/logo.svg') }}">
    <meta property="og:site_name" content="">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="th" />
    <meta property="fb:pages" content="">
    <meta property="fb:app_id" content="">
    <title>{{ config('app.webtitle') }}</title>
    @include('layouts.stylesheet')
    <style>
        body {
            background-color: #fff !important;
        }

        main {
            background: none;
        }
    </style>
</head>

<body>
    <main class="d-flex flex-column justify-content-center col-md-6 col-12 mx-auto">
        <img src="{{ asset('assets/images/cover.jpeg') }}" class="w-100" />
    </main>
</body>

</html>
