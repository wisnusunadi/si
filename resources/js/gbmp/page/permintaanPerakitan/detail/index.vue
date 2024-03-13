<script>
import Header from '../../../components/header.vue';
import headerDetail from './header.vue';
import pagination from '../../../components/pagination.vue';
export default {
    components: {
        Header,
        headerDetail,
        pagination,
    },
    data() {
        return {
            title: 'Detail Permintaan Perakitan',
            breadcumbs: [
                {
                    name: 'Home',
                    link: '/'
                },
                {
                    name: 'Permintaan Perakitan',
                    link: '/gbmp/perakitan'
                },
                {
                    name: 'Detail Permintaan Perakitan',
                    link: '/permintaan-perakitan/detail'
                }
            ],
            produk: {
                header: {
                    id: 1,
                    no_permintaan: '20210621001',
                    no_bppb: '20210621001',
                    tgl_permintaan: '2023-09-23',
                    tanggal_permintaan: '23 September 2023',
                    tgl_akhir: '2023-10-23',
                    tanggal_akhir_persiapan: '23 Oktober 2023',
                    persentase: 25
                },
                produk: [
                    {
                        no: 1,
                        nama: 'Produk 1',
                        jumlah: 10,
                        sudah_transfer: 5
                    },
                    {
                        no: 2,
                        nama: 'Produk 2',
                        jumlah: 20,
                        sudah_transfer: 10
                    },
                    {
                        no: 3,
                        nama: 'Produk 3',
                        jumlah: 30,
                        sudah_transfer: 15
                    }
                ]
            },
            search: '',
            renderPaginate: [],
            showModal: false,
            detailTransfer: {}
        }
    },
    methods: {
        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
        },
        transfer(item) {
            this.detailTransfer = item;
            this.showModal = true;
            this.$nextTick(() => {
                $('#modelId').modal('show');
            });
        },
    },
    computed: {
        filteredDalamProses() {
            return this.produk.produk.filter((data) => {
                return Object.keys(data).some((key) => {
                    return String(data[key]).toLowerCase().includes(this.search.toLowerCase());
                });
            });
        },
    }
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <headerDetail :header="produk.header" />
        <div class="card">
            <div class="card-body">
                <div class="d-flex bd-highlight">
                    <div class="p-2 flex-grow-1 bd-highlight">
                        <button class="btn btn-success"
                            @click="$router.push({ name: 'permintaan-perakitan-transfer', params: { id: $route.params.id } })">Transfer</button>
                    </div>
                    <div class="p-2 bd-highlight">
                        <input type="text" class="form-control" placeholder="Cari..." v-model="search">
                    </div>
                </div>
                <table class="table text-center">
                    <thead>
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">Nama Produk</th>
                            <th colspan="2">Jumlah</th>
                            <th rowspan="2">Aksi</th>
                        </tr>
                        <tr>
                            <th>Belum Transfer</th>
                            <th>Sudah Transfer</th>
                        </tr>
                    </thead>
                    <tbody v-if="renderPaginate.length > 0">
                        <tr v-for="(item, index) in renderPaginate" :key="index">
                            <td>{{ item.no }}</td>
                            <td>{{ item.nama }}</td>
                            <td>{{ item.jumlah }}</td>
                            <td>{{ item.sudah_transfer }}</td>
                            <td>
                                <button class="btn btn-outline-primary btn-sm" @click="transfer(item)">
                                    <i class="fa fa-eye"></i>
                                    Detail
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-else>
                        <tr>
                            <td colspan="100%">Tidak ada data</td>
                        </tr>
                    </tbody>
                </table>
                <pagination :filteredDalamProses="filteredDalamProses"
                    @updateFilteredDalamProses="updateFilteredDalamProses" />
            </div>
        </div>
    </div>
</template>