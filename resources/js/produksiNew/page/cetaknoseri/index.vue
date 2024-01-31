<script>
import Header from '../../components/header.vue';
import cetakseri from './create.vue';
import pilihan from '../perakitanBerlangsung/riwayat/modalPilihan.vue'
import riwayat from './riwayat.vue';
import seriviatext from '../../../gbj/page/PermintaanReworkGBJ/permintaan/formPermintaan/seriviatext.vue';
import axios from 'axios';
import moment from 'moment'
export default {
    components: {
        Header,
        cetakseri,
        pilihan,
        riwayat,
        seriviatext
    },
    data() {
        return {
            title: 'Cetak No. Seri',
            breadcumbs: [
                {
                    name: 'Beranda',
                    link: '/produksi/dashboard'
                },
                {
                    name: 'Cetak No. Seri',
                    link: '#'
                }
            ],
            search: '',
            headers: [
                {
                    text: 'Id',
                    value: 'id',
                    sortable: false,
                },
                {
                    text: 'No. Seri',
                    value: 'noseri',
                },
                {
                    text: 'Keperluan',
                    value: 'keperluan',
                },
                {
                    text: 'Tanggal Buat',
                    value: 'tanggal',
                },
                {
                    text: 'Operator',
                    value: 'user',
                },
                {
                    text: 'Aksi',
                    value: 'aksi',
                    sortable: false,
                }
            ],
            items: [],
            noSeriSelected: [],
            showModal: false,
            checkAll: false,
            showModalPilihan: false,
            showModalRiwayat: false,
            selectRiwayat: null,
            showModalSeriViaText: false,
            cetakSeriType: 'all',
            cetakSeriSingle: [],
            tanggal_awal: moment().startOf('month').format('YYYY-MM-DD'),
            tanggal_akhir: moment().endOf('month').format('YYYY-MM-DD'),
        }
    },
    methods: {
        openModalCreate() {
            this.showModal = true;
            this.$nextTick(() => {
                $('.modalcetak').modal('show');
            });
        },
        selectNoSeri(id) {
            if (this.noSeriSelected.find((data) => data === id)) {
                this.noSeriSelected = this.noSeriSelected.filter((data) => data !== id);
            } else {
                this.noSeriSelected.push(id);
            }
        },
        checkedAll() {
            this.checkAll = !this.checkAll;
            if (this.checkAll) {
                this.noSeriSelected = this.items.map((data) => data.id);
            } else {
                this.noSeriSelected = [];
            }
        },
        openModalPilihan() {
            this.showModalPilihan = true;
            this.cetakSeriType = 'all'
            this.$nextTick(() => {
                $('.modalPilihan').modal('show');
            });
        },
        openRiwayat(item) {
            this.selectRiwayat = item;
            this.showModalRiwayat = true;
            this.$nextTick(() => {
                $('.modalRiwayat').modal('show');
            });
        },
        openModalSeriText() {
            this.showModalSeriViaText = true;
            this.$nextTick(() => {
                $('.modalChecked').modal('show');
            });
        },
        submit(noseri) {
            let noseriarray = noseri.split(/[\n, \t]/)
            let noseridouble = []
            let noserinotfound = []

            noseriarray = noseriarray.filter((data) => {
                return data !== '' && data !== null
            })

            noseriarray.forEach((item, index) => {
                if (noseriarray.indexOf(item) !== index) {
                    noseridouble.push(item)
                }
            })

            if (noseridouble.length > 0) {
                this.$swal('Peringatan!', `No. Seri ${noseridouble.join(', ')} duplikat`, 'warning')
            }

            noseriarray = [...new Set(noseriarray)]

            for (let i = 0; i < noseriarray.length; i++) {
                let found = false
                for (let j = 0; j < this.items.length; j++) {
                    if (noseriarray[i] === this.items[j].noseri) {
                        found = true
                        this.selectNoSeri(this.items[j].id)
                    } else {
                        found = false
                    }
                }
                if (!found) {
                    noserinotfound.push(noseriarray[i])
                }
            }

            if (noserinotfound.length > 0) {
                this.$swal('Peringatan!', `No. Seri ${noserinotfound.join(', ')} tidak ditemukan`, 'warning')
            }
        },
        cetakSeriSatu(id) {
            this.cetakSeriSingle = [id]
            this.cetakSeriType = 'single'
            this.showModalPilihan = true;
            this.$nextTick(() => {
                $('.modalPilihan').modal('show');
            });
        },
        async getData() {
            try {
                this.$store.dispatch('setLoading', true)
                const { data } = await axios.post('/api/prd/fg/non_stok/show', {
                    tanggalAwal: this.tanggal_awal,
                    tanggalAkhir: this.tanggal_akhir,
                })
                this.items = data.map((item) => {
                    return {
                        tanggal: this.dateTimeFormat(item.tgl_buat),
                        ...item
                    }
                })
            } catch (error) {
                console.log(error)
            } finally {
                this.$store.dispatch('setLoading', false)
            }
        },
    },
    watch: {
        noSeriSelected() {
            if (this.noSeriSelected.length === this.items.length) {
                this.checkAll = true;
            } else {
                this.checkAll = false;
            }
        }
    },
    created() {
        this.getData()
    }
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <cetakseri v-if="showModal" @closeModal="showModal = false" @refresh="getData" />
        <pilihan v-if="showModalPilihan" @closeModal="showModalPilihan = false"
            :data="cetakSeriType == 'single' ? cetakSeriSingle : noSeriSelected" />
        <riwayat v-if="showModalRiwayat" :riwayat="selectRiwayat" @closeModal="showModalRiwayat = false" />
        <seriviatext v-if="showModalSeriViaText" @close="showModalSeriViaText = false" @submit="submit" />
        <div class="card">
            <div class="card-body" v-if="!$store.state.loading">
                <div class="d-flex bd-highlight">
                    <div class="p-2 flex-grow-1 bd-highlight">
                        <button class="btn btn-outline-primary btn-sm" @click="openModalPilihan"
                            v-if="noSeriSelected.length > 0">
                            <i class="fas fa-print"></i>
                            Cetak No. Seri
                        </button>
                        <button class="btn btn-outline-info btn-sm" @click="openModalSeriText">Pilih No. Seri Via
                            Text</button>
                        <button class="btn btn-primary btn-sm" @click="openModalCreate">
                            <i class="fas fa-plus"></i>
                            Tambah No. Seri
                        </button> <br>
                        <span class="filter">
                            <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <button class="btn btn-outline-info btn-sm mt-2">
                                    <i class="fas fa-filter"></i>
                                    Filter Tanggal
                                </button>
                            </a>
                            <form id="filter_ekat">
                                <div class="dropdown-menu">
                                    <div class="px-3 py-3">
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="jenis_penjualan">Tanggal Awal</label>
                                                    <input type="date" class="form-control" v-model="tanggal_awal"
                                                        @change="getData" :max="tanggal_akhir">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="jenis_penjualan">Tanggal Akhir</label>
                                                    <input type="date" class="form-control" v-model="tanggal_akhir"
                                                        @change="getData" :min="tanggal_awal">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </span>
                    </div>
                    <div class="p-2 bd-highlight">
                        <input type="text" class="form-control" v-model="search" placeholder="Cari...">
                    </div>
                </div>
                <div class="d-flex flex-row-reverse bd-highlight">
                    <div class="p-2 bd-highlight">
                    </div>
                </div>
                <data-table :headers="headers" :items="items" :search="search">
                    <template #header.id>
                        <div>
                            <input type="checkbox" :checked="checkAll" @click="checkedAll">
                        </div>
                    </template>
                    <template #item.id="{ item }">
                        <div>
                            <input type="checkbox" @click="selectNoSeri(item.id)"
                                :checked="noSeriSelected && noSeriSelected.find((noseri) => noseri === item.id)" />
                        </div>
                    </template>
                    <template #item.aksi="{ item }">
                        <div>
                            <button class="btn btn-outline-primary btn-sm" @click="cetakSeriSatu(item.id)">
                                <i class="fas fa-print"></i>
                                Cetak No. Seri
                            </button>
                            <button class="btn btn-outline-info btn-sm" @click="openRiwayat(item)">
                                <i class="fas fa-rounded fa-info-circle"></i>
                                Riwayat Cetak No. Seri
                            </button>
                        </div>
                    </template>
                </data-table>
            </div>
            <div class="card-body" v-else>
                <div class="d-flex justify-content-center">
                    <div class="spinner-grow text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>