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
                        <li class="breadcrumb-item active" aria-current="page">Thank You</li>
                    </ol>
                </nav>
                <h2 class="breadcrumb-title mb-0">
                    Thank You <span class="text-primary"></span>
                </h2>
            </div>
        </div>
    </div>
</div>
<!-- /Breadcrumb -->

<!-- Page Content -->
<div class="page-content">
    <div class="container">

        <!-- Received gigs -->
        <div class="service-wrap text-center rounded-0 border-0 shadow-none p-0">
            <div class="received-iocn mb-4 m-auto d-flex align-items-center justify-content-center">
                <i class="ti ti-check"></i>
            </div>
            <h5 class="mb-1"> Thank you! Your Order has been Recieved </h5>
            <p> Order Number : <span> #{{ $booking->order_id }} </span> </p>
        </div>
        <!-- Received gigs -->

        <!-- Order details -->
        <div class="service-widget member-widget">
            <h5 class="service-head d-flex align-items-center">Order Details</h5>
            <div class="user-details bg-light p-3 mb-16">
                <div class="user-img service-user">
                    <img src="{{ $firstImageUrl }}" alt="img">
                </div>
                <div class="user-info">
                    <h5><span class="me-2">{{ $gigs->title }}</span> </h5>
                    <p>Delivery : Jan 29 2025</p>
                </div>
            </div>
            <ul class="member-info">
                <li>
                    Gigs Price
                    <span>{{ $currencySymbol }}{{ $booking->gigs_price }}</span>
                </li>
                <li>
                    Gig's Quantity x {{ $booking->quantity }}
                    <span>{{ $currencySymbol }}{{ $booking->gigs_total_price }}</span>
                </li>
                @if($booking->total_extra_service_price > 0)
                <li>
                    Extra Services
                    <span>{{ $currencySymbol }}{{ $booking->total_extra_service_price }}</span>
                </li>
                @endif

                @if($booking->gigs_fast_price > 0)
                <li>
                    Fast Service Fees
                    <span>{{ $currencySymbol }}{{ $booking->gigs_fast_price }}</span>
                </li>
                @endif
        
            </ul>
            <div class="about-me m-0 pt-3 mt-3 border-top border-grey">
                <h6 class="d-flex justify-content-between align-items-center m-0">Total <span> {{ $currencySymbol }}{{ $booking->final_price }}</span></h6>
            </div>
        </div>
        <!-- Order details -->

        <!-- Billing details -->
        <div class="row">
            <div class="col-lg-12">
                <div class="service-widget">
                    <h5 class="service-head mb-3">Billing Information </h5>
                    <h6 class="mb-2">{{ $bookingInfo->first_name }} {{ $bookingInfo->last_name }}</h6>
                    <div class="service-text">
                        <p class="mb-1">{{ $bookingInfo->address }}</p>
                        <p class="mb-1">Phone Number : 310-437-2766</p>
                        <p class="mb-0">Email ID : {{ $bookingInfo->email }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="service-widget m-0">
                    <h5 class="service-head mb-3">Payment Details </h5>
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div class="service-text mb-0">
                            <h6 class="mb-1">Payment Method</h6>
                            <p class="mb-0">{{ ucfirst($booking->payment_type) }}</p>
                        </div>

                        <div class="service-text mb-0">
                            <h6 class="mb-1">Transaction ID</h6>
                            <p class="mb-0">#{{ $booking->transaction_id }}</p>
                        </div>

                        <div class="service-text mb-0">
                            <h6 class="mb-1">Time & Date</h6>
                            <p class="mb-0">{{ $booking->created_at->format('d M Y, h:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Billing details -->
    </div>

</div>
<!-- Page Content -->

@endsection
