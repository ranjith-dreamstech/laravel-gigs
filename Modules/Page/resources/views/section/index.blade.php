@extends('admin.admin')
@section('content')
<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="content me-4">

        <!-- Breadcrumb -->
        <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
            <div class="my-auto mb-2">
                <h2 class="mb-1">{{ __('admin.cms.section') }}</h2>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">{{ __('admin.common.home') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('admin.cms.section') }}</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- /Breadcrumb -->

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
            <table class="table" id="sectionTable">
                <thead class="thead-light">
                    <tr>
                        <th>{{ strtoupper(__('admin.cms.section_name')) }}</th>
                        <th>{{ strtoupper(__('admin.cms.theme_id')) }}</th>
                        <th>{{ strtoupper(__('admin.common.status')) }}</th>
                        @if (hasPermission($permissions, 'section', 'edit'))
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


<div class="modal fade" id="add_banner_sec">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ __('admin.cms.edit_section') }}</h4>
                <button type="button" class="btn-close custom-btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ti ti-x"></i>
                </button>
            </div>
            <form id="addBannerOneForm" autocomplete="off">
                <input type="hidden" name="section_id" id="section_id">

                <div class="modal-body">
                    <div id="section_id_1" style="display: none;">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <div class="mb-3">
                                    <label for="thumbnail_image_one" class="form-label">{{ __('admin.cms.thumbnail_image') }}</label>
                                    <input type="file" name="thumbnail_image_one" id="thumbnail_image_one" class="form-control" accept="image/*" onchange="previewThumbnailOne(this)">
                                    <div class="invalid-feedback" id="thumbnail_image_one_error"></div>
                                </div>
                                <img id="thumbnail_preview_one" src="" alt="Preview" class="mt-2" style="max-height: 120px; display: none;">
                            </div>

                            <div class="form-group col-md-12">
                                <div class="mb-3">
                                    <label for="label_one" class="form-label">{{ __('admin.cms.label') }}</label>
                                    <input type="text" name="label_one" id="label_one" class="form-control" placeholder="{{ __('admin.cms.enter_label') }}">
                                    <div class="invalid-feedback" id="label_error"></div>
                                </div>
                            </div>


                            <div class="form-group col-md-12">
                                <div class="mb-3">
                                    <label for="line_one" class="form-label">{{ __('admin.cms.line_one') }}</label>
                                    <input type="text" name="line_one" id="line_one" class="form-control" placeholder="{{ __('admin.cms.enter_line_one') }}">
                                    <div class="invalid-feedback" id="line_one_error"></div>
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <div class="mb-3">
                                    <label for="line_two" class="form-label">{{ __('admin.cms.line_two') }}</label>
                                    <input type="text" name="line_two" id="line_two" class="form-control" placeholder="{{ __('admin.cms.enter_line_two') }}">
                                    <div class="invalid-feedback" id="line_two_error"></div>
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <div class="mb-3">
                                    <label for="description_one" class="form-label">{{ __('admin.common.description') }}</label>
                                    <textarea type="text" name="description_one" id="description_one" class="form-control" placeholder="{{ __('admin.cms.enter_description') }}"></textarea>
                                    <div class="invalid-feedback" id="description_error"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="section_id_2" style="display: none;">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <div class="mb-3">
                                    <label for="thumbnail_image_two" class="form-label">{{ __('admin.cms.thumbnail_image') }}</label>
                                    <input type="file" name="thumbnail_image_two" id="thumbnail_image_two" class="form-control" accept="image/*" onchange="previewThumbnailTwo(this)">
                                    <div class="invalid-feedback" id="thumbnail_image_two_error"></div>
                                </div>
                                <img id="thumbnail_preview_two" src="" alt="Preview" class="mt-2" style="max-height: 120px; display: none;">
                            </div>

                            <div class="form-group col-md-12">
                                <div class="mb-3">
                                    <label for="label_two" class="form-label">{{ __('admin.cms.label') }}</label>
                                    <input type="text" name="label_two" id="label_two" class="form-control" placeholder="{{ __('admin.cms.enter_label') }}">
                                    <div class="invalid-feedback" id="label_two_error"></div>
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <div class="mb-3">
                                    <label for="description_two" class="form-label">{{ __('admin.common.description') }}</label>
                                    <textarea type="text" name="description_two" id="description_two" class="form-control" placeholder="{{ __('admin.cms.enter_description') }}"></textarea>
                                    <div class="invalid-feedback" id="description_two_error"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="section_id_3" style="display: none;">
                        <div class="row">

                            <div class="form-group col-md-6">
                                <div class="mb-3">
                                    <label for="label_1" class="form-label">{{ __('admin.cms.label') }} 1</label>
                                    <input type="text" name="label_1" id="label_1" class="form-control" placeholder="{{ __('admin.cms.enter_label') }}" maxlength="50">
                                    <div class="invalid-feedback" id="label_1_error"></div>
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <div class="mb-3">
                                    <label for="dis_1" class="form-label">{{ __('admin.common.description') }} 1</label>
                                    <input type="text" name="dis_1" id="dis_1" class="form-control" placeholder="{{ __('admin.cms.enter_description') }}" maxlength="100">
                                    <div class="invalid-feedback" id="dis_1_error"></div>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <div class="mb-3">
                                    <label for="label_2" class="form-label">{{ __('admin.cms.label') }} 2</label>
                                    <input type="text" name="label_2" id="label_2" class="form-control" placeholder="{{ __('admin.cms.enter_label') }}" maxlength="50">
                                    <div class="invalid-feedback" id="label_2_error"></div>
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <div class="mb-3">
                                    <label for="dis_2" class="form-label">{{ __('admin.common.description') }} 2</label>
                                    <input type="text" name="dis_2" id="dis_2" class="form-control" placeholder="{{ __('admin.cms.enter_description') }}" maxlength="100">
                                    <div class="invalid-feedback" id="dis_2_error"></div>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <div class="mb-3">
                                    <label for="label_3" class="form-label">{{ __('admin.cms.label') }} 3</label>
                                    <input type="text" name="label_3" id="label_3" class="form-control" placeholder="{{ __('admin.cms.enter_label') }}" maxlength="50">
                                    <div class="invalid-feedback" id="label_3_error"></div>
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <div class="mb-3">
                                    <label for="dis_3" class="form-label">{{ __('admin.common.description') }} 3</label>
                                    <input type="text" name="dis_3" id="dis_3" class="form-control" placeholder="{{ __('admin.cms.enter_description') }}" maxlength="100">
                                    <div class="invalid-feedback" id="dis_3_error"></div>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <div class="mb-3">
                                    <label for="label_4" class="form-label">{{ __('admin.cms.label') }} 4</label>
                                    <input type="text" name="label_4" id="label_4" class="form-control" placeholder="{{ __('admin.cms.enter_label') }}" maxlength="50">
                                    <div class="invalid-feedback" id="label_4_error"></div>
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <div class="mb-3">
                                    <label for="dis_4" class="form-label">{{ __('admin.common.description') }} 4</label>
                                    <input type="text" name="dis_4" id="dis_4" class="form-control" placeholder="{{ __('admin.cms.enter_description') }}" maxlength="100">
                                    <div class="invalid-feedback" id="dis_4_error"></div>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <div class="mb-3">
                                    <label for="label_5" class="form-label">{{ __('admin.cms.label') }} 5</label>
                                    <input type="text" name="label_5" id="label_5" class="form-control" placeholder="{{ __('admin.cms.enter_label') }}" maxlength="50">
                                    <div class="invalid-feedback" id="label_5_error"></div>
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <div class="mb-3">
                                    <label for="dis_5" class="form-label">{{ __('admin.common.description') }} 5</label>
                                    <input type="text" name="dis_5" id="dis_5" class="form-control" placeholder="{{ __('admin.cms.enter_description') }}" maxlength="100">
                                    <div class="invalid-feedback" id="dis_5_error"></div>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <div class="mb-3">
                                    <label for="label_6" class="form-label">{{ __('admin.cms.label') }} 6</label>
                                    <input type="text" name="label_6" id="label_6" class="form-control" placeholder="{{ __('admin.cms.enter_label') }}" maxlength="50">
                                    <div class="invalid-feedback" id="label_6_error"></div>
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <div class="mb-3">
                                    <label for="dis_6" class="form-label">{{ __('admin.common.description') }} 6</label>
                                    <input type="text" name="dis_6" id="dis_6" class="form-control" placeholder="{{ __('admin.cms.enter_description') }}" maxlength="100">
                                    <div class="invalid-feedback" id="dis_6_error"></div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div id="section_id_4" style="display: none;">
                        <div class="row">
                            @for ($i = 1; $i <= 5; $i++)
                                <div class="form-group col-md-6">
                                <div class="mb-3">
                                    <label for="image_{{ $i }}" class="form-label">{{ __('admin.cms.image') }} {{ $i }}</label>
                                    <input type="file" name="image_{{ $i }}" id="image_{{ $i }}" class="form-control" accept="image/*" onchange="previewImage(this, 'imagePrev{{ $i }}')">
                                    <div class="invalid-feedback" id="image_{{ $i }}_error"></div>
                                </div>
                                <img id="imagePrev{{ $i }}" src="" alt="Preview {{ $i }}" class="mt-2" style="max-height: 120px; display: none;">
                        </div>
                        @endfor
                    </div>
                </div>

        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">{{ __('admin.common.cancel') }}</button>
            <button type="submit" id="btn_banner_one" class="btn btn-primary banner_one">{{ __('admin.common.save_changes') }}</button>
        </div>
        </form>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('backend/assets/js/page/section.js') }}"></script>
@endpush
