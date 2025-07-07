@extends('frontend.buyer.partials.app')
@section('content')

<!-- Page Content -->
<div class="page-wrapper">
    <div class="page-content content">
        <div class="main-title mb-4">
            <h4>{{ __('web.user.my_sellers') }}</h4>
        </div>
        <div class="row seller-list card-loader">
            <div class="col-xl-4 col-md-6">
                <div class="card seller-card-skeleton skeleton">
                    <div class="card-body text-center">
                        <div class="seller-avatar-skeleton skeleton mx-auto mb-2"></div>
                        <div class="seller-name-skeleton skeleton mx-auto mb-2"></div>
                        <div class="seller-job-skeleton skeleton mx-auto mb-2"></div>
                        <div class="seller-location-wrapper d-flex justify-content-center align-items-center gap-2">
                            <div class="seller-flag-skeleton skeleton"></div>
                            <div class="seller-dot-skeleton skeleton"></div>
                            <div class="seller-gigs-skeleton skeleton"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card seller-card-skeleton skeleton">
                    <div class="card-body text-center">
                        <div class="seller-avatar-skeleton skeleton mx-auto mb-2"></div>
                        <div class="seller-name-skeleton skeleton mx-auto mb-2"></div>
                        <div class="seller-job-skeleton skeleton mx-auto mb-2"></div>
                        <div class="seller-location-wrapper d-flex justify-content-center align-items-center gap-2">
                            <div class="seller-flag-skeleton skeleton"></div>
                            <div class="seller-dot-skeleton skeleton"></div>
                            <div class="seller-gigs-skeleton skeleton"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card seller-card-skeleton skeleton">
                    <div class="card-body text-center">
                        <div class="seller-avatar-skeleton skeleton mx-auto mb-2"></div>
                        <div class="seller-name-skeleton skeleton mx-auto mb-2"></div>
                        <div class="seller-job-skeleton skeleton mx-auto mb-2"></div>
                        <div class="seller-location-wrapper d-flex justify-content-center align-items-center gap-2">
                            <div class="seller-flag-skeleton skeleton"></div>
                            <div class="seller-dot-skeleton skeleton"></div>
                            <div class="seller-gigs-skeleton skeleton"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card seller-card-skeleton skeleton">
                    <div class="card-body text-center">
                        <div class="seller-avatar-skeleton skeleton mx-auto mb-2"></div>
                        <div class="seller-name-skeleton skeleton mx-auto mb-2"></div>
                        <div class="seller-job-skeleton skeleton mx-auto mb-2"></div>
                        <div class="seller-location-wrapper d-flex justify-content-center align-items-center gap-2">
                            <div class="seller-flag-skeleton skeleton"></div>
                            <div class="seller-dot-skeleton skeleton"></div>
                            <div class="seller-gigs-skeleton skeleton"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card seller-card-skeleton skeleton">
                    <div class="card-body text-center">
                        <div class="seller-avatar-skeleton skeleton mx-auto mb-2"></div>
                        <div class="seller-name-skeleton skeleton mx-auto mb-2"></div>
                        <div class="seller-job-skeleton skeleton mx-auto mb-2"></div>
                        <div class="seller-location-wrapper d-flex justify-content-center align-items-center gap-2">
                            <div class="seller-flag-skeleton skeleton"></div>
                            <div class="seller-dot-skeleton skeleton"></div>
                            <div class="seller-gigs-skeleton skeleton"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card seller-card-skeleton skeleton">
                    <div class="card-body text-center">
                        <div class="seller-avatar-skeleton skeleton mx-auto mb-2"></div>
                        <div class="seller-name-skeleton skeleton mx-auto mb-2"></div>
                        <div class="seller-job-skeleton skeleton mx-auto mb-2"></div>
                        <div class="seller-location-wrapper d-flex justify-content-center align-items-center gap-2">
                            <div class="seller-flag-skeleton skeleton"></div>
                            <div class="seller-dot-skeleton skeleton"></div>
                            <div class="seller-gigs-skeleton skeleton"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row seller-list d-none real-card" id="my_sellers_list">

        </div>
        <div class="text-center d-none real-card">
            <button type="button" class="btn btn-lg btn-dark d-inline-flex align-items-center load_more_btn d-none"><i
                    class="ti ti-loader-3 me-2"></i>Load More</button>
        </div>
    </div>
</div>
<!-- /Page Content -->

@endsection

@push('scripts')
<script src="{{ asset('frontend/custom/js/my-sellers.js') }}"></script>
@endpush
