@extends('layouts.adminform')
@section('title')
    : User
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
    <link rel="stylesheet" href="{{ asset('assets/css/memberform.css') }}" type="text/css">
@endsection
@section('content')
    <div class="container">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.member') }}">User</a></li>
                <li class="breadcrumb-item active" aria-current="page">User Detail</li>
            </ol>
        </nav>
        <div class="row">
            <div class="col-6">
                <div class="mb-3">
                    <label for="fullname" class="form-label">User Name</label>
                    <input type="text" class="form-control-plaintext" id="fullname"
                        value="{{ $member->firstname }} {{ $member->lastname }}" readonly>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Mobile No.</label>
                    <input type="text" class="form-control-plaintext" id="phone" value="{{ $member->phone }}"
                        readonly>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control-plaintext" id="address" readonly>{{ $address->address }} {{ $address->subdistrict }} {{ $address->district }} {{ $address->province }} {{ $address->zipcode }}</textarea>
                </div>
            </div>
            <div class="col-6">
                <div class="row">
                    <div class="col-6">
                        <h2 class="upload-title">Upload History</h2>
                    </div>
                    <div class="col-6 text-end">
                        <p class="display-point">
                            Total Spend: <span class="point">{{ number_format($member->point, 2, '.', ',') }}</span>
                        </p>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="slip" class="table table-striped table-hover">
                        <thead>
                            <td class="col-3">Receipt number</td>
                            <td class="col-4">Date & Time</td>
                            <td class="col-2">Price</td>
                            <td class="col-3">Status</td>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="button" class="btn btn-outline-primary btn-sm d-flex" id="upload">
                            Upload Receipt
                        </button>
                        <input type="file" class="d-none" name="fileupload" id="fileupload" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container d-flex justify-content-end mt-3">
        <div>
            <a class="btn btn-back" href="{{ route('admin.member') }}">
                Close
            </a>
        </div>
    </div>
    <!-- Process Modal -->
    <div class="modal fade" id="processModal" tabindex="-1" aria-labelledby="Process Modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body d-flex justify-content-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/js/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap5.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        var processModal = new bootstrap.Modal(document.getElementById('processModal'), {
            backdrop: 'static'
        })

        oTable = '';
        $(document).ready(function() {
            $('#upload').on('click', function() {
                $('#fileupload').click();
            });
            $('#fileupload').on('change', function() {
                let files = $('#fileupload')[0].files;
                if (checkUploadSelectFile(files)) {
                    //check file image
                    if (!checkUploadFileType(files, 'jpg,jpeg,png,heic')) {
                        alert('กรุณาเลือกไฟล์รูปภาพ');
                        return false
                    }

                    //check file size 2 MB
                    if (!checkUploadFileSize(files, 2)) {
                        alert('ขนาดไฟล์ไม่เกิน 2 MB.');
                        return false
                    }
                    $('#upload').prop('disabled', true);
                    processModal.show();
                    setTimeout(() => {
                        sendSlipData();
                    }, 500);
                }
            });
            oTable = $('#slip').DataTable({
                ajax: {
                    url: `{{ route('admin.member.slip', ['id' => $member->id]) }}`,
                    dataType: 'json',
                    type: 'GET'
                },
                processing: true,
                serverSide: true,
                pageLength: 25,
                searching: false,
                search: {
                    return: true,
                },
                columns: [{
                        data: 'receiptno',
                        name: 'receiptno'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'total',
                        name: 'total'
                    },
                    {
                        data: 'statustext',
                        name: 'status',
                        className: "dt-center",
                    }
                ],
                order: [
                    [1, 'asc']
                ]
            });
        });

        const reloaddata = () => {
            oTable.ajax.url(`{{ route('admin.member.slip', ['id' => $member->id]) }}`).load();
        }

        const sendSlipData = () => {
            let files = $('#fileupload')[0].files;
            let fd = new FormData();
            fd.append('slip', files[0]);
            $.ajax({
                url: '{{ route('admin.member.upload', ['id' => $member->id]) }}',
                method: "POST",
                data: fd,
                async: false,
                cache: false,
                contentType: false,
                processData: false
            }).done(function(response) {
                processModal.hide();
                $('#upload').prop('disabled', false);
                reloaddata();
            }).
            fail(() => {
                processModal.hide();
                $('#upload').prop('disabled', false);
                alert('pleas contact admin.');
            });
        }
    </script>
@endsection
