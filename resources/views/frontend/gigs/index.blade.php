@extends('frontend.seller.partials.app')
@push('styles')
<!-- Owl carousel CSS -->
<link rel="stylesheet" href="{{ asset('frontend/assets/css/owl.carousel.min.css') }}">
@endpush
@section('content')

<!-- Page Content -->
<div class="page-wrapper">
    <div class="page-content content bg-light">
        <div class="row">
            <!-- Manage Gigs -->
            <div class="col-12">
                <div class="d-flex mb-4 align-items-center justify-content-between gap-3 flex-wrap">
                    <div class="main-title">
                        <h4 class="mb-0">{{ __('web.gigs.title') }}</h4>
                    </div>
                    <div class="head-info mb-0">
                        <a href="/add-gigs" class="btn btn-primary btn-md">{{ __('web.gigs.add_new_gig') }}</a>
                    </div>
                </div>

                <ul class="nav nav-tabs nav-tabs-bottom mb-4" id="statusTabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-status="1">{{ __('web.gigs.active') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-status="0">{{ __('web.gigs.inactive') }}</a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane show active" id="bottom-tab1">
                        <div class="row d-none appendGigs" id="bottomActive">
                            <!-- Service List -->
                        </div>

                        <div class="row" id="skeletonCard">
                            @for ($i = 0; $i < 3; $i++)
                                <div class="col-xl-4 col-md-6">
                                    <div class="gigs-grid gigs-card-skeleton skeleton">
                                        <div class="gigs-img position-relative">
                                            <div class="img-slider owl-carousel">
                                                <div class="slide-images">
                                                    <div class="gigs-image-skeleton skeleton"></div>
                                                </div>
                                            </div>
                                            <div class="fav-selection">
                                                <div class="fav-icon-skeleton skeleton"></div>
                                                <div class="fav-icon-skeleton skeleton"></div>
                                            </div>
                                        </div>
                                        <div class="gigs-content">
                                            <div class="label-skeleton skeleton mb-2"></div>
                                            <div class="title-skeleton skeleton mb-2"></div>
                                            <div class="footer-skeleton d-flex justify-content-between align-items-center">
                                                <div class="price-skeleton skeleton"></div>
                                                <div class="badge-skeleton skeleton"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>

                    <div class="text-center loadMore d-none">
                        <div class="text-center">
                            <a href="#" id="loadMoreBtn" class="btn btn-md btn-dark d-inline-flex align-items-center">
                                <i class="ti ti-loader-3 me-2"></i>{{ __('web.gigs.load_more') }}
                            </a>
                        </div>
                    </div>
                </div>
                <!-- /Manage Gigs -->
            </div>
        </div>
    </div>
</div>
<!-- /Page Content -->

<!-- remove fav -->
<div class="modal new-modal fade" id="remove-favourite" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-sm modal-dialog-centered remove-modal">
        <div class="modal-content">
            <div class="modal-body">
                <form id="deleteGigs">
                    <input type="hidden" id="deleteGigId" name="delete_id">
                    <div class="row">
                        <div class="col-md-12 delete-card text-center">
                            <span>
                                <img src="/backend/assets/img/icons/seller-gigs-trash.svg" alt="img" class="mb-3">
                            </span>
                            <h6 class="mb-1">{{ __('web.gigs.are_you_sure') }}</h6>
                            <p class="mb-4">{{ __('web.gigs.delete_confirmation') }}</p>
                            <div class="modal-btn d-flex align-items-center">
                                <button type="button" class="close-btn btn-light btn-sm btn border-0 w-100 me-3 fw-medium" data-bs-dismiss="modal">
                                    {{ __('web.gigs.cancel') }}
                                </button>
                                <button class="btn btn-primary btn-sm w-100" type="submit">
                                    {{ __('web.gigs.delete') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /remove fav -->


@endsection

@push('plugins')
<!-- Owl Carousel JS -->
<script src="{{ asset('/frontend/assets/js/owl.carousel.min.js') }}"></script>
@endpush

@push('scripts')
<script src="{{ asset('/frontend/assets/js/gigs-list.js') }}"></script>
@endpush
