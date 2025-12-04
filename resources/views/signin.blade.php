@extends('layouts.template')
@section('title')
    : Signin
@endsection
@section('meta')
    <meta name="description" content="{{ config('app.webtitle') }} - Signin">
    <meta name="keywords" content="Signin">
    <meta property="og:title" content="{{ config('app.webtitle') }} - Signin">
    <meta property="og:description" content="{{ config('app.webtitle') }} - Signin">
    <meta property="og:image" content="">
    <meta property="twitter:image" content="">
    <meta property="og:url" content="{{ url()->current() }}">
@endsection
@section('stylesheet')
    <link rel="stylesheet" href="{{ asset('assets/css/signup.css') }}" type="text/css">
@endsection
@section('aascript')
    digitalData.page.category.pageType = "Sign in page";
    digitalData.page.attributes.contentType = "Submission";
    digitalData.page.attributes.articleName = "เข้าสู่ระบบ";
@endsection
@section('content')
    <x-header :url="route('home')" />
    <div class="d-flex flex-wrap align-content-between content-block">
        <div class="col-12 px-3">
            <div class="d-flex flex-column">
                <div class="text-center pt-3">
                    <h1>
                        @lang('index.signin')
                    </h1>
                </div>
                <div class="alert alert-danger d-none" role="alert" id="signinalert">
                    {{-- Phone number or password is miss match --}}
                </div>
                <div>
                    <form id="signinform">
                        <div class="mb-3">
                            <label for="phone" class="form-label">@lang('signup.phonelabel')</label>
                            <input type="text" class="form-control" name="phone" id="phone"
                                placeholder="@lang('signup.phoneplaceholder')">
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" id="password"
                                placeholder="Password">
                        </div>
                        <div class="d-grid gap-2 pb-5">
                            <button type="submit" class="btn btn-main" id="btn-submit" disabled>@lang('index.signin')</button>
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
            $('#phone, #password').on('input', function() {
                let phone = $('#phone').val();
                let password = $('#password').val();
                if (phone !== '' && password !== '') {
                    $('#btn-submit').prop('disabled', false);
                } else {
                    $('#btn-submit').prop('disabled', true);
                }
            });

            $('#signinform').submit(function() {
                $('#btn-submit').html(`<span class="spinner-border me-3"></span>`);
                $('#btn-submit').prop('disabled', true);
                $('#signinalert').addClass('d-none');
                $.ajax({
                    url: '{{ route('member.storesignin') }}',
                    method: 'POST',
                    data: {
                        phone: $('#phone').val(),
                        password: $('#password').val(),
                    },
                    async: false,
                    cache: false
                }).done(function(response) {
                    window.location.href = "{{ route('member') }}"
                }).fail((response) => {
                    $('#signinalert').html('');
                    let messages = response.responseJSON.messages;
                    if (messages.signin !== undefined) {
                        $('#signinalert').removeClass('d-none');
                        $('#signinalert').html(messages.signin);
                    }
                    $('#btn-submit').prop('disabled', false);
                    $('#btn-submit').html(`@lang('index.signin')`);
                    return false;
                });
                return false;
            });
        });
    </script>
@endsection
