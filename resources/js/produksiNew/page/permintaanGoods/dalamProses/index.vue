<script>
import status from '../../../components/status.vue';
import persentase from '../../../../emiindo/components/persentase.vue'
import pengambilan from './pengambilan.vue';
export default {
    components: {
        status,
        persentase,
        pengambilan
    },
    data() {
        return {
            headers: [
                {
                    text: 'Nomor Permintaan',
                    value: 'no_permintaan'
                },
                {
                    text: 'Nomor Referensi',
                    value: 'no_referensi'
                },
                {
                    text: 'Tanggal Permintaan',
                    value: 'tgl_permintaan'
                },
                {
                    text: 'Tujuan Permintaan',
                    value: 'tujuan'
                },
                {
                    text: 'Tanggal Pengambilan',
                    value: 'tgl_pengambilan'
                },
                {
                    text: 'Durasi',
                    value: 'durasi'
                },
                {
                    text: 'Jenis',
                    value: 'jenis'
                },
                {
                    text: 'Status',
                    value: 'status'
                },
                {
                    text: 'Persentase',
                    value: 'persentase'
                },
                {
                    text: 'Aksi',
                    value: 'aksi'
                }
            ],
            search: '',
            items: [
                {
                    no_permintaan: 'NSO-2021080001',
                    no_referensi: 'SO-2021080001',
                    tgl_permintaan: '21 Agustus 2021',
                    tujuan: 'Lorem',
                    tgl_pengambilan: null,
                    durasi: null,
                    jenis: 'permintaan',
                    status: 'persiapan_barang',
                    persentase: 0,
                },
                {
                    no_permintaan: 'NSO-2021080002',
                    no_referensi: 'SO-2021080002',
                    tgl_permintaan: '21 Agustus 2021',
                    tujuan: 'Ipsum',
                    tgl_pengambilan: null,
                    durasi: null,
                    jenis: 'permintaan',
                    status: 'barang_siap_diambil',
                    persentase: 0,
                },
                {
                    no_permintaan: 'NSO-2021080003',
                    no_referensi: 'SO-2021080003',
                    tgl_permintaan: '21 Agustus 2021',
                    tujuan: 'Dolor',
                    tgl_pengambilan: '2024-08-22',
                    durasi: '1 Hari',
                    jenis: 'peminjaman',
                    status: 'barang_keluar',
                    persentase: 0,
                },
                {
                    no_permintaan: 'NSO-2021080004',
                    no_referensi: 'SO-2021080004',
                    tgl_permintaan: '21 Agustus 2021',
                    tujuan: 'Sit Amet',
                    tgl_pengambilan: '2024-08-22',
                    durasi: '2 Hari',
                    jenis: 'permintaan',
                    status: 'barang_keluar',
                    persentase: 0,
                },
                {
                    no_permintaan: 'NSO-2021080005',
                    no_referensi: 'SO-2021080005',
                    tgl_permintaan: '21 Agustus 2021',
                    tujuan: 'Consectetur',
                    tgl_pengambilan: '2024-08-22',
                    durasi: '3 Hari',
                    jenis: 'peminjaman',
                    status: 'proses_peminjaman',
                    persentase: 50,
                }
            ],
            showModal: false,
            detailSelected: null
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
                    text: 'Batas Peminjaman Habis',
                    color: 'text-danger',
                    icon: 'fas fa-exclamation-circle'
                }
            }
        },
        openModalPengambilan(item) {
            this.detailSelected = item;
            this.showModal = true;
            this.$nextTick(() => {
                $('.modalPengambilan').modal('show');
            });
        },
        pengambilanBarang({ id }) {
            swal.fire({
                title: 'Apakah anda yakin?',
                text: 'Anda akan mengambil barang ini',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, ambil barang'
            }).then((result) => {
                if (result.isConfirmed) {
                    swal.fire('Berhasil!', 'Barang telah diambil', 'success');
                }
            });
        },
        terimaBarang({ id }) {
            swal.fire({
                title: 'Apakah anda yakin?',
                text: 'Anda akan menerima barang ini',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, terima barang'
            }).then((result) => {
                if (result.isConfirmed) {
                    swal.fire('Berhasil!', 'Barang telah diterima', 'success');
                }
            });
        }
    },
}
</script>
<template>
    <div>
        <pengambilan v-if="showModal" @close="showModal = false" :detailSelected="detailSelected" />
        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight">
                <input type="text" class="form-control" v-model="search" placeholder="Cari...">
            </div>
        </div>
        <data-table :headers="headers" :items="items" :search="search">
            <template #item.tgl_pengambilan="{ item }">
                <div v-if="item.tgl_pengambilan">

                </div>
            </template>
            <template #item.jenis="{ item }">
                <div class="text-capitalize">
                    {{ item.jenis }}
                </div>
            </template>
            <template #item.status="{ item }">
                <div>
                    <status :status="item.status" />
                </div>
            </template>
            <template #item.persentase="{ item }">
                <div>
                    <persentase :persentase="item.persentase" />
                </div>
            </template>
            <template #item.tgl_pengambilan="{ item }">
                <div v-if="item.jenis == 'peminjaman'">
                    <div :class="calculateDateFromNow(item.tgl_pengambilan).color">{{
                        dateFormat(item.tgl_pengambilan) }}</div>
                    <small :class="calculateDateFromNow(item.tgl_pengambilan).color">
                        <i :class="calculateDateFromNow(item.tgl_pengambilan).icon"></i>
                        {{ calculateDateFromNow(item.tgl_pengambilan).text }}
                    </small>
                </div>
                <div v-else>
                    {{ dateFormat(item.tgl_pengambilan) }}
                </div>
            </template>
            <template #item.aksi="{ item }">
                <div>
                    <button class="btn btn-outline-info btn-sm" v-if="item.status == 'barang_siap_diambil'"
                        @click="pengambilanBarang(item)">
                        <i class="fas fa-hand-holding"></i>
                        Pengambilan
                    </button>
                    <button class="btn btn-outline-success btn-sm" v-if="item.status == 'barang_keluar'"
                        @click="terimaBarang(item)">
                        <i class="fas fa-check"></i>
                        Terima
                    </button>
                </div>
            </template>
        </data-table>
    </div>
</template>