@extends($layout)
@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb-bar breadcrumb-bar-info">
    <div class="breadcrumb-img">
        <div class="breadcrumb-left">
            <img src="{{ asset('frontend/assets/img/bg/banner-bg-03.png') }}" alt="img">
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-12">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index.html">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Checkout</li>
                    </ol>
                </nav>
                <h2 class="breadcrumb-title mb-0">
                    Checkout <span class="text-primary"></span>
                </h2>
            </div>
        </div>
    </div>
</div>
<!-- /Breadcrumb -->

<!-- Page Content -->
<div class="page-content">
    <div class="container">
        <form id="submitCheckoutForm" autocomplete="off">
            @csrf
            <input type="hidden" name="extra_service_ids" value="{{ request('extra_service_ids') }}">
            <div class="row">
                <div class="col-lg-8">
                    <div class="login-card mb-3 mb-lg-0">
                        <div class="login-heading text-start mb-4">
                            <h5>Billing Details</h5>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-wrap form-focus">
                                    <label for="first_name" class="mb-1 fw-medium text-dark">First Name <span class="text-primary">*</span></label>
                                    <input type="text" name="first_name" id="first_name" value="{{ $user->userDetail ? $user->userDetail->first_name : '' }}" class="form-control floating">
                                    <span id="first_name_error" class="text-danger error-text"></span>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-wrap form-focus">
                                    <label for="last_name" class="mb-1 fw-medium text-dark">Last Name <span class="text-primary">*</span></label>
                                    <input type="text" name="last_name" id="last_name" value="{{ $user->userDetail ? $user->userDetail->last_name : '' }}" class="form-control floating">
                                    <span id="last_name_error" class="text-danger error-text"></span>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-wrap form-focus">
                                    <label for="address" class="mb-1 fw-medium text-dark">Address <span class="text-primary">*</span></label>
                                    <input type="text" name="address" id="address" value="{{ $user->userDetail ? $user->userDetail->address : '' }}" class="form-control floating">
                                    <span id="address_error" class="text-danger error-text"></span>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="input-block form-wrap form-focus">
                                    <label for="category_id" class="mb-1 fw-medium text-dark">Category <span class="text-primary">*</span></label>
                                    <select name="category_id" id="category_id" class="select2 form-control">
                                        <option value="">Select</option>
                                        <option value="1">Digital Marketing</option>
                                        <option value="2">Writing</option>
                                        <option value="3">Social Media</option>
                                    </select>
                                    <span id="category_error" class="text-danger error-text"></span>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="input-block form-wrap form-focus">
                                    <label for="country_id" class="mb-1 fw-medium text-dark">Country <span class="text-primary">*</span></label>
                                    <select class="form-control select2" name="country_id" id="country_id">
                                        <option value="">{{__('web.home.select_country')}}</option>
                                        @foreach ($countries as $country)
                                        <option value="{{ $country->id }}"
                                            @if(optional($user->userDetail)->country_id == $country->id) selected @endif>
                                            {{ $country->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <span id="country_id_error" class="text-danger error-text"></span>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="input-block form-wrap form-focus">
                                    <label for="state_id" class="mb-1 fw-medium text-dark">State <span class="text-primary">*</span></label>
                                    <input type="hidden" id="selected_state_id" value="{{ optional($user->userDetail)->state_id }}">
                                    <select class="form-control select2" name="state_id" id="state_id">
                                        <option value="">{{__('web.home.select_state')}}</option>
                                    </select>
                                    <span id="state_id_error" class="text-danger error-text"></span>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="input-block form-wrap form-focus">
                                    <label for="city_id" class="mb-1 fw-medium text-dark">City <span class="text-primary">*</span></label>
                                    <input type="hidden" id="selected_city_id" value="{{ optional($user->userDetail)->city_id }}">
                                    <select class="form-control select2" name="city_id" id="city_id">
                                        <option value="">{{__('web.home.select_city')}}</option>
                                    </select>
                                    <span id="city_id_error" class="text-danger error-text"></span>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="input-block form-wrap form-focus">
                                    <label for="email" class="mb-1 fw-medium text-dark">Email <span class="text-primary">*</span></label>
                                    <input type="text" name="email" id="email" value="{{ $user->email ? $user->email : '' }}" class="form-control">
                                    <span id="email_error" class="text-danger error-text"></span>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="input-block form-wrap form-focus">
                                    <label for="postal_code" class="mb-1 fw-medium text-dark">Pincode <span class="text-primary">*</span></label>
                                    <input type="text" name="postal_code" id="postal_code" value="{{ $user->userDetail ? $user->userDetail->postal_code : '' }}" class="form-control">
                                    <span id="pincode_error" class="text-danger error-text"></span>
                                </div>
                            </div>

                        </div>

                        <div class="check-payment">
                            <h5> Payment </h5>
                            <!-- tab -->
                            <ul class="nav payment-gateway">
                                <li>
                                    <div class="active" data-bs-toggle="tab" data-bs-target="#upload-img">
                                        <label class="payment-card mb-0">
                                            <input type="radio" name="payment_type" value="paypal" id="paypal" checked>
                                            <span class="content">
                                                <span class="radio-btn"></span>
                                                <span class="payment-text">Pay with Paypal</span>
                                                <img src="/backend/assets/img/payment/gateway-01.png" alt="">
                                            </span>
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <div data-bs-toggle="tab" data-bs-target="#upload-video">
                                        <label class="payment-card mb-0">
                                            <input type="radio" name="payment_type" value="stripe" id="stripe">
                                            <span class="content">
                                                <span class="radio-btn"></span>
                                                <span class="payment-text">Pay with Stripe</span>
                                                <img src="/backend/assets/img/payment/gateway-02.png" alt="" class="img-fluid img2">
                                            </span>
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <div data-bs-toggle="tab" data-bs-target="#upload-link">
                                        <label class="payment-card mb-0">
                                            <input type="radio" name="payment_type" value="cod" id="cod">
                                            <span class="content">
                                                <span class="radio-btn"></span>
                                                <span class="payment-text">Pay with Wallet</span>
                                                <img src="/backend/assets/img/payment/gateway-03.png" alt="" class="img-fluid img3">
                                            </span>
                                        </label>
                                    </div>
                                </li>
                            </ul>
                            <!-- tab -->


                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <!-- Order details -->
                    <div class="service-widget member-widget mb-0">
                        <h5 class="service-head d-flex align-items-center">Order Details <span class="ms-3"> #OR4478 </span> </h5>
                        <div class="user-details bg-light p-3 mb-16">
                            <div class="user-img">
                                <img src="{{ $firstImageUrl }}" alt="img">
                            </div>
                            <div class="user-info">
                                <input type="hidden" name="gig_id" id="gig_id" value="{{ $gig_id }}">
                                <h5><span class="me-2">{{ $gigs->title }}</span> </h5>
                                <p>Delivery : Jan 29 2025</p>
                            </div>
                        </div>
                        <ul class="member-info">
                            <li>
                                Gigs Price
                                <span>{{ $currencySymbol }}{{ $gigs->general_price }}</span>
                                <input type="hidden" name="gig_price" id="gig_price" value="{{ $gigs->general_price }}">
                            </li>
                            <li>
                                Gig's Quantity × {{ $quantity }}
                                <span>{{ $currencySymbol }}{{ $total_price }}</span>
                                <input type="hidden" name="gig_quantity" id="gig_quantity" value="{{ $quantity }}">
                            </li>
                            @if($extra_service_total > 0)
                            <li>
                                Extra Services
                                <span>{{ $currencySymbol }}{{ $extra_service_total }}</span>
                                <input type="hidden" name="gig_extra_service_total" id="gig_extra_service_total" value="{{ $extra_service_total }}">
                            </li>
                            @endif

                            @if($fast_service_total > 0)
                            <li>
                                Fast Service Fees
                                <span>{{ $currencySymbol }}{{ $fast_service_total }}</span>
                                <input type="hidden" name="gig_fast_service_total" id="gig_fast_service_total" value="{{ $fast_service_total }}">
                            </li>
                            @endif
                            
                        </ul>
                        <div class="about-me ">
                            <h6 class="d-flex justify-content-between align-items-center">Total <span> {{ $currencySymbol }}{{ $final_price }}</span></h6>
                            <input type="hidden" name="final_price" id="final_price" value="{{ $final_price }}">
                        </div>
                        <button type="submit" id="final_btn" class="btn btn-primary mb-0 w-100">Pay {{ $currencySymbol }}{{ $final_price }}</button>
                    </div>
                    <!-- Order details -->
                </div>
            </div>
        </form>
    </div>
</div>
<!-- /Page Content -->


<div class="modal fade" id="order_wait" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center p-4 border-0 shadow-sm" style="border-radius: 16px;">
            <div class="mb-3 d-flex justify-content-center">
                <div class="spinner-border text-warning" style="width: 2rem; height: 2rem;" aria-live="polite" aria-busy="true">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <h5 class="fw-semibold mb-2">Please wait…</h5>
            <p class="text-muted mb-0" style="font-size: 0.95rem;">
                Do not close or refresh the window while your order is being processed.
            </p>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('frontend/assets/js/booking/user-booking.js') }}"></script>
@endpush
