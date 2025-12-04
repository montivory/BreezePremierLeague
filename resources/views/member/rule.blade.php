@extends('layouts.template')
@section('title')
    : Campaign detail
@endsection
@section('meta')
    <meta name="description" content="{{ config('app.webtitle') }} - Campaign detail">
    <meta name="keywords" content="Campaign detail">
    <meta property="og:title" content="{{ config('app.webtitle') }} - Campaign detail">
    <meta property="og:description" content="{{ config('app.webtitle') }} - Campaign detail">
    <meta property="og:image" content="">
    <meta property="twitter:image" content="">
@endsection
@section('stylesheet')
    <link rel="stylesheet" href="{{ asset('assets/css/rule.css') }}" type="text/css">
@endsection
@section('aascript')
    digitalData.page.category.pageType = "Campaign detail page";
    digitalData.page.attributes.contentType = "Submission";
    digitalData.page.attributes.articleName = "รายละเอียดกิจกรรม";
@endsection
@section('content')
    <x-header />
    <div class="content-block">
        <div class="col-12 d-flex flex-column">
            <div>
                <img src="{{ asset('assets/images/banner.jpg') }}" class="img-fluid w-100" />
            </div>
            <div class="d-flex flex-column p-4">
                <div>
                    <h1 class="rule-title">รายละเอียดกิจกรรม</h1>
                </div>
                <div class="rule-section text-center">
                    <div>
                        <p class="rule-text">ผู้มียอดการซื้อผลิตภัณฑ์สะสมสูงสุด 5 ท่านแรก รับทันที</p>
                    </div>
                    <div class="border-section">
                        <div class="rule-sub-title">บัตรเข้าชมฟุตบอล<br>AFC vs Manchester United<br>Premier League</div>
                        <div class="rule-text my-2">ณ สนามฟุตบอล Emirates Stadium<br>สหราชอาณาจักร<br>ในวันที่ 25 มกราคม
                            2026</div>
                        <div class="rule-text" style="font-size: 12px;">ท่านละ 1 รางวัล รางวัลละ 2 ใบ มูลค่าใบละ 450
                            ปอนด์สเตอร์ลิง<br>(เทียบเท่าประมาณ 18,900 บาท)</div>
                    </div>
                    <div class="rule-sub-title fs-6">เพียงซื้อสินค้าที่ร่วมรายการผ่าน Shopee, Lazada หรือ TikTok
                        พร้อมส่งหลักฐานที่เว็บไซต์</div>
                </div>
                <div class="d-grid gap-2 regis-section mt-4">
                    <a href="javascript:void(0);" link="{{ route('upload') }}" class="analytic-link btn btn-main"
                        eventLabel="join campaign now-{{ route('upload') }}">
                        @lang('index.registerlink')
                    </a>
                </div>
                <div class="text-center mt-4">
                    <a href="javascript:void(0);" link="{{ route('term') }}" class="btn btn-link analytic-link"
                        eventLabel="terms and conditions-{{ route('term') }}">ข้อกำหนดและเงื่อนไขในการเข้าร่วม</a>
                </div>
            </div>
        </div>
    </div>
    </div>
    <x-member-menu />
@endsection
@section('script')
    <script type="text/javascript">
        $(function() {
            $('.nav-link').on('click', function() {
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
@endsection
