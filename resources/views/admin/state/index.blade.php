@extends('admin.admin')

@section('meta_title', __('admin.common.state') . ' || ' . $companyName)

@section('content')
<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="content me-4">
        <!-- Breadcrumb -->
        <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
            <div class="my-auto mb-2">
                <h2 class="mb-1">{{ __('admin.cms.locations') }}</h2>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">{{ __('admin.common.home') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('admin.common.state') }}</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex my-xl-auto right-content align-items-center flex-wrap ">
                @if (hasPermission($permissions, 'cms_locations', 'create'))
                <div class="mb-2">
                    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#state_modal" id="add_state"
                        class="btn btn-primary d-flex align-items-center"><i
                            class="ti ti-plus me-2"></i>{{ __('admin.cms.add_state') }}</a>
                </div>
                @endif
            </div>
        </div>
        <!-- /Breadcrumb -->
        <!-- Table Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
            <div class="d-flex align-items-center flex-wrap row-gap-3">
                <div class="top-search">
                    <div class="top-search-group">
                        <span class="input-icon">
                            <i class="ti ti-search"></i>
                        </span>
                        <input type="text" class="form-control" id="search"
                            placeholder="{{ __('admin.common.search') }}">
                    </div>
                </div>
            </div>
            <div class="d-flex my-xl-auto right-content align-items-center flex-wrap row-gap-3">
                <div class="dropdown">
                    <button type="button" class="dropdown-toggle btn btn-white d-inline-flex align-items-center"
                        data-bs-toggle="dropdown">
                        <i class="ti ti-badge me-1"></i> {{ __('admin.common.status') }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end p-2" id="statusFilter">
                        <li>
                            <button type="button" class="dropdown-item rounded-1 selectStatus"
                                data-status="1">{{ __('admin.common.active') }}</button>
                        </li>
                        <li>
                            <button type="button" class="dropdown-item rounded-1 selectStatus"
                                data-status="0">{{ __('admin.common.inactive') }}</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /Table Header -->
        <div class="custom-datatable-filter table-responsive table-loader position-relative vh-10">
            @include('admin.content-loader')
        </div>
        <!-- Custom Data Table -->
        <div class="custom-datatable-filter table-responsive brandstable d-none real-table">
            <table class="table" id="stateTable">
                <thead class="thead-light">
                    <tr>
                        <th>{{ strtoupper(__('admin.cms.state_name')) }}</th>
                        <th>{{ strtoupper(__('admin.cms.country_name')) }}</th>
                        <th>{{ strtoupper(__('admin.common.status')) }}</th>
                        @if (hasPermission($permissions, 'cms_locations', 'edit') || hasPermission($permissions,
                        'cms_locations', 'delete'))
                        <th>{{ strtoupper(__('admin.common.action')) }}</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <!-- Custom Data Table -->
        <div class="table-footer d-none"></div>
    </div>
    @include('admin.partials.footer')
</div>
<!-- /Page Wrapper -->

<!-- Add Seat -->
<div class="modal fade addmodal" id="state_modal">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="mb-0 modal-title">{{ __('admin.cms.create_state') }}</h4>
                <button type="button" class="btn-close custom-btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ti ti-x fs-16"></i>
                </button>
            </div>
            <form id="stateForm">
                @csrf
                <input type="hidden" name="id" id="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">{{ __('admin.common.state') }}<span class="text-danger">
                                *</span></label>
                        <input type="text" class="form-control" name="name" id="name" maxlength="50">
                        <span id="name_error" class="text-danger error-text"></span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('admin.common.country') }}<span class="text-danger">
                                *</span></label>
                        <select class="form-control select2" name="country_id" id="country_id">
                            <option value="">{{ __('admin.common.select') }}</option>
                            @foreach ($country_ids as $country)
                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                        <span id="country_id_error" class="text-danger error-text"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <div class="form-check form-check-md form-switch me-2 d-none" id="statusDiv">
                            <label for="status" class="form-check-label form-label mt-0 mb-0">
                                <input class="form-check-input form-label me-2 status" id="status" type="checkbox"
                                    role="switch" aria-checked="true" checked>
                                {{ __('admin.common.status') }}
                            </label>
                        </div>
                        <div class="d-flex justify-content-center">
                            <a href="javascript:void(0);" class="btn btn-light me-3"
                                data-bs-dismiss="modal">{{ __('admin.common.cancel') }}</a>
                            <button type="submit"
                                class="btn btn-primary submitbtn">{{ __('admin.common.create_new') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /Add Seat -->

<!-- Delete Seat -->
<div class="modal fade deletemodal" id="delete-modal">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <form id="delateState">
                @csrf
                <input type="hidden" name="delete_id" id="delete_id">
                <div class="modal-body text-center">
                    <span class="avatar avatar-lg bg-transparent-danger rounded-circle text-danger mb-3">
                        <i class="ti ti-trash-x fs-26"></i>
                    </span>
                    <h4 class="mb-1">{{ __('admin.cms.delete_state') }}</h4>
                    <p class="mb-3">{{ __('admin.cms.state_delete_confirmation') }}</p>
                    <div class="d-flex justify-content-center">
                        <a href="javascript:void(0);" class="btn btn-light me-3"
                            data-bs-dismiss="modal">{{ __('admin.common.cancel') }}</a>
                        <button type="submit" class="btn btn-primary">{{ __('admin.common.yes_delete') }}</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /Delete Seat -->
@endsection

@push('scripts')
<script src="{{ asset('backend/assets/js/state.js') }}"></script>
@endpush
