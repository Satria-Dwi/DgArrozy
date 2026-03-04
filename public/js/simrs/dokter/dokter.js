// dashboard-medis.js
// 🔹 Semua fungsi diisolasi agar aman, tetap bisa dipanggil dari HTML jika perlu

(() => {
    // ===== GLOBAL VAR =====
    let chartPasienDokter = null;
    let filterAktif = false;
    let currentPageRalan = 1;
    let currentPageRanap = 1;
    let currentPageOperasi = 1;

    // ===== UTILITY =====
    function formatDate(date) {
        return date.toISOString().split('T')[0];
    }

    function getRangeTanggal() {
        return {
            tgl_awal: document.getElementById('tglAwal')?.value,
            tgl_akhir: document.getElementById('tglAkhir')?.value
        };
    }

    function setDefaultTanggalHariIni() {
        const today = formatDate(new Date());
        const tglAwal = document.getElementById('tglAwal');
        const tglAkhir = document.getElementById('tglAkhir');

        if (tglAwal && !tglAwal.value) tglAwal.value = today;
        if (tglAkhir && !tglAkhir.value) tglAkhir.value = today;
    }

    // ===== MODAL =====
    function closeModalDetail(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function setTextModal(modal, id, value = '-') {
        const el = modal.querySelector('#' + id);
        if (el) el.innerText = value;
    }

    function showRow(modal, id, show = true) {
        const el = modal.querySelector('#' + id);
        if (el) el.closest('p').style.display = show ? '' : 'none';
    }

    function showDetailPasien(noRawat, jenis = 'ralan') {
        if (!noRawat) return;
        const encoded = encodeURIComponent(noRawat);

        const config = {
            ralan: {
                url: `/dokter/pasien-detail-ralan/${encoded}`,
                modalId: 'modalDetailRalan',
                before: (modal) => {
                    // sembunyikan ranap
                    showRow(modal, 'detailKamar', false);
                    showRow(modal, 'detailTglMasuk', false);
                    showRow(modal, 'detailJamMasuk', false);
                    showRow(modal, 'detailDiagnosaAwal', false);
                    showRow(modal, 'detailDiagnosaAkhir', false);
                    // tampilkan ralan
                    showRow(modal, 'detailPoli', true);
                    showRow(modal, 'detailDokter', true);
                    showRow(modal, 'detailTglKeluar', true);
                    showRow(modal, 'detailJamKeluar', true);
                },
                extra: (modal, d) => {
                    setTextModal(modal, 'detailPoli', d.nm_poli);
                    setTextModal(modal, 'detailDokter', d.nm_dokter);
                    setTextModal(modal, 'detailTglKeluar', d.tgl_keluar);
                    setTextModal(modal, 'detailJamKeluar', d.jam_keluar);
                }
            },
            ranap: {
                url: `/dokter/pasien-detail-ranap/${encoded}`,
                modalId: 'modalDetailRanap',
                before: (modal) => {
                    showRow(modal, 'detailKamar', true);
                    showRow(modal, 'detailTglMasuk', true);
                    showRow(modal, 'detailJamMasuk', true);
                    showRow(modal, 'detailDiagnosaAwal', true);
                    showRow(modal, 'detailDiagnosaAkhir', true);
                    showRow(modal, 'detailPoli', false);
                    showRow(modal, 'detailDokter', false);
                },
                extra: (modal, d) => {
                    setTextModal(modal, 'detailKamar', d.kd_kamar);
                    setTextModal(modal, 'detailTglMasuk', d.tgl_masuk);
                    setTextModal(modal, 'detailJamMasuk', d.jam_masuk);
                    setTextModal(modal, 'detailTglKeluar', d.tgl_keluar);
                    setTextModal(modal, 'detailJamKeluar', d.jam_keluar);
                    setTextModal(modal, 'detailDiagnosaAwal', d.diagnosa_awal);
                    setTextModal(modal, 'detailDiagnosaAkhir', d.diagnosa_akhir);
                }
            }
        };

        const cfg = config[jenis];
        if (!cfg) return;
        const modal = document.getElementById(cfg.modalId);
        if (!modal) return;

        cfg.before(modal);

        fetch(cfg.url, { headers: { Accept: 'application/json' } })
            .then(r => r.ok ? r.json() : Promise.reject(r.status))
            .then(d => {
                // data umum
                setTextModal(modal, 'detailNoRawat', d.no_rawat);
                setTextModal(modal, 'detailNoRM', d.no_rkm_medis);
                setTextModal(modal, 'detailNama', d.nm_pasien);
                setTextModal(modal, 'detailJK', d.jk);
                setTextModal(modal, 'detailUmur', d.umur);
                setTextModal(modal, 'detailAlamat', d.alamat);
                setTextModal(modal, 'detailPnjwb', d.png_jawab ?? '-');
                setTextModal(modal, 'detailDokter', d.nm_dokter ?? '-');

                cfg.extra(modal, d);

                modal.classList.remove('hidden');
                modal.classList.add('flex');
            })
            .catch(err => {
                console.error('Fetch detail pasien gagal:', err);
                alert('Detail pasien gagal dimuat');
            });
    }

    // ===== TOTAL PASIEN =====
    function loadTotalPasienDokter() {
        fetch("/dokter/total-pasien")
            .then(res => res.json())
            .then(res => {
                const el = document.getElementById('totalPasienDokter');
                if (el && res.total_pasien !== undefined) el.innerText = res.total_pasien;
            });
    }

    function loadRawatInap() {
        const params = new URLSearchParams();
        if (filterAktif) {
            const { tgl_awal, tgl_akhir } = getRangeTanggal();
            if (tgl_awal) params.append('tgl_awal', tgl_awal);
            if (tgl_akhir) params.append('tgl_akhir', tgl_akhir);
        }

        fetch(`/dokter/total-rawat-inap?${params}`)
            .then(res => res.json())
            .then(data => {
                const el = document.getElementById("jumlahRanap");
                if (el) el.textContent = data.jumlah_pasien_rawat_inap ?? 0;
            })
            .catch(err => console.error(err));
    }

    function loadRawatJalan() {
        const el = document.getElementById('rawatJalan');
        if (!el) return;

        const params = new URLSearchParams();
        if (filterAktif) {
            const { tgl_awal, tgl_akhir } = getRangeTanggal();
            if (tgl_awal) params.append('tgl_awal', tgl_awal);
            if (tgl_akhir) params.append('tgl_akhir', tgl_akhir);
        }

        fetch(`/dokter/total-rawat-jalan?${params}`)
            .then(res => res.json())
            .then(res => {
                el.innerText = res.total_pasien_rawat_jalan ?? 0;
            })
            .catch(err => console.error(err));
    }

    function loadTotalOperasiDokter() {
        const totalElem = document.getElementById('totalOperasi');
        if (!totalElem) return;

        const params = new URLSearchParams();
        if (filterAktif) {
            const { tgl_awal, tgl_akhir } = getRangeTanggal();
            if (tgl_awal) params.append('tgl_awal', tgl_awal);
            if (tgl_akhir) params.append('tgl_akhir', tgl_akhir);
        }

        fetch(`/dokter/operasi?${params}`)
            .then(res => res.json())
            .then(res => {
                if (res.error) totalElem.innerText = 0;
                else totalElem.innerText = res.total_operasi ?? 0;
            })
            .catch(err => {
                console.error(err);
                totalElem.innerText = 0;
            });
    }

    // ===== CHART =====
    function loadChartPasienPerDokter() {
        fetch("/dokter/chart-pasien")
            .then(res => res.json())
            .then(res => {
                const options = {
                    series: [{ name: 'Pasien', data: res.data }],
                    chart: { height: 60, sparkline: { enabled: true }, toolbar: { show: false }, animations: { enabled: true, easing: 'easeinout', speed: 300, animateGradually: { enabled: false }, dynamicAnimation: { enabled: false } } },
                    stroke: { curve: 'smooth', width: 3 },
                    markers: { size: 4, hover: { size: 6 } },
                    dataLabels: { enabled: true, formatter: val => val, offsetY: -6, style: { fontSize: '10px', fontWeight: 600, colors: ['#000'] } },
                    tooltip: { enabled: true, theme: 'dark', x: { show: false }, y: { title: { formatter: () => '' }, formatter: val => val + ' pasien' }, style: { fontSize: '12px' }, marker: { show: true } }
                };

                if (!chartPasienDokter) {
                    chartPasienDokter = new ApexCharts(document.querySelector("#chartpasienperdokter"), options);
                    chartPasienDokter.render();
                } else chartPasienDokter.updateSeries(options.series);

                const labelWrap = document.getElementById('labelHari');
                labelWrap.innerHTML = '';
                res.labels.forEach(item => {
                    const isToday = item.full === new Date().toISOString().slice(0, 10);
                    labelWrap.innerHTML += `<div class="flex flex-col items-center text-white ${isToday ? 'text-blue-400 font-semibold' : ''}">
                        <span>${item.hari.substring(0,3)}</span>
                        <span class="leading-none text-[9px]">/ ${item.tgl}</span>
                    </div>`;
                });
            });
    }

    // ===== FILTER =====
    function triggerAutoFilter() {
        const { tgl_awal, tgl_akhir } = getRangeTanggal();
        if (tgl_awal && tgl_akhir) {
            filterAktif = true;
            reloadTables();
            loadTotalOperasiDokter();
        }
    }

    function applyFilterTanggal() {
        const { tgl_awal, tgl_akhir } = getRangeTanggal();
        if (!tgl_awal || !tgl_akhir) return alert('Pilih tanggal awal dan akhir');
        filterAktif = true;
        loadRawatInap();
        loadRawatJalan();
        loadTotalOperasiDokter();
        reloadTables();
    }

    function resetFilter() {
        filterAktif = false;
        document.getElementById('tglAwal').value = '';
        document.getElementById('tglAkhir').value = '';
        loadRawatInap();
        loadRawatJalan();
        loadTotalOperasiDokter();
        reloadTables();
    }

    function reloadTables() {
        if (typeof loadtableRawatJalan === 'function') loadtableRawatJalan(1);
        if (typeof loadtableRawatInap === 'function') loadtableRawatInap(1);
        if (typeof loadTableOperasi === 'function') loadTableOperasi(1);
    }

    // ===== DASHBOARD =====
    function loadDashboardMedis() {
        loadTotalPasienDokter();
        loadChartPasienPerDokter();

        if (!filterAktif) setDefaultTanggalHariIni();

        // Cek spesialis (PHP check di Blade, jika mau JS cek, bisa disesuaikan)
        // loadTotalOperasiDokter();
        // loadTableOperasi(1);

        loadRawatInap();
        loadRawatJalan();
        if (typeof loadtableRawatJalan === 'function') loadtableRawatJalan(1);
        if (typeof loadtableRawatInap === 'function') loadtableRawatInap(1);
    }

    // ===== EXPORT KE GLOBAL =====
    window.showDetailPasien = showDetailPasien;
    window.closeModalDetailRalan = () => closeModalDetail('modalDetailRalan');
    window.closeModalDetailRanap = () => closeModalDetail('modalDetailRanap');
    window.loadDashboardMedis = loadDashboardMedis;
    window.applyFilterTanggal = applyFilterTanggal;
    window.resetFilter = resetFilter;
    window.triggerAutoFilter = triggerAutoFilter;

})();