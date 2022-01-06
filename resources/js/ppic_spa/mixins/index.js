export default {
    change_status(status) {
        if (status == 6) return 'penyusunan';
        else if (status == 7) return 'pelaksanaan';
        else if (status == 8) return 'selesai';
    },

    change_state(state) {
        if (state == 17) return 'perencanaan';
        else if (state == 18) return 'persetujuan';
        else if (state == 19) return 'perubahan'
    },

    convertJadwal: function (jadwal) {
        return jadwal.length == 0
            ? []
            : jadwal.map((item) => ({
                id: item.id,
                title: `${item.produk.produk.nama} ${item.produk.nama}`,
                start: item.tanggal_mulai,
                end: item.tanggal_selesai,
                backgroundColor: item.warna,
                borderColor: item.warna,

                produk_id: item.produk_id,
                jumlah: item.jumlah,
                progres: item.noseri_count
            }));
    },

}