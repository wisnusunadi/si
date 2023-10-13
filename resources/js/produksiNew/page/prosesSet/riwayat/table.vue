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
            const mapArray = data.produk.flatMap(product => product.noseri.map(noseri => ({
                ...noseri,
                nama: product.nama
            })));

            this.dataSelected = {
                header: data,
                data: mapArray
            }
            
            this.showModal = true
            this.$nextTick(() => {
                $('.modalProduk').modal('show')
            })
        },
        produkTransfer(data) {
            let result = 0
            // cek jumlah noseri every produk
            data.produk.forEach((produk) => {
                result += produk.noseri.length
            })
            return result
        }
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
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Nama Produk</th>
                    <th>Jumlah Transfer</th>
                    <th>Tanggal Transfer</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody v-if="dataTable.length > 0">
                <tr v-for="(data, idx) in dataTable" :key="idx">
                    <td>PRD-{{ data.urutan }}</td>
                    <td>{{ dateFormat(data.tgl_mulai) }}</td>
                    <td>{{ dateFormat(data.tgl_selesai) }}</td>
                    <td>{{ data.nama }}</td>
                    <td>{{ produkTransfer(data) }}</td>
                    <td>{{ dateFormat(data.tgl_transfer) }}</td>
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