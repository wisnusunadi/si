<script>
export default {
    props: ['status'],
    methods: {
        statusClassText(status) {
            switch (status) {
                case 'Proses':
                    return {
                        class: 'badge-warning',
                        text: 'Menunggu Proses Gudang'
                    }
                case 'belum_dikerjakan':
                    return {
                        class: 'badge-danger',
                        text: 'Belum Dikerjakan'
                    }
                case 'sedang_dikerjakan':
                    return {
                        class: 'badge-warning',
                        text: 'Sedang Dikerjakan'
                    }
                case 'selesai':
                    return {
                        class: 'badge-success',
                        text: 'Selesai'
                    }
                case 'batal':
                    return {
                        class: 'badge-danger',
                        text: 'Batal'
                    }
                case 'menunggu_persetujuan_atasan':
                    return {
                        class: 'badge-warning',
                        text: 'Menunggu Persetujuan Atasan'
                    }
                case 'permintaan_ditolak_atasan':
                    return {
                        class: 'badge-danger',
                        text: 'Permintaan Ditolak Atasan'
                    }
                case 'menunggu_persetujuan_gudang':
                    return {
                        class: 'badge-warning',
                        text: 'Menunggu Persetujuan Gudang'
                    }
                case 'permintaan_gagal_diacc':
                    return {
                        class: 'badge-danger',
                        text: 'Permintaan Gagal Diacc'
                    }
                case 'permintaan_ditolak_gudang':
                    return {
                        class: 'badge-danger',
                        text: 'Permintaan Ditolak Gudang'
                    }
                case 'permintaan': 
                    return {
                        class: 'badge-primary',
                        text: 'Permintaan'
                    }
                case 'peminjaman':
                    return {
                        class: 'badge-info',
                        text: 'Peminjaman'
                    }
                default:
                    return {
                        class: 'badge-primary',
                        text: 'Permintaan Baru'
                    }
            }
        }
    },
}
</script>
<template>
    <div>
        <span class="badge" :class="statusClassText(status).class">{{ statusClassText(status).text }}</span>
    </div>
</template>