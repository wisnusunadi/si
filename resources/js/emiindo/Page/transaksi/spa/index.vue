<script>
import batalComponents from '../batal/index.vue'
import returComponents from '../retur.vue'
import detailComponents from '../detail/index.vue'
import doComponents from '../do.vue'
export default {
    components: {
        batalComponents,
        returComponents,
        detailComponents,
        doComponents,
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
        cetakSPPB(id) {
            window.open(`/penjualan/penjualan/cetak_surat_perintah/${id}`, '_blank')
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
        },
        editSpa(item) {
            window.location.href = `/penjualan/penjualan/edit_ekatalog/${item}/spa`
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
        cekIsString(value) {
            if (typeof value === 'string') {
                return true
            } else {
                return false
            }
        },
        hapus(item) {
            this.$swal({
                title: 'Apakah Anda Yakin?',
                text: 'Data yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(`/api/spa/delete/${item.id}`).then(() => {
                        this.$emit('refresh')
                        this.$swal('Berhasil!', 'Data berhasil dihapus.', 'success')
                    }).catch(() => {
                        this.$swal('Gagal!', 'Data gagal dihapus.', 'error')
                    })
                }
            })
        },
        openDO(item) {
            this.detailSelected = item
            this.showModal = true
            this.$nextTick(() => {
                $('.modalDO').modal('show')
            })
        },
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
    <div>
        <batalComponents v-if="showModal" @close="showModal = false" :batal="detailSelected" />
        <returComponents v-if="showModal" @close="showModal = false" :retur="detailSelected" />
        <detailComponents v-if="showModal" @close="showModal = false" :detail="detailSelected" />
        <doComponents v-if="showModal" @close="showModal = false" :doData="detailSelected" />
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
                                                <input class="form-check-input" type="checkbox" :value="status.value"
                                                    :id="`status${key}`"
                                                    @click="filter($store.state.years, status.value)">
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
                <data-table :headers="header" :items="spa" :search="search" v-if="!$store.state.loading">
                    <template #item.status="{ item }">
                        <div>
                            <persentase :persentase="item.persentase" v-if="!cekIsString(item.persentase)" />
                            <span class="red-text badge" v-else>{{ item.persentase }}</span>
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
                                    <button class="dropdown-item" type="button" @click="editSpa(item.id)">
                                        <i class="fas fa-pencil-alt"></i>
                                        Edit
                                    </button>
                                </a>
                                <a target="_blank" href="#" v-if="item.no_po != null && item.tgl_po != null">
                                    <button class="dropdown-item" type="button">
                                        <i class="fas fa-print"></i>
                                        SPPB
                                    </button>
                                </a>
                                <a data-toggle="modal" data-jenis="ekatalog" class="editmodal" data-id="5092">
                                    <button class="dropdown-item" type="button" @click="openDO(item)">
                                        <i class="fas fa-pencil-alt"></i>
                                        Edit DO
                                    </button>
                                </a>
                                <a href="#"><button class="dropdown-item openModalBatalRetur" @click="hapus(item)"
                                        type="button"><i class="fas fa-trash"></i>
                                        Hapus</button></a>
                                <a href="#"><button class="dropdown-item openModalBatalRetur" @click="batal(item)"
                                        type="button"><i class="fas fa-times"></i>
                                        Batal</button></a>
                                <a href="#"><button class="dropdown-item openModalBatalRetur" @click="retur(item)"
                                        type="button"><i class="fa-solid fa-arrow-rotate-left"></i>
                                        Retur</button></a>
                            </div>
                        </div>
                    </template>
                </data-table>
                <div v-else>
                    <div class="d-flex justify-content-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>