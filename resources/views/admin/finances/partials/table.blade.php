@forelse ($finances as $finance)
<tr class="hover:bg-gray-50 transition">
    <td class="px-4 py-2 border text-center">
        {{ $loop->iteration }}
    </td>

    <td class="px-4 py-2 border font-medium">
        {{ $finance->nama_perencanaan }}
    </td>

    <td class="px-4 py-2 border text-right">
        Rp {{ number_format($finance->modal_awal, 2, ',', '.') }}
    </td>

    <td class="px-4 py-2 border text-right text-green-600">
        Rp {{ number_format($finance->total_pendapatan, 2, ',', '.') }}
    </td>

    <td class="px-4 py-2 border text-right text-red-600">
        Rp {{ number_format($finance->total_pengeluaran, 2, ',', '.') }}
    </td>

    <td class="px-4 py-2 border text-right font-semibold">
        Rp {{ number_format($finance->modal_akhir, 2, ',', '.') }}
    </td>

    <td class="px-4 py-2 border text-center space-x-2">
        {{-- EDIT --}}
        <a href="{{ route('finances.edit', $finance->id) }}"
            class="inline-flex items-center px-3 py-1 text-sm bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition">
            ✏️ Edit
        </a>

        {{-- DELETE --}}
        <button
            onclick="deleteFinance({{ $finance->id }})"
            class="inline-flex items-center px-3 py-1 text-sm bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
            🗑 Hapus
        </button>
    </td>
</tr>
@empty
<tr>
    <td colspan="7" class="px-4 py-6 text-center text-gray-500">
        Data belum ada
    </td>
</tr>
@endforelse
