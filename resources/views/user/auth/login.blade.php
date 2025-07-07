<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>DreamGigs</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('frontend/assets/img/favicon.png') }}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/bootstrap.min.css') }}">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/assets/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/plugins/fontawesome/css/all.min.css') }}">

    <!-- Owl carousel CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/owl.carousel.min.css') }}">

    <!-- Toastr JS -->
    <script src="{{ asset('backend/assets/plugins/toastr/toastr.min.js') }}"></script>

    <!-- Fearther CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/feather.css') }}">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/style.css') }}">

</head>

<body>

    <!-- Main Wrapper -->
    <div class="main-wrapper">

        <!-- Sign In -->
        <div class="row gx-0">

            <!-- Banner Content -->
            <div class="col-lg-6">
                <div class="authentication-wrapper">
                    <div class="authentication-content">
                        <div class="login-carousel owl-carousel">
                            <div class="login-slider">
                                <img src="/frontend/assets/img/login-card-01.svg" class="img-fluid" alt="img">
                                <h2>Looking to Buy a service?</h2>
                                <p>Browse Listing & More 900 Services </p>
                            </div>
                            <div class="login-slider">
                                <img src="/frontend/assets/img/login-card-02.svg" class="img-fluid" alt="img">
                                <h2>Looking to Sell a service?</h2>
                                <p>Browse Listing & More 900 Services </p>
                            </div>
                        </div>
                    </div>
                    <div class="login-bg">
                        <img src="/frontend/assets/img/bg/shape-01.png" alt="img" class="shape-01">
                        <img src="/frontend/assets/img/bg/shape-02.png" alt="img" class="shape-02">
                        <img src="/frontend/assets/img/bg/shape-03.png" alt="img" class="shape-03">
                        <img src="/frontend/assets/img/bg/shape-04.png" alt="img" class="shape-04">
                        <img src="/frontend/assets/img/bg/shape-05.png" alt="img" class="shape-05">
                        <img src="/frontend/assets/img/bg/shape-06.png" alt="img" class="shape-06">
                        <img src="/frontend/assets/img/bg/shape-07.png" alt="img" class="shape-07">
                    </div>
                </div>
            </div>
            <!-- /Banner Content -->

            <!-- login Content -->
            <div class="col-lg-6">
                <div class="login-wrapper">
                    <div class="login-content">
                        <form id="userLoginForm">
                            <div class="login-userset">
                                <div class="login-logo">
                                    <img src="/frontend/assets/img/logo.svg" alt="Website logo">
                                </div>
                                <div class="login-card">
                                    <div class="login-heading">
                                        <h3>Hi, Welcome Back!</h3>
                                        <p>Fill the fields to get into your account</p>
                                    </div>

                                    <div>
                                        <label class="form-label" for="email">Email</label>
                                        <div class="form-wrap form-focus">
                                            <span class="form-icon">
                                                <i class="feather-mail"></i>
                                            </span>
                                            <input type="email" class="form-control floating" id="email" name="email" placeholder="">
                                            <span id="email_error" class="text-danger error-text"></span>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="password" class="form-label">Password</label>
                                        <div class="form-wrap form-focus pass-group">
                                            <span class="form-icon">
                                                <i class="toggle-password feather-eye-off"></i>
                                            </span>
                                            <input type="password" class="pass-input form-control  floating" id="password" name="password">
                                            <span id="password_error" class="text-danger error-text"></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-wrap">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                                    <label class="form-check-label" for="flexCheckDefault">
                                                        Remember Me
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-wrap text-md-end">
                                                <a class="forgot-link" href="{{ route('user-forgot-password') }}">Forgot Password?</a>
                                                <a class="form-check-label text-decoration-underline" id="login_otp"
                                                href="javascript:void(0);" style="cursor: pointer;">
                                                {{ __('Sign in with OTP') }}
                                            </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-wrap mantadory-info d-none">
                                        <p><i class="feather-alert-triangle"></i>Fill all the fields to submit</p>
                                    </div>
                                    <button type="submit" class="btn btn-primary user-login-btn">Sign In</button>

                                </div>
                                <div class="acc-in">
                                    <p>Don’t have an account? <a href="{{ route('user-register') }}">Sign Up</a></p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /Login Content -->
            <div class="modal fade" id="otp-email-modal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header d-flex align-items-center justify-content-end pb-0 border-0">
                            <a href="javascript:void(0);" data-bs-dismiss="modal" aria-label="Close" id="close-otp-modal">
                                <i class="ti ti-circle-x-filled fs-20"></i>
                            </a>
                        </div>
                        <div class="modal-body p-4">
                            <form action="#" class="digit-group">
                                <div class="text-center mb-3">
                                    <h3 class="mb-2">{{ __('Email OTP Verification') }}</h3>
                                    <p id="otp-email-message" class="fs-14">{{ __('OTP sent to your Email Address') }}</p>
                                </div>
                                <div class="text-center otp-input">
                                    <div class="inputcontainer">

                                    </div>
                                    <span id="error_message" class="text-danger"></span>
                                    <div>
                                        <div class="badge bg-danger-transparent mb-3">
                                            <p class="d-flex align-items-center">
                                                <i class="ti ti-clock me-1"></i>
                                                <span id="otp-timer">00:00</span>
                                            </p>
                                        </div>
                                        <div class="mb-3 d-flex justify-content-center">
                                            <p>Didn't get the OTP?  <a href="javascript:void(0);" class="resendEmailOtp text-primary">{{ __('Resend OTP') }}</a></p>
                                        </div>
                                        <div>
                                            <button type="button" id="verify-email-otp-btn"
                                                class="verify-email-otp-btn btn btn-lg btn-primary w-100">{{ __('Verify & Proceed') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- /Sign In -->
        @include('frontend.toast')
        <!-- Mouse Cursor -->
        <div class="mouse-cursor cursor-outer"></div>
        <div class="mouse-cursor cursor-inner"></div>
        <!-- /Mouse Cursor -->

    </div>
    <!-- /Main Wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('frontend/assets/js/jquery-3.7.1.min.js') }}"></script>

    <script src="{{ asset('backend/assets/js/jquery/jquery-validation.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/jquery/jquery-validation-additional-methods.min.js') }}"></script>

    <!-- Bootstrap Core JS -->
    <script src="{{ asset('frontend/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/bootstrap-scrollspy.js') }}"></script>

    <!-- Feather JS -->
    <script src="{{ asset('frontend/assets/js/feather.min.js') }}"></script>

    <!-- Owl Carousel JS -->
    <script src="{{ asset('frontend/assets/js/owl.carousel.min.js') }}"></script>

    <script src="{{ asset('frontend/assets/js/user/login.js') }}"></script>
    <!-- Custom JS -->
    <script src="{{ asset('frontend/assets/js/script.js') }}"></script>
    <script src="{{ asset('frontend/custom/js/custom-script.js') }}"></script>

</body>

</html>
