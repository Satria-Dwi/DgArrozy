let currentPageCekBerkasDigital = 1;

function loadCekBerkasDigital(page = 1) {
    currentPageCekBerkasDigital = page;

    const search =
        document.getElementById(
            'searchCekBerkasDigital'
        )?.value || '';

    const tanggal_dari =
        document.getElementById('tanggalDari')?.value || '';

    const tanggal_sampai =
        document.getElementById('tanggalSampai')?.value || '';

    const tbody = document.getElementById(
        "tableCekBerkasDigital"
    );

    const pagination = document.getElementById(
        "paginationCekBerkasDigital"
    );

    const infoCekBerkasDigital = document.getElementById(
        "infoCekBerkasDigital"
    );

    if (!tbody) return;

    tbody.innerHTML = `
        <tr>
            <td colspan="18" class="text-center py-8 text-slate-400">
                <div class="flex flex-col items-center gap-2">
                    <div class="animate-spin rounded-full h-6 w-6 border-2 border-blue-500 border-t-transparen">
                    </div>
                    <span> Memuat Data Berkas Digital... </span>
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

    fetch(`/casemix/cekberkasdigital/data?${params}`)
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
                            Tidak Ada Berkas Digital
                        </td>
                    </tr>    
                `;

                if (pagination)
                    pagination.innerHTML = '';

                if (infoCekBerkasDigital)
                    infoCekBerkasDigital.innerHTML = `
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

                const urlResume =
                    `/casemix/cekberkasdigital/resume-medis/${item.status_lanjut}/${item.no_rawat}`;

                const badgeStatus = (val) => {
                    return val
                        ? `<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                Lengkap
           </span>`
                        : `<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                Tidak Lengkap
           </span>`;
                };

                return `
                    <tr class="${rowClass} hover:bg-blue-50 dark:hover:bg-slate-700 transition-all duration-200">

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-slate-900 dark:text-slate-100">
                                ${item.no_rawat}
                            </div>
                            <div class="text-xs text-slate-500">
                                Closing : ${item.tgl_closing ?? '-'}
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-slate-900 dark:text-slate-100">
                                ${item.nm_pasien}
                            </div>
                            <div class="text-xs text-slate-500">
                                RM : ${item.no_rkm_medis}
                            </div>
                            <div class="text-xs text-slate-500">
                                Dokter : ${item.nm_dokter}
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-slate-900 dark:text-slate-100">
                                ${item.no_sep ?? '-'}
                            </div>
                            <div class="text-xs text-slate-500">
                                Status : ${item.status_lanjut}
                            </div>
                            <div class="text-xs text-slate-500">
                                Poli : ${item.kd_poli}
                            </div>
                        </td>                        

                       <td class="px-6 py-4 text-center">
                            ${badgeStatus(item.ada_resume)}

                            <a href="${item.ada_resume == 1 ? urlResume : '#'}"
                                target="_blank"
                                style="
                                    display:inline-flex;
                                    align-items:center;
                                    justify-content:center;
                                    margin-top:6px;
                                    padding:4px 8px;
                                    border-radius:8px;
                                    font-size:11px;
                                    font-weight:600;
                                    text-decoration:none;
                                    background:${item.ada_resume == 1 ? '#2563eb' : '#e5e7eb'};
                                    color:${item.ada_resume == 1 ? '#fff' : '#6b7280'};
                                    ${item.ada_resume == 1 ? '' : 'pointer-events:none;cursor:not-allowed;'}
                                ">
                                Resume
                            </a>
                        </td>   

                        <td class="px-6 py-4 text-center">
                            ${badgeStatus(item.ada_billing)}
                        </td>

                        <td class="px-6 py-4 text-center">
                            ${badgeStatus(item.ada_cppt)}
                        </td>

                        <td class="px-6 py-4 text-center">
                            ${badgeStatus(item.ada_cppt_dokter)}
                        </td>

                        <td class="px-6 py-4 text-center">
                            ${badgeStatus(item.ada_asmed)}
                        </td>

                        <td class="px-6 py-4 text-center">
                            ${badgeStatus(item.ada_triase)}
                        </td>

                        <td class="px-6 py-4 text-center">
                            ${badgeStatus(item.ada_op)}
                        </td>

                        <td class="px-6 py-4 text-center">
                            ${badgeStatus(item.ada_lab)}
                        </td>

                        <td class="px-6 py-4 text-center">
                            ${badgeStatus(item.ada_rad)}
                        </td>

                    </tr>
                    `;
            }).join('');

            tbody.innerHTML = html;

            renderPaginationCekBerkasDigital(res);

            if (infoCekBerkasDigital) {
                infoCekBerkasDigital.innerHTML = `
            <span class="font-semibold text-blue-600">
                ${res.total}
            </span>
            Berkas digital ditemukan
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
                    <td colspan="18" class="text-center py-8 text-red-500">
                        Gagal Memuat Data Berkas Digital
                    </td>
                </tr>
            `;

            if (pagination) {
                pagination.innerHTML = '';
            }

            if (infoCekBerkasDigital) {
                infoCekBerkasDigital.innerHTML = `
                    <span class="text-red-500">
                        Gagal Memuat data
                    </span>
                `;
            }
        });
}

function statusBadge(val) {
    return val
        ? `<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">✔</span>`
        : `<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">✖</span>`;
}

function renderPaginationCekBerkasDigital(res) {
    const pagination =
        document.getElementById(
            'paginationCekBerkasDigital'
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
            onclick="this.blur(); loadCekBerkasDigital(${current - 1})"
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
            onclick="this.blur(); loadCekBerkasDigital(${current + 1})"
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

function pageButton(
    page,
    current
) {

    const active =
        page === current;

    return `
        <button
            type="button"
            onclick="loadCekBerkasDigital(${page})"
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
        loadCekBerkasDigital();
    }
);

document
    .getElementById('searchCekBerkasDigital')
    ?.addEventListener(
        'input',
        debounce(() => {
            loadCekBerkasDigital(1);
        }, 500)
    );

document
    .getElementById('tanggalDari')
    ?.addEventListener('change', () => {
        loadCekBerkasDigital(1);
    });

document
    .getElementById('tanggalSampai')
    ?.addEventListener('change', () => {
        loadCekBerkasDigital(1);
    });