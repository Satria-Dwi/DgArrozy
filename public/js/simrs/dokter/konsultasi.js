let currentPageKonsultasi = 1;

function loadKonsultasi(page = 1) {
    currentPageKonsultasi = page;

    const tbody =
        document.getElementById(
            "tableKonsultasi"
        );

    const pagination =
        document.getElementById(
            "paginationKonsultasi"
        );

    if (!tbody) return;

    tbody.innerHTML = `
    <tr>
        <td colspan="6"
        class="
        text-center
        py-4
        text-slate-400
        ">
        Loading...
        </td>
    </tr>`;

    fetch(
        `/dokter/konsultasi?page=${page}`
    )

        .then(
            res => res.json()
        )

        .then(res => {

            tbody.innerHTML = '';

            const rows =
                res.data.data;

            if (
                !rows ||
                rows.length === 0
            ) {

                tbody.innerHTML = `
            <tr>

            <td
            colspan="6"
            class="
            text-center
            py-4
            text-slate-400
            ">

            Tidak ada
            konsultasi

            </td>

            </tr>`;

                pagination.innerHTML = '';

                return;
            }

            rows.forEach(
                (item, index) => {

                    let stripe =
                        index % 2 === 0
                            ?
                            'bg-white dark:bg-slate-900'
                            :
                            'bg-slate-100 dark:bg-slate-800';

                    tbody.innerHTML += `

        <tr
        class="
        ${stripe}
        border-b
        border-slate-300
        dark:border-slate-800
        ">

        <td
        class="text-center">

        ${item.tanggalperiksa}

        </td>

        <td
        class="text-center">

        ${item.no_permintaan}

        </td>

        <td
        class="text-center">

        ${item.no_rkm_medis}

        </td>

        <td>

        ${item.nm_pasien}

        </td>

        <td>

        ${item.dokterkonsul}

        </td>

        <td
        class="text-center">

        <button

        onclick="
        bukaKonsultasi(
        '${item.no_permintaan}'
        )
        "

        class="
        px-3
        py-1
        rounded-lg
        bg-amber-500
        hover:bg-amber-600
        text-white
        ">

        Lihat

        </button>

        </td>

        </tr>
        `;

                });

            renderPaginationKonsultasi(
                res.data
            );

        })

        .catch(err => {

            console.error(err);

            tbody.innerHTML = `

        <tr>

        <td
        colspan="6"
        class="
        text-center
        text-red-500
        ">

        Gagal memuat data

        </td>

        </tr>

        `;

        });

}

function renderPaginationKonsultasi(
    res
) {

    const pagination =
        document.getElementById(
            'paginationKonsultasi'
        );

    if (!pagination)
        return;

    pagination.innerHTML = '';

    if (
        res.last_page <= 1
    )
        return;

    for (
        let i = 1;
        i <= res.last_page;
        i++
    ) {

        pagination.innerHTML += `

<button

onclick="
loadKonsultasi(
${i}
)
"

class="
px-3
py-1
rounded
border

${i === res.current_page
                ?
                'bg-blue-600 text-white'
                :
                'bg-white dark:bg-slate-800'
            }

">

${i}

</button>

`;

    }

}

function bukaKonsultasi(
    noPermintaan
) {

    window.location =
        `/dokter/konsultasi/${noPermintaan}`;

}