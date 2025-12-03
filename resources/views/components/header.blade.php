<div class="nav-block">
    <div class="col-3 d-flex align-items-center nav-section">
        @if (isset($url))
            <a href="{{ $url }}">
                <img src="{{ asset('assets/images/icons/left.svg') }}" class="d-block mx-auto" />
            </a>
        @else
            <a class="hamburger-menu" data-bs-toggle="offcanvas" href="#sidebarMenu" role="button"
                aria-controls="sidebarMenu">
                <img src="{{ asset('assets/images/icons/menu.svg') }}" class="d-block mx-auto" id="hamburger-icon" />
            </a>
        @endif
    </div>
    <div class="col-6 brand-logo">
        <a href="{{ route('member') }}">
            <img src="{{ asset('assets/images/logo/logo.svg') }}" class="d-block mx-auto" />
        </a>
    </div>
    <div class="col-3 d-flex align-items-center profile-section text-end justify-content-end">
        <?php
            $member = session('member');
            if($member) {
        ?>
        <a class="profile-image" href="{{ route('profile') }}">
            <div class="d-flex flex-row">
                <div>
                    <?php
                    $charFirst = '';
                    if ($member) {
                        $charFirst = mb_substr($member->firstname, 0, 1);
                    }
                    ?>
                    <span>
                        {{ strtoupper($charFirst) }}
                    </span>
                </div>
            </div>
        </a>
        <?php
            }
        ?>

    </div>
</div>
