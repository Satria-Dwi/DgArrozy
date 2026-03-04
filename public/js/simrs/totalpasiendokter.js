
function loadTotalPasienDokter() {
    fetch("{{ url('/dashboard/dokter/total-pasien') }}")
        .then((res) => res.json())
        .then((res) => {
            if (res.total_pasien !== undefined) {
                document.getElementById("totalPasienDokter").innerText =
                    res.total_pasien;
            }
        });
}
