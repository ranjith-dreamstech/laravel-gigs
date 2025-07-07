@extends($layout)
@section('content')
<div class="container my-5 _service-details-loader service-loader">
    <!-- Breadcrumb and Wishlist -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex gap-2">
            <div class="skeleton _service-details-breadcrumb-sm"></div>
            <div class="skeleton _service-details-breadcrumb-sm"></div>
            <div class="skeleton _service-details-breadcrumb-lg"></div>
        </div>
        <div class="skeleton _service-details-wishlist"></div>
    </div>

    <!-- Title and Social Icons -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div class="skeleton _service-details-title"></div>
        <div class="d-flex gap-2 _service-details-social-group">
            <div class="skeleton _service-details-social-icon"></div>
            <div class="skeleton _service-details-social-icon"></div>
            <div class="skeleton _service-details-social-icon"></div>
            <div class="skeleton _service-details-social-icon"></div>
            <div class="skeleton _service-details-social-icon"></div>
        </div>
    </div>

    <!-- Subtitle -->
    <div class="skeleton _service-details-subtitle mb-4"></div>

    <!-- Share Icons -->
    <div class="d-flex gap-2 mb-4">
        <div class="skeleton _service-details-share-icon"></div>
        <div class="skeleton _service-details-share-icon"></div>
        <div class="skeleton _service-details-share-icon"></div>
        <div class="skeleton _service-details-share-icon"></div>
        <div class="skeleton _service-details-share-icon"></div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Carousel -->
            <div class="mb-3">
                <div class="skeleton _service-details-carousel-main"></div>
            </div>
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="skeleton _service-details-carousel-thumb"></div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="skeleton _service-details-carousel-thumb"></div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="skeleton _service-details-carousel-thumb"></div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="skeleton _service-details-carousel-thumb"></div>
                </div>
            </div>

            <!-- Description -->
            <div class="skeleton _service-details-text w-90 mb-2"></div>
            <div class="skeleton _service-details-text w-100 mb-2"></div>
            <div class="skeleton _service-details-text w-95 mb-2"></div>
            <div class="skeleton _service-details-text w-80 mb-4"></div>

            <!-- FAQ Section -->
            <div class="_service-details-faq mt-5">
                <div class="skeleton _service-details-faq-title mb-4"></div>
                <div class="mb-3">
                    <div class="skeleton _service-details-faq-question w-60 mb-2"></div>
                    <div class="skeleton _service-details-faq-answer w-100"></div>
                    <div class="skeleton _service-details-faq-answer w-90 mt-1"></div>
                </div>
                <div class="mb-3">
                    <div class="skeleton _service-details-faq-question w-50 mb-2"></div>
                    <div class="skeleton _service-details-faq-answer w-100"></div>
                    <div class="skeleton _service-details-faq-answer w-85 mt-1"></div>
                </div>
                <div class="mb-3">
                    <div class="skeleton _service-details-faq-question w-40 mb-2"></div>
                    <div class="skeleton _service-details-faq-answer w-100"></div>
                    <div class="skeleton _service-details-faq-answer w-80 mt-1"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="sticky-top" style="top: 100px;">
                <!-- Booking Card -->
                <div class="card p-3 mb-4">
                    <div class="skeleton _service-details-price mb-3"></div>
                    <div class="skeleton _service-details-extra mb-2"></div>
                    <div class="skeleton _service-details-extra mb-2"></div>
                    <div class="skeleton _service-details-book-btn mt-3"></div>
                </div>

                <!-- Member Card -->
                <div class="card p-3">
                    <div class="d-flex align-items-center mb-3">
                        <div class="skeleton _service-details-avatar me-3"></div>
                        <div class="w-100">
                            <div class="skeleton _service-details-member-name mb-2"></div>
                            <div class="skeleton _service-details-member-role"></div>
                        </div>
                    </div>
                    <div class="skeleton _service-details-info mb-2"></div>
                    <div class="skeleton _service-details-info mb-2"></div>
                    <div class="skeleton _service-details-info mb-2"></div>
                    <div class="skeleton _service-details-info mb-2"></div>
                    <div class="skeleton _service-details-info mb-3"></div>
                    <div class="skeleton _service-details-message-btn mt-3"></div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Breadcrumb -->
<div class="breadcrumb-bar breadcrumb-bar-info breadcrumb-info d-none main-content">
    <div class="breadcrumb-img">
        <div class="breadcrumb-left">
            <img src="/frontend/assets/img/bg/banner-bg-03.png" alt="Gigs Details Banner">
        </div>
    </div>
    <input type="hidden" name="slug" id="slug" data-slug="{{ $service->slug }}">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 col-12 text-start">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/" aria-label="Go to Home" title="Home Page">{{ __('web.common.home') }}</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="/gigs" aria-label="Go to Gigs" title="GIgs Page">{{ __('web.common.gigs') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('web.home.gigs_details') }}</li>
                    </ol>
                </nav>
                <h2 class="breadcrumb-title" aria-label="{{ $service->title }}">
                    {{ $service->title }}
                </h2>
                <ul class="info-links">
                    <li>
                        <i class="ti ti-star-filled text-warning"></i><span class="service-rating">0</span> <span class="reviews-count"></span>
                    </li>
                    <li>
                        <i class="ti ti-file"></i><span class="order_in_queue"></span>
                    </li>
                    <li>
                        <i class="ti ti-calendar-due"></i><span class="created_at"></span>
                    </li>
                    <li>
                        <i class="ti ti-home-shield"></i><span class="buyer"></span>
                    </li>
                    <li class="border-0">
                        <div class="tranlator d-flex align-items-center">
                            <img src="/frontend/assets/img/flags/us.svg" alt="US flag"
                                class="img-fluid img me-2 language">
                            <span class="location"></span>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="col-lg-4 col-12">
                <ul class="breadcrumb-links service-details">
                    <li class="mb-3 me-0 ">
                        @if(Auth::guard('web')->check())
                        <a href="#" class="fav-icon {{$isWishlisted ? 'favourite' : ''}}" data-id="{{$service->id}}" aria-label="Whishlist" title="Add to Whishlist"><span><i class="{{ $isWishlisted ? 'ti ti-heart-filled' : ' ti ti-heart' }} @if($isWishlisted) hearticon @endif"></i></span>{{ __('web.home.add_to_wishlist') }}</a>
                        @endif
                    </li>
                    <li class="me-0">
                        <div class="social-links d-flex align-items-center breadcrumb-social justify-content-lg-end">
                            {{__('web.home.share')}}
                            <ul class="ms-3">
                                <li><a href="javascript:void(0);" aria-label="Follow us on Facebook" title="Facebook"><i class="fa-brands fa-facebook"></i></a></li>
                                <li><a href="javascript:void(0);" aria-label="Follow us on Twitter" title="Twitter"><i class="fa-brands fa-x-twitter"></i></a></li>
                                <li><a href="javascript:void(0);" aria-label="Follow us on Instagram" title="Instagram"><i class="fa-brands fa-instagram"></i></a></li>
                                <li><a href="javascript:void(0);" aria-label="Follow us on Google" title="Google"><i class="fa-brands fa-google"></i></a></li>
                                <li><a href="javascript:void(0);" aria-label="Follow us on Youtube" title="Youtube"><i class="fa-brands fa-youtube"></i></a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- /Breadcrumb -->
<div class="page-content content d-none main-content">
    <div class="container">
        <div class="row">

            <!-- Service Details -->
            <div class="col-lg-8">

                <!-- Slider -->
                <div class="slider-card service-slider-card" id="service-carousel">

                </div>
                <!-- /Slider -->


                <!-- About Gigs -->
                <div class="service-wrap">
                    <h3>{{__('web.home.about_this_gig')}}</h3>
                    <p class="service-description"></p>
                </div>
                <!-- /About Gigs -->

                <div class="service-wrap service-wrap why-work-section d-none">
                    <h3>{{__('web.home.why_work_with_me')}}</h3>
                    <div class="why-work-with-me">

                    </div>
                </div>

                <!-- FAQ Lists -->
                <div class="service-wrap service-faq">
                    <h3>{{__('web.home.faq')}}</h3>
                    <div class="faq-lists">

                    </div>
                </div>
                <!-- /FAQ Lists -->

                <!-- Recent Works -->
                <div class="service-wrap d-none" id="recent-works-section">
                    <div class="row align-items-center">
                        <div class="col-sm-8">
                            <h3>{{__('web.home.recent_works')}}</h3>
                        </div>
                        <div class="col-sm-4">
                            <div class="owl-nav mynav1 nav-control"></div>
                        </div>
                    </div>
                    <div class="owl-carousel recent-carousel">

                    </div>
                </div>
                <!-- /Recent Works -->

                <!-- Review Lists -->
                <div class="review-widget">
                    <div class="review-title sort-search-gigs">
                        <div class="row align-items-center">
                            <div class="col-sm-6">
                                <h3>Reviews (<span class="total_reviews"></span>)</h3>
                            </div>
                        </div>
                    </div>

                    <!-- Total Ratings -->
                    <div class="total-rating align-items-center">
                        <div class="total-review">
                            <!-- Progress 1 -->
                            <div class="progress-lvl mb-2">
                                <h6>5 {{ __('web.home.star_ratings') }}</h6>
                                <div class="progress">
                                    <div class="progress-bar bg-warning" id="5_star_progress" role="progressbar"
                                        aria-label="Success example" style="width: 0%" aria-valuenow="25"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p id="5_star_count">0</p>
                            </div>

                            <!-- Progress 2 -->
                            <div class="progress-lvl mb-2">
                                <h6>4 {{ __('web.home.star_ratings') }}</h6>
                                <div class="progress">
                                    <div class="progress-bar bg-warning" id="4_star_progress" role="progressbar"
                                        aria-label="Success example" style="width: 0%" aria-valuenow="25"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p id="4_star_count">0</p>
                            </div>

                            <!-- Progress 3 -->
                            <div class="progress-lvl mb-2">
                                <h6>3 {{ __('web.home.star_ratings') }}</h6>
                                <div class="progress">
                                    <div class="progress-bar bg-warning" id="3_star_progress" role="progressbar"
                                        aria-label="Success example" style="width: 0%" aria-valuenow="25"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p id="3_star_count">0</p>
                            </div>

                            <!-- Progress 4 -->
                            <div class="progress-lvl mb-2">
                                <h6>2 {{ __('web.home.star_ratings') }}</h6>
                                <div class="progress">
                                    <div class="progress-bar bg-warning" id="2_star_progress" role="progressbar"
                                        aria-label="Success example" style="width: 0%" aria-valuenow="25"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p id="2_star_count">0</p>
                            </div>

                            <!-- Progress 5 -->
                            <div class="progress-lvl">
                                <h6>1 {{ __('web.home.star_ratings') }}</h6>
                                <div class="progress">
                                    <div class="progress-bar bg-warning" id="1_star_progress" role="progressbar"
                                        aria-label="Success example" style="width: 0%" aria-valuenow="25"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p id="1_star_count">0</p>
                            </div>
                        </div>
                        <div class="total-reviews text-center bg-white">
                            <h6> {{ __('web.home.customer_reviews_ratings')}} </h6>
                            <h2> <span class="average_ratings">0.0</span> / 5.0 </h2>
                            <div class="icons d-flex align-items-center justify-content-center gap-1 mb-2 star_icons">
                                <i class="ti ti-star-filled text-warning"></i>
                                <i class="ti ti-star-filled text-warning"></i>
                                <i class="ti ti-star-filled text-warning"></i>
                                <i class="ti ti-star-filled text-warning"></i>
                                <i class="ti ti-star-filled text-warning"></i>
                            </div>
                            <p class="text-center">{{ __('web.home.based_on')}} <span class="total_reviews">0</span> {{ __('web.common.reviews') }}</p>
                        </div>
                    </div>
                    <!-- Total Ratings -->

                    <div class="d-none" id="review_list_main_card">
                        <ul class="review-lists home-reviews mt-4"
                            id="review_list_container"
                            data-booking="{{ Auth::guard('web')->check() ? isBookingCompleted($gigsInfo->id, current_user()->id) : 0 }}"
                            data-gig_user_id="{{ $gigsInfo->user_id }}">

                        </ul>
                        <div class="text-center dark-btn">
                            <a href="javascript:void(0);" class="btn btn-dark text-center fs-13 load-more-reviews-btn" aria-label="Load More Reviews" title="Load More"> {{ __('web.common.load_more')}} </a>
                        </div>
                    </div>
                </div>
                <!-- /Review Lists -->

                @if (Auth::guard('web')->check() && current_user()->id != $gigsInfo->user_id && reviewExists($gigsInfo->id) == false)
                <!-- Review Tags -->
                <div class="login-card" id="leave_review_card">
                    <div class="login-heading text-start mb-4">
                        <h5>{{ __('web.home.leave_a_review') }}</h5>
                    </div>

                    <form id="reviewForm" autocomplete="off">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="set-rating position-relative mb-2">
                                    <label class="mb-1 fw-medium text-dark mb-1">{{ __('web.home.your_rating') }}<span class="text-primary"> *</span>
                                    </label>
                                    <div class="rating-selection rating-selection1" id="gigs_ratings">
                                        <input type="checkbox" name="rating" id="service5" class="gigs_ratings" value="5"><label for="service5"></label>
                                        <input type="checkbox" name="rating" id="service4" class="gigs_ratings" value="4"><label for="service4"></label>
                                        <input type="checkbox" name="rating" id="service3" class="gigs_ratings" value="3"><label for="service3"></label>
                                        <input type="checkbox" name="rating" id="service2" class="gigs_ratings" value="2"><label for="service2"></label>
                                        <input type="checkbox" name="rating" id="service1" class="gigs_ratings" value="1"><label for="service1"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-wrap form-focus">
                                    <label class="mb-1 fw-medium text-dark">{{ __('web.home.comments') }}<span class="text-primary"> *</span> </label>
                                    <textarea class="form-control text-area" id="comments" name="comments"></textarea>
                                    <span class="error-text text-danger" id="comments_error"></span>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary member-btn submit-review">{{ __('web.home.submit_review') }}</button>
                    </form>
                </div>
                <!-- /Review Tags -->
                @endif

            </div>
            <!-- /Service Details -->

            <!-- Member Details -->
            <div class="col-lg-4 theiaStickySidebar">
                <div class="row gx-3 row-gap-3 mb-4">
                    <div class="col-xl-4 col-lg-6 col-sm-4 col-6">
                        <div class="buy-box">
                            <i class="feather-clock"></i>
                            <p>{{__('web.home.delivery_time')}}</p>
                            <h6>{{ $gigsInfo->days }} {{__('web.home.day')}}</h6>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-sm-4 col-6">
                        <div class="buy-box">
                            <i class="feather-cloud"></i>
                            <p>{{__('web.home.total_sales')}}</p>
                            <h6>{{ $formattedSalesCount }}</h6>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-sm-4 col-6">
                        <div class="buy-box">
                            <i class="feather-eye"></i>
                            <p>{{__('web.home.total_views')}}</p>
                            <h6>{{ $formattedSalesCount }}</h6>
                        </div>
                    </div>
                </div>

                <div class="service-widget">
                    <div class="service-amt p-3 price-lvl price-lvl1 bg-light ">
                        <h3 class="text-grey">
                            <span class="d-block text-grey"> {{__('web.home.price')}} </span>
                            {{ $currencySymbol }}{{ $gigsInfo->general_price }}
                        </h3>
                    </div>

                    <input type="hidden" name="gigs_id" id="gigs_id" value="{{ $gigsInfo->id }}">
                    <input type="hidden" name="gigs_price" id="gigs_price" value="{{ $gigsInfo->general_price }}">
                    <input type="hidden" name="auth_user_id" id="auth_user_id" value="{{ current_user()->id ?? '' }}">
                    <div class="input-block form-wrap form-focus">
                        <label class="mb-1 fw-medium text-dark"> Quantity <span class="text-primary">*</span> </label>
                        <select class="select form-control quantity" data-select2-id="1" tabindex="-1"
                            aria-hidden="true">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4 </option>
                        </select>
                    </div>

                    @if($extraServices->isNotEmpty())
                    <div class="service-widget service-select-widget">
                        <h5 class="mb-3">{{ __('web.home.extra_services') }}</h5>

                        @foreach($extraServices as $service)
                        <div class="service-select d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <label class="custom_radio" for="extra_service_{{ $service->id }}">
                                    <input type="checkbox" name="extra_service[]" value="{{ $service->id }}">
                                    <span class="checkmark"></span>
                                    <span class="m-0 service-head-text">{{ $service->name }}
                                        <span> {{ __('web.user.delivery_in') }} {{ $service->days }} {{__('web.gigs.addon_days_label')}} </span>
                                    </span>
                                </label>
                            </div>
                            <p class="price m-0">{{ $currencySymbol }}{{ $service->price }}</p>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    @if(!empty($gigsInfo->fast_service_tile))
                    <div class="service-widget service-select-widget">
                        <h5 class="mb-3">{{__('web.home.super_fast_service')}}</h5>
                        <div class="service-select d-flex align-items-center justify-content-between m-0 p-0 border-0">
                            <div class="d-flex align-items-center">
                                <label class="custom_radio" for="fase_service">
                                    <input type="checkbox" name="fast_service" id="fast_service">
                                    <span class="checkmark"></span>
                                    <span class="m-0 service-head-text">{{ $gigsInfo->fast_service_tile }}
                                        <span>{{ __('web.user.delivery_in')}} {{ $gigsInfo->fast_service_days }} {{__('web.gigs.addon_days_label')}}</span>
                                    </span>
                                </label>
                            </div>
                            <p class="price text-primary high m-0 bg-primary-transparent">
                                {{ $currencySymbol }}{{ $gigsInfo->fast_service_price }}
                            </p>
                        </div>
                    </div>
                    @endif
                    
                    @if(auth()->check())
                    @if (Auth::guard('web')->check() && current_user()->id != $gigsInfo->user_id)
                    {{-- User is logged in and NOT owner of gig -> Show "Buy this Gig" --}}
                    <a href="#" id="sumbit_btn" class="btn btn-primary w-100 mb-0" aria-label="Buy this Gig" title="Buy This Gig">
                        <i class="feather-shopping-cart"></i> {{ __('web.home.buy_this_gig') }}
                    </a>
                    @else
                    {{-- User is logged in and IS owner of gig -> Show "My Gigs" --}}
                    <a href="{{ route('seller.seller-gigs') }}" class="btn btn-primary w-100 mb-0" aria-label="My Gigs" title="My Gigs">
                        <i class="feather-shopping-cart"></i> {{ __('web.home.my_gigs') }}
                    </a>
                    @endif
                    @else
                    {{-- User NOT logged in -> Show Login to Buy --}}
                    <a href="{{ route('user-login') }}" class="btn btn-primary w-100 mb-0" aria-label="Login to buy this gig" title="Login required to purchase">
                        <i class="feather-shopping-cart"></i> {{ __('web.home.buy_this_gig') }}
                    </a>
                    @endif

                </div>
                <div class="service-widget member-widget d-none">
                    <div class="user-details">
                        <div class="user-img users-img">
                            <img src="/frontend/assets/img/user/user-05.jpg" alt="provider" class="provider-img">
                        </div>
                        <div class="user-info">
                            <h5>
                                <span class="me-2 provider-name" aria-label="{{ __('web.home.provider_name') }}">{{ __('web.home.provider_name') }}</span>
                            </h5>
                            <p><i class="fa-solid fa-star"></i><span class="provider-rating"></span></p>
                        </div>
                    </div>
                    <ul class="member-info">
                        <li>
                            {{__('web.home.from')}}
                            <span class="provider-location"></span>
                        </li>
                        <li>
                            {{__('web.home.member_since')}}
                            <span class="provider-member_since"></span>
                        </li>
                        <li>
                            {{__('web.home.speaks')}}
                            <span class="provider-speaks"></span>
                        </li>
                        <li>
                            {{__('web.home.last_project_delivery')}}
                            <span class="provider-last_project_delivery">29 Jan 2024</span>
                        </li>
                        <li>
                            {{__('web.home.avg_response_time')}}
                            <span class="provider-avg_response_time"></span>
                        </li>
                    </ul>
                    <div class="about-me new-about about-me-container">
                        <h6>{{__('web.user.about_me')}}</h6>
                        <p><span class="more-content"></span></p>
                        <a href="javascript:void(0);" class="read-more">{{ __('web.blog.read_more') }}</a>
                    </div>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#contact_me"
                        class="btn btn-primary mb-0 w-100">{{__('web.home.contact_me')}}</a>
                </div>
            </div>
            <!-- /Member Details -->

        </div>

        <!-- Recent Work -->
        <div class="recent-works">
            <div class="row">
                <div class="col-md-12">
                    <div class="title-sec">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h3>{{__('web.home.recent_works')}}</h3>
                            </div>
                            <div class="col-md-4">
                                <div class="owl-nav worknav nav-control nav-top"></div>
                            </div>
                        </div>
                    </div>
                    <div class="gigs-slider owl-carousel">
                       
                    </div>
                </div>
            </div>
        </div>
        <!-- /Recent Work -->
    </div>
</div>

<!-- Order Details -->
<div class="modal new-modal fade" id="order_details" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Order Details</h5>
                <button type="button" class="close-btn" data-bs-dismiss="modal"><span>×</span></button>
            </div>
            <form id="validateVehicleBook" autocomplete="off" method="POST" action="{{ route('booking.checkout', ['slug' => $slug]) }}">
                @csrf
                <div class="modal-body service-modal">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="order-status">
                                <div class="order-item">
                                    <input type="hidden" name="gig_id" id="gig_id" value="">
                                    <input type="hidden" name="extra_service_total" id="extra_service_total" value="">
                                    <input type="hidden" name="extra_service_ids" id="extra_service_ids" value="">
                                    <input type="hidden" name="total_price" id="total_price" value="">
                                    <input type="hidden" name="fast_service_total" id="fast_service_total" value="">
                                    <input type="hidden" name="quantity" id="quantity" value="">
                                    <input type="hidden" name="final_price" id="final_price" value="">
                                    <div class="order-img">
                                        <img id="gigs_image" src="{{ uploadedAsset('default') }}" alt="gigs">
                                    </div>
                                    <div class="order-info">
                                        <h5 id="gigs_title" aria-label="{{ $gigsInfo->title }}">{{ $gigsInfo->title }}</h5>
                                        
                                    </div>
                                </div>
                                <h6 class="title">Details</h6>
                                <div class="user-details">
                                    <div class="user-img">
                                        <img id="providerImg" src="{{ uploadedAsset('default', 'profile') }}" alt="user">
                                    </div>
                                    <div class="user-info">
                                        <h5 id="gigs_owner">
                                            <span class="visually-hidden">{{ __('web.home.provider_name') }}: </span>
                                            <span class="location" id="gigs_location"></span>
                                        </h5>
                                        <p id="gigs_rating"></p>
                                    </div>
                                </div>
                                <h6 class="title">Service Details</h6>
                                <div class="detail-table table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">Service</th>
                                                <th scope="col">Quantity</th>
                                                <th scope="col">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td id="gigs_service_title"></td>
                                                <td id="gigs_service_qut">0</td>
                                                <td class="text-primary" id="gigs_service_price">$0</td>
                                            </tr>
                                            <tr>
                                                <td id="gigs_extra_title"></td>
                                                <td id="gigs_extra_qut">0</td>
                                                <td class="text-primary" id="gigs_extra_price">$0</td>
                                            </tr>
                                            <tr>
                                                <td id="gigs_fast_title"></td>
                                                <td id="gigs_fast_qut">0</td>
                                                <td class="text-primary" id="gigs_fast_price">$0</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="2" scope="col">Grand Total</th>
                                                <th class="text-primary" id="">$0</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="modal-btn">
                                    <div class="row gx-2">
                                        <div class="col-6">
                                            <a href="#" data-bs-dismiss="modal" class="btn btn-light text-dark w-100 justify-content-center">Cancel</a>
                                        </div>
                                        <div class="col-6">
                                            <button type="submit" id="validate_btn" class="btn btn-primary w-100">Pay Now</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /Order Details -->
@endsection
@push('plugins')
<!-- Slick JS -->
<script src="{{ asset('frontend/assets/plugins/slick/slick.js') }}"></script>
<!-- Sticky Sidebar JS -->
<script src="{{ asset('frontend/assets/plugins/theia-sticky-sidebar/ResizeSensor.js') }}"></script>
<script src="{{ asset('frontend/assets/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js') }}"></script>
@endpush
@push('scripts')
<script src="{{ asset('frontend/custom/js/service/details.js') }}"></script>
<script src="{{ asset('/frontend/assets/js/gigs-details.js') }}"></script>
<script src="{{ asset('/frontend/custom/js/service/recent-works.js') }}"></script>
@endpush
