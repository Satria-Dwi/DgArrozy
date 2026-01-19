document.addEventListener("DOMContentLoaded", () => {
    // =========================
    // ELEMENT
    // =========================
    const fromInput = document.getElementById("from");
    const toInput = document.getElementById("to");
    const btnFilter = document.getElementById("btnFilter");
    const btnRefresh = document.getElementById("btnRefresh");

    const filterTanggal = document.getElementById("filterTanggal");
    const totalRanap = document.getElementById("totalRanap");
    const totalPasien = document.getElementById("totalPasien");
    const totalRegPasien = document.getElementById("totalRegPasien");
    const totalPoli = document.getElementById("totalPoli");
    const totaligd = document.getElementById("totaligd");
    const totaloperasi = document.getElementById("totaloperasi");

    // KAMAR PER BANGSAL (GLOBAL)
    const kamarBangsal = document.getElementById("tempat_tidur_per_bangsal");

    if (!fromInput || !toInput || !btnFilter || !btnRefresh) {
        console.error("❌ Element tidak lengkap");
        return;
    }

    // =========================
    // DEFAULT TANGGAL
    // =========================
    const today = new Date().toISOString().split("T")[0];
    fromInput.value = today;
    toInput.value = today;

    let isFetching = false;
    let autoRefresh = null;

    // =========================
    // LOADING
    // =========================
    const setLoading = (state) => {
        btnFilter.disabled = state;
        btnRefresh.disabled = state;
        btnFilter.textContent = state ? "Loading..." : "Filter";
    };

    // =========================
    // FETCH PASIEN (GLOBAL)
    // =========================
    const fetchPasien = async () => {
        try {
            const res = await fetch("/mainadmin/pasien-summary");
            if (!res.ok) throw new Error(res.status);

            const data = await res.json();
            totalPasien.textContent = data.total_pasien ?? 0;
        } catch {
            totalPasien.textContent = "—";
        }
    };

    // =========================
    // FETCH SUMMARY (FILTER)
    // =========================
    const fetchSummary = async () => {
        const from = fromInput.value;
        const to = toInput.value;

        filterTanggal.textContent = `${from} s/d ${to}`;

        try {
            const res = await fetch(
                `/mainadmin/manajemendata?from=${from}&to=${to}`,
            );
            if (!res.ok) throw new Error(res.status);

            const { summary = {} } = await res.json();

            // RAWAT INAP
            totalRanap.textContent = summary.rawat_inap ?? 0;

            // REG PASIEN (AKUMULASI)
            const regData = summary.reg_pasien ?? [];
            const totalReg = regData.reduce(
                (sum, r) => sum + Number(r.total),
                0,
            );
            totalRegPasien.textContent = totalReg;

            // POLI
            totalPoli.textContent = summary.poli ?? 0;
      
            // IGD
            totaligd.textContent = summary.igd ?? 0;
            
            // IGD
            totaloperasi.textContent = summary.operasi ?? 0;

        } catch {
            totalRanap.textContent = "—";
            totalRegPasien.textContent = "—";
            totalPoli.textContent = "—";
        }
    };

    // =========================
    // FETCH KAMAR PER BANGSAL (TANPA FILTER)
    // =========================
    const fetchKamarBangsal = async () => {
        try {
            const res = await fetch("/mainadmin/tempat-tidur-bangsal");
            if (!res.ok) throw new Error(res.status);

            const data = await res.json();

            kamarBangsal.innerHTML = ""; // reset table

            if (!data.labels || data.labels.length === 0) {
                kamarBangsal.innerHTML = `
            <tr>
                <td colspan="5" class="text-center py-4 text-gray-500">
                    Tidak ada data
                </td>
            </tr>`;
                return;
            }

            let hasData = false;

            data.labels.forEach((bangsal, i) => {
                const totalBed = data.data_terisi[i] + data.data_kosong[i];

                // Jika total bed = 0, skip bangsal ini
                if (totalBed === 0) return;

                hasData = true;

                const borValue = Number(data.bor[i]);
                let borClass = "";
                if (borValue < 50) borClass = "low";
                else if (borValue < 80) borClass = "medium";
                else borClass = "high";

                const tr = document.createElement("tr");
                tr.innerHTML = `
            <td class="px-4 py-2">${bangsal}</td>
            <td class="px-4 py-2 text-center">${totalBed}</td>
            <td class="px-4 py-2 text-center text-red-600">
                ${data.data_terisi[i]}
            </td>
            <td class="px-4 py-2 text-center text-green-600">
                ${data.data_kosong[i]}
            </td>
            <td class="px-4 py-2">
                <div class="bor-bar">
                    <div class="bor-fill ${borClass}" style="width:${borValue}%">
                        ${borValue}%
                    </div>
                </div>
            </td>
            `;
                kamarBangsal.appendChild(tr);
            });

            // Jika setelah filter semua kosong
            if (!hasData) {
                kamarBangsal.innerHTML = `
            <tr>
                <td colspan="5" class="text-center py-4 text-gray-500">
                    Tidak ada data
                </td>
            </tr>`;
            }
        } catch (err) {
            console.error("❌ Kamar bangsal error:", err);
            kamarBangsal.innerHTML = `
        <tr>
            <td colspan="5" class="text-center py-4 text-red-500">
                Gagal memuat data
            </td>
        </tr>`;
        }
    };

    // fetch top penyakit //
    let chartTopPenyakit = null;

    const fetchTopPenyakit = async () => {
        try {
            const res = await fetch("/mainadmin/top-penyakit-bulan-ini");
            if (!res.ok) throw new Error(res.status);

            const data = await res.json();
            const labels = data.labels; // Nama penyakit
            const counts = data.data; // Jumlah kasus

            const canvas = document.getElementById("chartTopPenyakit");
            if (!canvas) return;

            const ctx = canvas.getContext("2d");

            // Gradient fill area
            const gradientFill = ctx.createLinearGradient(
                0,
                0,
                0,
                canvas.height,
            );
            gradientFill.addColorStop(0, "rgba(99,102,241,0.35)");
            gradientFill.addColorStop(1, "rgba(99,102,241,0.03)");

            if (!chartTopPenyakit) {
                chartTopPenyakit = new Chart(ctx, {
                    type: "line",
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                data: counts,
                                borderColor: "#6366f1", // Indigo
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
                                grid: { display: false },
                                ticks: { color: "#9ca3af", font: { size: 11 } },
                            },
                            y: {
                                beginAtZero: true,
                                grid: { color: "rgba(255,255,255,0.05)" },
                                ticks: { color: "#9ca3af", precision: 0 },
                            },
                        },
                    },
                });
            } else {
                chartTopPenyakit.data.labels = labels;
                chartTopPenyakit.data.datasets[0].data = counts;
                chartTopPenyakit.update();
            }
        } catch (err) {
            console.error("Gagal fetch top penyakit:", err);
        }
    };

    // fetch kunjungan poli hari ini

    let chartKunjunganPoli = null;

    const fetchKunjunganPoli = async () => {
        try {
            const url = window.location.origin + "/mainadmin/kunjungan-poli";
            const res = await fetch(url);
            if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);

            let { labels = [], data = [] } = await res.json();

            const canvas = document.getElementById("chartKunjunganPoli");
            if (!canvas) return;

            // Filter & sort data
            let combined = labels
                .map((label, i) => ({
                    label: label,
                    value: Number(data[i]) || 0,
                }))
                .filter((item) => item.value > 0)
                .sort((a, b) => b.value - a.value);

            if (combined.length === 0) {
                canvas.parentElement.style.height = "100px";
                return;
            }

            const cleanLabels = combined.map((i) => i.label);
            const cleanData = combined.map((i) => i.value);

            const colorPalette = [
                "#6366f1",
                "#4f46e5",
                "#4338ca",
                "#3730a3",
                "#312e81",
            ];
            const backgroundColors = cleanLabels.map(
                (_, i) => colorPalette[i % colorPalette.length],
            );

            // Set dynamic height
            const rowHeight = 40;
            canvas.parentElement.style.height =
                Math.max(cleanLabels.length * rowHeight, 180) + "px";

            if (!chartKunjunganPoli) {
                chartKunjunganPoli = new Chart(canvas, {
                    type: "bar",
                    plugins: [ChartDataLabels],
                    data: {
                        labels: cleanLabels,
                        datasets: [
                            {
                                data: cleanData,
                                backgroundColor: backgroundColors,
                                borderRadius: 6,
                                barThickness: 30,
                            },
                        ],
                    },
                    options: {
                        indexAxis: "y",
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: { display: false, beginAtZero: true },
                            y: {
                                grid: { display: false },
                                border: { display: false },
                                ticks: { display: false }, // hide default Y ticks
                            },
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: { enabled: true },
                            datalabels: {
                                color: function (context) {
                                    // putih di tengah, hitam di ujung kanan
                                    return context.datasetIndex === 0
                                        ? "#ffffff"
                                        : "#000000";
                                },
                                font: { size: 14, weight: "700" },
                                anchor: "center", // default untuk nama bar
                                align: "center",
                                formatter: function (value, context) {
                                    // overlay dua label: nama + value
                                    if (context.dataIndex !== undefined) {
                                        let label =
                                            context.chart.data.labels[
                                                context.dataIndex
                                            ];
                                        return label + " (" + value + ")"; // "NamaBar (Value)"
                                    }
                                    return value;
                                },
                            },
                        },
                    },
                });
            } else {
                chartKunjunganPoli.data.labels = cleanLabels;
                chartKunjunganPoli.data.datasets[0].data = cleanData;
                chartKunjunganPoli.data.datasets[0].backgroundColor =
                    backgroundColors;
                chartKunjunganPoli.update();
            }
        } catch (error) {
            console.error("Gagal fetch Kunjungan Poli:", error);
        }
    };

    const fetchAll = async () => {
        if (isFetching) return;

        isFetching = true;
        setLoading(true);

        try {
            await Promise.all([
                fetchSummary(), // 🔍 pakai filter tanggal
                fetchPasien(), // 🌍 global
                fetchKamarBangsal(), // 🏥 global (TIDAK ikut filter)
                fetchTopPenyakit(),
                fetchKunjunganPoli(),
            ]);
        } catch (e) {
            console.error("❌ fetchAll error:", e);
        } finally {
            setLoading(false);
            isFetching = false;
        }
    };

    let fastInterval = null;
    let isFastFetching = false;

    const fastRefresh = async () => {
        if (isFastFetching) return;
        isFastFetching = true;

        try {
            await Promise.all([
                fetchPasien(),
                fetchSummary(),
            ]);
        } catch (e) {
            console.error("❌ fastRefresh error:", e);
        } finally {
            isFastFetching = false;
        }
    };

    let mediumInterval = null;
    let isMediumFetching = false;

    const mediumRefresh = async () => {
        if (isMediumFetching) return;
        isMediumFetching = true;

        try {
            await Promise.all([
                fetchTopPenyakit(), 
                fetchKamarBangsal(), 
                fetchKunjunganPoli(),
            ]);
        } catch (e) {
            console.error("❌ mediumRefresh error:", e);
        } finally {
            isMediumFetching = false;
        }
    };

    const fetchFilteredData = async () => {
        setLoading(true);
        await fetchSummary();
        setLoading(false);
    };

    // =========================
    // AUTO REFRESH GLOBAL
    // =========================
    const startAutoRefresh = () => {
        clearInterval(fastInterval);
        clearInterval(mediumInterval);

        fastRefresh();
        mediumRefresh();

        fastInterval = setInterval(fastRefresh, 30000); // 30 detik
        mediumInterval = setInterval(mediumRefresh, 60000); // 60 detik
    };

    // =========================
    // EVENT
    // =========================
    btnFilter.addEventListener("click", async () => {
        await fetchFilteredData();
    });

    btnRefresh.addEventListener("click", async () => {
        await fetchFilteredData();
    });

    // =========================
    // INIT
    // =========================
    fetchAll();
    fetchFilteredData(); // sekali saat load
    startAutoRefresh();
});
