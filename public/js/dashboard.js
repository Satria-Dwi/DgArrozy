let chartHarian,
    chartPoliHariIni,
    chartTahunPasien,
    chartjk,
    penjaminChart,
    chartStatusKamar,
    chartPenyakitBulanIni,
    kamarChart,
    bangsalChart;

// ================= HELPER =================
const el = (id) => document.getElementById(id);
const setText = (id, value) => {
    if (el(id)) el(id).innerText = value ?? 0;
};

// ================= LOAD DASHBOARD DATA =================
async function loadDashboard() {
    const res = await fetch("/dashboard-data");
    const json = await res.json();
    const summary = json.summary ?? {};

    // ===== SUMMARY =====
    setText("ranap", summary.rawat_inap);
    setText("igd", summary.igd);
    setText("poli", summary.poli);
    setText("operasi", summary.operasi);
    setText("lahir", summary.lahir);
    setText("pasien", summary.pasien);

    // ===== UPDATE CHART =====
    // if (chartHarian && json.chart_harian) {
    //     chartHarian.data.labels = json.chart_harian.map((d) => d.tgl);
    //     chartHarian.data.datasets[0].data = json.chart_harian.map(
    //         (d) => d.total,
    //     );
    //     chartHarian.update();
    // }

    if (chartPoliHariIni && json.chart_poli_hari_ini) {
        chartPoliHariIni.data.labels = json.chart_poli_hari_ini.map(
            (d) => d.nm_poli,
        );
        chartPoliHariIni.data.datasets[0].data = json.chart_poli_hari_ini.map(
            (d) => d.total,
        );
        chartPoliHariIni.update();
    }

    if (chartTahunPasien && json.chart_tahun) {
        chartTahunPasien.data.labels = json.chart_tahun.labels;
        chartTahunPasien.data.datasets[0].data = json.chart_tahun.data;
        chartTahunPasien.update();
    }

    if (chartjk && json.jenis_kelamin) {
        chartjk.updateOptions({
            labels: json.jenis_kelamin.labels,
        });

        chartjk.updateSeries(json.jenis_kelamin.data);
    }

    if (penjaminChart && json.penjamin) {
        penjaminChart.updateOptions({
            labels: json.penjamin.labels,
        });

        penjaminChart.updateSeries(json.penjamin.data);
    }

    if (kamarChart && json.status_kamar) {
        const labels = json.status_kamar.labels;
        const data = json.status_kamar.data;

        let total = data.reduce((a, b) => a + b, 0);

        let kosongIndex = labels.findIndex((l) =>
            l.toLowerCase().includes("kosong"),
        );

        let kosong = kosongIndex !== -1 ? data[kosongIndex] : 0;

        let persen = total > 0 ? ((kosong / total) * 100).toFixed(1) : 0;

        kamarChart.updateSeries([parseFloat(persen)]);
    }

    if (bangsalChart && json.tempat_tidur_per_bangsal) {
        const data = json.tempat_tidur_per_bangsal;

        const labels = data.labels ?? [];
        const terisi = data.data_terisi ?? [];
        const kosong = data.data_kosong ?? [];

        if (!terisi.length) return;

        const totalBed = terisi.map((v, i) => v + (kosong[i] ?? 0));

        const persen = terisi.map((v, i) => {
            const total = totalBed[i] ?? 0;
            return total > 0 ? parseFloat(((v / total) * 100).toFixed(1)) : 0;
        });

        // warna dinamis berdasarkan BOR
        const warnaDinamis = persen.map((p) => {
            if (p >= 85) return "#ef4444";
            if (p >= 70) return "#f59e0b";
            return "#22c55e";
        });

        bangsalChart.updateOptions({
            labels: labels,
            colors: warnaDinamis,
        });

        bangsalChart.updateSeries(persen);
    }

    if (chartPenyakitBulanIni && json.penyakit_bulan_ini) {
        chartPenyakitBulanIni.data.labels = json.penyakit_bulan_ini.labels;
        chartPenyakitBulanIni.data.datasets[0].data =
            json.penyakit_bulan_ini.data;
        chartPenyakitBulanIni.update();
    }

    updateTableTempatTidur(json.tempat_tidur_per_bangsal);
}

// ================= TABLE TEMPAT TIDUR =================
function updateTableTempatTidur(data) {
    const tbody = el("tableTempatTidur");
    if (!tbody || !data) return;

    tbody.innerHTML = "";

    for (let i = 0; i < data.labels.length; i++) {
        const totalBed = data.data_terisi[i] + data.data_kosong[i];
        const bor = data.bor[i];

        let borClass = bor < 50 ? "low" : bor < 80 ? "medium" : "high";

        const tr = document.createElement("tr");
        tr.classList.add("text-black"); // 🔥 semua isi baris jadi hitam

        tr.innerHTML = `
            <td class="px-3 py-2">${data.labels[i]}</td>
            <td class="text-center px-3 py-2">${totalBed}</td>
            <td class="text-center px-3 py-2">${data.data_terisi[i]}</td>
            <td class="text-center px-3 py-2">${data.data_kosong[i]}</td>
            <td class="px-3 py-2">
                <div class="bor-bar">
                    <div class="${borClass}" style="width:${bor}%">${bor}%</div>
                </div>
            </td>
        `;
        tbody.appendChild(tr);
    }
}

// ================= INIT SLIDER =================
function initSliders() {
    document.querySelectorAll(".slider-wrapper").forEach((wrapper) => {
        const slider = wrapper.querySelector(".slider");
        const slides = slider?.querySelectorAll(".slide") ?? [];
        if (slides.length <= 1) return;

        let index = 0;
        setInterval(() => {
            index = (index + 1) % slides.length;
            slider.style.transform = `translateX(-${index * 100}%)`;
        }, 8000);
    });
}

// ================= REALTIME CLOCK =================
function realtimeClock() {
    const now = new Date();
    if (el("rt-hari"))
        el("rt-hari").textContent = now
            .toLocaleDateString("id-ID", { weekday: "long" })
            .toUpperCase();

    if (el("rt-tanggal"))
        el("rt-tanggal").textContent = now.toLocaleDateString("id-ID", {
            day: "2-digit",
            month: "long",
            year: "numeric",
        });

    if (el("rt-jam"))
        el("rt-jam").textContent =
            now.toLocaleTimeString("id-ID", { hour12: false }) + " WIB";
}

// ================= INIT ALL =================
document.addEventListener("DOMContentLoaded", async () => {
    await loadDashboard();
    const res = await fetch("/dashboard-data");
    const json = await res.json();

    const isTablet = window.matchMedia("(max-width:1024px)").matches;
    const isMobile = window.matchMedia("(max-width:768px)").matches;

    // ===== CHART JK =====
    if (document.querySelector("#chartJK")) {
        const options = {
            series: json.jenis_kelamin?.data ?? [],
            chart: {
                height: 320,
                type: "donut",
            },

            // ✅ TITLE
            title: {
                text: "Statistik Jenis Kelamin Pasien",
                align: "center",
                style: {
                    fontSize: "16px",
                    fontWeight: 600,
                },
            },

            // optional subtitle
            subtitle: {
                text: "Data Keseluruhan Pasien",
                align: "center",
                style: {
                    fontSize: "12px",
                },
            },

            labels: json.jenis_kelamin?.labels ?? [],

            // warna biru & pink
            colors: ["#3b82f6", "#ec4899"],

            plotOptions: {
                pie: {
                    donut: {
                        size: "65%",
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: "Total",
                                formatter: function (w) {
                                    return w.globals.seriesTotals.reduce(
                                        (a, b) => a + b,
                                        0,
                                    );
                                },
                            },
                        },
                    },
                },
            },

            legend: {
                position: "bottom",
            },

            dataLabels: {
                enabled: true,
            },

            responsive: [
                {
                    breakpoint: 480,
                    options: {
                        chart: {
                            height: 280,
                        },
                        legend: {
                            position: "bottom",
                        },
                    },
                },
            ],
        };

        chartjk = new ApexCharts(document.querySelector("#chartJK"), options);

        chartjk.render();
    }

    // ===== CHART STATUS KAMAR =====
    if (document.querySelector("#chartKetersediaanKamar")) {
        const labels = json.status_kamar?.labels ?? [];
        const data = json.status_kamar?.data ?? [];

        let kosong = 0;
        let terisi = 0;

        labels.forEach((label, index) => {
            const lower = label.trim().toLowerCase();

            if (lower.includes("kosong")) {
                kosong += Number(data[index]);
            }

            // karena di DB statusnya "ISI"
            if (lower === "isi") {
                terisi += Number(data[index]);
            }
        });

        const total = kosong + terisi;

        const persen = total > 0 ? ((kosong / total) * 100).toFixed(1) : 0;

        const optionsKamar = {
            series: [parseFloat(persen)],

            chart: {
                height: 340,
                type: "radialBar",
            },

            plotOptions: {
                radialBar: {
                    startAngle: -135,
                    endAngle: 225,
                    hollow: { size: "70%" },
                    track: { strokeWidth: "67%" },

                    dataLabels: {
                        name: {
                            offsetY: -10,
                            fontSize: "14px",
                        },
                        value: {
                            formatter: function (val) {
                                return val + "%";
                            },
                            fontSize: "32px",
                            fontWeight: 600,
                        },
                    },
                },
            },

            fill: {
                type: "gradient",
                gradient: {
                    shade: "dark",
                    type: "horizontal",
                    gradientToColors: ["#22c55e"],
                    stops: [0, 100],
                },
            },

            stroke: {
                lineCap: "round",
            },

            labels: ["Ketersediaan Kamar"],

            title: {
                text: "Kamar Inap Tersedia",
                align: "center",
            },
        };

        const kamarChart = new ApexCharts(
            document.querySelector("#chartKetersediaanKamar"),
            optionsKamar,
        );

        kamarChart.render();
    }

    // ===== CHART Penjamin =====
    if (document.querySelector("#chartPenjamin")) {
        const optionsPenjamin = {
            series: json.penjamin?.data ?? [],

            chart: {
                type: "donut",
                width: "100%", // penting
                height: 380, // fix tinggi supaya stabil
                dropShadow: {
                    enabled: true,
                    color: "#111",
                    top: -1,
                    left: 3,
                    blur: 3,
                    opacity: 0.4,
                },
            },

            stroke: {
                width: 0,
            },

            labels: json.penjamin?.labels ?? [],

            plotOptions: {
                pie: {
                    donut: {
                        size: "65%",
                        labels: {
                            show: true,

                            name: {
                                show: true,
                                fontSize: "14px",
                            },

                            value: {
                                show: true,
                                fontSize: "22px",
                                fontWeight: 600,
                                formatter: function (val) {
                                    return val; // tampilkan jumlah yang di-hover
                                },
                            },

                            total: {
                                show: true,
                                showAlways: false, // penting
                                label: "Total",
                                formatter: function (w) {
                                    return w.globals.seriesTotals.reduce(
                                        (a, b) => a + b,
                                        0,
                                    );
                                },
                            },
                        },
                    },
                },
            },

            dataLabels: {
                dropShadow: {
                    blur: 3,
                    opacity: 1,
                },
            },

            fill: {
                type: "pattern",
                opacity: 1,
                pattern: {
                    enabled: true,
                    style: [
                        "verticalLines",
                        "squares",
                        "horizontalLines",
                        "circles",
                        "slantedLines",
                    ],
                },
            },

            states: {
                hover: {
                    filter: "none",
                },
            },

            theme: {
                palette: "palette2",
            },

            title: {
                text: "Asuransi Pasien",
                align: "center",
            },

            legend: {
                position: "bottom",
            },

            // ❌ Hapus pengaturan width di responsive
            responsive: [
                {
                    breakpoint: 480,
                    options: {
                        legend: {
                            position: "bottom",
                        },
                    },
                },
            ],
        };

        penjaminChart = new ApexCharts(
            document.querySelector("#chartPenjamin"),
            optionsPenjamin,
        );

        penjaminChart.render();
    }
    // ===== CHART BangsalBed =====
    if (document.querySelector("#chartBangsalBed")) {
        const bangsalData = json.tempat_tidur_per_bangsal ?? {};

        const labels = bangsalData.labels ?? [];
        const terisi = bangsalData.data_terisi ?? [];
        const kosong = bangsalData.data_kosong ?? [];
        const persentase = (bangsalData.bor ?? []).map((v) => parseFloat(v));

        if (!labels.length) return;

        const totalBed = terisi.map((val, i) => val + (kosong[i] ?? 0));

        // 🎨 Warna unik per bangsal
        const warnaVariatif = [
            "#3b82f6", // biru
            "#22c55e", // hijau
            "#ef4444", // merah
            "#f59e0b", // orange
            "#8b5cf6", // ungu
            "#14b8a6", // teal
            "#ec4899", // pink
            "#6366f1", // indigo
            "#84cc16", // lime
            "#f97316", // orange tua
        ];

        const colors = labels.map(
            (_, i) => warnaVariatif[i % warnaVariatif.length],
        );

        if (window.bangsalChart) {
            window.bangsalChart.destroy();
        }

        const optionsBangsal = {
            series: persentase,

            chart: {
                height: 600, // 🔥 diperbesar
                type: "radialBar",
            },

            plotOptions: {
                radialBar: {
                    startAngle: 0,
                    endAngle: 270,

                    hollow: {
                        size: "10%", // tengah lebih besar → ring lebih lega
                    },

                    track: {
                        background: "#f1f5f9",
                        strokeWidth: "60%", // ring lebih tipis
                        margin: 6, // 🔥 jarak antar ring diperbesar
                    },

                    dataLabels: {
                        name: { show: false },
                        value: { show: false },

                        total: {
                            show: true,
                            label: "AVG BOR",
                            fontSize: "18px",
                            fontWeight: 600,
                            formatter: function () {
                                const avg =
                                    persentase.reduce((a, b) => a + b, 0) /
                                    persentase.length;
                                return avg.toFixed(1) + "%";
                            },
                        },
                    },

                    barLabels: {
                        enabled: true,
                        useSeriesColors: true,
                        fontSize: "15px",
                        offsetX: -10, // 🔥 geser sedikit supaya tidak nabrak
                        formatter: function (seriesName, opts) {
                            const i = opts.seriesIndex;
                            return (
                                seriesName +
                                " (" +
                                totalBed[i] +
                                " bed)" +
                                " : " +
                                terisi[i] +
                                " / " +
                                totalBed[i]
                            );
                        },
                    },
                },
            },

            stroke: {
                lineCap: "round", // 🔥 ujung bulat biar elegan
            },

            colors: colors,

            labels: labels,

            legend: {
                show: false,
            },

            responsive: [
                {
                    breakpoint: 480,
                    options: {
                        chart: { height: 380 },
                    },
                },
            ],
        };

        window.bangsalChart = new ApexCharts(
            document.querySelector("#chartBangsalBed"),
            optionsBangsal,
        );

        window.bangsalChart.render();
    }

    realtimeClock();
    setInterval(realtimeClock, 1000);

    initSliders();

    setInterval(loadDashboard, 15000);
});
