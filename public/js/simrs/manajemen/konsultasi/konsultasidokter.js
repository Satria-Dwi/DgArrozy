let currentPageKonsultasi = 1;
let currentPageKonsultasiSelesai = 1;

function loadKonsultasi(page = 1) {

    currentPageKonsultasi = page;
    const search =
        document.getElementById(
            'searchKonsultasi'
        )?.value || '';

    const tanggal =
        document.getElementById(
            'tanggalKonsultasi'
        )?.value || '';

    const tbody =
        document.getElementById(
            "tableKonsultasi"
        );

    const pagination =
        document.getElementById(
            "paginationKonsultasi"
        );

    const infoKonsultasi =
        document.getElementById(
            "infoKonsultasi"
        );

    if (!tbody) return;

    tbody.innerHTML = `
        <tr>
            <td colspan="7"
                class="text-center py-8 text-slate-400">
                <div class="flex flex-col items-center gap-2">
                    <div class="animate-spin rounded-full h-6 w-6 border-2 border-blue-500 border-t-transparent"></div>
                    <span>Memuat data konsultasi...</span>
                </div>
            </td>
        </tr>
    `;

    const params = new URLSearchParams({
        page,
        search,
        tanggal
    });

    fetch(
        `/konsultasi?${params}`
    )
        .then(response => {

            if (!response.ok) {
                throw new Error(
                    `HTTP ${response.status}`
                );
            }

            return response.json();
        })

        .then(res => {

            // console.log('Response:', res);

            tbody.innerHTML = '';

            const rows = res?.data?.data || [];

            if (rows.length === 0) {

                tbody.innerHTML = `
                    <tr>
                        <td colspan="7"
                            class="text-center py-8 text-slate-400">
                            Tidak ada konsultasi
                        </td>
                    </tr>
                `;

                if (pagination)
                    pagination.innerHTML = '';

                if (infoKonsultasi)
                    infoKonsultasi.innerHTML = `
                        <span class="font-medium">
                            0
                        </span>
                        konsultasi ditemukan
                    `;

                return;
            }

            const html = rows.map((item, index) => {


                // zebra tetap jalan
                const baseClass =
                    index % 2 === 0
                        ? 'bg-white'
                        : 'bg-slate-50';

                return `
        <tr class="
            ${baseClass}
            hover:bg-slate-100
            dark:hover:bg-slate-800
            transition-all duration-200
        ">

            <td class="px-6 py-4 text-center whitespace-nowrap">
                ${item.tanggalperiksa}
            </td>

            <td class="px-6 py-4 text-center whitespace-nowrap font-medium">
                ${item.no_permintaan}
            </td>

            <td class="px-6 py-4 text-center whitespace-nowrap">
                ${item.no_rkm_medis}
            </td>

            <td class="px-6 py-4">
                ${item.nm_pasien}
            </td>

            <td class="px-6 py-4">
                ${item.dokterkonsul}
            </td>

            <td class="px-6 py-4 text-center">
                <button
                    onclick="bukaKonsultasi('${item.no_permintaan}')"
                    class="
                        inline-flex
                        items-center
                        gap-2
                        bg-blue-600
                        hover:bg-blue-700
                        text-white
                        text-sm
                        font-semibold
                        py-2
                        px-4
                        rounded-xl
                        shadow-md
                        hover:shadow-lg
                        transition-all
                    ">
                    👁️ Lihat
                </button>
            </td>

        </tr>
    `;
            }).join('');

            tbody.innerHTML = html;

            renderPaginationKonsultasi(
                res.data
            );

            if (infoKonsultasi) {

                infoKonsultasi.innerHTML = `
                    <span class="font-semibold text-blue-600">
                        ${res.data.total}
                    </span>
                    konsultasi ditemukan
                    <span class="mx-2 text-slate-300">•</span>
                    Halaman
                    <span class="font-semibold">
                        ${res.data.current_page}
                    </span>
                    dari
                    <span class="font-semibold">
                        ${res.data.last_page}
                    </span>
                `;
            }

        })

        .catch(err => {

            console.error(
                'ERROR:',
                err
            );

            tbody.innerHTML = `
                <tr>
                    <td colspan="7"
                        class="text-center py-8 text-red-500">
                        Gagal memuat data konsultasi
                    </td>
                </tr>
            `;

            if (pagination)
                pagination.innerHTML = '';

            if (infoKonsultasi)
                infoKonsultasi.innerHTML = `
                    <span class="text-red-500">
                        Gagal memuat data
                    </span>
                `;
        });

}

function loadKonsultasiSelesai(page = 1) {

    currentPageKonsultasiSelesai = page;

    const search =
        document.getElementById('searchKonsultasiSelesai')?.value || '';

    const tanggal =
        document.getElementById('tanggalKonsultasiSelesai')?.value || '';

    const tbody =
        document.getElementById('tableKonsultasiSelesai');

    const info =
        document.getElementById('infoKonsultasiSelesai');

    const pagination =
        document.getElementById('paginationKonsultasiSelesai');

    if (!tbody) return;

    tbody.innerHTML = `
        <tr>
            <td colspan="7"
                class="text-center py-8 text-slate-400">
                <div class="flex flex-col items-center gap-2">
                    <div class="animate-spin rounded-full h-6 w-6 border-2 border-blue-500 border-t-transparent"></div>
                    <span>Memuat data konsultasi...</span>
                </div>
            </td>
        </tr>
    `;
    const params = new URLSearchParams({
        page,
        search,
        tanggal
    });

    fetch(`/konsultasi/history?${params}`)
        .then(res => res.json())
        .then(result => {

            if (result.error) {
                throw new Error("Gagal memuat data");
            }

            const data = result.data;

            tbody.innerHTML = "";

            if (data.data.length === 0) {

                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center py-6 text-slate-500">
                            Tidak ada data konsultasi
                        </td>
                    </tr>
                `;

                return;
            }

            data.data.forEach((item, index) => {

                // zebra + subtle effect (sama seperti map)
                // zebra tetap jalan
                const baseClass =
                    index % 2 === 0
                        ? 'bg-white'
                        : 'bg-slate-50';
                        
                tbody.innerHTML += `
                <tr class="
                    ${baseClass}
                    hover:bg-slate-100
                    dark:hover:bg-slate-800
                    transition-all duration-200
                ">

            <td class="px-6 py-4 text-center">
                ${item.tanggalperiksa}
            </td>

            <td class="px-6 py-4 text-center font-medium">
                ${item.no_permintaan}
            </td>

            <td class="px-6 py-4 text-center">
                ${item.no_rkm_medis}
            </td>

            <td class="px-6 py-4">
                ${item.nm_pasien}
            </td>

            <td class="px-6 py-4">
                ${item.dokterkonsul}
            </td>

            <td class="px-6 py-4 text-center">
                <button
                    onclick="bukaKonsultasiSelesai('${item.no_permintaan}')"
                    class="
                        inline-flex
                        items-center
                        gap-2
                        bg-emerald-600
                        hover:bg-emerald-700
                        text-white
                        text-sm
                        font-semibold
                        py-2 px-4
                        rounded-xl
                        shadow-md
                        transition-all
                    ">
                    👁️ Lihat
                </button>
            </td>

        </tr>
    `;
            });

            if (info) {
                info.innerHTML = `
                        <span class="font-semibold text-blue-600">
                            ${data.total}
                        </span>
                        konsultasi ditemukan
                        <span class="mx-2 text-slate-300">•</span>
                        Halaman
                        <span class="font-semibold">
                            ${data.current_page}
                        </span>
                        dari
                        <span class="font-semibold">
                            ${data.last_page}
                        </span>
                    `;
            };

            renderPaginationKonsultasiSelesai(data);

        })
        .catch(error => {

            console.error(error);

            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-6 text-red-500">
                        Gagal memuat data
                    </td>
                </tr>
            `;
        });
}

function renderPaginationKonsultasi(res) {

    const pagination =
        document.getElementById(
            'paginationKonsultasi'
        );

    if (!pagination) return;

    const current =
        res.current_page;

    const last =
        res.last_page;

    if (last <= 1) {

        pagination.innerHTML = '';
        return;
    }

    let html = `
        <div
            style="
                display:flex;
                align-items:center;
                gap:8px;
                background:white;
                padding:10px;
                border-radius:20px;
                box-shadow:
                    0 4px 20px rgba(0,0,0,.08);
            "
        >
    `;

    // PREVIOUS
    html += `
        <button
            type="button"
            onclick="this.blur(); loadKonsultasi(${current - 1})"
            ${current === 1 ? 'disabled' : ''}
            style="
                width:44px;
                height:44px;
                border:none;
                border-radius:14px;
                background:#f1f5f9;
                cursor:pointer;
                font-size:18px;
            "
        >
            ‹
        </button>
    `;

    let start =
        Math.max(1, current - 2);

    let end =
        Math.min(last, current + 2);

    if (start > 1) {

        html += pageButton(
            1,
            current
        );

        if (start > 2) {

            html += `
                <span style="
                    padding:0 8px;
                    color:#94a3b8;
                ">
                    ...
                </span>
            `;
        }
    }

    for (
        let i = start;
        i <= end;
        i++
    ) {

        html += pageButton(
            i,
            current
        );
    }

    if (end < last) {

        if (end < last - 1) {

            html += `
                <span style="
                    padding:0 8px;
                    color:#94a3b8;
                ">
                    ...
                </span>
            `;
        }

        html += pageButton(
            last,
            current
        );
    }

    // NEXT
    html += `
        <button
            type="button"
            onclick="this.blur(); loadKonsultasi(${current + 1})"
            ${current === last ? 'disabled' : ''}
            style="
                width:44px;
                height:44px;
                border:none;
                border-radius:14px;
                background:#f1f5f9;
                cursor:pointer;
                font-size:18px;
            "
        >
            ›
        </button>
    `;

    html += `</div>`;

    pagination.innerHTML =
        html;
}

function renderPaginationKonsultasiSelesai(res) {

    const pagination =
        document.getElementById(
            'paginationKonsultasiSelesai'
        );

    if (!pagination) return;

    const current =
        res.current_page;

    const last =
        res.last_page;

    if (last <= 1) {

        pagination.innerHTML = '';
        return;
    }

    let html = `
        <div
            style="
                display:flex;
                align-items:center;
                gap:8px;
                background:white;
                padding:10px;
                border-radius:20px;
                box-shadow:
                    0 4px 20px rgba(0,0,0,.08);
            "
        >
    `;

    // Prev
    html += `
        <button
            onclick="loadKonsultasiSelesai(${current - 1})"
            ${current === 1 ? 'disabled' : ''}
            style="
                width:44px;
                height:44px;
                border:none;
                border-radius:14px;
                background:#f1f5f9;
                cursor:pointer;
                font-size:18px;
            "
        >
            ‹
        </button>
    `;

    let start =
        Math.max(1, current - 2);

    let end =
        Math.min(last, current + 2);

    if (start > 1) {

        html += pageButtonKonsultasiSelesai(
            1,
            current
        );

        if (start > 2) {

            html += `
                <span style="
                    padding:0 8px;
                    color:#94a3b8;
                ">
                    ...
                </span>
            `;
        }
    }

    for (
        let i = start;
        i <= end;
        i++
    ) {

        html += pageButtonKonsultasiSelesai(
            i,
            current
        );
    }

    if (end < last) {

        if (end < last - 1) {

            html += `
                <span style="
                    padding:0 8px;
                    color:#94a3b8;
                ">
                    ...
                </span>
            `;
        }

        html += pageButtonKonsultasiSelesai(
            last,
            current
        );
    }

    html += `
        <button
            onclick="loadKonsultasiSelesai(${current + 1})"
            ${current === last ? 'disabled' : ''}
            style="
                width:44px;
                height:44px;
                border:none;
                border-radius:14px;
                background:#f1f5f9;
                cursor:pointer;
                font-size:18px;
            "
        >
            ›
        </button>
    `;

    html += `</div>`;

    pagination.innerHTML =
        html;
}

function pageButton(
    page,
    current
) {

    const active =
        page === current;

    return `
        <button
            type="button"
            onclick="loadKonsultasi(${page})"
            style="
                width:44px;
                height:44px;
                border:none;
                border-radius:14px;
                cursor:pointer;
                font-weight:600;
                transition:.2s;

                ${active
            ? `
                        background:
                            linear-gradient(
                                135deg,
                                #2563eb,
                                #4f46e5
                            );
                        color:white;
                        box-shadow:
                            0 8px 20px
                            rgba(
                                37,
                                99,
                                235,
                                .35
                            );
                        transform:
                            scale(1.1);
                    `
            : `
                        background:#f8fafc;
                        color:#334155;
                    `
        }
            "
        >
            ${page}
        </button>
    `;
}

function pageButtonKonsultasiSelesai(
    page,
    current
) {

    const active =
        page === current;

    return `
        <button
            type="button"
            onclick="loadKonsultasiSelesai(${page})"
            style="
                width:44px;
                height:44px;
                border:none;
                border-radius:14px;
                cursor:pointer;
                font-weight:600;
                transition:.2s;

                ${active
            ? `
                        background:
                            linear-gradient(
                                135deg,
                                #2563eb,
                                #4f46e5
                            );
                        color:white;
                        box-shadow:
                            0 8px 20px
                            rgba(
                                37,
                                99,
                                235,
                                .35
                            );
                        transform:
                            scale(1.1);
                    `
            : `
                        background:#f8fafc;
                        color:#334155;
                    `
        }
            "
        >
            ${page}
        </button>
    `;
}

function bukaKonsultasi(noPermintaan) {

    fetch(`/konsultasi/data/${noPermintaan}`)
        .then(response => response.json())
        .then(result => {

            let d = result.data;

            // Detail Konsultasi
            document.getElementById('modalNoPermintaan').textContent = d.no_permintaan ?? '-';
            if (d.tanggalkonsultasi) {

                const tanggal = new Date(
                    d.tanggalkonsultasi.replace(' ', 'T')
                );

                document.getElementById('modalTanggal').textContent =
                    tanggal.toLocaleString('id-ID', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    }) + ' WIB';

            } else {

                document.getElementById('modalTanggal').textContent = '-';

            }
            document.getElementById('modalNoRM').textContent = d.no_rkm_medis ?? '-';
            document.getElementById('modalPasien').textContent = d.nm_pasien ?? '-';
            document.getElementById('modalDokter').textContent = d.nm_dokter_pengirim ?? '-';
            document.getElementById('modalJenis').textContent = d.jenis_permintaan ?? '-';
            document.getElementById('modalDiagnosa').textContent = d.diagnosa_kerja ?? '-';
            document.getElementById('modalUraian').textContent = d.uraian_konsultasi ?? '-';
            if (d.tanggalkonsultasi) {

                const tanggal = new Date(
                    d.tanggalkonsultasi.replace(' ', 'T')
                );

                document.getElementById('modalTanggalTTD').textContent =
                    'Probolinggo, ' +
                    tanggal.toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    });
            }

            // Timeline
            document.getElementById('modalKeteranganKonsultasi').textContent =
                d.uraian_konsultasi ?? '-';

            document.getElementById('modalDokterTujuan').textContent =
                d.nm_dokter_tujuan ?? '-';

            // Format tanggal Indonesia
            let tanggalFormat = '-';

            if (d.tanggalkonsultasi) {

                const tanggal = new Date(
                    d.tanggalkonsultasi.replace(' ', 'T')
                );

                tanggalFormat = tanggal.toLocaleString('id-ID', {
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                }) + ' WIB';
            }

            document.getElementById('modalWaktuKirim').textContent = tanggalFormat;

            // Tampilkan modal
            const modal = document.getElementById('modalKonsultasi');

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            generateTTD();

        })
        .catch(error => {
            console.error(error);
            alert('Gagal Memuat Data');
        });
}

function bukaKonsultasiSelesai(noPermintaan) {

    fetch(`/konsultasi/history/${noPermintaan}`)
        .then(response => response.json())
        .then(result => {

            let d = result.data;

            // Detail Konsultasi
            document.getElementById('modalHistoryNoPermintaan').textContent = d.no_permintaan ?? '-';
            if (d.tanggalkonsultasi) {

                const tanggal = new Date(
                    d.tanggalkonsultasi.replace(' ', 'T')
                );

                document.getElementById('modalHistoryTanggal').textContent =
                    tanggal.toLocaleString('id-ID', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    }) + ' WIB';

            } else {

                document.getElementById('modalHistoryTanggal').textContent = '-';

            }
            document.getElementById('modalHistoryNoRM').textContent = d.no_rkm_medis ?? '-';
            document.getElementById('modalHistoryPasien').textContent = d.nm_pasien ?? '-';
            document.getElementById('modalHistoryDokter').textContent = d.nm_dokter_pengirim ?? '-';
            document.getElementById('modalHistoryJenis').textContent = d.jenis_permintaan ?? '-';
            document.getElementById('modalHistoryDiagnosa').textContent = d.diagnosa_kerja ?? '-';
            document.getElementById('modalHistoryUraian').textContent = d.uraian_konsultasi ?? '-';
            if (d.tanggalkonsultasi) {

                const tanggal = new Date(
                    d.tanggalkonsultasi.replace(' ', 'T')
                );

                document.getElementById('modalHistoryTanggalTTD').textContent =
                    'Probolinggo, ' +
                    tanggal.toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    });
            }

            // Timeline
            document.getElementById('modalHistoryKeteranganKonsultasi').textContent =
                d.uraian_konsultasi ?? '-';

            document.getElementById('modalHistoryDokterTujuan').textContent =
                d.nm_dokter_tujuan ?? '-';

            // Format tanggal Indonesia
            let tanggalFormat = '-';

            if (d.tanggalkonsultasi) {

                const tanggal = new Date(
                    d.tanggalkonsultasi.replace(' ', 'T')
                );

                tanggalFormat = tanggal.toLocaleString('id-ID', {
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                }) + ' WIB';
            }

            document.getElementById('modalHistoryWaktuKirim').textContent = tanggalFormat;
            // SET tombol STEP 3 supaya bisa klik lagi
            // document.querySelector('.btn-lihat-ulang').dataset.permintaan = d.no_permintaan;

            document.getElementById('modalHistoryActionButton').innerHTML = `
                <button
                    onclick="bukaKonsultasiJawaban('${d.no_permintaan}')"
                    class="
                        inline-flex
                        items-center
                        gap-2
                        bg-emerald-600
                        hover:bg-emerald-700
                        text-white
                        text-sm
                        font-semibold
                        py-2
                        px-4
                        rounded-xl
                        shadow-md
                        transition-all
                    ">
                    👁️ Lihat Jawaban
                </button>
            `;

            // Tampilkan modal
            const modal = document.getElementById('modalKonsultasiHistory');

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            generateTTDHistory();

        })
        .catch(error => {
            console.error(error);
            alert('Gagal Memuat Data');
        });
}

function bukaKonsultasiJawaban(noPermintaan) {

    fetch(`/konsultasi/jawabanhistory/${noPermintaan}`)
        .then(res => res.json())
        .then(result => {

            let d = result.data;

            // ======================
            // IDENTITAS
            // ======================
            document.getElementById('modalHistoryJawabanNoPermintaan').textContent = d.no_permintaan ?? '-';
            document.getElementById('modalHistoryJawabanPasien').textContent = d.nm_pasien ?? '-';
            document.getElementById('modalHistoryJawabanNoRM').textContent = d.no_rkm_medis ?? '-';
            document.getElementById('modalHistoryJawabanJenis').textContent = d.jenis_permintaan ?? '-';

            // ======================
            // KONSULTASI AWAL
            // ======================
            document.getElementById('modalHistoryJawabanDiagnosa').textContent = d.diagnosa_konsultasi ?? '-';
            document.getElementById('modalHistoryJawabanUraian').textContent = d.uraian_konsultasi ?? '-';
            document.getElementById('modalHistoryJawabanDokterPengirim').textContent = d.dokter_pengirim ?? '-';

            // ======================
            // DOKTER TUJUAN
            // ======================
            document.getElementById('modalHistoryJawabanDokterTujuan').textContent = d.dokter_tujuan ?? '-';

            // ======================
            // JAWABAN KONSULTASI
            // ======================
            document.getElementById('modalHistoryJawabanJawaban').textContent = d.uraian_jawaban ?? '-';
            document.getElementById('modalHistoryJawabanSaran').textContent = d.diagnosa_jawaban ?? '-';
            // document.getElementById('modalHistoryJawabanTindakLanjut').textContent = d.tanggal_jawaban ?? '-';
            if (d.tanggal_jawaban) {

                const tanggal = new Date(
                    d.tanggal_jawaban.replace(' ', 'T')
                );

                document.getElementById('modalHistoryJawabanTindakLanjut').textContent =
                    tanggal.toLocaleString('id-ID', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    }) + ' WIB';

            } else {

                document.getElementById('modalHistoryJawabanTindakLanjut').textContent = '-';

            }

            if (d.tanggal_jawaban) {

                const tanggal = new Date(
                    d.tanggal_jawaban.replace(' ', 'T')
                );

                document.getElementById('modalHistoryJawabanTanggalTTD').textContent =
                    'Probolinggo, ' +
                    tanggal.toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    });
            }

            // ======================
            // SHOW MODAL
            // ======================
            const modal = document.getElementById('modalKonsultasiHistoryJawaban');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            generateTTDHistoryJawaban();
        })
        .catch(err => {
            console.error(err);
            alert('Gagal memuat data');
        });
}

function tutupModalKonsultasiHistory() {
    const modal = document.getElementById('modalKonsultasiHistoryJawaban');

    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function resetModalHistoryJawaban() {
    const ids = [
        'modalHistoryJawabanNoPermintaan',
        'modalHistoryJawabanTanggal',
        'modalHistoryJawabanPasien',
        'modalHistoryJawabanNoRM',
        'modalHistoryJawabanJenis',
        'modalHistoryJawabanDokterTujuan',
        'modalHistoryJawabanDiagnosa',
        'modalHistoryJawabanUraian',
        'modalHistoryJawabanJawaban',
        'modalHistoryJawabanSaran',
        'modalHistoryJawabanTindakLanjut',
        'modalHistoryJawabanTanggalTTD',
        'modalHistoryJawabanDokter'
    ];

    ids.forEach(id => {
        const el = document.getElementById(id);
        if (el) el.textContent = '';
    });

    const qr = document.getElementById('qrTtdHistoryJawaban');
    if (qr) qr.innerHTML = '';
}

function generateTTD() {

    const dokter =
        document.getElementById("modalDokter").textContent;

    const tanggal =
        document.getElementById("modalTanggal").textContent;

    const nomor =
        document.getElementById("modalNoPermintaan").textContent;

    const qrText = `
                Dokter : ${dokter}
                Tanggal : ${tanggal}
                No Permintaan : ${nomor}
                Status : Ditandatangani Secara Elektronik
                `;

    document.getElementById("qrTtd").innerHTML = "";

    new QRCode(document.getElementById("qrTtd"), {
        text: qrText,
        width: 120,
        height: 120
    });
    // console.log("QR dibuat");
}

function generateTTDHistory() {

    const dokter =
        document.getElementById("modalHistoryDokter").textContent;

    const tanggal =
        document.getElementById("modalHistoryTanggal").textContent;

    const nomor =
        document.getElementById("modalHistoryNoPermintaan").textContent;

    const qrText = `
                Dokter : ${dokter}
                Tanggal : ${tanggal}
                No Permintaan : ${nomor}
                Status : Ditandatangani Secara Elektronik
                `;

    document.getElementById("qrTtdHistory").innerHTML = "";

    new QRCode(document.getElementById("qrTtdHistory"), {
        text: qrText,
        width: 120,
        height: 120
    });
    // console.log("QR dibuat");
}

function generateTTDHistoryJawaban() {

    const dokter =
        document.getElementById("modalHistoryJawabanDokterTujuan").textContent;

    const tanggal =
        document.getElementById("modalHistoryJawabanTindakLanjut").textContent;

    const nomor =
        document.getElementById("modalHistoryJawabanNoPermintaan").textContent;

    const qrText = `
                Dokter : ${dokter}
                Tanggal : ${tanggal}
                No Permintaan : ${nomor}
                Status : Ditandatangani Secara Elektronik
                `;

    document.getElementById("qrTtdHistoryJawaban").innerHTML = "";

    new QRCode(document.getElementById("qrTtdHistoryJawaban"), {
        text: qrText,
        width: 120,
        height: 120
    });
    console.log("QR dibuat");
}

function tutupModalKonsultasi() {
    const modal = document.getElementById('modalKonsultasi');

    modal.classList.remove('flex');
    modal.classList.add('hidden');
}

function tutupModalKonsultasiHistory() {
    const modal = document.getElementById('modalKonsultasiHistory');

    modal.classList.remove('flex');
    modal.classList.add('hidden');
}

function tutupModalJawabanKonsultasiHistory() {
    const modal = document.getElementById('modalKonsultasiHistoryJawaban');

    modal.classList.remove('flex');
    modal.classList.add('hidden');
}

function debounce(
    func,
    delay = 500
) {

    let timeout;

    return function (...args) {

        clearTimeout(timeout);

        timeout = setTimeout(
            () => func.apply(this, args),
            delay
        );
    };
}

document.addEventListener('click', function (e) {

    const btn = e.target.closest('.btn-lihat-ulang');

    if (!btn) return;

    const noPermintaan = btn.dataset.permintaan;

    // console.log('CLICK OK:', noPermintaan); // <-- cek dulu

    if (!noPermintaan) {
        alert('no_permintaan kosong!');
        return;
    }

    bukaKonsultasiJawaban(noPermintaan);
});

document.addEventListener('DOMContentLoaded', function () {

    loadKonsultasi();
    loadKonsultasiSelesai();

    if (window.showNotifKonsultasi) {
        cekNotifKonsultasi();
    }

});

document
    .getElementById('searchKonsultasi')
    ?.addEventListener(
        'input',
        debounce(() => {
            loadKonsultasi(1);
        }, 500)
    );

document
    .getElementById('tanggalKonsultasi')
    ?.addEventListener(
        'change',
        () => {
            loadKonsultasi(1);
        }
    );

document
    .getElementById('searchKonsultasiSelesai')
    ?.addEventListener(
        'input',
        debounce(() => {
            loadKonsultasiSelesai(1);
        }, 500)
    );

document
    .getElementById('tanggalKonsultasiSelesai')
    ?.addEventListener(
        'change',
        () => {
            loadKonsultasiSelesai(1);
        }
    );
