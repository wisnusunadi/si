<script>
import produk from './produk.vue';
export default {
    components: {
        produk,
    },
    props: ['produk', 'search'],
    data() {
        return {
            headers: [
                {
                    text: 'No Order',
                    value: 'no_order'
                },
                {
                    text: 'Nama Pemilik',
                    value: 'pemilik'
                },
                {
                    text: 'Nama Pemilik Sertifikat',
                    value: 'pemilik_sertif'
                },
                {
                    text: 'No SO',
                    value: 'so'
                },
                {
                    text: 'Customer',
                    value: 'customer'
                },
                {
                    text: 'Tanggal Kalibrasi',
                    value: 'tgl_transfer'
                },
                {
                    text: 'Jenis Transaksi',
                    value: 'jenis_transaksi'
                },
                {
                    text: 'Aksi',
                    value: 'aksi'
                }
            ],
            modal: false,
            selectedProduk: null,
        }
    },
    methods: {
        detailProduk(data) {
            this.modal = true;
            this.selectedProduk = data;
            this.$nextTick(() => {
                $('.modalRiwayatProduk').modal('show');
            })
        }
    },
}
</script>
<template>
    <div>
        <produk v-if="modal" :headerSO="selectedProduk" />
        <data-table :headers="headers" :items="produk" :search="search">
            <template #item.aksi="{item}">
                <div>
                    <button class="btn btn-outline-primary" @click="detailProduk(item)">
                        <i class="fa fa-eye"></i>
                        Detail
                    </button>
                </div>
            </template>
        </data-table> 
    </div>
</template>