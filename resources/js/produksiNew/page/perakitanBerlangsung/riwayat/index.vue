<script>
import DataTable from '../../../components/DataTable.vue';
import modalPilihan from './modalPilihan.vue';
import riwayatCetak from './riwayatCetak.vue';
export default {
    props: ['dataRiwayat', 'tanggalAkhir', 'tanggalAwal'],
    components: {
        DataTable,
        modalPilihan,
        riwayatCetak
    },
    data() {
        return {
            headers: [
                {
                    text: 'id',
                    value: 'id',
                    sortable: false,
                },
                {
                    text: 'No. Seri',
                    value: 'noseri'
                },
                {
                    text: 'Jenis Perakitan',
                    value: 'jenis_perakitan',
                    sortable: false,
                },
                {
                    text: 'Tanggal Dibuat',
                    value: 'tgl_buat',
                    sortable: false,
                },
                {
                    text: 'No BPPB',
                    value: 'no_bppb'
                },
                {
                    text: 'Nama Produk',
                    value: 'nama'
                },
                {
                    text: 'Aksi',
                    sortable: false,
                    value: 'aksi'
                }
            ],
            search: '',
            checkAll: false,
            noSeriSelected: [],
            cetakSeriSingle: [],
            cetakSeriType: 'all',
            riwayatSelected: null,
            showModalRiwayat: false,
            tanggal_awal: JSON.parse(JSON.stringify(this.tanggalAwal)),
            tanggal_akhir: JSON.parse(JSON.stringify(this.tanggalAkhir)),
            jenisPerakitanOptions: [
                {
                    label: 'Terjadwal',
                    value: 'terjadwal'
                },
                {
                    label: 'Tidak Terjadwal',
                    value: 'tidak_terjadwal'
                }
            ],
            jenisPerakitanSelected: null,
        }
    },
    methods: {
        checkAllSeri() {
            this.checkAll = !this.checkAll;
            if (this.checkAll) {
                this.noSeriSelected = this.filterData.map((item) => item.id);
            } else {
                this.noSeriSelected = [];
            }
        },
        selectNoSeri(noseri) {
            if (this.noSeriSelected.find((data) => data === noseri)) {
                this.noSeriSelected = this.noSeriSelected.filter((data) => data.id !== noseri)
                this.checkAll = false
            } else {
                this.noSeriSelected.push(noseri)
            }

            if (this.noSeriSelected.length === this.dataRiwayat.length) {
                this.checkAll = true
            }
        },
        cetakSeri(noseri) {
            this.cetakSeriSingle = [noseri]
            this.cetakSeriType = 'single'
            this.$nextTick(() => {
                $('.modalPilihan').modal('show');
            });
        },
        cetakBanyakSeri() {
            this.cetakSeriType = 'all'
            this.$nextTick(() => {
                $('.modalPilihan').modal('show');
            });
        },
        selectRiwayat(item) {
            this.riwayatSelected = item
            this.showModalRiwayat = true
            this.$nextTick(() => {
                $('.modalRiwayat').modal('show');
            });
        },
        updateTanggal() {
            this.$emit('updateTanggal', {
                tanggalAwal: this.tanggal_awal,
                tanggalAkhir: this.tanggal_akhir
            })
        }
    },
    computed: {
        filterData() {
            let data = this.dataRiwayat
            let jenis = this.jenisPerakitanSelected?.value
            if (this.jenisPerakitanSelected) {
                data = data.filter(item => item.jenis_perakitan == jenis)
            }
            return data
        }
    },
    watch: {
        search() {
            if (this.noSeriSelected.length == this.dataRiwayat.length) {
                this.checkAll = true
            } else {
                this.checkAll = false
            }
        },
        tanggal_awal() {
            this.$emit('updateTanggalAwal', this.tanggal_awal)
        },
    }
}
</script>
<template>
    <div>
        <modalPilihan :data="cetakSeriType == 'single' ? cetakSeriSingle : noSeriSelected"
            v-if="cetakSeriSingle.length > 0 || noSeriSelected.length > 0" />
        <riwayatCetak :riwayat="riwayatSelected" v-if="showModalRiwayat" @closeModal="showModalRiwayat = false" />
        <div class="d-flex bd-highlight">
            <div class="p-2 flex-grow-1 bd-highlight">
                <button class="btn btn-outline-primary btn-sm" v-if="noSeriSelected.length > 0" @click="cetakBanyakSeri">
                    <i class="fa fa-print"></i>
                    Cetak Nomor Seri
                </button>
            </div>
            <div class="p-2 bd-highlight">
                <input type="text" v-model="search" class="form-control" placeholder="Cari...">
            </div>
        </div>
        <DataTable :headers="headers" :items="filterData" :search="search">
            <template #header.id>
                <input type="checkbox" @click="checkAllSeri" :checked="checkAll">
            </template>

            <template #header.jenis_perakitan>
                <span class="text-bold pr-2">Jenis Perakitan</span>
                <span class="filter">
                    <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-filter"></i>
                    </a>
                    <form id="filter_ekat">
                        <div class="dropdown-menu">
                            <div class="px-3 py-3 font-weight-normal">
                                <v-select :options="jenisPerakitanOptions" v-model="jenisPerakitanSelected"></v-select>
                            </div>
                        </div>
                    </form>
                </span>
            </template>

            <template #header.tgl_buat>
                <span class="text-bold pr-2">Tanggal Dibuat</span>
                <span class="filter">
                    <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-filter"></i>
                    </a>
                    <form id="filter_ekat">
                        <div class="dropdown-menu">
                            <div class="px-3 py-3">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="jenis_penjualan">Tanggal Awal</label>
                                            <input type="date" class="form-control" v-model="tanggal_awal"
                                                @change="updateTanggal" :max="tanggal_akhir">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="jenis_penjualan">Tanggal Akhir</label>
                                            <input type="date" class="form-control" v-model="tanggal_akhir"
                                                @change="updateTanggal" :min="tanggal_awal">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </span>
            </template>

            <template #item.jenis_perakitan="{item}">
                <div>
                    <span>{{ item.jenis_perakitan == 'terjadwal' ? 'Terjadwal' : 'Tidak Terjadwal' }}</span>
                </div>
            </template>

            <template #item.id="{ item }">
                <input type="checkbox" :checked="noSeriSelected && noSeriSelected.find((noseri) => noseri === item.id)"
                    @click="selectNoSeri(item.id)" />
            </template>
            <template #item.aksi="{ item }">
                <button class="btn btn-outline-primary btn-sm" @click="cetakSeri(item.id)">
                    <i class="fa fa-print"></i>
                    Cetak No. Seri
                </button>
                <button class="btn btn-outline-info btn-sm" @click="selectRiwayat(item)">
                    <i class="fa fa-info-circle"></i>
                    Riwayat Cetak No. Seri
                </button>
            </template>
        </DataTable>
    </div>
</template>