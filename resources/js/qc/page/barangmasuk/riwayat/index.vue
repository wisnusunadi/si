<script>
import modalRiwayat from './modalRiwayat.vue'
export default {
    components: {
        modalRiwayat
    },
    props: ['data'],
    data() {
        return {
            headers: [
                {
                    text: 'Tanggal Penyerahan',
                    value: 'tgl_penyerahan',
                },
                {
                    text: 'Waktu Penyerahan',
                    value: 'wkt_penyerahan',
                },
                {
                    text: 'No BPPB',
                    value: 'no_bppb',
                },
                {
                    text: 'Nama Produk',
                    value: 'produk',
                },
                {
                    text: 'Jumlah',
                    value: 'jml'
                },
                {
                    text: 'Aksi',
                    value: 'aksi'
                }
            ],
            search: '',
            showModal: false,
            detailRiwayat: {}
        }
    },
    methods: {
        detail(item) {
            this.detailRiwayat = item
            this.showModal = true
            this.$nextTick(() => {
                $('.modalRiwayat').modal('show')
            })
        }
    }
}
</script>
<template>
    <div>
        <modalRiwayat v-if="showModal" @closeModal="showModal = false" :detailRiwayat="detailRiwayat" />
        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight">
                <input type="text" class="form-control" v-model="search">
            </div>
        </div>
        <data-table :headers="headers" :items="data" :search="search">
            <template #item.aksi="{ item }">
                <button class="btn btn-sm btn-outline-info" @click="detail(item)">
                    <i class="fa fa-eye"></i>
                    Detail
                </button>
            </template>
        </data-table>
    </div>
</template>