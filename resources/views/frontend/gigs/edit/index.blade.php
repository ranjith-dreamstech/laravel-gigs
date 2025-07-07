@extends($layout)
@section('content')
@push('styles')
<link rel="stylesheet" href="assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css">
@endpush
<!-- Breadcrumb -->
<div class="breadcrumb-bar breadcrumb-bar-info">
    <div class="breadcrumb-img">
        <div class="breadcrumb-left">
            <img src="/frontend/assets/img/bg/banner-bg-03.png" alt="img">
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
                        <li class="breadcrumb-item" aria-current="page">{{ __('web.gigs.edit_gig') }}</li>
                    </ol>
                </nav>
                <h2 class="breadcrumb-title mb-0">
                    {{ __('web.gigs.edit_gig') }}
                </h2>
            </div>
        </div>
    </div>
</div>
<!-- /Breadcrumb -->

<!-- Page Content -->
<div class="page-content">
    <div class="container">

        <form id="editGigsForm">
            <input type="hidden" id="selected_sub" value="{{ $gigs->sub_category_id }}">
            <div class="row">

                <!-- General -->
                <div class="col-lg-4">
                    <div class="property-info">
                        <h4>{{ __('web.gigs.general_title') }}</h4>
                        <p>{{ __('web.gigs.general_subtitle') }}</p>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="add-property-wrap">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-wrap">
                                    <label class="col-form-label">{{ __('web.gigs.title_label') }}</label>
                                    <input type="text" class="form-control" placeholder="Title for your Gig *" name="title" id="title" maxlength="200" value="{{ $gigs->title }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-wrap">
                                    <label class="col-form-label">{{ __('web.gigs.category_label') }}</label>
                                    <select class="select2 form-control" name="category_id" id="category_id">
                                        <option value="">{{ __('web.gigs.category_placeholder') }}</option>
                                        @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $gigs && $gigs->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-wrap">
                                    <label class="col-form-label">{{ __('web.gigs.sub_category_label') }}</label>
                                    <select class="select2 form-control" name="sub_category_id" id="sub_category_id">
                                        <option value="">{{ __('web.gigs.sub_category_placeholder') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-wrap">
                                    <label class="col-form-label">{{ __('web.gigs.tags_label') }}</label>
                                    <div class="input-block input-block-tagsinput mb-1">
                                        <input type="text"  class="input-tags form-control" name="tags" value="{{ $gigs->tags }}" id="html">
                                    </div>
                                    <span>{{ __('web.gigs.tags_note') }}</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-wrap">
                                    <label class="col-form-label">{{ __('web.gigs.description_label') }}</label>
                                    <textarea class="form-control" rows="6" id="description" maxlength="1000" name="description" placeholder="{{ __('web.gigs.description_placeholder') }} *">{{ $gigs->description }}</textarea>
                                </div>
                                <div class="add-content-general">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-wrap">
                                                <label class="col-form-label">{{ __('web.gigs.price_label') }} ($)</label>
                                                <input type="text" name="general_price" id="general_price" class="form-control" placeholder="For ($)" value="{{ $gigs->general_price }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-wrap">
                                                <label class="col-form-label">{{ __('web.gigs.delivery_label') }}</label>
                                                <select class="select form-control" name="days" id="days">
                                                    <option value="">{{ __('web.gigs.delivery_placeholder') }}</option>
                                                    @for ($i = 1; $i <= 30; $i++)
                                                        <option value="{{ $i }}" {{ $gigs && $gigs->days == $i ? 'selected' : '' }}>
                                                        {{ $i }} day{{ $i > 1 ? 's' : '' }}
                                                        </option>
                                                        @endfor
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-wrap">
                                                <label class="col-form-label">{{ __('web.gigs.revision_label') }}</label>
                                                <input type="text" class="form-control" name="no_revisions" id="no_revisions" placeholder="{{ __('web.gigs.revision_label') }}" value="{{ $gigs->no_revisions }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                              
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /General -->

                <!-- Buyer -->
                <div class="col-lg-4">
                    <div class="property-info">
                        <h4>{{ __('web.gigs.buyer_title') }}</h4>
                        <p>{{ __('web.gigs.buyer_subtitle') }}</p>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="add-property-wrap">
                        <div class="add-content">

                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" name="gig_id" id="gig_id" value="{{ $gigs->id }}">
                                <a href="javascript:void(0);" class="btn btn-secondary multiple-amount-add"><i class="feather-plus-circle"></i>Add New</a>
                            </div>
                            <div class="col-md-12">
                                <label class="custom_check extra-serv">
                                    <input type="checkbox" class="disable-check"
                                        {{ !empty($gigs->fast_service_tile) ? 'checked' : '' }}>
                                    <span class="checkmark"></span>{{ __('web.gigs.superfast_label') }}
                                </label>
                            </div>
                            <div class="col-md-4">
                                <div class="form-wrap">
                                    <label class="col-form-label">{{ __('web.gigs.fast_service_label') }}</label>
                                    <input type="text" name="fast_service_tile" id="fast_service_tile" class="form-control exta-label" value="{{ $gigs->fast_service_tile }}" placeholder="I Can" disabled>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-wrap">
                                    <label class="col-form-label">{{ __('web.gigs.fast_service_price_label') }} ($)</label>
                                    <input type="text" name="fast_service_price" id="fast_service_price" class="form-control exta-label" value="{{ $gigs->fast_service_price }}" placeholder="For ($)" disabled>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-wrap">
                                    <label class="col-form-label">{{ __('web.gigs.fast_service_days_label') }}</label>
                                    <input type="text" id="fast_service_days" name="fast_service_days" value="{{ $gigs->fast_service_days }}" class="form-control exta-label" placeholder="In (Day)" disabled>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <h6>{{ __('web.gigs.work_mode_label') }} *</h6>
                                <div class="form-wrap gig-option">
                                    <label class="custom_radio">
                                        <input type="radio" name="buyer" value="remote" {{ $gigs && $gigs->buyer === 'remote' ? 'checked' : '' }}>
                                        <span class="checkmark"></span>{{ __('web.gigs.buyer_option_remote') }}
                                    </label>
                                    <label class="custom_radio">
                                        <input type="radio" name="buyer" value="buyer" {{ $gigs && $gigs->buyer === 'buyer' ? 'checked' : '' }}>
                                        <span class="checkmark"></span>{{ __('web.gigs.buyer_option_onsite') }}
                                    </label>
                                </div>
                                <div class="form-wrap">
                                    <label class="col-form-label">{{ __('web.gigs.buyer_description_label') }}</label>
                                    <textarea class="form-control" maxlength="1000" name="fast_dis" id="fast_dis" rows="6" placeholder="{{ __('web.gigs.buyer_description_placeholder') }}">{{ $gigs->fast_dis ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Buyer -->

                <!-- Upload -->
                <div class="col-lg-4">
                    <div class="property-info">
                        <h4>{{ __('web.gigs.upload_title') }}</h4>
                        <p>{{ __('web.gigs.upload_subtitle') }}</p>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="add-property-wrap">
                        <div class="row">
                            <div class="col-md-12">
                                <h6>{{ __('web.gigs.upload_images_note') }}</h6>
                                <ul class="nav upload-list">
                                    <li>
                                        <a href="#" class="active" data-bs-toggle="tab" data-bs-target="#upload-img">
                                            <span>
                                                <img src="/frontend/assets/img/icons/Upload-01.svg" alt="icon">
                                            </span>
                                            <h6>{{ __('web.gigs.upload_images_tab') }}</h6>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" data-bs-toggle="tab" data-bs-target="#upload-video">
                                            <span>
                                                <img src="/frontend/assets/img/icons/Upload-02.svg" alt="icon">
                                            </span>
                                            <h6>{{ __('web.gigs.upload_videos_tab') }}</h6>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" data-bs-toggle="tab" data-bs-target="#upload-link">
                                            <span>
                                                <img src="/frontend/assets/img/icons/Upload-03.svg" alt="icon">
                                            </span>
                                            <h6>{{ __('web.gigs.upload_links_tab') }}</h6>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane show active" id="upload-img">
                                        <div class="drag-upload form-wrap">
                                            <input type="file" name="gigs_image[]" id="gigs_image" accept="image/*" multiple>
                                            <div class="img-upload">
                                                <p><i class="feather-upload-cloud"></i>{{ __('web.gigs.drag') }}</p>
                                            </div>
                                        </div>
                                        <div class="upload-file-wrap d-flex align-items-center flex-wrap" id="imagePreview">

                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="upload-video">
                                        <div class="drag-upload form-wrap">
                                            <input type="file" accept="video/*">
                                            <div class="img-upload">
                                                <p><i class="feather-upload-cloud"></i>{{ __('web.gigs.drag') }}</p>
                                            </div>
                                        </div>
                                        <div class="upload-wrap">
                                            <div class="upload-image">
                                                <span>
                                                    <i class="feather-image"></i>
                                                </span>
                                                <h6>Video_gig-1.mp4</h6>
                                            </div>
                                            <a href="javascript:void(0);" class="del-action"><i class="feather-trash-2"></i></a>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="upload-link">
                                        <div class="form-wrap">
                                            <label class="col-form-label">{{ __('web.gigs.video_platform_label') }}</label>
                                            <select class="select form-control" name="video_platform" id="video_platform">
                                                <option value="">{{ __('web.gigs.video_platform_placeholder') }}</option>
                                                <option value="youtube" {{ $gigs && $gigs->video_platform === 'youtube' ? 'selected' : '' }}>{{ __('web.gigs.video_platform_youtube') }}</option>
                                                <option value="vimeo" {{ $gigs && $gigs->video_platform === 'vimeo' ? 'selected' : '' }}>{{ __('web.gigs.video_platform_vimeo') }}</option>
                                            </select>
                                            <span class="invalid-feedback" id="video_platform_error"></span>
                                        </div>
                                        <div class="form-wrap">
                                            <label class="col-form-label">{{ __('web.gigs.video_link_label') }} *</label>
                                            <input type="text" id="video_link" name="video_link" value="{{ $gigs->video_link }}" class="form-control">
                                            <span class="invalid-feedback" id="video_link_error"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Upload -->


                <!-- Faq -->
                <div class="col-lg-4">
                    <div class="property-info">
                        <h4>{{ __('web.gigs.faq_title') }}</h4>
                        <p>{{ __('web.gigs.faq_subtitle') }}.</p>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="add-property-wrap">
                        <div class="row">
                            <div class="col-md-12">
                                <h6>{{ __('web.gigs.faq_subtitle') }}</h6>
                                <div class="col-md-12">
                                    <a href="javascript:void(0);" class="btn btn-secondary multiple-faq-add" data-bs-toggle="modal" data-bs-target="#faq_add"><i class="feather-plus-circle"></i>{{ __('web.gigs.add_faq_button') }}</a>
                                </div>
                                <div class="faq-wrapper faq-lists" id="faqContainer">
                                    <!-- Dynamic FAQs will be appended here -->
                                </div>
                            </div>
                        </div>
                        <div class=" mt-3">
                            <h6>{{ __('web.gigs.status_tab') }}</h6>
                            <div class="">
                                <div class="d-flex align-items-start justify-content-start gap-3">
                                    <div>
                                        <input type="checkbox" id="status" name="status" {{ $gigs->status == 1 ? 'checked' : '' }}>
                                    </div>
                                    <h6 style="margin-bottom: 31px;">{{ __('web.common.status') }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn-item text-end">
                        <a href="#" class="btn btn-secondary">{{ __('web.common.cancel') }}</a>
                        <button type="submit" class="btn btn-primary">{{ __('web.gigs.publish') }}</button>
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

<!-- Gigs Publish -->
<div class="modal custom-modal fade" id="success_gigs" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="success-message text-center">
                    <div class="success-popup-icon">
                        <img src="/frontend/assets/img/icons/happy-icon.svg" alt="icon">
                    </div>
                    <div class="success-content">
                        <h4>{{ __('web.gigs.gigs_publish_success_title') }}</h4>
                        <p>{{ __('web.gigs.gigs_publish_success_message') }}</p>
                    </div>
                    <div class="col-lg-12 text-center">
                        <a href="/seller/seller-gigs" class="btn btn-primary">{{ __('web.gigs.gigs_publish_success_back_btn') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Gigs Publish -->

@endsection

@push('plugins')
<script src="{{ asset('/assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js') }}"></script>
@endpush

@push('scripts')
<script src="{{ asset('/frontend/assets/js/gigs-edit.js') }}"></script>
@endpush
