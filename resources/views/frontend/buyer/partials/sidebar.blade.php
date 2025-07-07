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
                            <a href="{{ route('buyer.dashboard') }}" class="{{ request()->routeIs('buyer.dashboard') ? 'active' : '' }}">
                                <i class="ti ti-layout-grid me-2"></i><span>Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('buyer.purchase-index') }}" class="{{ request()->routeIs('buyer.purchase-index') ? 'active' : '' }}">
                                <i class="ti ti-layers-intersect me-2"></i><span>My Purchase</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('buyer.my-sellers') }}" class="{{ request()->routeIs('buyer.my-sellers') ? 'active' : '' }}">
                                <i class="ti ti-user-bolt me-2"></i><span>My Sellers</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('buyer.favorites') }}">
                                <i class="ti ti-heart me-2"></i><span>Favourites</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.wallet') }}" class="{{ request()->routeIs('user.wallet') ? 'active' : '' }}">
                                <i class="ti ti-wallet me-2"></i><span>Wallet</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('buyer.reviews') }}" class="{{ request()->routeIs('buyer.reviews') ? 'active' : '' }}">
                                <i class="ti ti-stars me-2"></i><span>My Reviews</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('buyer.messages') }}" class="{{ request()->routeIs('buyer.messages') ? 'active' : '' }}">
                                <i class="ti ti-message me-2"></i><span>Messages</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('buyer.notifications') }}" class="{{ request()->routeIs('buyer.notifications') ? 'active' : '' }}">
                                <i class="ti ti-bell me-2"></i><span>Notifications</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('buyer.buyerTransaction') }}" class="{{ request()->routeIs('buyer.buyerTransaction') ? 'active' : '' }}">
                                <i class="ti ti-transition-top me-2"></i><span>Transactions</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('buyerprofile') }}" class="{{ request()->routeIs('buyerprofile') ? 'active' : '' }}">
                                <i class="ti ti-user me-2"></i><span>My Profile</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('buyer.settings') }}" class="{{ request()->routeIs('buyer.settings') ? 'active' : '' }}">
                                <i class="ti ti-settings-check me-2"></i><span>Settings</span>
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
