@extends('admin.admin')
@section('content')

<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="content me-4">
        <div class="mb-4">
            <a href="{{ route('admin.pageIndex') }}" class="d-flex align-items-center"><span class="me-1"><i class="ti ti-arrow-narrow-left"></i></span>{{__('admin.page.pages')}}</a>
        </div>
        <div class="row">
            <div class="add_page col-md-8 pageSectionList shadow-sm">
                <div class="card mb-0">
                    <div class="card-header">
                        <h5>{{ __('admin.page.add_page') }}</h5>
                    </div>
                    <form id="addPageForm" autocomplete="off">
                        @csrf
                        <input type="hidden" name="lang_id" id="lang_id" value="{{ $authUser->language_id }}">
                        <div class="card-body pb-1">
                            <div class="border-bottom mb-3">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="title" class="form-label">{{ __('admin.page.title') }} <span class="text-danger">*</span></label>
                                            <input type="text" name="title" id="title" placeholder="{{ __('admin.page.title_placeholder') }}" class="form-control">
                                            <span class="invalid-feedback" id="title_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="slug" class="form-label">{{ __('admin.page.slug') }} <span class="text-danger">*</span></label>
                                            <input type="text" name="slug" id="slug" placeholder="{{ __('admin.page.slug_placeholder') }}" class="form-control">
                                            <span class="invalid-feedback" id="slug_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="keyword" class="form-label">{{ __('admin.page.keywords') }}</label>
                                            <input type="text" name="keyword" id="keyword" placeholder="{{ __('admin.page.keywords_placeholder') }}" class="form-control input-tags">
                                            <span class="invalid-feedback" id="keyword_error"></span>
                                        </div>
                                    </div>
                                    <div class="textareasContainer" id="draggable-left"></div>
                                    <div class="col-md-12 text-end">
                                        <button type="button" id="addTextarea" class="btn btn-primary mb-2">{{ __('admin.page.add_section') }}</button>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="mb-3">
                                    <h6>{{ __('admin.page.seo_settings') }}</h6>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="mete_title" class="form-label">{{ __('admin.page.meta_title') }} <span class="text-danger">*</span></label>
                                            <input type="text" name="mete_title" id="mete_title" placeholder="{{ __('admin.page.meta_title_placeholder') }}" class="form-control">
                                            <span class="invalid-feedback" id="mete_title_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="meta_key" class="form-label">{{ __('admin.page.meta_keywords') }} <span class="text-danger">*</span></label>
                                            <input type="text" name="meta_key" id="meta_key" placeholder="{{ __('admin.page.meta_keywords_placeholder') }}" class="form-control">
                                            <span class="invalid-feedback" id="meta_key_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="meta_description" class="form-label">{{ __('admin.page.meta_description') }}</label>
                                            <textarea rows="4" name="meta_description" id="meta_description" placeholder="{{ __('admin.page.meta_description_placeholder') }}" class="form-control"></textarea>
                                            <span class="invalid-feedback" id="meta_description_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="canonical_url" class="form-label">{{ __('admin.page.canonical_url') }} <span class="text-danger">*</span></label>
                                            <input type="text" name="canonical_url" id="canonical_url" placeholder="{{ __('admin.page.canonical_url_placeholder') }}" class="form-control">
                                            <span class="invalid-feedback" id="canonical_url_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="og_title" class="form-label">{{ __('admin.page.og_title') }} <span class="text-danger">*</span></label>
                                            <input type="text" name="og_title" id="og_title" placeholder="{{ __('admin.page.og_title_placeholder') }}" class="form-control">
                                            <span class="invalid-feedback" id="og_title_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="og_description" class="form-label">{{ __('admin.page.og_description') }}</label>
                                            <textarea rows="4" name="og_description" id="og_description" placeholder="{{ __('admin.page.og_description_placeholder') }}" class="form-control"></textarea>
                                            <span class="invalid-feedback" id="og_description_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="og_image" class="form-label">{{ __('admin.page.og_image') }} <span class="text-danger">*</span></label>
                                            <input type="file" name="og_image" id="og_image" class="form-control">
                                            <span class="form-text text-muted"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="theme_id" name="theme_id" value="1">
                        <div class="card-footer d-flex justify-content-end">
                            <a href="javascript:void(0);" class="btn btn-light me-2">{{ __('admin.page.cancel') }}</a>
                            <button type="submit" class="btn btn-primary" id="add-page">{{ __('admin.common.create_new') }}</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="add_page col-md-4">
                <div class="card mb-0">
                    <div class="card-header">
                        <h5>{{ __('admin.page.section_list') }}</h5>
                    </div>
                    <div class="d-flex align-items-center justify-content-center setSection">
                        <button class="btn btn-primary w-100">{{ __('admin.page.screen_one') }}</button>
                        <button class="btn btn-dark w-100">{{ __('admin.page.screen_two') }}</button>
                    </div>
                    <div id="cardContainer" class="section-list d-none real-table"></div>
                    <div class="custom-datatable-filter table-responsive table-loader">
                        <table class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center">
                                        <div class="skeleton th-skeleton th-loader"></div>
                                    </th>
                                    <th>
                                        <div class="skeleton th-skeleton th-loader"></div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @for($i = 0; $i < 5; $i++)
                                    <tr>
                                    <td>
                                        <div class="skeleton data-skeleton data-loader"></div>
                                    </td>
                                    <td>
                                        <div class="skeleton data-skeleton data-loader"></div>
                                    </td>
                                    </tr>
                                    @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.partials.footer')
</div>
<!-- /Page Wrapper -->
@endsection
@push('scripts')
<script src="{{ asset('backend/assets/js/page/add.js') }}"></script>
@endpush
