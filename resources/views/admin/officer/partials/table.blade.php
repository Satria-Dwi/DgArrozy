<div id="table-wrapper">
    <table class="min-w-full text-sm text-left">
        <thead class="bg-gray-100 sticky top-0 z-10">
            <tr class="text-gray-700 uppercase tracking-wider">
                <th class="px-6 py-4">No</th>
                <th class="px-6 py-4">Nama</th>
                <th class="px-6 py-4">Gender</th>
                <th class="px-6 py-4">Jabatan</th>
                <th class="px-6 py-4">Jenjang</th>
                <th class="px-6 py-4">Departemen</th>
                <th class="px-6 py-4 text-center">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach ($pegawai as $p)
                <tr class="hover:bg-indigo-50 transition">
                    <td class="px-6 py-4">
                        {{ $pegawai->firstItem() + $loop->index }}
                    </td>
                    <td class="px-6 py-4 font-semibold">{{ $p->nama }}</td>
                    <td class="px-6 py-4">{{ $p->jk }}</td>
                    <td class="px-6 py-4">{{ $p->jbtn }}</td>
                    <td class="px-6 py-4">{{ $p->jenjang_jabatan ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $p->nama_departemen ?? '-' }}</td>
                    <td class="px-6 py-4 text-center">
                        @if ($p->stts_aktif === 'AKTIF')
                            <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-700">
                                Aktif
                            </span>
                        @else
                            <span class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-700">
                                Tidak Aktif
                            </span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
