<script>
import moment from 'moment'
import modalGenerate from './modalGenerate.vue';
export default {
    props: ['dataTable'],
    components: {
        modalGenerate
    },
    data() {
        return {
            showModal: false,
            detailData: {},
        }
    },
    methods: {
        periode(date) {
            // change to yyyy-mm-dd format
            date = date.split(' ').reverse().join('-');
            return moment(date).lang('id').format('MMMM');
        },
        selisih(selisih, tanggal_selesai) {
            if (tanggal_selesai) {
                if (selisih > 0) {
                    return `<span class="badge badge-danger">Lebih ${selisih} hari</span>`
                } else if (selisih < 0) {
                    return `<span class="badge badge-warning">Kurang ${selisih} hari</span>`
                } else {
                    return `<span class="badge badge-success">Tepat Waktu</span>`
                }
            }
        },
        detail(data) {
            this.detailData = JSON.parse(JSON.stringify(data))
            this.showModal = true
            this.$nextTick(() => {
                $('.modalGenerate').modal('show')
            })
        }
    },
}
</script>
<template>
    <div>
        <modalGenerate v-if="showModal" :dataGenerate="detailData" @close="showModal = false"></modalGenerate>
        <table class="table">
            <thead>
                <tr>
                    <th>Periode</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>No BPPB</th>
                    <th>Nama Produk</th>
                    <th>Jumlah Rakit</th>
                    <th>Aksi Produk</th>
                </tr>
            </thead>
            <tbody v-if="dataTable.length > 0">
                <tr v-for="(data, idx) in dataTable" :key="idx">
                    <td>{{ periode(data.tanggal_mulai) }}</td>
                    <td>{{ data.tgl_mulai }}</td>
                    <td>
                        <span>{{ data.tgl_selesai }}</span> <br>
                        <span v-html="selisih(data.selisih, data.tanggal_selesai)"></span>
                        
                    </td>
                    <td>{{ data.no_bppb }}</td>
                    <td>{{ data.nama_produk }}</td>
                    <td>
                        <span>{{ data.jumlah_unit }}</span><br>
                        <span class="badge badge-dark">{{ data.kurang_rakit }}</span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" @click="detail(data)">
                            <i class="fa fa-barcode"></i>
                            Generate Nomor Seri
                        </button>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data</td>
                </tr>
            </tbody>
        </table>

    </div>
</template>