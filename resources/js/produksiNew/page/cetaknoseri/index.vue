<script>
import Header from '../../components/header.vue';
import cetakseri from './create.vue'
export default {
    components: {
        Header,
        cetakseri,
    },
    data() {
        return {
            title: 'Cetak No. Seri',
            breadcumbs: [
                {
                    name: 'Beranda',
                    link: '/produksi/dashboard'
                },
                {
                    name: 'Cetak No. Seri',
                    link: '#'
                }
            ],
            search: '',
            headers: [
                {
                    text: 'Id',
                    value: 'id',
                    sortable: false,
                },
                {
                    text: 'No. Seri',
                    value: 'noseri',
                },
                {
                    text: 'Keperluan',
                    value: 'keperluan',
                },
                {
                    text: 'Tanggal Buat',
                    value: 'tanggal_buat',
                },
                {
                    text: 'Aksi',
                    value: 'aksi',
                    sortable: false,
                }
            ],
            items: [
                {
                    id: 1,
                    noseri: 'TD16241D00133',
                    keperluan: 'Cetak Outer Box',
                    tanggal_buat: '23 September 2021',
                }
            ],
            noSeriSelected: [],
            showModal: false,
        }
    },
    methods: {
        openModalCreate() {
            this.showModal = true;
            this.$nextTick(() => {
                $('.modalcetak').modal('show');
            });
        },
    },
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <cetakseri v-if="showModal" @closeModal="showModal = false" />
        <div class="card">
            <div class="card-body">
                <div class="d-flex bd-highlight">
                    <div class="p-2 flex-grow-1 bd-highlight">
                        <button class="btn btn-outline-primary btn-sm" v-if="noSeriSelected.length > 0">
                            <i class="fas fa-print"></i>
                            Cetak No. Seri
                        </button>
                        <button class="btn btn-primary btn-sm" @click="openModalCreate">
                            <i class="fas fa-plus"></i>
                            Tambah No. Seri
                        </button>
                    </div>
                    <div class="p-2 bd-highlight">
                        <input type="text" class="form-control" v-model="search" placeholder="Cari...">
                    </div>
                </div>
                <div class="d-flex flex-row-reverse bd-highlight">
                    <div class="p-2 bd-highlight">
                    </div>
                </div>
                <data-table :headers="headers" :items="items" :search="search">
                    <template #header.id>
                        <div>
                            <input type="checkbox">
                        </div>
                    </template>
                    <template #item.id="{ item }">
                        <div>
                            <input type="checkbox">
                        </div>
                    </template>
                    <template #item.aksi="{ item }">
                        <div>
                            <button class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-print"></i>
                                Cetak No. Seri
                            </button>
                            <button class="btn btn-outline-info btn-sm">
                                <i class="fas fa-rounded fa-info-circle"></i>
                                Riwayat Cetak No. Seri
                            </button>
                        </div>
                    </template>
                </data-table>
            </div>
        </div>
    </div>
</template>