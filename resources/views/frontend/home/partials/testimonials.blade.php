<div class="testimonials-section-two">
    <img src="{{ asset('frontend/assets/img/home/shape-4.svg') }}" alt="img"
        class="img-fluid testimonials-bg3 d-lg-inline-flex d-none">
    <div class="container">
        <div class="row align-items-center row-gap-4">
            <div class="col-lg-6">
                <div class="section-header-two" data-aos="fade-up">
                    <h2 class="mb-2 p-0">{{ $section['section_title'] }}</h2>
                    <p>{{ $section['section_label'] }}</p>
                </div>
                <h6 class="mb-3" data-aos="fade-up">{{__('web.home.we_have_global_customres')}}</h6>
                <div class="avatar-list-stacked me-2" data-aos="fade-up">
                    <span class="avatar avatar-md rounded-circle border-0"><img
                            src="/frontend/assets/img/user/user-01.webp"
                            class="img-fluid rounded-circle border border-white" alt="Img"></span>
                    <span class="avatar avatar-md rounded-circle border-0"><img
                            src="/frontend/assets/img/user/user-02.webp"
                            class="img-fluid rounded-circle border border-white" alt="Img"></span>
                    <span class="avatar avatar-md rounded-circle border-0"><img
                            src="/frontend/assets/img/user/user-03.webp"
                            class="img-fluid rounded-circle border border-white" alt="Img"></span>
                    <span class="avatar avatar-md rounded-circle border-0"><img
                            src="/frontend/assets/img/user/user-04.webp"
                            class="img-fluid rounded-circle border border-white" alt="Img"></span>
                    <span class="avatar avatar-md rounded-circle border-0"><img
                            src="/frontend/assets/img/user/user-05.webp"
                            class="img-fluid rounded-circle border border-white" alt="Img"></span>
                    <span class="avatar avatar-md rounded-circle border-0"><img
                            src="/frontend/assets/img/user/user-06.webp"
                            class="img-fluid rounded-circle border border-white" alt="Img"></span>
                    <span class="avatar avatar-md rounded-circle border-0"><img
                            src="/frontend/assets/img/user/user-07.webp"
                            class="img-fluid rounded-circle border border-white" alt="Img"></span>
                </div>
            </div>
            <div class="col-lg-6">
                @if(!empty($section['section_content']) && count($section['section_content']) > 0)
                @foreach($section['section_content'] as $item)
                <div class="testimonials-item bg-white rounded" data-aos="fade-up">
                    <div class="d-flex align-items-center gigs-left-text mb-3">
                        <a href="javascript:void(0);" class="avatar avatar-sm flex-shrink-0"><img
                                src="/frontend/assets/img/user/user-21.webp" class="img-fluid rounded-pill"
                                alt="img"></a>
                        <div class="ms-2">
                            <h6 class="mb-0"><a href="#">{{ $item->customer_name }}</a></h6>
                        </div>
                    </div>
                    @php
                    $rating = $item->ratings ? round($item->ratings) : 0;
                    $emptyStars = 5 - $rating;
                    $filledStars = $rating;
                    @endphp
                    <p class="mb-3">{{ $item->review ?? "" }}</p>
                    @for($i = 1; $i <= $filledStars; $i++) <i class="ti ti-star-filled text-warning"></i>
                        @endfor
                        @for($i = 1; $i <= $emptyStars; $i++) <i class="ti ti-star text-warning"></i>
                            @endfor
                </div>
                @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
