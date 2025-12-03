@extends('layouts.adminform')
@section('title')
    : Prize {{ $reward->nameen }}
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
    <link rel="stylesheet" href="{{ asset('assets/css/prize.css') }}" type="text/css">
@endsection
@section('content')
    <div class="container">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.prize') }}">Prize Management</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $reward->nameen }}</li>
            </ol>
        </nav>
    </div>
    <div class="container flex-column">
        <div class="d-flex flex-row">
            <div class="me-5">
                <span class="fw-bold">Reward</span><br>
                {{ $reward->nameen }}
            </div>
            <div class="me-5">
                <span class="fw-bold">Remaining</span><br>
                {{ $reward->amount }}/{{ $reward->quantity }}
            </div>
            <div>
                <a href="{{ route('admin.prize.export', ['rewardid' => $reward->id]) }}" class="btn btn-outline-primary btn-export me-3">Download User</a>
            </div>
            <div>
                <a href="javascript:void(0);" class="btn btn-outline-secondary btn-import">Import Update</a>
                <input type="file" multiple="false" name="csvimport" id="csvimport" class="d-none" />
            </div>
        </div>
        <div class="error-report">

        </div>
        <div class="table-responsive">
            <h2 class="mt-5 mb-3">Redemption list</h2>
            <table class="table table-striped">
                <thead>
                    <td class="col-1">No.</td>
                    <td class="col-3">Name</td>
                    <td class="col-2">Mobile No.</td>
                    <td class="col-2">Status</td>
                    <td class="col-2">Tracking No.</td>
                </thead>
                <tbody id="member-body">
                    @foreach ($members as $member)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>
                                <a href="{{ route('admin.member.view', ['id' => $member->memberId]) }}">{{ $member->firstname }}
                                    {{ $member->lastname }}</a>
                                <input type="hidden" name="redeem" value="{{ $member->id }}" />
                            </td>
                            <td>
                                {{ $member->phone }}
                            </td>
                            <td class="pe-3">
                                <select class="form-select" name="shipping" aria-label="shipping">
                                    <option @if (!$member->shipping) selected @endif value="0">Wait for
                                        shipping</option>
                                    <option @if ($member->shipping) selected @endif value="1">Send to user
                                    </option>
                                </select>
                                <input type="hidden" name="currentshipping" value="{{ $member->shipping }}" />
                            </td>
                            <td>
                                <input type="text" name="trackno" class="form-control" value="{{ $member->trackno }}" />
                                <input type="hidden" name="currenttrackno" value="{{ $member->trackno }}" />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="container d-flex mt-auto pb-3">
        <div class="text-end col-12">
            <a class="btn btn-save me-3">
                Save
            </a>
            <a class="btn btn-back" href="{{ route('admin.prize') }}">
                Close
            </a>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="processModal" tabindex="-1" aria-labelledby="processModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="d-flex justify-content-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        var processModal = new bootstrap.Modal(document.getElementById('processModal'), {
            backdrop: 'static'
        });

        $(function() {
            $('.btn-save').on('click', function() {
                let updates = [];
                $.each($('#member-body tr'), function(i, v) {
                    let shipping = $(v).find('select[name=shipping]').val();
                    let currentshipping = $(v).find('input[name=currentshipping]').val();
                    let trackno = $(v).find('input[name=trackno]').val();
                    let currenttrackno = $(v).find('input[name=currenttrackno]').val();
                    if ((shipping != currentshipping) || (trackno != currenttrackno)) {
                        let memberObj = {};
                        memberObj.redeem = $(v).find('input[name=redeem]').val();
                        memberObj.shipping = shipping;
                        memberObj.trackno = trackno;
                        updates.push(memberObj);
                    }
                });
                if (updates.length > 0) {
                    $.ajax({
                        url: "{{ route('admin.prize.store') }}",
                        method: "POST",
                        data: {
                            datas: updates
                        }
                    }).done(() => {
                        window.location.reload();
                    })
                }
            });
            $('.btn-import').on('click', function() {
                $('#csvimport').click();
            });
            $('#csvimport').on('change', function() {
                let files = $('#csvimport')[0].files;
                if (checkUploadSelectFile(files)) {
                    $('.error-report').addClass('d-none');
                    //check file image
                    if (!checkUploadFileType(files, 'csv')) {
                        $('.error-report').removeClass('d-none');
                        $('.error-report').html('กรุณาเลือกไฟล์ csv');
                        return false
                    }
                    //check file size 2 MB
                    if (!checkUploadFileSize(files, 2)) {
                        $('.error-report').removeClass('d-none');
                        $('.error-report').html('ขนาดไฟล์ไม่เกิน 2 MB.');
                        return false
                    }
                    processModal.show();
                    setTimeout(() => {
                        uploadCsv();
                    }, 500);
                }
            });
        });

        const uploadCsv = () => {
            let fd = new FormData();
            if (isBlank($('#csvimport').val())) {
                return false;
            }
            let files = $('#csvimport')[0].files;
            fd.append('importfile', files[0]);
            $.ajax({
                url: '{{ route('admin.prize.import', ['rewardid' => $reward->id]) }}',
                method: "POST",
                data: fd,
                contentType: false,
                processData: false
            }).done(function(response) {
                if (response.result) {
                    window.location.reload();
                } else {
                    processModal.hide();
                    alert(response.message);
                }
            }).fail((response) => {
                processModal.hide();
                alert(response.message);
            })
        }
    </script>
@endsection
