<script>
import Header from '../../../../components/header.vue';
import pagination from '../../../../components/pagination.vue';
import Table from './table.vue';
import ModalCreate from '../modalCreate/';
import axios from 'axios';
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
            dataTable: [],
            renderPaginate: [],
            showModal: false,
            selectSeri: {},
            showTambah: false,
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
        },
        async getData() {
            try {
                this.$store.dispatch('setLoading', true);
                const id = this.$route.params.id;
                const { data } = await axios.get(`/api/prd/rw/proses/produk/${id}`);
                const { produk_reworks_id, set, urutan, item, belum } = data
                this.dataTable = item
                this.showTambah = belum == 0 ? true : false
                let header = {
                    produk_reworks_id,
                    set,
                    urutan,
                }
                this.$store.dispatch('setSeri', header);
            } catch (error) {
                console.log(error);
            } finally {
                this.$store.dispatch('setLoading', false);
            }
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
    mounted() {
        this.getData();
    },
}
</script>
<template>
    <div v-if="!$store.state.loading">
        <ModalCreate v-if="showModal" @closeModal="closeModalCreate" :selectSeri="selectSeri" @refresh="getData" />
        <Header :title="title" :breadcumbs="breadcumbs" />
        <div class="card">
            <div class="card-body">
                <div class="d-flex bd-highlight">
                    <div class="p-2 flex-grow-1 bd-highlight">
                        <button class="btn btn-primary" @click="showModalCreate" v-if="!showTambah">
                            Tambah <i class="fa fa-plus"></i>
                        </button>
                    </div>
                    <div class="p-2 bd-highlight"> <input type="text" v-model="search" class="form-control"
                            placeholder="Cari...">
                    </div>
                </div>
                <Table :dataTable="renderPaginate" @editNoseriProduk="editNoseriProduk" @refresh="getData"/>
                <pagination :filteredDalamProses="filteredDalamProses"
                    @updateFilteredDalamProses="updateFilteredDalamProses" />
            </div>
        </div>
    </div>
</template>