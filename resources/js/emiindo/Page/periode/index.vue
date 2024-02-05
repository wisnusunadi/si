<script>
import Header from '../../components/header.vue';
import createModal from './create.vue';
export default {
    components: {
        Header,
        createModal
    },
    data() {
        return {
            title: 'Periode Penjualan',
            breadcumbs: [
                {
                    name: 'Home',
                    link: '/penjualan/dashboard'
                },
                {
                    name: 'Periode Penjualan',
                    link: '/penjualan/periode'
                }
            ],
            search: '',
            headers: [
                {
                    text: 'No',
                    value: 'no',
                    sortable: false,
                },
                {
                    text: 'Periode',
                    value: 'periode',
                },
                {
                    text: 'Durasi buka',
                    value: 'durasi_buka',
                },
                {
                    text: 'Alasan',
                    value: 'alasan',
                },
                {
                    text: 'Tanggal pengajuan',
                    value: 'tanggal_pengajuan',
                },
                {
                    text: 'Tanggal persetujuan',
                    value: 'tanggal_persetujuan',
                },
                {
                    text: 'Pemohon',
                    value: 'pemohon',
                },
                {
                    text: 'Aksi',
                    value: 'aksi',
                    sortable: false,
                }
            ],
            items: [
                {
                    no: 1,
                    periode: 2023,
                    durasi_buka: '10 hari',
                    alasan: 'Penambahan po',
                    tanggal_pengajuan: '23 September 2021',
                    tanggal_persetujuan: '-',
                    pemohon: 'Admin',
                    status: 'Dalam Pengajuan',
                    isOpened: false,
                },
                {
                    no: 2,
                    periode: 2023,
                    durasi_buka: '10 hari',
                    alasan: 'Penambahan po',
                    tanggal_pengajuan: '23 September 2021',
                    tanggal_persetujuan: '-',
                    pemohon: 'Admin',
                    status: 'Ditolak',
                    isOpened: false,

                },
                {
                    no: 3,
                    periode: 2021,
                    durasi_buka: '10 hari',
                    alasan: 'Penambahan po',
                    tanggal_pengajuan: '23 September 2021',
                    tanggal_persetujuan: '23 September 2021',
                    pemohon: 'Admin',
                    status: 'Selesai',
                    isOpened: false,

                },
                {
                    no: 4,
                    periode: 2022,
                    durasi_buka: '10 hari',
                    alasan: 'Penambahan po',
                    tanggal_pengajuan: '23 September 2021',
                    tanggal_persetujuan: '23 September 2021',
                    pemohon: 'Admin',
                    status: 'Disetujui',
                    isOpened: true
                }
            ],
            filterTahun: [],
            showModal: false,
        }
    },
    methods: {
        tutup(item) {
            this.$swal({
                title: 'Apakah anda yakin?',
                text: "Anda tidak dapat mengembalikan data ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, saya yakin!',
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$swal(
                        'Berhasil!',
                        'Periode penjualan telah diselesaikan',
                        'success'
                    )
                    item.isOpened = false;
                }
            })
        },
        showModalCreate() {
            this.showModal = true;
            this.$nextTick(() => {
                $('.modalPeriode').modal('show');
            })
        },
        statusBadge(status) {
            if (status == 'Dalam Pengajuan') {
                return 'badge badge-primary';
            } else if (status == 'Ditolak') {
                return 'badge badge-danger';
            } else if (status == 'Disetujui') {
                return 'badge badge-success';
            } else if (status == 'Selesai') {
                return 'badge badge-secondary';
            }
        }
    },
    computed: {
        getUniqueTahun() {
            return [...new Set(this.items.map(item => item.periode))];
        },
        filterData() {
            if (this.filterTahun.length > 0) {
                return this.items.filter(item => this.filterTahun.includes(item.periode));
            } else {
                return this.items;
            }
        },
    }
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <createModal v-if="showModal" />
        <div class="card">
            <div class="card-body">
                <div class="d-flex bd-highlight">
                    <div class="p-2 flex-grow-1 bd-highlight">
                        <button class="btn btn-sm btn-outline-primary" @click="showModalCreate">
                            <i class="fas fa-plus"></i>
                            Tambah</button>
                        <span class=" filter">
                            <button class="btn btn-sm btn-outline-secondary" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <i class="fas fa-filter"></i> Filter Periode
                            </button>
                            <form id="filter_ekat">
                                <div class="dropdown-menu" style="">
                                    <div class="px-3 py-3">
                                        <div class="form-group row">
                                            <v-select class="col-12" :options="getUniqueTahun" v-model="filterTahun"
                                                multiple></v-select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </span>
                    </div>
                    <div class="p-2 bd-highlight">
                        <input v-model="search" class="form-control" placeholder="Cari...">
                    </div>
                </div>
                <data-table :headers="headers" :items="filterData" :search="search">
                    <template #item.tanggal_pengajuan="{ item }">
                        <div>
                            <span>{{ item.tanggal_pengajuan }}</span> <br>
                            <span :class="statusBadge(item.status)">{{ item.status }}</span>
                        </div>
                    </template>
                    <template #item.aksi="{ item }">
                        <div>
                            <button class="btn btn-outline-success btn-sm" @click="tutup(item)" v-if="item.isOpened">
                                <i class="fas fa-check"></i>
                                Selesai
                            </button>
                        </div>
                    </template>
                </data-table>
            </div>
        </div>
    </div>
</template>