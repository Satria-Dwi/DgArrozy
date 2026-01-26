@extends('admin.mainlayouts.app')

@section('content')
    <div class="flex min-h-screen">
        @include('admin.layouts.sidebar')
        <main class="flex-1 p-6 main-content">
            <form class="card-edit" action="{{ route('admin.account.update', $account->id) }}" method="POST">
                @csrf
                @method('PUT')

                <h1 class="text-2xl font-bold mb-2 text-center">Edit Account</h1>
                <p class="text-center mb-4 text-gray-600">Update user details</p>

                <label>Email</label>
                <input type="email" name="email" value="{{ old('email', $account->email) }}" class="form-control">

                <label>Password</label>
                <input type="password" name="password" class="form-control">

                <label>Role</label>
                <select name="role_id">
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" {{ $account->role_id == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>

                <label>Status</label>
                <select name="is_active">
                    <option value="1" {{ $account->is_active ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ !$account->is_active ? 'selected' : '' }}>Inactive</option>
                </select>

                <button class="btn" type="submit">Update Account</button>
                <a href="{{ route('admin.account.index') }}" type="submit">Cancel</button>
            </form>
        </main>
    </div>
@endsection
