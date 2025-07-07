@extends('frontend.seller.partials.app')
@section('content')

<!-- Page Content -->
<div class="page-wrapper">
    <div class="page-content content bg-light">
        <div class="container px-0">
            <div class="content">
                <!--User Files -->
                <div class="">
                    <div class="main-title my-4">
                        <h4>{{ __('web.uploads.title') }}</h4>
                        <div id="tableinfo"></div>
                    </div>
                    <div class="table-filter">
                        <ul class="filter-item">
                            <li>
                                <div class="skeleton label-skeleton label-loader me-2"></div>
                                <div class="d-none real-label">
                                    <div id="reportrangeOrder" class="reportrange-picker d-flex align-items-center">
                                        <i class="ti ti-calendar text-gray-5 fs-14 me-1"></i><span class="reportrange-picker-field"></span>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="skeleton label-skeleton label-loader me-2"></div>
                                <div class="dropdown d-none real-label">
                                    <a href="javascript:void(0);" class="dropdown-toggle btn btn-white d-inline-flex align-items-center" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                        <i class="ti ti-upload me-2"></i>{{ __('web.uploads.filter_by_uploaded_for') }}
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-lg p-2">
                                        <li>
                                            <div class="mb-2">
                                                <div class="dropdown-add-search">
                                                    <span class="input-icon">
                                                        <i class="ti ti-search"></i>
                                                    </span>
                                                    <input type="hidden" name="buyer_id" id="buyer_id" value="{{ $buyer_id }}">
                                                    <input type="text" class="form-control" placeholder="{{ __('web.common.search') }}" name="search_seller" id="search_seller">
                                                </div>
                                            </div>
                                        </li>
                                        <div id="appendGigs"></div>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <div class="skeleton label-skeleton label-loader me-2"></div>
                                <div class="dropdown d-none real-label">
                                    <a href="javascript:void(0);" class="dropdown-toggle btn btn-white d-inline-flex align-items-center" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                        <i class="ti ti-file-search me-2"></i>{{ __('web.uploads.file_type') }}
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-lg p-2">
                                        <div id="appendTypes"></div>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <div class="skeleton label-skeleton label-loader me-2"></div>
                                <div class="dropdown d-none real-label">
                                    <a href="javascript:void(0);" class="btn btn-white clearBtn d-inline-flex align-items-center">
                                        <i class="ti ti-x me-2"></i>{{ __('web.uploads.clear_filter') }}
                                    </a>
                                </div>
                            </li>
                        </ul>
                        <div id="tablefilter"></div>
                    </div>
                    <div class="table-responsive custom-table d-none real-table">
                        <table class="table datatable" id="uploadedFile">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ __('web.uploads.order_id') }}</th>
                                    <th>{{ __('web.uploads.uploaded_for') }}</th>
                                    <th>{{ __('web.uploads.uploaded_on') }}</th>
                                    <th>{{ __('web.uploads.file_type') }}</th>
                                    <th>{{ __('web.uploads.action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive table-loader">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" class="text-center">
                                        <div class="skeleton th-skeleton th-loader"></div>
                                    </th>
                                    <th scope="col">
                                        <div class="skeleton th-skeleton th-loader"></div>
                                    </th>
                                    <th scope="col">
                                        <div class="skeleton th-skeleton th-loader"></div>
                                    </th>
                                    <th scope="col">
                                        <div class="skeleton th-skeleton th-loader"></div>
                                    </th>
                                    <th scope="col">
                                        <div class="skeleton th-skeleton th-loader"></div>
                                    </th>
                                    <th scope="col">
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
                                    <th scope="col">
                                        <div class="skeleton data-skeleton data-loader"></div>
                                    </th>
                                    <th scope="col">
                                        <div class="skeleton data-skeleton data-loader"></div>
                                    </th>
                                    <th scope="col" >
                                        <div class="skeleton data-skeleton data-loader"></div>
                                    </th>
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
                                    <th scope="col">
                                        <div class="skeleton data-skeleton data-loader"></div>
                                    </th>
                                    <th scope="col">
                                        <div class="skeleton data-skeleton data-loader"></div>
                                    </th>
                                    <th scope="col">
                                        <div class="skeleton data-skeleton data-loader"></div>
                                    </th>
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
                                    <th scope="col">
                                        <div class="skeleton data-skeleton data-loader"></div>
                                    </th>
                                    <th scope="col">
                                        <div class="skeleton data-skeleton data-loader"></div>
                                    </th>
                                    <th scope="col">
                                        <div class="skeleton data-skeleton data-loader"></div>
                                    </th>
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
                                    <th scope="col">
                                        <div class="skeleton data-skeleton data-loader"></div>
                                    </th>
                                    <th scope="col">
                                        <div class="skeleton data-skeleton data-loader"></div>
                                    </th>
                                    <th scope="col">
                                        <div class="skeleton data-skeleton data-loader"></div>
                                    </th>
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
                                    <th scope="col">
                                        <div class="skeleton data-skeleton data-loader"></div>
                                    </th>
                                    <th scope="col">
                                        <div class="skeleton data-skeleton data-loader"></div>
                                    </th>
                                    <th scope="col">
                                        <div class="skeleton data-skeleton data-loader"></div>
                                    </th>
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
                                    <th scope="col">
                                        <div class="skeleton data-skeleton data-loader"></div>
                                    </th>
                                    <th scope="col">
                                        <div class="skeleton data-skeleton data-loader"></div>
                                    </th>
                                    <th scope="col">
                                        <div class="skeleton data-skeleton data-loader"></div>
                                    </th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /User Files -->
            </div>
        </div>
    </div>
</div>
<!-- /Page Content -->

<!-- File Details -->
<div class="modal new-modal fade" id="file_view" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">File Details - #124</h5>
                <button type="button" class="close-btn" data-bs-dismiss="modal"><span>×</span></button>
            </div>
            <div class="modal-body">
                <div class="file-view">
                    <div class="file-img">
                        <img src="assets/img/gigs/gigs-04.jpg" class="img-fluid" alt="img">
                    </div>
                    <div class="upload-wrap mb-0">
                        <div class="upload-image">
                            <span>
                                <i class="ti ti-photo"></i>
                            </span>
                            <p class="mb-0">Video_gig-1.mp4</p>
                        </div>
                        <div class="d-flex align-items-center">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /File Details -->


<!-- Order Cancel -->
<div class="modal new-modal fade" id="cancel_order" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('web.uploads.delete_file') }}</h5>
                <button type="button" class="close-btn" data-bs-dismiss="modal"><i class="ti ti-x"></i></button>
            </div>
            <div class="modal-body">
                <form id="orderCancel">
                    <input type="hidden" name="order_file_id" id="order_file_id">
                    <div class="row">
                        <div class="modal-btn">
                            <button class="btn btn-primary w-100" type="submit">{{ __('web.uploads.delete') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Order Cancel -->
@endsection
@push('scripts')
<script src="{{ asset('frontend/custom/js/fileuplaod.js') }}"></script>
@endpush
