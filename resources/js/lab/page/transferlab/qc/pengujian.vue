<script>
import modal from './modal.vue';
export default {
    components: {
        modal,
    },
    props: ['produk', 'search'],
    data() {
        return {
            headers: [
                {
                    text: 'No',
                    value: 'no',
                },
                {
                    text: 'No SO',
                    value: 'no_so',
                },
                {
                    text: 'Nama Produk',
                    value: 'nama',
                },
                {
                    text: 'Tanggal Pengujian',
                    value: 'tgl_pengujian',
                },
                {
                    text: 'Tanggal Selesai',
                    value: 'tgl_selesai_uji',
                },
                {
                    text: 'Jumlah',
                    value: 'jumlah_pengujian',
                },
                {
                    text: 'Aksi',
                    value: 'aksi',
                }
            ],
            detailProduk: {},
            showModal: false,
        }
    },
    methods: {
        detail(item) {
            this.detailProduk = item;
            this.showModal = true;
            this.$nextTick(() => {
                $('.modalPengujian').modal('show');
            })
        },
    },
}
</script>
<template>
    <div>
        <modal :detail="detailProduk" v-if="showModal" @close="showModal = false"></modal>
        <data-table :headers="headers" :items="produk" :search="search" v-if="!$store.state.loading">
            <template #item.aksi="{ item }">
                <button class="btn btn-outline-primary btn-sm" @click="detail(item)">
                    <i class="fa fa-eye"></i>
                    Detail
                </button>
            </template>
        </data-table>
                <div class="spinner-border spinner-border-sm" role="status" v-else>
                <span class="sr-only">Loading...</span>
            </div>
    </div>
</template>