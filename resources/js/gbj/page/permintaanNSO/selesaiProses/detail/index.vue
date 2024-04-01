<script>
import Header from '../../../../components/header'
import headerDetail from './header.vue'
export default {
    components: {
        Header,
        headerDetail,
    },
    data() {
        return {
            title: 'Detail Permintaan Selain Sales Order',
            breadcumbs: [
                {
                    name: 'Home',
                    link: '/'
                },
                {
                    name: 'Permintaan Selain Sales Order',
                    link: '/gbj/nso'
                },
                {
                    name: 'Detail Permintaan Selain Sales Order',
                    link: '#'
                }
            ],
            headersProduk: [
                {
                    text: 'No',
                    value: 'no'
                },
                {
                    text: 'Nama Produk',
                    value: 'nama'
                },
                {
                    text: 'Jumlah',
                    value: 'jumlah'
                },
                {
                    text: 'Waktu Ambil',
                    value: 'waktu_ambil'
                },
                {
                    text: 'Aksi',
                    value: 'aksi'
                }
            ],
            searchProduk: '',
            produk: {
                header: {
                    id: 3,
                    no_permintaan: 'NSO-2021080003',
                    no_referensi: 'SO-2021080003',
                    tgl_permintaan: '21 Agustus 2021',
                    tgl_ambil: '2024-08-24',
                    tgl_close: '2024-08-25',
                    nama_bagian: 'Bagus-Produksi',
                    tujuan_permintaan: 'Lorem',
                    durasi: '1 Hari',
                    jenis: 'Peminjaman',
                    status: 'selesai',
                    ket: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nunc nec ultricies.',
                },
                produk: [
                    {
                        no: 1,
                        nama: 'Produk 1',
                        waktu_ambil: '2024-08-24 13:00:00',
                        jumlah: 2,
                    },
                    {
                        no: 2,
                        nama: 'Produk 2',
                        waktu_ambil: '2024-08-24 13:00:00',
                        jumlah: 3,
                    }
                ],
                pengembalian: [
                    {
                        no: 1,
                        nama: 'Produk 1',
                        waktu_kembali: '2024-08-24 13:00:00',
                        jumlah: 2,
                    },
                    {
                        no: 2,
                        nama: 'Produk 2',
                        waktu_kembali: '2024-08-24 13:00:00',
                        jumlah: 3,
                    }
                ]
            },
            headersNoSeri: [
                {
                    text: 'No',
                    value: 'no'
                },
                {
                    text: 'No Seri',
                    value: 'noseri'
                },
                {
                    text: 'Waktu Ambil',
                    value: 'waktu_ambil'
                },
            ],
            noseriPeminjaman: [],
            searchNoSeri: '',
            noseriPengembalian: [],
        }
    },
    methods: {
        detailProduk(item) {
            this.noseriPeminjaman = [
                {
                    no: 1,
                    noseri: 'NS-2021080001',
                    waktu_ambil: '2024-08-24 13:00:00',
                },
                {
                    no: 2,
                    noseri: 'NS-2021080002',
                    waktu_ambil: '2024-08-24 13:00:00',
                }
            ]
        },
    },
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <headerDetail :header="produk.header" />
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="pills-home-tab" data-toggle="pill" data-target="#pills-home"
                    type="button" role="tab" aria-controls="pills-home" aria-selected="true">Peminjaman</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="pills-profile-tab" data-toggle="pill" data-target="#pills-profile" type="button"
                    role="tab" aria-controls="pills-profile" aria-selected="false">Pengembalian</a>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                <div class="row">
                    <div :class="noseriPeminjaman.length > 0 ? 'col-8' : 'col-12'">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-row-reverse bd-highlight">
                                    <div class="p-2 bd-highlight">
                                        <input type="text" class="form-control" v-model="searchProduk"
                                            placeholder="Cari...">
                                    </div>
                                </div>
                                <data-table :headers="headersProduk" :items="produk.produk" :search="searchProduk">
                                    <template #item.waktu_ambil="{ item }">
                                        {{ dateTimeFormat(item.waktu_ambil) }}
                                    </template>
                                    <template #item.aksi="{ item }">
                                        <button class="btn btn-sm btn-outline-primary" @click="detailProduk(item)">
                                            <i class="fas fa-eye"></i>
                                            Detail
                                        </button>
                                    </template>
                                </data-table>
                            </div>
                        </div>
                    </div>
                    <div class="col-4" v-if="noseriPeminjaman.length > 0">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-row-reverse bd-highlight">
                                    <div class="p-2 bd-highlight">
                                        <input type="text" class="form-control" v-model="searchNoSeri"
                                            placeholder="Cari...">
                                    </div>
                                </div>
                                <data-table :headers="headersNoSeri" :items="noseriPeminjaman" :search="searchNoSeri">
                                    <template #item.waktu_ambil="{ item }">
                                        {{ dateTimeFormat(item.waktu_ambil) }}
                                    </template>
                                </data-table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                <div class="row">
                    <div class="col-8"></div>
                    <div class="col-4"></div>
                </div>
            </div>
        </div>
    </div>
</template>
