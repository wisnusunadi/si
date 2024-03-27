<script>
import batalComponents from '../batal/index.vue'
import returComponents from '../retur.vue'
import detailComponents from '../detail.vue'
import doComponents from '../do.vue'
import statusComponents from '../../../components/status.vue'
import pagination from '../../../components/pagination.vue'
export default {
    components: {
        batalComponents,
        returComponents,
        detailComponents,
        statusComponents,
        doComponents,
        pagination
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
            status: ['sepakat', 'negosiasi', 'batal', 'draft'],
            renderPaginate: [],
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
        openDO(item) {
            this.detailSelected = item
            this.showModal = true
            this.$nextTick(() => {
                $('.modalDO').modal('show')
            })
        },
        detail(item) {
            this.detailSelected = item
            this.showModal = true
            this.$nextTick(() => {
                $('.modalDetail').modal('show')
            })
        },
        cetakSPPB(id) {
            window.open(`/penjualan/penjualan/cetak_surat_perintah/${id}`, '_blank')
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
        filter(year, status) {
            this.$store.dispatch('setYears', year)
            if (status != '') {
                this.$emit('filter', status)
            } else {
                this.$emit('refresh')
            }
        },
        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
        },
        editEkat(item) {
            window.location.href = `/penjualan/penjualan/edit_ekatalog/${item}/ekatalog`
        },
        cekIsString(value) {
            if (typeof value === 'string') {
                return true
            } else {
                return false
            }
        },
    },
    computed: {
        yearsComputed() {
            let years = []
            for (let i = 0; i < 5; i++) {
                years.push(moment().subtract(i, 'years').format('YYYY'))
            }
            return years
        },
        filteredDalamProses() {
            const includesSearch = (obj, search) => {
                if (obj && typeof obj === 'object') {
                    return Object.keys(obj).some(key => {
                        if (typeof obj[key] === 'object') {
                            return includesSearch(obj[key], search);
                        }
                        return String(obj[key]).toLowerCase().includes(search.toLowerCase());
                    });
                }
                return false;
            };

            return this.ekat.filter(data => includesSearch(data, this.search));
        },
    }
}
</script>
<template>
    <div>
        <batalComponents v-if="showModal" @close="showModal = false" :batal="detailSelected" />
        <returComponents v-if="showModal" @close="showModal = false" :retur="detailSelected"
            @refresh="$emit('refresh')" />
        <detailComponents v-if="showModal" @close="showModal = false" :detail="detailSelected" />
        <doComponents v-if="showModal" @close="showModal = false" :doData="detailSelected"
            @refresh="$emit('refresh')" />
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
                                                <input class="form-check-input form-years-select" type="radio"
                                                    :value="year" :id="`status${key}`" @click="filter(year, '')"
                                                    :checked="key == 0" v-model="$store.state.years">
                                                <label class="form-check-label" :for="`status${key}`">
                                                    {{ year }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="jenis_penjualan">Status</label>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-check" v-for="(status, key) in status" :key="key">
                                                <input class="form-check-input" type="checkbox" :value="status"
                                                    :id="`status${key}`" @click="filter($store.state.years, status)">
                                                <label class="form-check-label" :for="`status${key}`">
                                                    {{ status.charAt(0).toUpperCase() + status.slice(1) }}
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

                <table class="table text-center" v-if="!$store.state.loading">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No Urut</th>
                            <th>Nomor SO</th>
                            <th>Nomor AKN</th>
                            <th>Nomor PO</th>
                            <th>Tanggal Buat</th>
                            <th>Tanggal Edit</th>
                            <th>Tanggal Delivery</th>
                            <th>Customer</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody v-if="renderPaginate.length > 0">
                        <tr v-for="(item, index) in renderPaginate" :key="index"
                            :class="{ 'strike-through-row text-danger font-weight-bold': item.status == 'batal' }">
                            <td :class="{ 'strike-through': item.status == 'batal' }">{{ index + 1 }}</td>
                            <td :class="{ 'strike-through': item.status == 'batal' }">{{ item.urutan }}</td>
                            <td :class="{ 'strike-through': item.status == 'batal' }">{{ item.so }}</td>
                            <td :class="{ 'strike-through': item.status == 'batal' }">
                                {{ item.no_paket }}
                                <statusComponents :status="item.status" />
                            </td>
                            <td :class="{ 'strike-through': item.status == 'batal' }">{{ item.no_po }}</td>
                            <td :class="{ 'strike-through': item.status == 'batal' }">{{ item.tgl_buat }}</td>
                            <td :class="{ 'strike-through': item.status == 'batal' }">{{ item.tgl_edit }}</td>
                            <td :class="{ 'strike-through': item.status == 'batal' }">
                                <div v-if="item.tgl_kontrak_custom">
                                    <div :class="calculateDateFromNow(item.tgl_kontrak_custom).color">{{
            dateFormat(item.tgl_kontrak_custom) }}</div>
                                    <small :class="calculateDateFromNow(item.tgl_kontrak_custom).color">
                                        <i :class="calculateDateFromNow(item.tgl_kontrak_custom).icon"></i>
                                        {{ calculateDateFromNow(item.tgl_kontrak_custom).text }}
                                    </small>
                                </div>
                                <div v-else></div>
                            </td>
                            <td :class="{ 'strike-through': item.status == 'batal' }">{{ item.nama_customer }}</td>
                            <td>
                                <persentase :persentase="item.persentase" v-if="!cekIsString(item.persentase)" />
                                <span class="red-text badge" v-else>{{ item.persentase }}</span>
                            </td>
                            <td>
                            <div>
                                    <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton"
                                        aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i>
                                    </div>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="">
                                        <a data-toggle="modal" data-target="ekatalog" class="detailmodal" data-attr="#"
                                            data-id="5092">
                                            <button class="dropdown-item" type="button" @click="detail(item)">
                                                <i class="fas fa-eye"></i>
                                                Detail
                                            </button>
                                        </a>
                                        <a target="_blank" href="#">
                                            <button class="dropdown-item" type="button" @click="editEkat(item.id)">
                                                <i class="fas fa-pencil-alt"></i>
                                                Edit
                                            </button>
                                        </a>
                                        <a target="_blank" href="#">
                                            <button class="dropdown-item" type="button"
                                                @click="cetakSPPB(item.pesanan_id)">
                                                <i class="fas fa-print"></i>
                                                SPPB
                                            </button>
                                        </a>
                                        <a data-toggle="modal" data-jenis="ekatalog" class="editmodal" data-id="5092">
                                            <button class="dropdown-item" type="button" @click="openDO(item)">
                                                <i class="fas fa-pencil-alt"></i>
                                                Edit No Urut &amp; DO
                                            </button>
                                        </a>
                                        <a href="#"><button class="dropdown-item openModalBatalRetur"
                                                @click="batal(item)" type="button"><i class="fas fa-times"></i>
                                                Batal</button></a>
                                        <a href="#"><button class="dropdown-item openModalBatalRetur"
                                                @click="retur(item)" type="button"><i
                                                    class="fa-solid fa-arrow-rotate-left"></i>
                                                Retur</button></a>
                                    </div>
                                </div>
                            </td>
                        </tr>

                    </tbody>
                    <tbody v-else>
                        <tr>
                            <td colspan="100%" class="text-center">Data tidak ditemukan</td>
                        </tr>
                    </tbody>
                </table>

                <div v-else>
                    <div class="d-flex justify-content-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>


                <pagination :filteredDalamProses="filteredDalamProses" v-if="!$store.state.loading"
                    @updateFilteredDalamProses="updateFilteredDalamProses" />
            </div>
        </div>
    </div>
</template>
<style>
.strike-through-row .strike-through {
    position: relative;
}

.strike-through-row .strike-through::before {
    content: '';
    position: absolute;
    top: 35%;
    left: 0;
    width: 100%;
    height: 1px;
    background-color: red;
}

.strike-through-row .strike-through td {
    position: relative;
    z-index: 2;
}
</style>