<div class="faq-sec faq-section-two">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5">
                <div class="faq-image d-lg-block d-none">
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="faq-left-img">
                                <img src="{{ asset('frontend/assets/img/home/faq-01.webp') }}" alt="" aria-hidden="true"
                                    class="img-fluid mb-2" data-aos="fade-down">
                                <img src="{{ asset('frontend/assets/img/home/faq-02.webp') }}" alt="" aria-hidden="true"
                                    class="img-fluid" data-aos="fade-up">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="faq-right-img">
                                <img src="{{ asset('frontend/assets/img/home/faq-03.webp') }}" alt="" aria-hidden="true"
                                    class="img-fluid mb-2" data-aos="fade-down">
                                <img src="{{ asset('frontend/assets/img/home/faq-04.webp') }}" alt="" aria-hidden="true"
                                    class="img-fluid" data-aos="fade-up">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 ps-xl-3">
                <div class="section-header-two" data-aos="fade-up">
                    <h2 class="mb-2"><span class="title-bg"></span>{{ $section['section_title'] }}<span
                            class="title-bg2"></span></h2>
                    <p>{{ $section['section_label'] }}</p>
                </div>
                <div class="faq-wrapper faq-lists">
                    @if (!empty($section['section_content']) && count($section['section_content']) > 0)
                    @foreach ($section['section_content'] as $key => $content)
                    <div class="faq-card aos" data-aos="fade-up">
                        <span class="count">{{ str_pad($key + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <h4 class="faq-title">
                            <a class="collapsed" data-bs-toggle="collapse" href="#faq_{{ $key }}" aria-expanded="false">
                                {{ $content->question ?? "" }}
                            </a>
                        </h4>
                        <div id="faq_{{ $key }}" class="card-collapse collapse">
                            <div class="faq-content">
                                <p>{{ $content->answer ?? "" }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
