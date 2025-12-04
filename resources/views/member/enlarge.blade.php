@extends('layouts.template')
@section('title')
    : View Slip
@endsection
@section('meta')
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta property="og:title" content="">
    <meta property="og:description" content="">
    <meta property="og:image" content="">
    <meta property="twitter:image" content="">
    <meta property="og:url" content="">
@endsection
@section('stylesheet')
    <link rel="stylesheet" href="{{ asset('assets/css/zoomist.css') }}" type="text/css">
    <style>
        .slip-preview {
            max-height: auto !important;
        }

        .content-block {
            padding-bottom: 64px;
        }
    </style>
@endsection
@section('aascript')
    digitalData.page.category.pageType = "Receipt Upload page";
    digitalData.page.attributes.contentType = "Submission";
    digitalData.page.attributes.articleName = "อัพโหลดใบเสร็จ";
@endsection
@section('content')
    <div class="content-block">
        <div class="col-12">
            <div class="zoomist-container">
                <div class="zoomist-wrapper">
                    <!-- zoomist-image is required -->
                    <div class="zoomist-image">
                        <!-- you can add anything you want to zoom here. -->
                        <img src="{{ $url }}" class="slip-preview w-100" />
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-4">
            <div class="col-12 px-4">
                <a href="javascript:void(0);" class="btn btn-outline">
                    ย้อนกลับ
                </a>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="module">
        import Zoomist from '{{ asset('/js/zoomist.js') }}'

        const zoomist = new Zoomist(".zoomist-container", {
            // Optional parameters
            maxScale: 10,
            bounds: false,
            // if you need slider
            slider: true,
            // if you need zoomer
            zoomer: true,
        });
    </script>
    <script type="text/javascript">
        $(function() {
            $('.btn-outline').on('click', function() {
                window.close();
            });
        })
    </script>
@endsection
