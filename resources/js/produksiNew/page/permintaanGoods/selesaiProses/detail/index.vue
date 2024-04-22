<script>
import Header from '../../../../components/header.vue'
import headerDetail from './header.vue'
import pengajuan from './pengajuan'
import pemasukan from './pemasukan'
import peminjaman from './peminjaman'
import perubahan from './perubahan'
import pengembalian from './pengembalian'
export default {
    components: { Header, headerDetail, pengajuan, pemasukan, peminjaman, perubahan, pengembalian },
    data() {
        return {
            title: 'Detail Permintaan Barang',
            breadcumbs: [
                {
                    name: 'Home',
                    link: '/'
                },
                {
                    name: 'Permintaan Barang',
                    link: '/produksi/permintaan_goods'
                },
                {
                    name: 'Detail Permintaan Barang',
                    link: '/produksi/permintaan_goods/detail'
                }
            ],
            detail: {
                headers: {
                    no_permintaan: 'NSO-2021080001',
                    no_referensi: 'SO-2021080001',
                    status: 'selesai',
                    tgl_permintaan: '2021-08-21',
                    tgl_pengambilan: '2024-08-23',
                    tgl_close: '2024-08-26',
                    tujuan_permintaaan: 'Lorem',
                    durasi: '3 hari'
                }
            }
        }
    },
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <headerDetail :header="detail.headers" />
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="pills-pengajuan-tab" data-toggle="pill" data-target="#pills-pengajuan"
                    type="button" role="tab" aria-controls="pills-pengajuan" aria-selected="true">Pengajuan</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="pills-pemasukan-tab" data-toggle="pill" data-target="#pills-pemasukan"
                    type="button" role="tab" aria-controls="pills-pemasukan" aria-selected="false">Pemasukan</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="pills-peminjaman-tab" data-toggle="pill" data-target="#pills-peminjaman"
                    type="button" role="tab" aria-controls="pills-peminjaman" aria-selected="false">Peminjaman</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="pills-perubahan-tab" data-toggle="pill" data-target="#pills-perubahan"
                    type="button" role="tab" aria-controls="pills-perubahan" aria-selected="false">Perubahan</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="pills-pengembalian-tab" data-toggle="pill" data-target="#pills-pengembalian"
                    type="button" role="tab" aria-controls="pills-pengembalian" aria-selected="false">Pengembalian</a>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-pengajuan" role="tabpanel"
                aria-labelledby="pills-pengajuan-tab">
                <pengajuan />
            </div>
            <div class="tab-pane fade" id="pills-pemasukan" role="tabpanel" aria-labelledby="pills-profile-tab">
                <pemasukan />
            </div>
            <div class="tab-pane fade" id="pills-peminjaman" role="tabpanel" aria-labelledby="pills-peminjaman-tab">
                <peminjaman />
            </div>
            <div class="tab-pane fade" id="pills-perubahan" role="tabpanel" aria-labelledby="pills-perubahan-tab">
                <perubahan />
            </div>
            <div class="tab-pane fade" id="pills-pengembalian" role="tabpanel" aria-labelledby="pills-pengembalian-tab">
                <pengembalian />
            </div>
        </div>
    </div>
</template>