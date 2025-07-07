<header class="header site-header" role="banner">
    <div class="container">
        <nav class="navbar navbar-expand-lg header-nav p-0">
            <div class="navbar-header">
                <a id="mobile_btn" href="javascript:void(0);" aria-label="Toggle navigation menu">
                    <span class="bar-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </a>
                <a href="{{ route('home') }}" class="navbar-brand logo">
                    <img src="{{ $logo }}" class="img-fluid" alt="{{ $companyName }} Main Logo">
                </a>
                <a href="{{ route('home') }}" class="dark-logo">
                    <img src="{{ $logo }}" alt="{{ $companyName }} Dark Logo" class="img-fluid">
                </a>
                <a href="{{ route('home') }}" class="navbar-brand logo-small">
                    <img src="{{ $smallLogo }}" class="img-fluid" alt="{{ $companyName }} Small Logo">
                </a>
            </div>
            <div class="main-menu-wrapper">
                <div class="menu-header">
                    <a href="{{ route('home') }}" class="menu-logo">
                        <img src="{{ $logo }}" class="img-fluid" alt="{{ $companyName }} Main Logo">
                    </a>
                    <a href="{{ route('home') }}" class="menu-logo dark-logo">
                        <img src="{{ $logo }}" alt="{{ $companyName }} Dark Logo" class="img-fluid">
                    </a>
                    <a id="menu_close" class="menu-close" href="#" aria-label="Close menu"> <i class="fas fa-times"></i></a>
                </div>
                <div class="mobile-profile">
                    <h6>{{__('web.common.account')}}</h6>
                    <div>
                        <a href="{{ route('buyer.notifications') }}" class="mobile-nofification" aria-label="Notification"><i class="ti ti-bell"></i></a>
                        <a href="{{ route('buyerprofile') }}" class="avatar avatar-sm" aria-label="Profile"><img src="{{ asset('frontend/assets/img/user/user-09.jpg') }}" alt="Profile" class="img-fluid rounded-circle"></a>
                    </div>
                </div>
                <ul class="main-nav navbar-nav">
                    @if ($headers)
                        @foreach ($headers as $header)
                            @if ($header->menus)
                                @foreach ($header->menus_array as $menu)
                                    @php
                                        
                                        $rawLink = trim($menu['link']);
                                        $isFullUrl = filter_var($rawLink, FILTER_VALIDATE_URL);
                                        $menuLink = $isFullUrl ? rtrim($rawLink, '/') : rtrim(url($rawLink), '/');
                                        $currentUrl = rtrim(Request::url(), '/');
                                        $active = '';

                                        if (
                                        $currentUrl == $menuLink ||
                                        (Str::contains($menuLink, 'gigs') && Str::contains($currentUrl, 'service-details')) ||
                                        (Str::contains($menuLink, 'blogs') && Str::contains($currentUrl, 'blog-details'))
                                        ) {
                                        $active = 'active';
                                        }
                                    @endphp
                                    <li>
                                        <a href="{{ $menuLink }}" class="{{ $active }}">{{ $menu['label'] }}</a>
                                    </li>
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                </ul>
            </div>

           <div class="d-flex align-items-center">
            <div class="nav-item dropdown flag-nav nav-item-box nav-item-box-home me-2">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="javascript:void(0);" role="button">
                    <img src="{{ asset('backend/assets/img/flags/'. app()->getLocale() .'.svg') }}" alt="Language Switcher" class="img-fluid">
                </a>
                <ul class="dropdown-menu p-2">
                    @if(!empty($allLanguages) && count($allLanguages) > 0)
                    @foreach($allLanguages as $language)
                    <li class="mb-1">
                        <a href="javascript:void(0);" class="dropdown-item rounded-2 d-flex align-items-center change-user-language" data-id="{{ $language->id }}" data-language_code="{{ $language->code }}">
                            <img src="{{ asset('backend/assets/img/flags/'. $language->code.'.svg') }}" alt="" height="16" class="img-fluid me-2">
                            {{ $language->name }}
                        </a>
                    </li>
                    @endforeach
                    @endif
                </ul>
            </div>
            <div class="nav-item dropdown flag-nav nav-item-box nav-item-box-home me-2 d-none">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-label="Toggle Theme" href="javascript:void(0);" role="button">
                    <i class="ti ti-sun-high"></i>
                </a>
                <ul class="dropdown-menu p-2">
                    <li class="mb-1">
                        <a href="javascript:void(0);" class="dropdown-item active theme-toggle rounded-2" id="light-mode-toggle">
                            <i class="ti ti-sun-high me-2"></i>Light Mode
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="dropdown-item theme-toggle rounded-2" id="dark-mode-toggle">
                            <i class="ti ti-moon me-2"></i>Dark Mode
                        </a>
                    </li>
                </ul>
            </div>
            @auth
            <div class="nav-item dropdown flag-nav nav-item-box nav-item-box-home me-3">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="javascript:void(0);" role="button">
                    <i class="ti ti-bell"></i>
                    <span class="badge badge-pill d-none" id="newNotificationBadge"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-end noti-blk">
                    <div class="topnav-dropdown-header border-bottom">
                        <div class="d-flex align-items-center">
                          <h6 class="mb-0">{{__('web.common.notifications')}}</h6>
                          <div class="count ms-1 unread-count"></div>
                        </div>
                        <a href="javascript:void(0)" class="mark-all-noti has-notification" id="mark-all-as-read"> {{ __('web.common.maer_all_as_read') }} <i class="feather-check-square"></i></a>
                    </div>
                    <ul class="notification-list">
                        
                    </ul>
                    <div class="clear-all-noti has-notification">
                        <a class="clear-notification" href="{{ route('buyer.notifications') }}"> {{__('web.common.view_all')}} </a>
                    </div>
                </div>
            </div>
            @endauth
            <!-- User Menu -->
            @if(Auth::guard('web')->check())
                <div class="nav-item dropdowns has-arrow logged-item">
                    <a href="javascript:void(0)" class="nav-link toggle" >
                        <span class="log-user dropdown-toggle">
                            <span class="users-img">
                                <img class="rounded-circle" src="{{ getCurrentUserImage() }}" alt="Profile">
                            </span>
                            <span class="user-text">{{ getCurrentUserFullname() }}</span>
                            <i class="ti ti-chevron-down ms-2"></i>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end list-group">
                        <div class="user-item">
                            <img src="{{ getCurrentUserImage() }}" alt="Profile">
                            <div class="user-name">
                                <h6>{{ getCurrentUserFullname()}}</h6>
                                <p>{{__('web.user.joined_on')}} : {{ current_user()->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                        <div class="search-filter-selected select-icon">
                            <div class="form-group">
                                <span class="sort-text"><img src="/frontend/assets/img/icons/user-cog.svg" class="img-fluid" alt="img"></span>
                                <select class="select" id="user-type-switch">
                                    <option value="buyer">{{__('web.gigs.buyer_title')}}</option>
                                    <option value="seller">{{__('web.user.seller')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="buyer-menu-options">
                            <a class="dropdown-item drop-line" href="{{ route('buyer.dashboard') }}">
                                <i class="ti ti-layout-grid me-2"></i>{{__('web.user.dashboard')}}
                            </a>
                            <a class="dropdown-item" href="{{ route('buyer.purchase-index') }}">
                                <i class="ti ti-layers-intersect me-2"></i>{{__('web.user.my_purchase')}}
                            </a>
                            <a class="dropdown-item" href="{{ route('user.wallet') }}">
                                <i class="ti ti-wallet me-2"></i>{{__('web.user.my_wallet')}}
                            </a>
                            <hr>
                            <a class="dropdown-item" href="{{ route('buyer.settings') }}">
                                <i class="ti ti-settings-check me-2"></i>{{__('web.common.settings')}}
                            </a>
                            <a class="dropdown-item" href="{{ route('buyerprofile') }}">
                                <i class="ti ti-user me-2"></i>{{__('web.user.my_profile')}}
                            </a>
                            <hr>
                            <a class="dropdown-item log-out" href="{{ route('user.logout') }}">
                                <i class="ti ti-logout me-2"></i>{{__('web.common.logout')}}
                            </a>
                        </div>
                        <div class="seller-menu-options d-none">
                            <a class="dropdown-item drop-line" href="{{ route('seller.dashboard') }}">
                                <i class="ti ti-layout-grid me-2"></i>{{__('web.user.seller')}}
                            </a>
                            <a class="dropdown-item" href="{{ route('seller.my-buyers') }}">
                                <i class="ti ti-layers-intersect me-2"></i>{{__('web.user.my_buyers')}}
                            </a>
                            <a class="dropdown-item" href="{{ route('user.wallet') }}">
                                <i class="ti ti-wallet me-2"></i>{{__('web.user.my_wallet')}}
                            </a>
                            <hr>
                            <a class="dropdown-item" href="{{ route('seller.settings') }}">
                                <i class="ti ti-settings-check me-2"></i>{{__('web.common.settings')}}
                            </a>
                            <a class="dropdown-item" href="{{ route('sellerprofile') }}">
                                <i class="ti ti-user me-2"></i>{{__('web.user.my_profile')}}
                            </a>
                            <hr>
                            <a class="dropdown-item log-out" href="{{ route('user.logout') }}">
                                <i class="ti ti-logout me-2"></i>{{__('web.common.logout')}}
                            </a>
                        </div>
                    </div>
                </div>
            @else
            <ul class="nav header-navbar-rht">
                <li class="nav-item">
                    <a class="btn btn-light d-inline-flex align-items-center" href="{{ route('user-login') }}"><i class="ti ti-lock me-1"></i>{{__('web.common.sign_in')}}</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-primary d-inline-flex align-items-center" href="{{ route('user-register') }}"><i class="ti ti-user me-1"></i>{{__('web.common.sign_up')}}</a>
                </li>
            </ul>
            @endif
            <!-- /User Menu -->
           </div>
        </nav>
    </div>
</header>
