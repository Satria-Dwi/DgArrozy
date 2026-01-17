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

    if (!fromInput || !toInput || !btnFilter || !btnRefresh) {
        console.error("❌ Element tidak lengkap");
        return;
    }

    // =========================
    // DEFAULT TANGGAL (HARI INI)
    // =========================
    const today = new Date().toISOString().split("T")[0];
    fromInput.value = today;
    toInput.value = today;

    // =========================
    // LOADING STATE
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
        } catch (err) {
            console.error("❌ Error pasien:", err);
            totalPasien.textContent = "—";
        }
    };

    // =========================
    // FETCH GLOBAL (SUMMARY)
    // =========================
    const fetchGlobal = async () => {
        const from = fromInput.value;
        const to = toInput.value;

        filterTanggal.textContent = `${from} s/d ${to}`;

        try {
            const res = await fetch(
                `/mainadmin/manajemendata?from=${from}&to=${to}`,
            );
            if (!res.ok) throw new Error(res.status);

            const data = await res.json();
            const summary = data.summary ?? {};

            // RAWAT INAP
            totalRanap.textContent = summary.rawat_inap ?? 0;

            // REG PASIEN (AKUMULASI)
            const regData = summary.reg_pasien ?? [];
            const totalReg = regData.reduce(
                (sum, item) => sum + Number(item.total),
                0,
            );
            totalRegPasien.textContent = totalReg;

            // POLI
            totalPoli.textContent = summary.poli ?? 0;
        } catch (err) {
            console.error("❌ Error global:", err);
            totalRanap.textContent = "—";
            totalRegPasien.textContent = "—";
            totalPoli.textContent = "—";
        }
    };

    // =========================
    // FETCH ALL
    // =========================
    const fetchAll = async () => {
        setLoading(true);
        await Promise.all([fetchGlobal(), fetchPasien()]);
        setLoading(false);
    };

    // =========================
    // REALTIME AUTO REFRESH
    // =========================
    let autoRefresh = setInterval(() => {
        console.log("🔄 Auto refresh dashboard");
        fetchAll();
    }, 30000); // 30 detik

    // =========================
    // EVENT
    // =========================
    btnFilter.addEventListener("click", fetchAll);
    btnRefresh.addEventListener("click", fetchAll);

    // =========================
    // INITIAL LOAD
    // =========================
    fetchAll();
});
