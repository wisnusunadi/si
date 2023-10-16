<script>
import modalProduk from './modalProduk'
export default {
    components: {
        modalProduk,
    },
    data() {
        return {
            dataSelected: {},
            showModal: false,
        }
    },
    props: ['dataTable'],
    methods: {
        detail(data) {
            this.dataSelected = JSON.parse(JSON.stringify(data))
            
            this.showModal = true
            this.$nextTick(() => {
                $('.modalProduk').modal('show')
            })
        },
    },
}
</script>
<template>
    <div>
        <modalProduk v-if="showModal" @closeModal="showModal = false" :dataSelected="dataSelected" />
        <table class="table text-center">
            <thead>
                <tr>
                    <th>No Urut</th>
                    <th>Nama Produk</th>
                    <th>Jumlah Transfer</th>
                    <th>Tanggal Penerimaan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody v-if="dataTable.length > 0">
                <tr v-for="(data, idx) in dataTable" :key="idx">
                    <td>{{ data.no_urut }}</td>
                    <td>{{ data.nama }}</td>
                    <td>{{ data.item.length }}</td>
                    <td>{{ data.tgl_tf }}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-info" @click="detail(data)">
                            <i class="fas fa-info-circle"></i>
                            Detail
                        </button>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>