@extends('admin.admin')
@section('content')

    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content me-4">

            <!-- Breadcrumb -->
            <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
                <div class="my-auto mb-2">
                    <h4 class="mb-1">{{ __('admin.manage.sub_category') }}</h4>
                    <nav>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}">{{ __('admin.common.home') }}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('admin.manage.sub_category') }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="d-flex my-xl-auto right-content align-items-center flex-wrap ">
                    @if (hasPermission($permissions, 'sub_category', 'create'))
                        <div class="mb-2">

                            <div class="skeleton label-skeleton label-loader"></div>
                            <a href="javascript:void(0);"
                                class="btn btn-primary d-flex align-items-center d-none real-label" id="add_category"
                                data-bs-toggle="modal" data-bs-target="#add_category_modal"><i
                                    class="ti ti-plus me-2"></i>{{ __('admin.manage.add_new_sub_category') }}</a>

                        </div>
                    @endif
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
                                id="sortBy">{{ __('admin.common.latest') }}</span>
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
                        <select id="filterCategory" name="category_id" class="form-select">
                            <option value="">{{ __('admin.common.select_category') }}</option>
                            @if ($categories->isNotEmpty())
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            @else
                                <option disabled selected>{{ __('admin.common.no_category_found') }}</option>
                            @endif
                        </select>
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

            <!-- Custom Data Table -->
            <div class="custom-datatable-filter table-responsive d-none real-table">
                <table class="table" id="subCategoryTable">
                    <thead class="thead-light">
                        <tr>

                            <th>{{ strtoupper(__('admin.common.name')) }}</th>
                            <th>{{ strtoupper(__('admin.common.slug')) }}</th>
                            <th>{{ strtoupper(__('admin.common.category')) }}</th>
                            <th>{{ strtoupper(__('admin.common.status')) }}</th>
                            <th>{{ strtoupper(__('admin.common.featured')) }}</th>
                            @if (hasPermission($permissions, 'sub_category', 'edit') || hasPermission($permissions, 'sub_category', 'delete'))
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

    <!-- Add category -->
    <div class="modal fade" id="add_category_modal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form id="subCategoryForm" autocomplete="off">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="mb-0">{{ __('admin.manage.create_sub_category') }}</h5>
                        <button type="button" class="btn-close custom-btn-close" data-bs-dismiss="modal"
                            aria-label="Close">
                            <i class="ti ti-x fs-16"></i>
                        </button>
                    </div>
                    <div class="modal-body pb-1 customer-modal-scroll">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="image" class="form-label">{{ __('admin.common.image') }}<span
                                            class="text-danger"> *</span></label>
                                    <div class="d-flex align-items-center flex-wrap row-gap-3">
                                        <div
                                            class="d-flex align-items-center justify-content-center avatar avatar-xxl border me-3 flex-shrink-0 text-dark frames">
                                            <img src="" class="img-fluid rounded d-none" id="imagePreview"
                                                alt="img">
                                            <i class="ti ti-photo-up text-gray-4 fs-24 upload_icon"></i>
                                        </div>
                                        <div class="profile-upload">
                                            <div class="profile-uploader d-flex align-items-center">
                                                <div class="drag-upload-btn btn btn-md btn-dark">
                                                    <i class="ti ti-photo-up fs-14"></i>
                                                    {{ __('admin.common.upload') }}
                                                    <input type="file" class="form-control image-sign" name="image"
                                                        id="image"
                                                        onchange="previewImage(event, 'imagePreview', 918, 678)">
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <p class="fs-14">
                                                    {{ __('admin.common.upload_image_size_category', ['size' => 2]) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-danger error-text" id="image_error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="icon" class="form-label">{{ __('admin.common.icon') }}<span
                                            class="text-danger"> *</span></label>
                                    <div class="d-flex align-items-center flex-wrap row-gap-3">
                                        <div
                                            class="d-flex align-items-center justify-content-center avatar avatar-xxl border me-3 flex-shrink-0 text-dark frames">
                                            <img src="" class="img-fluid rounded d-none" id="iconPreview"
                                                alt="img">
                                            <i class="ti ti-photo-up text-gray-4 fs-24 upload_icon"></i>
                                        </div>
                                        <div class="profile-upload">
                                            <div class="profile-uploader d-flex align-items-center">
                                                <div class="drag-upload-btn btn btn-md btn-dark">
                                                    <i class="ti ti-photo-up fs-14"></i>
                                                    {{ __('admin.common.upload') }}
                                                    <input type="file" class="form-control image-sign" name="icon"
                                                        id="icon"
                                                        onchange="previewImage(event, 'iconPreview', 15, 25)">
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <p class="fs-14">{{ __('admin.common.upload_icon_size', ['size' => 2]) }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-danger error-text" id="icon_error"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="categoryname" class="form-label">{{ __('admin.common.name') }}<span
                                            class="text-danger"> *</span></label>
                                    <input type="text" class="form-control" name="categoryname" id="categoryname">
                                    <span class="text-danger error-text" id="categoryname_error"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="slug" class="form-label">{{ __('admin.common.slug') }}<span
                                            class="text-danger"> *</span></label>
                                    <input type="text" class="form-control" name="slug" id="slug">
                                    <span class="text-danger error-text" id="slug_error"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="description" class="form-label">{{ __('admin.common.description') }}<span
                                            class="text-danger"> *</span></label>
                                    <input type="text" class="form-control" name="description" id="description">
                                    <span class="text-danger error-text" id="description_error"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">{{ __('admin.common.category') }}<span
                                            class="text-danger"> *</span></label>
                                    <select id="category_id" name="category_id" class="form-select">
                                        @if ($categories->isNotEmpty())
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        @else
                                            <option disabled selected>{{ __('admin.common.no_category_found') }}</option>
                                        @endif
                                    </select>
                                    <span class="text-danger error-text" id="category_id_error"></span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <div class="form-check form-check-md form-switch me-2">
                                        <label for="status" class="form-check-label form-label mt-0 mb-0">
                                            <input class="form-check-input form-label me-2 feature" id="feature"
                                                name="feature" checked type="checkbox" role="switch"
                                                aria-checked="true">
                                            {{ __('admin.common.feature') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
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
    <!-- /Add category -->

    <!-- Edit category -->
    <div class="modal fade" id="editCategoryModal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form id="editcategoryForm" autocomplete="off">
                <input type="hidden" name="id" id="id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="mb-0">{{ __('admin.manage.edit_sub_category') }}</h5>
                        <button type="button" class="btn-close custom-btn-close" data-bs-dismiss="modal"
                            aria-label="Close">
                            <i class="ti ti-x fs-16"></i>
                        </button>
                    </div>
                    <div class="modal-body pb-1 customer-modal-scroll">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_image" class="form-label">{{ __('admin.common.image') }}<span
                                            class="text-danger"> *</span></label>
                                    <div class="d-flex align-items-center flex-wrap row-gap-3">
                                        <div
                                            class="d-flex align-items-center justify-content-center avatar avatar-xxl border me-3 flex-shrink-0 text-dark frames">
                                            <img src="" class="img-fluid rounded d-none" id="editImagePreview"
                                                alt="img">
                                            <i class="ti ti-photo-up text-gray-4 fs-24 upload_icon"></i>
                                        </div>
                                        <div class="profile-upload">
                                            <div class="profile-uploader d-flex align-items-center">
                                                <div class="drag-upload-btn btn btn-md btn-dark">
                                                    <i class="ti ti-photo-up fs-14"></i>
                                                    {{ __('admin.common.upload') }}
                                                    <input type="file" class="form-control image-sign" name="image"
                                                        id="edit_image"
                                                        onchange="previewImage(event, 'editImagePreview', 918, 678)">
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <p class="fs-14">
                                                    {{ __('admin.common.upload_image_size_category', ['size' => 2]) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-danger error-text" id="image_error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_icon" class="form-label">{{ __('admin.common.icon') }}<span
                                            class="text-danger"> *</span></label>
                                    <div class="d-flex align-items-center flex-wrap row-gap-3">
                                        <div
                                            class="d-flex align-items-center justify-content-center avatar avatar-xxl border me-3 flex-shrink-0 text-dark frames">
                                            <img src="" class="img-fluid rounded d-none" id="editIconPreview"
                                                alt="img">
                                            <i class="ti ti-photo-up text-gray-4 fs-24 upload_icon"></i>
                                        </div>
                                        <div class="profile-upload">
                                            <div class="profile-uploader d-flex align-items-center">
                                                <div class="drag-upload-btn btn btn-md btn-dark">
                                                    <i class="ti ti-photo-up fs-14"></i>
                                                    {{ __('admin.common.upload') }}
                                                    <input type="file" class="form-control image-sign" name="icon"
                                                        id="edit_icon"
                                                        onchange="previewImage(event, 'editIconPreview', 15, 25)">
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <p class="fs-14">{{ __('admin.common.upload_icon_size', ['size' => 2]) }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-danger error-text" id="icon_error"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="categoryname" class="form-label">{{ __('admin.common.name') }}<span
                                            class="text-danger"> *</span></label>
                                    <input type="text" class="form-control" name="categoryname" id="categoryname">
                                    <span class="text-danger error-text" id="categoryname_error"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="slug" class="form-label">{{ __('admin.common.slug') }}<span
                                            class="text-danger"> *</span></label>
                                    <input type="text" class="form-control" name="slug" id="slug">
                                    <span class="text-danger error-text" id="slug_error"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="description" class="form-label">{{ __('admin.common.description') }}<span
                                            class="text-danger"> *</span></label>
                                    <input type="text" class="form-control" name="description" id="description">
                                    <span class="text-danger error-text" id="description_error"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">{{ __('admin.common.category') }}<span
                                            class="text-danger"> *</span></label>
                                    <select id="category_id" name="category_id" class="form-select">
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error-text" id="category_id_error"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <div class="form-check form-check-md form-switch me-2">
                                        <label for="status" class="form-check-label form-label mt-0 mb-0">
                                            <input class="form-check-input form-label me-2 feature" id="feature"
                                                name="feature" checked type="checkbox" role="switch"
                                                aria-checked="true">
                                            {{ __('admin.common.feature') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <div class="form-check form-check-md form-switch me-2">
                                <label for="status" class="form-check-label form-label mt-0 mb-0">
                                    <input class="form-check-input form-label me-2 status" id="status" name="status"
                                        type="checkbox" role="switch" aria-checked="false">
                                    {{ __('admin.common.status') }}
                                </label>
                            </div>
                            <div class="d-flex justify-content-center">
                                <a href="javascript:void(0);" class="btn btn-light me-3"
                                    data-bs-dismiss="modal">{{ __('admin.common.cancel') }}</a>
                                <button type="submit"
                                    class="btn btn-primary submitbtn">{{ __('admin.common.save_changes') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- /Edit category -->

    <!-- Delete Customer -->
    <div class="modal fade" id="delete_modal">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <form id="deleteCategoryForm">
                    <input type="hidden" name="delete_id" id="delete_id">
                    <div class="modal-body text-center">
                        <span class="avatar avatar-lg bg-transparent-danger rounded-circle text-danger mb-3">
                            <i class="ti ti-trash-x fs-26"></i>
                        </span>
                        <h4 class="mb-1">{{ __('admin.manage.delete_sub_category') }}</h4>
                        <p class="mb-3">{{ __('admin.manage.delete_sub_category_confirmation') }}</p>
                        <div class="d-flex justify-content-center">
                            <a href="javascript:void(0);" class="btn btn-light me-3"
                                data-bs-dismiss="modal">{{ __('admin.common.cancel') }}</a>
                            <button type="submit" class="btn btn-primary">{{ __('admin.common.delete') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /Delete Customer -->

@endsection

@push('scripts')
    <script src="{{ asset('backend/assets/js/category/sub-category.js') }}"></script>
@endpush
