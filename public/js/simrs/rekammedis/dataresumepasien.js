const state = {
    tab: "Ralan",
    data: [],
    page: 1,
};

// let state.tab = "Ralan";

document.addEventListener("DOMContentLoaded", function () {
    state.tab = localStorage.getItem("rm_tab") || "Ralan";

    updateTabUI();
    updateHeader();
    toggleDiagnosaFilter();
    loadData(1);

    initAutoSearch();
});

let searchTimer = null;

function initAutoSearch() {
    const filterIds = [
        "keyword",
        "kode_penyakit",
        "diagnosa_final",
        "tanggal_awal",
        "tanggal_akhir",
        "umur_dari",
        "umur_sampai",
        "jk"
    ];

    filterIds.forEach(id => {
        const el = document.getElementById(id);

        if (!el) return;

        const eventType =
            el.tagName === "SELECT" ||
                el.type === "date"
                ? "change"
                : "input";

        el.addEventListener(eventType, () => {
            clearTimeout(searchTimer);

            searchTimer = setTimeout(() => {
                loadData(1);
            }, 500); // delay 500ms
        });
    });
}

// =======================
// Tab handling
// =======================
function setTab(tab) {
    state.tab = tab;
    localStorage.setItem("rm_tab", tab);

    state.page = 1;
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
    if (state.tab === "Ralan") {
        tabRalan.classList.add("bg-white", "text-blue-600", "shadow-sm");
    } else {
        tabRanap.classList.add("bg-white", "text-blue-600", "shadow-sm");
    }
}

function updateHeader() {

    const extraHeader =
        document.getElementById("extraHeader");

    const asalPoliHeader =
        document.getElementById("asalPoliHeader");

    const verifyHeader =
        document.getElementById("verifyHeader");

    const commentHeader =
        document.getElementById("commentHeader");

    const verifyDateHeader =
        document.getElementById("verifyDateHeader");

    const verifiedByHeader =
        document.getElementById("verifiedByHeader");

    const isRanap = state.tab === "Ranap";

    const sumberHeader =
        document.getElementById("sumberHeader");

    if (extraHeader) {
        extraHeader.textContent = isRanap ? "Kamar" : "Poli";
    }

    if (asalPoliHeader) {
        asalPoliHeader.style.display =
            isRanap ? "" : "none";
    }

    if (verifyHeader) {
        verifyHeader.style.display =
            isRanap ? "" : "none";
    }

    if (commentHeader) {
        commentHeader.style.display =
            isRanap ? "" : "none";
    }

    if (verifyDateHeader) {
        verifyDateHeader.style.display =
            isRanap ? "" : "none";
    }

    if (verifiedByHeader) {
        verifiedByHeader.style.display =
            isRanap ? "" : "none";
    }

    if (sumberHeader) {
        sumberHeader.style.display =
            isRanap ? "" : "none";
    }
    // console.log("TAB:", state.tab);
}

// =======================
// Toggle Diagnosa Akhir Filter
// =======================
function toggleDiagnosaFilter() {
    const diagDiv = document.getElementById("filterDiagnosaFinal");
    if (!diagDiv) return;

    if (state.tab === "Ranap") {
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
    // Menggunakan optional chaining (?.) untuk menghindari error jika elemen tidak ditemukan di halaman
    return (
        (document.getElementById("tanggal_awal")?.value || "") !== "" ||
        (document.getElementById("tanggal_akhir")?.value || "") !== "" ||
        (document.getElementById("umur_dari")?.value || "") !== "" ||
        (document.getElementById("umur_sampai")?.value || "") !== "" ||
        (document.getElementById("jk")?.value || "") !== "" ||
        (document.getElementById("kode_penyakit")?.value || "") !== "" ||
        (state.tab === "Ranap" && (document.getElementById("diagnosa_final")?.value || "") !== "")
    );
}

// =======================
// Load Data Pasien
// =======================
async function loadData(page = 1) {
    state.page = page;

    const url = state.tab === "Ralan"
        ? "/rm/pasien/ralan"
        : "/rm/pasien/ranap";

    const params = new URLSearchParams({
        page,
        per_page: 20,
        keyword: val("keyword"),
        tanggal_awal: val("tanggal_awal"),
        tanggal_akhir: val("tanggal_akhir"),
        umur_dari: val("umur_dari"),
        umur_sampai: val("umur_sampai"),
        jk: val("jk"),
        kode_penyakit: val("kode_penyakit"),
        diagnosa_final: state.tab === "Ranap"
            ? val("diagnosa_final")
            : ""
    });

    try {
        const res = await fetch(`${url}?${params}`);
        if (!res.ok) throw new Error("Server error");

        const json = await res.json();

        state.data = json.data;

        renderTable(json);
    } catch (err) {
        console.error(err);
        alert("Gagal load data");
    }
}

function val(id) {
    return document.getElementById(id)?.value || "";
}

// =======================
// Render Table
// =======================
function renderTable(res) {
    const tbody = document.getElementById("tableBody");
    if (!tbody) return;

    if (!res.data || res.data.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="20" class="text-center py-6 text-gray-400">
                    Tidak ada data
                </td>
            </tr>`;
        return;
    }

    tbody.innerHTML = res.data.map((row, i) => {
        const extra = state.tab === "Ralan" ? row.nm_poli : row.nm_kamar;

        return `
        <tr data-no-rawat="${row.no_rawat}"
            class="${i % 2 ? 'bg-gray-50' : 'bg-white'}">

            <td>${row.tanggal_rawat ?? "-"}</td>
            <td>${row.no_rawat ?? "-"}</td>
            <td>${row.no_rkm_medis ?? "-"}</td>
            <td class="font-medium">${row.nm_pasien ?? "-"}</td>
            <td>${row.jk ?? "-"}</td>
            <td>${row.umur ?? "-"}</td>
            <td>${row.nik ?? "-"}</td>
            <td>${row.status ?? "-"}</td>
            <td>${row.kasus ?? "-"}</td>
            <td>${extra ?? "-"}</td>
            <td>${row.nm_dokter ?? "-"}</td>
            <td>${row.kode_penyakit ?? "-"}</td>
            <td>${row.diagnosa_final ?? row.nama_penyakit ?? "-"}</td>

            ${state.tab === "Ranap" ? `
                <td>${row.nm_poli ?? "-"}</td>
                <td class="text-center">
                    ${Number(row.verified_resume) === 1
                    ? '<span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded">Resume</span>'
                    : '<span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-700 rounded">Kamar Inap</span>'
                }
                </td>

                <td class="text-center">
                    <input type="checkbox"
                        data-action="verify"
                        data-no-rawat="${row.no_rawat}"
                        data-no-rm="${row.no_rkm_medis}"
                        ${Number(row.verified) === 1 ? "checked" : ""}
                        onchange="openVerifyModal(this)" />
                </td>

                <td class="comment">
                    <span class="comment-text">${row.comment ?? "-"}</span>
                    <button
                        data-no-rawat="${row.no_rawat}"
                        data-comment="${encodeURIComponent(row.comment ?? '')}"
                        onclick="editComment(this)"
                        class="ml-2 text-blue-500 text-xs">
                        edit
                    </button>
                </td>
                <td class="date">${row.verify_date ?? "-"}</td>
                <td class="by">${row.verified_by ?? "-"}</td>
            ` : ""}
        </tr>
        `;
    }).join("");
    // console.log("ROW SAMPLE:", res.data[0]);
    renderPagination(res);
}



async function editComment(el) {
    const no_rawat = el.dataset.noRawat;
    const oldComment = decodeURIComponent(el.dataset.comment || "");

    const { value: comment } = await Swal.fire({
        title: 'Edit Comment',
        input: 'text',
        inputValue: oldComment,
        showCancelButton: true,
        confirmButtonText: 'Simpan'
    });

    if (comment === undefined) return;

    try {
        const res = await fetch("/rm/pasien/verify-ranap/comment", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                no_rawat,
                comment
            })
        });

        const json = await res.json();

        if (!json.success) throw new Error(json.message);

        const row = document.querySelector(`tr[data-no-rawat="${no_rawat}"]`);
        if (row) {
            row.querySelector(".comment-text").innerText = comment || "-";
        }

        // update dataset biar edit berikutnya update
        el.dataset.comment = encodeURIComponent(comment || "");

    } catch (err) {
        console.error(err);
        alert("Gagal update comment");
    }
}

async function openVerifyModal(el) {
    const { value: comment } = await Swal.fire({
        title: 'Komentar',
        input: 'text',
        showCancelButton: true
    });

    if (comment === null) {
        el.checked = !el.checked;
        return;
    }

    await saveVerify(el, comment);
}

function formatDate(dateString) {
    if (!dateString || dateString === "-") {
        return "-";
    }

    return dateString;
}

async function saveVerify(el, comment) {
    const payload = {
        no_rawat: el.dataset.noRawat,
        no_rm: el.dataset.noRm,
        verified: el.checked ? 1 : 0,
        comment: comment || ""
    };

    try {
        const res = await fetch("/rm/pasien/verify-ranap", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN":
                    document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content
            },
            body: JSON.stringify(payload)
        });

        const json = await res.json();

        if (!json.success) {
            throw new Error(json.message);
        }

        const row = el.closest("tr");

        // update comment text saja
        const commentText =
            row.querySelector(".comment-text");

        if (commentText) {
            commentText.innerText =
                payload.comment || "-";
        }

        // update dataset tombol edit
        const editBtn =
            row.querySelector("button[data-no-rawat]");

        if (editBtn) {
            editBtn.dataset.comment =
                encodeURIComponent(payload.comment || "");
        }

        // kalau uncheck -> langsung kosong
        row.querySelector(".date").innerText =
            el.checked
                ? formatDate(json.data?.verify_date)
                : "-";

        row.querySelector(".by").innerText =
            el.checked
                ? (json.data?.verified_by || "-")
                : "-";

    } catch (err) {
        console.error(err);
        alert("Gagal simpan verifikasi");

        el.checked = !el.checked;
    }
}

// =======================
// Export Excel
// =======================
function exportExcel() {
    // if (!hasFilter()) {
    //     alert("Silakan isi minimal satu filter terlebih dahulu.");
    //     return;
    // }

    let url =
        state.tab === "Ralan"
            ? "/rm/pasien/ralan/export"
            : "/rm/pasien/ranap/export";

    let params = new URLSearchParams({
        tanggal_awal: document.getElementById("tanggal_awal")?.value || '',
        tanggal_akhir: document.getElementById("tanggal_akhir")?.value || '',
    });

    // Perbaikan Export untuk Umur Range
    const umurDari = document.getElementById("umur_dari")?.value;
    const umurSampai = document.getElementById("umur_sampai")?.value;

    if (umurDari) params.append("umur_dari", umurDari);
    if (umurSampai) params.append("umur_sampai", umurSampai);

    const jkVal = document.getElementById("jk")?.value;
    if (jkVal) params.append("jk", jkVal);

    const kodePenyakitVal = document.getElementById("kode_penyakit")?.value;
    if (kodePenyakitVal) params.append("kode_penyakit", kodePenyakitVal);

    if (state.tab === "Ranap") {
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
    if (!pagination) return;
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
