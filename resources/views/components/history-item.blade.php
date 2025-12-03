@php
    $rejectreason = '';
    switch ($item->rejectreason) {
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
            $rejectreason = $item->rejectreason;
            break;
    }
    $datetime = date('d/m/Y H:i', strtotime($item->created_at));
    $detail = $item->system ? 'อัปโหลดโดยแอดมิน' : $item->receiptno;
@endphp
<div class="items d-flex flex-wrap align-items-center justify-content-between">
    <div class="flex-column">
        <div class="datetime">{{ $datetime }}</div>
        <div class="item-detail">
            {{ $detail }}
        </div>
        @if (!$item->system)
            <div class="merchantname">{{ $item->merchantname }}</div>
        @endif
    </div>
    @if ($item->status == 'reject')
        <div class="d-flex align-items-center justify-content-end item-point flex-fill">
            <a href="javascript:void(0);" class="reject-link" data-bs-toggle="tooltip" title="{{ $rejectreason }}">
                @lang('historyitem.reject')
            </a>
            <a href="{{ route('upload') }}" class="resend-link">@lang('historyitem.resend')</a>
        </div>
    @elseif ($item->status == 'process')
        <div class="process">
            <span class="time-icon"></span> กำลังตรวจสอบ
        </div>
    @else
        <div class="price">
            {{ $item->slippoints }} บาท
        </div>
    @endif
</div>
