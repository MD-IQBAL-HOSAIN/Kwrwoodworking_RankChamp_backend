<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    @php
        $system = \App\Models\SystemSetting::first();
        $logo = $system ? $system->logo : null;
        $privacy = App\Models\PrivacyPolicy::first();
        $terms = App\Models\PrivacyPolicy::offset(1)->first();
    @endphp

    <div class="app-brand demo">
        <a href="" class="app-brand-link">
            <img src="{{ asset($logo) }}" alt="" class="img-fluid">
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

        <li class="menu-item {{ Request::routeIs('dashboard') ? 'active' : '' }}">
            <a class="menu-link" href="{{ route('dashboard') }}">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>

        <li class="menu-header small text-uppercase"><span class="menu-header-text">CMS</span></li>
        <!-- CMS -->
        {{-- <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-cog"></i>
                <div data-i18n="Layouts">CMS</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item">
                    <a class="menu-link" href="">Contact Section</a>
                </li>
            </ul>
        </li> --}}

        {{-- It's for home page cms --}}
        <li
            class="menu-item {{ Request::routeIs('cms.home.*') || Request::routeIs('cms.home.header') || Request::routeIs('cms.home.about') || Request::routeIs('cms.home.contact') || Request::routeIs('cms.service.banner') || Request::routeIs('cms.product.banner') || Request::routeIs('cms.cart.banner') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bxs-store-alt"></i>
                <div data-i18n="Layouts">CMS Page</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item {{ Request::routeIs('cms.home.header') ? 'active' : '' }}">
                    <a class="menu-link" href="{{ route('cms.home.header') }}">Home baner</a>
                </li>
            </ul>

            {{--  Service Banner --}}
            <ul class="menu-sub">
                <li class="menu-item {{ Request::routeIs('cms.service.banner') ? 'active' : '' }}">
                    <a class="menu-link" href="{{ route('cms.service.banner') }}">Service Banner</a>
                </li>
            </ul>
            {{-- Product Banner --}}
            <ul class="menu-sub">
                <li class="menu-item {{ Request::routeIs('cms.product.banner') ? 'active' : '' }}">
                    <a class="menu-link" href="{{ route('cms.product.banner') }}">Product Banner</a>
                </li>
            </ul>
            {{-- Cart Banner --}}
            <ul class="menu-sub">
                <li class="menu-item {{ Request::routeIs('cms.cart.banner') ? 'active' : '' }}">
                    <a class="menu-link" href="{{ route('cms.cart.banner') }}">Cart Banner</a>
                </li>
            </ul>

            <ul class="menu-sub">
                <li class="menu-item {{ Request::routeIs('cms.home.about') ? 'active' : '' }}">
                    <a class="menu-link" href="{{ route('cms.home.about') }}">Home About</a>
                </li>
            </ul>

            <ul class="menu-sub">
                <li class="menu-item {{ Request::routeIs('cms.home.contact') ? 'active' : '' }}">
                    <a class="menu-link" href="{{ route('cms.home.contact') }}">Contact Info</a>
                </li>
            </ul>

        </li>


        {{-- ..................................................... --}}

        <!-- Products -->
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Products</span></li>
        <!-- Layouts -->
        <li class="menu-item {{ Request::routeIs('product.index') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bxl-blogger"></i>
                <div data-i18n="Layouts">Product</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item {{ Request::routeIs('product.index') ? 'active' : '' }}"><a class="menu-link"
                        href="{{ route('product.index') }}">Products</a></li>
            </ul>
        </li>

        <!-- order -->
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Order Management</span></li>
        <!-- Layouts -->
        <li class="menu-item {{ Request::routeIs('order.index') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bxl-blogger"></i>
                <div data-i18n="Layouts">Order Management</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item {{ Request::routeIs('order.index') ? 'active' : '' }}">
                    <a class="menu-link" href="{{ route('order.index') }}">Orders</a>
                </li>
            </ul>
        </li>

        <!-- Service -->
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Service</span></li>

        <li class="menu-item {{ Request::routeIs('service.all') ? 'active' : '' }}">
            <a href="{{ route('service.all') }}" class="menu-link">
                <i class='menu-icon tf-icons bx bxs-badge-check'></i>
                <div data-i18n="Layouts">Service</div>
            </a>
        </li>



        <!-- FAQ-->
        <li class="menu-header small text-uppercase"><span class="menu-header-text">FAQ</span></li>

        <li class="menu-item {{ Request::routeIs('faq.index*') ? 'active' : '' }}">
            <a class="menu-link" href="{{ route('faq.index') }}">
                <i class='menu-icon tf-icons bx bxs-badge-check'></i>
                <div data-i18n="Layouts">FAQ</div>
            </a>
        </li>

        <!-- Reviews-->
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Review</span></li>

        <li class="menu-item {{ Request::routeIs('review.index*') ? 'active' : '' }}">
            <a class="menu-link" href="{{ route('review.index') }}">
                <i class='menu-icon tf-icons bx bxs-badge-check'></i>
                <div data-i18n="Layouts">Review</div>
            </a>
        </li>

        <!-- User-->
        <li class="menu-header small text-uppercase"><span class="menu-header-text">User</span></li>

        <li class="menu-item {{ Request::routeIs('alluser.user*') ? 'active' : '' }}">
            <a href="{{ route('alluser.user') }}" class="menu-link">
                <i class='menu-icon tf-icons bx bxs-user'></i>
                <div data-i18n="Layouts">User</div>
            </a>
        </li>

        {{-- ..................................................... --}}



        <!-- Settings -->
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Settings</span></li>
        <!-- Layouts -->
        <li
            class="menu-item {{ Request::routeIs('admin.system.setting') || Request::routeIs('system.mail.index') || Request::routeIs('social.media') || Request::routeIs('stripe.index') || Request::routeIs('privacy.edit') || Request::routeIs('terms.edit') || Request::routeIs('profilesetting') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-cog"></i>
                <div data-i18n="Layouts">Settings</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item {{ Request::routeIs('admin.system.setting') ? 'active' : '' }}"><a
                        class="menu-link" href="{{ route('admin.system.setting') }}">System Settings</a>
                </li>
                {{-- prifile setting start --}}
                <li class="menu-item {{ Request::routeIs('profilesetting') ? 'active' : '' }}">
                    <a class="menu-link" href="{{ route('profilesetting') }}">
                        <div data-i18n="Support">Profile Setting</div>
                    </a>
                </li>
                {{-- prifile setting end --}}

                <li class="menu-item {{ Request::routeIs('social.media') ? 'active' : '' }}">
                    <a class="menu-link" href="{{ route('social.media') }}">Social Media</a>
                </li>

                <li class="menu-item {{ Request::routeIs('system.mail.index') ? 'active' : '' }}">
                    <a class="menu-link" href="{{ route('system.mail.index') }}">Mail Setting</a>
                </li>

                <li class="menu-item {{ Request::routeIs('stripe.index') ? 'active' : '' }}">
                    <a class="menu-link" href="{{ route('stripe.index') }}">Stripe</a>
                </li>

                <li class="menu-item {{ Request::routeIs('privacy.edit') ? 'active' : '' }}">
                    <a class="menu-link" href="{{ route('privacy.edit', ['slug' => $privacy->slug]) }}">Privacy &
                        Policy </a>
                </li>

                <li class="menu-item {{ Request::routeIs('terms.edit') ? 'active' : '' }}">
                    <a class="menu-link" href="{{ route('terms.edit', ['slug' => $terms->slug]) }}">Terms &
                        Conditions</a>
                </li>

            </ul>
            {{-- <li class="menu-item"><a class="menu-link" href="">Paypal</a></li> --}}
        </li>
    </ul>
</aside>
