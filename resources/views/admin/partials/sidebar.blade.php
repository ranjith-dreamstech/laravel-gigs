<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <!-- Logo -->
    <div class="sidebar-logo">
        <a href="{{ route('dashboard') }}" class="logo logo-normal">
            <img src="{{ $logo }}" alt="Logo">
        </a>
        <a href="{{ route('dashboard') }}" class="logo-small">
            <img src="{{ $smallLogo }}" alt="Logo">
        </a>
        <a href="{{ route('dashboard') }}" class="dark-logo">
            <img src="{{ $logo }}" alt="Logo">
        </a>
    </div>
    <!-- /Logo -->
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                @if (haspermission($permissions, 'dashboard', 'view'))
                <li class="menu-title"><span></span></li>
                <li>
                    <ul>
                        <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <a href="{{ route('dashboard') }}">
                                <i class="ti ti-layout-grid-add"></i><span>{{ __('admin.main.dashboard') }}</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                @if (hasPermission($permissions, ['gigs', 'category', 'subcategory'], 'view'))
                <li class="menu-title"><span>{{ strtoupper(__('admin.manage.manage')) }}</span></li>
                <li>
                    <ul>
                        @if (hasPermission($permissions, 'gigs', 'view'))
                        <li class="{{ request()->routeIs('admin.gigs.index') ? 'active' : ''}}">
                            <a href="{{ route('admin.gigs.index') }}">
                                <i class="ti ti-box"></i><span>{{ __('admin.manage.gigs') }}</span>
                            </a>
                        </li>
                        @endif
                        @if (hasPermission($permissions, 'category', 'view'))
                        <li class="{{ request()->routeIs('admin.category') ? 'active' : ''}}">
                            <a href="{{ route('admin.category') }}">
                                <i class="ti ti-category"></i><span>{{ __('admin.manage.category') }}</span>
                            </a>
                        </li>
                        @endif
                        @if (hasPermission($permissions, 'subcategory', 'view'))
                        <li class="{{ request()->routeIs('admin.subCategoryIndex') ? 'active' : ''}}">
                            <a href="{{ route('admin.subCategoryIndex') }}">
                                <i class="ti ti-subtask"></i><span>{{ __('admin.manage.sub_category') }}</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
                @if (hasPermission($permissions, ['buyer_earning', 'buyer_earning', 'refund'], 'view'))
                <li class="menu-title"><span>{{ strtoupper(__('admin.finance.finance')) }}</span></li>
                <li>
                    <ul>
                        @if (hasPermission($permissions, 'buyer_earning', 'view'))
                        <li class="{{ request()->routeIs('admin.buyer-earning') ? 'active' : ''}}">
                            <a href="{{ route('admin.buyer-earning') }}">
                                <i class="ti ti-coin"></i><span>{{ __('admin.finance.buyer_earning') }}</span>
                            </a>
                        </li>
                        @endif
                        @if (hasPermission($permissions, 'buyer_request', 'view'))
                        <li class="{{ request()->routeIs('admin.buyer-request') ? 'active' : ''}}">
                            <a href="{{ route('admin.buyer-request') }}">
                                <i class="ti ti-user-plus"></i><span>{{ __('admin.finance.buyer_request') }}</span>
                            </a>
                        </li>
                        @endif
                        @if (hasPermission($permissions, 'refund', 'view'))
                        <li class="{{ request()->routeIs('admin.booking-refund') ? 'active' : ''}}">
                            <a href="{{ route('admin.booking-refund') }}">
                                <i class="ti ti-receipt-refund"></i><span>{{ __('admin.finance.refund') }}</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
                @if (hasPermission($permissions, ['messages'], 'view'))
                <li class="menu-title"><span>{{ strtoupper(__('admin.others.others')) }}</span></li>
                <li>
                    <ul>
                        @if (hasPermission($permissions, 'messages', 'view'))
                        <li class="{{ request()->routeIs('admin.messages') ? 'active' : '' }}">
                            <a href="{{ route('admin.messages') }}">
                                <i class="ti ti-message"></i><span>{{ __('admin.others.messages') }}</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
                @if (hasPermission($permissions, ['page', 'section', 'menu_management', 'testimonials', 'faq', 'blogs', 'cms_locations', 'how_it_works', 'copyright'], 'view'))
                <li class="menu-title"><span>{{ strtoupper(__('admin.cms.cms')) }}</span></li>
                <li>
                    <ul>
                        @if (hasPermission($permissions, 'page', 'view'))
                        <li class="{{ request()->routeIs('admin.pageIndex') ? 'active' : '' }}">
                            <a href="{{ route('admin.pageIndex') }}">
                                <i class="ti ti-file-invoice"></i><span>{{ __('admin.cms.pages') }}</span>
                            </a>
                        </li>
                        @endif
                        @if (hasPermission($permissions, 'section', 'view'))
                        <li class="{{ request()->routeIs('admin.indexSection') ? 'active' : '' }}">
                            <a href="{{ route('admin.indexSection') }}">
                                <i class="ti ti-file-symlink"></i><span>{{ __('admin.cms.section') }}</span>
                            </a>
                        </li>
                        @endif
                        @if (hasPermission($permissions, 'menu_management', 'view'))
                        <li class="{{ request()->routeIs('admin.menu') ? 'active' : '' }}">
                            <a href="{{ route('admin.menu') }}">
                                <i class="ti ti-menu-2"></i><span>{{ __('admin.cms.menu_management') }}</span>
                            </a>
                        </li>
                        @endif
                        @if (hasPermission($permissions, 'blogs', 'view'))
                        <li class="submenu">
                            <a href="javascript:void(0);" class="{{ request()->is('admin/content/blog-category') || request()->is('admin/content/blogs') || request()->is('admin/content/blog-comments') || request()->is('admin/content/blog-tags') || request()->is('admin/content/add-blog') || request()->is('admin/content/blogs/*') ? 'subdrop active' : '' }}">
                                <i class="ti ti-brand-blogger"></i><span>{{ __('admin.cms.blogs') }}</span><span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="{{ route('admin.blogs') }}" class="{{ request()->is('admin/content/blogs') || request()->is('admin/content/add-blog') || request()->is('admin/content/blogs/*') ? 'active' : '' }}">{{ __('admin.cms.all_blogs') }}</a></li>
                                <li><a href="{{ route('admin.blog-category') }}" class="{{ request()->is('admin/content/blog-category') ? 'active' : '' }}">{{ __('admin.common.categories') }}</a></li>
                                <li><a href="{{ route('admin.blog-comments') }}" class="{{ request()->is('admin/content/blog-comments') ? 'active' : '' }}">{{ __('admin.common.comments') }}</a></li>
                                <li><a href="{{ route('admin.blog-tags') }}" class="{{ request()->is('admin/content/blog-tags') ? 'active' : '' }}">{{ __('admin.cms.blog_tags') }}</a></li>
                            </ul>
                        </li>
                        @endif
                        @if (hasPermission($permissions, 'cms_locations', 'view'))
                        <li class="submenu">
                            <a href="javascript:void(0);" class="{{ request()->routeIs(['country.index', 'state.index', 'city.index']) ? 'subdrop active' : '' }}">
                                <i class="ti ti-device-camera-phone"></i><span>{{ __('admin.cms.locations') }}</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a class="{{ request()->routeIs('country.index') ? 'active' : '' }}" href="{{ route('country.index') }}">{{ __('admin.common.countries') }}</a></li>
                                <li><a class="{{ request()->routeIs('state.index') ? 'active' : '' }}" href="{{ route('state.index') }}">{{ __('admin.common.states') }}</a></li>
                                <li><a class="{{ request()->routeIs('city.index') ? 'active' : '' }}" href="{{ route('city.index') }}">{{ __('admin.common.cities') }}</a></li>
                            </ul>
                        </li>
                        @endif
                        @if (hasPermission($permissions, 'testimonials', 'view'))
                        <li class="{{ request()->routeIs('admin.testimoials') ? 'active' : '' }}">
                            <a href="{{ route('admin.testimoials') }}">
                                <i class="ti ti-brand-hipchat"></i><span>{{ __('admin.cms.testimonials') }}</span>
                            </a>
                        </li>
                        @endif
                        @if (hasPermission($permissions, 'faq', 'view'))
                        <li class="{{ request()->routeIs('admin.faq') ? 'active' : '' }}">
                            <a href="{{ route('admin.faq') }}">
                                <i class="ti ti-question-mark"></i><span>{{ __('admin.cms.faq') }}</span>
                            </a>
                        </li>
                        @endif
                        @if (hasPermission($permissions, 'how_it_works', 'view'))
                        <li class="{{ request()->routeIs('admin.howItWorks') ? 'active' : '' }}">
                            <a href="{{ route('admin.howItWorks') }}">
                                <i class="ti ti-messages"></i><span>{{ __('admin.cms.how_it_works') }}</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
                @if (hasPermission($permissions, ['users', 'roles_permissions'], 'view'))
                <li class="menu-title"><span>{{ strtoupper(__('admin.user_management.user_management')) }}</span></li>
                <li>
                    <ul>
                        @if (hasPermission($permissions, 'users', 'view'))
                        <li class="{{ request()->routeIs('admin.users') ? 'active' : '' }}">
                            <a href="{{ route('admin.users') }}">
                                <i class="ti ti-user-circle"></i><span>{{ __('admin.common.users') }}</span>
                            </a>
                        </li>
                        @endif
                        @if (hasPermission($permissions, 'roles_permissions', 'view'))
                        <li class="{{ request()->routeIs(['admin.roles-permisions', 'admin.permissions']) ? 'active' : '' }}">
                            <a href="{{ route('admin.roles-permisions') }}">
                                <i class="ti ti-user-shield"></i><span>{{ __('admin.user_management.roles_permissions') }}</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
                @if (hasPermission($permissions, ['account_settings', 'website_settings', 'rental_settings', 'app_settings', 'system_settings', 'finance_settings', 'other_settings'], 'view') || request()->routeIs(['admin.profile-settings']))
                <li class="menu-title"><span>{{ strtoupper(__('admin.general_settings.settings_configuration')) }}</span></li>
                <li>
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);" class="{{ request()->routeIs(['admin.profile-settings','admin.security-settings', 'admin.notifications-settings']) ? 'subdrop active' : '' }}">
                                <i class="ti ti-user-cog"></i><span>{{ __('admin.general_settings.account_settings') }}</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li>
                                    <a href="{{ route('admin.profile-settings') }}" class="{{ request()->routeIs('admin.profile-settings') ? 'active' : '' }}">{{ __('admin.general_settings.profile') }}</a>
                                </li>
                                @if (hasPermission($permissions, 'account_settings', 'view'))
                                <li>
                                    <a href="{{ route('admin.security-settings') }}" class="{{ request()->routeIs('admin.security-settings') ? 'active' : '' }}">{{ __('admin.general_settings.security') }}</a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.notifications-settings') }}" class="{{ request()->routeIs('admin.notifications-settings') ? 'active' : '' }}">{{ __('admin.general_settings.notifications') }}</a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @if (hasPermission($permissions, 'website_settings', 'view'))
                        <li class="submenu">
                            <a href="javascript:void(0);" class="{{ request()->routeIs(['admin.company-settings', 'admin.logo-settings', 'admin.localization','admin.prefixes-settings','admin.seosetup-settings','admin.languages','admin.copyright','admin.maintenance-settings','admin.ai-configuration', 'admin.theme-settings', 'admin.otp-settings']) ? 'subdrop active' : '' }}">
                                <i class="ti ti-world-cog"></i><span>{{ __('admin.general_settings.website_settings') }}</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li>
                                    <a href="{{ route('admin.company-settings') }}" class="{{ request()->routeIs('admin.company-settings') ? 'active' : '' }}">{{ __('admin.general_settings.company_settings') }}</a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.logo-settings') }}" class="{{ request()->routeIs('admin.logo-settings') ? 'active' : '' }}">{{ __('admin.general_settings.logo_favicon_settings') }}</a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.localization') }}" class="{{ request()->routeIs('admin.localization') ? 'active' : '' }}">{{ __('admin.general_settings.localization') }}</a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.prefixes-settings') }}" class="{{ request()->routeIs('admin.prefixes-settings') ? 'active' : '' }}">{{ __('admin.general_settings.prefixes') }}</a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.seosetup-settings') }}" class="{{ request()->routeIs('admin.seosetup-settings') ? 'active' : '' }}">{{ __('admin.general_settings.seo_setup_settings') }}</a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.languages') }}" class="{{ request()->routeIs('admin.languages') ? 'active' : '' }}">{{ __('admin.general_settings.languages') }}</a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.maintenance-settings') }}" class="{{ request()->routeIs('admin.maintenance-settings') ? 'active' : '' }}">{{ __('admin.general_settings.maintenance_mode') }}</a>
                                </li>
                                <li class="d-none">
                                    <a href="{{ route('admin.ai-configuration') }}" class="{{ request()->routeIs('admin.ai-configuration') ? 'active' : '' }}">{{ __('admin.general_settings.ai_configuration') }}</a>
                                </li>
                               
                                <li>
                                    <a href="{{ route('admin.theme-settings') }}" class="{{ request()->routeIs('admin.theme-settings') ? 'active' : '' }}">{{ __('admin.general_settings.theme_settings') }}</a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.otp-settings') }}" class="{{ request()->routeIs('admin.otp-settings') ? 'active' : '' }}">{{ __('admin.general_settings.otp_settings') }}</a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.copyright') }}" class="{{ request()->routeIs('admin.copyright') ? 'active' : '' }}">{{ __('admin.cms.copyright') }}</a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        @if (hasPermission($permissions, 'app_settings', 'view'))
                        <li class="submenu">
                            <a href="javascript:void(0);" class="{{ request()->routeIs(['admin.signature-setting', 'admin.invoiceSettings-settings']) ? 'subdrop active' : '' }}">
                                <i class="ti ti-device-mobile-cog"></i><span>{{ __('admin.general_settings.app_settings') }}</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li>
                                    <a href="{{ route('admin.invoiceSettings-settings') }}" class="{{ request()->routeIs('admin.invoiceSettings-settings') ? 'active' : '' }}">{{ __('admin.general_settings.invoice_settings') }}</a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.signature-settings') }}" class="{{ request()->routeIs('admin.signature-setting') ? 'active' : '' }}">{{ __('admin.general_settings.signature') }}</a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        @if (hasPermission($permissions, 'system_settings', 'view'))
                        <li class="submenu">
                            <a href="javascript:void(0);" class="{{ request()->routeIs(['email_templates.index', 'admin.smsGateway-settings', 'admin.gdpr-cookies-settings', 'admin.email-settings']) ? 'subdrop active' : '' }}">
                                <i class="ti ti-device-desktop-cog"></i><span>{{ __('admin.general_settings.system_settings') }}</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li>
                                    <a href="{{ route('admin.email-settings') }}" class="{{ request()->routeIs('admin.email-settings') ? 'active' : '' }}">{{ __('admin.general_settings.email_settings') }}</a>
                                </li>
                                <li>
                                    <a href="{{ route('email_templates.index') }}" class="{{ request()->routeIs('email_templates.index') ? 'active' : '' }}">{{ __('admin.general_settings.email_templates') }}</a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.smsGateway-settings') }}" class="{{ request()->routeIs('admin.smsGateway-settings') ? 'active' : '' }}">{{ __('admin.general_settings.sms_gateways') }}</a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.gdpr-cookies-settings') }}" class="{{ request()->routeIs('admin.gdpr-cookies-settings') ? 'active' : '' }}">{{ __('admin.general_settings.gdpr_cookies') }}</a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        @if (hasPermission($permissions, 'finance_settings', 'view'))
                        <li class="submenu">
                            <a href="javascript:void(0);" class="{{ request()->routeIs(['admin.currencies', 'admin.tax-rates', 'admin.paymentIndex-settings']) ? 'subdrop active' : '' }}">
                                <i class="ti ti-settings-dollar"></i><span>{{ __('admin.general_settings.finance_settings') }}</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li>
                                    <a href="{{ route('admin.paymentIndex-settings') }}" class="{{ request()->routeIs('admin.paymentIndex-settings') ? 'active' : '' }}">{{ __('admin.general_settings.payment_methods') }}</a>
                                </li>
                               
                                <li>
                                    <a href="{{ route('admin.tax-rates') }}" class="{{ request()->routeIs('admin.tax-rates') ? 'active' : '' }}">{{ __('admin.general_settings.tax_rates') }}</a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.currencies') }}" class="{{ request()->routeIs('admin.currencies') ? 'active' : '' }}">{{ __('admin.general_settings.currencies') }}</a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        @if (hasPermission($permissions, 'other_settings', 'view'))
                        <li class="submenu">
                            <a href="javascript:void(0);" class="{{ request()->routeIS(['admin.sitemap','admin.storage-settings', 'admin.database-settings', 'admin.system-backup-settings', 'admin.clearCache-settings']) ? 'subdrop active' : '' }}">
                                <i class="ti ti-settings-2"></i><span>{{ __('admin.general_settings.other_settings') }}</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li>
                                    <a href="{{ route('admin.sitemap') }}" class="{{ request()->routeIs('admin.sitemap') ? 'active' : '' }}">{{ __('admin.general_settings.sitemap') }}</a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.clearCache-settings') }}" class="{{ request()->routeIs('admin.clearCache-settings') ? 'active' : '' }}">{{ __('admin.general_settings.clear_cache') }}</a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.storage-settings') }}" class="{{ request()->routeIs('admin.storage-settings') ? 'active' : '' }}">{{ __('admin.general_settings.storage') }}</a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.system-backup-settings') }}" class="{{ request()->routeIs('admin.system-backup-settings') ? 'active' : '' }}">{{ __('admin.general_settings.system_backup') }}</a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.database-settings') }}" class="{{ request()->routeIs('admin.database-settings') ? 'active' : '' }}">{{ __('admin.general_settings.database_backup') }}</a>
                                </li>
                            </ul>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
            </ul>
        </div>
    </div>
</div>
<!-- /Sidebar -->
