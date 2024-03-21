<script>
import batalComponents from '../batal/index.vue'
import returComponents from '../retur.vue'
import detailComponents from '../detail.vue'
export default {
    components: {
        batalComponents,
        returComponents,
        detailComponents
    },
    props: ['spa'],
    data() {
        return {
            header: [
                {
                    text: 'No',
                    value: 'no'
                },
                {
                    text: 'Nomor SO',
                    value: 'so'
                },
                {
                    text: 'Nomor PO',
                    value: 'no_po',
                },
                {
                    text: 'Tanggal Order',
                    value: 'tgl_order'
                },
                {
                    text: 'Customer',
                    value: 'nama_customer'
                },
                {
                    text: 'Status',
                    value: 'status'
                },
                {
                    text: 'Aksi',
                    value: 'aksi',
                }
            ],
            search: '',
            showModal: false,
            detailSelected: {},
        }
    },
    methods: {
        detail(item) {
            this.detailSelected = item
            this.showModal = true
            this.$nextTick(() => {
                $('.modalDetail').modal('show')
            })
        },
    },
}
</script>
<template>
    <div>
        <detailComponents :detail="detailSelected" v-if="showModal" @close="showModal = false" />
        <div class="card">
            <div class="card-body">
                <div class="d-flex bd-highlight">
                    <div class="p-2 flex-grow-1 bd-highlight">
                        <button class="btn btn-outline-info">
                            <i class="fas fa-plus"></i> Tambah
                        </button>
                    </div>
                    <div class="p-2 bd-highlight"><input type="text" class="form-control" v-model="search"
                            placeholder="Cari..."></div>
                </div>
                <data-table :headers="header" :items="spa" :search="search">
                    <template #item.status="{ item }">
                        <div>
                            <persentase :persentase="item.persentase" />
                            
                        </div>
                    </template>
                    <template #item.aksi="{ item }">
                        <div>
                            <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton"
                                aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="">
                                <a data-toggle="modal" data-target="ekatalog" class="detailmodal" data-attr="#"
                                    data-id="5092">
                                    <button class="dropdown-item" type="button" @click="detail(item)">
                                        <i class="fas fa-eye"></i>
                                        Detail
                                    </button>
                                </a>
                                <a target="_blank" href="#">
                                    <button class="dropdown-item" type="button">
                                        <i class="fas fa-print"></i>
                                        SPPB
                                    </button>
                                </a>
                                <a data-toggle="modal" data-jenis="ekatalog" class="editmodal" data-id="5092">
                                    <button class="dropdown-item" type="button">
                                        <i class="fas fa-pencil-alt"></i>
                                        Edit No Urut &amp; DO
                                    </button>
                                </a>
                                <a href="#"><button class="dropdown-item openModalBatalRetur" type="button"><i
                                            class="fas fa-times"></i> Batal</button></a>
                                <a href="#"><button class="dropdown-item openModalBatalRetur" type="button"><i
                                            class="fa-solid fa-arrow-rotate-left"></i>
                                        Retur</button></a>
                            </div>
                        </div>
                    </template>
                </data-table>
            </div>
        </div>
    </div>
</template>