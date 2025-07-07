<!-- Header -->
<header class="header dashboard-header">
    <div class="header-user">
        <div class="header-left-mob">
            <a href="/" class="logo">
                <img src="/frontend/assets/img/logo.svg" alt="Logo">
            </a>
        </div>

        <a id="mobile_btn" class="mobile_btn" href="#sidebar">
            <span class="bar-icon">
            <i class="ti ti-baseline-density-medium"></i>
        </span>
        </a>
        <div class="nav user-menu nav-list">
            <div class="wallet-amount">
                @php
                $wallet = getUserWalletBalance(auth()->id());
                $currency = getDefaultCurrencySymbol();
                 @endphp

            <span>
                <i class="ti ti-point-filled me-1"></i>
                {{ __('web.user.wallet_balance') }}: {{ $currency }}{{ number_format($wallet['total_balance'], 2) }}
            </span>

            </div>
        </div>
        <div class="header-right d-flex align-items-center">
            <div class="dashboard-link">
                <ul class="d-inline-flex align-items-center p-1 rounded-pill">
                    <li><a href="{{ route('buyer.dashboard') }}" class="active" data-userlayout="buyer">{{ __('web.user.buyer') }}</a></li>
                    <li><a href="{{ route('seller.dashboard') }}" data-userlayout="seller">{{ __('web.user.seller') }}</a></li>
                </ul>
            </div>
            <div class="nav-item dropdown flag-nav nav-item-box">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="javascript:void(0);" role="button">
                    <img src="{{ asset('backend/assets/img/flags/'. app()->getLocale() .'.svg') }}" alt="Language" class="img-fluid">
                </a>
                <ul class="dropdown-menu p-2">
                    @if(!empty($allLanguages) && count($allLanguages) > 0)
                    @foreach($allLanguages as $language)
                    <li>
                        <a href="javascript:void(0);" class="dropdown-item justify-content-start change-user-language" data-id="{{ $language->id }}" data-language_code="{{ $language->code }}">
                            <img src="{{ asset('backend/assets/img/flags/'. $language->code.'.svg') }}" alt="" height="16" class="me-2">
                            {{ $language->name }}
                        </a>
                    </li>
                    @endforeach
                    @endif
                </ul>
            </div>
            <div class="nav-item dropdown nav-item-box">
                <a class="nav-link" href="{{ route('buyer.notifications') }}">
                    <i class="ti ti-bell"></i>
                </a>
            </div>
            <a href="{{ route('buyerprofile') }}" class="dropdown-toggle d-flex align-items-center">
                <span class="avatar online avatar-sm">
                <img src="{{ getCurrentUserImage() }}" alt="Img" class="img-fluid rounded-circle">
            </span>
            </a>
        </div>
        <div class="mobile-user-menu">
            <a href="{{ route('buyerprofile') }}" class="dropdown-toggle d-flex align-items-center">
                <span class="avatar online avatar-sm">
                <img src="{{ getCurrentUserImage() }}" alt="Img" class="img-fluid rounded-circle">
            </span>
            </a>
        </div>
    </div>
</header>
<!-- /Header -->
