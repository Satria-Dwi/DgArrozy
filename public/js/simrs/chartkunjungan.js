let chartKunjungan = null;

document.addEventListener("DOMContentLoaded", () => {
    const el = document.querySelector("#chartkunjungan");
    if (!el) return;

    const url = el.dataset.url;
    const interval = Number(el.dataset.interval) || 60000;

    loadChartPasien(el, url);
    setInterval(() => loadChartPasien(el, url), interval);
});

function formatTanggalDenganHari(tanggalArr) {
    const hari = ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"];
    const tahun = new Date().getFullYear();

    return tanggalArr.map((tgl) => {
        const dateObj = new Date(`${tgl} ${tahun}`);
        const namaHari = hari[dateObj.getDay()];
        return `${namaHari}, ${tgl}`;
    });
}

function loadChartPasien(el, url) {
    if (!el || !url) return;

    fetch(url)
        .then((res) => res.json())
        .then((res) => {
            if (!res?.tanggal?.length) return;

            const categoriesHari = formatTanggalDenganHari(res.tanggal);

            const options = {
                title: {
                    text: "Statistik Pengunjung",
                    align: "center",
                    margin: 8,
                    offsetY: 6,
                    style: {
                        fontSize: "14px",
                        fontWeight: 600,
                        color: "#334155",
                    },
                },
                series: [
                    { name: "Pasien BPJS", type: "column", data: res.bpjs },
                    { name: "Pasien Umum", type: "column", data: res.umum },
                    { name: "Total Pasien", type: "line", data: res.total },
                ],
                chart: {
                    height: 320,
                    background: "transparent",
                    toolbar: { show: false },
                    offsetX: 0,
                    offsetY: 0,
                },
                stroke: {
                    width: [0, 0, 3],
                    curve: "smooth",
                },
                plotOptions: {
                    bar: {
                        borderRadius: 6,
                        columnWidth: "45%",
                    },
                },
                dataLabels: { enabled: false },
                xaxis: {
                    categories: categoriesHari,
                    labels: {
                        style: {
                            colors: "#475569",
                            fontSize: "12px",
                        },
                    },
                    title: {
                        text: "Hari, Tanggal",
                        style: { color: "#475569" },
                    },
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: "#475569",
                            fontSize: "12px",
                        },
                    },
                    title: {
                        text: "Jumlah Pasien",
                        style: { color: "#475569" },
                    },
                },
                legend: {
                    position: "top",
                    horizontalAlign: "center",
                    offsetY: 4,
                    labels: {
                        colors: "#475569",
                    },
                },
                grid: {
                    borderColor: "#e2e8f0",
                    strokeDashArray: 4,
                },
                tooltip: {
                    theme: "light",
                },
            };

            if (chartKunjungan) {
                chartKunjungan.updateOptions(options);
            } else {
                chartKunjungan = new ApexCharts(el, options);
                chartKunjungan.render();
            }
        })
        .catch((err) => console.error("Chart Kunjungan:", err));
}
