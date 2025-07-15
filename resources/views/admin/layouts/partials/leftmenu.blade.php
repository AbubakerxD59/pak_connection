<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link">
        <img src="{{ getCompanyLogoUrl() }}" alt="{{ env('APP_NAME', 'PakConnetions') }}" class="brand-image"
            style="width: 220px;">
        <span class="brand-text font-weight-light">{{ getCompanyName() }}</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel py-3 d-flex">
            <div class="image off">
                <!-- <img src="/assets/img/user-icon.png" class="userimg" alt="User Image"> -->
                <i class="nav-icon fas fa-power-off"></i>
            </div>
            <div class="info">
                <form action="{{ route('logout') }}" method="POST" id="signout_form">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                </form>
                <a class="sign-out pointer">Logout</a>
            </div>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column py-2" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                        class="nav-link {{ request()->route()->getName() == 'dashboard' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-list-alt"></i>
                        <p>{{ __('partials.left_menu_dashboard') }}</p>
                    </a>
                </li>
                {{-- Manage Users --}}
                @if (check_permission('view_user') || check_permission('view_role') || check_permission('view_permission'))
                    @php
                        $user = in_array(request()->route()->getName(), [
                            'users.index',
                            'users.create',
                            'users.edit',
                            'roles.index',
                            'roles.create',
                            'roles.edit',
                            'permissions.index',
                        ])
                            ? true
                            : false;
                    @endphp
                    <li class="nav-item {{ $user ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $user ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user"></i>
                            <p>
                                CUSTOMERS
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                @can('view_user')
                                    <a href="{{ route('users.index') }}"
                                        class="nav-link {{ in_array(request()->route()->getName(), ['users.index', 'users.create', 'users.edit']) ? 'active' : '' }}">
                                        <i class="nav-icon far fa-dot-circle"></i>
                                        <p>Customers</p>
                                    </a>
                                @endcan
                                @can('view_role')
                                    <a href="{{ route('roles.index') }}"
                                        class="nav-link {{ in_array(request()->route()->getName(), ['roles.index', 'roles.create', 'roles.edit']) ? 'active' : '' }}">
                                        <i class="nav-icon far fa-dot-circle"></i>
                                        <p>Roles</p>
                                    </a>
                                @endcan
                                @can('view_permission')
                                    <a href="{{ route('permissions.index') }}"
                                        class="nav-link {{ in_array(request()->route()->getName(), ['permissions.index']) ? 'active' : '' }}">
                                        <i class="nav-icon far fa-dot-circle"></i>
                                        <p>Permissions</p>
                                    </a>
                                @endcan
                            </li>
                        </ul>
                    </li>
                @endif

                {{-- Manage Packages --}}
                @if (check_permission('view_package'))
                    @php
                        $package = in_array(request()->route()->getName(), [
                            'packages.index',
                            'packages.create',
                            'packages.edit',
                        ])
                            ? true
                            : false;
                    @endphp
                    <li class="nav-item {{ $package ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $package ? 'active' : '' }}">
                            <i class="nav-icon fas fa-credit-card"></i>
                            <p>
                                PACKAGES
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                @can('view_package')
                                    <a href="{{ route('packages.index') }}"
                                        class="nav-link {{ in_array(request()->route()->getName(), ['packages.index', 'packages.create', 'packages.edit']) ? 'active' : '' }}">
                                        <i class="nav-icon far fa-dot-circle"></i>
                                        <p>Packages</p>
                                    </a>
                                @endcan
                            </li>
                        </ul>
                    </li>
                @endif

                {{-- Promo Code --}}
                @can('view_feature')
                    @php
                        $promo = in_array(request()->route()->getName(), [
                            'promo-code.index',
                            'promo-code.create',
                            'promo-code.edit',
                        ])
                            ? true
                            : false;
                    @endphp
                    <li class="nav-item {{ $promo ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $promo ? 'active' : '' }}">
                            <i class="nav-icon fas fa-percentage"></i>
                            <p>
                                Promo Codes
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                @can('view_promocode')
                                    <a href="{{ route('promo-code.index') }}"
                                        class="nav-link {{ in_array(request()->route()->getName(), ['promo-code.index', 'promo-code.create', 'promo-code.edit']) ? 'active' : '' }}">
                                        <i class="nav-icon far fa-dot-circle"></i>
                                        <p>Promo Codes</p>
                                    </a>
                                @endcan
                            </li>
                        </ul>
                    </li>
                @endcan

                {{-- Services --}}
                @can('view_feature')
                    @php
                        $service = in_array(request()->route()->getName(), [
                            'features.index',
                            'features.create',
                            'features.edit',
                        ])
                            ? true
                            : false;
                    @endphp
                    <li class="nav-item {{ $service ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $service ? 'active' : '' }}">
                            <i class="nav-icon fas fa-star"></i>
                            <p>
                                Services
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                @can('view_feature')
                                    <a href="{{ route('features.index') }}"
                                        class="nav-link {{ in_array(request()->route()->getName(), ['features.index', 'features.create', 'features.edit']) ? 'active' : '' }}">
                                        <i class="nav-icon far fa-dot-circle"></i>
                                        <p>Services</p>
                                    </a>
                                @endcan
                            </li>
                        </ul>
                    </li>
                @endcan

                {{-- Field --}}
                @can('view_fields')
                    @php
                        $field = in_array(request()->route()->getName(), [
                            'fields.index',
                            'fields.create',
                            'fields.edit',
                        ])
                            ? true
                            : false;
                    @endphp
                    <li class="nav-item {{ $field ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $field ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list"></i>
                            <p>
                                Fields
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                @can('view_fields')
                                    <a href="{{ route('fields.index') }}"
                                        class="nav-link {{ in_array(request()->route()->getName(), ['fields.index', 'fields.create', 'fields.edit']) ? 'active' : '' }}">
                                        <i class="nav-icon far fa-dot-circle"></i>
                                        <p>Feilds</p>
                                    </a>
                                @endcan
                            </li>
                        </ul>
                    </li>
                @endcan

                {{-- Order --}}
                @can('view_orders')
                    @php
                        $orders = in_array(request()->route()->getName(), [
                            'orders.index',
                            'orders.create',
                            'orders.edit',
                            'transactions.index',
                            'transactions.create',
                            'transactions.edit',
                        ])
                            ? true
                            : false;
                    @endphp
                    <li class="nav-item {{ $orders ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $orders ? 'active' : '' }}">
                            <i class="nav-icon fas fa-bookmark"></i>
                            <p>
                                ORDERS
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        @can('view_orders')
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('orders.index') }}"
                                        class="nav-link {{ in_array(request()->route()->getName(), ['orders.index', 'orders.create', 'orders.edit']) ? 'active' : '' }}">
                                        <i class="nav-icon far fa-dot-circle"></i>
                                        <p>Orders</p>
                                    </a>
                                </li>
                            </ul>
                        @endcan
                        @can('view_transactions')
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('transactions.index') }}"
                                        class="nav-link {{ in_array(request()->route()->getName(), ['transactions.index', 'transactions.create', 'transactions.edit']) ? 'active' : '' }}">
                                        <i class="nav-icon far fa-dot-circle"></i>
                                        <p>Transactions</p>
                                    </a>
                                </li>
                            </ul>
                        @endcan
                    </li>


                @endcan


                {{-- Book Service --}}
                @can('view_booked_services')
                    @php
                        $field = in_array(request()->route()->getName(), [
                            'booked-services.index',
                            'booked-services.create',
                            'booked-services.edit',
                            'booked-services.view.bookservice',
                        ])
                            ? true
                            : false;
                    @endphp
                    <li class="nav-item {{ $field ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $field ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list"></i>
                            <p>
                                Bookings
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                @can('view_booked_services')
                                    <a href="{{ route('booked-services.view.bookservice') }}"
                                        class="nav-link {{ in_array(request()->route()->getName(), ['booked-services.view.bookservice', 'booked-services.create', 'booked-services.edit', 'booked-services.index']) ? 'active' : '' }}">
                                        <i class="nav-icon far fa-dot-circle"></i>
                                        <p>Book Service</p>
                                    </a>
                                @endcan
                            </li>
                        </ul>
                    </li>
                @endcan

                {{-- Earnings --}}
                {{-- <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-wallet"></i>
                        <p>
                            EARNINGS
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}" class="nav-link">
                                <i class="nav-icon far fa-dot-circle"></i>
                                <p>Earnings</p>
                            </a>
                            <a href="{{ route('roles.index') }}" class="nav-link">
                                <i class="nav-icon far fa-dot-circle"></i>
                                <p>Invoices</p>
                            </a>
                        </li>
                    </ul>
                </li> --}}
            </ul>
        </nav>
    </div>
</aside>
