@extends('layouts.adminform')
@section('title')
    : Receipt Verification
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
@endsection
@section('content')
    <div class="container">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.slip') }}">Receipt</a></li>
                <li class="breadcrumb-item active" aria-current="page">Receipt Verification</li>
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
                <div class="row mb-4">
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
                            class="form-control @if ($systemFlag != '') is-invalid border border-danger @endif" />
                        <div class="invalid-feedback" id="receiptno-feedback">
                            {{ $systemFlag }}
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="total" class="col-form-label col-3">Total Spend : </label>
                    <div class="col-9">
                        <input type="text" name="total" id="total" value="{{ $slip->total }}"
                            class="form-control" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row summary">
        <div class="container">
            <div class="row justify-content-end">
                <div class="col-3 summary-item">
                    <span>Total Price : <span class="total-price">0</span></span>
                    {{-- <span>Selected Item(s): <span class="item-select">0</span></span> --}}
                </div>
                <div class="col-4 summary-item">
                    {{-- <span>Total Price : <span class="total-price">0</span></span>
                    <span class="right-gray-icon"></span>
                    <span>Earn Point(s) : <span class="point">0</span></span> --}}
                </div>
            </div>
        </div>
    </div>
    <div class="container d-flex justify-content-end mt-3 mb-5">
        <div class="me-3">
            <a class="btn btn-approve" id="activeApproveModal">
                APPROVE
            </a>
        </div>
        <div class="me-3">
            <a class="btn btn-reject" id="activeRejectModal">
                REJECT
            </a>
        </div>
        <div>
            <a class="btn btn-back" href="{{ route('admin.slip') }}">
                Close
            </a>
        </div>
    </div>
    {{-- approve modal --}}
    <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body d-flex justify-content-center align-items-center">
                    <div class="d-flex flex-column">
                        <div>
                            <h2 class="approve-title">Confirm Action</h2>
                        </div>
                        <div>
                            <p class="approve-detail">Are you sure you want confirm and send <span class="text-green"
                                    id="approvePoint">0</span> points
                                to user ? you cannot undo this action</p>
                        </div>
                        <div class="d-flex justify-content-center">
                            <a class="btn btn-approve me-2" id="approveSlip">
                                APPROVE
                            </a>
                            <a class="btn btn-cancelmodal ms-2" data-bs-dismiss="modal">
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- reject modal --}}
    <div class="modal fade" id="rejectModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="rejectModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body d-flex justify-content-center align-items-center">
                    <div class="d-flex flex-column">
                        <div>
                            <h2 class="approve-title">Confirm Action</h2>
                        </div>
                        <div>
                            <p class="approve-detail">Are you sure you want to reject this receipt ?</p>
                            <p class="approve-detail">Select reason:</p>
                        </div>
                        <div class="mb-3">
                            <ul class="list-group">
                                <li class="list-group-item active" aria-current="true" data-value="used">
                                    ใบเสร็จถูกใช้ไปแล้ว</li>
                                <li class="list-group-item" data-value="unclear">ใบเสร็จไม่ชัดเจน</li>
                                <li class="list-group-item" data-value="not_found">ไม่พบใบเสร็จ</li>
                                <li class="list-group-item" data-value="not_participating">
                                    ใบเสร็จนี้มาจากร้านค้าที่ไม่ร่วมรายการ</li>
                            </ul>
                        </div>
                        <div class="row mb-3">
                            <label for="rejectreason" class="col-3 col-form-label">Other</label>
                            <div class="col-9">
                                <input type="text" name="rejectreason" id="rejectreason" class="form-control border">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a class="btn btn-reject" id="rejectSlip">
                                REJECT
                            </a>
                            <a class="btn btn-cancelmodal" data-bs-dismiss="modal">
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
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
    <script type="text/javascript">
        const slipId = '{{ $slip->id }}';
        const approveModal = new bootstrap.Modal(document.getElementById('approveModal'), {
            backdrop: 'static'
        });
        const rejectModal = new bootstrap.Modal(document.getElementById('rejectModal'), {
            backdrop: 'static'
        });
        let totalPrice = 0;
        $(function() {
            bindTableAction();
            $('#activeApproveModal').on('click', function() {
                let totalVal = $('#total').val().trim();
                let receiptno = $('#receiptno').val().trim();
                if (receiptno === '') {
                    alert('กรุณากรอกเลขที่ใบเสร็จ');
                    $('#receiptno').addClass('is-invalid');
                    return false;
                }
                if (totalVal === '' || isNaN(totalVal)) {
                    alert('กรุณากรอกยอด Total Spend ให้ถูกต้อง');
                    $('#total').addClass('is-invalid');
                    return false;
                }
                $('#approvePoint').text(totalVal);
                if (!checkReceipt()) {
                    alert('Duplicate receipt no.');
                    return false;
                }
                approveModal.toggle();
            });
            $('#approveSlip').on('click', function() {
                $(this).addClass('disabled');
                setTimeout(() => {
                    storeData();
                }, 500);
            });
            $('#activeRejectModal').on('click', function() {
                rejectModal.toggle();
            });
            $('#rejectSlip').on('click', function() {
                let reason = '';
                if ($('.list-group-item.active').length > 0) {
                    reason = $('.list-group-item.active').text();
                }
                if (!isBlank($('#rejectreason').val())) {
                    reason += ` ${$('#rejectreason').val()}`;
                }
                if (reason.trim() == '') {
                    alert('โปรดเลือกสาเหตุ');
                    return false;
                }
                $(this).addClass('disabled');
                setTimeout(() => {
                    rejectData();
                }, 500);
            });
            $('#total').on('keyup change', function() {
                let val = $(this).val().trim();
                if (val === '' || isNaN(val)) {
                    $(this).addClass('is-invalid');
                    $('.total-price').text(0);
                    totalPrice = 0;
                    return;
                }
                $(this).removeClass('is-invalid');
                let num = parseFloat(val);
                $('.total-price').text(num.toLocaleString());
                totalPrice = num;
            });
            let initTotal = $('#total').val().trim();
            if (!isNaN(initTotal) && initTotal !== '') {
                $('.total-price').text(parseFloat(initTotal).toLocaleString());
                totalPrice = parseFloat(initTotal);
            } else {
                $('.total-price').text(0);
                totalPrice = 0;
            }
        });

        const bindTableAction = () => {
            $('.list-group-item').unbind('click');
            $('.list-group-item').on('click', function() {
                if ($(this).hasClass('active')) {
                    $(this).removeClass('active');
                } else {
                    $('.list-group-item').removeClass('active');
                    $(this).addClass('active');
                }
            });
        }

        const checkReceipt = () => {
            let result = true;
            let formd = new FormData();
            formd.append('slip', slipId);
            formd.append('receiptno', $('#receiptno').val());
            $.ajax({
                url: '{{ route('admin.search.slip') }}',
                method: "POST",
                data: formd,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
            }).done(function(response) {
                result = response.result;
            })
            return result;
        }

        const storeData = () => {
            let fd = new FormData();
            fd.append('slip', slipId);
            fd.append('receiptno', $('#receiptno').val());
            fd.append('totalPrice', totalPrice);
            $.ajax({
                url: '{{ route('admin.slip.store') }}',
                method: "POST",
                data: fd,
                async: false,
                cache: false,
                contentType: false,
                processData: false
            }).done(function(response) {
                if (response) {
                    alert('Approve complete.');
                    window.location.href = '{{ route('admin.slip') }}';
                } else {
                    alert('Duplicate receipt no.');
                    $('#approveSlip').removeClass('disabled');
                }
            });
        }

        const rejectData = () => {
            let reason = '';
            if ($('.list-group-item.active').length > 0) {
                reason = $('.list-group-item.active').data('value');
            }
            if (!isBlank($('#rejectreason').val())) {
                reason = ` ${$('#rejectreason').val()}`;
            }
            let fd = new FormData();
            fd.append('slip', slipId);
            fd.append('rejectreason', reason.trim());
            $.ajax({
                url: '{{ route('admin.slip.reject') }}',
                method: "POST",
                data: fd,
                async: false,
                cache: false,
                contentType: false,
                processData: false
            }).done(function(response) {
                alert('Reject complete.');
                window.location.href = '{{ route('admin.slip') }}';
            })
        }
    </script>
@endsection
