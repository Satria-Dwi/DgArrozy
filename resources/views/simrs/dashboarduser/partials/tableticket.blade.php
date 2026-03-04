<div class="overflow-x-auto mt-6">
    <table class="min-w-full bg-white shadow rounded-lg">
        <thead>
            <tr class="bg-indigo-500 text-white text-left">
                <th class="px-4 py-2">#</th>
                <th class="px-4 py-2">Kode Ticket</th>
                <th class="px-4 py-2">Nama User</th>
                <th class="px-4 py-2">Departemen</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Tanggal Dibuat</th>
                <th class="px-4 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tickets as $ticket)
                <tr class="border-b hover:bg-gray-50 text-black">
                    <td class="px-4 py-2">
                        {{ $loop->iteration + ($tickets->currentPage() - 1) * $tickets->perPage() }}
                    </td>
                    <td class="px-4 py-2">{{ $ticket->kode_ticket }}</td>
                    <td class="px-4 py-2">{{ $ticket->user_nama }}</td>
                    <td class="px-4 py-2">{{ $ticket->departemen ?? '-' }}</td>
                    <td class="px-4 py-2">
                        @php
                            // Warna badge status kompatibel PHP 7.x
                            switch ($ticket->status) {
                                case 'open':
                                case 'approval_1':
                                case 'approval_2':
                                    $statusColor = 'bg-yellow-200 text-yellow-800';
                                    break;
                                case 'progress':
                                    $statusColor = 'bg-blue-200 text-blue-800';
                                    break;
                                case 'closed':
                                    $statusColor = 'bg-green-200 text-green-800';
                                    break;
                                case 'approved':
                                    $statusColor = 'bg-purple-200 text-purple-800';
                                    break;
                                default:
                                    $statusColor = 'bg-gray-200 text-gray-800';
                            }
                        @endphp
                        <span class="px-2 py-1 rounded-full text-sm {{ $statusColor }}">
                            {{ ucfirst($ticket->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($ticket->created_at)->format('d M Y H:i') }}</td>
                    <td class="px-4 py-2">
                        <a href="{{ url('ticket/detail/' . $ticket->id) }}" class="text-indigo-600 hover:underline">Detail</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center px-4 py-6 text-gray-500">
                        Tidak ada tiket ditemukan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $tickets->links() }}
    </div>
</div>
