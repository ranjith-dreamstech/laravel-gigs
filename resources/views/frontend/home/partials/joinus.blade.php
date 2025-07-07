<div class="container">
    <div class="join-with-us">
        <img src="{{ asset('frontend/assets/img/home/shape-5.svg') }}" alt="img" class="img-fluid join-with-us-bg">
        <div class="d-sm-flex align-items-center justify-content-between">
            <div data-aos="fade-right">
                <h2 class="text-white">{{__('web.home.start_as_seller')}} </h2>
                <p class="mb-0">{{__('web.home.join_us_content')}}</p>
            </div>
            <a href="{{ route('seller.dashboard') }}" class="btn btn-lg btn-primary flex-shrink-0" data-aos="fade-left">{{__('web.home.join_with_us')}}</a>
        </div>
    </div>
 </div>
 