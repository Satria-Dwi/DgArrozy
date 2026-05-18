let currentTab = "Ralan";

document.addEventListener("DOMContentLoaded", function () {
    updateTabUI();
    updateHeader();
    toggleDiagnosaFilter();
});

// =======================
// Helper Aman Ambil Value
// =======================
function getValue(id) {
    return document.getElementById(id)?.value || "";
}

// =======================
// Tab handling
// =======================
function setTab(tab) {
    currentTab = tab;

    updateTabUI();
    updateHeader();
    toggleDiagnosaFilter();

    // hanya load jika filter ada
    if (hasFilter()) {
        loadData(1);
    }
}

function updateTabUI() {
    const tabRalan = document.getElementById("tabRalan");
    const tabRanap = document.getElementById("tabRanap");

    if (!tabRalan || !tabRanap) return;

    tabRalan.className =
        "tabJenis px-5 py-2 text-sm font-semibold rounded-lg transition-all duration-200 text-slate-600 hover:text-blue-600";

    tabRanap.className =
        "tabJenis px-5 py-2 text-sm font-semibold rounded-lg transition-all duration-200 text-slate-600 hover:text-blue-600";

    if (currentTab === "Ralan") {
        tabRalan.classList.add("bg-white", "text-blue-600", "shadow-sm");
    } else {
        tabRanap.classList.add("bg-white", "text-blue-600", "shadow-sm");
    }
}

function updateHeader() {
    const extraHeader = document.getElementById("extraHeader");
    const asalPoliHeader = document.getElementById("asalPoliHeader");

    if (!extraHeader) return;

    if (currentTab === "Ralan") {
        extraHeader.textContent = "Poli";

        if (asalPoliHeader) {
            asalPoliHeader.style.display = "none";
        }
    } else {
        extraHeader.textContent = "Kamar";

        if (asalPoliHeader) {
            asalPoliHeader.style.display = "";
        }
    }
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

        const diagInput = diagDiv.querySelector("input");

        if (diagInput) {
            diagInput.value = "";
        }
    }
}

// =======================
// Cek minimal 1 filter diisi
// =======================
function hasFilter() {
    return (
        getValue("tanggal_awal") !== "" ||
        getValue("tanggal_akhir") !== "" ||
        getValue("umur_dari") !== "" ||
        getValue("umur_sampai") !== "" ||
        getValue("jk") !== "" ||
        getValue("kode_penyakit") !== "" ||
        (currentTab === "Ranap" &&
            getValue("diagnosa_final") !== "")
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

    // DEBUG
    console.log({
        tanggal_awal: document.getElementById("tanggal_awal"),
        tanggal_akhir: document.getElementById("tanggal_akhir"),
        umur_dari: document.getElementById("umur_dari"),
        umur_sampai: document.getElementById("umur_sampai"),
        jk: document.getElementById("jk"),
        kode_penyakit: document.getElementById("kode_penyakit"),
        diagnosa_final: document.getElementById("diagnosa_final"),
    });

    let url =
        currentTab === "Ralan"
            ? "/rm/pasien/ralan"
            : "/rm/pasien/ranap";

    let params = new URLSearchParams({
        page: page,
        tanggal_awal: getValue("tanggal_awal"),
        tanggal_akhir: getValue("tanggal_akhir"),
        per_page: 20,
    });

    // =======================
    // Filter Umur
    // =======================
    const umurDari = getValue("umur_dari");
    const umurSampai = getValue("umur_sampai");

    if (umurDari) {
        params.append("umur_dari", umurDari);
    }

    if (umurSampai) {
        params.append("umur_sampai", umurSampai);
    }

    // =======================
    // Filter Jenis Kelamin
    // =======================
    const jkVal = getValue("jk");

    if (jkVal) {
        params.append("jk", jkVal);
    }

    // =======================
    // Filter Kode Penyakit
    // =======================
    const kodePenyakitVal = getValue("kode_penyakit");

    if (kodePenyakitVal) {
        params.append("kode_penyakit", kodePenyakitVal);
    }

    // =======================
    // Filter Diagnosa Final
    // =======================
    if (currentTab === "Ranap") {

        const diagVal = getValue("diagnosa_final");

        if (diagVal) {
            params.append("diagnosa_final", diagVal);
        }
    }

    fetch(`${url}?${params.toString()}`)
        .then((res) => {

            if (!res.ok) {
                throw new Error("Server error: " + res.status);
            }

            return res.json();
        })
        .then((res) => {
            renderTable(res);
        })
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

    if (!tbody) return;

    tbody.innerHTML = "";

    const totalEl = document.getElementById("totalPasien");

    if (totalEl) {

        const total = res.total ?? res.data.length ?? 0;

        totalEl.textContent = `Total pasien: ${total}`;
    }

    if (!res.data || res.data.length === 0) {

        tbody.innerHTML = `
            <tr>
                <td colspan="14"
                    class="text-center py-6 text-gray-400">
                    Tidak ada data ditemukan
                </td>
            </tr>
        `;

        return;
    }

    res.data.forEach((row, index) => {

        let extraCell =
            currentTab === "Ralan"
                ? (row.nm_poli ?? "-")
                : (row.nm_kamar ?? "-");

        let diagnosa = "-";

        if (
            typeof row.diagnosa_final === "string" &&
            row.diagnosa_final.trim() !== "" &&
            row.diagnosa_final !== "-"
        ) {

            diagnosa = row.diagnosa_final;

        } else if (
            row.nama_penyakit &&
            row.nama_penyakit !== "-"
        ) {

            diagnosa = row.nama_penyakit;
        }

        let rowClass =
            index % 2 === 0
                ? "bg-white hover:bg-gray-50 text-black"
                : "bg-gray-50 hover:bg-gray-100 text-black";

        tbody.innerHTML += `
            <tr class="${rowClass}">

                <td class="px-4 py-3 whitespace-nowrap">
                    ${row.tanggal_rawat ?? "-"}
                </td>

                <td class="px-4 py-3 whitespace-nowrap">
                    ${row.no_rawat ?? "-"}
                </td>

                <td class="px-4 py-3 whitespace-nowrap">
                    ${row.no_rkm_medis ?? "-"}
                </td>

                <td class="px-4 py-3 whitespace-nowrap font-medium">
                    ${row.nm_pasien ?? "-"}
                </td>

                <td class="px-4 py-3 whitespace-nowrap">
                    ${row.jk ?? "-"}
                </td>

                <td class="px-4 py-3 whitespace-nowrap">
                    ${row.umur ?? "-"}
                </td>

                <td class="px-4 py-3 whitespace-nowrap">
                    ${row.nik ?? "-"}
                </td>

                <td class="px-4 py-3 whitespace-nowrap">
                    ${row.status ?? "-"}
                </td>

                <td class="px-4 py-3 whitespace-nowrap">
                    ${row.kasus ?? "-"}
                </td>

                <td class="px-4 py-3 whitespace-nowrap">
                    ${extraCell}
                </td>

                ${currentTab === "Ranap"
                ? `
                            <td class="px-4 py-3 whitespace-nowrap">
                                ${row.nm_poli ?? "-"}
                            </td>
                        `
                : ``
            }

                <td class="px-4 py-3 whitespace-nowrap">
                    ${row.nm_dokter ?? "-"}
                </td>

                <td class="px-4 py-3 whitespace-nowrap">
                    ${row.kode_penyakit ?? "-"}
                </td>

                <td class="px-4 py-3 whitespace-nowrap">
                    ${diagnosa}
                </td>

            </tr>
        `;
    });

    function renderPagination(res) {
        const pagination = document.getElementById("pagination");

        if (!pagination) return;

        pagination.innerHTML = "";

        if (!res.last_page || res.last_page <= 1) return;

        const current = res.current_page;
        const last = res.last_page;

        let html = `<div class="flex items-center space-x-1">`;

        // tombol prev
        html += `
        <button
            onclick="loadData(${current - 1})"
            ${current === 1 ? "disabled" : ""}
            class="px-3 py-1.5 rounded-lg border text-sm transition ${current === 1
                ? "bg-gray-100 text-gray-400 cursor-not-allowed"
                : "bg-white text-black hover:bg-blue-50 hover:text-blue-600"
            }"
        >
            ‹
        </button>
    `;

        let start = Math.max(1, current - 2);
        let end = Math.min(last, current + 2);

        if (start > 1) {
            html += pageButton(1, current);

            if (start > 2) {
                html += ellipsis();
            }
        }

        for (let i = start; i <= end; i++) {
            html += pageButton(i, current);
        }

        if (end < last) {
            if (end < last - 1) {
                html += ellipsis();
            }

            html += pageButton(last, current);
        }

        // tombol next
        html += `
        <button
            onclick="loadData(${current + 1})"
            ${current === last ? "disabled" : ""}
            class="px-3 py-1.5 rounded-lg border text-sm transition ${current === last
                ? "bg-gray-100 text-gray-400 cursor-not-allowed"
                : "bg-white text-black hover:bg-blue-50 hover:text-blue-600"
            }"
        >
            ›
        </button>
    `;

        html += `</div>`;

        pagination.innerHTML = html;
    }
}