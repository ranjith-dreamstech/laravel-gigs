@extends('frontend.seller.partials.app')
@section('content')

<!-- Page Content -->
<div class="page-wrapper">
    <div class="page-content content bg-light">
        <div class="row justify-content-center">
            <div class="col-lg-10">


                <div class="main-title mb-4">
                    <h4>{{ __('web.common.settings') }}</h4>
                </div>

                <div class="settings-info bg-white">
                    <div class="settings-page-lists">
                        <ul class="settings-head nav nav-tabs">
                            <li class="nav-item">
                                <a href="javascript:void(0);" class="nav-link active" data-bs-toggle="tab"
                                    data-bs-target="#Personal_Information" aria-selected="false" role="tab"
                                    tabindex="-1">
                                    {{ __('web.user.profile') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="javascript:void(0);" class="nav-link" data-bs-toggle="tab"
                                    data-bs-target="#acc_settings" aria-selected="false" role="tab" tabindex="-1">
                                    {{ __('web.user.account_settings') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="javascript:void(0);" class="nav-link" data-bs-toggle="tab"
                                    data-bs-target="#security" aria-selected="false" role="tab" tabindex="-1">
                                    {{ __('web.user.security') }}
                                </a>
                            </li>
                            <li class="nav-item d-none">
                                <a href="javascript:void(0);" class="nav-link" data-bs-toggle="tab"
                                    data-bs-target="#preferences" aria-selected="false" role="tab" tabindex="-1">
                                    Preferences
                                </a>
                            </li>
                            <li class="nav-item d-none">
                                <a href="javascript:void(0);" class="nav-link" data-bs-toggle="tab"
                                    data-bs-target="#billing" aria-selected="false" role="tab" tabindex="-1">
                                    Plan & Billing
                                </a>
                            </li>
                            <li class="nav-item d-none">
                                <a href="javascript:void(0);" class="nav-link" data-bs-toggle="tab"
                                    data-bs-target="#notifications" aria-selected="false" role="tab" tabindex="-1">
                                    Notifications
                                </a>
                            </li>
                            <li class="nav-item d-none">
                                <a href="javascript:void(0);" class="nav-link" data-bs-toggle="tab"
                                    data-bs-target="#integration" aria-selected="false" role="tab" tabindex="-1">
                                    Integrations
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="tab-content">
                        <!-- Profile Settings -->
                        <div class="tab-pane fade show active" id="Personal_Information" role="tabpanel">
                            <form id="profileForm">
                                <div class="settings-card">
                                    <div class="settings-card-head bg-light">
                                        <h4>{{ __('web.user.personal_information') }}</h4>
                                    </div>
                                    <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}">
                                    <div class="settings-card-body">
                                        <div class="img-upload-head">
                                            <div class="profile-img">
                                                <img src="{{ $user->userDetail->profile_image ?? asset('backend/assets/img/default-profile.png') }}"
                                                    id="imagePreview" alt="">
                                            </div>
                                            <div class="img-formate">
                                                <div class="upload-remove-btns">
                                                    <div class="drag-upload form-wrap">
                                                        <input type="file" name="image" id="image">
                                                        <div class="img-upload">
                                                            <p>{{ __('web.user.upload_image') }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <p>{{ __('web.user.upload_image_size_description') }}</p>
                                            </div>
                                        </div>
                                        <span class="error-text text-danger" id="image_error"></span>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-wrap">
                                                    <label class="form-label"> {{ __('web.user.first_name') }} <span
                                                            class="text-primary">*</span></label>
                                                    <input type="text" class="form-control" id="first_name"
                                                        name="first_name"
                                                        value="{{ $user->userDetail->first_name ?? '' }}">
                                                    <span id="first_name_error" class="text-danger error-text"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-wrap">
                                                    <label class="form-label"> {{ __('web.user.last_name') }} <span
                                                            class="text-primary">*</span></label>
                                                    <input type="text" class="form-control" id="last_name"
                                                        name="last_name"
                                                        value="{{ $user->userDetail->last_name ?? '' }}">
                                                    <span id="last_name_error" class="text-danger error-text"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-wrap">
                                                    <label class="form-label"> {{ __('web.user.email') }} <span
                                                            class="text-primary">*</span></label>
                                                    <input type="email" class="form-control" id="email" name="email"
                                                        value="{{ $user->email ?? '' }}" readonly>
                                                    <span id="email_error" class="text-danger error-text"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-wrap">
                                                    <label class="form-label"> {{ __('web.user.phone_number') }} <span
                                                            class="text-primary">*</span></label>
                                                    <input type="text" class="form-control user_phone_number"
                                                        id="phone_number" name="phone_number"
                                                        value="{{ $user->phone_number ?? '' }}">
                                                    <input type="hidden" id="international_phone_number"
                                                        name="international_phone_number"
                                                        value="{{ $user->phone_number ?? '' }}">
                                                    <span id="phone_number_error" class="text-danger error-text"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-wrap">
                                                    <label class="form-label"> {{ __('web.user.date_of_birth') }} <span
                                                            class="text-primary">*</span></label>
                                                    <input type="date" class="form-control" id="dob" name="dob"
                                                        value="{{ $user->userDetail->dob ?? '' }}"
                                                        max="{{ date('Y-m-d', strtotime('-1 day')) }}">
                                                    <span id="dob_error" class="text-danger error-text"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-wrap gig-option">
                                                    <label class="mb-1 fw-medium text-dark d-block mb-2">
                                                        {{ __('web.user.gender') }} <span
                                                            class="text-primary">*</span></label>
                                                    <label class="custom_radio">
                                                        <input type="radio" name="gender" value="male"
                                                            {{ ($user->userDetail->gender ?? '') == 'male' ? 'checked' : '' }}>
                                                        <span
                                                            class="checkmark"></span>{{ ucfirst(__('web.user.male')) }}
                                                    </label>
                                                    <label class="custom_radio">
                                                        <input type="radio" name="gender" value="female"
                                                            {{ ($user->userDetail->gender ?? '') == 'female' ? 'checked' : '' }}>
                                                        <span
                                                            class="checkmark"></span>{{ ucfirst(__('web.user.female')) }}
                                                    </label>
                                                    <span id="gender_error" class="text-danger error-text"></span>
                                                </div>

                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-wrap">
                                                    <label
                                                        class="form-label mb-1 fw-medium text-dark">{{ __('web.user.country') }}<span
                                                            class="text-primary">*</span></label>
                                                    <select class="select2 form-control" name="country" id="country"
                                                        data-default-id="{{ $user->userDetail->country_id ?? '' }}"
                                                        data-placeholder="{{ __('web.common.select') }}">
                                                        @if(!empty($countries) && count($countries) > 0)
                                                        @foreach($countries as $country)
                                                        <option value="{{ $country->id }}" @if(Auth::guard('web')->
                                                            user()->userDetail &&
                                                            Auth::guard('web')->user()->userDetail->country_id ==
                                                            $country->id) selected @endif >{{ $country->name }}</option>
                                                        @endforeach
                                                        @endif
                                                    </select>
                                                    <span id="country_error" class="text-danger error-text"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-wrap">
                                                    <label
                                                        class="form-label mb-1 fw-medium text-dark">{{ __('web.user.state') }}<span
                                                            class="text-primary">*</span></label>
                                                    <select class="select2 form-control" name="state" id="state"
                                                        data-default-id="{{ $user->userDetail->state_id ?? '' }}"
                                                        data-placeholder="{{ __('web.common.select') }}">
                                                    </select>
                                                    <span id="state_error" class="text-danger error-text"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-wrap">
                                                    <label
                                                        class="form-label mb-1 fw-medium text-dark">{{ __('web.user.city') }}<span
                                                            class="text-primary">*</span></label>
                                                    <select class="select2 form-control" name="city" id="city"
                                                        data-default-id="{{ $user->userDetail->city_id ?? '' }}"
                                                        data-placeholder="{{ __('web.common.select') }}">
                                                    </select>
                                                    <span id="city_error" class="text-danger error-text"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-wrap mb-0">
                                                    <label class="form-label"> {{ __('web.user.address') }} <span
                                                            class="text-primary">*</span></label>
                                                    <input type="text" class="form-control" id="address" name="address"
                                                        value="{{ $user->userDetail->address ?? '' }}">
                                                    <span id="address_error" class="text-danger error-text"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-wrap">
                                                    <label class="form-label"> {{ __('web.user.postal_code') }} <span
                                                            class="text-primary">*</span></label>
                                                    <input type="text" class="form-control" id="postal_code"
                                                        name="postal_code"
                                                        value="{{ $user->userDetail->postal_code ?? '' }}">
                                                    <span id="postal_code_error" class="text-danger error-text"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="settings-card">
                                    <div class="settings-card-head bg-light">
                                        <h4>{{ __('web.user.change_email') }}</h4>
                                    </div>
                                    <div class="settings-card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-wrap mb-lg-0 mb-md-0">
                                                    <label class="form-label"> {{ __('web.user.current_email') }} <span
                                                            class="text-primary"></span></label>
                                                    <input type="text" class="form-control" name="current_email"
                                                        id="current_email">
                                                    <span id="current_email_error"
                                                        class="text-danger error-text"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-wrap mb-lg-0 mb-md-0">
                                                    <label class="form-label"> {{ __('web.user.new_email') }} <span
                                                            class="text-primary"></span></label>
                                                    <input type="text" class="form-control" name="new_email"
                                                        id="new_email">
                                                    <span id="new_email_error" class="text-danger error-text"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-wrap mb-0">
                                                    <label class="form-label"> {{ __('web.user.confirm_email') }} <span
                                                            class="text-primary"></span></label>
                                                    <input type="text" class="form-control" name="confirm_email"
                                                        id="confirm_email">
                                                    <span id="confirm_email_error"
                                                        class="text-danger error-text"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="settings-card">
                                    <div class="settings-card-head bg-light">
                                        <h4>{{ __('web.user.other_information') }}</h4>
                                    </div>
                                    <div class="settings-card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-wrap">
                                                    <label class="form-label">{{ __('web.user.job_title') }}</label>
                                                    <input type="text" class="form-control" name="job_title"
                                                        id="job_title" value="{{ $user->userDetail->job_title ?? '' }}">
                                                    <span id="job_title_error" class="text-danger error-text"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-wrap">
                                                    <label
                                                        class="form-label">{{ __('web.user.language_known') }}</label>
                                                    <div class="input-block input-block-tagsinput mb-1">
                                                        <input type="text" data-role="tagsinput"
                                                            class="input-tags form-control" name="language_known"
                                                            id="language_known"
                                                            value="{{ $user->userDetail->language_known ?? '' }}">
                                                    </div>
                                                    <span>{{ __('web.user.enter_value_separated_by_comma') }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-wrap">
                                                    <label class="col-form-label">{{ __('web.user.tags') }}</label>
                                                    <div class="input-block input-block-tagsinput mb-1">
                                                        <input type="text" data-role="tagsinput"
                                                            class="input-tags form-control" name="tags" id="tags"
                                                            value="{{ $user->userDetail->tags ?? '' }}">
                                                    </div>
                                                    <span>{{ __('web.user.enter_value_separated_by_comma') }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-wrap">
                                                    <label class="col-form-label">{{ __('web.user.skills') }}</label>
                                                    <div class="input-block input-block-tagsinput mb-1">
                                                        <input type="text" data-role="tagsinput"
                                                            class="input-tags form-control" name="skills" id="skills"
                                                            value="{{ $user->userDetail->skills ?? '' }}">
                                                    </div>
                                                    <span>{{ __('web.user.enter_value_separated_by_comma') }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-wrap">
                                                    <label class="form-label"> {{ __('web.user.about_me') }} </label>
                                                    <textarea class="form-control text-area" name="about" id="about"
                                                        placeholder="{{ __('web.user.type_here') }}...">{{ $user->userDetail->about ?? '' }}</textarea>
                                                    <span id="about_error" class="text-danger error-text"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-wrap m-0">
                                                    <label class="form-label"> {{ __('web.user.why_work_with_me') }}
                                                    </label>
                                                    <textarea class="form-control text-area" name="profile_description"
                                                        id="profile_description"
                                                        placeholder="{{ __('web.user.type_here') }}...">{{ $user->userDetail->profile_description ?? '' }}</textarea>
                                                    <span id="profile_description_error"
                                                        class="text-danger error-text"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="settings-card-footer">
                                    <div class="btn-item">
                                        <a href="#" class="btn">{{ __('web.common.cancel') }}</a>
                                        <button class="btn btn-primary profile_savebtn"
                                            type="submit">{{ __('web.common.save_changes') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- /Profile Settings -->

                        <!-- Account Settings -->
                        <div class="tab-pane fade" id="acc_settings" role="tabpanel">
                            <!-- Paypal -->
                            <div class="settings-card">
                                <div class="settings-card-head bg-light">
                                    <h4>{{ __('web.user.paypal') }}</h4>
                                </div>
                                <form id="paypalForm" autocomplete="off">
                                    <div class="settings-card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-wrap">
                                                    <label class="form-label"> {{ __('web.user.email') }} </label>
                                                    <input type="text" class="form-control" name="paypal_email"
                                                        id="paypal_email"
                                                        value="{{ $accountSettings['paypal']['paypal_email'] ?? ''}}">
                                                    <span id="paypal_email_error" class="text-danger error-text"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-wrap">
                                                    <label class="form-label"> {{ __('web.user.auth_password') }}
                                                    </label>
                                                    <input type="text" class="form-control" name="paypal_password"
                                                        id="paypal_password"
                                                        value="{{ $accountSettings['paypal']['paypal_password'] ?? ''}}">
                                                    <span id="paypal_password_error"
                                                        class="text-danger error-text"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="settings-card-footer text-start mt-0">
                                            <button type="submit"
                                                class="btn btn-dark text-white paypal_savebtn">{{ __('web.common.save_changes') }}
                                                <i class="ti ti-arrow-up-right ms-1"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- /Paypal -->

                            <!-- Stripe Transfer -->
                            <div class="settings-card">
                                <div class="settings-card-head bg-light">
                                    <h4>{{ __('web.user.stripe') }}</h4>
                                </div>
                                <form id="stripeForm">
                                    <div class="settings-card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-wrap">
                                                    <label class="form-label"> {{ __('web.user.email') }} <span
                                                            class="text-primary">*</span></label>
                                                    <input type="text" class="form-control" name="stripe_email"
                                                        id="stripe_email"
                                                        value="{{ $accountSettings['stripe']['stripe_email'] ?? ''}}">
                                                    <span id="stripe_email_error" class="text-danger error-text"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-wrap">
                                                    <label class="form-label"> {{ __('web.user.auth_password') }} <span
                                                            class="text-primary">*</span></label>
                                                    <input type="text" class="form-control" name="stripe_password"
                                                        id="stripe_password"
                                                        value="{{ $accountSettings['stripe']['stripe_password'] ?? ''}}">
                                                    <span id="stripe_password_error"
                                                        class="text-danger error-text"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="settings-card-footer text-start mt-0">
                                            <button type="submit"
                                                class="btn btn-dark text-white stripe_savebtn">{{ __('web.common.save_changes') }}
                                                <i class="ti ti-arrow-up-right ms-1"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- /Stripe Transfer -->

                            <!-- Bank Transfer -->
                            <div class="settings-card">
                                <div class="settings-card-head bg-light">
                                    <h4>{{ __('web.user.bank_transfer') }}</h4>
                                </div>
                                <form id="bankForm" autocomplete="off">
                                    <div class="settings-card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-wrap">
                                                    <label class="form-label"> {{ __('web.user.account_holder_name') }}
                                                    </label>
                                                    <input type="text" class="form-control" name="account_holder_name"
                                                        id="account_holder_name"
                                                        value="{{ $accountSettings['bank_transfer']['account_holder_name'] ?? ''}}">
                                                    <span id="account_holder_name_error"
                                                        class="text-danger error-text"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-wrap">
                                                    <label class="form-label"> {{ __('web.user.bank_name') }} </label>
                                                    <input type="text" class="form-control" name="bank_name"
                                                        id="bank_name"
                                                        value="{{ $accountSettings['bank_transfer']['bank_name'] ?? ''}}">
                                                    <span id="bank_name_error" class="text-danger error-text"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-wrap">
                                                    <label class="form-label"> {{ __('web.user.ifsc_code') }} </label>
                                                    <input type="text" class="form-control" name="ifsc_code"
                                                        id="ifsc_code"
                                                        value="{{ $accountSettings['bank_transfer']['ifsc_code'] ?? ''}}">
                                                    <span id="ifsc_code_error" class="text-danger error-text"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-wrap">
                                                    <label class="form-label"> {{ __('web.user.account_number') }}
                                                    </label>
                                                    <input type="text" class="form-control" name="account_number"
                                                        id="account_number"
                                                        value="{{ $accountSettings['bank_transfer']['account_number'] ?? ''}}">
                                                    <span id="account_number_error"
                                                        class="text-danger error-text"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="settings-card-footer text-start mt-0">
                                            <button type="submit"
                                                class="btn btn-dark text-white bank_savebtn">{{ __('web.common.save_changes') }}
                                                <i class="ti ti-arrow-up-right ms-1"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- /Bank Transfer -->

                        </div>
                        <!-- /Account Settings  -->

                        <!-- Security settings -->
                        <div class="tab-pane fade" id="security" role="tabpanel">
                            <div class="card flex-fill mb-0 border-0 bg-light-500 shadow-none securitys-card-info">
                                <div class="card-body">
                                    <div>
                                        <div
                                            class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 border-bottom mb-4 pb-4">
                                            <div class="d-flex align-items-center">
                                                <span
                                                    class="avatar avatar-md border bg-light fs-24 me-2 d-flex align-items-center justify-content-center">
                                                    <i
                                                        class="ti ti-lock text-dark fs-24 d-flex align-items-center justify-content-center"></i>
                                                </span>
                                                <div>
                                                    <h6 class="mb-1">{{ __('web.user.auth_password') }}</h6>
                                                    <span class="fs-13">{{ __('web.user.password_description') }}</span>
                                                </div>
                                            </div>
                                            <div>
                                                <span
                                                    class="badge bg-soft-secondary new-badge text-muted me-3">{{ __('web.user.last_changed') }},
                                                    {{ formatDateTime($user->last_password_changed_at, false) }}</span>
                                                <a href="javascript:void(0);"
                                                    class="btn btn-sm btn-primary secure-password"
                                                    id="change_passwordbtn" data-bs-toggle="modal"
                                                    data-bs-target="#password_update"> <i class="ti ti-edit"></i> </a>
                                            </div>
                                        </div>
                                        <div
                                            class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 border-bottom mb-4 pb-4">
                                            <div class="d-flex align-items-center">
                                                <span
                                                    class="avatar avatar-md border bg-light fs-24 me-2 d-flex align-items-center justify-content-center">
                                                    <i
                                                        class="ti ti-device-laptop text-dark fs-24 d-flex align-items-center justify-content-center"></i>
                                                </span>
                                                <div>
                                                    <h6 class="mb-1">{{ __('web.user.device_management') }}</h6>
                                                    <span
                                                        class="fs-13">{{ __('web.user.device_management_description') }}</span>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <a href="javascript:void(0);"
                                                    class="btn btn-sm btn-primary secure-password"
                                                    data-bs-toggle="modal" data-bs-target="#device_management_modal"> <i
                                                        class="ti ti-file-description"></i> </a>
                                            </div>
                                        </div>
                                        <div
                                            class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 border-bottom mb-4 pb-4 ">
                                            <div class="d-flex align-items-center">
                                                <span
                                                    class="avatar flex-shrink-0 avatar-md border bg-light fs-24 me-2 d-flex align-items-center justify-content-center">
                                                    <i
                                                        class="ti ti-exclamation-circle text-dark fs-24 d-flex align-items-center justify-content-center"></i>
                                                </span>
                                                <div>
                                                    <h6 class="mb-1">{{ __('web.user.deactivate_account') }}</h6>
                                                    <span
                                                        class="fs-13">{{ __('web.user.deactivate_account_description') }}</span>
                                                </div>
                                            </div>
                                            <a href="" class="btn btn-sm btn-primary secure-password"
                                                data-bs-toggle="modal" data-bs-target="#delete_account"> <i
                                                    class="ti ti-trash"></i> </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Security settings -->

                        <!-- Preference -->
                        <div class="tab-pane fade" id="preferences" role="tabpanel">
                            <div class="row">
                                <div class="col-xl-4 col-md-4 col-sm-6">
                                    <div class="settings-card">
                                        <div class="settings-card-body d-flex justify-content-between">
                                            <h6 class="settings-text">Purchase List</h6>
                                            <div class="status-toggle d-flex align-items-center">
                                                <input type="checkbox" id="toggle1" class="check" checked="">
                                                <label for="toggle1" class="checktoggle"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-4 col-sm-6">
                                    <div class="settings-card">
                                        <div class="settings-card-body d-flex justify-content-between">
                                            <h6 class="settings-text">Sales List</h6>
                                            <div class="status-toggle d-flex align-items-center">
                                                <input type="checkbox" id="toggle2" class="check" checked="">
                                                <label for="toggle2" class="checktoggle"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-4 col-sm-6">
                                    <div class="settings-card">
                                        <div class="settings-card-body d-flex justify-content-between">
                                            <h6 class="settings-text">Uploaded Files</h6>
                                            <div class="status-toggle d-flex align-items-center">
                                                <input type="checkbox" id="toggle3" class="check" checked="">
                                                <label for="toggle3" class="checktoggle"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-4 col-sm-6">
                                    <div class="settings-card">
                                        <div class="settings-card-body d-flex justify-content-between">
                                            <h6 class="settings-text">Reviews</h6>
                                            <div class="status-toggle d-flex align-items-center">
                                                <input type="checkbox" id="toggle4" class="check" checked="">
                                                <label for="toggle4" class="checktoggle"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-4 col-sm-6">
                                    <div class="settings-card">
                                        <div class="settings-card-body d-flex justify-content-between">
                                            <h6 class="settings-text">Wishlist</h6>
                                            <div class="status-toggle d-flex align-items-center">
                                                <input type="checkbox" id="toggle5" class="check" checked="">
                                                <label for="toggle5" class="checktoggle"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-4 col-sm-6">
                                    <div class="settings-card">
                                        <div class="settings-card-body d-flex justify-content-between">
                                            <h6 class="settings-text">Chat</h6>
                                            <div class="status-toggle d-flex align-items-center">
                                                <input type="checkbox" id="toggle6" class="check" checked="">
                                                <label for="toggle6" class="checktoggle"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-4 col-sm-6">
                                    <div class="settings-card">
                                        <div class="settings-card-body d-flex justify-content-between">
                                            <h6 class="settings-text">Wallet</h6>
                                            <div class="status-toggle d-flex align-items-center">
                                                <input type="checkbox" id="toggle7" class="check" checked="">
                                                <label for="toggle7" class="checktoggle"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-4 col-sm-6">
                                    <div class="settings-card">
                                        <div class="settings-card-body d-flex justify-content-between">
                                            <h6 class="settings-text">Payments</h6>
                                            <div class="status-toggle d-flex align-items-center">
                                                <input type="checkbox" id="toggle8" class="check" checked="">
                                                <label for="toggle8" class="checktoggle"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Preference -->

                        <!-- Plan Billing -->
                        <div class="tab-pane fade" id="billing" role="tabpanel">
                            <div class="plan-billing-info">
                                <h6 class="mb-3">Current Plan Information</h6>
                                <div class="billing-type">
                                    <div class="settings-card bg-light">
                                        <div class="settings-card-head">
                                            <h6 class="text-dark fw-medium">Basic Plan</h6>
                                            <span> <i class="ti ti-clock"> </i> 20 Days Left</span>
                                        </div>
                                        <div class="settings-card-body">
                                            <div class="btn-item">
                                                <a href="#" class="btn btn-dark text-white" data-bs-toggle="modal"
                                                    data-bs-target="#add-card"> <i class="ti ti-plus me-1"></i>Add New
                                                    Card </a>
                                                <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#plans-modal"> <i
                                                        class="ti ti-shield-checkered me-1"></i> Upgrade </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="settings-card d-block">
                                                <div class="settings-new">
                                                    <div class="settings-img d-flex align-items-center gap-3 mb-3">
                                                        <img src="/frontend/assets/img/payment/gateway-04.png" alt="">
                                                        <h6>
                                                            <span> James Peterson </span>
                                                            Visa •••• 1568
                                                        </h6>
                                                    </div>
                                                    <div
                                                        class="setting-bottom d-flex align-items-center justify-content-between">
                                                        <span class="badge badge-success bg-success text-white"> Default
                                                        </span>
                                                        <div class="edit d-flex gap-2">
                                                            <a href="javascript:void(0);" data-bs-toggle="modal"
                                                                data-bs-target="#edit-card"> <i class="ti ti-edit"></i>
                                                            </a>
                                                            <a href="javascript:void(0);" data-bs-toggle="modal"
                                                                data-bs-target="#delete-card"> <i
                                                                    class="ti ti-trash"></i> </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="settings-card d-block">
                                                <div class="settings-new">
                                                    <div class="settings-img d-flex align-items-center gap-3 mb-3">
                                                        <img src="/frontend/assets/img/payment/gateway-05.png" alt="">
                                                        <h6>
                                                            <span> James Peterson </span>
                                                            Visa •••• 1568
                                                        </h6>
                                                    </div>
                                                    <div
                                                        class="setting-bottom d-flex align-items-center justify-content-between">
                                                        <span class="text-muted text-decoration-underline"> Set as
                                                            default </span>
                                                        <div class="edit d-flex gap-2">
                                                            <a href="javascript:void(0);" data-bs-toggle="modal"
                                                                data-bs-target="#edit-card"> <i class="ti ti-edit"></i>
                                                            </a>
                                                            <a href="javascript:void(0);" data-bs-toggle="modal"
                                                                data-bs-target="#delete-card"> <i
                                                                    class="ti ti-trash"></i> </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4"></div>
                                    </div>
                                </div>
                                <div class="plan-bill-table">
                                    <div class="dashboard-header">
                                        <div class="main-title">
                                            <h3>Invoices</h3>
                                            <div id="tableinfo"></div>
                                        </div>
                                        <div class="head-info">
                                            <p>Total Payments <span class="text-primary">(17)</span></p>
                                        </div>
                                    </div>
                                    <div class="table-responsive custom-table invoice-table">
                                        <table class="table table-stripe">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th class="text-start">ID</th>
                                                    <th>Invoice No</th>
                                                    <th>Billing Date</th>
                                                    <th>Plan</th>
                                                    <th>Status</th>
                                                    <th class="text-end">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-start">1</td>
                                                    <td>
                                                        <a href="#">INV0045</a>
                                                    </td>
                                                    <td>01 Dec 2023 09:00AM</td>
                                                    <td>
                                                        Basic
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge badge-receive bg-success d-inline-flex align-items-center"><i
                                                                class="feather-check me-2"></i>Paid</span>
                                                    </td>
                                                    <td>
                                                        <div class="table-action justify-content-end">
                                                            <a href="javascript:void(0);"><i
                                                                    class="feather-download"></i></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-start">2</td>
                                                    <td>
                                                        <a href="#">INV0044</a>
                                                    </td>
                                                    <td>01 Nov 2023 10:00AM</td>
                                                    <td>
                                                        Basic
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge badge-receive bg-success d-inline-flex align-items-center"><i
                                                                class="feather-check me-2"></i>Paid</span>
                                                    </td>
                                                    <td>
                                                        <div class="table-action justify-content-end">
                                                            <a href="javascript:void(0);"><i
                                                                    class="feather-download"></i></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-start">3</td>
                                                    <td>
                                                        <a href="#">INV0043</a>
                                                    </td>
                                                    <td>01 Oct 2023 09:15AM</td>
                                                    <td>
                                                        Basic
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge badge-receive bg-success d-inline-flex align-items-center"><i
                                                                class="feather-check me-2"></i>Paid</span>
                                                    </td>
                                                    <td>
                                                        <div class="table-action justify-content-end">
                                                            <a href="javascript:void(0);"><i
                                                                    class="feather-download"></i></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-start">4</td>
                                                    <td>
                                                        <a href="#">INV0043</a>
                                                    </td>
                                                    <td>01 Sep 2023 09:30AM</td>
                                                    <td>
                                                        Basic
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge badge-receive bg-success d-inline-flex align-items-center"><i
                                                                class="feather-check me-2"></i>Paid</span>
                                                    </td>
                                                    <td>
                                                        <div class="table-action justify-content-end">
                                                            <a href="javascript:void(0);"><i
                                                                    class="feather-download"></i></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-start">5</td>
                                                    <td>
                                                        <a href="#">INV0041</a>
                                                    </td>
                                                    <td>30 Aug 2023 09:30AM</td>
                                                    <td>
                                                        Basic
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge badge-receive bg-success d-inline-flex align-items-center"><i
                                                                class="feather-check me-2"></i>Paid</span>
                                                    </td>
                                                    <td>
                                                        <div class="table-action justify-content-end">
                                                            <a href="javascript:void(0);"><i
                                                                    class="feather-download"></i></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-start">6</td>
                                                    <td>
                                                        <a href="#">INV0040</a>
                                                    </td>
                                                    <td>25 Aug 2023 07:30AM</td>
                                                    <td>
                                                        Basic
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge badge-receive bg-success d-inline-flex align-items-center"><i
                                                                class="feather-check me-2"></i>Paid</span>
                                                    </td>
                                                    <td>
                                                        <div class="table-action justify-content-end">
                                                            <a href="javascript:void(0);"><i
                                                                    class="feather-download"></i></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-start">7</td>
                                                    <td>
                                                        <a href="#">INV0039</a>
                                                    </td>
                                                    <td>15 Aug 2023 06:30AM</td>
                                                    <td>
                                                        Basic
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge badge-receive bg-success d-inline-flex align-items-center"><i
                                                                class="feather-check me-2"></i>Paid</span>
                                                    </td>
                                                    <td>
                                                        <div class="table-action justify-content-end">
                                                            <a href="javascript:void(0);"><i
                                                                    class="feather-download"></i></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-start">8</td>
                                                    <td>
                                                        <a href="#">INV0038</a>
                                                    </td>
                                                    <td>10 Aug 2023 09:30AM</td>
                                                    <td>
                                                        Basic
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge badge-receive bg-success d-inline-flex align-items-center"><i
                                                                class="feather-check me-2"></i>Paid</span>
                                                    </td>
                                                    <td>
                                                        <div class="table-action justify-content-end">
                                                            <a href="javascript:void(0);"><i
                                                                    class="feather-download"></i></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-start">9</td>
                                                    <td>
                                                        <a href="#">INV0037</a>
                                                    </td>
                                                    <td>01 Aug 2023 11:30AM</td>
                                                    <td>
                                                        Basic
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge badge-receive bg-success d-inline-flex align-items-center"><i
                                                                class="feather-check me-2"></i>Paid</span>
                                                    </td>
                                                    <td>
                                                        <div class="table-action justify-content-end">
                                                            <a href="javascript:void(0);"><i
                                                                    class="feather-download"></i></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-start">10</td>
                                                    <td>
                                                        <a href="#">INV0036</a>
                                                    </td>
                                                    <td>20 Jul 2023 12:30AM</td>
                                                    <td>
                                                        Basic
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge badge-receive bg-success d-inline-flex align-items-center"><i
                                                                class="feather-check me-2"></i>Paid</span>
                                                    </td>
                                                    <td>
                                                        <div class="table-action justify-content-end">
                                                            <a href="javascript:void(0);"><i
                                                                    class="feather-download"></i></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-start">11</td>
                                                    <td>
                                                        <a href="#">INV0035</a>
                                                    </td>
                                                    <td>10 JUl 2023 05:30AM</td>
                                                    <td>
                                                        Basic
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge badge-receive bg-success d-inline-flex align-items-center"><i
                                                                class="feather-check me-2"></i>Paid</span>
                                                    </td>
                                                    <td>
                                                        <div class="table-action justify-content-end">
                                                            <a href="javascript:void(0);"><i
                                                                    class="feather-download"></i></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-start">12</td>
                                                    <td>
                                                        <a href="#">INV0034</a>
                                                    </td>
                                                    <td>01 Jul 2023 09:30AM</td>
                                                    <td>
                                                        Basic
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge badge-receive bg-success d-inline-flex align-items-center"><i
                                                                class="feather-check me-2"></i>Paid</span>
                                                    </td>
                                                    <td>
                                                        <div class="table-action justify-content-end">
                                                            <a href="javascript:void(0);"><i
                                                                    class="feather-download"></i></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-start">13</td>
                                                    <td>
                                                        <a href="#">INV0033</a>
                                                    </td>
                                                    <td>01 Jun 2023 11:30AM</td>
                                                    <td>
                                                        Basic
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge badge-receive bg-success d-inline-flex align-items-center"><i
                                                                class="feather-check me-2"></i>Paid</span>
                                                    </td>
                                                    <td>
                                                        <div class="table-action justify-content-end">
                                                            <a href="javascript:void(0);"><i
                                                                    class="feather-download"></i></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div
                                        class="table-bottom-footer d-sm-flex d-md-flex d-lg-flex align-items-center justify-content-between mt-4">
                                        <div class="dataTables_length" id="DataTables_Table_0_length">
                                            <label>Showing
                                                <select class="form-select form-select-sm">
                                                    <option value="10">10</option>
                                                    <option value="25">25</option>
                                                    <option value="50">50</option>
                                                    <option value="100">100</option>
                                                </select> Results
                                            </label>
                                        </div>
                                        <div class="table-footer mt-0">
                                            <div id="tablepage"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Plan Billing -->

                        <!-- Notification -->
                        <div class="tab-pane fade" id="notifications" role="tabpanel">
                            <div class="notification-info-table">
                                <div class="table-card noti-setting-table custom-setting-table">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        Transactional Notifications
                                                    </th>
                                                    <th class="text-grey fw-regular">
                                                        Push
                                                    </th>
                                                    <th class="text-grey fw-regular">
                                                        Email
                                                    </th>
                                                    <th class="text-grey fw-regular">
                                                        SMS
                                                    </th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr>
                                                    <td>
                                                        Order Confirmation
                                                    </td>
                                                    <td>
                                                        <div class="status-toggle d-flex align-items-center">
                                                            <input type="checkbox" id="toggle14" class="check"
                                                                checked="">
                                                            <label for="toggle14" class="checktoggle"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="status-toggle d-flex align-items-center">
                                                            <input type="checkbox" id="toggle15" class="check"
                                                                checked="">
                                                            <label for="toggle15" class="checktoggle"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="status-toggle d-flex align-items-center">
                                                            <input type="checkbox" id="toggle16" class="check"
                                                                checked="">
                                                            <label for="toggle16" class="checktoggle"></label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Service Requests
                                                    </td>
                                                    <td>
                                                        <div class="status-toggle d-flex align-items-center">
                                                            <input type="checkbox" id="toggle17" class="check"
                                                                checked="">
                                                            <label for="toggle17" class="checktoggle"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="status-toggle d-flex align-items-center">
                                                            <input type="checkbox" id="toggle18" class="check"
                                                                checked="">
                                                            <label for="toggle18" class="checktoggle"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="status-toggle d-flex align-items-center">
                                                            <input type="checkbox" id="toggle19" class="check">
                                                            <label for="toggle19" class="checktoggle"></label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Payment Receipts
                                                    </td>
                                                    <td>
                                                        <div class="status-toggle d-flex align-items-center">
                                                            <input type="checkbox" id="toggle20" class="check"
                                                                checked="">
                                                            <label for="toggle20" class="checktoggle"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="status-toggle d-flex align-items-center">
                                                            <input type="checkbox" id="toggle21" class="check"
                                                                checked="">
                                                            <label for="toggle21" class="checktoggle"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="status-toggle d-flex align-items-center">
                                                            <input type="checkbox" id="toggle22" class="check">
                                                            <label for="toggle22" class="checktoggle"></label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Appointment Reminders
                                                    </td>
                                                    <td>
                                                        <div class="status-toggle d-flex align-items-center">
                                                            <input type="checkbox" id="toggle10" class="check"
                                                                checked="">
                                                            <label for="toggle10" class="checktoggle"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="status-toggle d-flex align-items-center">
                                                            <input type="checkbox" id="toggle11" class="check"
                                                                checked="">
                                                            <label for="toggle11" class="checktoggle"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="status-toggle d-flex align-items-center">
                                                            <input type="checkbox" id="toggle12" class="check"
                                                                checked="">
                                                            <label for="toggle12" class="checktoggle"></label>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="notification-info-table">
                                <div class="table-card noti-setting-table custom-setting-table">
                                    <div class="table-responsive">
                                        <table class="table">

                                            <thead>
                                                <tr>
                                                    <th>
                                                        User Engagement Notifications
                                                    </th>
                                                    <th class="text-grey fw-regular">
                                                        Push
                                                    </th>
                                                    <th class="text-grey fw-regular">
                                                        Email
                                                    </th>
                                                    <th class="text-grey fw-regular">
                                                        SMS
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        Profile Completion Reminder
                                                    </td>
                                                    <td>
                                                        <div class="status-toggle d-flex align-items-center">
                                                            <input type="checkbox" id="toggle-1" class="check"
                                                                checked="">
                                                            <label for="toggle-1" class="checktoggle"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="status-toggle d-flex align-items-center">
                                                            <input type="checkbox" id="toggle-2" class="check"
                                                                checked="">
                                                            <label for="toggle-2" class="checktoggle"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="status-toggle d-flex align-items-center">
                                                            <input type="checkbox" id="toggle-3" class="check"
                                                                checked="">
                                                            <label for="toggle-3" class="checktoggle"></label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Feedback or Survey Requests
                                                    </td>
                                                    <td>
                                                        <div class="status-toggle d-flex align-items-center">
                                                            <input type="checkbox" id="toggle-4" class="check"
                                                                checked="">
                                                            <label for="toggle-4" class="checktoggle"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="status-toggle d-flex align-items-center">
                                                            <input type="checkbox" id="toggle-5" class="check"
                                                                checked="">
                                                            <label for="toggle-5" class="checktoggle"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="status-toggle d-flex align-items-center">
                                                            <input type="checkbox" id="toggle-6" class="check">
                                                            <label for="toggle-6" class="checktoggle"></label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Achievements
                                                    </td>
                                                    <td>
                                                        <div class="status-toggle d-flex align-items-center">
                                                            <input type="checkbox" id="toggle-7" class="check"
                                                                checked="">
                                                            <label for="toggle-7" class="checktoggle"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="status-toggle d-flex align-items-center">
                                                            <input type="checkbox" id="toggle-8" class="check"
                                                                checked="">
                                                            <label for="toggle-8" class="checktoggle"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="status-toggle d-flex align-items-center">
                                                            <input type="checkbox" id="toggle-9" class="check">
                                                            <label for="toggle-9" class="checktoggle"></label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Suggestions
                                                    </td>
                                                    <td>
                                                        <div class="status-toggle d-flex align-items-center">
                                                            <input type="checkbox" id="toggle-10" class="check"
                                                                checked="">
                                                            <label for="toggle-10" class="checktoggle"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="status-toggle d-flex align-items-center">
                                                            <input type="checkbox" id="toggle-11" class="check"
                                                                checked="">
                                                            <label for="toggle-11" class="checktoggle"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="status-toggle d-flex align-items-center">
                                                            <input type="checkbox" id="toggle-12" class="check"
                                                                checked="">
                                                            <label for="toggle-12" class="checktoggle"></label>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="notification-info-table">
                                <div class="table-card noti-setting-table custom-setting-table">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        System Notifications
                                                    </th>
                                                    <th class="text-grey fw-regular">
                                                        Push
                                                    </th>
                                                    <th class="text-grey fw-regular">
                                                        Email
                                                    </th>
                                                    <th class="text-grey fw-regular">
                                                        SMS
                                                    </th>
                                                </tr>

                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        System Maintenance Schedules
                                                    </td>
                                                    <td>
                                                        <div class="status-toggle d-flex align-items-center">
                                                            <input type="checkbox" id="toggles-1" class="check"
                                                                checked="">
                                                            <label for="toggles-1" class="checktoggle"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="status-toggle d-flex align-items-center">
                                                            <input type="checkbox" id="toggles-2" class="check"
                                                                checked="">
                                                            <label for="toggles-2" class="checktoggle"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="status-toggle d-flex align-items-center">
                                                            <input type="checkbox" id="toggles-3" class="check"
                                                                checked="">
                                                            <label for="toggles-3" class="checktoggle"></label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Updates
                                                    </td>
                                                    <td>
                                                        <div class="status-toggle d-flex align-items-center">
                                                            <input type="checkbox" id="toggles-4" class="check"
                                                                checked="">
                                                            <label for="toggles-4" class="checktoggle"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="status-toggle d-flex align-items-center">
                                                            <input type="checkbox" id="toggles-5" class="check"
                                                                checked="">
                                                            <label for="toggles-5" class="checktoggle"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="status-toggle d-flex align-items-center">
                                                            <input type="checkbox" id="toggles-6" class="check">
                                                            <label for="toggles-6" class="checktoggle"></label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Security Alerts
                                                    </td>
                                                    <td>
                                                        <div class="status-toggle d-flex align-items-center">
                                                            <input type="checkbox" id="toggles-7" class="check"
                                                                checked="">
                                                            <label for="toggles-7" class="checktoggle"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="status-toggle d-flex align-items-center">
                                                            <input type="checkbox" id="toggles-8" class="check"
                                                                checked="">
                                                            <label for="toggles-8" class="checktoggle"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="status-toggle d-flex align-items-center">
                                                            <input type="checkbox" id="toggles-9" class="check">
                                                            <label for="toggles-9" class="checktoggle"></label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Service Availability
                                                    </td>
                                                    <td>
                                                        <div class="status-toggle d-flex align-items-center">
                                                            <input type="checkbox" id="toggles-10" class="check"
                                                                checked="">
                                                            <label for="toggles-10" class="checktoggle"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="status-toggle d-flex align-items-center">
                                                            <input type="checkbox" id="toggles-11" class="check"
                                                                checked="">
                                                            <label for="toggles-11" class="checktoggle"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="status-toggle d-flex align-items-center">
                                                            <input type="checkbox" id="toggles-12" class="check"
                                                                checked="">
                                                            <label for="toggles-12" class="checktoggle"></label>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Notification -->

                        <!-- Integration -->
                        <div class="tab-pane fade" id="integration" role="tabpanel">
                            <div class="notification-info-table">
                                <div class="table-card integrated-table custom-setting-table">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        SMS Gateway Integrations
                                                    </th>
                                                    <th>

                                                    </th>
                                                    <th>

                                                    </th>
                                                    <th>

                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="custom-first-row">
                                                    <td>
                                                        <div class="d-flex align-items-center integration-name">
                                                            <span class="integration-icon">
                                                                <img src="/frontend/assets/img/gateway/gateway-01.svg"
                                                                    alt="">
                                                            </span>
                                                            <h6> Nexmo </h6>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-soft-secondary new-badge text-success"> <i
                                                                class="ti ti-point-filled me-1"></i> Connected</span>
                                                    </td>
                                                    <td>
                                                        <a href="#" class="settings-modal" data-bs-toggle="modal"
                                                            data-bs-target="#integration_popup"><i
                                                                class="feather-settings"></i></a>
                                                    </td>
                                                    <td>
                                                        <div class="status-toggle d-flex align-items-center">
                                                            <input type="checkbox" id="toggle-13" class="check"
                                                                checked="">
                                                            <label for="toggle-13" class="checktoggle"></label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center integration-name">
                                                            <span class="integration-icon">
                                                                <img src="/frontend/assets/img/gateway/gateway-02.svg"
                                                                    alt="">
                                                            </span>
                                                            <h6> 2Factor </h6>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-soft-secondary new-badge text-success"> <i
                                                                class="ti ti-point-filled me-1"></i>Connected</span>
                                                    </td>
                                                    <td>
                                                        <a href="#" class="settings-modal" data-bs-toggle="modal"
                                                            data-bs-target="#integration_popup"><i
                                                                class="feather-settings"></i></a>
                                                    </td>
                                                    <td>
                                                        <div class="status-toggle d-flex align-items-center">
                                                            <input type="checkbox" id="toggle-14" class="check"
                                                                checked="">
                                                            <label for="toggle-14" class="checktoggle"></label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center integration-name">
                                                            <span class="integration-icon">
                                                                <img src="/frontend/assets/img/gateway/gateway-03.svg"
                                                                    alt="">
                                                            </span>
                                                            <h6> Twilio </h6>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-soft-secondary new-badge text-success"> <i
                                                                class="ti ti-point-filled me-1"></i>Connected</span>
                                                    </td>
                                                    <td>
                                                        <a href="#" class="settings-modal" data-bs-toggle="modal"
                                                            data-bs-target="#integration_popup"><i
                                                                class="feather-settings"></i></a>
                                                    </td>
                                                    <td>
                                                        <div class="status-toggle d-flex align-items-center">
                                                            <input type="checkbox" id="toggle-15" class="check">
                                                            <label for="toggle-15" class="checktoggle"></label>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="notification-info-table">
                                <div class="table-card integrated-table custom-setting-table">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        Email Integrations
                                                    </th>
                                                    <th>

                                                    </th>
                                                    <th>

                                                    </th>
                                                    <th>

                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="custom-first-row">
                                                    <td>
                                                        <div class="d-flex align-items-center integration-name">
                                                            <span class="integration-icon">
                                                                <img src="/frontend/assets/img/gateway/gateway-04.svg"
                                                                    alt="">
                                                            </span>
                                                            <h6>SendGrid</h6>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <span class="badge bg-soft-secondary new-badge text-success"> <i
                                                                class="ti ti-point-filled me-1"></i>Connected</span>
                                                    </td>
                                                    <td>
                                                        <a href="#" class="settings-modal" data-bs-toggle="modal"
                                                            data-bs-target="#integration_popup"><i
                                                                class="feather-settings"></i></a>
                                                    </td>
                                                    <td>
                                                        <div class="status-toggle d-flex align-items-center">
                                                            <input type="checkbox" id="toggle-16" class="check"
                                                                checked="">
                                                            <label for="toggle-16" class="checktoggle"></label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center integration-name">
                                                            <span class="integration-icon">
                                                                <img src="/frontend/assets/img/gateway/gateway-05.svg"
                                                                    alt="">
                                                            </span>
                                                            <h6>PHP Mailer</h6>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <span class="badge bg-soft-secondary new-badge text-success"> <i
                                                                class="ti ti-point-filled me-1"></i>Connected</span>
                                                    </td>
                                                    <td>
                                                        <a href="#" class="settings-modal" data-bs-toggle="modal"
                                                            data-bs-target="#integration_popup"><i
                                                                class="feather-settings"></i></a>
                                                    </td>
                                                    <td>
                                                        <div class="status-toggle d-flex align-items-center">
                                                            <input type="checkbox" id="toggle-17" class="check"
                                                                checked="">
                                                            <label for="toggle-17" class="checktoggle"></label>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="notification-info-table">
                                <div class="table-card integrated-table custom-setting-table">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        Payment Gateway Integrations
                                                    </th>
                                                    <th>

                                                    </th>
                                                    <th>

                                                    </th>
                                                    <th>

                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="custom-first-row">
                                                    <td>
                                                        <div class="d-flex align-items-center integration-name">
                                                            <span class="integration-icon">
                                                                <img src="/frontend/assets/img/gateway/gateway-09.svg"
                                                                    alt="">
                                                            </span>
                                                            <h6> Paypal </h6>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-soft-secondary new-badge text-success"> <i
                                                                class="ti ti-point-filled me-1"></i>Connected</span>
                                                    </td>
                                                    <td>
                                                        <a href="#" class="settings-modal" data-bs-toggle="modal"
                                                            data-bs-target="#integration_popup"><i
                                                                class="feather-settings"></i></a>
                                                    </td>
                                                    <td>
                                                        <div class="status-toggle d-flex align-items-center">
                                                            <input type="checkbox" id="toggle-18" class="check"
                                                                checked="">
                                                            <label for="toggle-18" class="checktoggle"></label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center integration-name">
                                                            <span class="integration-icon">
                                                                <img src="/frontend/assets/img/gateway/gateway-10.svg"
                                                                    alt="">
                                                            </span>
                                                            <h6> Stripe </h6>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-soft-secondary new-badge text-success"> <i
                                                                class="ti ti-point-filled me-1"></i>Connected</span>
                                                    </td>
                                                    <td>
                                                        <a href="#" class="settings-modal" data-bs-toggle="modal"
                                                            data-bs-target="#integration_popup"><i
                                                                class="feather-settings"></i></a>
                                                    </td>
                                                    <td>
                                                        <div class="status-toggle d-flex align-items-center">
                                                            <input type="checkbox" id="toggle-19" class="check"
                                                                checked="">
                                                            <label for="toggle-19" class="checktoggle"></label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center integration-name">
                                                            <span class="integration-icon">
                                                                <img src="/frontend/assets/img/gateway/gateway-11.svg"
                                                                    alt="">
                                                            </span>
                                                            <h6> Visa </h6>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-soft-danger new-badge text-success"> <i
                                                                class="ti ti-point-filled me-1"></i> Connected</span>
                                                    </td>
                                                    <td>
                                                        <a href="#" class="settings-modal" data-bs-toggle="modal"
                                                            data-bs-target="#integration_popup"><i
                                                                class="feather-settings"></i></a>
                                                    </td>
                                                    <td>
                                                        <div class="status-toggle d-flex align-items-center">
                                                            <input type="checkbox" id="toggle-20" class="check">
                                                            <label for="toggle-20" class="checktoggle"></label>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="notification-info-table">
                                <div class="table-card integrated-table custom-setting-table">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        Social Media Integrations
                                                    </th>
                                                    <th>

                                                    </th>
                                                    <th>

                                                    </th>
                                                    <th>

                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="custom-first-row">
                                                    <td>
                                                        <div class="d-flex align-items-center integration-name">
                                                            <span class="integration-icon">
                                                                <img src="/frontend/assets/img/gateway/gateway-06.svg"
                                                                    alt="">
                                                            </span>
                                                            <h6>Facebook</h6>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <span class="badge bg-soft-secondary new-badge text-success"> <i
                                                                class="ti ti-point-filled me-1"></i>Connected</span>
                                                    </td>
                                                    <td>
                                                        <a href="#" class="settings-modal" data-bs-toggle="modal"
                                                            data-bs-target="#integration_popup"><i
                                                                class="feather-settings"></i></a>
                                                    </td>
                                                    <td>
                                                        <div class="status-toggle d-flex align-items-center">
                                                            <input type="checkbox" id="toggle-21" class="check"
                                                                checked="">
                                                            <label for="toggle-21" class="checktoggle"></label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center integration-name">
                                                            <span class="integration-icon">
                                                                <img src="/frontend/assets/img/gateway/gateway-07.svg"
                                                                    alt="">
                                                            </span>
                                                            <h6>Twitter</h6>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <span class="badge bg-soft-secondary new-badge text-success"> <i
                                                                class="ti ti-point-filled me-1"></i>Connected</span>
                                                    </td>
                                                    <td>
                                                        <a href="#" class="settings-modal" data-bs-toggle="modal"
                                                            data-bs-target="#integration_popup"><i
                                                                class="feather-settings"></i></a>
                                                    </td>
                                                    <td>
                                                        <div class="status-toggle d-flex align-items-center">
                                                            <input type="checkbox" id="toggle-22" class="check"
                                                                checked="">
                                                            <label for="toggle-22" class="checktoggle"></label>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Integration -->
                    </div>
                    <!-- /Page Content -->
                </div>

            </div>
        </div>
    </div>
</div>
<!-- /Page Content -->

<!-- Plans Modal -->
<div class="modal new-modal fade" id="plans-modal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upgrade Plan</h5>
                <button type="button" class="close-btn" data-bs-dismiss="modal"><i class="ti ti-x"></i></button>
            </div>
            <div class="modal-body service-modal pb-0">
                <div class="text-center mb-3">
                    <div
                        class="enable-item d-inline-flex align-items-center justify-content-center bg-light px-3 py-2 rounded-pill">
                        <label class="mb-0 me-2">Monthly</label>
                        <div class="form-check form-switch check-on m-0">
                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked">
                        </div>
                        <label class="mb-0">Yearly</label>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-xl-4 col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <p class="mb-3"><strong>Basic</strong></p>
                                <div class="d-flex align-items-center flex-wrap gap-2 mb-3">
                                    <h3 class="mb-0">$99</h3>
                                    <span>/month</span>
                                    <span class="badge bg-info text-white p-1 rounded-2">Only 10 Users</span>
                                </div>
                                <p class="mb-3">Best for Freelancers & small businesses needs simple invoicing.</p>
                                <a href="#" class="buy-plan-btn mb-3" data-bs-toggle="modal"
                                    data-bs-target="#checkout-modal"><i class="ti ti-shopping-cart me-2"></i>Buy
                                    Plan</a>
                                <p class="text-center mb-3"><small>FEATURES</small></p>
                                <p class="d-flex align-items-center mb-2"><i
                                        class="ti ti-circle-check-filled me-2 text-success"></i>10 Staffs</p>
                                <p class="d-flex align-items-center mb-2"><i
                                        class="ti ti-circle-check-filled me-2 text-success"></i>50 Listings / Services
                                </p>
                                <p class="d-flex align-items-center mb-2"><i
                                        class="ti ti-circle-check-filled me-2 text-success"></i>20 Orders / Jobs</p>
                                <p class="d-flex align-items-center mb-2"><i
                                        class="ti ti-circle-check-filled me-2 text-success"></i>Limited Time Support</p>
                                <p class="d-flex align-items-center mb-2"><i
                                        class="ti ti-circle-check-filled me-2 text-success"></i>5 Product Page
                                    Optimizations</p>
                                <p class="d-flex align-items-center mb-2"><i
                                        class="ti ti-circle-check-filled me-2 text-success"></i>5 High-Quality Backlinks
                                </p>
                                <p class="d-flex align-items-center"><i
                                        class="ti ti-circle-check-filled me-2 text-success"></i>Portfolio</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6">
                        <div class="card">
                            <span class="badge popular-badge">Most Popular</span>
                            <div class="card-body">
                                <p class="mb-3"><strong>Standard</strong></p>
                                <div class="d-flex align-items-center flex-wrap gap-2 mb-3">
                                    <h3 class="mb-0">$199</h3>
                                    <span>/month</span>
                                    <span class="badge bg-info text-white p-1 rounded-2">Only 25 Users</span>
                                </div>
                                <p class="mb-3">Growing businesses managing recurring invoices & reports.</p>
                                <a href="#" class="buy-plan-btn mb-3" data-bs-toggle="modal"
                                    data-bs-target="#checkout-modal"><i class="ti ti-shopping-cart me-2"></i>Buy
                                    Plan</a>
                                <p class="text-center mb-3"><small>FEATURES</small></p>
                                <p class="d-flex align-items-center mb-2"><i
                                        class="ti ti-circle-check-filled me-2 text-success"></i>20 Staffs</p>
                                <p class="d-flex align-items-center mb-2"><i
                                        class="ti ti-circle-check-filled me-2 text-success"></i>100 Listings / Services
                                </p>
                                <p class="d-flex align-items-center mb-2"><i
                                        class="ti ti-circle-check-filled me-2 text-success"></i>50 Orders / Jobs</p>
                                <p class="d-flex align-items-center mb-2"><i
                                        class="ti ti-circle-check-filled me-2 text-success"></i>24/7 Customer Support
                                </p>
                                <p class="d-flex align-items-center mb-2"><i
                                        class="ti ti-circle-check-filled me-2 text-success"></i>15 Product Page
                                    Optimizations</p>
                                <p class="d-flex align-items-center mb-2"><i
                                        class="ti ti-circle-check-filled me-2 text-success"></i>10 High-Quality
                                    Backlinks</p>
                                <p class="d-flex align-items-center"><i
                                        class="ti ti-circle-check-filled me-2 text-success"></i>Portfolio</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <p class="mb-3"><strong>Professional</strong></p>
                                <div class="d-flex align-items-center flex-wrap gap-2 mb-3">
                                    <h3 class="mb-0">$299</h3>
                                    <span>/month</span>
                                    <span class="badge bg-info text-white p-1 rounded-2">Only 50 Users</span>
                                </div>
                                <p class="mb-3">Best for Large sales teams requiring automation & integrations.</p>
                                <a href="#" class="buy-plan-btn mb-3" data-bs-toggle="modal"
                                    data-bs-target="#checkout-modal"><i class="ti ti-shopping-cart me-2"></i>Buy
                                    Plan</a>
                                <p class="text-center mb-3"><small>FEATURES</small></p>
                                <p class="d-flex align-items-center mb-2"><i
                                        class="ti ti-circle-check-filled me-2 text-success"></i>Unlimited Staffs</p>
                                <p class="d-flex align-items-center mb-2"><i
                                        class="ti ti-circle-check-filled me-2 text-success"></i>Unlimited Listings /
                                    Services</p>
                                <p class="d-flex align-items-center mb-2"><i
                                        class="ti ti-circle-check-filled me-2 text-success"></i>Unlimited Orders / Jobs
                                </p>
                                <p class="d-flex align-items-center mb-2"><i
                                        class="ti ti-circle-check-filled me-2 text-success"></i>24/7 Customer Support
                                </p>
                                <p class="d-flex align-items-center mb-2"><i
                                        class="ti ti-circle-check-filled me-2 text-success"></i>30 Product Page
                                    Optimizations</p>
                                <p class="d-flex align-items-center mb-2"><i
                                        class="ti ti-circle-check-filled me-2 text-success"></i>15 High-Quality
                                    Backlinks</p>
                                <p class="d-flex align-items-center"><i
                                        class="ti ti-circle-check-filled me-2 text-success"></i>Portfolio</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Plans Modal -->

<div class="modal new-modal fade" id="checkout-modal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Checkout</h5>
                <button type="button" class="close-btn" data-bs-dismiss="modal"><i class="ti ti-x"></i></button>
            </div>
            <div class="modal-body pb-2">
                <div class="row">
                    <div class="col-lg-6">
                        <h6 class="mb-3">Basic Information</h6>
                        <div class="mb-4 border-bottom">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="mb-1 fw-medium text-dark">First Name <span
                                                class="text-primary">*</span></label>
                                        <input type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="mb-1 fw-medium text-dark">Last Name <span
                                                class="text-primary">*</span></label>
                                        <input type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="mb-1 fw-medium text-dark"> Email <span
                                                class="text-primary">*</span></label>
                                        <input type="email" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-wrap">
                                        <label class="mb-1 fw-medium text-dark">Phone number <span
                                                class="text-primary">*</span></label>
                                        <input type="tel" class="form-control" id="phone4" name="phone4">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h6 class="mb-3">Address Information</h6>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="mb-1 fw-medium text-dark">Address<span
                                            class="text-primary">*</span></label>
                                    <input type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label mb-1 fw-medium text-dark">Country<span
                                            class="text-primary">*</span></label>
                                    <select class="select">
                                        <option>Select</option>
                                        <option>Canada</option>
                                        <option>Germany</option>
                                        <option>France</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label mb-1 fw-medium text-dark">State<span
                                            class="text-primary">*</span></label>
                                    <select class="select">
                                        <option>Select</option>
                                        <option>California</option>
                                        <option>Newyork</option>
                                        <option>Texas</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label mb-1 fw-medium text-dark">City<span
                                            class="text-primary">*</span></label>
                                    <select class="select">
                                        <option>Select</option>
                                        <option>Los Angeles</option>
                                        <option>Fresno</option>
                                        <option>San Fransisco</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="mb-1 fw-medium text-dark">Postal Code<span
                                            class="text-primary">*</span></label>
                                    <input type="text" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 d-flex">
                        <div
                            class="flex-fill w-100 rounded-2 bg-light p-3 mb-3 d-flex justify-content-between flex-column">
                            <div class="card w-100 subscription-details">
                                <div class="card-body">
                                    <h6 class="mb-3">Subscription Details</h6>
                                    <ul>
                                        <li class="mb-2">Plan Name <span class="float-end">Basic</span></li>
                                        <li class="mb-2">Plan Amount <span class="float-end">$99.00</span></li>
                                        <li class="mb-2">Tax <span class="float-end">$0.00</span></li>
                                        <li>Total <span class="float-end">$99.00</span></li>
                                    </ul>
                                </div>
                            </div>
                            <div>
                                <div class="d-flex align-item-center justify-content-center mb-4">
                                    <img src="/frontend/assets//img/icons/shield-lock.svg" alt="img"
                                        class="img-fluid flex-shrink-0 me-2">
                                    <div>
                                        <h6 class="mb-0">100% Cashback Guarantee</h6>
                                        <span>We Protect Your Money</span>
                                    </div>
                                </div>
                                <a href="#" class="btn btn-primary w-100" data-bs-toggle="modal"
                                    data-bs-target="#payment-successful">Pay $99.00</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- payment successful -->
<div class="modal new-modal fade" id="payment-successful" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-body text-center">
                <img src="/frontend/assets//img/icons/success-icon.svg" alt="img" class="mb-4">
                <h6 class="mb-2">Payment Successful</h6>
                <p class="mb-4">Your purchase of the Basic Plan has been completed with Reference Number #12559845</p>
                <div class="modal-btn d-flex align-items-center">
                    <button type="button" class="close-btn btn-light btn-sm btn border-0 w-100 me-3"
                        data-bs-toggle="modal" data-bs-target="#plans-modal">Back to Plans</button>
                    <button class="btn btn-primary btn-sm w-100" type="submit">Purchase details</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- payment successful -->

<!-- add card  -->
<div class="modal new-modal fade" id="add-card" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered model-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Card</h5>
                <button type="button" class="close-btn" data-bs-dismiss="modal"><i class="ti ti-x"></i></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="mb-1 fw-medium text-dark">Name on the Card <span
                                    class="text-primary">*</span></label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="mb-1 fw-medium text-dark">Card Number <span
                                    class="text-primary">*</span></label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="mb-1 fw-medium text-dark">Expiration Date <span
                                    class="text-primary">*</span></label>
                            <input type="date" class="form-control datetimepicker">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="mb-1 fw-medium text-dark">CVV <span class="text-primary">*</span></label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-btn d-flex gap-3">
                    <a href="javascript:void(0);" class="btn btn-light w-100 text-dark"
                        data-bs-dismiss="modal">Cancel</a>
                    <a href="javascript:void(0);" class="btn btn-primary w-100">Add Cart</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /add card -->

<!-- add card  -->
<div class="modal new-modal fade" id="edit-card" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered model-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Card</h5>
                <button type="button" class="close-btn" data-bs-dismiss="modal"><i class="ti ti-x"></i></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="mb-1 fw-medium text-dark">Name on the Card <span
                                    class="text-primary">*</span></label>
                            <input type="text" class="form-control" value="Kevin Reynolds">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="mb-1 fw-medium text-dark">Card Number <span
                                    class="text-primary">*</span></label>
                            <input type="text" class="form-control" value="5396 5250 1908 1568">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="mb-1 fw-medium text-dark">Expiration Date <span
                                    class="text-primary">*</span></label>
                            <input type="date" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="mb-1 fw-medium text-dark">CVV <span class="text-primary">*</span></label>
                            <input type="text" class="form-control" value="556">
                        </div>
                    </div>
                </div>
                <div class="modal-btn d-flex gap-3">
                    <a href="javascript:void(0);" class="btn btn-light w-100 text-dark"
                        data-bs-dismiss="modal">Cancel</a>
                    <a href="javascript:void(0);" class="btn btn-primary w-100">Save Changes</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /add card -->


<!-- delete card -->
<div class="modal new-modal fade" id="delete-card" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center">
                <img src="/frontend/assets//img/icons/delete-vector.svg" alt="img" class="mb-4">
                <h6 class="mb-4">Are you sure want to permanently delete this card?</h6>
                <div class="modal-btn d-flex align-items-center">
                    <button type="button" class="close-btn btn-light btn-sm btn border-0 w-100 me-3"
                        data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary btn-sm w-100" type="submit">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Delete details -->

<!-- Delete Review  -->
<div class="modal new-modal fade" id="delete_account" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered notify-modal remove-modal">
        <div class="modal-content">
            <div class="modal-body service-modal">
                <div class="sumary-widget text-center">
                    <div class="summary-info">
                        <img src="/frontend/assets/img/delete-notify.png" alt="delete-notify"
                            class="img-fluid img1 mb-4">
                        <h6 class="mb-1 text-center"> {{__('web.user.are_you_sure')}} </h6>
                        <p class="mb-4"> {{__('web.user.want_to_delete_account')}} </p>
                        <div class="delete-btn d-flex align-item-center justify-content-between gap-2">
                            <a href="" class="btn btn-light w-100 bg-light close-btn" data-bs-dismiss="modal">
                                {{__('web.common.cancel')}} </a>
                            <button class="btn btn-primary w-100 close-btn delete_account_confirm">
                                {{__('web.common.delete')}} </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Delete details -->

<!-- Password Modal  -->
<div class="modal new-modal fade" id="password_update" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{__('web.user.change_password')}}</h5><button type="button" class="close-btn"
                    data-bs-dismiss="modal"><span>×</span></button>
            </div>
            <form id="changePasswordForm" autocomplete="off">
                <div class="modal-body service-modal">
                    <div class="form-wrap form-focus">
                        <label class="mb-1 fw-medium text-dark">{{__('web.user.current_password')}} </label>
                        <input type="text" class="form-control" name="current_password" id="current_password">
                        <span id="current_password_error" class="text-danger error-text"></span>
                    </div>
                    <div class="form-wrap form-focus">
                        <label class="mb-1 fw-medium text-dark">{{__('web.user.new_password')}} </label>
                        <input type="text" class="form-control" name="new_password" id="new_password">
                        <span id="new_password_error" class="text-danger error-text"></span>
                    </div>
                    <div class="form-wrap form-focus">
                        <label class="mb-1 fw-medium text-dark">{{__('web.user.confirm_password')}} </label>
                        <input type="text" class="form-control" name="confirm_password" id="confirm_password">
                        <span id="confirm_password_error" class="text-danger error-text"></span>
                    </div>
                    <div class="modal-btn d-flex gap-3 pt-3">
                        <a href="javascript:void(0);"
                            class="btn btn-light w-100 text-dark">{{__('web.common.cancel')}}</a>
                        <button type="submit"
                            class="btn btn-primary w-100 change_password_btn">{{__('web.common.save_changes')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /Password Modal -->

<!-- Device Management Modal  -->
<div class="modal new-modal fade" id="device_management_modal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{__('web.user.device_management')}}</h5><button type="button" class="close-btn"
                    data-bs-dismiss="modal"><span>×</span></button>
            </div>
            <div class="modal-body service-modal">
                <div class="table-responsive custom-table invoice-table">
                    <table class="table table-stripe" id="userDevicesTable">
                        <thead class="thead-light">
                            <tr>
                                <th>{{__('web.user.device')}}</th>
                                <th>{{__('web.user.date')}}</th>
                                <th>{{__('web.user.ip_address')}}</th>
                                <th>{{__('web.user.location')}}</th>
                                <th>{{__('web.common.action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Device Management Modal -->

<!-- Payment integration Modal  -->
<div class="modal new-modal fade" id="integration_popup" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Paypal Configuartion</h5><button type="button" class="close-btn"
                    data-bs-dismiss="modal"><span>×</span></button>
            </div>
            <div class="modal-body service-modal">
                <div class="form-wrap form-focus">
                    <label class="mb-1 fw-medium text-dark">API Key <span class="text-primary">*</span> </label>
                    <input type="text" class="form-control">
                </div>
                <div class="form-wrap form-focus">
                    <label class="mb-1 fw-medium text-dark">API Secret Key<span class="text-primary">*</span> </label>
                    <input type="text" class="form-control">
                </div>
                <div class="form-wrap form-focus">
                    <label class="mb-1 fw-medium text-dark">Sender ID<span class="text-primary">*</span> </label>
                    <input type="text" class="form-control">
                </div>
                <div class="form-wrap gig-option">
                    <label class="mb-1 fw-medium text-dark d-block mb-2"> Status <span
                            class="text-primary">*</span></label>
                    <label class="custom_radio">
                        <input type="radio" name="buyer" checked>
                        <span class="checkmark"></span>Active
                    </label>
                    <label class="custom_radio">
                        <input type="radio" name="buyer">
                        <span class="checkmark"></span>In Active
                    </label>
                </div>

                <div class="modal-btn d-flex gap-3 pt-3">
                    <a href="javascript:void(0);" class="btn btn-light w-100 text-dark">Cancel</a>
                    <a href="javascript:void(0);" class="btn btn-primary w-100">Submit</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Payment integration Modal -->

@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('frontend/assets/plugins/intltelinput/css/intlTelInput.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css') }}">
@endpush

@push('plugins')
<script src="{{ asset('frontend/assets/plugins/intltelinput/js/intlTelInput.js') }}"></script>
<script src="{{ asset('frontend/assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js') }}"></script>
@endpush

@push('scripts')
<script src="{{ asset('frontend/custom/js/seller-settings.js') }}"></script>
@endpush
