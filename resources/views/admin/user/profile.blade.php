@extends('layouts.adminform')
@section('title')
    Profile
@endsection
@section('meta')
@endsection
@section('stylesheet')
@endsection
@section('content')
    <div class="container-fluid mt-5">
        <div class="h3 border-bottom border-primary text-primary">
            Profile : {{ $data->firstname }} {{ $data->lastname }}
        </div>
        <!-- Content Row -->
        <div class="row">
            <div class="col-md-12">
                <form id="profileform" action="#">
                    <div class="mb-3">
                        <div class="col-12 mt-2 text-end">
                            <button name="submitbtn" id="submitbtn" class="btn btn-outline-primary btn-sm"
                                type="button">Save</button>
                            <a href="{{ route('admin.slip') }}" class="btn btn-outline-danger btn-sm">Cancel</a>
                        </div>
                    </div>
                    <div class="row mb-3 mt-3">
                        <label for="email" class="col-form-label col-2">Email</label>
                        <div class="col-10">
                            <input type="email" name="email" id="email" class="form-control" readonly
                                value="{{ $data->email }}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="password" class="col-form-label col-2">Password</label>
                        <div class="col-4">
                            <input type="password" name="password" id="password" class="form-control">
                            <div id="passwordHelper" class="form-text">
                                <ul>
                                    <li>The password field must be at least 8 characters.</li>
                                    <li>The password field must contain at least one uppercase and one lowercase letter.
                                    </li>
                                    <li>The password field must contain at least one number.</li>
                                </ul>
                            </div>
                            <div class="invalid-feedback" id="password-feedback">
                                
                            </div>
                        </div>
                        <label for="password_confirmation" class="col-form-label col-2">Password Confirmation</label>
                        <div class="col-4">
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="form-control" />
                        </div>
                    </div>
            </div>
            <div class="row mb-3 mt-3">
                <label for="firstname" class="col-form-label col-2">Firstname</label>
                <div class="col-10">
                    <input type="text" name="firstname" id="firstname" class="form-control"
                        value="{{ $data->firstname }}">
                </div>
            </div>
            <div class="row mb-3 mt-3">
                <label for="lastname" class="col-form-label col-2">Lastname</label>
                <div class="col-10">
                    <input type="text" name="lastname" id="lastname" class="form-control" value="{{ $data->lastname }}">
                </div>
            </div>
            </form>
        </div>
    </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        $(function() {
            $('#submitbtn').on('click', function() {
                buildData();
            })
        })

        const buildData = () => {
            let pass = true;
            $('.is-invalid').removeClass('is-invalid');
            $.each($('.validate'), function(i, obj) {
                if ($(obj).val().trim().length === 0) {
                    $(obj).addClass('is-invalid');
                    pass = false;
                }
            })
            if (!pass) {
                return false;
            }
            let data = {};
            data.id = '{{ $data->id }}';
            data.password = '';
            if ($('#password').val().trim().length > 0) {
                data.password = $('#password').val();
                data.password_confirmation = $('#password_confirmation').val();
            }
            data.firstname = $('#firstname').val();
            data.lastname = $('#lastname').val();
            $('#submitbtn').prop('disabled', true);
            setTimeout(() => {
                $.ajax({
                    url: "{{ route('admin.profile.store') }}",
                    method: "POST",
                    async: false,
                    cache: false,
                    data: data
                }).done(() => {
                    alert('Update profile successful');
                    location.reload();
                }).fail((response) => {
                    let messages = response.responseJSON.messages;
                    $('#password-feedback').html('');
                    if (messages.password !== undefined) {
                        $('#password').addClass('is-invalid');
                        let messageError = `<ul>`;
                        $.each(messages.password, function(i, v) {
                            messageError += `<li>${v}</li>`;
                        })
                        messageError += `</ul>`;
                        $('#password-feedback').append(messageError);
                    }
                    $('#submitbtn').prop('disabled', false);
                })
            }, 500)
        }
    </script>
@endsection
