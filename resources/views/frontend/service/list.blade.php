@extends($layout)
@section('content')
    <!-- Breadcrumb -->
    <div class="breadcrumb-bar breadcrumb-bar-info">
        <div class="breadcrumb-img">
            <div class="breadcrumb-left">
                <img src="{{ asset('frontend/assets/img/bg/banner-bg-03.png') }}" alt="Banner">
            </div>
        </div>
        <label for="q" class="d-none">Search Query</label>
        <input type="hidden" name="q" id="q" value="{{ request('q') }}">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-12">
                    <nav aria-label="breadcrumb" class="page-breadcrumb" id="custom-category" data-category_id="{{ $filterCategory }}">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="/" aria-label="Go to Home" title="Home Page">{{ __('web.common.home') }}</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">{{__('web.home.services')}}</li>
                            <li class="breadcrumb-item active d-none" aria-current="page" id="category-breadcrumb"></li>
                        </ol>
                    </nav>
                    <h2 class="breadcrumb-title mb-0" id="breadcrumb-text">
                        Loading...
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <!-- /Breadcrumb -->
    <div class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">

                    <!-- Category Section -->
                    <div class="marketing-section d-none" id="category_banner">
                        <div class="marketing-content">
                            <h2 class="category-title">Loading..</h2>
                            <p class="category-description"></p>
                            <div class="marketing-bg">
                                <img src="{{ asset('frontend/assets/img/bg/market-bg.png') }}" alt="Marketing section background graphic" class="market-bg">
                                <img src="{{ asset('frontend/assets/img/bg/market-bg-01.png') }}" alt="Marketing section highlight overlay" class="market-img">
                            </div>
                        </div>
                    </div>
                    <!-- /Category Section -->

                    <!-- Trending Categories -->
                    <div class="trend-section">
                        <div class="row align-items-center">
                            <div class="col-sm-10">
                                <h5>Loading trending categories...</h5>
                            </div>
                            <div class="col-sm-2 text-sm-end">
                                <div class="owl-nav trend-nav nav-control nav-top" aria-label="Trending category carousel controls"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="trend-items owl-carousel" id="subcategory-container-items">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Trending Categories -->

                    <!-- Filter -->
                    @include('frontend.service.partials.filter')
                    <!-- /Filter -->

                </div>
            </div>

            <!-- Service -->
            <div class="service-gigs">
                <div class="row">

                    <div class="col-lg-12">
                        <div class="row list-loader" id="service-loader">
                            <div class="col-lg-4 col-md-6">
                                <div class="giglist-card">
                                    <div class="giglist-img-wrapper skeleton">
                                        <div class="giglist-badges">
                                            <span class="giglist-badge skeleton"></span>
                                            <span class="giglist-badge skeleton"></span>
                                        </div>
                                        <div class="giglist-action-icons">
                                            <span class="giglist-icon skeleton"></span>
                                            <span class="giglist-icon skeleton"></span>
                                        </div>
                                        <div class="giglist-profile-thumb skeleton"></div>
                                    </div>

                                    <div class="giglist-body">
                                        <div class="giglist-category skeleton"></div>
                                        <div class="giglist-location skeleton"></div>
                                        <div class="giglist-title skeleton"></div>
                                        <div class="giglist-rating skeleton"></div>
                                        <div class="giglist-footer">
                                            <div class="giglist-share skeleton"></div>
                                            <div class="giglist-delivery skeleton"></div>
                                            <div class="giglist-price skeleton"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="giglist-card">
                                    <div class="giglist-img-wrapper skeleton">
                                        <div class="giglist-badges">
                                            <span class="giglist-badge skeleton"></span>
                                            <span class="giglist-badge skeleton"></span>
                                        </div>
                                        <div class="giglist-action-icons">
                                            <span class="giglist-icon skeleton"></span>
                                            <span class="giglist-icon skeleton"></span>
                                        </div>
                                        <div class="giglist-profile-thumb skeleton"></div>
                                    </div>

                                    <div class="giglist-body">
                                        <div class="giglist-category skeleton"></div>
                                        <div class="giglist-location skeleton"></div>
                                        <div class="giglist-title skeleton"></div>
                                        <div class="giglist-rating skeleton"></div>
                                        <div class="giglist-footer">
                                            <div class="giglist-share skeleton"></div>
                                            <div class="giglist-delivery skeleton"></div>
                                            <div class="giglist-price skeleton"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="giglist-card">
                                    <div class="giglist-img-wrapper skeleton">
                                        <div class="giglist-badges">
                                            <span class="giglist-badge skeleton"></span>
                                            <span class="giglist-badge skeleton"></span>
                                        </div>
                                        <div class="giglist-action-icons">
                                            <span class="giglist-icon skeleton"></span>
                                            <span class="giglist-icon skeleton"></span>
                                        </div>
                                        <div class="giglist-profile-thumb skeleton"></div>
                                    </div>

                                    <div class="giglist-body">
                                        <div class="giglist-category skeleton"></div>
                                        <div class="giglist-location skeleton"></div>
                                        <div class="giglist-title skeleton"></div>
                                        <div class="giglist-rating skeleton"></div>
                                        <div class="giglist-footer">
                                            <div class="giglist-share skeleton"></div>
                                            <div class="giglist-delivery skeleton"></div>
                                            <div class="giglist-price skeleton"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row service-list" id="service-container">

                        </div>
                    </div>
                    <div class="col-md-12">
                        <!-- Pagination -->
                        <div class="pagination" id="gig-pagination">

                        </div>
                        <!-- /Pagination -->
                    </div>
                </div>
            </div>
            <!-- /Service -->

        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('frontend/custom/js/service/list.js') }}"></script>
@endpush
