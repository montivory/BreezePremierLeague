<div class="modal fade" id="profilePopup" tabindex="-1" aria-labelledby="profilePopup" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content modal-content-profile">
            <div class="modal-header modal-header-profile">
                <div class="col-md-4 col-12 mx-auto d-flex flex-row align-items-center">
                    <div class="col-2"></div>
                    <div class="col-8">
                        <img src="{{ asset('assets/images/Logo/logo.svg') }}" class="d-block mx-auto logo" />
                    </div>
                    <div class="col-2 text-center">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <form method="post" id="profileform">
                    <input type="hidden" name="addressid" id="addressid" value="{{ $address->id }}" />
                    <div class="col-md-4 col-12 mx-auto">
                        <div>
                            <h1 class="profile-title" id="profile-title">@lang('profile.editprofile')</h1>
                        </div>
                        <div class="mb-3">
                            <label for="firstname" class="form-label">@lang('profile.firstname')</label>
                            <input type="text" class="form-control validate" name="firstname" id="firstname"
                                placeholder="" validatetype="name" value="{{ $member->firstname }}">
                            <div class="invalid-feedback">
                                @lang('profile.firstnameerror')
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="lastname" class="form-label">@lang('profile.lastname')</label>
                            <input type="text" class="form-control validate" name="lastname" id="lastname"
                                placeholder="" validatetype="name" value="{{ $member->lastname }}">
                            <div class="invalid-feedback">
                                @lang('profile.lastnameerror')
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">@lang('profile.phone')</label>
                            <input type="text" class="form-control phone-input validate" name="phone"
                                id="phone" placeholder="e.g. 061 000 2345" validatetype="phone"
                                value="{{ $address->phone ? $address->phone : $member->phone }}">
                            <div class="invalid-feedback">
                                @lang('profile.phoneerror')
                            </div>
                        </div>
                        <div class="py-3">
                            <h2 class="address-title">@lang('profile.addresstitle')</h2>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">@lang('profile.address')</label>
                            <input type="text" class="form-control validate" name="address" id="address"
                                placeholder="Address No." validatetype="text" value="{{ $address->address }}">
                            <div class="invalid-feedback">
                                @lang('profile.addresserror')
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="road" class="form-label">@lang('profile.road')</label>
                            <input type="text" class="form-control" name="road" id="road" placeholder=""
                                value="{{ $address->road }}">
                        </div>
                        <div class="mb-3">
                            <label for="province" class="form-label">@lang('profile.province')</label>
                            <select class="form-select" id="province" name="province">
                                @foreach ($provinces as $province)
                                    <option value="{{ $province }}"
                                        @if ($address->province == $province) selected @endif>{{ $province }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="district" class="form-label">@lang('profile.district')</label>
                            <input type="text" class="form-control validate" name="district" id="district"
                                placeholder="" validatetype="text" value="{{ $address->district }}">
                            <div class="invalid-feedback">
                                @lang('profile.districterror')
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="subdistrict" class="form-label">@lang('profile.subdistrict')</label>
                            <input type="text" class="form-control validate" name="subdistrict" id="subdistrict"
                                placeholder="" validatetype="text" value="{{ $address->subdistrict }}">
                            <div class="invalid-feedback">
                                @lang('profile.subdistricterror')
                            </div>
                        </div>
                        <div class="mb-4 col-6">
                            <label for="postalcode" class="form-label">@lang('profile.zipcode')</label>
                            <input type="text" class="form-control validate" name="postcode" id="postcode"
                                placeholder="" validatetype="text" value="{{ $address->zipcode }}">
                            <div class="invalid-feedback">
                                @lang('profile.zipcodeerror')
                            </div>
                        </div>
                        <div class="d-grid gap-2 lucky-link mb-3">
                            <button type="button" class="btn btn-main profile-save-btn" id="profile-save-btn">
                                @lang('profile.save')
                            </button>
                        </div>
                        <div class="mb-3 text-center">
                            <a class="cancel-link" data-bs-dismiss="modal" href="javascript:void(0);">
                                @lang('profile.cancel')
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
