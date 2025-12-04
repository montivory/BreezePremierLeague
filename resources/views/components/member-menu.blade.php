<div class="fixed-bottom col-md-4 col-12 mx-auto menu-section">
    <div class="member-menu d-flex">
        <div class="col-4 d-flex menu-items">
            <a href="javascript:void(0);" link="{{ route('member') }}"
                class="d-flex justify-content-center align-items-center @if (Route::currentRouteName() === 'member') active @endif analytic-link"
                eventLabel="home-{{ route('member') }}">
                <div class="d-flex flex-column">
                    <img src="@if (Route::currentRouteName() === 'member') {{ asset('assets/images/menu/home-active.svg') }} @else {{ asset('assets/images/menu/home-inactive.svg') }} @endif"
                        class="d-block mx-auto menu-icon" />
                    <span>หน้าหลัก</span>
                </div>
            </a>
        </div>
        <div class="col-4 d-flex menu-items">
            <a href="javascript:void(0);" link="{{ route('upload') }}"
                class="d-flex justify-content-center align-items-center @if (Route::currentRouteName() === 'upload') active @endif analytic-link"
                eventLabel="send receipt-{{ route('upload') }}">
                <div class="d-flex flex-column">
                    <img src="@if (Route::currentRouteName() === 'upload') {{ asset('assets/images/menu/gift-active.svg') }} @else {{ asset('assets/images/menu/gift-inactive.svg') }} @endif"
                        class="d-block mx-auto menu-icon" />
                    <span>ส่งหลักฐาน</span>
                </div>
            </a>
        </div>
        <div class="col-4 d-flex menu-items">
            <a href="javascript:void(0);" link="{{ route('rule') }}"
                class="d-flex justify-content-center align-items-center @if (Route::currentRouteName() === 'rule') active @endif analytic-link"
                eventLabel="send receipt-{{ route('rule') }}">
                <div class="d-flex flex-column">
                    <img src="@if (Route::currentRouteName() === 'rule') {{ asset('assets/images/menu/file-active.svg') }} @else {{ asset('assets/images/menu/file-inactive.svg') }} @endif"
                        class="d-block mx-auto menu-icon" />
                    <span>รายละเอียด</span>
                </div>
            </a>
        </div>
    </div>
</div>
