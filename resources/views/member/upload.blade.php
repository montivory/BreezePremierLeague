@extends('layouts.template')
@section('title')
    : Upload
@endsection
@section('meta')
    <meta name="description" content="{{ config('app.webtitle') }} - Upload">
    <meta name="keywords" content="Upload">
    <meta property="og:title" content="{{ config('app.webtitle') }} - Upload">
    <meta property="og:description" content="{{ config('app.webtitle') }} - Upload">
    <meta property="og:image" content="">
    <meta property="twitter:image" content="">
@endsection
@section('stylesheet')
    <link rel="stylesheet" href="{{ asset('assets/css/upload.css') }}" type="text/css">
@endsection
@section('aascript')
    digitalData.page.category.pageType = "Upload page";
    digitalData.page.attributes.contentType = "Submission";
    digitalData.page.attributes.articleName = "ส่งใบเสร็จ";
@endsection
@section('content')
    <x-header />
    <div class="content-block">
        <div class="body-block">
            <div class="col-12" id="upload-form">
                <div class="d-flex flex-column">
                    <div>
                        <h2 class="upload-title">
                            ส่งหลักฐานการชำระเงิน
                        </h2>
                    </div>
                    <form id="uploadForm">
                        <div class="mb-3">
                            <label class="form-label">Order ID*</label>
                            <input type="text" class="form-control validate" name="order_id" id="order_id"
                                placeholder="00000000000000">
                            <div class="invalid-feedback">
                                กรุณากรอกรหัส Order ID
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">ยอดสินค้าที่ร่วมรายการ*</label>
                            <input type="text" class="form-control validate" name="amount" id="amount"
                                placeholder="0.00">
                            <div class="invalid-feedback">
                                กรุณากรอกยอดสินค้า
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="merchant_name" class="form-label">ช่องทางการซื้อสินค้า</label>
                            <select class="form-select" id="merchant_name" name="merchant_name">
                                <option selected>กรุณาเลือก</option>
                                <option value="1262">Lazada</option>
                                <option value="1263">Shopee</option>
                                <option value="3657">TikTok</option>
                            </select>
                        </div>

                        <!-- Upload Button -->
                        <div class="d-grid gap-2 lucky-link mb-3" id="firstUpload">
                            <label for="firstname" class="form-label">หลักฐานการสั่งซื้อ*</label>
                            <a class="btn btn-upload" id="btnUpload" eventLabel="send receipt">
                                <div class="d-flex align-items-center justify-content-center">
                                    <span class="add-icon"></span>
                                    อัปโหลดใบเสร็จ
                                </div>
                            </a>
                            <input type="file" id="slip" name="slip" class="d-none"
                                accept=".png, .jpg, .jpeg, .heic" />
                        </div>
                        <!-- พื้นที่แสดงรูป preview -->
                        <div id="slippreview" class="mt-3"></div>
                        <div class="upload-helper">เฉพาะไฟล์ JPG, PNG หรือ HEIC ขนาดไม่เกิน 2 MB</div>
                        <div class="upload-instructions">
                            <a href="javascript:void(0);" link="{{ route('instructions') }}"
                                class="instructions-link analytic-link"
                                eventLabel="upload instruction-{{ route('instructions') }}">
                                @lang('upload.moreupload')
                                <span class="instructions-icon"></span>
                            </a>
                        </div>
                        <button id="btn-submit" type="button" class="btn btn-main w-100" disabled eventLabel="submit">
                            ส่งข้อมูล
                        </button>
                    </form>
                </div>
                <x-member-menu />
            </div>
            <div class="col-12" style="display:none;" id="upload-success">
                <div class="upload-success-section">
                    <div class="d-flex flex-column col-12">
                        <div>
                            <img src="{{ asset('assets/images/upload/success.svg') }}"
                                class="d-block mx-auto upload-complete-thumbnail" />
                        </div>
                        <div>
                            <h1 class="upload-complete-title">อัปโหลดเสร็จสิ้น</h1>
                        </div>
                        <div>
                            <p class="upload-complete-detail">
                                ได้รับใบเสร็จของท่านเรียบร้อยแล้ว<br>แอดมินจะทำการตรวจสอบภายใน 48 ชม.<br>(เวลาทำการ
                                จ. - ศ. เวลา 9.00 – 17.00 น.)
                            </p>
                        </div>
                    </div>
                    <div class="col-12 d-flex flex-column">
                        <div class="d-grid gap-2 lucky-link send-more-receipt">
                            <a href="javascript:void(0);" link="{{ route('upload') }}" class="btn btn-main analytic-link"
                                eventLabel="upload more-{{ route('upload') }}">
                                ส่งเพิ่ม
                            </a>
                        </div>
                        <div class="text-center">
                            <a href="javascript:void(0);" link="{{ route('member') }}" class="analytic-link tohome"
                                eventLabel="back to home page-{{ route('member') }}">
                                กลับหน้าแรก
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12" style="display:none;" id="upload-fail">
                <div class="upload-fail-section">
                    <div>
                        <img src="{{ asset('assets/images/upload/fail.svg') }}"
                            class="d-block mx-auto upload-fail-thumbnail" />
                    </div>
                    <div>
                        <h2 class="fail-title">อัปโหลดไม่สำเร็จ</h2>
                        <p class="fail-description">
                            สอบถามผ่านช่องทางข้อความของเพจ<br>
                            Facebook : Breeze Thailand<br>
                            ในวันจันทร์ ถึง ศุกร์ และในเวลาทำการ 10.00 น. ถึง 19.00 น
                        </p>
                    </div>
                    <div class="col-12 d-flex flex-column">
                        <div class="d-grid gap-2 lucky-link send-more-receipt">
                            <a href="javascript:void(0);" link="{{ route('upload') }}"
                                class="btn btn-main analytic-link" eventLabel="upload again-{{ route('upload') }}">
                                ลองอีกครั้ง
                            </a>
                        </div>
                        <div class="text-center">
                            <a href="javascript:void(0);" link="{{ route('member') }}" class="analytic-link tohome"
                                eventLabel="back to home page-{{ route('member') }}">
                                กลับหน้าแรก
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        $(function() {
            $('.btn-upload').on('click', function() {
                $('#slip').click();
            });

            $('#merchant_name').on('change', checkFormReady);

            function checkFormReady() {
                const orderId = $('#order_id').val().trim();
                const amount = $('#amount').val().trim();
                const slipFile = $('#slip')[0].files.length > 0;
                const merchant = $('#merchant_name').val();

                if (orderId !== '' && amount !== '' && !isNaN(amount) && slipFile && merchant !== 'กรุณาเลือก') {
                    $('#btn-submit').prop('disabled', false);
                } else {
                    $('#btn-submit').prop('disabled', true);
                }
            }

            $('#order_id, #amount').on('keyup change', function() {
                checkFormReady();
                if ($(this).attr('id') === 'amount') {
                    const val = $(this).val().trim();
                    if (val !== '' && isNaN(val)) {
                        $(this).addClass('is-invalid');
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                }
            });

            $('#slip').on('change', function(e) {
                let file = $('#slip')[0].files;

                if (!checkUploadSelectFile(file)) {
                    alert('กรุณาเลือกรูปภาพอย่างน้อย 1 รูป');
                    return false;
                }

                if (!checkUploadFileType(file, 'jpg,jpeg,png,heic')) {
                    alert('กรุณาเลือกไฟล์รูปภาพ');
                    return false;
                }

                if (!checkUploadFileSize(file, 2)) {
                    alert('ขนาดไฟล์ไม่เกิน 2 MB');
                    return false;
                }

                file = e.target.files[0];

                setTimeout(() => {
                    selectedFile = file;
                    showPreview(file);
                    checkFormReady();
                }, 300);
            });

            $(document).on('click', '.remove-img', function() {
                selectedFile = null;
                $('#slippreview').empty();
                $('#slip').val('');
                $('#firstUpload').removeClass('d-none');
                $('#confirmWrap').addClass('d-none');
                checkFormReady();
            });

            $('#btn-submit').on('click', function() {
                let isValid = true;
                const orderId = $('#order_id').val().trim();
                const amount = $('#amount').val().trim();
                const slipFile = $('#slip')[0].files[0];
                const merchant = $('#merchant_name').val();

                $('#order_id, #amount').removeClass('is-invalid');

                if (orderId === '') {
                    $('#order_id').addClass('is-invalid');
                    isValid = false;
                }

                if (amount === '' || isNaN(amount)) {
                    $('#amount').addClass('is-invalid');
                    isValid = false;
                }

                if (merchant === 'กรุณาเลือก') {
                    $('#merchant_name').addClass('is-invalid');
                    isValid = false;
                }

                if (!slipFile) {
                    alert('กรุณาอัปโหลดใบเสร็จ');
                    isValid = false;
                }

                if (!isValid) return;
                $('#btn-submit').html(`<span class="spinner-border me-3"></span>`);
                @if (config('app.env') == 'production')
                    if (typeof ctConstants !== "undefined") {
                        let eventLabel = $(this).attr('eventLabel');
                        var ev2 = {};
                        ev2.eventInfo = {
                            'type': ctConstants.trackEvent,
                            'eventAction': ctConstants.linkClick,
                            'eventLabel': `Submit receipt-{{ route('upload') }}#submit`,
                            'eventValue': 1
                        };
                        ev2.category = {
                            'primaryCategory': ctConstants.custom
                        };
                        digitalData.event.push(ev2);
                    }
                @endif
                sendImageData(orderId, amount, slipFile, merchant);
            });

            function showPreview(file) {
                const winUrl = URL.createObjectURL(file);
                const enlargeUrl = `{{ route('enlarge') }}?url=${winUrl}`;

                const html = `
                <div class="preview-wrapper position-relative">
                    <a href="${enlargeUrl}" target="_blank">
                        <img src="${winUrl}" class="preview-img" />
                    </a>
                    <button class="btn btn-sm btn-light remove-img position-absolute"
                        style="top: 6px; right: 6px; border-radius: 50%; padding: 3px 6px; font-size:14px;">✕</button>
                </div>
            `;

                $('#slippreview').html(html);
                $('#firstUpload').addClass('d-none');
                $('#confirmWrap').removeClass('d-none');
            }

        });

        const sendImageData = (orderId, amount, slipFile, merchant) => {
            let form = new FormData();
            form.append("order_id", orderId);
            form.append("amount", amount);
            form.append("slip", slipFile);
            form.append("merchant_name", merchant);
            form.append("_token", "{{ csrf_token() }}");

            setTimeout(() => {
                $.ajax({
                        url: "{{ route('storeupload') }}",
                        method: "POST",
                        data: form,
                        async: false,
                        cache: false,
                        contentType: false,
                        processData: false,
                    })
                    .done(function(res) {
                        $('#upload-form').fadeOut(() => {
                            $('#upload-success').fadeIn();
                        });
                        $('#btn-submit').html(`ส่งข้อมูล`);
                    })
                    .fail(function(err) {
                        $('#upload-form').fadeOut(() => {
                            $('#upload-fail').fadeIn();
                        });
                        $('#btn-submit').html(`ส่งข้อมูล`);
                    });
            }, 1000);
        }
    </script>
@endsection
