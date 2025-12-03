@extends('layouts.template')
@section('title')
    : Instructions
@endsection
@section('meta')
    <meta name="description" content="{{ config('app.webtitle') }} - Instructions">
    <meta name="keywords" content="Instructions">
    <meta property="og:title" content="{{ config('app.webtitle') }} - Instructions">
    <meta property="og:description" content="{{ config('app.webtitle') }} - Instructions">
    <meta property="og:image" content="">
    <meta property="twitter:image" content="">
@endsection
@section('stylesheet')
    <link rel="stylesheet" href="{{ asset('assets/css/instructions.css') }}" type="text/css">
@endsection
@section('aascript')
    digitalData.page.category.pageType = "Upload instruction page";
    digitalData.page.attributes.contentType = "Submission";
    digitalData.page.attributes.articleName = "คำแนะนำการอัพโหลด";
@endsection
@section('content')
    <x-header url="{{ route('upload') }}" />
    <div class="content-block">
        <div>
            <h1 class="title">คำแนะนำในการอัปโหลด</h1>
        </div>
        <div>
            <nav class="nav nav-pills flex-row text-center">
                <a class="nav-link col-4 active" data-bs-toggle="tab" data-bs-target="#nav-shopee"
                    href="javascript:void(0);" eventLabel="shopee">Shopee</a>
                <a class="nav-link col-4" data-bs-toggle="tab" data-bs-target="#nav-lazada" href="javascript:void(0);"
                    eventLabel="lazada">Lazada</a>
                <a class="nav-link col-4" data-bs-toggle="tab" data-bs-target="#nav-tiktok" href="javascript:void(0);"
                    eventLabel="tiktok">Tiktok</a>
            </nav>
        </div>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-shopee" role="tabpanel" aria-labelledby="nav-shopee-tab">
                <div class="d-flex flex-column mt-4 px-4 text-center">
                    <div class="instructions-title">การอัปโหลดหลักฐานจาก Shopee</div>
                    <div class="my-4">
                        <img class="img-fluid w-100" src="{{ asset('assets/images/instructions/shopee1.jpg') }}"
                            alt="Shopee1">
                    </div>
                    <div class="instructions-detail">เข้าไปที่หน้าโปรไฟล์ แล้วเลือก “ที่ต้องได้รับ”</div>
                    <div class="my-4">
                        <img class="img-fluid w-100" src="{{ asset('assets/images/instructions/shopee2.jpg') }}"
                            alt="Shopee2">
                    </div>
                    <div class="instructions-detail">เลือก “สำเร็จ” และเลือกคำสั่งซื้อที่ซื้อสินค้าร่วมรายการ</div>
                    <div class="my-4">
                        <img class="img-fluid w-100" src="{{ asset('assets/images/instructions/shopee3.jpg') }}"
                            alt="Shopee3">
                    </div>
                    <div class="instructions-detail">แคปหน้าจอในส่วนนี้ ให้เห็นหมายเลขคำสั่งซื้อ และยอดรวมสินค้าชัดเจน</div>
                </div>
            </div>
            <div class="tab-pane fade" id="nav-lazada" role="tabpanel" aria-labelledby="nav-lazada-tab">
                <div class="d-flex flex-column mt-4 px-4 text-center">
                    <div class="instructions-title">การอัปโหลดหลักฐานจาก Lazada</div>
                    <div class="my-4">
                        <img class="img-fluid w-100" src="{{ asset('assets/images/instructions/lazada1.jpg') }}"
                            alt="Lazada1">
                    </div>
                    <div class="instructions-detail">เข้าไปที่หน้าโปรไฟล์ แล้วเลือก “ดูรายการสั่งซื้อทั้งหมด”</div>
                    <div class="my-4">
                        <img class="img-fluid w-100" src="{{ asset('assets/images/instructions/lazada2.jpg') }}"
                            alt="Lazada2">
                    </div>
                    <div class="instructions-detail">เลือกคำสั่งซื้อที่ซื้อสินค้าที่ร่วมรายการ เพื่อดูรายละเอียดคำสั่งซื้อ
                        แคปหน้าจอในส่วนนี้ ให้เห็นหมายเลขคำสั่งซื้อ และยอดรวมสินค้าชัดเจน</div>
                </div>
            </div>
            <div class="tab-pane fade" id="nav-tiktok" role="tabpanel" aria-labelledby="nav-tiktok-tab">
                <div class="d-flex flex-column mt-4 px-4 text-center">
                    <div class="instructions-title">การอัปโหลดหลักฐานจาก Tiktok Shop</div>
                    <div class="my-4">
                        <img class="img-fluid w-100" src="{{ asset('assets/images/instructions/tiktok1.jpg') }}"
                            alt="Tiktok1">
                    </div>
                    <div class="instructions-detail">เข้าไปที่หน้าโปรไฟล์ แล้วเลือก “Order”</div>
                    <div class="my-4">
                        <img class="img-fluid w-100" src="{{ asset('assets/images/instructions/tiktok2.jpg') }}"
                            alt="Tiktok2">
                    </div>
                    <div class="instructions-detail">เลือกคำสั่งซื้อที่ซื้อสินค้าที่ร่วมรายการ เพื่อดูรายละเอียดคำสั่งซื้อ
                        แคปหน้าจอในส่วนนี้ ให้เห็นหมายเลขคำสั่งซื้อ และยอดรวมสินค้าชัดเจน</div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript"></script>
@endsection
