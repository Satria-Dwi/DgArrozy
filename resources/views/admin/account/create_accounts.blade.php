@extends('layouts.app')

@section('content')
    <div class="flex min-h-screen">
         @include('admin.layouts.sidebar')

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <div class="form mt-10 mb-10" id="signinmodal">
                <form class="card" method="POST" action="{{ route('admin.account.store') }}">
                    @csrf

                    <h1 class="text-2xl font-bold mb-6">Create Account</h1>

                    <!-- Success Message -->
                    @if (session('success'))
                        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Error Messages -->
                    @if ($errors->any())
                        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Email -->
                    <div class="mb-4">
                        <label class="@error('email') is-invalid @enderror" for="email">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}"
                            class="w-full border border-gray-300 rounded px-3 py-2" required>
                    </div>

                    <!-- Role -->
                    <div class="mb-4">
                        <label class="Role" for="role_id">Role</label>
                        <select id="role_id" name="role_id" class="w-full border border-gray-300 rounded px-3 py-2"
                            required>
                            <option value="">-- Pilih Role --</option>
                            @foreach (\App\Models\DgarrozyRole::all() as $role)
                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="mb-4">
                        <label class="is_active" for="is_active">Status</label>
                        <select id="is_active" name="is_active" class="w-full border border-gray-300 rounded px-3 py-2">
                            <option value="1" {{ old('is_active') == 1 ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>

                    <!-- Password -->
                    <div class="mb-6">
                        <label class="password" for="password">Password</label>
                        <input id="password" name="password" type="password"
                            class="w-full border border-gray-300 rounded px-3 py-2" required>
                    </div>

                    <!-- Submit -->
                    <button type="submit"
                        class="btn">
                        Create Account
                    </button>
                </form>
            </div>
        </main>
    </div>
@endsection
