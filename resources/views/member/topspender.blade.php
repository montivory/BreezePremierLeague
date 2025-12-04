@extends('layouts.template')
@section('title')
    : Top Spender
@endsection
@section('meta')
    <meta name="description" content="{{ config('app.webtitle') }} - Top Spender">
    <meta name="keywords" content="Top Spender">
    <meta property="og:title" content="{{ config('app.webtitle') }} - Top Spender">
    <meta property="og:description" content="{{ config('app.webtitle') }} - Support">
    <meta property="og:image" content="">
    <meta property="twitter:image" content="">
@endsection
@section('stylesheet')
    <link rel="stylesheet" href="{{ asset('assets/css/topspender.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/css/history.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/css/historyitem.css') }}" type="text/css">
@endsection
@section('aascript')
    digitalData.page.category.pageType = "Topspender page";
    digitalData.page.attributes.contentType = "Submission";
    digitalData.page.attributes.articleName = "Top Spender";
@endsection
@section('content')
    <x-header url="{{ route('member') }}" />
    <div class="content-block">
        <div class="align-content-between flex-wrap body-block">
            <div class="col-12 px-3">
                <div class="d-flex flex-column">
                    <div class="d-flex flex-wrap flex-column">
                        <div class="my-4">
                            <h2 class="text-center">Top Spender</h2>
                            <p class="title-detail text-center">*คะแนนจะอัปเดตหลังตรวจสอบใบเสร็จภายใน 1-3 วันทำการ</p>
                        </div>
                        <div>
                            @php
                                $top = array_slice($totalTopspender, 0, 10);
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
                            <div class="">
                                <div class="topspander-section">
                                    @foreach ($top as $index => $spender)
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h3 class="mb-0">{{ $index + 1 }}.
                                                {{ maskName($spender->firstname) }}
                                            </h3>
                                            <h4 class="mb-0">{{ number_format($spender->point, 2, '.', ',') }} บาท</h4>
                                        </div>
                                        @if (count($top) > 1 && $index < count($top) - 1)
                                            <div class="line mt-2 mb-3"></div>
                                        @endif
                                    @endforeach
                                </div>
                                <div class="d-flex align-items-center justify-content-between px-4">
                                    <div>
                                        คุณมียอดสะสม
                                    </div>
                                    <div class="point-text">
                                        {{ $point === 0 ? '0' : number_format($point, 2, '.', ',') }} บาท
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-grid gap-2 lucky-link" style="margin: 16px 24px;">
                            <a href="javascript:void(0);" link="{{ route('upload') }}" class="analytic-link btn btn-main"
                                eventLabel="join campaign now-{{ route('upload') }}">
                                ส่งหลักฐานการชำระเงิน
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript"></script>
@endsection
