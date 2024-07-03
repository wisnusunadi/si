<script>
import DataTable from '../../../components/DataTable.vue';
import modaltransfer from './modalTransfer'
export default {
    props: ['dataTable'],
    components: {
        DataTable,
        modaltransfer
    },
    data() {
        return {
            headers: [
                {
                    text: 'No. Urut',
                    value: 'no_urut'
                },
                {
                    text: 'Nama Produk',
                    value: 'nama'
                },
                {
                    text: 'Jumlah',
                    value: 'jumlah'
                },
                {
                    text: 'Aksi',
                    value: 'aksi'
                }
            ],
            search: '',
            dataSelected: null,
            showModalTransfer: false,
        }
    },
    methods: {
        // kirim data
        kirim(data) {
            this.dataSelected = JSON.parse(JSON.stringify(data));
            this.dataSelected.item = this.dataSelected.item.map(item => {
                return {
                    ...item,
                    layout: {
                        id: 7,
                        label: 'Blok B',
                    }
                }
            })
            this.showModalTransfer = true;
            this.$nextTick(() => {
                $('.modalDetailTransfer').modal('show');
            });
        }
    },
}
</script>
<template>
    <div>
        <modaltransfer :dataSelected="dataSelected" v-if="showModalTransfer" @closeModal="showModalTransfer = false" />
        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight">
                <input type="text" v-model="search" class="form-control" placeholder="Cari...">
            </div>
        </div>
        <data-table :headers="headers" :items="dataTable" :search="search">
            <template #item.aksi="{ item }">
                <button class="btn btn-sm btn-outline-primary" @click="kirim(item)">
                    <i class="fa fa-check"></i>
                    Terima</button>
                <button class="btn btn-sm btn-outline-info">
                    <i class="fa fa-print"></i>
                    Cetak BPBJ
                </button>
            </template>
        </data-table>
    </div>
</template>