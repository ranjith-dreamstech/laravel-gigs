@extends('admin.admin')
@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content me-4">
            <!-- Breadcrumb -->
            <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
                <div class="my-auto mb-2">
                    <h4 class="mb-1">{{ __('admin.finance.refund') }}</h4>
                    <nav>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}">{{ __('admin.common.home') }}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('admin.finance.refund') }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="d-flex my-xl-auto right-content align-items-center flex-wrap ">
                </div>
            </div>
            <!-- /Breadcrumb -->
            <!-- Table Header -->
            <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
                <div class="d-flex align-items-center flex-wrap row-gap-3">
                    <input type="hidden" name="sort_by_input" id="sort_by_input">
                    <input type="hidden" name="sort_by_status" id="sort_by_status">
                    <div class="skeleton label-skeleton label-loader me-2"></div>
                    <div class="dropdown me-2 d-none real-label">
                        <a href="javascript:void(0);" class="dropdown-toggle btn btn-white d-inline-flex align-items-center"
                            data-bs-toggle="dropdown">
                            <i class="ti ti-filter me-1"></i> {{ __('admin.common.sort_by') }} : <span class="ms-1"
                                id="current_sort">{{ __('admin.common.latest') }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end p-2 sort_by_list">
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1"
                                    data-sort="latest">{{ __('admin.common.latest') }}</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1"
                                    data-sort="ascending">{{ __('admin.common.ascending') }}</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1"
                                    data-sort="descending">{{ __('admin.common.descending') }}</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1"
                                    data-sort="last month">{{ __('admin.common.last_month') }}</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1"
                                    data-sort="last 7 days">{{ __('admin.common.last_7_days') }}</a>
                            </li>
                        </ul>
                    </div>
                    <div class="skeleton label-skeleton label-loader"></div>
                    <div class="d-none real-label">

                    </div>
                </div>

                <div class="d-flex my-xl-auto right-content align-items-center flex-wrap row-gap-3">

                    <div class="skeleton label-skeleton label-loader"></div>
                    <div class="top-search d-none real-label">
                        <div class="top-search-group">
                            <span class="input-icon">
                                <i class="ti ti-search"></i>
                            </span>
                            <input type="text" class="form-control" name="search" id="search"
                                placeholder="{{ __('admin.common.search') }}">
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Table Header -->

            <div class="collapse" id="filtercollapse">
                <div class="filterbox mb-3 d-flex align-items-center">
                    <h6 class="me-3">{{ __('admin.common.filters') }}</h6>
                    <div class="dropdown me-2">
                        <a href="javascript:void(0);" class="dropdown-toggle btn btn-white d-inline-flex align-items-center"
                            data-bs-toggle="dropdown" data-bs-auto-close="outside">
                            {{ __('admin.common.language') }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-lg p-2" id="language_list">
                            <li>
                                <div class="top-search m-2">
                                    <div class="top-search-group">
                                        <span class="input-icon">
                                            <i class="ti ti-search"></i>
                                        </span>
                                        <input type="text" class="form-control"
                                            placeholder="{{ __('admin.common.search') }}">
                                    </div>
                                </div>
                            </li>
                            <div class="custom-scroll">

                            </div>
                        </ul>
                    </div>
                    <a href="javascript:void(0);" class="me-2 text-purple links"
                        id="apply_filter">{{ __('admin.common.apply') }}</a>
                    <a href="javascript:void(0);" class="text-danger links"
                        id="reset_filter">{{ __('admin.common.clear_all') }}</a>
                </div>
            </div>

            <div class="custom-datatable-filter table-responsive table-loader">
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>
                                <div class="skeleton th-skeleton th-loader"></div>
                            </th>
                            <th>
                                <div class="skeleton th-skeleton th-loader"></div>
                            </th>
                            <th>
                                <div class="skeleton th-skeleton th-loader"></div>
                            </th>
                            <th>
                                <div class="skeleton th-skeleton th-loader"></div>
                            </th>
                            <th>
                                <div class="skeleton th-skeleton th-loader"></div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="skeleton data-skeleton data-loader"></div>
                            </td>
                            <td>
                                <div class="skeleton data-skeleton data-loader"></div>
                            </td>
                            <td>
                                <div class="skeleton data-skeleton data-loader"></div>
                            </td>
                            <td>
                                <div class="skeleton data-skeleton data-loader"></div>
                            </td>
                            <td>
                                <div class="skeleton data-skeleton data-loader"></div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="skeleton data-skeleton data-loader"></div>
                            </td>
                            <td>
                                <div class="skeleton data-skeleton data-loader"></div>
                            </td>
                            <td>
                                <div class="skeleton data-skeleton data-loader"></div>
                            </td>
                            <td>
                                <div class="skeleton data-skeleton data-loader"></div>
                            </td>
                            <td>
                                <div class="skeleton data-skeleton data-loader"></div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="skeleton data-skeleton data-loader"></div>
                            </td>
                            <td>
                                <div class="skeleton data-skeleton data-loader"></div>
                            </td>
                            <td>
                                <div class="skeleton data-skeleton data-loader"></div>
                            </td>
                            <td>
                                <div class="skeleton data-skeleton data-loader"></div>
                            </td>
                            <td>
                                <div class="skeleton data-skeleton data-loader"></div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="skeleton data-skeleton data-loader"></div>
                            </td>
                            <td>
                                <div class="skeleton data-skeleton data-loader"></div>
                            </td>
                            <td>
                                <div class="skeleton data-skeleton data-loader"></div>
                            </td>
                            <td>
                                <div class="skeleton data-skeleton data-loader"></div>
                            </td>
                            <td>
                                <div class="skeleton data-skeleton data-loader"></div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="skeleton data-skeleton data-loader"></div>
                            </td>
                            <td>
                                <div class="skeleton data-skeleton data-loader"></div>
                            </td>
                            <td>
                                <div class="skeleton data-skeleton data-loader"></div>
                            </td>
                            <td>
                                <div class="skeleton data-skeleton data-loader"></div>
                            </td>
                            <td>
                                <div class="skeleton data-skeleton data-loader"></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="custom-datatable-filter table-responsive d-none real-table">
                <!-- Custom Data Table -->
                <table class="table" id="refundTable">
                    <thead class="thead-light">
                        <tr>
                            <th>{{ strtoupper(__('admin.finance.name')) }}</th>
                            <th>{{ strtoupper(__('admin.finance.gigs')) }}</th>
                            <th>{{ strtoupper(__('admin.finance.final_price')) }}</th>
                            <th>{{ strtoupper(__('admin.common.status')) }}</th>
                            <th>{{ strtoupper(__('admin.finance.booking_date')) }}</th>
                            @if (hasPermission($permissions, 'buyer_request', 'edit') || hasPermission($permissions, 'buyer_request', 'delete'))
                                <th>{{ strtoupper(__('admin.common.action')) }}</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <!-- Custom Data Table -->
            <div class="table-footer d-none"></div>
        </div>
        @include('admin.partials.footer')
    </div>
    <!-- /Page Wrapper -->

    <!-- Add view model -->
    <div class="modal fade" id="add_category_modal">
        <div class="modal-dialog modal-dialog-centered">
            <form id="adminRefundForm" autocomplete="off" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="mb-0">{{ __('admin.finance.refund') }}</h5>
                        <button type="button" class="btn-close custom-btn-close" data-bs-dismiss="modal"
                            aria-label="Close">
                            <i class="ti ti-x fs-16"></i>
                        </button>
                    </div>

                    <div class="modal-body pb-1 customer-modal-scroll">
                        <div class="row">
                            <!-- Hidden Field -->
                            <input type="hidden" id="bookingid" name="bookingid">

                            <div class="mb-3">
                                <label for="codFile" class="form-label">{{ __('Upload_Payment_Proof') }}</label>
                                <input type="file" id="codFile" name="codFile" class="form-control"
                                    accept="image/*,application/pdf">
                                <div id="filePreview" class="mt-3 d-none"></div>
                                <span class="text-danger error-text" id="codFile_error"></span>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <div class="d-flex justify-content-center">
                            <a href="javascript:void(0);" class="btn btn-light me-3"
                                data-bs-dismiss="modal">{{ __('admin.common.cancel') }}</a>
                            <button type="submit" id=""
                                class="btn btn-primary submitbtn">{{ __('admin.common.update') }}</button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <!-- /Add category -->
@endsection

@push('scripts')
    <script src="{{ asset('backend/assets/js/finance/refund.js') }}"></script>
@endpush
