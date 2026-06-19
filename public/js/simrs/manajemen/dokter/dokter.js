function loadDashboardManajemen() {
    loadLaporanDokter();
}

function loadLaporanDokter() {

    fetch("/dashboard/laporan-dokter-realtime")
        .then(response => {
            if (!response.ok) {
                throw new Error("Gagal mengambil data");
            }
            return response.json();
        })
        .then(data => {

            let tbody = document.querySelector("#tableDokter tbody");
            if (!tbody) return;

            tbody.innerHTML = "";

            data.forEach((row, index) => {

                let stripeClass = (index % 2 === 0)
                    ? "bg-white dark:bg-slate-900"
                    : "bg-slate-100 dark:bg-slate-800";

                let tr = `
                    <tr class="${stripeClass} border-b border-slate-300 dark:border-slate-800
                        hover:bg-blue-100 dark:hover:bg-slate-700
                        transition duration-200 text-black dark:text-white">

                        <td class="text-center">${index + 1}</td>
                        <td>${row.nm_dokter}</td>
                        <td class="text-center font-bold">${row.total_pasien}</td>
                        <td class="text-center">${row.total_rawat_jalan}</td>
                        <td class="text-center">${row.total_rawat_inap}</td>
                    </tr>
                `;

                tbody.innerHTML += tr;
            });

        })
        .catch(error => {
            console.error("Error loadLaporanDokter:", error);
        });
}