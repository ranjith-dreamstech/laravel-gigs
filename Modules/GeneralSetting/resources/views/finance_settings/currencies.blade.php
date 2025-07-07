@extends('admin.admin')

@section('meta_title', __('admin.general_settings.currencies') . ' || ' . $companyName)

@section('content')
    <div class="page-wrapper">
        <div class="content">
            <x-admin.breadcrumb :title="__('admin.general_settings.settings')" :breadcrumbs="[
            __('admin.general_settings.settings') => ''
        ]" />
            <div class="row">
                @include('admin.partials.general_settings_side_menu')
                <div class="col-xl-9">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ __('admin.general_settings.finance_settings') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="payment-section">
                                <!-- Table Header -->
                                <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
                                    <h6>{{ __('admin.general_settings.currencies') }}</h6>
                                    <div>
                                        @if (hasPermission($permissions, 'finance_settings', 'create'))
                                            <button type="button" class="btn btn-primary d-flex align-items-center"
                                                id="add_new_currency" data-bs-toggle="modal" data-bs-target="#add_currency">
                                                <i class="ti ti-plus me-2"></i>{{ __('admin.general_settings.add_currency') }}
                                            </button>
                                        @endif
                                    </div>
                                </div>
                                <div class="custom-datatable-filter table-responsive position-relative vh-10 table-loader">
                                    @include('admin.content-loader')
                                </div>
                                <!-- Custom Data Table -->
                                <div
                                    class="custom-datatable-filter table-responsive brandstable country-table d-none real-table">
                                    <table class="table" id="currencyTable">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>{{ strtoupper(__('admin.general_settings.currency')) }}</th>
                                                <th>{{ strtoupper(__('admin.general_settings.code')) }}</th>
                                                <th>{{ strtoupper(__('admin.general_settings.symbol')) }}</th>
                                                <th>{{ strtoupper(__('admin.general_settings.status')) }}</th>
                                                @if (hasPermission($permissions, 'finance_settings', 'edit') || hasPermission($permissions, 'finance_settings', 'delete'))
                                                    <th>{{ strtoupper(__('admin.common.action')) }}</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                                <!-- Table Footer -->
                                <div class="table-footer d-none"></div>
                                <!-- /Table Footer -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('admin.partials.footer')
    </div>

    <!-- Add Currency -->
    <x-admin.modal className="addmodal" id="add_currency" :title="__('admin.general_settings.add_currency')"
        formId="currencyForm" dialogClass="modal-dialog-centered modal-md">
        <x-slot name="body">
            @csrf
            <input type="hidden" name="id" id="id">

            <div class="mb-3">
                <label for="currency_name" class="form-label">{{ __('admin.general_settings.currency_name') }} <span
                        class="text-danger">*</span></label>
                <input type="text" class="form-control" name="currency_name" id="currency_name">
                <span id="currency_name_error" class="text-danger error-text"></span>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="code" class="form-label">{{ __('admin.general_settings.code') }} <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="code" id="code">
                        <span id="code_error" class="text-danger error-text"></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="symbol" class="form-label">{{ __('admin.general_settings.symbol') }} <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="symbol" id="symbol">
                        <span id="symbol_error" class="text-danger error-text"></span>
                    </div>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <div class="d-flex justify-content-between align-items-center w-100" id="modalfootdiv">
                <div class="form-check form-check-md form-switch me-2 d-none" id="status_div">
                    <label for="status" class="form-check-label form-label mt-0 mb-0">
                        <input
                            class="form-check-input form-label me-2"
                            type="checkbox"
                            role="switch"
                            name="status"
                            id="status"
                            aria-checked="false"
                            onchange="this.setAttribute('aria-checked', this.checked ? 'true' : 'false')"
                        />
                        {{ __('admin.common.status') }}
                    </label>
                </div>
                <div class="d-flex justify-content-center">
                    <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">
                        {{ __('admin.general_settings.cancel') }}
                    </button>
                    <button type="submit" class="btn btn-primary submitbtn">
                        {{ __('admin.general_settings.create_new') }}
                    </button>
                </div>
            </div>
        </x-slot>
    </x-admin.modal>

    <!-- /Add Currency -->

    <!-- Delete Currency -->
    <x-admin.delete-modal className="deletemodal" id="delete-modal" action="" formId="deleteCurrencyForm"
        :hiddenInputs="['id' => '']" :title="__('admin.general_settings.delete_currency')"
        :description="__('admin.general_settings.delete_currency_confirmation')">
    </x-admin.delete-modal>


    <!-- /Delete Currency -->
@endsection

@push('scripts')
    <script src="{{ asset('backend/assets/js/general_setting/currencies.js') }}"></script>
@endpush
