<script>
import modalDetail from './detail';
import modalPengembalian from './pengembalian';
export default {
    components: {
        modalDetail,
        modalPengembalian
    },
    data() {
        return {
            headers: [
                {
                    text: 'No',
                    value: 'no',
                },
                {
                    text: 'Nomor Referensi',
                    value: 'noref'
                },
                {
                    text: 'Tanggal Permintaan',
                    value: 'tanggal',
                },
                {
                    text: 'Bagian',
                    value: 'bagian',
                },
                {
                    text: 'Tujuan Permintaan',
                    value: 'tujuan',
                },
                {
                    text: 'Status',
                    value: 'status',
                },
                {
                    text: 'Aksi',
                    value: 'aksi',
                }
            ],
            search: '',
            permintaanNSO: [
                {
                    no: 1,
                    noref: 'MEMO/2021/001',
                    tglminta: '2023-01-01',
                    tanggal: '01 Januari 2023',
                    tujuan: 'Peminjaman',
                    bagian: 'After Sales',
                    lama: '2 Hari',
                    status: 'new'
                },
                {
                    no: 2,
                    noref: 'MEMO/2021/002',
                    tglminta: '2023-01-01',
                    tanggal: '01 Januari 2023',
                    tujuan: 'Peminjaman',
                    bagian: 'After Sales',
                    lama: '2 Hari',
                    status: 'update'
                },
                {
                    no: 3,
                    noref: 'MEMO/2021/003',
                    tglminta: '2023-01-01',
                    tanggal: '01 Januari 2023',
                    tujuan: 'Peminjaman',
                    bagian: 'After Sales',
                    lama: '2 Hari',
                    status: 'barangdisiapkan'
                },
                {
                    no: 4,
                    noref: 'MEMO/2021/004',
                    tglminta: '2023-01-01',
                    tanggal: '01 Januari 2023',
                    tujuan: 'Peminjaman',
                    bagian: 'After Sales',
                    lama: '2 Hari',
                    status: 'barangkeluar'
                },
                {
                    no: 5,
                    noref: 'MEMO/2021/005',
                    tglminta: '2023-01-01',
                    tanggal: '01 Januari 2023',
                    tujuan: 'Penggantian Barang',
                    bagian: 'Logistik',
                    status: 'barangdisiapkan'
                },
                {
                    no: 6,
                    noref: 'MEMO/2021/006',
                    tglminta: '2023-01-01',
                    tanggal: '01 Januari 2023',
                    tujuan: 'Peminjaman',
                    bagian: 'After Sales',
                    lama: '2 Hari',
                    status: 'belumselesaikembali'
                },
            ],
            detailSelected: {},
            showModal: false
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
        pengembalian(item) {
            this.detailSelected = item
            this.showModal = true
            this.$nextTick(() => {
                $('.modalPengembalian').modal('show')
            })
        },
        statusProses(status) {
            switch (status) {
                case 'update':
                    return {
                        text: 'Update Permintaan',
                        class: 'badge-warning'
                    }
                // barang disiapkan jika persentase persiapan barang tidak 100 %
                case 'barangdisiapkan':
                    return {
                        text: 'Barang Disiapkan',
                        class: 'badge-success'
                    }
                case 'barangkeluar':
                    return {
                        text: 'Barang Keluar',
                        class: 'badge-info'
                    }
                case 'belumselesaikembali':
                    return {
                        text: 'Pengembalian Belum Selesai',
                        class: 'badge-danger'
                    }
                default:
                    return {
                        text: 'Permintaan Baru',
                        class: 'badge-primary'
                    }
            }
        },
    },
}
</script>
<template>
    <div>
        <modalDetail v-if="showModal" @close="showModal = false" :permintaan="detailSelected" />
        <modalPengembalian v-if="showModal" @close="showModal = false" :pengembalian="detailSelected" />
        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight">
                <input type="text" class="form-control" v-model="search" placeholder="Cari...">
            </div>
        </div>
        <data-table :headers="headers" :items="permintaanNSO" :search="search">
            <template #item.status="{ item }">
                <span class="badge" :class="statusProses(item.status).class">{{ statusProses(item.status).text }}</span>
            </template>
            <template #item.aksi="{ item }">
                <button class="btn btn-sm btn-outline-primary" @click="detail(item)">
                    <i class="fas fa-eye"></i>
                    Detail
                </button>
                <button class="btn btn-sm btn-outline-success" @click="pengembalian(item)"
                    v-if="item.status == 'barangkeluar' || item.status == 'belumselesaikembali' && item.tujuan == 'Peminjaman'">
                    <i class="fas fa-qrcode"></i>
                    Pengembalian
                </button>
            </template>
        </data-table>
    </div>
</template>
