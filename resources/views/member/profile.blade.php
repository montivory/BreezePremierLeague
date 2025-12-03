@extends('layouts.template')
@section('title')
    : Profile
@endsection
@section('meta')
    <meta name="description" content="{{ config('app.webtitle') }} - Profile">
    <meta name="keywords" content="Profile">
    <meta property="og:title" content="{{ config('app.webtitle') }} - Profile">
    <meta property="og:description" content="{{ config('app.webtitle') }} - Profile">
    <meta property="og:image" content="">
    <meta property="twitter:image" content="">
@endsection
@section('stylesheet')
    <link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/css/profilemodal.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/css/select2-bootstrap-5-theme.min.css') }}" type="text/css">
    <style>

    </style>
@endsection
@section('aascript')
    digitalData.page.category.pageType = "Profile page";
    digitalData.page.attributes.contentType = "Submission";
    digitalData.page.attributes.articleName = "จัดการโปรไฟล์";
@endsection
@section('content')
    <x-header url="{{ route('member') }}" />
    <div class="content-block mt-4">
        <div class="align-content-between flex-wrap body-block">
            <div class="col-12">
                <div class="d-flex flex-column">
                    <div class="d-flex flex-row">
                        <div>
                            <?php
                            $charFirst = '';
                            if ($member) {
                                $charFirst = mb_substr($member->firstname, 0, 1);
                            }
                            ?>
                            <span class="profile-bg">
                                {{ strtoupper($charFirst) }}
                            </span>
                        </div>
                        <div class="mobile-section">
                            <div class="fw-normal">โปรไฟล์ของฉัน</div>
                            <div>{{ $member->firstname }} {{ $member->lastname }}</div>
                        </div>
                    </div>
                    <div>
                        <h2 class="myprofile">โปรไฟล์</h2>
                    </div>
                    <div class="d-flex flex-column menu-items mt-3">
                        <div class="menu-item">
                            <a href="javascript:void(0);" class="menu-link edit-profile-menu">
                                <span class="profile-icon"></span>จัดการโปรไฟล์
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="javascript:void(0);" link="{{ route('signout') }}" class="analytic-link signout"
                                eventLabel="log out"><span class="logout-icon"></span>@lang('menu.logout')</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-profilemodal :provinces="$provinces" :address="$address" :member="$member" />
@endsection
@section('script')
    <script src="{{ asset('assets/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        var profileModal = new bootstrap.Modal(document.getElementById('profilePopup'), {
            backdrop: 'static',
            keyboard: true,
            focus: true
        });

        var profileModalEl = document.getElementById('profilePopup');

        $(function() {
            $('.edit-profile-menu').on('click', function() {
                @if (config('app.env') == 'production')
                    if (typeof ctConstants !== "undefined") {
                        var ev2 = {};
                        ev2.eventInfo = {
                            'type': ctConstants.trackEvent,
                            'eventAction': ctConstants.linkClick,
                            'eventLabel': 'edit profile',
                            'eventValue': 1
                        };
                        ev2.category = {
                            'primaryCategory': ctConstants.custom
                        };
                        digitalData.event.push(ev2);
                    }
                @endif
                profileModal.show();
            });
            $('#province').select2({
                theme: "bootstrap-5",
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                    'style',
                placeholder: $(this).data('placeholder'),
                closeOnSelect: true,
                dropdownParent: $("#profilePopup")
            });
            $('.profile-save-btn').on('click', function() {
                if (verifyData(true)) {
                    $.ajax({
                        url: '{{ route('update.profile') }}',
                        method: 'POST',
                        data: {
                            addressid: $('#addressid').val(),
                            firstname: $('#firstname').val(),
                            lastname: $('#lastname').val(),
                            password: $('#password').val(),
                            password_confirmation: $('#password_confirmation').val(),
                            address: $('#address').val(),
                            road: $('#road').val(),
                            province: $('#province').val(),
                            district: $('#district').val(),
                            subdistrict: $('#subdistrict').val(),
                            postcode: $('#postcode').val(),
                            phone: $('#phone').val(),
                        },
                        async: false,
                        cache: false
                    }).done(function(response) {
                        window.location.href = "{{ route('member') }}"
                    }).fail((response) => {
                        $('#password-feedback').html('');
                        let messages = response.responseJSON.messages;
                        if (messages.password !== undefined) {
                            $('#password').addClass('is-invalid');
                            let messageError = `<ul>`;
                            $.each(messages.password, function(i, v) {
                                messageError += `<li class='text-danger'>${v}</li>`;
                            })
                            messageError += `</ul>`;
                            $('#password-feedback').append(messageError);
                        }
                        $('#btn-submit').prop('disabled', false);

                    })
                }
            });
            $('.validate').on('change', function() {
                let varName = '';
                switch ($(this).attr('validatetype')) {
                    case 'name':
                        if (!isName(this.value)) {
                            $(this).addClass('is-invalid');
                        } else {
                            $(this).removeClass('is-invalid');
                        }
                        break;
                    case 'text':
                        if (isBlank(this.value)) {
                            $(this).addClass('is-invalid');
                        } else {
                            $(this).removeClass('is-invalid');
                        }
                        break;
                    case 'phone':
                        if (isPhone(this.value)) {
                            $(this).removeClass('is-invalid');
                        } else {
                            $(this).addClass('is-invalid');
                        }
                        break;
                    case 'email':
                        if (isEmail(this.value)) {
                            $(this).removeClass('is-invalid');
                        } else {
                            $(this).addClass('is-invalid');
                        }
                        break;
                }
                verifyData(false);
            });
        });

        const verifyData = (display) => {
            let result = true;
            $.each($('.validate'), function(key, value) {
                switch ($(this).attr('validatetype')) {
                    case 'name':
                        if (!isName(this.value)) {
                            if (display) {
                                $(this).addClass('is-invalid');
                            }
                            result = false;
                        } else {
                            $(this).removeClass('is-invalid');
                        }
                        break;
                    case 'text':
                        if (isBlank(this.value)) {
                            if (display) {
                                $(this).addClass('is-invalid');
                            }
                            result = false;
                        } else {
                            $(this).removeClass('is-invalid');
                        }
                        break;
                    case 'date':
                        if (isBlank(this.value)) {
                            if (display) {
                                $(this).addClass('is-invalid');
                            }
                            result = false;
                        } else {
                            $(this).removeClass('is-invalid');
                        }
                        break;
                    case 'phone':
                        if (!isPhone(this.value)) {
                            if (display) {
                                $(this).addClass('is-invalid');
                            }
                            result = false;
                        } else {
                            $(this).removeClass('is-invalid');
                        }
                        break;
                    case 'email':
                        if (!isEmail(this.value)) {
                            if (display) {
                                $(this).addClass('is-invalid');
                            }
                            result = false;
                        } else {
                            $(this).removeClass('is-invalid');
                        }
                        break;
                }
            });
            return result;
        }
    </script>
@endsection
