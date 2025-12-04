@extends('layouts.template')
@section('title')
    : Signup
@endsection
@section('meta')
    <meta name="description" content="{{ config('app.webtitle') }} - Signup">
    <meta name="keywords" content="Signup">
    <meta property="og:title" content="{{ config('app.webtitle') }} - Signup">
    <meta property="og:description" content="{{ config('app.webtitle') }} - Signup">
    <meta property="og:image" content="">
    <meta property="twitter:image" content="">
    <meta property="og:url" content="{{ url()->current() }}">
@endsection
@section('stylesheet')
    <link rel="stylesheet" href="{{ asset('assets/css/signup.css') }}" type="text/css">
@endsection
@section('aascript')
    digitalData.page.category.pageType = "Register page";
    digitalData.page.attributes.contentType = "Submission";
    digitalData.page.attributes.articleName = "ลงทะเบียน";
@endsection
@section('content')
    <x-header :url="route('home')" />
    <div class="d-flex flex-wrap align-content-between content-block">
        <div class="col-12 px-3">
            <div class="d-flex flex-column">
                <div class="text-center">
                    <h1>
                        @lang('signup.title')
                    </h1>
                </div>
                <div>
                    <div class="mb-3">
                        <h6>ข้อมูลส่วนตัว</h6>
                    </div>
                    <form name="signupform" id="signupform">
                        <div class="mb-3">
                            <label for="firstname" class="form-label">@lang('profile.firstname')</label>
                            <input type="text" class="form-control validate" name="firstname" id="firstname"
                                placeholder="" validatetype="name">
                            <div class="invalid-feedback">
                                @lang('profile.firstnameerror')
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="lastname" class="form-label">@lang('profile.lastname')</label>
                            <input type="text" class="form-control validate" name="lastname" id="lastname"
                                placeholder="" validatetype="name">
                            <div class="invalid-feedback">
                                @lang('profile.lastnameerror')
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">@lang('signup.phonelabel')</label>
                            <input type="tel" class="form-control validate" name="phone" id="phone"
                                placeholder="@lang('signup.phoneplaceholder')" validatetype="phone">
                            <div class="invalid-feedback" id="phone-feedback">
                                @lang('signup.phoneerror')
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">@lang('profile.password')</label>
                            <input type="password" class="form-control" name="password" id="password"
                                placeholder="@lang('profile.password')">
                            <div id="passwordHelper" class="form-text">
                                <ul>
                                    <li class="text-white">@lang('profile.passwordhelper.min')</li>
                                    <li class="text-white">@lang('profile.passwordhelper.mix')</li>
                                    <li class="text-white">@lang('profile.passwordhelper.number')</li>
                                </ul>
                            </div>
                            <div class="invalid-feedback" id="password-feedback"></div>
                        </div>
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">@lang('profile.passwordconfirm')</label>
                            <input type="password" class="form-control" name="password_confirmation"
                                id="password_confirmation" placeholder="@lang('profile.passwordconfirm')">
                        </div>
                        <div class="mb-3">
                            <h6>ที่อยู่</h6>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">@lang('profile.address')</label>
                            <input type="text" class="form-control validate" name="address" id="address" placeholder=""
                                validatetype="text">
                            <div class="invalid-feedback">
                                @lang('profile.addresserror')
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="road" class="form-label">@lang('profile.road')</label>
                            <input type="text" class="form-control validate" name="road" id="road" placeholder=""
                                validatetype="text" />
                            <div class="invalid-feedback">
                                @lang('profile.roaderror')
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="province" class="form-label">@lang('profile.province')</label>
                            <select class="form-select" id="province" name="province">
                                @foreach ($provinces as $province)
                                    <option>{{ $province }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="district" class="form-label">@lang('profile.district')</label>
                            <input type="text" class="form-control validate" name="district" id="district"
                                placeholder="" validatetype="text">
                            <div class="invalid-feedback">
                                @lang('profile.districterror')
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="subdistrict" class="form-label">@lang('profile.subdistrict')</label>
                            <input type="text" class="form-control validate" name="subdistrict" id="subdistrict"
                                placeholder="" validatetype="text">
                            <div class="invalid-feedback">
                                @lang('profile.subdistricterror')
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="postalcode" class="form-label">@lang('profile.zipcode')</label>
                            <input type="text" class="form-control validate" name="postcode" id="postcode"
                                placeholder="" validatetype="number">
                            <div class="invalid-feedback">
                                @lang('profile.zipcodeerror')
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input term-consent" type="checkbox" name="term-consent"
                                    id="term-consent">
                                <label class="form-check-label term-consent-label" for="term-consent">
                                    @lang('signup.consent')
                                </label>
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-main" id="btn-submit"
                                disabled>@lang('signup.submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        $(function() {
            $('#phone').on('change', function() {
                verifyForm();
            });
            $('#term-consent').on('click', function() {
                verifyForm();
            });
            $('#btn-submit').on('click', function() {
                if (verifyForm()) {
                    $('#btn-submit').html(`<span class="spinner-border me-3"></span>`);
                    $('#btn-submit').prop('disabled', true);
                    @if (config('app.env') == 'production')
                        var ev2 = {};
                        ev2.eventInfo = {
                            'type': ctConstants.trackEvent,
                            'eventAction': ctConstants.linkClick,
                            'eventLabel': `sign up-`,
                            'eventValue': 1
                        };
                        ev2.category = {
                            'primaryCategory': ctConstants.custom
                        };
                        digitalData.event.push(ev2);
                    @endif

                    setTimeout(() => {
                        $.ajax({
                            url: '{{ route('create.member.password') }}',
                            method: 'POST',
                            data: {
                                firstname: $('#firstname').val(),
                                lastname: $('#lastname').val(),
                                phone: $('#phone').val(),
                                password: $('#password').val(),
                                password_confirmation: $('#password_confirmation').val(),
                                address: $('#address').val(),
                                road: $('#road').val(),
                                province: $('#province').val(),
                                district: $('#district').val(),
                                subdistrict: $('#subdistrict').val(),
                                postcode: $('#postcode').val(),
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
                                    messageError +=
                                        `<li class='text-danger'>${v}</li>`;
                                })
                                messageError += `</ul>`;
                                $('#password-feedback').append(messageError);
                                $('html, body').animate({
                                    scrollTop: $('#password').offset()
                                        .top - 100
                                }, 300);
                            }
                            $('#btn-submit').prop('disabled', false);
                            $('#btn-submit').html(`ลงทะเบียน`);
                        })
                    }, 1000);
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
                    case 'number':
                        if (isNumber(this.value)) {
                            $(this).removeClass('is-invalid');
                        } else {
                            $(this).addClass('is-invalid');
                        }
                        break;
                }
                verifyForm();
            });
        });

        const checkTerm = () => {
            if ($('#term-consent').prop('checked')) {
                return true;
            }
            return false;
        }

        const verifyPhone = () => {
            let result = true;
            $('#phone').removeClass('is-invalid');
            $.ajax({
                url: '{{ route('checkphone') }}',
                method: 'POST',
                data: {
                    phone: $('#phone').val()
                },
                async: false,
                cache: false
            }).done(function(response) {
                if (response.result) {
                    result = false;
                    $('#phone-feedback').text('@lang('signup.phoneerrorduplicate')');
                    $('#phone').addClass('is-invalid');
                    $('html, body').animate({
                        scrollTop: $('#phone').offset()
                            .top - 100
                    }, 300);
                } else {
                    $('#phone').removeClass('is-invalid');
                }
            }).fail(() => {
                $('#phone-feedback').text('@lang('signup.phoneerrorduplicate')');
                $('#phone').addClass('is-invalid');
                $('html, body').animate({
                    scrollTop: $('#phone').offset()
                        .top - 100
                }, 300);
            })
            return result;
        }

        const verifyForm = () => {
            let result = true;
            $.each($('.validate'), function(key, value) {
                switch ($(this).attr('validatetype')) {
                    case 'name':
                        if (!isName(this.value)) {
                            if ($('#term-consent').prop("checked") === true) {
                                $(this).addClass('is-invalid');
                            }
                            result = false;
                        } else {
                            $(this).removeClass('is-invalid');
                        }
                        break;
                    case 'text':
                        if (isBlank(this.value)) {
                            if ($('#term-consent').prop("checked") === true) {
                                $(this).addClass('is-invalid');
                            }
                            result = false;
                        } else {
                            $(this).removeClass('is-invalid');
                        }
                        break;
                    case 'date':
                        if (isBlank(this.value)) {
                            if ($('#term-consent').prop("checked") === true) {
                                $(this).addClass('is-invalid');
                            }
                            result = false;
                        } else {
                            $(this).removeClass('is-invalid');
                        }
                        break;
                    case 'phone':
                        if (!isPhone(this.value)) {
                            if ($('#term-consent').prop("checked") === true) {
                                $(this).addClass('is-invalid');
                            }
                            result = false;
                        } else {
                            $(this).removeClass('is-invalid');
                        }
                        break;
                    case 'email':
                        if (!isEmail(this.value)) {
                            if ($('#term-consent').prop("checked") === true) {
                                $(this).addClass('is-invalid');
                            }
                            result = false;
                        } else {
                            $(this).removeClass('is-invalid');
                        }
                        break;
                    case 'number':
                        if (!isNumber(this.value)) {
                            if ($('#term-consent').prop("checked") === true) {
                                $(this).addClass('is-invalid');
                            }
                            result = false;
                        } else {
                            $(this).removeClass('is-invalid');
                        }
                        break;
                }
            });
            if (result) {
                result = verifyPhone();
            }
            if (result) {
                result = checkTerm();
            }
            if (result) {
                $('#btn-submit').prop('disabled', false);
            } else {
                $('#btn-submit').prop('disabled', true);
            }
            return result;
        }
    </script>
@endsection
