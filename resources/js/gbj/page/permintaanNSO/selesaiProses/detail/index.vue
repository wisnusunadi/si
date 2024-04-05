<script>
import Header from '../../../../components/header'
import headerDetail from './header.vue'
import pengeluaran from './pengeluaran/index.vue';
import pengembalian from './pengembalian.vue';
import perubahan from './perubahan/index.vue';
import peminjaman from './peminjaman.vue';
import riwayatpengajuan from './riwayatpengajuan/index.vue';
export default {
    components: {
        Header,
        headerDetail,
        pengeluaran,
        pengembalian,
        perubahan,
        peminjaman,
        riwayatpengajuan
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
            produk: null,
        }
    },
    methods: {
        dataProdukDetail() {
            console.log(this.$route.params)
            if (!this.$route.params.selesai && this.$route.params.jenis == 'Peminjaman') {
                this.produk = {
                    header: {
                        id: 3,
                        no_permintaan: 'NSO-2021080003',
                        no_referensi: 'SO-2021080003',
                        tgl_permintaan: '21 Agustus 2021',
                        tgl_ambil: '2024-08-23',
                        tgl_close: '2024-08-26',
                        nama: 'Bagus',
                        bagian: 'Produksi',
                        tujuan_permintaan: 'Lorem',
                        durasi: '1 Hari',
                        lebih_durasi: 0,
                        jenis: 'Peminjaman',
                        status: 'selesai',
                        ket: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nunc nec ultricies.',
                    },
                    produk: [
                        {
                            no: 1,
                            id: 1,
                            gbj_id: 18,
                            nama: 'Produk 1',
                            waktu_ambil: '2024-08-03 13:00:00',
                            noSeriSelected: [],
                            jumlah: 2,
                            jumlah_sisa: 0,
                        },
                        {
                            no: 2,
                            id: 2,
                            gbj_id: 19,
                            nama: 'Produk 2',
                            waktu_ambil: '2024-08-03 13:00:00',
                            noSeriSelected: [],
                            jumlah: 3,
                            jumlah_sisa: 1,
                        },
                        {
                            no: 3,
                            id: 3,
                            gbj_id: 19,
                            nama: 'Produk 3',
                            waktu_ambil: null,
                            noSeriSelected: [],
                            jumlah: 3,
                            jumlah_sisa: 1,
                        }
                    ],
                    perubahan: [
                        {
                            no: 1,
                            no_ubah: 'UBAH-2021080003',
                            nama: 'Produk 3',
                            jumlah: 3,
                            tanggal_selesai: '2024-04-03',
                            hari: 4,
                            hasil: null,
                            alasan: null,
                        },
                        {
                            no: 2,
                            no_ubah: 'UBAH-2021080003',
                            nama: 'Produk 1',
                            jumlah: 2,
                            tanggal_selesai: '2024-08-23',
                            hari: 1,
                            hasil: 'Diterima',
                            alasan: '-',
                        },
                        {
                            no: 3,
                            no_ubah: 'UBAH-2021080003',
                            nama: 'Produk 2',
                            jumlah: 3,
                            tanggal_selesai: '2024-04-03',
                            hari: 4,
                            hasil: 'Ditolak',
                            alasan: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nunc nec ultricies.',
                        },
                    ],
                    pengembalian: [
                        {
                            no: 1,
                            nama: 'Produk 1',
                            waktu_kembali: '2024-09-02 13:00:00',
                            tgl_close: '2024-08-23 13:00:00',
                            jumlah: 2,
                        },
                        {
                            no: 2,
                            nama: 'Produk 2',
                            waktu_kembali: '2024-08-22 13:00:00',
                            tgl_close: '2024-08-23 13:00:00',
                            jumlah: 3,
                        }
                    ],
                    peminjaman: [
                        {
                            no: 1,
                            expanded: false,
                            nama: 'Produk 1',
                            jumlah: 2,
                            tgl_peminjaman: '2024-08-23',
                            tgl_pengambilan: '2024-08-25',
                            noseri: [
                                {
                                    no: 1,
                                    noseri: 'TR12345678',
                                    tgl_pengembalian: '2024-08-23 13:00:00',
                                },
                                {
                                    no: 2,
                                    noseri: 'TE12345678',
                                    tgl_pengembalian: '2024-08-23 13:00:00',
                                }
                            ]
                        },
                        {
                            no: 2,
                            expanded: false,
                            nama: 'Produk 2',
                            jumlah: 3,
                            tgl_peminjaman: '2024-08-23',
                            tgl_pengambilan: '2024-08-25',
                            noseri: [
                                {
                                    no: 1,
                                    noseri: 'TL12345678',
                                    tgl_pengembalian: '2024-08-23 13:00:00',
                                },
                                {
                                    no: 2,
                                    noseri: 'TM12345678',
                                    tgl_pengembalian: '2024-08-23 13:00:00',
                                }
                            ]
                        },
                        {
                            no: 3,
                            expanded: false,
                            nama: 'Produk 3',
                            jumlah: 3,
                            tgl_peminjaman: '2024-08-23',
                            tgl_pengambilan: null,
                            noseri: [
                                {
                                    no: 1,
                                    noseri: 'TL12345678',
                                    tgl_pengembalian: null,
                                },
                                {
                                    no: 2,
                                    noseri: 'TM12345678',
                                    tgl_pengembalian: null,
                                }
                            ]
                        },
                        {
                            no: 4,
                            expanded: false,
                            nama: 'Produk 4',
                            jumlah: 3,
                            tgl_peminjaman: '2024-08-23',
                            tgl_pengambilan: null,
                            noseri: []
                        }
                    ],
                    riwayatpengajuan: [
                        {
                            no: 1,
                            no_perubahan: 'UBAH-2021080003',
                            versi: 'V3',
                            tgl_pengajuan: '2024-08-23',
                            feedback: [
                                'Perubahan Produk dari Tensione ke Digit One',
                                'Perubahan jumlah produk tensione dari 3 ke 5'
                            ],
                            hasil: null,
                            alasan: null,
                        },
                        {
                            no: 2,
                            no_perubahan: 'UBAH-2021080003',
                            versi: 'V2',
                            tgl_pengajuan: '2024-08-23',
                            feedback: [
                                'Perubahan Produk dari Tensione ke Digit One',
                                'Perubahan jumlah produk tensione dari 3 ke 5'
                            ],
                            hasil: 'Ditolak',
                            alasan: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nunc nec ultricies.',
                        },
                        {
                            no: 3,
                            no_perubahan: 'UBAH-2021080003',
                            versi: 'V1',
                            tgl_pengajuan: '2024-08-23',
                            feedback: [
                                'Perubahan Produk dari Tensione ke Digit One',
                                'Perubahan jumlah produk tensione dari 3 ke 5'
                            ],
                            hasil: 'Diterima',
                            alasan: '-',
                        },


                    ]
                }
            } else if (this.$route.params.selesai && this.$route.params.jenis == 'Peminjaman') {
                this.produk = {
                    header: {
                        id: 3,
                        no_permintaan: 'NSO-2021080003',
                        no_referensi: 'SO-2021080003',
                        tgl_permintaan: '21 Agustus 2021',
                        tgl_ambil: '2024-08-23',
                        tgl_close: '2024-08-26',
                        nama: 'Bagus',
                        bagian: 'Produksi',
                        tujuan_permintaan: 'Lorem',
                        durasi: '1 Hari',
                        lebih_durasi: 0,
                        jenis: 'Peminjaman',
                        status: 'selesai',
                        ket: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nunc nec ultricies.',
                    },
                    produk: [
                        {
                            no: 1,
                            id: 1,
                            gbj_id: 18,
                            nama: 'Produk 1',
                            waktu_ambil: '2024-08-03 13:00:00',
                            noSeriSelected: [],
                            jumlah: 2,
                            jumlah_sisa: 0,
                        },
                        {
                            no: 2,
                            id: 2,
                            gbj_id: 19,
                            nama: 'Produk 2',
                            waktu_ambil: '2024-08-03 13:00:00',
                            noSeriSelected: [],
                            jumlah: 3,
                            jumlah_sisa: 1,
                        }
                    ],
                    perubahan: [
                        {
                            no: 1,
                            no_ubah: 'UBAH-2021080003',
                            nama: 'Produk 1',
                            jumlah: 2,
                            tanggal_selesai: '2024-08-23',
                            hari: 1,
                            hasil: 'Diterima',
                            alasan: '-',
                        },
                        {
                            no: 2,
                            no_ubah: 'UBAH-2021080003',
                            nama: 'Produk 2',
                            jumlah: 3,
                            tanggal_selesai: '2024-04-03',
                            hari: 4,
                            hasil: 'Ditolak',
                            alasan: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nunc nec ultricies.',
                        },
                    ],
                    pengembalian: [
                        {
                            no: 1,
                            nama: 'Produk 1',
                            waktu_kembali: '2024-09-02 13:00:00',
                            tgl_close: '2024-08-23 13:00:00',
                            jumlah: 2,
                        },
                        {
                            no: 2,
                            nama: 'Produk 2',
                            waktu_kembali: '2024-08-22 13:00:00',
                            tgl_close: '2024-08-23 13:00:00',
                            jumlah: 3,
                        }
                    ],
                    peminjaman: [
                        {
                            no: 1,
                            expanded: false,
                            nama: 'Produk 1',
                            jumlah: 2,
                            tgl_peminjaman: '2024-08-23',
                            tgl_pengambilan: '2024-08-25',
                            noseri: [
                                {
                                    no: 1,
                                    noseri: 'TR12345678',
                                    tgl_pengembalian: '2024-08-23 13:00:00',
                                },
                                {
                                    no: 2,
                                    noseri: 'TE12345678',
                                    tgl_pengembalian: '2024-08-23 13:00:00',
                                }
                            ]
                        },
                        {
                            no: 2,
                            expanded: false,
                            nama: 'Produk 2',
                            jumlah: 3,
                            tgl_peminjaman: '2024-08-23',
                            tgl_pengambilan: '2024-08-25',
                            noseri: [
                                {
                                    no: 1,
                                    noseri: 'TL12345678',
                                    tgl_pengembalian: '2024-08-23 13:00:00',
                                },
                                {
                                    no: 2,
                                    noseri: 'TM12345678',
                                    tgl_pengembalian: '2024-08-23 13:00:00',
                                }
                            ]
                        }
                    ],
                    riwayatpengajuan: [
                        {
                            no: 2,
                            no_perubahan: 'UBAH-2021080002',
                            versi: 'V2',
                            tgl_pengajuan: '2024-08-23',
                            feedback: [
                                'Perubahan Produk dari Tensione ke Digit One',
                                'Perubahan jumlah produk tensione dari 3 ke 5'
                            ],
                            hasil: 'Ditolak',
                            alasan: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nunc nec ultricies.',
                        },
                        {
                            no: 1,
                            no_perubahan: 'UBAH-2021080003',
                            versi: 'V1',
                            tgl_pengajuan: '2024-08-23',
                            feedback: [
                                'Perubahan Produk dari Tensione ke Digit One',
                                'Perubahan jumlah produk tensione dari 3 ke 5'
                            ],
                            hasil: 'Diterima',
                            alasan: '-',
                        },
                    ]
                }
            } else if (!this.$route.params.selesai && this.$route.params.jenis == 'Permintaan') {
                this.produk = {
                    header: {
                        id: 3,
                        no_permintaan: 'NSO-2021080003',
                        no_referensi: 'SO-2021080003',
                        tgl_permintaan: '21 Agustus 2021',
                        tgl_ambil: '2024-08-23',
                        tgl_close: '2024-08-26',
                        nama: 'Bagus',
                        bagian: 'Produksi',
                        tujuan_permintaan: 'Lorem',
                        durasi: '1 Hari',
                        lebih_durasi: 0,
                        jenis: 'Peminjaman',
                        status: 'selesai',
                        ket: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nunc nec ultricies.',
                    },
                    produk: [
                        {
                            no: 1,
                            id: 1,
                            gbj_id: 18,
                            nama: 'Produk 1',
                            waktu_ambil: '2024-08-03 13:00:00',
                            noSeriSelected: [],
                            jumlah: 2,
                            jumlah_sisa: 0,
                        },
                        {
                            no: 2,
                            id: 2,
                            gbj_id: 19,
                            nama: 'Produk 2',
                            waktu_ambil: '2024-08-03 13:00:00',
                            noSeriSelected: [],
                            jumlah: 3,
                            jumlah_sisa: 1,
                        },
                        {
                            no: 3,
                            id: 3,
                            gbj_id: 19,
                            nama: 'Produk 3',
                            waktu_ambil: null,
                            noSeriSelected: [],
                            jumlah: 3,
                            jumlah_sisa: 1,
                        }
                    ],
                    perubahan: [],
                    pengembalian: [],
                    peminjaman: [],
                    riwayatpengajuan: [
                        {
                            no: 1,
                            no_perubahan: 'UBAH-2021080003',
                            versi: 'V3',
                            tgl_pengajuan: '2024-08-23',
                            feedback: [
                                'Perubahan Produk dari Tensione ke Digit One',
                                'Perubahan jumlah produk tensione dari 3 ke 5'
                            ],
                            hasil: null,
                            alasan: null,
                        },
                        {
                            no: 2,
                            no_perubahan: 'UBAH-2021080003',
                            versi: 'V2',
                            tgl_pengajuan: '2024-08-23',
                            feedback: [
                                'Perubahan Produk dari Tensione ke Digit One',
                                'Perubahan jumlah produk tensione dari 3 ke 5'
                            ],
                            hasil: 'Ditolak',
                            alasan: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nunc nec ultricies.',
                        },
                        {
                            no: 3,
                            no_perubahan: 'UBAH-2021080003',
                            versi: 'V1',
                            tgl_pengajuan: '2024-08-23',
                            feedback: [
                                'Perubahan Produk dari Tensione ke Digit One',
                                'Perubahan jumlah produk tensione dari 3 ke 5'
                            ],
                            hasil: 'Diterima',
                            alasan: '-',
                        },


                    ]
                }
            } else
                // if (this.$route.params.selesai && this.$route.params.jenis == 'Permintaan')
                {
                this.produk = {
                    header: {
                        id: 3,
                        no_permintaan: 'NSO-2021080003',
                        no_referensi: 'SO-2021080003',
                        tgl_permintaan: '21 Agustus 2021',
                        tgl_ambil: '2024-08-23',
                        tgl_close: '2024-08-26',
                        nama: 'Bagus',
                        bagian: 'Produksi',
                        tujuan_permintaan: 'Lorem',
                        durasi: '1 Hari',
                        lebih_durasi: 0,
                        jenis: 'Peminjaman',
                        status: 'selesai',
                        ket: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nunc nec ultricies.',
                    },
                    produk: [
                        {
                            no: 1,
                            id: 1,
                            gbj_id: 18,
                            nama: 'Produk 1',
                            waktu_ambil: '2024-08-03 13:00:00',
                            noSeriSelected: [],
                            jumlah: 2,
                            jumlah_sisa: 0,
                        },
                        {
                            no: 2,
                            id: 2,
                            gbj_id: 19,
                            nama: 'Produk 2',
                            waktu_ambil: '2024-08-03 13:00:00',
                            noSeriSelected: [],
                            jumlah: 3,
                            jumlah_sisa: 1,
                        }
                    ],
                    perubahan: [],
                    pengembalian: [],
                    peminjaman: [],
                    riwayatpengajuan: [
                        {
                            no: 2,
                            no_perubahan: 'UBAH-2021080002',
                            versi: 'V2',
                            tgl_pengajuan: '2024-08-23',
                            feedback: [
                                'Perubahan Produk dari Tensione ke Digit One',
                                'Perubahan jumlah produk tensione dari 3 ke 5'
                            ],
                            hasil: 'Ditolak',
                            alasan: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nunc nec ultricies.',
                        },
                        {
                            no: 1,
                            no_perubahan: 'UBAH-2021080003',
                            versi: 'V1',
                            tgl_pengajuan: '2024-08-23',
                            feedback: [
                                'Perubahan Produk dari Tensione ke Digit One',
                                'Perubahan jumlah produk tensione dari 3 ke 5'
                            ],
                            hasil: 'Diterima',
                            alasan: '-',
                        },
                    ]
                }
            }
        }
    },
    created() {
        this.dataProdukDetail()
    }
}
</script>
<template>
    <div class="body">
        <Header :title="title" :breadcumbs="breadcumbs" />
        <headerDetail :header="produk.header" />
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="pills-riwayat-tab" data-toggle="pill" data-target="#pills-riwayat"
                    type="button" role="tab" aria-controls="pills-riwayat" aria-selected="false">Pengajuan</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="pills-home-tab" data-toggle="pill" data-target="#pills-home" type="button"
                    role="tab" aria-controls="pills-home" aria-selected="true">Pengeluaran</a>
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
            <div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                <pengeluaran :pengeluaran="produk.produk" />
            </div>
            <div class="tab-pane fade" id="pills-peminjaman" role="tabpanel" aria-labelledby="pills-peminjaman-tab">
                <peminjaman :peminjaman="produk.peminjaman" />
            </div>
            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                <perubahan :perubahan="produk.perubahan" />
            </div>
            <div class="tab-pane fade show active" id="pills-riwayat" role="tabpanel"
                aria-labelledby="pills-riwayat-tab">
                <riwayatpengajuan :pengajuan="produk.riwayatpengajuan" />
            </div>
            <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                <pengembalian :pengembalian="produk.pengembalian" />
            </div>
        </div>
    </div>
</template>
<style>
.body {
    font-size: 14px;
}
</style>
