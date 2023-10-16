<script>
import axios from 'axios'
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
        async detail(data) {
            try {
                const { data: mapArray } = await axios.get(`/api/gbj/rw/riwayat_permintaan/${data.id}`)

                this.dataSelected = {
                    header: data,
                    data: mapArray
                }

                this.showModal = true
                this.$nextTick(() => {
                    $('.modalProduk').modal('show')
                })
            } catch (error) {
                
            }
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
                    <th>Tanggal Transfer</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody v-if="dataTable.length > 0">
                <tr v-for="(data, idx) in dataTable" :key="idx">
                    <td>{{ data.no_urut }}</td>
                    <td>{{ data.nama }}</td>
                    <td>{{ data.jumlah }}</td>
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