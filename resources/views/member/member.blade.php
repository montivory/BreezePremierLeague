@extends('layouts.template')
@section('title')
    : Member
@endsection
@section('meta')
    <meta name="description" content="{{ config('app.webtitle') }} - Member">
    <meta name="keywords" content="Member">
    <meta property="og:title" content="{{ config('app.webtitle') }} - Member">
    <meta property="og:description" content="{{ config('app.webtitle') }} - Member">
    <meta property="og:image" content="">
    <meta property="twitter:image" content="">
@endsection
@section('stylesheet')
    <link rel="stylesheet" href="{{ asset('assets/css/member.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/css/upload.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/css/historyitem.css') }}" type="text/css">
@endsection
@section('aascript')
    digitalData.page.category.pageType = "Member page";
    digitalData.page.attributes.contentType = "Submission";
    digitalData.page.attributes.articleName = "หน้าสมาชิก";
@endsection
@section('content')
    <x-header />
    <div class="content-block">
        <div class="col-12 d-flex flex-column">
            <div>
                <img src="{{ asset('assets/images/banner.jpg') }}" class="banner w-100 img-fluid" />
            </div>
            <div class="point-section d-flex flex-column my-4 ">
                <div>
                    @lang('member.point', [
                        'point' => $point == 0 ? '0' : number_format($point, 2, '.', ','),
                    ])
                </div>
                <div class="line my-3"></div>
                <div>
                    <h3 class="text-center">Top Spender</h3>
                    <div class="topspander-section">
                        @php
                            function maskName($name)
                            {
                                $length = mb_strlen($name);
                                if ($length <= 2) {
                                    $visible = mb_substr($name, 0, 1);
                                    $masked = str_repeat('*', $length - 1);
                                } elseif ($length == 3) {
                                    $visible = mb_substr($name, 0, 1);
                                    $masked = str_repeat('*', 2);
                                } elseif ($length == 4) {
                                    $visible = mb_substr($name, 0, 1);
                                    $masked = str_repeat('*', 3);
                                } else {
                                    $visible = mb_substr($name, 0, 2);
                                    $masked = str_repeat('*', $length - 2);
                                }
                                return $visible . $masked;
                            }
                        @endphp
                        @foreach ($topSpenders as $index => $spender)
                            @if ($index < 3)
                                <div class="d-flex justify-content-between align-items-center">
                                    <p>{{ $index + 1 }}.
                                        {{ maskName($spender->firstname) }}
                                    </p>
                                    <h4>{{ number_format($spender->point, 2, '.', ',') }} บาท</h4>
                                </div>
                                @if ($index !== min(2, count($topSpenders) - 1))
                                    <div class="line"></div>
                                @endif
                            @endif
                        @endforeach
                    </div>
                    <div class="btn-point-section">
                        <a href="javascript:void(0);" class="topspender-link btn btn-show-more analytic-link"
                            link="{{ route('topspender') }}"
                            eventLabel="transaction topspender-{{ route('topspender') }}">
                            @lang('upload.viewall')
                        </a>
                    </div>
                </div>
            </div>
            <div class="mx-4">
                <div class="transection">
                    @lang('upload.history')
                </div>
                <div class="history-item">
                    @if (sizeof($transactions) > 0)
                        @foreach ($transactions as $transaction)
                            <x-history-item :item="$transaction" />
                        @endforeach
                    @else
                        <div>
                            <img src="{{ asset('assets/images/no-history.svg') }}" class="no-history-photo" />
                            <h2 class="nohistory-title">
                                @lang('history.nohistory')
                            </h2>
                            <p class="nohistory-detail">
                                @lang('history.nohistorydetail')
                            </p>
                        </div>
                    @endif
                </div>
                @if (sizeof($transactions) > 0)
                    <div class="d-flex flex-wrap btn-outline-section mt-4">
                        <div class="col-12">
                            <a href="javascript:void(0);" class="history-link btn btn-outline analytic-link"
                                link="{{ route('member.history') }}"
                                eventLabel="transaction history-{{ route('member.history') }}">
                                @lang('upload.viewall')
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <x-member-menu />
@endsection
@section('script')
    <script type="text/javascript">
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        @production
        @if ($message = Session::get('success'))
            @if ($message == 'newaccount')
                function sendEventToAnalytics() {
                    var event = {};
                    event.eventInfo = {
                        type: "trackEvent",
                        eventAction: "SignUp Submit",
                        eventLabel: "GIGYA|Form Submit|Newsletter Sign Up",
                        eventValue: 1
                    };
                    event.category = {
                        primaryCategory: "Other"
                    };
                    event.subcategory = "Lead";
                    digitalData.event.push && digitalData.event.push(event);
                    return true;
                }
                setTimeout(function() {
                    sendEventToAnalytics();
                }, 2000);
            @endif
        @endif
        @endproduction
    </script>
@endsection
