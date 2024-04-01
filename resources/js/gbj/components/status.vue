<script>
export default {
    props: ['status'],
    methods: {
        statusProses(status) {
            switch (status) {
                case 'menunggu':
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
                case 'barangsiapdiambil':
                    return {
                        class: 'badge-warning',
                        text: 'Barang Siap Diambil'
                    }
                case 'barangkeluar':
                    return {
                        class: 'badge-success',
                        text: 'Barang Keluar'
                    }
                case 'barangdisiapkan':
                    return {
                        class: 'badge-info',
                        text: 'Barang Disiapkan'
                    }
                case 'tolak':
                    return {
                        class: 'badge-danger',
                        text: 'Tolak'
                    }
                case 'peminjaman':
                    return {
                        class: 'badge-info',
                        text: 'Peminjaman'
                    }
                case 'permintaan':
                    return {
                        class: 'badge-primary',
                        text: 'Permintaan'
                    }
                case 'batal':
                    return {
                        class: 'badge-danger',
                        text: 'Batal'
                    }

                default:
                    return {
                        class: 'badge-primary',
                        text: status
                    }
            }
        }
    },
}
</script>
<template>
    <div>
        <span class="badge" :class="statusProses(status).class">{{ statusProses(status).text }}</span>
    </div>
</template>