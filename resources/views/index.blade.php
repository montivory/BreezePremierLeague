@extends('layouts.template')
@section('meta')
    <meta name="description" content="{{ config('app.webtitle') }}">
    <meta name="keywords" content="{{ config('app.webtitle') }}">
    <meta property="og:title" content="{{ config('app.webtitle') }}">
    <meta property="og:description" content="{{ config('app.webtitle') }}">
    <meta property="og:image" content="">
    <meta property="twitter:image" content="">
    <meta property="og:url" content="{{ url()->current() }}">
@endsection
@section('stylesheet')
    <link rel="stylesheet" href="{{ asset('assets/css/index.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/css/term.css') }}" type="text/css">
@endsection
@section('aascript')
    digitalData.page.dmpattributes.price= "Freebie Hunter";
    digitalData.page.dmpattributes.product= "Detergent, Liquid Detergent";
    digitalData.page.dmpattributes.interest= "Quick Wash";
    digitalData.page.dmpattributes.purchasedetails= "Buyer, Online Purchase";
    digitalData.page.dmpattributes.sport= "Football, Football Fans, Soccer Players";
    digitalData.page.dmpattributes.travel= "Destination_Europe";
@endsection
@section('content')
    @if ($message = Session::get('error'))
        <div class="position-absolute px-4 mt-5 col-md-4 col-12" style="z-index: 99;">
            <div class="alert alert-clear flex-grow-1">
                {{ $message }}
            </div>
        </div>
    @endif
    <div class="d-flex flex-wrap content-block">
        <div class="col-12">
            <div class="d-flex flex-column">
                <div class="text-center">
                    <img src="{{ asset('assets/images/landing.jpg') }}" class="img-fluid w-100" />
                </div>
                <div class="d-grid gap-3 regis-section">
                    <button class="btn btn-main analytic-link" id="link-signup" link="{{ route('signup') }}"
                        eventLabel="sign up">ใช้งานครั้งแรก / Register</button>
                    <button class="btn btn-main analytic-link" id="link-signin" link="{{ route('signin') }}"
                        eventLabel="sign in">เข้าร่วมกิจกรรม / Log In</button>
                </div>
                <div class="mx-auto">
                    <button type="button" class="btn btn-link analytic-link" link="{{ route('term') }}"
                        eventLabel="terms and conditions-{{ route('term') }}">ข้อกำหนดและเงื่อนไขในการเข้าร่วม</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body" style="px-2">
                    <div class="modal-title">
                        เงื่อนไขการร่วมกิจกรรม ‘เชียร์พรีเมียร์ลีกกับบรีสที่ Emirates Stadium’
                    </div>
                    <div class="term-content">
                        <div class="term-detail">
                            <x-term-detail />
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="not-show">
                            <label class="form-check-label" for="not-show">
                                ฉันได้อ่านและยอมรับข้อตกลงและเงื่อนไขแล้ว
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-main w-100" id="closeModalBtn" disabled>ตกลง</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            var $modal = $('#alertModal');
            var $closeBtn = $('#closeModalBtn');
            var $checkbox = $('#not-show');

            var bsModal = new bootstrap.Modal($modal[0]);

            @if (!$notshow)
                bsModal.show();
            @endif

            $checkbox.on('change', function() {
                $closeBtn.prop('disabled', !$(this).is(':checked'));
            });

            $closeBtn.on('click', function() {
                if ($checkbox.is(':checked')) {
                    bsModal.hide();
                }
            });

            $modal.on('hide.bs.modal', function(e) {
                if (!$checkbox.is(':checked')) {
                    e.preventDefault();
                }
            });

            $modal.on('hidden.bs.modal', function() {
                if ($checkbox.is(':checked')) {
                    document.cookie = "notshow_breeze2025YearEnd=true; path=/; max-age=" + 60 * 60 * 24 *
                        365;
                }
            });
        });
    </script>
@endsection
