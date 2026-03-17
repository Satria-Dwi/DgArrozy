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

    const colkdPerawatan = table.column(4);
    const colnmperawatan = table.column(5);
    const colkodedokter = table.column(6);
    const colnamadokter = table.column(7);
    const coljam = table.column(9);
    const colpoly = table.column(11);
    const colAnestesi = table.column(12);
    const colpngJawab = table.column(10);
    const colLab = table.column(13);
    const coldokterLab = table.column(14);
    const colradio = table.column(15);
    const coldokterRadiologi = table.column(16);

    colAnestesi.visible(false); // default hide
    colkodedokter.visible(true);
    colnamadokter.visible(true);
    colkdPerawatan.visible(true);
    colnmperawatan.visible(true);
    coljam.visible(true);
    colpoly.visible(true);
    colpngJawab.visible(true);
    colLab.visible(false);
    coldokterLab.visible(false);
    colradio.visible(false);
    coldokterRadiologi.visible(false);

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
                            "-",
                            row.tgl_operasi ?? "-",
                            row.jam_operasi ?? "-",
                            row.png_jawab ?? "-",
                            row.operator1 ?? "-",
                            row.dokter_anestesi ?? "-",
                            "-",
                            "-",
                            "-",
                            "-",
                            "-"
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
                            "-",
                            "-",
                            "-",
                            "-",
                            "-",// dummy anestesi
                        ];
                    }

                    /* ===========================
                       Laboratorium
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
                            "-",
                            "-",
                            "-",
                            "-",  
                            "-",            // dummy anestesi
                        ];
                    } else if (jenis === "mcu") {
                        dataRow = [
                            "",                         // nomor urut otomatis
                            row.no_rawat ?? "-",
                            row.no_rkm_medis ?? "-",
                            row.nm_pasien ?? "-",
                            "-",  
                            "-",      
                            "-",                        // nm_perawatan / dummy
                            row.dokter_mcu ?? "-",                        // dokter Lab / dummy
                            row.tgl_registrasi ?? "-",                        // jam / dummy
                            "-",                        // poliklinik / bangsal / ruangan
                            "-",                        // penjab
                            "-",                        // operator / dummy
                            "-",                        // anestesi / dummy
                            row.ada_lab ?? "Tidak",    // Laboratorium Ya/Tidak
                            row.dokter_lab ?? "-",     // dokter Lab
                            row.ada_radiologi ?? "Tidak", // Radiologi Ya/Tidak
                            row.dokter_radiologi ?? "-",  // dokter Radiologi
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
                            "-",
                            "-",
                            "-",
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
            colkodedokter.visible(true);
            colnamadokter.visible(true);
            colAnestesi.visible(false);
            colkdPerawatan.visible(true);  // kolom kode perawatan tidak dipakai
            colnmperawatan.visible(true);  // kolom nama perawatan tidak dipakai
            coljam.visible(true);           // kolom jam tidak dipakai
            colpoly.visible(true);         // kolom poli/bangsal tidak dipakai
            colpngJawab.visible(true);     // kolom penjab tidak dipakai
            colLab.visible(false);          // kolom lab tidak dipakai
            coldokterLab.visible(false);    // kolom dokter lab tidak dipakai
            colradio.visible(false);        // kolom radiologi tidak dipakai
            coldokterRadiologi.visible(false); 
        } else if (jenis === "ralan") {
            $("#kolomTerakhirHeader").text("Poliklinik");
            colkodedokter.visible(true);
            colnamadokter.visible(true);
            colAnestesi.visible(false);
            colkdPerawatan.visible(true);  // kolom kode perawatan tidak dipakai
            colnmperawatan.visible(true);  // kolom nama perawatan tidak dipakai
            coljam.visible(true);           // kolom jam tidak dipakai
            colpoly.visible(true);         // kolom poli/bangsal tidak dipakai
            colpngJawab.visible(true);     // kolom penjab tidak dipakai
            colLab.visible(false);          // kolom lab tidak dipakai
            coldokterLab.visible(false);    // kolom dokter lab tidak dipakai
            colradio.visible(false);        // kolom radiologi tidak dipakai
            coldokterRadiologi.visible(false); 
        } else if (jenis === "operasi") {
            $("#kolomTerakhirHeader").text("Operator");
            colAnestesi.visible(true);
            colkodedokter.visible(false);
            colnamadokter.visible(false);
            colkdPerawatan.visible(true);  // kolom kode perawatan tidak dipakai
            colnmperawatan.visible(true);  // kolom nama perawatan tidak dipakai
            coljam.visible(true);           // kolom jam tidak dipakai
            colpoly.visible(true);         // kolom poli/bangsal tidak dipakai
            colpngJawab.visible(true);     // kolom penjab tidak dipakai
            colLab.visible(false);          // kolom lab tidak dipakai
            coldokterLab.visible(false);    // kolom dokter lab tidak dipakai
            colradio.visible(false);        // kolom radiologi tidak dipakai
            coldokterRadiologi.visible(false); 
        } else if (jenis === "radiologi") {
            $("#kolomTerakhirHeader").text("Ruangan");
            colkodedokter.visible(true);
            colnamadokter.visible(true);
            colAnestesi.visible(false);
            colkdPerawatan.visible(true);  // kolom kode perawatan tidak dipakai
            colnmperawatan.visible(true);  // kolom nama perawatan tidak dipakai
            coljam.visible(true);           // kolom jam tidak dipakai
            colpoly.visible(true);         // kolom poli/bangsal tidak dipakai
            colpngJawab.visible(true);     // kolom penjab tidak dipakai
            colLab.visible(false);          // kolom lab tidak dipakai
            coldokterLab.visible(false);    // kolom dokter lab tidak dipakai
            colradio.visible(false);        // kolom radiologi tidak dipakai
            coldokterRadiologi.visible(false); 
        } else if (jenis === "laboratorium") {
            $("#kolomTerakhirHeader").text("Ruangan");
            colkodedokter.visible(true);
            colnamadokter.visible(true);
            colAnestesi.visible(false);
            colkdPerawatan.visible(true);  // kolom kode perawatan tidak dipakai
            colnmperawatan.visible(true);  // kolom nama perawatan tidak dipakai
            coljam.visible(true);           // kolom jam tidak dipakai
            colpoly.visible(true);         // kolom poli/bangsal tidak dipakai
            colpngJawab.visible(true);     // kolom penjab tidak dipakai
            colLab.visible(false);          // kolom lab tidak dipakai
            coldokterLab.visible(false);    // kolom dokter lab tidak dipakai
            colradio.visible(false);        // kolom radiologi tidak dipakai
            coldokterRadiologi.visible(false); 
        } else if (jenis === "mcu") {
            colkodedokter.visible(false);   // kode dokter MCU
            colnamadokter.visible(true);   // nama dokter MCU
            colAnestesi.visible(false);    // kolom anestesi tidak dipakai
            colkdPerawatan.visible(false);  // kolom kode perawatan tidak dipakai
            colnmperawatan.visible(false);  // kolom nama perawatan tidak dipakai
            coljam.visible(false);           // kolom jam tidak dipakai
            colpoly.visible(false);         // kolom poli/bangsal tidak dipakai
            colpngJawab.visible(false);     // kolom penjab tidak dipakai
            colLab.visible(true);          // kolom lab tidak dipakai
            coldokterLab.visible(true);    // kolom dokter lab tidak dipakai
            colradio.visible(true);        // kolom radiologi tidak dipakai
            coldokterRadiologi.visible(true); // kolom dokter radiologi tidak dipakai
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