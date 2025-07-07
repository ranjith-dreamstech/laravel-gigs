@php
@endphp
<div class="hero-section-two">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="banner-content aos" data-aos="fade-up">
                    <img src="{{ asset('frontend/assets/img/home/banner-shape-1.svg') }}" alt="banner1" class="img-fluid banner-bg-1 d-none d-lg-flex">
                    <span class="d-inline-flex mb-3 align-items-center hero-title"><i class="ti ti-point-filled me-1"></i>{{$section['section_content'][0]->label ?? ""}}</span>
                    <h1 class="mb-2">{{ $section['section_content'][0]->line_one ?? "" }}</h1>
                    <p class="mb-4">{{ $section['section_content'][0]->description   ?? "" }}</p>
                    <a href="{{ route('index.services') }}" class="btn btn-lg btn-primary d-inline-flex align-items-center">{{ __('web.home.explore_services') }}<i class="ti ti-chevron-right ms-1"></i></a>
                    <div class="banner-users d-flex align-items-center flex-wrap gap-3">
                        <div class="avatar-list-stacked me-2">
                            @if(!empty($section['section_content'][0]->customer_images))
                            @foreach($section['section_content'][0]->customer_images as $image)
                            <span class="avatar avatar-md rounded-circle border-0"><img src="{{ $image }}" class="img-fluid rounded-circle border border-white" alt="" aria-hidden="true"></span>
                            @endforeach
                            @endif
                        </div>
                        <div>
                            <div class="d-flex align-items-center mb-1">
                                <i class="ti ti-star-filled text-warning"></i>
                                <i class="ti ti-star-filled text-warning"></i>
                                <i class="ti ti-star-filled text-warning"></i>
                                <i class="ti ti-star-filled text-warning"></i>
                                <i class="ti ti-star-filled text-warning"></i>
                                <h6 class="mb-0 ms-2">{{ $section['section_content'][0]->average_ratings ?? "" }}</h6>
                            </div>
                            <p class="mb-0">{{__('web.home.trusted_by')}} {{ $section['section_content'][0]->trusted_customer }}+ {{__('web.home.customers')}}</p>
                        </div>
                    </div>
                </div>
           </div>
            <div class="col-lg-5">
               <div class="banner-image">
                <img src="{{ asset('frontend/assets/img/home/banner-shape-2.svg') }}" alt="banner2" class="img-fluid banner-bg-2 d-none d-lg-flex">

                 <img src="{{ $section['section_content'][0]->thumbnail_image ?? '/frontend/assets/img/home/banner-image.webp' }}" alt="banner3" class="img-fluid banner-img">
                 <div class="trustpilot">
                    <h6 class="d-inline-flex align-items-center"><img src="{{ asset('frontend/assets/img/home/star1.svg') }}" class="me-2" alt="" aria-hidden="true">{{ __('web.home.trustpilot') }}</h6>
                    <div class="d-flex align-items-center mb-2">
                        <span>{{__('web.home.excellent')}}</span>
                        <div class="ms-2 d-inline-flex align-items-center">
                            <span class="excellent-star"><img src="{{ asset('frontend/assets/img/home/star2.svg') }}" alt="img"></span>
                            <span class="excellent-star"><img src="{{ asset('frontend/assets/img/home/star2.svg') }}" alt="img"></span>
                            <span class="excellent-star"><img src="{{ asset('frontend/assets/img/home/star2.svg') }}" alt="img"></span>
                            <span class="excellent-star"><img src="{{ asset('frontend/assets/img/home/star2.svg') }}" alt="img"></span>
                            <span class="excellent-star"><img src="{{ asset('frontend/assets/img/home/star2.svg') }}" alt="img"></span>
                        </div>
                    </div>
                    <p class="mb-0">{{__('web.home.based_on')}} {{ $section['section_content'][0]->review_count }} {{__('web.common.reviews')}}</p>
                 </div>
               </div>
            </div>
        </div>
    </div>
</div>
