<script>
import batalComponents from '../batal/index.vue'
import returComponents from '../retur.vue'
import detailComponents from '../detail.vue'
import statusComponents from '../../../components/status.vue'
export default {
    components: {
        batalComponents,
        returComponents,
        detailComponents,
        statusComponents
    },
    props: ['ekat'],
    data() {
        return {
            header: [
                {
                    text: 'No',
                    value: 'no'
                },
                {
                    text: 'No Urut',
                    value: 'urutan',
                },
                {
                    text: 'Nomor SO',
                    value: 'so'
                },
                {
                    text: 'Nomor AKN',
                    value: 'no_paket'
                },
                {
                    text: 'Nomor PO',
                    value: 'no_po',
                },
                {
                    text: 'Tanggal Buat',
                    value: 'tgl_buat'
                },
                {
                    text: 'Tanggal Edit',
                    value: 'tgl_edit'
                },
                {
                    text: 'Tanggal Delivery',
                    value: 'tgl_kontrak'
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
        tambah() {
            window.location.href = '/penjualan/penjualan/create'
        },
        batal(item) {
            this.detailSelected = item
            this.showModal = true
            this.$nextTick(() => {
                $('.modalBatal').modal('show')
            })
        },
        retur(item) {
            this.detailSelected = item
            this.showModal = true
            this.$nextTick(() => {
                $('.modalRetur').modal('show')
            })
        },
        detail(item) {
            this.detailSelected = item
            this.showModal = true
            this.$nextTick(() => {
                $('.modalDetail').modal('show')
            })
        },
        calculateDateFromNow(date) {
            // kalkulasi tanggal dari sekarang
            const tglSekarang = new Date();
            const tglKontrak = new Date(date);
            if (tglKontrak < tglSekarang) {
                return {
                    text: `Lebih ${moment(tglSekarang).diff(tglKontrak, 'days')} Hari`,
                    color: 'text-danger font-weight-bold',
                    icon: 'fas fa-exclamation-circle'
                }
            } else if (tglKontrak > tglSekarang) {
                return {
                    text: `${moment(tglKontrak).diff(tglSekarang, 'days')} Hari Lagi`,
                    color: 'text-dark',
                    icon: 'fas fa-clock'
                }
            } else {
                return {
                    text: 'Batas Kontrak Habis',
                    color: 'text-danger',
                    icon: 'fas fa-exclamation-circle'
                }
            }
        },
    },
}
</script>
<template>
    <div>
        <batalComponents v-if="showModal" @close="showModal = false" />
        <returComponents v-if="showModal" @close="showModal = false" />
        <detailComponents v-if="showModal" @close="showModal = false" :detail="detailSelected" />
        <div class="card">
            <div class="card-body">
                <div class="d-flex bd-highlight">
                    <div class="p-2 flex-grow-1 bd-highlight">
                        <button class="btn btn-outline-info" @click="tambah">
                            <i class="fas fa-plus"></i> Tambah
                        </button>
                    </div>
                    <div class="p-2 bd-highlight"><input type="text" class="form-control" v-model="search"
                            placeholder="Cari..."></div>
                </div>
                <data-table :headers="header" :items="ekat" :search="search">
                    <template #item.no_paket="{ item }">
                        {{ item.no_paket }}
                        <statusComponents :status="item.status" />
                    </template>
                    <template #item.tgl_kontrak="{ item }">
                        <div v-if="item.tgl_kontrak_custom">
                            <div :class="calculateDateFromNow(item.tgl_kontrak_custom).color">{{
            dateFormat(item.tgl_kontrak_custom) }}</div>
                            <small :class="calculateDateFromNow(item.tgl_kontrak_custom).color">
                                <i :class="calculateDateFromNow(item.tgl_kontrak_custom).icon"></i>
                                {{ calculateDateFromNow(item.tgl_kontrak_custom).text }}
                            </small>
                        </div>
                        <div v-else>
                        </div>
                    </template>
                    <template #item.status="{ item }">
                        <persentase :persentase="item.persentase" />
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
                                <a href="#"><button class="dropdown-item openModalBatalRetur" @click="batal(item)"
                                        type="button"><i class="fas fa-times"></i> Batal</button></a>
                                <a href="#"><button class="dropdown-item openModalBatalRetur" @click="retur(item)"
                                        type="button"><i class="fa-solid fa-arrow-rotate-left"></i>
                                        Retur</button></a>
                            </div>
                        </div>
                    </template>
                </data-table>
            </div>
        </div>
    </div>
</template>