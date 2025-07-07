<!-- Header -->
<div class="header">
    <div class="main-header">
        <div class="header-left">
            <a href="#" class="logo">
                <img src="{{ $logo }}" alt="Logo">
            </a>
            <a href="#" class="dark-logo">
                <img src="{{ $logo }}" alt="Logo">
            </a>
        </div>
        <a id="mobile_btn" class="mobile_btn" href="#sidebar">
            <span class="bar-icon">
                <span></span>
                <span></span>
                <span></span>
            </span>
        </a>
        <div class="header-user">
            <div class="nav user-menu nav-list">
                <div class="me-auto d-flex align-items-center" id="header-search">
                    <a id="toggle_btn" href="javascript:void(0);">
                        <i class="ti ti-menu-deep"></i>
                    </a>
                </div>
                <div class="d-flex align-items-center header-icons">
                    <!-- Flag -->
                    <div class="nav-item dropdown has-arrow flag-nav nav-item-box">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="javascript:void(0);"
                            role="button">
                            <img src="{{ asset('backend/assets/img/flags/'. app()->getLocale() .'.svg') }}" alt="Language" class="img-fluid">
                        </a>
                        <ul class="dropdown-menu p-2">
                            @if ($allLanguages)
                                @foreach ($allLanguages as $language)
                                    <li>
                                        <a href="javascript:void(0);" class="dropdown-item change-language" data-id="{{ $language->id }}" data-language_code="{{ $language->code }}">
                                            <img src="{{ asset('backend/assets/img/flags/'. $language->code.'.svg') }}" alt="" height="16">
                                            {{ $language->name }}
                                        </a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                    <!-- /Flag -->
                    <div class="theme-item">
                        <a href="javascript:void(0);" id="dark-mode-toggle" class="theme-toggle btn btn-menubar">
                            <i class="ti ti-moon"></i>
                        </a>
                        <a href="javascript:void(0);" id="light-mode-toggle" class="theme-toggle d-none btn btn-menubar">
                            <i class="ti ti-sun-high"></i>
                        </a>
                    </div>
                    <div class="notification_item">
                        <a href="#" class="btn btn-menubar position-relative" id="notification_popup" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                            <i class="ti ti-bell"></i>
                            <span class="badge bg-violet rounded-pill"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end notification-dropdown">
                            <div class="topnav-dropdown-header pb-0">
                                <h5 class="notification-title">Notifications</h5>
                            </div>
                            <div class="noti-content">
                            </div>
                            <div class="d-flex align-items-center justify-content-between topnav-dropdown-footer">
                                <div class="d-flex align-items-center">
                                    <a href="#" class="link-primary text-decoration-underline me-3 notify-action d-none" id="mark-all-as-read">Mark all as Read</a>
                                </div>
                                <a href="/admin/notifications" class="btn btn-primary btn-sm d-inline-flex align-items-center notify-action d-none">View All Notifications<i class="ti ti-chevron-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('admin.messages') }}" class="btn btn-menubar position-relative">
                            <i class="ti ti-mail"></i>
                            <span class="badge bg-danger rounded-pill"></span>
                        </a>
                    </div>
                    <div class="dropdown profile-dropdown">
                        <a href="javascript:void(0);" class="d-flex align-items-center" data-bs-toggle="dropdown"  data-bs-auto-close="outside">
                            <span class="avatar avatar-sm">
                                <img src="{{ getCurrentUserImage() }}" alt="Img" class="img-fluid rounded-circle">
                            </span>
                        </a>
                        <div class="dropdown-menu">
                            <div class="profileset d-flex align-items-center">
                                <span class="user-img me-2">
                                    <img src="{{ getCurrentUserImage() }}" alt="">
                                </span>
                                <div>
                                    <h6 class="fw-semibold mb-1"> {{ getCurrentUserFullname() }} </h6>
                                    <p class="fs-13"> {{ current_user()->email ?? '' }} </p>
                                </div>
                            </div>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.profile-settings') }}">
                                <i class="ti ti-user-edit"></i>{{ __('admin.common.edit_profile') }}
                            </a>
                            <a class="dropdown-item d-flex align-items-center" href="javascript:void(0);">
                                <i class="ti ti-bell"></i>{{ __('admin.general_settings.notifications') }}
                            </a>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.security-settings') }}">
                                <i class="ti ti-exchange"></i>{{ __('admin.general_settings.change_password') }}
                            </a>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.company-settings') }}">
                                <i class="ti ti-settings"></i>{{ __('admin.general_settings.settings') }}
                            </a>
                            <a class="dropdown-item logout d-flex align-items-center" href="{{ route('admin.logout') }}">
                                <i class="ti ti-logout"></i>{{ __('admin.common.logout') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="dropdown mobile-user-menu">
            <a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-ellipsis-v"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-end">
                <a class="dropdown-item" href="{{ route('admin.profile-settings') }}">My Profile</a>
                <a class="dropdown-item" href="{{ route('admin.company-settings') }}">Settings</a>
                <a class="dropdown-item" href="{{ route('admin.logout') }}">Logout</a>
            </div>
        </div>
        <!-- /Mobile Menu -->

    </div>

</div>
<!-- /Header -->
