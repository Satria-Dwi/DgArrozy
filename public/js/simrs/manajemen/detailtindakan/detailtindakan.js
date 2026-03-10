$(function () {
    /* ===========================
       CSRF SETUP
    ============================ */
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    /* ===========================
       AUTO OPEN DATE PICKER
    ============================ */
    ["start", "end"].forEach(function (id) {
        const input = document.getElementById(id);
        if (!input) return;

        input.addEventListener("mousedown", function (e) {
            e.preventDefault();
            if (this.showPicker) {
                this.showPicker();
            } else {
                this.focus();
            }
        });
    });

    /* ===========================
       INIT DATATABLE
    ============================ */
    let table = $("#rawatTable").DataTable({
        processing: true,
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        deferLoading: 0,
        autoWidth: false,
        scrollX: true,
        columnDefs: [
            { orderable: false, targets: 0 },
            { width: "auto", targets: "_all" },
        ],
        language: {
            emptyTable: "Silakan pilih tanggal lalu klik Tampilkan Data",
        },
    });

    // Simpan kolom dokter anestesi (kolom ke-12 / index 12)
    const colAnestesi = table.column(12);
    colAnestesi.visible(false); // default hide

    /* ===========================
       NOMOR URUT OTOMATIS
    ============================ */
    table.on("draw.dt", function () {
        let info = table.page.info();
        table
            .column(0, { page: "current" })
            .nodes()
            .each(function (cell, i) {
                cell.innerHTML = info.start + i + 1;
            });
    });

    /* ===========================
       FUNCTION LOAD DATA
    ============================ */
    function loadData() {
        const start = $("#start").val();
        const end = $("#end").val();
        const jenis = $("#jenisRawat").val();

        if (!start || !end) {
            alert("Pilih tanggal mulai dan akhir");
            return;
        }

        const url = "/manajemen/detailtindakan/" + jenis;

        $("#filterBtn").prop("disabled", true).text("Memuat...");
        table.clear().draw();

        $.ajax({
            url: url,
            type: "GET",
            dataType: "json",
            data: { start, end },

            success: function (response) {
                if (!response || response.length === 0) {
                    table.draw();
                    return;
                }

                const maxData = 1000;
                if (response.length > maxData) {
                    alert("Data terlalu banyak, maksimal 1000 baris ditampilkan.");
                    response = response.slice(0, maxData);
                }

                response.forEach(function (row) {
                    let dataRow;

                    /* ===========================
                       OPERASI
                    ============================ */
                    if (jenis === "operasi") {
                        dataRow = [
                            "",
                            row.no_rawat ?? "-",
                            row.no_rkm_medis ?? "-",
                            row.nm_pasien ?? "-",
                            row.kode_paket ?? "-",
                            row.nm_perawatan ?? "-",
                            "-",
                            row.operator1 ?? "-",
                            row.tgl_operasi ?? "-",
                            row.jam_operasi ?? "-",
                            row.png_jawab ?? "-",
                            row.operator1 ?? "-",
                            row.dokter_anestesi ?? "-",
                        ];
                    }

                    /* ===========================
                       RADIOLOGI
                    ============================ */
                    else if (jenis === "radiologi") {

                        dataRow = [
                            "",
                            row.no_rawat ?? "-",
                            row.no_rkm_medis ?? "-",
                            row.nm_pasien ?? "-",
                            row.kd_jenis_prw ?? "-",
                            row.nm_perawatan ?? "-",
                            row.kd_dokter ?? "-",
                            row.nm_dokter ?? "-",
                            row.tgl_periksa ?? "-",
                            row.jam ?? "-",
                            row.png_jawab ?? "-",
                            row.ruangan ?? "-",     // bangsal / poli
                            "-",                 // dummy anestesi
                        ];
                    }

                    /* ===========================
                       RADIOLOGI
                    ============================ */
                    else if (jenis === "laboratorium") {

                        dataRow = [
                            "",
                            row.no_rawat ?? "-",
                            row.no_rkm_medis ?? "-",
                            row.nm_pasien ?? "-",
                            row.kd_jenis_prw ?? "-",
                            row.nm_perawatan ?? "-",
                            row.kd_dokter ?? "-",
                            row.nm_dokter ?? "-",
                            row.tgl_periksa ?? "-",
                            row.jam ?? "-",
                            row.png_jawab ?? "-",
                            row.ruangan ?? "-",     // bangsal / poli
                            "-",                 // dummy anestesi
                        ];
                    }

                    /* ===========================
                       RANAP / RALAN
                    ============================ */
                    else {

                        const kolomTerakhir =
                            jenis === "ranap"
                                ? row.nm_bangsal ?? "-"
                                : row.nm_poli ?? "-";

                        dataRow = [
                            "",
                            row.no_rawat ?? "-",
                            row.no_rkm_medis ?? "-",
                            row.nm_pasien ?? "-",
                            row.kd_jenis_prw ?? "-",
                            row.nm_perawatan ?? "-",
                            row.kd_dokter ?? "-",
                            row.nm_dokter ?? "-",
                            row.tgl_perawatan ?? "-",
                            row.jam_rawat ?? "-",
                            row.png_jawab ?? "-",
                            kolomTerakhir,
                            "-",
                        ];
                    }

                    table.row.add(dataRow);
                });

                table.draw();
            },

            error: function (xhr) {
                console.error(xhr.responseText);
                alert("Terjadi kesalahan saat mengambil data");
            },

            complete: function () {
                $("#filterBtn").prop("disabled", false).text("Tampilkan Data");
            },
        });
    }

    /* ===========================
       FILTER BUTTON
    ============================ */
    $("#filterBtn").on("click", function () {
        loadData();
    });

    /* ===========================
       TAB JENIS RAWAT
    ============================ */
    $(".tabJenis").on("click", function () {
        const jenis = $(this).data("jenis");
        $("#jenisRawat").val(jenis);

        // style active tab
        $(".tabJenis")
            .removeClass("bg-white text-blue-600 shadow-sm")
            .addClass("text-slate-600");

        $(this)
            .addClass("bg-white text-blue-600 shadow-sm")
            .removeClass("text-slate-600");

        // header kolom terakhir & visibilitas kolom anestesi
        if (jenis === "ranap") {
            $("#kolomTerakhirHeader").text("Bangsal");
            colAnestesi.visible(false);
        } else if (jenis === "ralan") {
            $("#kolomTerakhirHeader").text("Poliklinik");
            colAnestesi.visible(false);
        } else if (jenis === "operasi") {
            $("#kolomTerakhirHeader").text("Operator");
            colAnestesi.visible(true);
        } else if (jenis === "radiologi") {
            $("#kolomTerakhirHeader").text("Ruangan");
            colAnestesi.visible(false);
        } else if (jenis === "laboratorium") {
            $("#kolomTerakhirHeader").text("Ruangan");
            colAnestesi.visible(false);
        }

        // reload data
        const start = $("#start").val();
        const end = $("#end").val();

        if (start && end) {
            loadData();
        } else {
            table.clear().draw();
        }
    });
});

$("#exportBtn").on("click", function () {

    const start = $("#start").val();
    const end = $("#end").val();
    const jenis = $("#jenisRawat").val();

    if (!start || !end) {
        alert("Pilih tanggal dulu");
        return;
    }

    window.open(
        "/manajemen/detailtindakan/export/" + jenis + "?start=" + start + "&end=" + end,
        "_blank"
    );
});