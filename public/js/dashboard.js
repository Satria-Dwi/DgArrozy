let chartHarian,
    chartPoliHariIni,
    chartTahunPasien,
    chartjk,
    penjaminChart,
    chartStatusKamar,
    chartPenyakitBulanIni,
    tableTempatTidur;
let currentSlide = 0;

async function loadDashboard() {
    const res = await fetch("/dashboard-data");
    const json = await res.json();

    const summary = json.summary;
    // ===== SUMMARY =====
    document.getElementById("ranap").innerText = summary.rawat_inap;
    document.getElementById("igd").innerText = summary.igd;
    document.getElementById("poli").innerText = summary.poli;
    document.getElementById("operasi").innerText = summary.operasi;
    document.getElementById("lahir").innerText = summary.lahir;
    document.getElementById("pasien").innerText = summary.pasien;

    // ===== UPDATE CHART =====
    if (chartHarian) {
        chartHarian.data.labels = json.chart_harian.map((d) => d.tgl);
        chartHarian.data.datasets[0].data = json.chart_harian.map(
            (d) => d.total,
        );
        chartHarian.update();
    }

    if (chartPoliHariIni) {
        chartPoliHariIni.data.labels = json.chart_poli_hari_ini.map(
            (d) => d.nm_poli,
        );
        chartPoliHariIni.data.datasets[0].data = json.chart_poli_hari_ini.map(
            (d) => d.total,
        );
        chartPoliHariIni.update();
    }

    // ===== UPDATE CHART TAHUN PASIEN =====
    if (chartTahun && json.chart_tahun) {
        chartTahun.data.labels = json.chart_tahun.labels;
        chartTahun.data.datasets[0].data = json.chart_tahun.data;
        chartTahun.update();
    }

    // ===== UPDATE CHART JENIS KELAMIN =====
    if (chartjk) {
        chartjk.data.labels = json.jenis_kelamin.labels;
        chartjk.data.datasets[0].data = json.jenis_kelamin.data;
        chartjk.update();
    }

    if (penjaminChart) {
        penjaminChart.data.labels = json.penjamin.labels;
        penjaminChart.data.datasets[0].data = json.penjamin.data;
        penjaminChart.update();
    }

    if (chartStatusKamar) {
        chartStatusKamar.data.labels = json.status_kamar.labels;
        chartStatusKamar.data.datasets[0].data = json.status_kamar.data;
        chartStatusKamar.update();
    }

    if (chartPenyakitBulanIni) {
        chartPenyakitBulanIni.data.labels = json.penyakit_bulan_ini.labels;
        chartPenyakitBulanIni.data.datasets[0].data =
            json.penyakit_bulan_ini.data;
        chartPenyakitBulanIni.update();
    }

    if (tableTempatTidur) {
        tableTempatTidur.data.labels = json.tempat_tidur_per_bangsal.labels;
        tableTempatTidur.data.datasets[0].data =
            json.tempat_tidur_per_bangsal.data;
        tableTempatTidur.update();
    }
}

function initSliders() {
    document.querySelectorAll(".slider-wrapper").forEach((wrapper) => {
        const slider = wrapper.querySelector(".slider");
        const slides = slider.querySelectorAll(".slide");
        let index = 0;

        if (slides.length <= 1) return; // 🔥 stop kalau cuma 1 slide

        setInterval(() => {
            index = (index + 1) % slides.length;
            slider.style.transform = `translateX(-${index * 100}%)`;
        }, 8000);
    });
}

const isTablet = window.matchMedia("(max-width: 1024px)").matches;
const isMobile = window.matchMedia("(max-width: 768px)").matches;
const responsiveScales = {
    ticks: {
        font: {
            size: isMobile ? 10 : isTablet ? 11 : 12,
        },
    },
};

const responsiveLegend = {
    display: isMobile || isTablet,
    position: "bottom",
    labels: {
        boxWidth: 12,
        font: {
            size: isMobile ? 10 : 11,
        },
    },
};

document.addEventListener("DOMContentLoaded", async () => {
    const res = await fetch("/dashboard-data");
    const json = await res.json();

    const isTablet = window.matchMedia("(max-width: 1024px)").matches;
    const isMobile = window.matchMedia("(max-width: 768px)").matches;

    const responsiveScales = {
        ticks: {
            font: {
                size: isMobile ? 10 : isTablet ? 11 : 12,
            },
        },
    };

    // ===== CHART HARIAN =====
    chartHarian = new Chart(document.getElementById("chartHarian"), {
        type: "bar",
        data: {
            labels: json.chart_harian.map((d) => d.tgl),
            datasets: [
                {
                    label: "Jumlah Pasien",
                    data: json.chart_harian.map((d) => d.total),
                    backgroundColor: "#4facfe",
                    borderRadius: 8,
                    maxBarThickness: isMobile ? 18 : 32, // 📱 lebih ramping
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // ⬅ WAJIB untuk wrapper height
            scales: {
                x: {
                    ...responsiveScales,
                    ticks: {
                        autoSkip: true,
                        maxTicksLimit: isMobile ? 6 : 10,
                    },
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                        font: {
                            size: isMobile ? 10 : 12,
                        },
                    },
                },
            },
            plugins: {
                legend: {
                    display: !isMobile, // 📱 mobile: hide legend
                },
                tooltip: {
                    callbacks: {
                        label: (ctx) => `${ctx.raw} pasien`,
                    },
                },
            },
        },
    });

    // ===== CHART POLI HARI INI =====
    chartPoliHariIni = new Chart(document.getElementById("chartPolihari"), {
        type: "bar",
        data: {
            labels: json.chart_poli_hari_ini.map((d) => d.nm_poli),
            datasets: [
                {
                    label: "Pasien Hari Ini",
                    data: json.chart_poli_hari_ini.map((d) => d.total),
                    backgroundColor: "#fb923c",
                    borderRadius: 8,
                    maxBarThickness: isMobile ? 18 : 32,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // ⬅️ WAJIB
            scales: {
                x: responsiveScales,
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                        font: {
                            size: isMobile ? 10 : 12,
                        },
                    },
                },
            },
            plugins: {
                legend: {
                    display: !isMobile,
                },
            },
        },
    });

    // ===== CHART TAHUN (PIE / DOUGHNUT) =====
    const canvasTahun = document.getElementById("chartTahunPasien");
    if (canvasTahun && json.chart_tahun) {
        chartTahun = new Chart(canvasTahun, {
            type: "doughnut",
            data: {
                labels: json.chart_tahun.labels,
                datasets: [
                    {
                        data: json.chart_tahun.data,
                        backgroundColor: [
                            "#4e79a7",
                            "#59a14f",
                            "#f28e2b",
                            "#e15759",
                            "#76b7b2",
                            "#edc949",
                        ],
                        borderWidth: 2,
                        borderColor: "#ffffff",
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // 🔥 WAJIB (biar ikut chart-wrapper)
                cutout: "62%",
                animation: {
                    duration: 900,
                    easing: "easeOutQuart",
                },
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => `${ctx.label}: ${ctx.raw} pasien`,
                        },
                    },
                    datalabels: {
                        color: "#ffffff",
                        font: {
                            weight: "bold",
                            size: isMobile ? 9 : 11, // 📱 responsive
                        },
                        anchor: "center",
                        align: "center",
                        formatter: (value, ctx) => {
                            if (value === 0) return "";

                            const tahun = ctx.chart.data.labels[ctx.dataIndex];
                            return `Tahun ${tahun}\n${value} pasien`;
                        },
                    },
                },
            },
            plugins: [ChartDataLabels],
        });
    }

    // ===== CHART JENIS KELAMIN =====
    const canvasJK = document.getElementById("chartJK");
    if (canvasJK) {
        chartjk = new Chart(canvasJK, {
            type: "doughnut",
            data: {
                labels: json.jenis_kelamin.labels,
                datasets: [
                    {
                        data: json.jenis_kelamin.data,
                        backgroundColor: [
                            "#3b82f6", // Laki-laki
                            "#ec4899", // Perempuan
                        ],
                        borderWidth: 2,
                        borderColor: "#ffffff",
                    },
                ],
            },
            options: {
                responsive: true,
                cutout: "62%", // ✅ sama
                animation: {
                    duration: 900, // ✅ sama
                    easing: "easeOutQuart", // ✅ sama
                },
                plugins: {
                    legend: {
                        display: false, // ✅ sama
                    },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => `${ctx.label}: ${ctx.raw} pasien`,
                        },
                    },
                    datalabels: {
                        color: "#fff", // ✅ sama
                        font: {
                            weight: "bold",
                            size: 12,
                        },
                        formatter: (value, ctx) =>
                            `${
                                ctx.chart.data.labels[ctx.dataIndex]
                            }\n${value} pasien`,
                    },
                },
            },
            plugins: [ChartDataLabels], // ✅ sama
        });
    }

    const canvas = document.getElementById("penjaminChart");
    if (canvas && json.penjamin) {
        const penjamin = json.penjamin;
        if (penjamin.labels.length === 0) return;

        if (penjaminChart) {
            penjaminChart.destroy();
        }

        penjaminChart = new Chart(canvas, {
            type: "bar",
            data: {
                labels: penjamin.labels,
                datasets: [
                    {
                        label: "Jumlah Pasien",
                        data: penjamin.data,
                        backgroundColor: "#9C27B0", // beda warna saja
                        borderRadius: 8,
                        maxBarThickness: 80,
                    },
                ],
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                    },
                },
            },
        });
    }

    // ===== CHART STATUS KAMAR =====
    const canvasStatusKamar = document.getElementById("chartStatusKamar");
    if (canvasStatusKamar && json.status_kamar) {
        chartStatusKamar = new Chart(canvasStatusKamar, {
            type: "doughnut",
            data: {
                labels: json.status_kamar.labels, // ["Kosong", "Isi", "Booking"]
                datasets: [
                    {
                        data: json.status_kamar.data,
                        backgroundColor: [
                            "#22c55e", // Kosong
                            "#ef4444", // Isi
                            "#f59e0b", // Booking
                        ],
                        borderWidth: 2,
                        borderColor: "#ffffff",
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: "62%",
                animation: {
                    duration: 900,
                    easing: "easeOutQuart",
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => `${ctx.label}: ${ctx.raw} kamar`,
                        },
                    },
                    datalabels: {
                        color: "#ffffff",
                        font: {
                            weight: "bold",
                            size: isMobile ? 10 : 12,
                        },
                        anchor: "center",
                        align: "center",
                        formatter: (value, ctx) => {
                            const label = ctx.chart.data.labels[ctx.dataIndex];
                            return `${label}\n${value}`;
                        },
                    },
                },
            },
            plugins: [ChartDataLabels],
        });
    }

    // ===== CHART PENYAKIT BULAN INI (CRYPTO STYLE) =====
    const canvasPenyakit = document.getElementById("chartPenyakitBulanIni");

    if (canvasPenyakit && json.penyakit_bulan_ini) {
        const ctx = canvasPenyakit.getContext("2d");

        // Gradient fill area
        const gradientFill = ctx.createLinearGradient(
            0,
            0,
            0,
            canvasPenyakit.height,
        );
        gradientFill.addColorStop(0, "rgba(99,102,241,0.35)");
        gradientFill.addColorStop(1, "rgba(99,102,241,0.03)");

        chartPenyakitBulanIni = new Chart(ctx, {
            type: "line",
            data: {
                labels: json.penyakit_bulan_ini.labels,
                datasets: [
                    {
                        data: json.penyakit_bulan_ini.data,
                        borderColor: "#6366f1", // indigo crypto
                        borderWidth: 2.5,
                        tension: 0.45,
                        fill: true,
                        backgroundColor: gradientFill,

                        pointRadius: 0,
                        pointHoverRadius: 6,
                        pointBackgroundColor: "#6366f1",
                        pointBorderColor: "#fff",
                        pointBorderWidth: 2,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: "index",
                    intersect: false,
                },
                animation: {
                    duration: 1200,
                    easing: "easeOutCubic",
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: "rgba(17,24,39,0.95)",
                        titleColor: "#e5e7eb",
                        bodyColor: "#fff",
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: (ctx) => ` ${ctx.raw} pasien`,
                        },
                    },
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                        },
                        ticks: {
                            color: "#9ca3af",
                            font: { size: 11 },
                        },
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: "rgba(255,255,255,0.05)",
                        },
                        ticks: {
                            color: "#9ca3af",
                            precision: 0,
                        },
                    },
                },
            },
        });
    }

    // ===== REALTIME CLOCK =====
    function realtimeClock() {
        const now = new Date();

        const hari = now
            .toLocaleDateString("id-ID", { weekday: "long" })
            .toUpperCase();

        const tanggal = now.toLocaleDateString("id-ID", {
            day: "2-digit",
            month: "long",
            year: "numeric",
        });

        const jam =
            now.toLocaleTimeString("id-ID", {
                hour12: false,
            }) + " WIB";

        const elHari = document.getElementById("rt-hari");
        const elTanggal = document.getElementById("rt-tanggal");
        const elJam = document.getElementById("rt-jam");

        if (elHari) elHari.textContent = hari;
        if (elTanggal) elTanggal.textContent = tanggal;
        if (elJam) elJam.textContent = jam;
    }

    // ===== UPDATE TEMPAT TIDUR =====
    const tbody = document.getElementById("tableTempatTidur");
    tbody.innerHTML = "";
    const data = json.tempat_tidur_per_bangsal;

    if (data) {
        for (let i = 0; i < data.labels.length; i++) {
            const totalBed = data.data_terisi[i] + data.data_kosong[i];
            const borValue = data.bor[i];

            let borClass = "";
            if (borValue < 50) borClass = "low";
            else if (borValue < 80) borClass = "medium";
            else borClass = "high";

            const tr = document.createElement("tr");
            tr.innerHTML = `
                <td>${data.labels[i]}</td>
                <td class="text-center">${totalBed}</td>
                <td class="text-center">${data.data_terisi[i]}</td>
                <td class="text-center">${data.data_kosong[i]}</td>
                <td>
                    <div class="bor-bar">
                        <div class="${borClass}" style="width: ${borValue}%">
                            ${borValue}%
                        </div>
                    </div>
                </td>
            `;
            tbody.appendChild(tr);
        }
    }

    realtimeClock(); // tampil langsung saat load
    setInterval(realtimeClock, 1000); // update tiap detik

    initSliders();
});

document.addEventListener("DOMContentLoaded", () => {
    loadDashboard();
    setInterval(loadDashboard, 15000);
});
