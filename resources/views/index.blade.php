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
    digitalData.page.dmpattributes.price = "Freebie Hunter";
    digitalData.page.dmpattributes.interest = "Brightening, Home Skin Care, Body Serum, Body Lotion";
    digitalData.page.dmpattributes.purchasedetails = "Buyer, Retailer Lotus, Discount Coupon";
    digitalData.page.dmpattributes.persona = "Carefree Eaters";
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
                {{-- <div class="d-grid gap-2 regis-section">
                    <a class="btn btn-main analytic-link" id="link-signup"
                        link="https://access.line.me/oauth2/v2.1/authorize?response_type=code&client_id={{ config('app.line_login_clientid') }}&redirect_uri={{ urlencode(config('app.line_login_callback_url')) }}&state={{ csrf_token() }}&scope=profile%20openid%20email%20phone"
                        eventLabel="sign up with Line-https://line.me">
                        <img src="{{ asset('assets/images/click-button.png') }}" class="w-100" />
                    </a>
                </div>
                <div class="d-grid regis-section term-section">
                    <a href="javascript:void(0);" link="{{ route('term') }}" class="btn btn-main analytic-link"
                        eventLabel="terms and conditions-{{ route('term') }}">
                        <img src="{{ asset('assets/images/term.png') }}" class="img-fluid w-100" />
                    </a>
                </div>
                <div class="remark-section pb-5">
                    <p class="remark">หมายเหตุ</p>
                    <ul class="remark-list">
                        <li>สินค้า 1 แถม 1 และสินค้าล้างสต๊อกไม่ร่วมรายการ</li>
                        <li>ระยะเวลาโปรโมชั่น ลูกค้าซื้อสินค้าและลงทะเบียนได้ตั้งแต่วันที่ 13 พ.ย. 2568 – 10 ธ.ค. 2568</li>
                        <li>ลูกค้าสามารถแลกคูปองได้ภายใน 15 ธ.ค. 2568 (5 วันหลังสิ้นสุดรายการโปรโมชั่น)</li>
                        <li>ชุดเมนูที่ร่วมรายการ ได้แก่ ชุดอิ่มคุ้ม 2 ประกอบด้วย ไก่ทอด 2 ชิ้น เฟรนซ์ฟราย ปกติ 1 ชุด
                            และเป๊ปซี่ 1
                            แก้ว</li>
                        <li>สามารถแลกรับโปรโมชั่นนี้ได้ที่แคชเชียร์เท่านั้น ช่องทางการสั่งโดยเครื่องสั่งอาหารอัตโนมัติ
                            (Kiosk)
                            ไม่ร่วมรายการ</li>
                        <li>ไม่สามารถใช้ร่วมกับคูปองส่วนลดหรือโปรโมชั่นอื่นได้</li>
                        <li>คูปองมีจำนวนจำกัด 15,000 สิทธิ์ โดยนับจากจำนวนคูปองที่มีการนำมาแลกที่ร้าน KFC เท่านั้น</li>
                        <li>อาหารในภาพจัดตกแต่งเพื่อการโฆษณาเท่านั้น</li>
                        <li>ไม่สามารถเปลี่ยนขนาดหรือชนิดของสินค้าในชุดได้</li>
                        <li>ขอสงวนสิทธิ์การเลือกชิ้นไก่และการเปลี่ยนแปลงเงื่อนไขโดยไม่ต้องแจ้งให้ทราบล่วงหน้า</li>
                        <li>ไม่สามารถเปลี่ยนหรือทอนเป็นเงินสดได้</li>
                        <li>สำหรับทานที่ร้านหรือซื้อกลับบ้าน ณ โลตัสสาขาที่ร่วมรายการเท่านั้น</li>
                    </ul>
                </div> --}}
                <div class="my-4 mx-4">
                    <button class="btn btn-main analytic-link w-100" id="link-signup"
                        link="https://access.line.me/oauth2/v2.1/authorize?response_type=code&client_id={{ config('app.line_login_clientid') }}&redirect_uri={{ urlencode(config('app.line_login_callback_url')) }}&state={{ csrf_token() }}&scope=profile%20openid%20email%20phone"
                        eventLabel="sign up with Line-https://line.me">
                        เข้าร่วมกิจกรรม
                    </button>
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
                        <div>
                            <h1 class="term-detail">
                                การร่วมกิจกรรมแลกคูปอง KFC กับวาสลีนและซิตร้า
                            </h1>
                        </div>
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
