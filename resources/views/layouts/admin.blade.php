<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/Logo/logo.svg') }}">
    <meta property="og:site_name" content="">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="th" />
    <meta property="fb:pages" content="">
    <meta property="fb:app_id" content="">
    @yield('meta')
    <title>{{ config('app.webtitle') }} @yield('title')</title>
    <!-- Plugin CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/css/fonts.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/css/datatables.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap5.css') }}" type="text/css">
    @yield('stylesheet')
</head>

<body>
    <header class="navbar bg-primary flex-md-nowrap p-0">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="{{ route('admin.dashboard') }}">Unilever</a>
    </header>

    <div class="container-fluid">
        <div class="row">
            @include('layouts.admin-sidebar')
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                @yield('content')
            </main>
        </div>
    </div>
    @include('layouts.javascript')
    @yield('script')
</body>

</html>
