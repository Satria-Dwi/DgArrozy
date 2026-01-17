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

    let chartTopPenyakit = null;

    const fetchTopPenyakit = async () => {
        try {
            const res = await fetch("/admin/top-penyakit-bulan-ini");
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
            ]);
        } catch (e) {
            console.error("❌ fetchAll error:", e);
        } finally {
            setLoading(false);
            isFetching = false;
        }
    };

    // =========================
    // AUTO REFRESH GLOBAL
    // =========================
    const startAutoRefresh = () => {
        clearInterval(autoRefresh);
        autoRefresh = setInterval(fetchAll, 30000);
    };

    // =========================
    // EVENT
    // =========================
    btnFilter.addEventListener("click", () => {
        fetchAll();
        startAutoRefresh();
    });

    btnRefresh.addEventListener("click", () => {
        fetchAll();
        startAutoRefresh();
    });

    // =========================
    // INIT
    // =========================
    fetchAll();
    startAutoRefresh();
});
