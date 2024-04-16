<script>
import status from '../../../components/status.vue';
import tambah from './tambah.vue';
export default {
    components: { status, tambah },
    data() {
        return {
            search: '',
            headers: [
                {
                    text: 'Nomor Permintaan',
                    value: 'no_permintaan',
                },
                {
                    text: 'Nomor Referensi',
                    value: 'no_referensi',
                },
                {
                    text: 'Tanggal Permintaan',
                    value: 'tgl_permintaan',
                },
                {
                    text: 'Tujuan Permintaan',
                    value: 'tujuan_permintaan',
                },
                {
                    text: 'Tanggal Kebutuhan',
                    value: 'tgl_kebutuhan',
                },
                {
                    text: 'Durasi',
                    value: 'durasi',
                },
                {
                    text: 'Aksi',
                    value: 'aksi',
                }
            ],
            items: [
                {
                    no_permintaan: 'NSO-2021080001',
                    no_referensi: 'SO-2021080001',
                    tgl_permintaan: '2021-08-01',
                    tujuan_permintaan: 'Lorem Ipsum',
                    tgl_kebutuhan: '2021-08-01',
                    status: 'batal',
                    durasi: '1 hari',
                    jenis: 'peminjaman',
                },
                {
                    no_permintaan: 'NSO-2021080002',
                    no_referensi: 'SO-2021080002',
                    tgl_permintaan: '2021-08-02',
                    tujuan_permintaan: 'Lorem Ipsum',
                    tgl_kebutuhan: '2021-08-02',
                    status: 'menunggu_persetujuan_atasan',
                    durasi: null,
                    jenis: 'permintaan',
                },
                {
                    no_permintaan: 'NSO-2021080003',
                    no_referensi: 'SO-2021080003',
                    tgl_permintaan: '2021-08-03',
                    tujuan_permintaan: 'Lorem Ipsum',
                    tgl_kebutuhan: '2021-08-03',
                    status: 'permintaan_ditolak_atasan',
                    durasi: '2 hari',
                    jenis: 'peminjaman',
                },
                {
                    no_permintaan: 'NSO-2021080004',
                    no_referensi: 'SO-2021080004',
                    tgl_permintaan: '2021-08-04',
                    tujuan_permintaan: 'Lorem Ipsum',
                    tgl_kebutuhan: '2021-08-04',
                    status: 'menunggu_persetujuan_gudang',
                    durasi: null,
                    jenis: 'permintaan',
                },
                {
                    no_permintaan: 'NSO-2021080005',
                    no_referensi: 'SO-2021080005',
                    tgl_permintaan: '2021-08-05',
                    tujuan_permintaan: 'Lorem Ipsum',
                    tgl_kebutuhan: '2021-08-05',
                    status: 'permintaan_gagal_diacc',
                    durasi: '3 hari',
                    jenis: 'peminjaman',
                },
                {
                    no_permintaan: 'NSO-2021080006',
                    no_referensi: 'SO-2021080006',
                    tgl_permintaan: '2021-08-06',
                    tujuan_permintaan: 'Lorem Ipsum',
                    tgl_kebutuhan: '2021-08-06',
                    status: 'permintaan_ditolak_gudang',
                    durasi: '3 hari',
                    jenis: 'permintaan',
                }
            ],
            showModal: false,
            detailSelected: {},
        }
    },
    methods: {
        openTambah() {
            this.showModal = true;
            this.$nextTick(() => {
                $('.modalTambah').modal('show');
            });
        }  
    },
}
</script>
<template>
    <div>
        <tambah v-if="showModal" @close="showModal = false" />
        <div class="d-flex bd-highlight">
            <div class="p-2 flex-grow-1 bd-highlight">
                <button class="btn btn-primary" @click="openTambah">
                    <i class="fas fa-plus"></i> Tambah
                </button>
            </div>
            <div class="p-2 bd-highlight">
                <input type="text" class="form-control" v-model="search" placeholder="Cari...">
            </div>
        </div>
        <data-table :headers="headers" :items="items" :search="search">
            <template #item.tgl_permintaan="{item}">
                <div>
                    {{ dateFormat(item.tgl_permintaan) }} <br>
                    <status :status="item.jenis" />
                </div>
            </template>
            <template #item.tgl_kebutuhan="{item}">
                <div>
                    {{ dateFormat(item.tgl_kebutuhan) }} <br>
                    <status :status="item.status" />
                </div>
            </template>
            <template #item.aksi="{item}">
                <div>
                    <button class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-eye"></i>
                        Detail
                    </button>
                    <button class="btn btn-outline-warning btn-sm">
                        <i class="fas fa-edit"></i>
                        Edit
                    </button>
                    <button class="btn btn-outline-danger btn-sm">
                        <i class="fas fa-times"></i>
                        Batal
                    </button>
                </div>
            </template>
        </data-table>
    </div>
</template>