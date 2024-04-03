<script>
import Header from '../../../../components/header'
import headerDetail from './header.vue'
import pengeluaran from './pengeluaran.vue';
import pengembalian from './pengembalian.vue';
import perubahan from './perubahan.vue';
import peminjaman from './peminjaman.vue';
export default {
    components: {
        Header,
        headerDetail,
        pengeluaran,
        pengembalian,
        perubahan,
        peminjaman
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
                        id: 1,
                        nama: 'Produk 1',
                        waktu_ambil: '2024-08-24 13:00:00',
                        noSeriSelected: [],
                        jumlah: 2,
                    },
                    {
                        no: 2,
                        id: 2,
                        nama: 'Produk 2',
                        waktu_ambil: '2024-08-24 13:00:00',
                        noSeriSelected: [],
                        jumlah: 3,
                    }
                ],
                perubahan: [
                    {
                        no: 1,
                        nama: 'Produk 1',
                        jumlah: 2,
                        tanggal_selesai: '2024-08-24',
                        hari: 1,
                        diterima: 'Ya',
                        alasan: '-',
                    },
                    {
                        no: 2,
                        nama: 'Produk 2',
                        jumlah: 3,
                        tanggal_selesai: '2024-04-03',
                        hari: 4,
                        diterima: 'Tidak',
                        alasan: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nunc nec ultricies.',
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
                ],
                peminjaman: [
                    {
                        no: 1,
                        nama: 'Produk 1',
                        jumlah: 2,
                        tgl_peminjaman: '2024-08-24',
                        tgl_pengambilan: '2024-08-25',
                        noseri: [
                            {
                                no: 1,
                                noseri: 'NS-2021080001',
                                tgl_pengembalian: '2024-08-24 13:00:00',
                            },
                            {
                                no: 2,
                                noseri: 'NS-2021080002',
                                tgl_pengembalian: '2024-08-24 13:00:00',
                            }
                        ]
                    },
                    {
                        no: 2,
                        nama: 'Produk 2',
                        jumlah: 3,
                        tgl_peminjaman: '2024-08-24',
                        tgl_pengambilan: '2024-08-25',
                        noseri: [
                            {
                                no: 1,
                                noseri: 'NS-2021080001',
                                tgl_pengembalian: '2024-08-24 13:00:00',
                            },
                            {
                                no: 2,
                                noseri: 'NS-2021080002',
                                tgl_pengembalian: '2024-08-24 13:00:00',
                            }
                        ]
                    }
                ]
            },
        }
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
                    type="button" role="tab" aria-controls="pills-home" aria-selected="true">Pengeluaran</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="pills-peminjaman-tab" data-toggle="pill" data-target="#pills-peminjaman"
                    type="button" role="tab" aria-controls="pills-peminjaman" aria-selected="false">Peminjaman</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="pills-profile-tab" data-toggle="pill" data-target="#pills-profile" type="button"
                    role="tab" aria-controls="pills-profile" aria-selected="false">Perubahan</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="pills-contact-tab" data-toggle="pill" data-target="#pills-contact" type="button"
                    role="tab" aria-controls="pills-contact" aria-selected="false">Pengembalian</a>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                <pengeluaran :pengeluaran="produk.produk" />
            </div>
            <div class="tab-pane fade" id="pills-peminjaman" role="tabpanel" aria-labelledby="pills-peminjaman-tab">
                <peminjaman :peminjaman="produk.peminjaman" />
            </div>
            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                <perubahan :perubahan="produk.perubahan" />
            </div>
            <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                <pengembalian :pengembalian="produk.pengembalian" />
            </div>
        </div>
    </div>
</template>
