<table class="min-w-full text-sm border">
    <thead class="bg-indigo-600 text-white">
        <tr>
            <th class="px-3 py-2 border">#</th>
            <th class="px-3 py-2 border">Nama</th>
            <th class="px-3 py-2 border">Jabatan</th>
            <th class="px-3 py-2 border">NIK / Username</th>
            <th class="px-3 py-2 border">Password</th>
            <th class="px-3 py-2 border">Status</th>
            <th class="px-3 py-2 border">Aksi</th>
            <th class="px-3 py-2 border">Role</th>
        </tr>
    </thead>
    <tbody class="bg-white">
        @forelse ($user as $i => $row)
            @php
                $exists = DB::table('dgarrozy_usertickets')->where('id_user', $row->nik)->exists();
            @endphp
            <tr class="hover:bg-gray-50">
                <td class="px-3 py-2 border text-center">{{ $i + 1 }}</td>
                <td class="px-3 py-2 border">{{ $row->nama ?? '-' }}</td>
                <td class="px-3 py-2 border">{{ $row->jbtn ?? '-' }}</td>
                <td class="px-3 py-2 border font-mono">{{ $row->nik }}</td>
                <td class="px-3 py-2 border font-mono text-red-600">{{ $row->password }}</td>
                <td class="px-3 py-2 border text-center">
                    <span
                        class="px-2 py-1 rounded text-xs
                        {{ $row->stts_aktif === 'AKTIF' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $row->stts_aktif ?? '-' }}
                    </span>
                </td>
                <td class="px-3 py-2 border text-center">
                    @if ($exists)
                        <button type="button"
                            class="remove-from-userticket-btn text-xs px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700"
                            data-id="{{ $row->nik }}">
                            Remove
                        </button>
                    @else
                        <button type="button"
                            class="add-to-userticket-btn text-xs px-2 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700"
                            data-id="{{ $row->nik }}" data-nik="{{ $row->nik }}">
                            Add to UserTicket
                        </button>
                    @endif

                </td>
                <td class="px-3 py-2 border text-center">
                    <select
                        class="role-select role-modern px-3 py-2 text-sm rounded-lg border border-gray-300 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all duration-200 cursor-pointer"
                        data-id-user="{{ $row->nik }}">
                        @foreach ([
        'pembuat' => '📝 PEMBUAT',
        'head_section' => '👔 HEAD SECTION',
        'app_dept' => '🏢 APPROVER DEPT',
        'approved' => '✅ APPROVED',
        'admin' => '🛡️ ADMIN',
    ] as $value => $label)
                            <option value="{{ $value }}"
                                {{ ($row->role_user ?? 'pembuat') === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center py-6 text-gray-500">Tidak ada data user</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div id="toast-container" class="fixed top-5 right-5 z-50 space-y-3"></div>

<style>
    .toast {
        min-width: 260px;
        padding: 12px 16px;
        border-radius: 10px;
        color: #fff;
        font-size: 14px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, .15);
        animation: slideIn .3s ease, fadeOut .4s ease 2.6s forwards;
    }

    .toast.success {
        background: #16a34a;
    }

    .toast.error {
        background: #dc2626;
    }

    .toast.info {
        background: #2563eb;
    }

    @keyframes slideIn {
        from {
            transform: translateX(120%);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes fadeOut {
        to {
            opacity: 0;
            transform: translateX(120%);
        }
    }
</style>
<script>
    function showToast(message, type = 'success') {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.innerText = message;
        container.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }


    // ===== UPDATE ROLE =====
    function initRoleSelect() {
        document.querySelectorAll('.role-select').forEach(select => {
            select.onchange = () => {
                fetch('/admin/simrs/user-ticket/update-role', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            id_user: select.dataset.idUser,
                            role_user: select.value
                        })
                    })
                    .then(res => res.json())
                    .then(res => showToast(res.message, 'success'))
                    .catch(() => showToast('Gagal update role', 'error'));
            }
        });
    }

    initRoleSelect();
</script>
