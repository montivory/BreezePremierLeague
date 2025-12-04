@extends('layouts.template')
@section('title')
    : Term
@endsection
@section('meta')
    <meta name="description" content="{{ config('app.webtitle') }} - Term">
    <meta name="keywords" content="Term">
    <meta property="og:title" content="{{ config('app.webtitle') }} - Term">
    <meta property="og:description" content="{{ config('app.webtitle') }} - Term">
    <meta property="og:image" content="">
    <meta property="twitter:image" content="">
@endsection
@section('stylesheet')
    <link rel="stylesheet" href="{{ asset('assets/css/term.css') }}" type="text/css">
@endsection
@section('aascript')
    digitalData.page.category.pageType = "Term page";
    digitalData.page.attributes.contentType = "Submission";
    digitalData.page.attributes.articleName = "ข้อตกลงและเงื่อนไข";
@endsection
@section('content')
    @php
        $previousUrl = url()->previous();
        $previousPath = parse_url($previousUrl, PHP_URL_PATH);
        $goTo = $previousPath === '/' || $previousPath === '/home' ? 'home' : 'member';
    @endphp

    <x-header url="{{ route($goTo) }}" />

    <div class="content-block">
        <div class="col-12 d-flex flex-column">
            <div class="term-detail">
                <div>
                    <h1 class="term-title text-center">
                        เงื่อนไขการร่วมกิจกรรม ‘เชียร์พรีเมียร์ลีกกับบรีสที่ Emirates Stadium’
                    </h1>
                </div>
                <x-term-detail />
            </div>
        </div>
    </div>
@endsection
@section('script')
@endsection
