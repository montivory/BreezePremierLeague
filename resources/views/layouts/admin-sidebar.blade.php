<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link @if (Route::currentRouteName() === 'admin.slip' && $status == 'process') active @endif"
                    href="{{ route('admin.slip', ['status' => 'process']) }}">
                    Waiting for verify ({{ countSlips('process') }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link ps-5 @if (Route::currentRouteName() === 'admin.slip' && $status == 'all') active @endif"
                    href="{{ route('admin.slip', ['status' => 'all']) }}">
                    All Receipt ({{ countSlips('all') }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link ps-5 @if (Route::currentRouteName() === 'admin.slip' && $status == 'approve') active @endif"
                    href="{{ route('admin.slip', ['status' => 'approve']) }}">
                    Verified ({{ countSlips('approve') }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @if (Route::currentRouteName() === 'admin.member') active @endif" href="{{ route('admin.member') }}">
                    Member ({{ countMembers() }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @if (Route::currentRouteName() === 'admin.topspender') active @endif"
                    href="{{ route('admin.topspender') }}">
                    Top Spender
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @if (Route::currentRouteName() === 'admin.profile') active @endif" href="{{ route('admin.profile') }}">
                    Profile
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('logout') }}">
                    Logout
                </a>
            </li>
        </ul>
    </div>
</nav>
