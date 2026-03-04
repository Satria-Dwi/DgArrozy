<div class="overflow-x-auto">

    <table class="w-full text-sm text-left border border-gray-200 text-black bg-white">
        <thead class="bg-gray-100 text-black">
            <tr>
                <th class="px-4 py-2">No</th>
                <th class="px-4 py-2">NIK</th>
                <th class="px-4 py-2">Password</th>
                <th class="px-4 py-2">Nama</th>
                <th class="px-4 py-2">Jabatan</th>
                <th class="px-4 py-2">Departemen</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($user as $row)
                <tr class="border-t hover:bg-gray-50 even:bg-gray-50">
                    <td class="px-4 py-2">
                        {{ $user->firstItem() + $loop->index }}
                    </td>
                    <td class="px-4 py-2">{{ $row->nik }}</td>
                    <td class="px-4 py-2">{{ $row->password }}</td>
                    <td class="px-4 py-2">{{ $row->nama }}</td>
                    <td class="px-4 py-2">{{ $row->jbtn }}</td>
                    <td class="px-4 py-2">{{ $row->nama_departemen }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-gray-500">
                        Data tidak ditemukan
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $user->links() }}
    </div>

</div>