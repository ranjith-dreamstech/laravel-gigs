@extends('admin.admin')

@section('meta_title', __('admin.general_settings.localization') . ' || ' . $companyName)

@section('content')
<div class="page-wrapper">
    <div class="content">
        <x-admin.breadcrumb
            :title="__('admin.general_settings.settings')"
            :breadcrumbs="[__('admin.general_settings.settings') => '']"
        />
        <div class="row">
            @include('admin.partials.general_settings_side_menu')
            <div class="col-xl-9">
                <div class="card">
                    <form action="" id="localizationForm">
                        @csrf
                        <div class="card-header">
                            <h5>{{ __('admin.general_settings.website_settings') }}</h5>
                        </div>
                        @include('admin.general_settings_loader')
                        <div class="card-body d-none real-card">
                            <div class="localization-content mb-3">
                                <div>
                                    <h6 class="mb-3">{{ __('admin.general_settings.localization') }}</h6>
                                    <div class="row mb-3">
                                        <div class="col-md-8">
                                            <p class="text-gray-9 fw-medium">{{ __('admin.general_settings.time_zone') }} <span class="text-danger">*</span></p>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="">
                                                <select class="form-control" id="timezone" name="timezone">
                                                    <option value="">{{ __('admin.general_settings.select') }}</option>
                                                </select>
                                                <span class="text-danger error-text" id="timezone_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-8">
                                            <p class="text-gray-9 fw-medium">{{ __('admin.general_settings.start_weekon') }} <span class="text-danger">*</span></p>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="">
                                                <select class="select" name="week_start_day" id="week_start_day">
                                                    <option value="">{{ __('admin.general_settings.select') }}</option>
                                                    @if(!empty($weekdays) && count($weekdays) > 0)
                                                        @foreach($weekdays as $weekday)
                                                            <option value="{{ $weekday }}">{{ ucfirst($weekday) }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <span class="text-danger error-text" id="week_start_day_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-8">
                                            <p class="text-gray-9 fw-medium">{{ __('admin.general_settings.date_format') }} <span class="text-danger">*</span></p>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="">
                                                <select class="select" name="date_format" id="date_format">
                                                    <option value="">{{ __('admin.general_settings.select') }}</option>
                                                    @if(!empty($dateformats) && count($dateformats) > 0)
                                                        @foreach($dateformats as $dateformat)
                                                            <option value="{{ $dateformat->id }}">{{ $dateformat->title }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <span class="text-danger error-text" id="date_format_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-8">
                                            <p class="text-gray-9 fw-medium">{{ __('admin.general_settings.time_format') }} <span class="text-danger">*</span></p>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="">
                                                <select class="select" name="time_format" id="time_format">
                                                    <option value="">{{ __('admin.general_settings.select') }}</option>
                                                    @if(!empty($timeformats) && count($timeformats) > 0)
                                                        @foreach($timeformats as $timeformat)
                                                            <option value="{{ $timeformat->id }}">{{ $timeformat->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <span class="text-danger error-text" id="time_format_error"></span>
                                        </div>
                                    </div>
                                    <div class="localization-list">
                                        <p class="text-gray-9 fw-medium">{{ __('admin.general_settings.language_switcher') }} <span class="text-danger">*</span></p>
                                        <div>
                                            <div class="form-check form-check-md form-switch me-2 d-none real-input">
                                                <input class="form-check-input form-label" type="checkbox" role="switch" aria-checked="true" name="language_switcher" id="language_switcher" checked>
                                            </div>
                                            <span class="text-danger error-text" id="language_switcher_error"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="localization-content border-0">
                                <div>
                                    <h6 class="mb-3">{{ __('admin.general_settings.currency_information') }}</h6>
                                    <div class="row mb-3">
                                        <div class="col-md-8">
                                            <p class="text-gray-9 fw-medium">{{ __('admin.general_settings.currency') }} <span class="text-danger">*</span></p>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="">
                                                <select class="select" name="currency" id="currency">
                                                    <option value="">{{ __('admin.general_settings.select') }}</option>
                                                    @if(!empty($currencies) && count($currencies) > 0)
                                                        @foreach($currencies as $currency)
                                                            <option value="{{ $currency->id }}" data-symbol="{{ $currency->symbol }}">{{ $currency->currency_name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <span class="text-danger error-text" id="currency_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-8">
                                            <p class="text-gray-9 fw-medium">{{ __('admin.general_settings.currency_symbol') }} <span class="text-danger">*</span></p>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="">
                                                <select class="select" id="currency_symbol" name="currency_symbol">
                                                    <option value="">{{ __('admin.general_settings.select') }}</option>
                                                </select>
                                                <span class="text-danger error-text" id="currency_symbol_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="localization-list d-none">
                                        <p class="text-gray-9 fw-medium">{{ __('admin.general_settings.currency_switcher') }} <span class="text-danger">*</span></p>
                                        <div>
                                            <div class="form-check form-check-md form-switch me-2 d-none real-input">
                                               <input class="form-check-input form-label" type="checkbox" role="switch" aria-checked="true" name="currency_switcher" id="currency_switcher" checked>
                                            </div>
                                            <span class="text-danger error-text" id="currency_switcher_error"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-none real-card">
                            <div class="d-flex align-items-center justify-content-end">
                                <a href="{{ route('dashboard') }}" class="btn btn-light me-3" >{{ __('admin.general_settings.cancel') }}</a>
                                @if (hasPermission($permissions, 'website_settings', 'edit'))
                                <button type="submit" class="btn btn-primary submitbtn">{{ __('admin.general_settings.save_changes') }}</button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('admin.partials.footer')
</div>
@endsection

@push('scripts')
<script src="{{ asset('backend/assets/js/general_setting/localization.js') }}"></script>
@endpush
