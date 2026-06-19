let currentPageKonsultasiPerawat = 1;
let currentPageKonsultasiSelesai = 1;

function loadKonsultasiPerawat(page = 1) {
    currentPageKonsultasiPerawat = page;

    const search =
        document.getElementById(
            'searchKonsultasiPerawat'
        )?.value || '';

    const tanggal =
        document.getElementById(
            'tanggalKonsultasiPerawat'
        )?.value || '';

    const tbody = document.getElementById(
        "tableKonsultasiPerawat"
    );

    const pagination = document.getElementById(
        "paginationKonsultasiPerawat"
    );

    const infoKonsultasiPerawat = document.getElementById(
        "infoKonsultasiPerawat"
    );

    if (!tbody) return;

    tbody.innerHTML = `
        <tr>
            <td colspan="7" class="text-center py-8 text-slate-400">
                <div class="flex flex-col items-center gap-2">
                    <div class="animate-spin rounded-full h-6 w-6 border-2 border-blue-500 border-t-transparent">
                    </div>
                    <span> Memuat Data Konsultasi...</span>
                </div>
            </td>
        </tr>
    `;
    const params = new URLSearchParams({
        page,
        search,
        tanggal
    });

    
    fetch(`/konsultasiperawat?${params.toString()}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(
                    `HTTP ${response.status}`
                );
            }
            return response.json();
        })

        .then(res => {
            tbody.innerHTML = '';
            const rows = res?.data?.data || [];
            if (rows.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center py-8 text-slate-400">
                            Tidak Ada Konsultasi
                        </td>
                    </tr>
                `;

                if (pagination)
                    pagination.innerHTML = '';

                if (infoKonsultasiPerawat)
                    infoKonsultasiPerawat.innerHTML = `
                    <span class="font-medium">
                        0
                    </span>
                `;

                return;
            }

            const html = rows.map((item, index) => {
                const rowClass =
                    index % 2 === 0
                        ? 'bg-white dark:bg-slate-900'
                        : 'bg-slate-50 dark:bg-slate-800/50';
                return `
                    <tr class="${rowClass} hover:bg-blue-50 dark:hover:bg-slate-700/50 transition-al duration-200">

                        <td class="px-6 py-4 text-center whitespace-nowrap">
                            ${item.tanggalperiksa}
                        </td>
                        <td class="px-6 py-4 text-center whitespace-nowrap">
                            ${item.no_permintaan}
                        </td>
                        <td class="px-6 py-4 text-center whitespace-nowrap">
                            ${item.no_rkm_medis}
                        </td>
                        <td class="px-6 py-4 text-left whitespace-nowrap">
                            ${item.nm_pasien}
                        </td>
                        <td class="px-6 py-4 text-left whitespace-nowrap">
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

            renderPaginationKonsultasiPerawat(
                res.data
            );

            if (infoKonsultasiPerawat) {
                infoKonsultasiPerawat.innerHTML = `
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

            if (infoKonsultasiPerawat)
                infoKonsultasiPerawat.innerHTML = `
                    <span class="text-red-500">
                        Gagal memuat data
                    </span>
                `;
        });
}

function loadKonsultasiPerawatSelesai(page = 1) {

    currentPageKonsultasiSelesai = page;
    const search =
        document.getElementById(
            'searchKonsultasiSelesaiPerawat'
        )?.value || '';

    const tanggal =
        document.getElementById(
            'tanggalKonsultasiSelesaiPerawat'
        )?.value || '';

    const tbody = document.getElementById("tableKonsultasiPerawatSelesai");
    const info = document.getElementById("infoKonsultasiPerawatSelesai");
    const pagination = document.getElementById("paginationKonsultasiPerawatselesai");

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

    fetch(`/konsultasiperawat/history?${params.toString()}`)
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

                const rowClass =
                    index % 2 === 0
                        ? 'bg-white dark:bg-slate-900'
                        : 'bg-slate-50 dark:bg-slate-800/50';

                tbody.innerHTML += `
                            <tr class="${rowClass} 
                                border-b
                                border-slate-100
                                dark:border-slate-800
                                hover:bg-slate-50
                                dark:hover:bg-slate-800/50
                            ">

                                <td class="px-4 py-3 text-center">
                                    ${item.tanggalperiksa}
                                </td>

                                <td class="px-4 py-3 text-center font-medium">
                                    ${item.no_permintaan}
                                </td>

                                <td class="px-4 py-3 text-center">
                                    ${item.no_rkm_medis}
                                </td>

                                <td class="px-4 py-3">
                                    ${item.nm_pasien}
                                </td>

                                <td class="px-4 py-3">
                                    ${item.dokterkonsul}
                                </td>

                                <td class="px-4 py-3 text-center">
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
                                            py-2
                                            px-4
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

            renderPaginationKonsultasiPerawatSelesai(data);

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

function renderPaginationKonsultasiPerawat(res) {
    const pagination =
        document.getElementById(
            'paginationKonsultasiPerawat'
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
            onclick="this.blur(); loadKonsultasiPerawat(${current - 1})"
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
            onclick="this.blur(); loadKonsultasiPerawat(${current + 1})"
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

function renderPaginationKonsultasiPerawatSelesai(res) {
    // console.log('renderPagination dipanggil');
    const pagination =
        document.getElementById(
            'paginationKonsultasiPerawatSelesai'
        );
    // console.log('pagination element:', pagination);
    // console.log('current:', res.current_page);
    // console.log('last:', res.last_page);
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
            onclick="loadKonsultasiPerawatSelesai(${current - 1})"
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

        html += pageButtonKonsultasiPerawatSelesai(
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

        html += pageButtonKonsultasiPerawatSelesai(
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

        html += pageButtonKonsultasiPerawatSelesai(
            last,
            current
        );
    }

    html += `
        <button
            onclick="loadKonsultasiPerawatSelesai(${current + 1})"
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
    // console.log(html);
    // pagination.innerHTML = html;
    // console.log('HTML berhasil dimasukkan');
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
            onclick="loadKonsultasiPerawat(${page})"
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

function pageButtonKonsultasiPerawatSelesai(
    page,
    current
) {

    const active =
        page === current;
    return `
        <button
            type="button"
            onclick="loadKonsultasiPerawatSelesai(${page})"
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

    fetch(`/konsultasiperawat/data/${noPermintaan}`)
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
            document.getElementById('modalPerawat').textContent = d.nm_perawat_pengirim ?? '-';
            document.getElementById('modalSituation').textContent = d.situation ?? '-';
            document.getElementById('modalSituationTimeLine').textContent = d.situation ?? '-';
            document.getElementById('modalBackground').textContent = d.background ?? '-';
            document.getElementById('modalAssessment').textContent = d.assessment ?? '-';
            document.getElementById('modalRecomendation').textContent = d.recomendation ?? '-';
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
            const modal = document.getElementById('modalKonsultasiPerawat');

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

    fetch(`/konsultasiperawat/history/${noPermintaan}`)
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
            document.getElementById('modalHistoryPerawat').textContent = d.nm_perawat_pengirim ?? '-';
            document.getElementById('modalHistorySituation').textContent = d.situation ?? '-';
            document.getElementById('modalHistorySituationTimeLine').textContent = d.situation ?? '-';
            document.getElementById('modalHistoryBackground').textContent = d.background ?? '-';
            document.getElementById('modalHistoryAssessment').textContent = d.assessment ?? '-';
            document.getElementById('modalHistoryRecomendation').textContent = d.recomendation ?? '-';
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
            const modal = document.getElementById('modalHistoryKonsultasiPerawat');

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

    fetch(`/konsultasiperawat/jawabanhistory/${noPermintaan}`)
        .then(res => res.json())
        .then(result => {

            let d = result.data;

            // ======================
            // IDENTITAS
            // ======================
            document.getElementById('modalHistoryPerawatJawabanNoPermintaan').textContent = d.no_permintaan ?? '-';
            document.getElementById('modalHistoryPerawatJawabanPasien').textContent = d.nm_pasien ?? '-';
            document.getElementById('modalHistoryPerawatJawabanNoRM').textContent = d.no_rkm_medis ?? '-';

            // ======================
            // KONSULTASI AWAL
            // ======================
            document.getElementById('modalHistoryPerawatJawabanSituation').textContent = d.situation ?? '-';
            document.getElementById('modalHistoryPerawatJawabanBackground').textContent = d.background ?? '-';
            document.getElementById('modalHistoryPerawatJawabanAssessment').textContent = d.assessment ?? '-';
            document.getElementById('modalHistoryPerawatJawabanRecomendation').textContent = d.recomendation ?? '-';

            // ======================
            // DOKTER Penjawab
            // ======================
            document.getElementById('modalHistoryPerawtatJawabanDokterPenjawab').textContent = d.dokter_penjawab ?? '-';

            // ======================
            // JAWABAN KONSULTASI
            // ======================
            document.getElementById('modalHistoryPerawatJawaban').textContent = d.nm_perawat_penerima ?? '-';
            // document.getElementById('modalHistoryPerawatTTD').textContent = d.nm_perawat_penerima ?? '-';
            document.getElementById('modalHistoryPerawatrespon').textContent = d.respon ?? '-';
            document.getElementById('modalHistoryPerawatinstruksi').textContent = d.instruksi ?? '-';
            document.getElementById('modalHistoryPerawatrencana').textContent = d.rencana ?? '-';
            // document.getElementById('modalHistoryJawabanTindakLanjut').textContent = d.tanggal_jawaban ?? '-';
            if (d.tanggal_jawaban) {

                const tanggal = new Date(
                    d.tanggal_jawaban.replace(' ', 'T')
                );

                document.getElementById('modalHistoryPerawatJawabanTindakLanjut').textContent =
                    tanggal.toLocaleString('id-ID', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    }) + ' WIB';

            } else {

                document.getElementById('modalHistoryPerawatJawabanTindakLanjut').textContent = '-';

            }

            if (d.tanggal_jawaban) {

                const tanggal = new Date(
                    d.tanggal_jawaban.replace(' ', 'T')
                );

                document.getElementById('modalHistoryPerawatJawabanTanggalTTD').textContent =
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
            const modal = document.getElementById('modalKonsultasiPerawatHistoryJawaban');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            generateTTDHistoryJawaban();
        })
        .catch(err => {
            console.error(err);
            alert('Gagal memuat data');
        });
}

function generateTTD() {

    const dokter =
        document.getElementById("modalPerawat").textContent;

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
        document.getElementById("modalHistoryPerawat").textContent;

    const tanggal =
        document.getElementById("modalHistoryTanggalTTD").textContent;

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
        document.getElementById("modalHistoryPerawtatJawabanDokterPenjawab").textContent;

    const tanggal =
        document.getElementById("modalHistoryPerawatJawabanTanggalTTD").textContent;

    const nomor =
        document.getElementById("modalHistoryPerawatJawabanNoPermintaan").textContent;

    const qrText = `
                Dokter : ${dokter}
                Tanggal : ${tanggal}
                No Permintaan : ${nomor}
                Status : Ditandatangani Secara Elektronik
                `;

    document.getElementById("qrTtdHistoryPerawatJawaban").innerHTML = "";

    new QRCode(document.getElementById("qrTtdHistoryPerawatJawaban"), {
        text: qrText,
        width: 120,
        height: 120
    });
    // console.log("QR dibuat");
}

function tutupModalKonsultasi() {
    const modal = document.getElementById('modalKonsultasiPerawat');

    modal.classList.remove('flex');
    modal.classList.add('hidden');
}

function tutupModalKonsultasiHistory() {
    const modal = document.getElementById('modalHistoryKonsultasiPerawat');

    modal.classList.remove('flex');
    modal.classList.add('hidden');
}

function tutupModalJawabanKonsultasiHistory() {
    const modal = document.getElementById('modalKonsultasiPerawatHistoryJawaban');

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

document.addEventListener(
    'DOMContentLoaded',
    function () {
        loadKonsultasiPerawat();
        loadKonsultasiPerawatSelesai();
    }
);

document
    .getElementById('searchKonsultasiPerawat')
    ?.addEventListener(
        'input',
        debounce(() => {
            loadKonsultasiPerawat(1);
        }, 500)
    );

document
    .getElementById('tanggalKonsultasiPerawat')
    ?.addEventListener(
        'change',
        () => {
            loadKonsultasiPerawat(1);
        }
    );

document
    .getElementById('searchKonsultasiSelesaiPerawat')
    ?.addEventListener(
        'input',
        debounce(() => {
            loadKonsultasiPerawatSelesai(1);
        }, 500)
    );

document
    .getElementById('tanggalKonsultasiSelesaiPerawat')
    ?.addEventListener(
        'change',
        () => {
            loadKonsultasiPerawatSelesai(1);
        }
    );