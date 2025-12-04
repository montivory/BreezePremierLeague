@extends('layouts.template')
@section('title')
    : History
@endsection
@section('meta')
    <meta name="description" content="{{ config('app.webtitle') }} - History">
    <meta name="keywords" content="History">
    <meta property="og:title" content="{{ config('app.webtitle') }} - History">
    <meta property="og:description" content="{{ config('app.webtitle') }} - History">
    <meta property="og:image" content="">
    <meta property="twitter:image" content="">
@endsection
@section('stylesheet')
    <link rel="stylesheet" href="{{ asset('assets/css/history.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/css/historyitem.css') }}" type="text/css">
@endsection
@section('aascript')
    digitalData.page.category.pageType = "History page";
    digitalData.page.attributes.contentType = "Submission";
    digitalData.page.attributes.articleName = "ประวัติการส่งหลักฐาน";
@endsection
@section('content')
    <x-header url="{{ route('member') }}" />
    <div class="content-block">
        <div>
            <h1 class="title">ประวัติการส่งใบเสร็จ</h1>
        </div>
        @if (sizeof($transactions) == 0)
            <div class="no-transaction">
                <div>
                    <img src="{{ asset('assets/images/no-history.svg') }}" class="no-history-photo">
                    <h2 class="nohistory-title">
                        ยังไม่มีประวัติ
                    </h2>
                    <p class="nohistory-detail">
                        ประวัติการส่งใบเสร็จของคุณจะแสดงที่นี่
                    </p>
                </div>
            </div>
        @else
            <div>
                <nav class="nav nav-pills flex-row text-center">
                    <a class="nav-link col-4 active" data-bs-toggle="tab" data-bs-target="#nav-all"
                        href="javascript:void(0);" eventLabel="all">@lang('history.taball')</a>
                    <a class="nav-link col-4" data-bs-toggle="tab" data-bs-target="#nav-pending" href="javascript:void(0);"
                        eventLabel="pending">@lang('history.tabpending')</a>
                    <a class="nav-link col-4" data-bs-toggle="tab" data-bs-target="#nav-reject" href="javascript:void(0);"
                        eventLabel="rejected">@lang('history.tabreject')</a>
                </nav>
            </div>
            @php
                $limititem = 10;
            @endphp
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-all" role="tabpanel" aria-labelledby="nav-all-tab">
                    <div class="history-item" id="all-data">
                        @foreach ($transactions as $transaction)
                            @if ($loop->index >= $limititem)
                                @break
                            @endif
                            <x-history-item :item="$transaction" :resend="true" />
                        @endforeach
                    </div>
                    @if (sizeof($transactions) > $limititem)
                        <div class="justify-content-center d-flex">
                            <a href="javascript:void(0);" class="btn more-link more-all-link" data="all"
                                status="all">@lang('history.morebutton')</a>
                        </div>
                    @endif
                </div>
                <div class="tab-pane fade" id="nav-pending" role="tabpanel" aria-labelledby="nav-pending-tab">
                    <div class="history-item" id="process-data">
                        @foreach ($slipPendings as $slipPending)
                            @if ($loop->index >= $limititem)
                                @break
                            @endif
                            <x-history-item :item="$slipPending" />
                        @endforeach
                    </div>
                    @if (sizeof($slipPendings) > $limititem)
                        <div class="justify-content-center d-flex">
                            <a href="javascript:void(0);" class="btn more-link more-process-link" data="slip"
                                status="process">@lang('history.morebutton')</a>
                        </div>
                    @endif
                </div>
                <div class="tab-pane fade" id="nav-reject" role="tabpanel" aria-labelledby="nav-reject-tab">
                    <div class="history-item" id="reject-data">
                        @foreach ($slipRejects as $slipReject)
                            @if ($loop->index >= $limititem)
                                @break
                            @endif
                            <x-history-item :item="$slipReject" :resend="true" />
                        @endforeach
                    </div>
                    @if (sizeof($slipRejects) > $limititem)
                        <div class="justify-content-center d-flex">
                            <a href="javascript:void(0);" class="btn more-link more-reject-link" data="slip"
                                status="reject">@lang('history.morebutton')</a>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        let allpage = 2;
        let pendingpage = 2;
        let rejectpage = 2;

        $(function() {
            $('.more-link').on('click', function() {
                loadData($(this).attr('data'), $(this).attr('status'));
            });
            $('.nav-link').on('click', function() {
                @if (config('app.env') == 'production')
                    if (typeof ctConstants !== "undefined") {
                        let eventLabel = $(this).attr('eventLabel');
                        var ev2 = {};
                        ev2.eventInfo = {
                            'type': ctConstants.trackEvent,
                            'eventAction': ctConstants.linkClick,
                            'eventLabel': eventLabel,
                            'eventValue': 1
                        };
                        ev2.category = {
                            'primaryCategory': ctConstants.custom
                        };
                        digitalData.event.push(ev2);
                    }
                @endif
            });
        });

        const loadData = (type, statusitem) => {
            let page = 0;

            switch (type) {
                case 'all':
                    page = allpage;
                    break;
                default:
                    if (statusitem === 'process') {
                        page = pendingpage;
                    } else {
                        page = rejectpage;
                    }
                    break;
            }

            $.ajax({
                url: `{{ route('loadhistory') }}?type=${type}&page=${page}&status=${statusitem}`,
                method: "GET",
                success: function(result) {
                    if (type === 'all') {
                        if (result.next) {
                            allpage++;
                        } else {
                            $('.more-all-link').addClass('disabled');
                        }
                    } else {
                        if (statusitem === 'process') {
                            if (result.next) {
                                pendingpage++;
                            } else {
                                $('.more-process-link').addClass('disabled');
                            }
                        } else {
                            if (result.next) {
                                rejectpage++;
                            } else {
                                $('.more-reject-link').addClass('disabled');
                            }
                        }
                    }

                    $.each(result.datas, function(i, v) {
                        let html = createItem(v);

                        if (type === 'all') {
                            $('#all-data').append(html);
                            if (v.type === 'slip' && v.status === 'reject') {
                                const elem = document.getElementById(`all-${v.id}`);
                                if (elem) {
                                    new bootstrap.Tooltip(elem);
                                }
                            }
                        } else {
                            if (statusitem === 'process') {
                                $('#process-data').append(html);
                            } else {
                                $('#reject-data').append(html);
                                if (v.type === 'slip' && v.status === 'reject') {
                                    const elem = document.getElementById(`slip-${v.id}`);
                                    if (elem) {
                                        new bootstrap.Tooltip(elem);
                                    }
                                }
                            }
                        }
                    });
                }
            });
        }

        const createItem = (data) => {
            let rightSection = '';
            let rejectreason = '';
            switch (data.rejectreason) {
                case 'used':
                    $rejectreason = 'ใบเสร็จถูกใช้ไปแล้ว';
                    break;
                case 'unclear':
                    $rejectreason = 'ใบเสร็จไม่ชัดเจน';
                    break;
                case 'not_found':
                    $rejectreason = 'ไม่พบใบเสร็จ';
                    break;
                case 'not_participating':
                    $rejectreason = 'ใบเสร็จนี้มาจากร้านค้าที่ไม่ร่วมรายการ';
                    break;
                default:
                    $rejectreason = data.rejectreason;
                    break;
            }
            let detail = data.system ? 'อัปโหลดโดยแอดมิน' : data.receiptno;
            if (data.status === 'reject') {
                rightSection = `
                    <div class="d-flex align-items-center justify-content-end item-point flex-fill">
                        <a href="javascript:void(0);" 
                        id="slip-${data.id}"
                        class="reject-link" 
                        data-bs-toggle="tooltip" 
                        title="${$rejectreason}">
                            @lang('historyitem.reject')
                        </a>
                        <a href="${data.resend_url}" class="resend-link">ส่งใหม่</a>
                    </div>
                `;
            } else if (data.status === 'process') {
                rightSection = `
                    <div class="process">
                        <span class="time-icon"></span> กำลังตรวจสอบ
                    </div>
                `;
            } else {
                rightSection = `
                    <div class="price">
                        ${data.sliptotal} บาท
                    </div>
                `;
            }
            return `
                <div class="items d-flex flex-wrap align-items-center justify-content-between">
                    <div class="flex-column">
                        <div class="datetime">${data.created_at}</div>
                        <div class="item-detail">
                            ${detail}
                        </div>
                        ${data.system ? '' : '<div class="merchantname">' + data.merchantname + '</div>'}
                    </div>
                    ${rightSection}
                </div>`;
        };
    </script>
@endsection
