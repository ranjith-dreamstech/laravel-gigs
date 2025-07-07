@extends('frontend.buyer.partials.app')
@section('content')

<!-- Page Content -->
<div class="page-wrapper">
    <div class="page-content content bg-light">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="main-title mb-4">
                    <h4>{{ __('web.common.settings') }}</h4>
                </div>

                <div class="settings-info bg-white rounded-2">
                    <div class="settings-page-lists">
                        <ul class="settings-head nav nav-tabs rounded-0 bg-transparent">
                            <li class="nav-item">
                                <a href="javascript:void(0);" class="nav-link active" data-bs-toggle="tab" data-bs-target="#Personal_Information" aria-selected="false" role="tab" tabindex="-1">
                                    {{ __('web.user.profile') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="javascript:void(0);" class="nav-link" data-bs-toggle="tab" data-bs-target="#acc_settings" aria-selected="false" role="tab" tabindex="-1">
                                    {{ __('web.user.account_settings') }}
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
                                                <img src="{{ $user->userDetail->profile_image ?? asset('backend/assets/img/default-profile.png') }}" id="imagePreview" alt="">
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
                                                    <label class="form-label"> {{ __('web.user.first_name') }} <span class="text-primary">*</span></label>
                                                    <input type="text" class="form-control" id="first_name" name="first_name" value="{{ $user->userDetail->first_name ?? '' }}">
                                                    <span id="first_name_error" class="text-danger error-text"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-wrap">
                                                    <label class="form-label"> {{ __('web.user.last_name') }} <span class="text-primary">*</span></label>
                                                    <input type="text" class="form-control" id="last_name" name="last_name" value="{{ $user->userDetail->last_name ?? '' }}">
                                                    <span id="last_name_error" class="text-danger error-text"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-wrap">
                                                    <label class="form-label"> {{ __('web.user.email') }} <span class="text-primary">*</span></label>
                                                    <input type="email" class="form-control" id="email" name="email" value="{{ $user->email ?? '' }}" readonly>
                                                    <span id="email_error" class="text-danger error-text"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-wrap">
                                                    <label class="form-label"> {{ __('web.user.phone_number') }} <span class="text-primary">*</span></label>
                                                    <input type="text" class="form-control user_phone_number" id="phone_number" name="phone_number" value="{{ $user->phone_number ?? '' }}">
                                                    <input type="hidden" id="international_phone_number" name="international_phone_number" value="{{ $user->phone_number ?? '' }}">
                                                    <span id="phone_number_error" class="text-danger error-text"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-wrap">
                                                    <label class="form-label"> {{ __('web.user.date_of_birth') }} <span class="text-primary">*</span></label>
                                                    <input type="date" class="form-control" id="dob" name="dob" value="{{ $user->userDetail->dob ?? '' }}" max="{{ date('Y-m-d', strtotime('-1 day')) }}">
                                                    <span id="dob_error" class="text-danger error-text"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-wrap gig-option">
                                                    <label class="mb-1 fw-medium text-dark d-block mb-2"> {{ __('web.user.gender') }} <span class="text-primary">*</span></label>
                                                    <label class="custom_radio">
                                                        <input type="radio" name="gender" value="male" {{ ($user->userDetail->gender ?? '') == 'male' ? 'checked' : '' }}>
                                                        <span class="checkmark"></span>{{ ucfirst(__('web.user.male')) }}
                                                    </label>
                                                    <label class="custom_radio">
                                                        <input type="radio" name="gender" value="female" {{ ($user->userDetail->gender ?? '') == 'female' ? 'checked' : '' }}>
                                                        <span class="checkmark"></span>{{ ucfirst(__('web.user.female')) }}
                                                    </label>
                                                    <span id="gender_error" class="text-danger error-text"></span>
                                                </div>
                                                    
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-wrap">
                                                    <label class="form-label mb-1 fw-medium text-dark">{{ __('web.user.country') }}<span class="text-primary">*</span></label>
                                                    <select class="select2 form-control" name="country" id="country" data-default-id="{{ $user->userDetail->country_id ?? '' }}" data-placeholder="{{ __('web.common.select') }}">
                                                        @if(!empty($countries) && count($countries) > 0)
                                                        @foreach($countries as $country)
                                                            <option value="{{ $country->id }}" @if(Auth::guard('web')->user()->userDetail && Auth::guard('web')->user()->userDetail->country_id == $country->id) selected @endif >{{ $country->name }}</option>
                                                        @endforeach
                                                        @endif
                                                    </select>
                                                    <span id="country_error" class="text-danger error-text"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-wrap">
                                                    <label class="form-label mb-1 fw-medium text-dark">{{ __('web.user.state') }}<span class="text-primary">*</span></label>
                                                    <select class="select2 form-control" name="state" id="state" data-default-id="{{ $user->userDetail->state_id ?? '' }}" data-placeholder="{{ __('web.common.select') }}">
                                                    </select>
                                                    <span id="state_error" class="text-danger error-text"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-wrap">
                                                    <label class="form-label mb-1 fw-medium text-dark">{{ __('web.user.city') }}<span class="text-primary">*</span></label>
                                                    <select class="select2 form-control" name="city" id="city" data-default-id="{{ $user->userDetail->city_id ?? '' }}" data-placeholder="{{ __('web.common.select') }}">
                                                    </select>
                                                    <span id="city_error" class="text-danger error-text"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-wrap mb-0">
                                                    <label class="form-label"> {{ __('web.user.address') }} <span class="text-primary">*</span></label>
                                                    <input type="text" class="form-control" id="address" name="address" value="{{ $user->userDetail->address ?? '' }}">
                                                    <span id="address_error" class="text-danger error-text"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-wrap">
                                                    <label class="form-label"> {{ __('web.user.postal_code') }} <span class="text-primary">*</span></label>
                                                    <input type="text" class="form-control" id="postal_code" name="postal_code" value="{{ $user->userDetail->postal_code ?? '' }}">
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
                                                        <label class="form-label"> {{ __('web.user.current_email') }} <span class="text-primary"></span></label>
                                                        <input type="text" class="form-control" name="current_email" id="current_email">
                                                        <span id="current_email_error" class="text-danger error-text"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-wrap mb-lg-0 mb-md-0">
                                                        <label class="form-label"> {{ __('web.user.new_email') }} <span class="text-primary"></span></label>
                                                        <input type="text" class="form-control" name="new_email" id="new_email">
                                                        <span id="new_email_error" class="text-danger error-text"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-wrap mb-0">
                                                        <label class="form-label"> {{ __('web.user.confirm_email') }} <span class="text-primary"></span></label>
                                                        <input type="text" class="form-control" name="confirm_email" id="confirm_email">
                                                        <span id="confirm_email_error" class="text-danger error-text"></span>
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
                                                        <input type="text" class="form-control" name="job_title" id="job_title" value="{{ $user->userDetail->job_title ?? '' }}">
                                                        <span id="job_title_error" class="text-danger error-text"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-wrap">
                                                        <label class="form-label">{{ __('web.user.language_known') }}</label>
                                                        <div class="input-block input-block-tagsinput mb-1">
                                                            <input type="text" data-role="tagsinput" class="input-tags form-control" name="language_known"  id="language_known" value="{{ $user->userDetail->language_known ?? '' }}">
                                                        </div>
                                                        <span>{{ __('web.user.enter_value_separated_by_comma') }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-wrap">
                                                        <label class="col-form-label">{{ __('web.user.tags') }}</label>
                                                        <div class="input-block input-block-tagsinput mb-1">
                                                            <input type="text" data-role="tagsinput" class="input-tags form-control" name="tags" id="tags" value="{{ $user->userDetail->tags ?? '' }}">
                                                        </div>
                                                        <span>{{ __('web.user.enter_value_separated_by_comma') }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-wrap">
                                                        <label class="col-form-label">{{ __('web.user.skills') }}</label>
                                                        <div class="input-block input-block-tagsinput mb-1">
                                                            <input type="text" data-role="tagsinput" class="input-tags form-control" name="skills" id="skills" value="{{ $user->userDetail->skills ?? '' }}">
                                                        </div>
                                                        <span>{{ __('web.user.enter_value_separated_by_comma') }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-wrap">
                                                        <label class="form-label"> {{ __('web.user.about_me') }} </label>
                                                        <textarea class="form-control text-area" name="about" id="about" placeholder="{{ __('web.user.type_here') }}...">{{ $user->userDetail->about ?? '' }}</textarea>
                                                        <span id="about_error" class="text-danger error-text"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-wrap m-0">
                                                        <label class="form-label"> {{ __('web.user.why_work_with_me') }} </label>
                                                        <textarea class="form-control text-area" name="profile_description" id="profile_description" placeholder="{{ __('web.user.type_here') }}...">{{ $user->userDetail->profile_description ?? '' }}</textarea>
                                                        <span id="profile_description_error" class="text-danger error-text"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                                <div class="settings-card-footer">
                                    <div class="btn-item">
                                        <a href="#" class="btn">{{ __('web.common.cancel') }}</a>
                                        <button class="btn btn-primary profile_savebtn" type="submit">{{ __('web.common.save_changes') }}</button>
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
                                                    <input type="text" class="form-control" name="paypal_email" id="paypal_email" value="{{ $accountSettings['paypal']['paypal_email'] ?? ''}}">
                                                    <span id="paypal_email_error" class="text-danger error-text"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-wrap">
                                                    <label class="form-label"> {{ __('web.user.auth_password') }} </label>
                                                    <input type="text" class="form-control" name="paypal_password" id="paypal_password" value="{{ $accountSettings['paypal']['paypal_password'] ?? ''}}">
                                                    <span id="paypal_password_error" class="text-danger error-text"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="settings-card-footer text-start mt-0">
                                            <button type="submit" class="btn btn-dark text-white paypal_savebtn">{{ __('web.common.save_changes') }} <i class="ti ti-arrow-up-right ms-1"></i></button>
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
                                                    <label class="form-label"> {{ __('web.user.email') }} <span class="text-primary">*</span></label>
                                                    <input type="text" class="form-control" name="stripe_email" id="stripe_email" value="{{ $accountSettings['stripe']['stripe_email'] ?? ''}}">
                                                    <span id="stripe_email_error" class="text-danger error-text"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-wrap">
                                                    <label class="form-label"> {{ __('web.user.auth_password') }} <span class="text-primary">*</span></label>
                                                    <input type="text" class="form-control" name="stripe_password" id="stripe_password" value="{{ $accountSettings['stripe']['stripe_password'] ?? ''}}">
                                                    <span id="stripe_password_error" class="text-danger error-text"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="settings-card-footer text-start mt-0">
                                            <button type="submit" class="btn btn-dark text-white stripe_savebtn">{{ __('web.common.save_changes') }} <i class="ti ti-arrow-up-right ms-1"></i></button>
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
                                                    <label class="form-label"> {{ __('web.user.account_holder_name') }} </label>
                                                    <input type="text" class="form-control" name="account_holder_name" id="account_holder_name" value="{{ $accountSettings['bank_transfer']['account_holder_name'] ?? ''}}">
                                                    <span id="account_holder_name_error" class="text-danger error-text"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-wrap">
                                                    <label class="form-label"> {{ __('web.user.bank_name') }} </label>
                                                    <input type="text" class="form-control" name="bank_name" id="bank_name" value="{{ $accountSettings['bank_transfer']['bank_name'] ?? ''}}">
                                                    <span id="bank_name_error" class="text-danger error-text"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-wrap">
                                                    <label class="form-label"> {{ __('web.user.ifsc_code') }} </label>
                                                    <input type="text" class="form-control" name="ifsc_code" id="ifsc_code" value="{{ $accountSettings['bank_transfer']['ifsc_code'] ?? ''}}">
                                                    <span id="ifsc_code_error" class="text-danger error-text"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-wrap">
                                                    <label class="form-label"> {{ __('web.user.account_number') }} </label>
                                                    <input type="text" class="form-control" name="account_number" id="account_number" value="{{ $accountSettings['bank_transfer']['account_number'] ?? ''}}">
                                                    <span id="account_number_error" class="text-danger error-text"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="settings-card-footer text-start mt-0">
                                            <button type="submit" class="btn btn-dark text-white bank_savebtn">{{ __('web.common.save_changes') }} <i class="ti ti-arrow-up-right ms-1"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- /Bank Transfer -->

                        </div>
                        <!-- /Account Settings  -->
                    </div>
                    <!-- /Page Content -->
                </div>
                
            </div>
        </div>
    </div>
    <!-- /Page Content -->

</div>

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
<script src="{{ asset('frontend/custom/js/buyer-settings.js') }}"></script>
@endpush
