let currentTab = "Ralan";

document.addEventListener("DOMContentLoaded", function () {
    updateTabUI();
    updateHeader();
    toggleDiagnosaFilter();
});

// =======================
// Tab handling
// =======================
function setTab(tab) {
    currentTab = tab;
    updateTabUI();
    updateHeader();
    toggleDiagnosaFilter();
    loadData(1);
}

function updateTabUI() {
    const tabRalan = document.getElementById("tabRalan");
    const tabRanap = document.getElementById("tabRanap");

    // Reset semua tombol ke default (non-aktif)
    tabRalan.className =
        "tabJenis px-5 py-2 text-sm font-semibold rounded-lg transition-all duration-200 text-slate-600 hover:text-blue-600";
    tabRanap.className =
        "tabJenis px-5 py-2 text-sm font-semibold rounded-lg transition-all duration-200 text-slate-600 hover:text-blue-600";

    // Tambahkan class aktif sesuai tab
    if (currentTab === "Ralan") {
        tabRalan.classList.add("bg-white", "text-blue-600", "shadow-sm");
    } else {
        tabRanap.classList.add("bg-white", "text-blue-600", "shadow-sm");
    }
}

function updateHeader() {
    const extraHeader = document.getElementById("extraHeader");
    extraHeader.textContent = currentTab === "Ralan" ? "Poli" : "Kamar";
}

// =======================
// Toggle Diagnosa Akhir Filter
// =======================
function toggleDiagnosaFilter() {
    const diagDiv = document.getElementById("filterDiagnosaFinal");
    if (!diagDiv) return;

    if (currentTab === "Ranap") {
        diagDiv.style.display = "block";
    } else {
        diagDiv.style.display = "none";
        // Reset value jika tab Ralan
        const diagInput = diagDiv.querySelector("input");
        if (diagInput) diagInput.value = "";
    }
}

// =======================
// Cek minimal 1 filter diisi
// =======================
function hasFilter() {
    return (
        document.getElementById("tanggal_awal").value !== "" ||
        document.getElementById("tanggal_akhir").value !== "" ||
        document.getElementById("umur_tahun").value !== "" ||
        document.getElementById("jk").value !== "" ||
        document.getElementById("kode_penyakit").value !== "" ||
        (currentTab === "Ranap" &&
            document.getElementById("diagnosa_final")?.value !== "")
    );
}

// =======================
// Load Data Pasien
// =======================
function loadData(page = 1) {
    if (!hasFilter()) {
        alert("Silakan isi minimal satu filter terlebih dahulu.");
        return;
    }

    let url = currentTab === "Ralan" ? "/rm/pasien/ralan" : "/rm/pasien/ranap";

    let params = new URLSearchParams({
        page: page,
        tanggal_awal: document.getElementById("tanggal_awal").value,
        tanggal_akhir: document.getElementById("tanggal_akhir").value,
        per_page: 20,
    });

    // Filter umur
    const umurVal = document.getElementById("umur_tahun").value;
    if (umurVal) {
        params.append(
            "umur_operator",
            document.getElementById("umur_operator").value,
        );
        params.append("umur_tahun", umurVal);
    }

    // Filter jenis kelamin
    const jkVal = document.getElementById("jk").value;
    if (jkVal) params.append("jk", jkVal);

    // Filter kode/penyakit
    const kodePenyakitVal = document.getElementById("kode_penyakit").value;
    if (kodePenyakitVal) params.append("kode_penyakit", kodePenyakitVal);

    // Filter diagnosa akhir hanya untuk Ranap
    if (currentTab === "Ranap") {
        const diagVal = document.getElementById("diagnosa_final")?.value;
        if (diagVal) params.append("diagnosa_final", diagVal);
    }

    fetch(`${url}?${params.toString()}`)
        .then((res) => {
            if (!res.ok) throw new Error("Server error: " + res.status);
            return res.json();
        })
        .then((res) => renderTable(res))
        .catch((err) => {
            alert("Terjadi kesalahan server.");
            console.error(err);
        });
}

// =======================
// Render Table
// =======================
function renderTable(res) {
    let tbody = document.getElementById("tableBody");
    tbody.innerHTML = "";

    const totalEl = document.getElementById("totalPasien");
    if (totalEl) {
        const total = res.total ?? res.data.length ?? 0;
        totalEl.textContent = `Total pasien: ${total}`;
    }

    if (!res.data || res.data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="12" class="text-center py-6 text-gray-400">Tidak ada data ditemukan</td></tr>`;
        return;
    }

    res.data.forEach((row, index) => {
        let extraCell =
            currentTab === "Ralan"
                ? (row.nm_poli ?? "-")
                : (row.nm_kamar ?? "-");

        let diagnosa = "-";
        if (
            row.diagnosa_final &&
            row.diagnosa_final !== "-" &&
            row.diagnosa_final.trim() !== ""
        ) {
            diagnosa = row.diagnosa_final;
        } else if (row.nama_penyakit && row.nama_penyakit !== "-") {
            diagnosa = row.nama_penyakit;
        }

        let rowClass =
            index % 2 === 0
                ? "bg-white hover:bg-gray-50 text-black"
                : "bg-gray-50 hover:bg-gray-100 text-black";

        tbody.innerHTML += `
        <tr class="${rowClass}">
            <td class="px-4 py-3 whitespace-nowrap">${row.tanggal_rawat ?? "-"}</td>
            <td class="px-4 py-3 whitespace-nowrap">${row.no_rawat ?? "-"}</td>
            <td class="px-4 py-3 whitespace-nowrap">${row.no_rkm_medis ?? "-"}</td>
            <td class="px-4 py-3 whitespace-nowrap font-medium">${row.nm_pasien ?? "-"}</td>
            <td class="px-4 py-3 whitespace-nowrap">${row.jk ?? "-"}</td>
            <td class="px-4 py-3 whitespace-nowrap">${row.umur ?? "-"}</td>
            <td class="px-4 py-3 whitespace-nowrap">${row.nik ?? "-"}</td>
            <td class="px-4 py-3 whitespace-nowrap">${row.status ?? "-"}</td>
            <td class="px-4 py-3 whitespace-nowrap">${row.kasus ?? "-"}</td>
            <td class="px-4 py-3 whitespace-nowrap">${extraCell}</td>
            <td class="px-4 py-3 whitespace-nowrap">${row.kode_penyakit ?? "-"}</td>
            <td class="px-4 py-3 whitespace-nowrap">${diagnosa}</td>
        </tr>`;
    });

    renderPagination(res);
}

function exportExcel() {
    if (!hasFilter()) {
        alert("Silakan isi minimal satu filter terlebih dahulu.");
        return;
    }

    let url =
        currentTab === "Ralan"
            ? "/rm/pasien/ralan/export"
            : "/rm/pasien/ranap/export";

    let params = new URLSearchParams({
        tanggal_awal: document.getElementById("tanggal_awal").value,
        tanggal_akhir: document.getElementById("tanggal_akhir").value,
    });

    const umurVal = document.getElementById("umur_tahun").value;
    if (umurVal) {
        params.append(
            "umur_operator",
            document.getElementById("umur_operator").value,
        );
        params.append("umur_tahun", umurVal);
    }

    const jkVal = document.getElementById("jk").value;
    if (jkVal) params.append("jk", jkVal);

    const kodePenyakitVal = document.getElementById("kode_penyakit").value;
    if (kodePenyakitVal) params.append("kode_penyakit", kodePenyakitVal);

    if (currentTab === "Ranap") {
        const diagVal = document.getElementById("diagnosa_final")?.value;
        if (diagVal) params.append("diagnosa_final", diagVal);
    }

    window.open(`${url}?${params.toString()}`, "_blank");
}

// =======================
// Pagination
// =======================
function renderPagination(res) {
    const pagination = document.getElementById("pagination");
    pagination.innerHTML = "";
    if (!res.last_page || res.last_page <= 1) return;

    const current = res.current_page;
    const last = res.last_page;
    let html = `<div class="flex items-center space-x-1">`;

    html += `<button onclick="loadData(${current - 1})" ${current === 1 ? "disabled" : ""} class="px-3 py-1.5 rounded-lg border text-sm transition ${current === 1 ? "bg-gray-100 text-gray-400 cursor-not-allowed" : "bg-white text-black hover:bg-blue-50 hover:text-blue-600"}">‹</button>`;

    let start = Math.max(1, current - 2);
    let end = Math.min(last, current + 2);

    if (start > 1) {
        html += pageButton(1, current);
        if (start > 2) html += ellipsis();
    }
    for (let i = start; i <= end; i++) html += pageButton(i, current);
    if (end < last) {
        if (end < last - 1) html += ellipsis();
        html += pageButton(last, current);
    }

    html += `<button onclick="loadData(${current + 1})" ${current === last ? "disabled" : ""} class="px-3 py-1.5 rounded-lg border text-sm transition ${current === last ? "bg-gray-100 text-gray-400 cursor-not-allowed" : "bg-white text-black hover:bg-blue-50 hover:text-blue-600"}">›</button>`;
    html += `</div>`;
    pagination.innerHTML = html;
}

function pageButton(page, current) {
    return `<button onclick="loadData(${page})" class="px-3 py-1.5 rounded-lg border text-sm transition ${page === current ? "bg-blue-600 text-white shadow-md scale-105" : "bg-white text-black hover:bg-blue-50 hover:text-blue-600"}">${page}</button>`;
}

function ellipsis() {
    return `<span class="px-2 text-gray-400 select-none">...</span>`;
}
