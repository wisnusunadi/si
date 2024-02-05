<script>
import modal from './modal.vue';
export default {
    components: {
        modal,
    },
    props: ['produk'],
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
            search: '',
        }
    },
    methods: {
        detail(item) {
            this.detailProduk = item;
            this.showModal = true;
            this.$nextTick(() => {
                $('.modalPengujian').modal('show');
            })
        }
    },
}
</script>
<template>
    <div>
        <modal :detail="detailProduk" v-if="showModal" @close="showModal = false"></modal>
        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight">
                <input type="text" class="form-control" v-model="search" placeholder="Cari...">
            </div>
        </div>
        <data-table :headers="headers" :items="produk" :search="search">
            <template #item.aksi="{ item }">
                <button class="btn btn-outline-primary btn-sm" @click="detail(item)">
                    <i class="fa fa-eye"></i>
                    Detail
                </button>
            </template>
        </data-table>
    </div>
</template>