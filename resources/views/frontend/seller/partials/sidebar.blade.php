<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <div class="d-flex align-items-center justify-content-between">
            <a href="/" class="logo logo-normal">
                <img src="/frontend/assets/img/logo.svg" alt="Logo">
            </a>
            <a href="/" class="logo-small">
                <img src="/frontend/assets/img/logo-small.svg" alt="Logo" class="img-fluid">
            </a>
            <a href="/" class="dark-logo">
                <img src="/frontend/assets/img/dark-logo.svg" alt="Logo" class="img-fluid">
            </a>
            <a id="toggle_btn" href="javascript:void(0);" class="active">
                <i class="ti ti-baseline-density-medium"></i>
            </a>
        </div>
    </div>
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li>
                    <ul>
                        <li>
                            <a href="{{ route('seller.dashboard') }}" class="{{ request()->routeIs('seller.dashboard') ? 'active' : '' }}">
                                <i class="ti ti-layout-grid me-2"></i><span>Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('seller.seller-gigs') }}" class="{{ request()->routeIs('seller.seller-gigs') ? 'active' : '' }}">
                                <i class="ti ti-layers-intersect me-2"></i><span>My Gigs</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('seller.my-buyers') }}" class="{{ request()->routeIs('seller.my-buyers') ? 'active' : '' }}">
                                <i class="ti ti-user-bolt me-2"></i><span>My Buyers</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.wallet') }}" class="{{ request()->routeIs('user.wallet') ? 'active' : '' }}">
                                <i class="ti ti-wallet me-2"></i><span>Wallet</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('seller.file-index') }}" class="{{ request()->routeIs('seller.file-index') ? 'active' : '' }}">
                                <i class="ti ti-files me-2"></i><span>Files</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('seller.order-index') }}" class="{{ request()->routeIs('seller.order-index') ? 'active' : '' }}">
                                <i class="ti ti-box me-2"></i><span>Order</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('seller.reviews') }}" class="{{ request()->routeIs('seller.reviews') ? 'active' : '' }}">
                                <i class="ti ti-stars me-2"></i> <span> My Reviews </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('seller.messages') }}" class="{{ request()->routeIs('seller.messages') ? 'active' : '' }}">
                                <i class="ti ti-message me-2"></i> <span>Messages </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('seller.notifications') }}" class="{{ request()->routeIs('seller.notifications') ? 'active' : '' }}">
                                <i class="ti ti-bell me-2"></i> <span>Notifications </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('seller.earning') }}" class="{{ request()->routeIs('seller.earning') ? 'active' : '' }}">
                                <i class="ti ti-moneybag me-2"></i> <span> Earnings </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('seller.settings') }}" class="{{ request()->routeIs('seller.settings') ? 'active' : '' }}">
                                <i class="ti ti-settings-check me-2"></i> <span> Settings</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
            <div class="sidebar-footer">
                <a href="{{ route('user.logout') }}"><i class="ti ti-logout me-2"></i><span>Logout</span></a>
            </div>
        </div>
    </div>
</div>
<!-- /Sidebar -->
