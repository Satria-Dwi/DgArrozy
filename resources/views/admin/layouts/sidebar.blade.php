<aside class="w-64 bg-gray-900 text-white flex flex-col">
    
    <div class="p-6 text-2xl font-bold tracking-wide">MArRozy</div>
    <nav class="flex-1 px-4 space-y-2">
        <a href="/mainadmin" class="block px-4 py-2 rounded-lg bg-blue-600 hover:bg-gray-800">Dashboard</a>
        @php
            $roleCode = session('account_role_code'); // ambil code dari session
        @endphp

        @if (session('dgarrozy_login') && in_array($roleCode, ['admin']))
            <a href="/dgarrozy-user" class="block px-4 py-2 rounded-lg bg-blue-600 hover:bg-gray-800">MAccounts</a>
        @endif
    </nav>
    <div class="p-4 text-sm text-gray-400">© 2026</div>
</aside>
