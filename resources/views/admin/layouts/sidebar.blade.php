{{-- <aside id="sidebar" class="sidebar">
    <div class="sidebar-brand">
        <span>MArRozy</span>
    </div>

    <nav class="sidebar-menu">
        <a href="/mainadmin" class="menu-item {{ request()->is('mainadmin') ? 'active' : '' }}">
            <i class="fas fa-chart-pie"></i>
            <span>Dashboard</span>
        </a>

        <a href="/officer" class="menu-item {{ request()->is('officer') ? 'active' : '' }}">
            <i class="fas fa-user-md"></i>
            <span>Officer</span>
        </a>

        <a href="/finances" class="menu-item {{ request()->is('finances*') ? 'active' : '' }}">
            <i class="fas fa-wallet"></i>
            <span>Finances</span>
        </a>

        @php $roleCode = session('account_role_code'); @endphp
        @if (session('dgarrozy_login') && in_array($roleCode, ['admin']))
            <a href="/dgarrozy-user" class="menu-item {{ request()->is('dgarrozy-user*') ? 'active' : '' }}">
                <i class="fas fa-users-cog"></i>
                <span>MAccounts</span>
            </a>
        @endif
    </nav>

    <div class="sidebar-footer">
        <form action="/signout" method="POST">
            @csrf
            <button class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Signout</span>
            </button>
        </form>

        <div class="copyright">© 2026</div>
    </div>
</aside>

<div id="sidebarOverlay" class="sidebar-overlay"></div> --}}

<aside id="sidebar" class="sidebar">
    <div class="sidebar-brand">
        <span>MArRozy</span>
    </div>

    <nav class="sidebar-menu">

        {{-- Dashboard --}}
        <a href="/mainadmin" class="menu-item {{ request()->is('mainadmin') ? 'active' : '' }}">
            <i class="fas fa-chart-pie"></i>
            <span>Dashboard</span>
        </a>

        {{-- Officer --}}
        <a href="/officer" class="menu-item {{ request()->is('officer*') ? 'active' : '' }}">
            <i class="fas fa-user-md"></i>
            <span>Officer</span>
        </a>

        {{-- ===== PARENT MENU ===== --}}
        @php
            $roleCode = session('admin_role_code');
        @endphp

        @if (session('admin_login') && in_array($roleCode, ['admin']))
            @php
                $masterActive =
                    request()->is('dgarrozy-user*') || request()->is('dgarrozysimrs/user*') || request()->is('roles*');
            @endphp

            {{-- Finances --}}
            <a href="/finances" class="menu-item {{ request()->is('finances*') ? 'active' : '' }}">
                <i class="fas fa-coins"></i>
                <span>Finances</span>
            </a>
            <div class="menu-parent {{ $masterActive ? 'open active' : '' }}">
                <button type="button" class="menu-item parent-toggle">
                    <i class="fas fa-layer-group"></i>
                    <span>Accounts</span>
                    <i class="fas fa-chevron-down arrow"></i>
                </button>

                {{-- SUB MENU --}}
                <div class="submenu">
                    <a href="/dgarrozy-user" class="submenu-item {{ request()->is('dgarrozy-user*') ? 'active' : '' }}">
                        <i class="fas fa-users-cog"></i>
                        <span>Account Management</span>
                    </a>

                    <a href="/dgarrozysimrs/user"
                        class="submenu-item {{ request()->is('dgarrozysimrs/user*') ? 'active' : '' }}">
                        <i class="fas fa-users-cog"></i>
                        <span>Account SIMRS</span>
                    </a>

                    {{-- <a href="/roles" class="submenu-item {{ request()->is('roles*') ? 'active' : '' }}">
                        <i class="fas fa-user-shield"></i>
                        <span>Roles</span>
                    </a> --}}
                </div>
            </div>
        @endif
    </nav>

    <div class="sidebar-footer">
        <form action="/signout" method="POST">
            @csrf
            <button class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Signout</span>
            </button>
        </form>

        <div class="copyright">© 2026</div>
    </div>
</aside>

<div id="sidebarOverlay" class="sidebar-overlay"></div>
