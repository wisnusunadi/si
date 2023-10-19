<script>
import DataTable from '../../../components/DataTable.vue';
export default {
    props: ['dataRiwayat'],
    components: {
        DataTable,
    },
    data() {
        return {
            headers: [
                {
                    text: 'id',
                    value: 'id',
                    sortable: false,
                },
                {
                    text: 'No. Seri',
                    value: 'noseri'
                },
                {
                    text: 'Tanggal Dibuat',
                    value: 'tanggal_dibuat'
                },
                {
                    text: 'No BPPB',
                    value: 'no_bppb'
                },
                {
                    text: 'Nama Produk',
                    value: 'nama_produk'
                },
                {
                    text: 'Aksi',
                    sortable: false,
                    value: 'aksi'
                }
            ],
            search: '',
            checkAll: false,
        }
    },
}
</script>
<template>
    <div>
        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight">
                <input type="text" v-model="search" class="form-control" placeholder="Cari...">
            </div>
        </div>
        <DataTable :headers="headers" :items="dataRiwayat" :search="search">
            <template #header.id>
                <input type="checkbox" @click="checkAll">
            </template>

            <template #item.id = "{ item }">
                <input type="checkbox">
            </template>
            <template #item.aksi = "{ item }">
                <button class="btn btn-outline-primary">
                    <i class="fa fa-print"></i>
                    Cetak Nomor Seri
                </button>
            </template>
        </DataTable>
    </div>
</template>