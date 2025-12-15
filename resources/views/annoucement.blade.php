@extends('layouts.template')
@section('title')
    : Annoucement
@endsection
@section('meta')
    <meta name="description" content="{{ config('app.webtitle') }} - Annoucement">
    <meta name="keywords" content="Annoucement">
    <meta property="og:title" content="{{ config('app.webtitle') }} - Annoucement">
    <meta property="og:description" content="{{ config('app.webtitle') }} - Support">
    <meta property="og:image" content="">
    <meta property="twitter:image" content="">
@endsection
@section('stylesheet')
    <link rel="stylesheet" href="{{ asset('assets/css/annoucement.css') }}" type="text/css">
@endsection
@section('aascript')
    digitalData.page.category.pageType = "Annoucement page";
    digitalData.page.attributes.contentType = "Submission";
    digitalData.page.attributes.articleName = "ประกาศรายชื่อผู้โชคดี";
@endsection
@section('content')
    <x-header-nonclickable />
    <div class="content-block">
        <div class="align-content-between flex-wrap body-block">
            <div>
                <img src="{{ asset('assets/images/banner.jpg') }}" class="img-fluid w-100" />
            </div>
            <div class="col-12 px-3">
                <div class="d-flex flex-column">
                    <div class="d-flex flex-wrap flex-column">
                        <div>
                            @php
                                $top = array_slice($totalTopspender, 0, 5);
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
                            <div>
                                <div class="topspander-section">
                                    <h2 class="text-center">ประกาศผล<br>Top Spender</h2>
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
                                <div class="text-center mt-4">
                                    <a href="javascript:void(0);" link="{{ route('term') }}"
                                        class="btn btn-link analytic-link"
                                        eventLabel="terms and conditions-{{ route('term') }}">ข้อกำหนดและเงื่อนไขการรับรางวัล</a>
                                </div>
                            </div>
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
