@extends('layouts.app')

@section('content')
<div class="flex min-h-screen">
     @include('admin.layouts.sidebar')

    <!-- Main Content -->
    <main class="flex-1 p-6">
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
                                @if($account->is_active)
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
    </main>
</div>
@endsection
