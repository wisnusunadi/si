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
    props: ['penjualan'],
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
                    text: 'Nomor AKN',
                    value: 'no_paket'
                },
                {
                    text: 'Nomor PO',
                    value: 'no_po',
                },
                {
                    text: 'Tanggal PO',
                    value: 'tgl_po'
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
            status: [
                {
                    text: 'Penjualan',
                    value: 7
                },
                {
                    text: 'PO',
                    value: 9
                },
                {
                    text: 'Gudang',
                    value: 6
                },
                {
                    text: 'QC',
                    value: 8
                },
                {
                    text: 'Kirim',
                    value: 11
                },
            ],
            jenisTransaksi: [
                {
                    value: 'ekatalog',
                    text: 'E-Catalogue'
                },
                {
                    value: 'spa',
                    text: 'SPA'
                },
                {
                    value: 'spb',
                    text: 'SPB'
                }
            ]
        }
    },
    methods: {
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
        tambah() {
            window.location.href = '/penjualan/penjualan/create'
        },
        filter(year, status) {
            this.$store.dispatch('setYears', year)
            if (status != '') {
                this.$emit('filter', status)
            } else {
                this.$emit('refresh')
            }
        }
    },
    computed: {
        yearsComputed() {
            let years = []
            for (let i = 0; i < 5; i++) {
                years.push(moment().subtract(i, 'years').format('YYYY'))
            }
            return years
        }
    }
}
</script>
<template>
    <div class="card">
        <div class="card-body">
            <div class="d-flex bd-highlight">
                <div class="p-2 flex-grow-1 bd-highlight">
                    <span class="filter">
                        <button class="btn btn-outline-secondary btn-sm" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <i class="fas fa-filter"></i> Filter Tahun
                        </button>
                        <button class="btn btn-outline-info btn-sm" @click="tambah">
                            <i class="fas fa-plus"></i> Tambah
                        </button>
                        <form id="filter_ekat">
                            <div class="dropdown-menu" style="">
                                <div class="px-3 py-3">
                                    <div class="form-group">
                                        <div class="form-check" v-for="(year, key) in yearsComputed" :key="key">
                                            <input class="form-check-input form-years-select" type="radio" :value="year"
                                                :id="`status${key}`" @click="filter(year, '')" :checked="key == 0"
                                                v-model="$store.state.years">
                                            <label class="form-check-label" :for="`status${key}`">
                                                {{ year }}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="jenis_penjualan">Jenis Penjualan</label>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check" v-for="(status, key) in jenisTransaksi" :key="key">
                                            <input class="form-check-input" type="checkbox" :value="status.value"
                                                :id="`status${key}`" @click="$emit('filterTransaksi', status.value)">
                                            <label class="form-check-label" :for="`status${key}`">
                                                {{ status.text.charAt(0).toUpperCase() + status.text.slice(1) }}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="jenis_penjualan">Status</label>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check" v-for="(status, key) in status" :key="key">
                                            <input class="form-check-input" type="checkbox" :value="status.value"
                                                :id="`status${key}`" @click="filter($store.state.years, status.value)">
                                            <label class="form-check-label" :for="`status${key}`">
                                                {{ status.text.charAt(0).toUpperCase() + status.text.slice(1) }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </span>
                </div>
                <div class="p-2 bd-highlight"><input type="text" class="form-control" v-model="search"
                        placeholder="Cari..."></div>
            </div>
            <data-table :headers="header" :items="penjualan" :search="search">
                <template #item.status="{ item }">
                    <div>
                        <persentase :persentase="item.persentase" />
                    </div>
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
                <template #item.aksi="{ item }">
                    <div>
                        <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true"
                            aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="">
                            <a data-toggle="modal" data-target="ekatalog" class="detailmodal" data-attr="#"
                                data-id="5092">
                                <button class="dropdown-item" type="button">
                                    <i class="fas fa-eye"></i>
                                    Detail
                                </button>
                            </a>
                            <a target="_blank" href="#">
                                <button class="dropdown-item" type="button" @click="cetakSPPB(item.pesanan_id)">
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
</template>