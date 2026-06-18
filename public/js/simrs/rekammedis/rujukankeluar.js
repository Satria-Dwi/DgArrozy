let currentPageRujukanKeluar = 1;

function loadRujukanKeluar(page = 1) {
    currentPageRujukanKeluar = page;

    const search =
        document.getElementById(
            'searchRujukanKeluar'
        )?.value || '';

    const tanggal_dari =
        document.getElementById('tanggalDari')?.value || '';

    const tanggal_sampai =
        document.getElementById('tanggalSampai')?.value || '';

    const tbody = document.getElementById(
        "tableRujukanKeluar"
    );

    const pagination = document.getElementById(
        "paginationRujukanKeluar"
    );

    const infoRujukanKeluar = document.getElementById(
        "infoRujukanKeluar"
    );

    if (!tbody) return;

    tbody.innerHTML = `
        <tr>
            <td colspan="7" class="text-center py-8 text-slate-400">
                <div class="flex flex-col items-center gap-2">
                    <div class="animate-spin rounded-full h-6 w-6 border-2 border-blue-500 border-t-transparen">
                    </div>
                    <span> Memuat Data Rujukan Keluar... </span>
                </div>
            </td>
        </tr>
    `;

    const params = new URLSearchParams({
        page,
        search,
        tanggal_dari,
        tanggal_sampai
    });

    fetch(`/rm/rujukankeluar/get?${params}`)
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

            const rows = res?.data || [];

            if (rows.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center py-8 text-slate-400">
                            Tidak Ada Rujukan Keluar
                        </td>
                    </tr>    
                `;

                if (pagination)
                    pagination.innerHTML = '';

                if (infoRujukanKeluar)
                    infoRujukanKeluar.innerHTML = `
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
                    <tr class="${rowClass} hover:bg-blue-50 dark:hover:bg-slate-700 transition-al duration-200">
                        <td class="px-6 py-4 text-center whitespace-nowrap">
                        ${item.no_rujuk}
                        </td>
                        <td class="px-6 py-4 text-center whitespace-nowrap">
                        ${item.no_rawat}
                        </td>
                        <td class="px-6 py-4 text-center whitespace-nowrap">
                        ${item.no_rkm_medis}
                        </td>
                        <td class="px-6 py-4 text-left whitespace-nowrap">
                        ${item.nm_pasien}
                        </td>
                        <td class="px-6 py-4 text-left whitespace-nowrap">
                        ${item.asal}                        
                        </td>
                        <td class="px-6 py-4 text-left whitespace-nowrap">
                        ${item.rujuk_ke}                        
                        </td>
                        <td class="px-6 py-4 text-left whitespace-nowrap">
                        ${item.tgl_rujuk}                        
                        </td>
                        <td class="px-6 py-4 text-left whitespace-nowrap">
                        ${item.keterangan_diagnosa}                        
                        </td>
                        <td class="px-6 py-4 text-center whitespace-nowrap">
                        ${item.kd_dokter}                        
                        </td>
                        <td class="px-6 py-4 text-left whitespace-nowrap">
                        ${item.nm_dokter}                        
                        </td>
                        <td class="px-6 py-4 text-center whitespace-nowrap">
                        ${item.kat_rujuk}                        
                        </td>
                        <td class="px-6 py-4 text-center whitespace-nowrap">
                        ${item.ambulance}                        
                        </td>
                        <td class="px-6 py-4 text-center whitespace-nowrap">
                        ${item.keterangan}                        
                        </td>
                        <td class="px-6 py-4 text-center whitespace-nowrap">
                        ${item.jam}                        
                        </td>
                        <td class="px-6 py-4 text-left whitespace-nowrap">
                        ${item.alamat}                        
                        </td>
                        <td class="px-6 py-4 text-left whitespace-nowrap">
                        ${item.kelurahanpj}                        
                        </td>
                        <td class="px-6 py-4 text-left whitespace-nowrap">
                        ${item.kecamatanpj}                        
                        </td>
                        <td class="px-6 py-4 text-left whitespace-nowrap">
                        ${item.kabupatenpj}                        
                        </td>
                    </tr>
                `;
            }).join('');

            tbody.innerHTML = html;

            renderPaginationRujukanKeluar(res);

            if (infoRujukanKeluar) {
                infoRujukanKeluar.innerHTML = `
            <span class="font-semibold text-blue-600">
                ${res.total}
            </span>
            Rujukan Keluar ditemukan
            <span class="mx-2 text-slate-300">•</span>
            Halaman
            <span class="font-semibold">
                ${res.current_page}
            </span>
            dari
            <span class="font-semibold">
                ${res.last_page}
            </span>
        `;
            }
        })

        .catch(err => {

            console.error(
                'ERROR',
                err
            );

            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-8 text-red-500">
                        Gagal Memuat Data Rujukan Keluar
                    </td>
                </tr>
            `;

            if (pagination) {
                pagination.innerHTML = '';
            }

            if (infoRujukanKeluar) {
                infoRujukanKeluar.innerHTML = `
                    <span class="text-red-500">
                        Gagal Memuat data
                    </span>
                `;
            }
        });
}

function renderPaginationRujukanKeluar(res) {
    const pagination =
        document.getElementById(
            'paginationRujukanKeluar'
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
            onclick="this.blur(); loadRujukanKeluar(${current - 1})"
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
            onclick="this.blur(); loadRujukanKeluar(${current + 1})"
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

function openDateRange() {
    const dari = document.getElementById('tanggalDari');
    const sampai = document.getElementById('tanggalSampai');

    // buka salah satu (browser biasanya hanya support focus pertama)
    if (dari) {
        dari.showPicker ? dari.showPicker() : dari.focus();
    }
}

function exportExcel() {
    const search =
        document.getElementById('searchRujukanKeluar')?.value || '';

    const tanggal_dari =
        document.getElementById('tanggalDari')?.value || '';

    const tanggal_sampai =
        document.getElementById('tanggalSampai')?.value || '';

    const params = new URLSearchParams({
        search,
        tanggal_dari,
        tanggal_sampai
    });

    const url = `/rm/rujukankeluar/export?${params}`;

    window.open(url, '_blank');
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
            onclick="loadRujukanKeluar(${page})"
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
        loadRujukanKeluar();
    }
);

document
    .getElementById('searchRujukanKeluar')
    ?.addEventListener(
        'input',
        debounce(() => {
            loadRujukanKeluar(1);
        }, 500)
    );

document
    .getElementById('tanggalDari')
    ?.addEventListener('change', () => {
        loadRujukanKeluar(1);
    });

document
    .getElementById('tanggalSampai')
    ?.addEventListener('change', () => {
        loadRujukanKeluar(1);
    });