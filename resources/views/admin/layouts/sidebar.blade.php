<aside id="sidebar" class="sidebar">
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

<div id="sidebarOverlay" class="sidebar-overlay"></div>
