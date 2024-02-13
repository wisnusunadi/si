<script>
import detailSO from './detailSO.vue'
import pagination from '../../../components/pagination.vue'
import axios from 'axios'
export default {
    components: {
        detailSO,
        pagination
    },
    props: ['detailSelected'],
    data() {
        return {
            search: '',
            items: [],
            renderPaginate: [],
            detailSelectedSO: {},
            showModal: false
        }
    },
    methods: {
        closeModal() {
            $('.modalDetail').modal('hide')
            this.$nextTick(() => {
                this.$emit('closeModal')
            })
        },
        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
        },
        openModalDetailSO(data) {
            this.detailSelectedSO = data
            this.showModal = true
            this.$nextTick(() => {
                $('.modalDetail').modal('hide')
                $('.modalDetailSO').modal('show')
            })
        },
        closeModalDetailSO() {
            this.showModal = false
            this.$nextTick(() => {
                $('.modalDetailSO').modal('hide')
                $('.modalDetail').modal('show')
            })
        },
        async getData() {
            try {
                const { data } = await axios.get(`/api/v2/gbj/get_detail_rekap_so_produk/${this.detailSelected.id}`)
                this.items = data.map((item, index) => {
                    return {
                        no: index + 1,
                        ...item,
                    }
                })
            } catch (error) {
                console.log(error)
            }
        }
    },
    created() {
        this.getData()
    },
    computed: {
        filterDalamProses() {
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
    <div>
        <detailSO v-if="showModal" :detailSelected="detailSelectedSO" @closeModal="closeModalDetailSO" />
        <div class="modal fade modalDetail" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
            aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-header">
                                <div class="row row-cols-3">
                                    <div class="col"> <label for="">Total Permintaan</label>
                                        <div class="card nomor-so">
                                            <div class="card-body">
                                                <span id="total_tf">{{ detailSelected.permintaan }} Unit</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col"> <label for="">Belum Terpenuhi</label>
                                        <div class="card nomor-akn">
                                            <div class="card-body">
                                                <span id="belum_tf">{{ detailSelected.sisa }} Unit</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col"> <label for="">Sudah Terpenuhi</label>
                                        <div class="card nomor-po">
                                            <div class="card-body">
                                                <span id="sudah_tf">{{ detailSelected.count_transfer }} Unit</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex flex-row-reverse bd-highlight">
                                    <div class="p-2 bd-highlight">
                                        <input type="text" class="form-control" v-model="search" placeholder="Cari...">
                                    </div>
                                </div>
                                <table class="table table-striped text-center">
                                    <thead>
                                        <tr>
                                            <th rowspan="2">No</th>
                                            <th colspan="2">Nomor</th>
                                            <th rowspan="2">Customer</th>
                                            <th colspan="2">Jumlah</th>
                                            <th rowspan="2">Aksi</th>
                                        </tr>
                                        <tr>
                                            <th>Sales Order</th>
                                            <th>Purchase Order</th>
                                            <th>Pesanan</th>
                                            <th>Terkirim</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="renderPaginate.length > 0">
                                        <tr v-for="(item, index) in renderPaginate" :key="index">
                                            <td>{{ item.no }}</td>
                                            <td>{{ item.so }}</td>
                                            <td>{{ item.no_po }}</td>
                                            <td>{{ item.customer }}</td>
                                            <td>{{ item.count_pesanan }}</td>
                                            <td>{{ item.count_transfer }}</td>
                                            <td>
                                                <button type="button" class="btn btn-outline-primary btn-sm"
                                                    @click="openModalDetailSO(item)">
                                                    <i class="fas fa-eye"></i>
                                                    Detail
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="100%" class="text-center">Tidak ada data</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <pagination :filteredDalamProses="filterDalamProses"
                                    @updateFilteredDalamProses="updateFilteredDalamProses" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>