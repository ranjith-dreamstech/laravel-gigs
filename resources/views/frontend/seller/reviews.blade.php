@extends('frontend.seller.partials.app')
@section('content')
   <!-- Page Content -->
   <div class="page-wrapper">
    <div class="page-content content bg-light">
        <div class="main-title mb-4">
            <h4>{{ __('web.user.reviews') }}</h4>
        </div>

        <!--User Reviews -->
        <div class="col-xl-12 col-lg-12">
            <div class="user-review">
                <ul class="review-lists card-loader" id="skeletonCard">
                    <!-- Skeleton for Review 1 -->
                <!-- Skeleton for Review -->
                <li class="review-item">
                    <div class="review-wrap skeleton">
                        <div class="review-user-info skeleton">
                            <div class="review-img skeleton reviewer-avatar"></div>
                            <div class="reviewer-info skeleton">
                                <div class="label-skeleton reviewer-name"></div>
                                <div class="label-skeleton reviewer-location"></div>
                                <div class="label-skeleton reviewer-rating"></div>
                                <div class="label-skeleton review-time"></div>
                            </div>
                        </div>
                        <div class="review-content skeleton">
                            <div class="label-skeleton review-title"></div>
                            <div class="label-skeleton review-text"></div>
                            <div class="label-skeleton review-text"></div>
                        </div>
                        <div class="table-action skeleton">
                            <div class="label-skeleton delete-action"></div>
                        </div>
                    </div>
                </li>

        
                    <!-- Skeleton for Review 2 -->
                   <!-- Skeleton for Review -->
                    <li class="review-item">
                        <div class="review-wrap skeleton">
                            <div class="review-user-info skeleton">
                                <div class="review-img skeleton reviewer-avatar"></div>
                                <div class="reviewer-info skeleton">
                                    <div class="label-skeleton reviewer-name"></div>
                                    <div class="label-skeleton reviewer-location"></div>
                                    <div class="label-skeleton reviewer-rating"></div>
                                    <div class="label-skeleton review-time"></div>
                                </div>
                            </div>
                            <div class="review-content skeleton">
                                <div class="label-skeleton review-title"></div>
                                <div class="label-skeleton review-text"></div>
                                <div class="label-skeleton review-text"></div>
                            </div>
                            <div class="table-action skeleton">
                                <div class="label-skeleton delete-action"></div>
                            </div>
                        </div>
                    </li>

                    <!-- More skeleton reviews as needed... -->
                </ul>
                <ul id="review_list" class="real-card">
                    <!-- JS appends here -->
                </ul>
            </div>
        </div>

      
        
        
        <!-- /User Reviews -->
        <div class="review-btn real-card d-none text-center mt-3">
            <a href="javascript:void(0);"
               class="btn btn-dark text-white load_more_btn" >
               <i class="ti ti-loader-3 me-1 rotate"></i>{{ __('web.user.load_more') }}
            </a>
        </div>
        
    </div>
</div>
<!-- /Page Content -->


<!-- Delete Review Modal -->
<div class="modal fade" id="deleteReviewModal">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center">
                <form id="deleteReviewForm">
                    @csrf
                    <input type="hidden" name="id" id="delete_review_id">
                    <span class="avatar avatar-lg bg-transparent-danger rounded-circle text-danger mb-3">
                        <i class="ti ti-trash-x fs-26"></i>
                    </span>
                    <h4 class="mb-1">{{ __('web.user.delete_review') }}</h4>
                    <p class="mb-3">{{ __('web.user.confirmation_delete_review') }}</p>
                    <div class="d-flex justify-content-center">
                        <a href="javascript:void(0);" class="btn btn-light me-3" data-bs-dismiss="modal">{{ __('web.common.cancel') }}</a>
                        <button type="submit" class="btn btn-primary submitbtn">{{ __('web.user.yes_delete') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<!-- /Delete details -->

<!-- Leave Review Modal  -->
<div class="modal new-modal fade" id="leave_review" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Leave a Review</h5>
                <button type="button" class="close-btn" data-bs-dismiss="modal"><span>×</span></button>
            </div>
            <div class="modal-body service-modal">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-wrap form-focus">
                            <label class="mb-1 fw-medium text-dark">Name <span class="text-primary">*</span></label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-wrap form-focus">
                            <label class="mb-1 fw-medium text-dark">Email <span class="text-primary">*</span> </label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-wrap form-focus">
                            <label class="mb-1 fw-medium text-dark">Write a Review <span class="text-primary">*</span></label>
                            <textarea class="text-area form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-btn d-flex pt-3">
                    <a href="javascript:void(0);" class="btn btn-primary w-100">Submit a Review</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Password Modal -->
@endsection
@push('scripts')
<script src="{{ asset('frontend/custom/js/seller-review.js') }}"></script>
@endpush
