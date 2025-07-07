@extends($userlayout)
@section('content')
 <!-- Page Content -->
 <div class="page-wrapper">
    <div class="page-content content">
        <div class="row">
            <!-- Manage Gigs -->
            <div class="col-12">
                <div class="d-flex mb-4 align-items-center justify-content-between gap-3 flex-wrap">
                    <div class="main-title">
                        <h4 class="mb-0">{{ __('web.user.favourites') }}</h4>
                    </div>
                    <div class="head-info mb-0">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#remove-favourite"><i class="ti ti-trash me-1"></i>Remove All</button>
                    </div>
                </div>
                <div class="row card-loader" id="skeletonCard">
                    <div class="col-xl-4 col-md-6">
                        <div class="gigs-grid gigs-card-skeleton skeleton">
                            <div class="gigs-img position-relative">
                                <div class="img-slider owl-carousel">
                                    <div class="slide-images">
                                        <div class="gigs-image-skeleton skeleton"></div>
                                    </div>
                                </div>
                                <div class="fav-selection">
                                 
                                    <div class="fav-icon-skeleton skeleton remove-favorite" data-gig-id="12345"></div>
                                   
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
                    <div class="col-xl-4 col-md-6">
                        <div class="gigs-grid gigs-card-skeleton skeleton">
                            <div class="gigs-img position-relative">
                                <div class="img-slider owl-carousel">
                                    <div class="slide-images">
                                        <div class="gigs-image-skeleton skeleton"></div>
                                    </div>
                                </div>
                                <div class="fav-selection">
                                    <div class="fav-icon-skeleton skeleton remove-favorite" data-gig-id="12345"></div>
                                    
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
                    <div class="col-xl-4 col-md-6">
                        <div class="gigs-grid gigs-card-skeleton skeleton">
                            <div class="gigs-img position-relative">
                                <div class="img-slider owl-carousel">
                                    <div class="slide-images">
                                        <div class="gigs-image-skeleton skeleton"></div>
                                    </div>
                                </div>
                                <div class="fav-selection">
                                    <div class="fav-icon-skeleton skeleton remove-favorite" data-gig-id="12345"></div>
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
                </div>

                <div class="row d-none real-card" id="my_favorite_list">
                </div>
            </div>
            <!-- /Manage Gigs -->
        </div>
        
        <div class="text-center d-none real-card">
            <button type="button" class="btn btn-lg btn-dark load_more_btn" data-page="2">
                <i class="ti ti-loader-3 me-2"></i> {{ __('web.user.load_more') }}
            </button>
        </div>
    </div>
</div>
<!-- /Page Content -->

<!-- remove fav -->
<div class="modal new-modal fade" id="remove-favourite" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <form id="remove-all-fav-form">
                    <div class="row">
                        <div class="col-md-12">
                            <span class="close-icon" style="cursor: pointer;"><i class="ti ti-xbox-x"></i></span>
                            <div class="mb-4">
                                <p class="mb-1">{{ __('web.user.are_you_sure') }}</p>
                                <span>{{ __('web.user.confirmation_to_delete_all_favourite') }}</span>
                            </div>
                            <div class="modal-btn d-flex align-items-center">
                                <button type="button" class="close-btn btn-light btn-sm btn border-0 w-100 me-3" data-bs-dismiss="modal">{{ __('web.user.no') }}</button>
                                <button class="btn btn-danger btn-sm w-100" type="submit">{{ __('web.user.yes') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('plugins')

<script src="{{ asset('frontend/assets/plugins/owl_carousel/js/owl.carousel.min.js') }}"></script>

@endpush

@push('styles')
<link rel="stylesheet" href="{{ asset('frontend/assets/plugins/owl_carousel/css/owl.carousel.min.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('frontend/custom/js/favorites.js') }}"></script>
@endpush
