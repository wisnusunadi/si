<script>
import status from '../../components/status.vue';
export default {
    props: ['dataTable'],
    components: {
        status,
    },
    methods: {
        kirim(id) {
            this.$swal({
                title: 'Apakah anda yakin?',
                text: "Anda akan mengirim data permintaan ini",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$swal({
                        title: 'Berhasil!',
                        text: 'Data berhasil dikirim',
                        icon: 'success',
                    })
                }
            })
        }
    },
}
</script>
<template>
    <div>
        <table class="table text-center">
            <thead>
                <tr>
                    <th rowspan="2">Tanggal Mulai</th>
                    <th rowspan="2">Tanggal Selesai</th>
                    <th rowspan="2">Nama Produk</th>
                    <th colspan="2">Jumlah</th>
                    <th rowspan="2">Status</th>
                    <th rowspan="2">Aksi</th>
                </tr>
                <tr>
                    <th>Selesai</th>
                    <th>Belum Selesai</th>
                </tr>
            </thead>
            <tbody v-if="dataTable.length > 0">
                <tr v-for="(data, index) in dataTable" :key="index">
                    <td>{{ dateFormat(data.tanggal_mulai) }}</td>
                    <td>{{ dateFormat(data.tanggal_selesai) }}</td>
                    <td>{{ data.nama_produk }}</td>
                    <td>{{ data.jumlah_selesai }}</td>
                    <td>{{ data.jumlah_belum_selesai }}</td>
                    <td>
                        <status :status="data.status" />
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-info" v-if="data.status != 'menunggu'"
                            @click="kirim(data.id)">
                            <i class="fas fa-paper-plane"></i>
                            Kirim
                        </button>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>