@extends('admin.admin')

@section('meta_title', __('admin.general_settings.profile') . ' || ' . $companyName)

@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content me-0 me-md-0 me-lg-4">
            <x-admin.breadcrumb
                :title="__('admin.general_settings.settings')"
                :breadcrumbs="[
                    __('admin.general_settings.settings') => ''
                ]"
            />
            <!-- Settings Prefix -->
            <div class="row">
                @include('admin.partials.general_settings_side_menu')
                <div class="col-lg-9">
                    <form id="adminProfileForm" action="{{ route('admin.updateprofile-settings') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card profile-setting-section h-100">
                            <div class="card-header">
                                <h5 class="fw-bold">{{ __('admin.general_settings.account_settings') }}</h5>
                            </div>
                            @include('admin.general_settings_loader')
                            <div class="card-body d-none real-card">
                                <h6 class="fw-bold mb-3 ">{{ __('admin.general_settings.basic_information') }}</h6>
                                <div class="row border-bottom mb-3">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="profile_photo" class="form-label">{{ __('admin.general_settings.profile_photo') }}</label>
                                            <div class="d-flex align-items-center flex-wrap row-gap-3 mb-3">
                                                <div class="d-flex align-items-center justify-content-center avatar avatar-xxl me-3 flex-shrink-0 text-dark frames">
                                                    <img src="{{ uploadedAsset('', 'profile') }}" id="profile_photo_preview" class="img-fluid" alt="Profile">
                                                </div>
                                                <div class="profile-upload">
                                                    <div class="profile-uploader d-flex align-items-center">
                                                        <div class="drag-upload-btn btn btn-md btn-dark">
                                                            <i class="ti ti-photo-up fs-14"></i>
                                                            {{ __('admin.common.change') }}
                                                            <input type="file" class="form-control image-sign" id="profile_photo" name="profile_photo" accept="image/*" >
                                                        </div>
                                                    </div>
                                                    <div class="mt-2">
                                                        <p class="fs-14">{{ __('admin.common.recommended_size_is') }}500px x 500px</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <span id="profile_photo_error" class="text-danger error-text"></span>
                                        </div>
                                    </div>
                                    <input type="hidden" class="form-control" id="id" name="id" value="1">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="first_name" class="form-label">{{ __('admin.common.first_name') }}<span class="text-danger ms-1">*</span></label>
                                            <input type="text" class="form-control" id="first_name" name="first_name" maxlength="30">
                                            <span id="first_name_error" class="text-danger error-text"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="last_name" class="form-label">{{ __('admin.common.last_name') }}<span class="text-danger ms-1">*</span></label>
                                            <input type="text" class="form-control" id="last_name" name="last_name" maxlength="30">
                                            <span id="last_name_error" class="text-danger error-text"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">{{ __('admin.general_settings.email_address') }}<span class="text-danger ms-1">*</span></label>
                                            <input type="email" class="form-control" id="email" name="email">
                                            <span id="email_error" class="text-danger error-text"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">{{ __('admin.common.phone_number') }}<span class="text-danger ms-1">*</span></label>
                                            <div class="">
                                                <input type="text" class="form-control admin_phone" id="admin_phone" name="phone" maxlength="15">
                                                <input type="hidden" id="international_phone_number" name="international_phone_number">
                                            </div>
                                            <span id="admin_phone_error" class="text-danger error-text"></span>
                                        </div>
                                    </div>
                                </div>
                                <h6 class="fw-bold mb-3">{{ __('admin.general_settings.address_information') }}</h6>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="address_line" class="form-label">{{ __('admin.general_settings.address_line') }}</label>
                                            <input type="text" class="form-control" id="address_line" name="address_line" maxlength="50">
                                            <span id="address_line_error" class="text-danger error-text"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="country" class="form-label">{{ __('admin.common.country') }}</label>
                                            <div class="">
                                                <select name="country" class="form-control select2" id="country"></select>
                                            </div>
                                            <span id="country_error" class="text-danger error-text"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="state" class="form-label">{{ __('admin.common.state') }} </label>
                                            <div class="">
                                                <select name="state" class="form-control select2" id="state"></select>
                                            </div>
                                            <span id="state_error" class="text-danger error-text"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div>
                                            <label for="city" class="form-label">{{ __('admin.common.city') }} </label>
                                            <div class="">
                                                <select name="city" id="city" class="form-control select2"></select>
                                            </div>
                                            <span id="city_error" class="text-danger error-text"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div>
                                            <label for="postal_code" class="form-label">{{ __('admin.common.postal_code') }}</label>
                                            <input type="text" class="form-control" id="postal_code" name="postal_code" maxlength="6">
                                            <span id="postal_code_error" class="text-danger error-text"></span>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="card-footer d-none real-card">
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('dashboard') }}" class="btn btn-light me-3">{{ __('admin.general_settings.cancel') }}</a>
                                    <button type="submit" class="btn btn-primary">{{ __('admin.general_settings.save_changes') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /Settings Prefix -->
        </div>
        @include('admin.partials.footer')
    </div>
    <!-- /Page Wrapper -->
    
@endsection
@push('scripts')
<script src="{{ asset('backend/assets/js/general_setting/adminprofile.js') }}"></script>
@endpush
