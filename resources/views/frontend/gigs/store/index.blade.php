@extends($layout)
@section('content')
@push('styles')
<link rel="stylesheet" href="assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css">
@endpush
<!-- Breadcrumb -->
<div class="breadcrumb-bar breadcrumb-bar-info">
    <div class="breadcrumb-img">
        <div class="breadcrumb-left">
            <img src="frontend/assets/img/bg/breadcrump-bg-01.png" alt="img">
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-12">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index.html">{{ __('web.common.home') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('web.gigs.create_gig') }}</li>
                    </ol>
                </nav>
                <h2 class="breadcrumb-title mb-0">
                    {{ __('web.gigs.create_gig') }}
                </h2>
            </div>
        </div>
    </div>
    <div class="breadcrumb-img">
        <div class="breadcrumb-right">
            <img src="frontend/assets/img/bg/breadcrump-bg-02.png" alt="img">
        </div>
    </div>
</div>
<!-- /Breadcrumb -->

<!-- Page Content -->
<div class="page-content">
    <div class="container">

        <form id="createGigsForm">
            <input type="hidden" name="currency" id="currency" value="{{ $currencySymbol }}">
            <div class="row justify-content-center">

                <div class="col-lg-10">
                    <div class="marketing-section gig-post">
                        <div class="gigs-step">
                            <ul>
                                @for ($i = 1; $i <= 6; $i++)
                                    <li>
                                    <span>
                                        <img src="{{ asset('frontend/assets/img/icons/' . __('web.gigs.step_0' . $i . '_icon')) }}" alt="icon">
                                    </span>
                                    <p>{{ __('web.gigs.step_0' . $i . '_text') }}</p>
                                    <h6>{{ __('web.gigs.step_0' . $i . '_title') }}</h6>
                                    </li>
                                    @endfor
                            </ul>
                        </div>
                        <div class="marketing-bg">
                            <img src="frontend/assets/img/bg/market-bg.png" alt="img" class="market-bg">
                            <img src="frontend/assets/img/bg/market-bg-01.png" alt="img" class="market-img">
                        </div>
                    </div>
                </div>

                <!-- General -->
                <div class="col-lg-10">
                    <div class="add-property-wrap">
                        <div class="property-info">
                            <h5 class="mb-1">{{ __('web.gigs.general_title') }}</h5>
                            <p>{{ __('web.gigs.general_subtitle') }}</p>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-wrap">
                                    <label class="col-form-label">{{ __('web.gigs.title_label') }}<span class="text-danger ms-1">*</span></label>
                                    <input type="text" name="title" id="title" maxlength="200" class="form-control mb-2">
                                    <span><i class="ti ti-info-circle me-1"></i>{{ __('web.gigs.title_note') }}</span>
                                    <span class="invalid-feedback" id="title_error"></span>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="form-wrap">
                                    <label class="col-form-label">{{ __('web.gigs.price_label') }} ({{ $currencySymbol }})<span class="text-danger ms-1">*</span></label>
                                    <input type="text" name="general_price" id="general_price" maxlength="6" class="form-control NumOnly">
                                    <span class="invalid-feedback" id="general_price_error"></span>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="form-wrap">
                                    <label class="col-form-label">{{ __('web.gigs.delivery_label') }}<span class="text-danger ms-1">*</span></label>
                                    <select class="select form-control" name="days" id="days">
                                        <option value="">{{ __('web.common.select') }}</option>
                                        @for ($i = 1; $i <= 30; $i++)
                                            <option value="{{ $i }}">{{ $i }} day{{ $i > 1 ? 's' : '' }}</option>
                                            @endfor
                                    </select>
                                    <span class="invalid-feedback" id="days_error"></span>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="form-wrap">
                                    <label class="col-form-label">{{ __('web.gigs.category_label') }}<span class="text-danger ms-1">*</span></label>
                                    <select class="select2 form-control" name="category_id" id="category_id">
                                        <option value="">{{ __('web.gigs.category_placeholder') }}</option>
                                        @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback" id="category_id_error"></span>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="form-wrap">
                                    <label class="col-form-label">{{ __('web.gigs.sub_category_label') }}<span class="text-danger ms-1">*</span></label>
                                    <select class="select2 form-control" name="sub_category_id" id="sub_category_id">
                                        <option value="">{{ __('web.gigs.sub_category_placeholder') }}</option>
                                    </select>
                                    <span class="invalid-feedback" id="sub_category_id_error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-wrap">
                                    <label class="col-form-label">{{ __('web.gigs.revision_label') }}<span class="text-danger ms-1">*</span></label>
                                    <input type="text" name="no_revisions" id="no_revisions" maxlength="2" class="form-control NumOnly">
                                    <span class="invalid-feedback" id="no_revisions_error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-wrap">
                                    <label class="col-form-label">{{ __('web.gigs.tags_label') }}</label>
                                    <div class="input-block input-block-tagsinput mb-1">
                                        <input type="text" data-role="tagsinput" class="input-tags form-control" name="tags" value="" id="html">
                                    </div>
                                    <span>{{ __('web.gigs.tags_note') }}</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-wrap">
                                    <label class="col-form-label">{{ __('web.gigs.description_label') }}<span class="text-danger ms-1">*</span></label>
                                    <textarea class="form-control mb-2" rows="6" name="description" maxlength="1000" id="description" placeholder="{{ __('web.gigs.description_placeholder') }}"></textarea>
                                    <span><i class="ti ti-info-circle me-1"></i>{{ __('web.gigs.description_note') }}</span>
                                    <span class="invalid-feedback" id="general_dis_error"></span>
                                </div>
                            </div>
                        </div>
                        <!-- Buyer -->
                        <div class="property-info">
                            <h5 class="mb-1">{{ __('web.gigs.buyer_title') }}</h5>
                            <p>{{ __('web.gigs.buyer_subtitle') }}</p>
                        </div>
                        <div class="add-content">

                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <a href="javascript:void(0);" class="btn btn-dark multiple-amount-add"><i class="ti ti-plus"></i>{{ __('web.gigs.add_new') }}</a>
                            </div>
                            <div class="col-md-12">
                                <label class="custom_check extra-serv">
                                    <input type="checkbox" class="disable-check">
                                    <span class="checkmark"></span>{{ __('web.gigs.superfast_label') }}
                                </label>
                            </div>
                            <div class="col-md-4">
                                <div class="form-wrap">
                                    <label class="col-form-label">{{ __('web.gigs.fast_service_label') }}</label>
                                    <input type="text" name="fast_service_tile" id="fast_service_tile" maxlength="50" class="form-control exta-label" disabled>
                                    <span class="invalid-feedback" id="fast_service_tile_error"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-wrap">
                                    <label class="col-form-label">{{ __('web.gigs.fast_service_price_label') }} ({{ $currencySymbol }})</label>
                                    <input type="text" name="fast_service_price" id="fast_service_price" maxlength="6" class="form-control exta-label NumOnly" disabled>
                                    <span class="invalid-feedback" id="fast_service_price_error"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-wrap">
                                    <label class="col-form-label">{{ __('web.gigs.fast_service_days_label') }}</label>
                                    <input type="text" name="fast_service_days" id="fast_service_days" maxlength="6" class="form-control exta-label NumOnly" disabled>
                                    <span class="invalid-feedback" id="fast_service_days_error"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="col-form-label">{{ __('web.gigs.work_mode_label') }}</label>
                                <div class="form-wrap gig-option">
                                    <label class="custom_radio">
                                        <input type="radio" name="buyer" value="remote" checked>
                                        <span class="checkmark"></span>{{ __('web.gigs.buyer_option_remote') }}
                                    </label>
                                    <label class="custom_radio">
                                        <input type="radio" name="buyer" value="buyer">
                                        <span class="checkmark"></span>{{ __('web.gigs.buyer_option_onsite') }}
                                    </label>
                                </div>
                                <div class="form-wrap">
                                    <label class="col-form-label">{{ __('web.gigs.buyer_description_label') }}</label>
                                    <textarea class="form-control mb-2" rows="6" maxlength="1000" name="fast_dis" id="fast_dis" placeholder="{{ __('web.gigs.buyer_description_placeholder') }}"></textarea>
                                    <span><i class="ti ti-info-circle me-1"></i>{{ __('web.gigs.buyer_description_note') }}</span>
                                </div>
                            </div>
                        </div>
                        <!-- Buyer -->
                        <!-- Upload -->
                        <div class="property-info">
                            <h5 class="mb-1">{{ __('web.gigs.upload_title') }}</h5>
                            <p>{{ __('web.gigs.upload_subtitle') }}.</p>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="col-form-label">{{ __('web.gigs.upload_images_tab') }}</label>
                                <ul class="nav upload-list">
                                    <li>
                                        <a href="#" class="active" data-bs-toggle="tab" data-bs-target="#upload-img">
                                            <span>
                                                <img src="frontend/assets/img/icons/upload-01.svg" alt="icon">
                                            </span>
                                            <h6>{{ __('web.gigs.upload_images_tab') }}</h6>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" data-bs-toggle="tab" data-bs-target="#upload-video">
                                            <span>
                                                <img src="frontend/assets/img/icons/upload-02.svg" alt="icon">
                                            </span>
                                            <h6>{{ __('web.gigs.upload_videos_tab') }}</h6>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" data-bs-toggle="tab" data-bs-target="#upload-link">
                                            <span>
                                                <img src="frontend/assets/img/icons/upload-03.svg" alt="icon">
                                            </span>
                                            <h6>{{__('web.gigs.upload_links_tab') }}</h6>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane show active" id="upload-img">
                                        <div class="drag-upload form-wrap mb-2">
                                            <input type="file" name="gigs_image[]" id="gigs_image" accept="image/*" multiple>
                                            <div class="img-upload">
                                                <p><i class="feather-upload-cloud"></i>{{ __('web.gigs.drag') }}</p>
                                            </div>
                                            <span class="invalid-feedback text-center" id="gigs_image_error"></span>
                                        </div>
                                        <span><i class="ti ti-info-circle me-1"></i>{{ __('web.gigs.upload_images_note') }}</span>
                                        <div class="upload-file-wrap d-flex align-items-center flex-wrap" id="imagePreview">

                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="upload-video">
                                        <div class="drag-upload form-wrap">
                                            <input type="file" name="gigs_video[]" id="gigs_video" accept="video/*">
                                            <div class="img-upload">
                                                <p><i class="feather-upload-cloud"></i>{{ __('web.gigs.drag') }}</p>
                                            </div>
                                            <span class="invalid-feedback text-center" id="gigs_video_error"></span>
                                        </div>
                                        <div class="upload-file-wrap d-flex align-items-center flex-wrap">
                                            <div class="upload-file-wrap d-flex align-items-center flex-wrap" id="VideoPreview">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="upload-link">
                                        <div class="link-upload">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-wrap">
                                                        <label class="col-form-label">{{ __('web.gigs.video_platform_label') }}<span class="text-danger ms-1">*</span></label>
                                                        <select class="select" name="video_platform" id="video_platform">
                                                            <option value="">{{ __('web.gigs.video_platform_placeholder') }}</option>
                                                            <option value="youtube">{{ __('web.gigs.video_platform_youtube') }}</option>
                                                            <option value="vimeo">{{ __('web.gigs.video_platform_vimeo') }}</option>
                                                        </select>
                                                        <span class="invalid-feedback" id="video_platform_error"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="form-wrap">
                                                        <label class="col-form-label">{{ __('web.gigs.video_link_label') }}<span class="text-danger ms-1">*</span></label>
                                                        <input type="text" id="video_link" name="video_link" class="form-control">
                                                        <span class="invalid-feedback" id="video_link_error"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Upload -->

                        <!-- faq -->
                        <div class="property-info d-flex align-items-center flex-wrap gap-2 justify-content-between">
                            <div>
                                <h5 class="mb-1">{{ __('web.gigs.faq_title') }}</h5>
                                <p>{{ __('web.gigs.faq_subtitle') }}</p>
                            </div>
                            <a href="javascript:void(0);" class="btn btn-dark mb-0" data-bs-toggle="modal" data-bs-target="#faq_add">
                                <i class="ti ti-plus"></i> {{ __('web.gigs.add_faq_button') }}
                            </a>
                        </div>
                        <div class="faq-wrapper faq-lists" id="faqContainer">
                            <!-- Dynamic FAQs will be appended here -->
                        </div>
                    </div>
                </div>
                <!-- /General -->

                <!-- Upload -->
                <div class="col-lg-12">
                    <div class="btn-item text-center">
                        <a href="#" class="btn btn-light">{{ __('web.common.cancel') }}</a>
                        <button type="submit" class="btn btn-primary validateBtn">
                            <span class="btn-text">{{ __('web.gigs.publish') }}</span>
                        </button>
                    </div>
                </div>
                <!-- /Upload -->
            </div>
        </form>

    </div>
</div>
<!-- /Page Content -->


<div class="modal new-modal fade" id="faq_add" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('web.gigs.add_faq_button') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('web.general.close') }}"></button>
            </div>

            <div class="modal-body service-modal">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-wrap">
                            <label class="col-form-label">{{ __('web.gigs.faq_question_label') }}</label>
                            <input name="question" id="question" class="form-control" placeholder="{{ __('web.gigs.faq_question_placeholder') }}">
                            <span class="invalid-feedback" id="question_error"></span>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-wrap">
                            <label class="col-form-label">{{ __('web.gigs.faq_answer_label') }}</label>
                            <textarea name="answer" id="answer" class="form-control" rows="6" placeholder="{{ __('web.gigs.faq_answer_placeholder') }}"></textarea>
                            <span class="invalid-feedback" id="answer_error"></span>
                        </div>
                    </div>
                </div>

                <div class="modal-btn mt-4">
                    <a href="javascript:void(0);" class="btn btn-primary w-100" id="addFaqBtn">
                        {{ __('web.gigs.add_faq_button') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Contact -->
<div class="modal custom-modal fade" id="success_gigs" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="success-message text-center">
                    <div class="success-popup-icon">
                        <img src="{{ asset('backend/assets/img/icons/happy-icon.svg') }}" alt="icon">
                    </div>
                    <div class="success-content">
                        <h4>{{ __('web.gigs.success_title') }}</h4>
                        <p>
                            {{ __('web.gigs.success_message') }}
                            <span>“{{ __('web.gigs.success_sample_gig') }}”</span>
                        </p>
                    </div>
                    <div class="col-lg-12 text-center">
                        <a href="{{ route('home') }}" class="btn btn-primary">{{ __('web.gigs.back_to_gigs') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Success Contact -->

@endsection


@push('plugins')
<script src="{{ asset('/assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js') }}"></script>
@endpush

@push('scripts')
<script src="{{ asset('/frontend/assets/js/gigs-create.js') }}"></script>
@endpush
