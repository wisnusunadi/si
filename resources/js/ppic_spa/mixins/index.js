export default {
    methods: {
        change_status(status) {
            if (status == 6) return 'penyusunan';
            else if (status == 7) return 'pelaksanaan';
            else if (status == 8) return 'selesai';
        },

        change_state(state) {
            if (state == 17) return 'perencanaan';
            else if (state == 18) return 'persetujuan';
            else if (state == 19) return 'perubahan'
        }
    }
}