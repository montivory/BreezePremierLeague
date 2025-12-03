<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/logo/logo.svg') }}">
    <meta property="og:site_name" content="{{ config('app.webtitle') }}">
    <meta property="og:url" content=" {{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="th" />
    <meta property="fb:pages" content="">
    <meta property="fb:app_id" content="">
    @yield('meta')
    <title>{{ config('app.webtitle') }} @yield('title')</title>
    @include('layouts.stylesheet')
    @yield('stylesheet')
    @include('layouts.trackscript')
    <style>
        .history-icon::before {
            content: "";
            background-image: url('{{ asset('assets/images/menu/history.svg') }}');
            background-repeat: no-repeat;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 8px;
        }

        .terms-icon::before {
            content: "";
            background-image: url('{{ asset('assets/images/menu/terms.svg') }}');
            background-repeat: no-repeat;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 8px;
        }
    </style>
</head>

<body>
    <main class="col-md-8 col-lg-6 col-xl-4 col-12">
        @yield('content')
    </main>
    <div class="offcanvas offcanvas-start sidebar-menu" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenu">
        <div class="offcanvas-body d-flex align-items-start flex-column">
            <div class="menu-detail">
                <a data-bs-dismiss="offcanvas" href="javascript:void(0);">
                    <img src="{{ asset('assets/images/icons/x.svg') }}" />
                </a>
                <div class="d-flex flex-column menu-items mt-3">
                    <div class="menu-item">
                        <a href="javascript:void(0);" link="{{ route('member.history') }}"
                            class="menu-link analytic-link" eventLabel="send receipt-{{ route('member.history') }}">
                            <span class="history-icon"></span>ประวัติการส่งใบเสร็จ
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="javascript:void(0);" link="{{ route('term') }}" class="menu-link analytic-link"
                            eventLabel="terms and conditions-{{ route('term') }}">
                            <span class="terms-icon"></span>@lang('rule.rules')
                        </a>
                    </div>
                    <div class="mt-4">
                        <a href="javascript:void(0);" link="{{ route('signout') }}" class="signout analytic-link"
                            eventLabel="log out">@lang('menu.logout')</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.javascript')
    <script type="text/javascript">
        // Setup CSRF token on every request
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(function() {
        $('.analytic-link').on('click', function() {
                let url = $(this).attr('link');
                @production
                if (typeof ctConstants !== "undefined") {
                    let eventLabel = $(this).attr('eventLabel');
                    var ev2 = {};
                    ev2.eventInfo = {
                        'type': ctConstants.trackEvent,
                        'eventAction': ctConstants.linkClick,
                        'eventLabel': eventLabel,
                        'eventValue': 1
                    };
                    ev2.category = {
                        'primaryCategory': ctConstants.custom
                    };
                    digitalData.event.push(ev2);
                }
            @endproduction
            window.location.href = url;
        }); $('.analytic-link-out').on('click', function() {
            @production
            if (typeof ctConstants !== "undefined") {
                let eventLabel = $(this).attr('eventLabel');
                var ev2 = {};
                ev2.eventInfo = {
                    'type': ctConstants.trackEvent,
                    'eventAction': ctConstants.linkClick,
                    'eventLabel': eventLabel,
                    'eventValue': 1
                };
                ev2.category = {
                    'primaryCategory': ctConstants.custom
                };
                digitalData.event.push(ev2);
            }
        @endproduction
        });
        });
    </script>
    @yield('script')
</body>

</html>
