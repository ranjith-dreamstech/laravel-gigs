<div class="container">
    <div class="trusted-customers-two">
        <img src="{{ asset('frontend/assets/img/home/shape-2.svg') }}" alt="" aria-hidden="true" class="trusted-bg img-fluid d-lg-flex d-none">
        <div class="row align-items-center">
            <div class="col-lg-7">
              <div class="trusted-customers-image position-relative d-lg-block d-none text-center" data-aos="fade-up">
                <img src="{{ asset('frontend/assets/img/home/jointeam.webp') }}" alt="" aria-hidden="true" class="img-fluid">
              </div>
            </div>
            <div class="col-lg-5" data-aos="fade-left">
                <h2 class="mb-3">{{ $section['section_title'] }}</h2>
                <p>{{ $section['section_label'] }}</p>
                <a href="{{ route('index.services') }}" class="btn btn-lg btn-white">{{__('web.home.view_all_services')}}</a>
            </div>
        </div>
    </div>
</div>
