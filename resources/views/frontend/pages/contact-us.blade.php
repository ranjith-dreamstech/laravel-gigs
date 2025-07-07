@extends($layout)
@push('styles')
<link rel="stylesheet" href="{{ asset('backend/assets/plugins/intltelinput/css/intlTelInput.css') }}">
@endpush
@section('content')
<div class="breadcrumb-bar">
    <div class="breadcrumb-img">
        <div class="breadcrumb-left">
            <img src="{{ asset('frontend/assets/img/bg/banner-bg-03.png') }}" alt="" aria-hidden="true">
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-12">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index.html">{{__('web.home.home')}}</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">{{__('web.home.contact_us')}}</li>
                    </ol>
                </nav>
                <h2 class="breadcrumb-title">
                    {{__('web.home.contact_us')}}
                </h2>
            </div>
        </div>
    </div>
</div>
<section class="contact-section">
    <!-- Contact Bottom -->
    <div class="contact-bottom bg-white">
        <div class="container">
            <div class="row justify-content-center">
                <!-- Contact Grid -->
                <div class="col-xl-4 col-lg-6 d-flex">
                    <div class="contact-grid con-info w-100">
                        <div class="contact-content">
                            <div class="contact-icon">
                                <span>
                                    <img src="{{ asset('frontend/assets/img/icons/contact-mail.svg') }}" alt="Icon">
                                </span>
                            </div>
                            <div class="contact-details">
                                <p><a href="mailto:{{$companyEmail ?? ""}}">{{$companyEmail ?? ""}}</a></p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Contact Grid -->

                <!-- Contact Grid -->
                <div class="col-xl-4 col-lg-6 d-flex">
                    <div class="contact-grid con-info w-100">
                        <div class="contact-content">
                            <div class="contact-icon">
                                <span>
                                    <img src="{{ asset('frontend/assets/img/icons/contact-phone.svg') }}" alt="Icon">
                                </span>
                            </div>
                            <div class="contact-details">
                                <p><a href="tel:{{$companyPhoneNumber ?? ""}}">{{$companyPhoneNumber ?? ""}}</a></p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Contact Grid -->

                <!-- Contact Grid -->
                <div class="col-xl-4 col-lg-6 d-flex">
                    <div class="contact-grid con-info w-100">
                        <div class="contact-content">
                            <div class="contact-icon">
                                <span>
                                    <img src="{{ asset('frontend/assets/img/icons/contact-map.svg') }}" alt="Icon">
                                </span>
                            </div>
                            <div class="contact-details contact-details-address">
                                <p>{{$companyAddress ?? ""}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Contact Grid -->

            </div>
        </div>
    </div>
    <!-- /Contact Bottom -->

    <!-- Contact Top -->
    <div class="contact-top pt-0">
        <div class="container">
            <div class="row align-items-center">
                <!-- Contact Image -->
                <div class="col-lg-6 col-md-12 d-flex">
                    <div class="contact-img">
                        <img src="{{ asset('frontend/assets/img/contact-01.png') }}" class="img-fluid" alt="img">
                    </div>
                </div>
                <!-- /Contact Image -->
                <!-- Contact Form -->
                <div class="col-lg-6 col-md-12 d-flex">
                    <div class="team-form w-100">
                        <div class="team-form-heading">
                            <h3>{{__('web.home.get_in_touch')}}</h3>
                            <p>{{__('web.home.contact_us_text')}}</p>
                        </div>
                        <form id="contactForm">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="{{__('web.user.name')}}" name="contact_name" id="contact_name">
                                        <span class="error-text text-danger" id="contact_name_error"></span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="contact_phone" id="contact_phone" placeholder="{{__('web.user.phone')}}">
                                        <input type="hidden" name="phone_number" id="international_phone_number">
                                        <span class="error-text text-danger" id="contact_phone_error"></span>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="contact_email" id="contact_email" placeholder="{{__('web.user.email')}}">
                                        <span class="error-text text-danger" id="contact_email_error"></span>
                                    </div>
                                    <div class="form-group">
                                        <textarea class="form-control" name="contact_message" id="contact_message" placeholder="{{__('web.home.your_message')}}"></textarea>
                                        <span class="error-text text-danger" id="contact_message_error"></span>
                                    </div>
                                    <div class="form-group mb-0">
                                        <button type="submit" class="btn btn-primary submitbtn">{{__('web.home.send_message')}}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /Contact Form -->
            </div>
        </div>
    </div>
    <!-- /Contact Top -->
    <div class="row">
        <!-- Contact Map -->
        <div class="col-md-12">
            <div class="contact-map map-v3 w-100">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3321.6088932774796!2d-117.8132203247921!3d33.64138153931407!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x80dcddf599c1986f%3A0x6826f6868b4f8e35!2sHillcrest%2C%20Irvine%2C%20CA%2092603%2C%20USA!5e0!3m2!1sen!2sin!4v1706772657955!5m2!1sen!2sin" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" height="500" title="googleMap"></iframe>
            </div>
        </div>
        <!-- /Contact Map -->
    </div>
</section>
@endsection
@push('scripts')
<script src="{{ asset('backend/assets/plugins/intltelinput/js/intlTelInput.js') }}"></script>
<script src="{{ asset('frontend/custom/js/home/contact-us.js') }}"></script>
@endpush
