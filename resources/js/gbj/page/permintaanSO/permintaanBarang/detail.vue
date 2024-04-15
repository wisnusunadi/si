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
            showModal: false,
            headers: [
                {
                    text: 'No',
                    value: 'no'
                },
                {
                    text: 'Nomor',
                    children: [
                        {
                            text: 'Sales Order',
                            value: 'so'
                        },
                        {
                            text: 'Purchase Order',
                            value: 'no_po'
                        }
                    ]
                },
                {
                    text: 'Customer',
                    value: 'customer'
                },
                {
                    text: 'Jumlah',
                    children: [
                        {
                            text: 'Pesanan',
                            value: 'count_pesanan'
                        },
                        {
                            text: 'Terkirim',
                            value: 'count_transfer'
                        }
                    ]
                },
                {
                    text: 'Aksi',
                    value: 'aksi'
                }
            ]
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
        <div class="modal fade modalDetail" id="staticBackdrop" data-backdrop="static" data-keyboard="false"
            tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
                                <data-table :headers="headers" :items="items" :search="search">
                                    <template #item.aksi="{ item }">
                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                            @click="openModalDetailSO(item)">
                                            <i class="fas fa-eye"></i>
                                            Detail
                                        </button>
                                    </template>
                                </data-table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>