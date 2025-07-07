<footer class="footer-two site-footer" role="contentinfo">
    <img src="{{ asset('frontend/assets/img/home/shape-3.svg') }}" alt="img" class="d-lg-flex d-none img-fluid footer-bg">
    <div class="footer-top-two position-relative">
        <img src="{{ asset('frontend/assets/img/home/footer-bg.svg') }}" alt="img" class="d-lg-flex d-none img-fluid footer-bg2">
        <div class="container">
            <div class="row row-gap-4 aos" data-aos="fade-up">
                @if (!empty($footers)) 
                    @foreach ($footers as $footer)
                    <div class="col-lg-3 col-sm-6 footer-links">
                     <h6>{{ ucfirst($footer->name) }}</h6>
                        <ul>
                            @if ($footer->parsed_menus)
                                @foreach ($footer->parsed_menus as $menu)
                                    @php
                                        $rawLink = trim($menu['link']);
                                        $isFullUrl = filter_var($rawLink, FILTER_VALIDATE_URL);
                                        $menuLink = $isFullUrl ? rtrim($rawLink, '/') : rtrim(url($rawLink), '/');
                                        $currentUrl = rtrim(Request::url(), '/');
                                    @endphp
                                    <li>
                                        <a href="{{ $menuLink }}"><i class="ti ti-chevron-right me-2"></i>{{ $menu['label'] }}</a>
                                    </li>
                                @endforeach
                            @endif										
                        </ul>
                    </div>
                    @endforeach
                @endif
               <div class="col-lg-3 col-sm-6 footer-contact">
                  <h6>Contact Us</h6>
                  @if ($companyAddress)
                  <div class="d-flex align-items-center mb-3">
                    <span class="footer-icon">
                        <i class="ti ti-map-pin"></i>
                    </span>
                    <div>
                        <p class="mb-0">Address</p>
                        <span>{{ $companyAddress }}</span>
                    </div>
                  </div>
                  @endif

                  @if ($companyPhoneNumber)
                  <div class="d-flex align-items-center mb-3">
                    <span class="footer-icon">
                        <i class="ti ti-device-tablet"></i>
                    </span>
                    <div>
                        <p class="mb-0">Phone</p>
                        <span>{{ $companyPhoneNumber }}</span>
                    </div>
                  </div>
                  @endif

                  @if ($companyEmail)
                  <div class="d-flex align-items-center">
                    <span class="footer-icon">
                        <i class="ti ti-mail-filled"></i>
                    </span>
                    <div>
                        <p class="mb-0">Email</p>
                        <span>{{ $companyEmail }}</span>
                    </div>
                  </div>
                  @endif
               </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom-two">
       <div class="container">
           <div class="mb-3 d-flex align-items-center justify-content-between flex-wrap gap-3">
            <a href="{{ route('home') }}" class="footer-logo">
                <img src="{{ $logo }}" alt="{{ $companyName }} Main Logo">
            </a>
            <a href="{{ route('home') }}" class="footer-dark-logo">
                <img src="{{ $logo }}" alt="{{ $companyName }} Dark Logo" class="img-fluid">
            </a>
            <div class="social-links">
                <ul>
                    <li class="me-2"><a href="javascript:void(0);" aria-label="facebook"><i class="fa-brands fa-facebook"></i></a></li>
                    <li class="me-2"><a href="javascript:void(0);" aria-label="twitter"><i class="fa-brands fa-x-twitter"></i></a></li>
                    <li class="me-2"><a href="javascript:void(0);" aria-label="instagram"><i class="fa-brands fa-instagram"></i></a></li>
                    <li class="me-2"><a href="javascript:void(0);" aria-label="google"><i class="fa-brands fa-google"></i></a></li>
                    <li><a href="javascript:void(0);" aria-label="youtube"><i class="fa-brands fa-youtube"></i></a></li>
                </ul>
            </div>
           </div>
           <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="copy-right-two">
                <p class="mb-0">{!! $copyright ?? 'Copyright © '.date('Y').' '.$companyName.'. All Rights Reserved.' !!}</p>
            </div>
            <div class="footer-links">
                <ul class="d-flex align-items-center flex-wrap gap-3">
                    <li><a href="{{ url('pages/privacy-policy') }}">Privacy Policy</a></li>
                    <li><a href="{{ url('pages/terms-conditions') }}">Terms & Conditions</a></li>
                    <li><a href="{{ url('pages/refund') }}">Cancellation Policy</a></li>
                </ul>		
            </div>
           </div>
       </div>
    </div>
 </footer>