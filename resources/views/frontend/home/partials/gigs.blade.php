<div class="services-section-two">
    <div class="container">
        <div class="section-header-two text-center" data-aos="fade-up">
            <h2 class="mb-2"><span class="title-bg"></span>{{ $section['section_title'] }}<span
                    class="title-bg2"></span></h2>
            <p>{{ $section['section_label'] }}</p>
        </div>
        <div class="row" data-aos="fade-up">
            @if(!empty($section['section_content']) && count($section['section_content']) > 0)
            @foreach($section['section_content'] as $service)

            <div class="col-xl-4 col-md-6">
                <div class="gigs-grid">
                    <div class="gigs-img">
                        <div class="img-slider owl-carousel">
                            @if(!empty($service['gig_image']) && count($service['gig_image']) > 0)
                            @foreach($service['gig_image'] as $image)
                            <div class="slide-images">
                                <a href="{{ route('service.detail', $service['slug']) }}">
                                    <img src="{{ $image }}" class="img-fluid" alt="{{ $service['title'] }}">
                                </a>
                            </div>
                            @endforeach
                            @endif
                        </div>
                        <div class="card-overlay-badge">
                            @if($service['is_feature'] == 1)
                            <a href="{{ route('service.detail', $service['slug']) }}"><span class="badge bg-warning"><i
                                        class="feather-star"></i>{{__('web.home.featured')}}</span></a>
                            @endif
                            @if($service['is_hot'] == 1)
                            <a href="{{ route('service.detail', $service['slug']) }}"><span class="badge bg-danger"><i
                                        class="fa-solid fa-meteor"></i>{{__('web.home.hot')}}</span></a>
                            @endif
                        </div>
                        <div class="fav-selection">
                            <a href="javascript:void(0);" aria-label="Open video">
                                <i class="feather-video"></i>
                            </a>
                            @auth
                            <a href="javascript:void(0);" aria-label="Add to wishlist"
                                class="fav-icon @if($service['is_wishlist'] == 1) favourite @endif"
                                data-id="{{ $service['id'] }}">
                                <i class="feather-heart"></i>
                            </a>
                            @endauth
                        </div>
                    </div>
                    <div class="gigs-content">
                        <div class="gigs-info">
                            <div>
                                <a href="{{ route('service.detail', $service['slug']) }}" class="badge bg-light">
                                    {{ $service['category'] }}
                                </a>
                            </div>
                            <div class="star-rate">
                                <span><i class="fa-solid fa-star"></i>{{ $service['rating'] }}
                                    ({{ $service['reviews'] }} {{__('web.common.reviews')}})</span>
                            </div>
                        </div>
                        <div class="gigs-title">
                            <h3 class="gig-heading">
                                <a href="{{ route('service.detail', $service['slug']) }}"
                                    title="View details about Laravel backend gig">
                                    {{ $service['title'] }}
                                </a>
                            </h3>
                        </div>

                        <div class="gigs-card-footer">
                            <div class="d-flex align-items-center gigs-left-text">
                                <a href="#" class="avatar avatar-sm flex-shrink-0"><img
                                        src="{{ $service['provider_image'] }}" class="img-fluid rounded-pill"
                                        alt="Profile"></a>
                                <div class="ms-2">
                                    <h6 class="mb-0"><a href="#"
                                            aria-label="Provider Profile">{{ $service['provider_name'] ?? "" }}</a></h6>
                                    <p class="mb-0">{{ $service['location'] ?? "" }}</p>
                                </div>
                            </div>
                            <div class="text-end">
                                <h6 class="mb-1">${{ $service['general_price'] }}</h6>
                                <span>{{ __('web.user.delivery_in') }} {{ $service['days'] }}
                                    {{ __('web.gigs.addon_days_label') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @endif
        </div>
        <div class="text-center mt-3" data-aos="fade-up">
            <a href="{{ route('index.services') }}" class="btn btn-lg btn-dark">{{__('web.home.view_all_services')}}</a>
        </div>
    </div>
</div>
