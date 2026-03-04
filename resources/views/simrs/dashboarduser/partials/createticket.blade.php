<!-- Modal -->
<div x-show="open" x-transition.opacity
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">

    <div @click.away="open = false" x-transition.scale
        class="bg-gray-900 rounded-xl shadow-lg w-full max-w-2xl p-6 overflow-y-auto max-h-[90vh]">

        <!-- Header -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-white">Buat Tiket Baru</h2>
            <button @click="open = false" class="text-gray-400 hover:text-white text-2xl">&times;</button>
        </div>

        <!-- Form -->
        <form action="{{ route('ticket.create') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-400">NIK</label>
                    <input type="text" name="user_nik" value="{{ $user['nik'] }}" readonly
                        class="w-full px-3 py-2 mt-1 rounded-lg bg-gray-800 text-white" />
                </div>
                <div>
                    <label class="text-sm text-gray-400">Nama</label>
                    <input type="text" name="user_nama" value="{{ $user['nama'] }}" readonly
                        class="w-full px-3 py-2 mt-1 rounded-lg bg-gray-800 text-white" />
                </div>
            </div>

            <div>
                <label class="text-sm text-gray-400">Departemen</label>
                <input type="text" name="user_departemen" value="{{ $user['departemen'] ?? '-' }}" readonly
                    class="w-full px-3 py-2 mt-1 rounded-lg bg-gray-800 text-white" />
            </div>

            <div>
                <label class="text-sm text-gray-400">Judul Tiket *</label>
                <input type="text" name="judul" required
                    class="w-full px-3 py-2 mt-1 rounded-lg border border-gray-600 focus:border-indigo-500 text-black" />
            </div>

            <div>
                <label class="text-sm text-gray-400">Deskripsi *</label>
                <textarea name="deskripsi" rows="4" required
                    class="w-full px-3 py-2 mt-1 rounded-lg border border-gray-600 focus:border-indigo-500 text-black"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-400">Kategori *</label>
                    <input type="text" name="kategori" required
                        class="w-full px-3 py-2 mt-1 rounded-lg border border-gray-600 focus:border-indigo-500 text-black" />
                </div>
                <div>
                    <label class="text-sm text-gray-400">Prioritas</label>
                    <select name="prioritas" class="w-full px-3 py-2 mt-1 rounded-lg border border-gray-600 text-black">
                        <option value="rendah">Rendah</option>
                        <option value="sedang" selected>Sedang</option>
                        <option value="tinggi">Tinggi</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button type="button" @click="open = false"
                    class="px-4 py-2 rounded-lg border border-gray-600 hover:bg-gray-700 transition">Batal</button>
                <button type="submit"
                    class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold transition">Kirim
                    Tiket</button>
            </div>
        </form>
    </div>
</div>
