<script>
import pagination from '../../../components/pagination.vue';
import detail from './detail.vue';
export default {
    components: {
        pagination,
        detail
    },
    props: ['items'],
    data() {
        return {
            search: '',
            items: [],
            renderPaginate: [],
            showModal: false,
            detailSelected: {}
        }
    },
    methods: {
        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
        },
        openModalDetail(item) {
            this.detailSelected = item;
            this.showModal = true;
            this.$nextTick(() => {
                $('.modalDetail').modal('show')
            });
        },
    },
    computed: {
        filteredDalamProses() {
            return this.items.filter((data) => {
                return Object.keys(data).some((key) => {
                    return String(data[key]).toLowerCase().includes(this.search.toLowerCase());
                });
            });
        }
    }
}
</script>
<template>
    <div class="card">
        <detail :detailSelected="detailSelected" v-if="showModal" @closeModal="showModal = false" />
        <div class="card-body">
            <div class="d-flex flex-row-reverse bd-highlight">
                <div class="p-2 bd-highlight">
                    <input type="text" class="form-control" v-model="search" placeholder="Cari...">
                </div>
            </div>
            <table class="table text-center">
                <thead>
                    <tr>
                        <th rowspan="2">No</th>
                        <th rowspan="2">Nama Produk</th>
                        <th colspan="2">Permintaan</th>
                        <th rowspan="2">Jumlah Transfer</th>
                        <th rowspan="2">Aksi</th>
                    </tr>
                    <tr>
                        <th>Total</th>
                        <th>Sisa</th>
                    </tr>
                </thead>
                <tbody v-if="renderPaginate.length > 0">
                    <tr v-for="(item, index) in renderPaginate" :key="index">
                        <td>{{ index + 1 }}</td>
                        <td>{{ item.produkk }}</td>
                        <td>{{ item.permintaan }}</td>
                        <td>{{ item.sisa }}</td>
                        <td>{{ item.count_transfer }}</td>
                        <td>
                            <button class="btn btn-outline-info btn-sm" @click="openModalDetail(item)">
                                <i class="fa fa-eye"></i>
                                Detail
                            </button>
                        </td>
                    </tr>
                </tbody>
                <tbody v-else>
                    <tr>
                        <td colspan=" 100%">Data tidak ditemukan
                        </td>
                    </tr>
                </tbody>
            </table>
            <pagination :filteredDalamProses="filteredDalamProses" @updateFilteredDalamProses="updateFilteredDalamProses" />
        </div>
    </div>
</template>