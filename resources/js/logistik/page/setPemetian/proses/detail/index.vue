<script>
import Header from '../../../../components/Header.vue';
import DataTable from '../../../../components/DataTable.vue';
import Generate from '../generate';
export default {
    components: {
        Header,
        DataTable,
        Generate,
    },
    data() {
        return {
            title: 'Detail Set Pemetian',
            breadcumbs: [
                {
                    name: 'Beranda',
                    link: '/logistik/dashboard'
                },
                {
                    name: 'Pemetian',
                    link: '/logistik/pengiriman/pemetian'
                },
                {
                    name: 'Detail Set Pemetian',
                    link: '#'
                }
            ],
            search: '',
            headers: [
                { text: 'No.', value: 'no', sortable: false },
                { text: 'No. Peti', value: 'no_peti' },
                { text: 'Tanggal Dibuat', value: 'tanggal_dibuat' },
                { text: 'Packer', value: 'packer' },
                { text: 'Aksi', value: 'action', sortable: false },
            ],
            items: [
                {
                    no_peti: 'PETI-1',
                    tanggal_dibuat: '31 Desember 2020',
                    packer: 'Packer 1',
                }
            ],
            showModalGenerate: false,
        }
    },
    methods: {
        openModalGenerate() {
            this.showModalGenerate = true;
            this.$nextTick(() => {
                $('.modalGenerate').modal('show');
            });
        },
    },
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <Generate v-if="showModalGenerate" @closeModal="showModalGenerate = false" />
        <div class="card">
            <div class="card-body">
                <div class="d-flex bd-highlight">
                    <div class="p-2 flex-grow-1 bd-highlight">
                        <button class="btn btn-primary" @click="openModalGenerate">
                            <i class="fas fa-plus"></i>
                            Tambah
                        </button>
                    </div>
                    <div class="p-2 bd-highlight">
                        <input type="text" class="form-control" v-model="search" placeholder="Cari...">
                    </div>
                </div>
                <DataTable :headers="headers" :items="items" :search="search">
                    <template #item.no="{ item, index }">
                        <div>
                            {{ index + 1 }}    
                        </div>
                    </template>
                    <template #item.action = "{item}">
                        <div>
                            <button class="btn btn-sm btn-outline-info">
                                <i class="fas fa-info-circle"></i>
                                Detail No. Seri Peti
                            </button>
                            <button class="btn btn-sm btn-outline-warning">
                                <i class="fas fa-pencil"></i>
                                Edit No. Seri Peti
                            </button>
                            <br>
                            <button class="btn btn-sm btn-outline-info my-1">
                                <i class="fas fa-eye"></i>
                                Lihat Packing List
                            </button>
                            <button class="btn btn-sm btn btn-outline-primary my-1">
                                <i class="fas fa-print"></i>
                                Cetak Packing List
                            </button>
                        </div>
                    </template>
                </DataTable>
            </div>
        </div>
    </div>
</template>