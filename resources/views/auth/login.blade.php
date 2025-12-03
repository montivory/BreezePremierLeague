<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/x-icon" href="">
    <meta property="og:site_name" content="">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="th" />
    <meta property="fb:pages" content="">
    <meta property="fb:app_id" content="">
    @yield('meta')
    <title>{{config('app.webtitle')}} - @yield('title')</title>
    @include('layouts.stylesheet')
    <style>
        body {
            display: flex;
            height: 100vh;
            justify-content: center;
            align-items: center;
        }
        .form-control {
            background-color: #fff!important;
        }
    </style>
</head>

<body>
    <div class="col-md-4 col-12">
        @if ($errors->any())
            <div class="mb-4">
                <div class="alert alert-danger" role="alert">
                    Email or Password miss match.
                </div>
            </div>
        @endif
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <!-- Email Address -->
            <div class="mb-3 row">
                <label for="email" class="col-2 col-form-label">Email</label>
                <div class="col-10">
                    <input class="form-control" id="email" type="email" name="email" required="required"
                        autofocus="autofocus" autocomplete="username" value="{{ old('email') }}">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="password" class="col-2 col-form-label">Password</label>
                <div class="col-10">
                    <input class="form-control" id="password" type="password" name="password" required="required"
                        autocomplete="current-password">
                </div>
            </div>
            <div class="row">
                <div class="col-10 offset-2">
                    <button type="submit" class="btn btn-outline-primary">Sign In</button>
                </div>
            </div>
        </form>
    </div>
    @include('layouts.javascript')
</body>

</html>
