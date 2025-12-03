@extends('layouts.adminform')
@section('title')
    : Receipt View
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
    <link rel="stylesheet" href="{{ asset('assets/css/slipverify.css') }}" type="text/css">
    <style>
    </style>
@endsection
@section('content')
    <div class="container">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.slip') }}">Receipt</a></li>
                <li class="breadcrumb-item active" aria-current="page">Receipt View</li>
            </ol>
        </nav>
        <div class="row">
            <div class="col-5">
                <div class="zoomist-container">
                    <div class="zoomist-wrapper">
                        <!-- zoomist-image is required -->
                        <div class="zoomist-image">
                            <!-- you can add anything you want to zoom here. -->
                            <img src="{{ $slip->url }}" class="slip-preview" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-7">
                <div class="row mb-3 pt-3">
                    <div class="col-12">
                        @if ($slip->status == 'approve')
                            <h2 class="status-title text-start">Status : <span class="approve-title">Approve</span> By :
                                {{ $slip->editby }}</h2>
                        @else
                            <h2 class="status-title text-start">Status : <span class="reject-title">Reject</span> By :
                                {{ $slip->editby }}</h2>
                        @endif
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-5">
                        Date & Time<br>
                        {{ $slip->created_at->Format('d/m/Y H:i') }}
                    </div>
                    <div class="col-7">
                        User Name<br>
                        {{ $member->firstname }} {{ $member->lastname }}
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="receiptno" class="col-form-label col-3">Receipt ID : </label>
                    <div class="col-9">
                        <input type="text" name="receiptno" id="receiptno" value="{{ $slip->receiptno }}"
                            class="form-control" />
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="merchantname" class="col-form-label col-3">Merchant Name : </label>
                    <div class="col-9">
                        <input type="text" name="merchantname" id="merchantname" value="{{ $slip->merchantname }}"
                            class="form-control" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row summary">
        <div class="container">
            <div class="row justify-content-end">
                <div class="col-4 summary-item">
                    <span>Total Price : <span class="total-price">{{ $slip->point }}</span></span>
                </div>
            </div>
        </div>
    </div>
    <div class="container d-flex justify-content-end mt-3 mb-5">
        <div>
            <a class="btn btn-back" href="{{ route('admin.slip') }}">
                Close
            </a>
        </div>
    </div>
@endsection
@section('script')
    <script type="module">
        import Zoomist from '{{ asset('assets/js/zoomist.js') }}'

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
    <script type="text/javascript"></script>
@endsection
