<script>
import DataTable from '../../../components/DataTable.vue'
import Status from '../../../components/status.vue'
import ModalTambah from './modalTambah.vue'
export default {
    components: {
        DataTable,
        Status,
        ModalTambah,
    },
    props: ['dataTable'],
    data() {
        return {
            search: '',
            headers: [
                {
                    text: 'No Urut',
                    value: 'no_urut',
                },
                {
                    text: 'Jumlah',
                    value: 'jumlah',
                },
                {
                    text: 'Status',
                    value: 'status',
                },
                {
                    text: 'Aksi',
                    value: 'aksi',
                    sortable: false,
                },
            ],
            modalTambah: false,
        }
    },
    methods: {
        tambah() {
            this.modalTambah = true;
            this.$nextTick(() => {
                $('.modalTambah').modal('show')
            })
        }
    },
}
</script>
<template>
    <div>
        <ModalTambah v-if="modalTambah" @closeModal="modalTambah = false" />
        <div class="card">
            <div class="card-body">
                <div class="d-flex bd-highlight">
                    <div class="p-2 flex-grow-1 bd-highlight">
                        <button class="btn btn-primary" @click="tambah">
                            <i class="fas fa-plus"></i>
                            Tambah
                        </button>
                    </div>
                    <div class="p-2 bd-highlight">
                        <input type="text" v-model="search" class="form-control" placeholder="Cari...">
                    </div>
                </div>
                <DataTable :items="dataTable" :headers="headers" :search="search">
                    <template #item.status="{ item }">
                        <Status :status="item.status" />
                    </template>
                    <template #item.aksi="{ item }">
                        <button class="btn btn-sm btn-outline-info">
                            <i class="fas fa-info-circle"></i>
                            Detail
                        </button>
                    </template>
                </DataTable>
            </div>
        </div>
    </div>
</template>