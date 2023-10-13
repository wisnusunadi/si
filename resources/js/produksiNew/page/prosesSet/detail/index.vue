<script>
import Header from '../../../components/header.vue';
import pagination from '../../../components/pagination.vue';
import Table from './table.vue';
import ModalCreate from '../modalCreate';
export default {
    components: {
        Header,
        pagination,
        Table,
        ModalCreate,
    },
    data() {
        return {
            title: 'Detail Produk',
            breadcumbs: [
                {
                    name: 'Beranda',
                    link: '/produksi/dashboard'
                },
                {
                    name: 'Set Produk Reworks',
                    link: '/produksi/prosesSetReworks'
                },
                {
                    name: 'Detail Produk',
                    link: '#'
                },
            ],
            search: '',
            dataTable: [
                {
                    id: 1,
                    noseri: '123456789',
                    tanggal: '2023-01-01',
                    checker: 'Siska',
                },
                {
                    id: 2,
                    noseri: '123456789',
                    tanggal: '2023-01-01',
                    checker: 'Siska',
                }
            ],
            renderPaginate: [],
            showModal: false,
            selectSeri: {},
        }
    },
    methods: {
        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
        },
        showModalCreate() {
            this.showModal = true;
            this.selectSeri = {}
            this.$nextTick(() => {
                $('.modalSet').modal('show');
            });
        },
        closeModalCreate() {
            $('.modalSet').modal('hide');

            this.$nextTick(() => {
                this.showModal = false;

            });
        },
        editNoseriProduk(data) {
            this.selectSeri = data
            this.showModal = true;
            this.$nextTick(() => {
                $('.modalSet').modal('show');
            });
        }
    },
    computed: {
        filteredDalamProses() {
            return this.dataTable.filter((data) => {
                return Object.keys(data).some((key) => {
                    return String(data[key]).toLowerCase().includes(this.search.toLowerCase());
                });
            });
        },
    },
}
</script>
<template>
    <div>
        <ModalCreate v-if="showModal" @closeModal="closeModalCreate" :selectSeri="selectSeri" />
        <Header :title="title" :breadcumbs="breadcumbs" />
        <div class="card">
            <div class="card-body">
                <div class="d-flex bd-highlight">
                    <div class="p-2 flex-grow-1 bd-highlight">
                        <button class="btn btn-primary" @click="showModalCreate">
                            Tambah <i class="fa fa-plus"></i>
                        </button>
                    </div>
                    <div class="p-2 bd-highlight"> <input type="text" v-model="search" class="form-control"
                            placeholder="Cari...">
                    </div>
                </div>
                <Table :dataTable="renderPaginate" @editNoseriProduk="editNoseriProduk" />
                <pagination :filteredDalamProses="filteredDalamProses"
                    @updateFilteredDalamProses="updateFilteredDalamProses" />
            </div>
        </div>
    </div>
</template>