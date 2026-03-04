@extends('admin.mainlayouts.app')

@section('content')
    <div class="flex min-h-screen">
        @include('admin.layouts.sidebar')

        <!-- Main Content -->
        {{-- <main class="flex-1 p-6 main-content">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Accounts List</h1>
            <a href="{{ route('admin.account.create') }}" 
               class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
               + Create Account
            </a>
        </div>

        <!-- Success / Error Message -->
        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Accounts Table -->
        <div class="overflow-x-auto bg-white shadow-md rounded">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-500 text-white">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium">#</th>
                        <th class="px-6 py-3 text-left text-sm font-medium">Email</th>
                        <th class="px-6 py-3 text-left text-sm font-medium">Role</th>
                        <th class="px-6 py-3 text-left text-sm font-medium">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-medium">Created At</th>
                        <th class="px-6 py-3 text-left text-sm font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($accounts as $account)
                        <tr>
                            <td class="px-6 py-4 text-sm">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 text-sm">{{ $account->email }}</td>
                            <td class="px-6 py-4 text-sm">{{ $account->role->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm">
                                @if ($account->is_active)
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Active</span>
                                @else
                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">{{ $account->created_at->format('d M Y H:i') }}</td>
                            <td class="px-6 py-4 text-sm space-x-2">
                                <a href="{{ route('admin.account.edit', $account->id) }}"
                                   class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                                   Edit
                                </a>
                                <form action="{{ route('admin.account.destroy', $account->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                        class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">No accounts found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main> --}}
        <!-- Main Content -->
        <main class="flex-1 p-6 main-content bg-slate-100 min-h-screen">

            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-slate-800">👤 Accounts Management</h1>
                    <p class="text-sm text-slate-500 mt-1">
                        Manage all system accounts and access roles
                    </p>
                </div>

                <a href="{{ route('admin.account.create') }}"
                    class="inline-flex items-center gap-2 px-5 py-3
                  rounded-xl bg-gradient-to-r from-indigo-600 to-blue-500
                  text-white font-semibold shadow-lg
                  hover:scale-[1.02] hover:shadow-xl transition">
                    <i class="fa-solid fa-plus"></i>
                    Create Account
                </a>
            </div>

            <!-- Alert -->
            @if (session('success'))
                <div class="mb-4 p-4 rounded-xl bg-green-100 text-green-800 border border-green-200 shadow">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 p-4 rounded-xl bg-red-100 text-red-800 border border-red-200 shadow">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Table Card -->
            <div class="bg-white/80 backdrop-blur rounded-2xl shadow-xl border border-white/40 overflow-hidden">

                <!-- Table Header -->
                <div class="px-6 py-4 bg-gradient-to-r from-slate-800 to-slate-700 text-white">
                    <h2 class="text-lg font-semibold">Account List</h2>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-100 text-slate-600 uppercase text-xs tracking-wider">
                            <tr>
                                <th class="px-6 py-3 text-left">#</th>
                                <th class="px-6 py-3 text-left">Email</th>
                                <th class="px-6 py-3 text-left">Role</th>
                                <th class="px-6 py-3 text-left">Status</th>
                                <th class="px-6 py-3 text-left">Created</th>
                                <th class="px-6 py-3 text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($accounts as $account)
                                <tr class="hover:bg-indigo-50 transition">
                                    <td class="px-6 py-4 font-medium text-slate-700">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td class="px-6 py-4 text-slate-800 font-mono">
                                        {{ $account->email }}
                                    </td>

                                    <td class="px-6 py-4">
                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-semibold
                                    bg-indigo-100 text-indigo-700">
                                            {{ $account->role->name ?? '-' }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4">
                                        @if ($account->is_active)
                                            <span
                                                class="inline-flex items-center gap-1 px-3 py-1
                                        bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                                ● Active
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1 px-3 py-1
                                        bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                                                ● Inactive
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-slate-500">
                                        {{ $account->created_at->format('d M Y • H:i') }}
                                    </td>

                                    <td class="px-6 py-4 text-center space-x-2">
                                        <a href="{{ route('admin.account.edit', $account->id) }}"
                                            class="inline-flex items-center px-3 py-2
                                          rounded-lg bg-yellow-500 text-white
                                          hover:bg-yellow-600 transition">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>

                                        <form action="{{ route('admin.account.destroy', $account->id) }}" method="POST"
                                            class="inline-block" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center px-3 py-2
                                               rounded-lg bg-red-500 text-white
                                               hover:bg-red-600 transition">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-8 text-slate-500">
                                        No accounts found 🚫
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>

    </div>
@endsection
