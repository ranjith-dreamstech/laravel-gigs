@extends('admin.admin')
@section('content')
<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="content me-4">

        <!-- Breadcrumb -->
        <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
            <div class="my-auto mb-2">
                <h2 class="mb-1">Gigs</h2>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">{{ __('admin.common.home') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Gigs</li>
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
                <div class="top-search">
                    <div class="top-search-group">
                        <span class="input-icon">
                            <i class="ti ti-search"></i>
                        </span>
                        <input type="text" class="form-control" placeholder="{{ __('admin.common.search') }}" name="search" id="search">
                    </div>
                </div>
            </div>
            <div class="d-flex my-xl-auto right-content align-items-center flex-wrap row-gap-3">
                <div class="dropdown">
                    <a href="javascript:void(0);" class="dropdown-toggle btn btn-white d-inline-flex align-items-center" data-bs-toggle="dropdown">
                        <i class="ti ti-badge me-1"></i> {{ __('admin.common.status') }}
                    </a>
                    <ul class="dropdown-menu  dropdown-menu-end p-2">
                        <li>
                            <a href="javascript:void(0);" class="dropdown-item rounded-1">{{ __('admin.common.active') }}</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="dropdown-item rounded-1">{{ __('admin.common.inactive') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /Table Header -->

        <div class="custom-datatable-filter table-responsive table-loader">
            <table class="table">
                <thead>
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
                    </tr>
                </tbody>
            </table>
        </div>


        <!-- Custom Data Table -->
        <div class="custom-datatable-filter table-responsive brandstable d-none real-table">
            <table class="table" id="gigsTable">
                <thead class="thead-light">
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Sub Category</th>
                        <th>Price</th>
                        <th>Days</th>
                        <th>No Revisions</th>
                        <th>Buyer</th>
                        <th>Status</th>
                        <th>Action</th>
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


<div class="modal fade addmodal" id="gigs_modal">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="mb-0 modal-title">Gigs Status</h4>
                <button type="button" class="btn-close custom-btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ti ti-x fs-16"></i>
                </button>
            </div>
            <form id="gigsStatusForm">
                @csrf
                <input type="hidden" name="id" id="id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">{{ __('admin.common.status') }}<span class="text-danger"> </span></label>
                                <select name="status" id="status" class="form-control select">
                                    <option value="">Select</option>
                                    <option value="1">Active</option>
                                    <option value="0">Block</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row w-100">
                        <div class="col-12">
                            <div class="d-flex justify-content-center">
                                <a href="javascript:void(0);" class="btn btn-light me-3" data-bs-dismiss="modal">{{ __('admin.common.close') }}</a>
                                <button type="submit" class="btn btn-primary submitbtn">{{ __('admin.common.update') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>



<div class="modal fade addmodal" id="detaislModel" tabindex="-1" aria-labelledby="detaislModelLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="mb-0 modal-title">Gigs Details</h4>
                <button type="button" class="btn-close custom-btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ti ti-x fs-16"></i>
                </button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <div class="d-flex justify-content-around align-items-center w-100">
                    <div class="d-flex justify-content-center">
                        <a href="javascript:void(0);" class="btn btn-light me-3" data-bs-dismiss="modal">{{ __('admin.common.close') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal  -->
<div class="modal fade" id="delete_page">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div id="append_details">
                </div>
                <div class="d-flex justify-content-center">
                    <a href="javascript:void(0);" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</a>
                    <a href="pages.html" class="btn btn-primary">Yes, Delete</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Delete Modal-->

<!-- /model -->
@endsection

@push('scripts')
<script src="{{ asset('backend/assets/js/gigs/gig-list.js') }}"></script>
@endpush
