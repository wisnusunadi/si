<script>
import Header from '../../../../components/header.vue'
import headerDetail from './header.vue';
import produk from './produk.vue'
export default {
    components: { Header, headerDetail, produk },
    data() {
        return {
            title: 'Detail Proses',
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
                    name: 'Detail Proses',
                    link: '/produksi/permintaan_goods/detail'
                }
            ],
            detail: null
        }
    },
    methods: {
        getData() {
            let status = this.$route.params.status;
            switch (status) {
                case 'batal':
                    this.detail = {
                        headers: {
                            id: 1,
                            no_permintaan: 'NSO-2021080001',
                            no_referensi: 'SO-2021080001',
                            tgl_permintaan: '2021-08-01',
                            tujuan_permintaan: 'Lorem Ipsum',
                            tgl_kebutuhan: '2021-08-01',
                            tgl_close: '2024-08-02',
                            status: 'batal',
                            durasi: '1 hari',
                            jenis: 'peminjaman',
                            tujuan: 'Lorem Ipsum',
                        },
                        produk: [
                            {
                                no: 1,
                                nama: 'Produk 1',
                                jumlah: 1,
                            },
                            {
                                no: 2,
                                nama: 'Produk 2',
                                jumlah: 2,
                            }
                        ]
                    }
                    break;
                case 'menunggu_persetujuan_atasan':
                    this.detail = {
                        headers: {
                            id: 2,
                            no_permintaan: 'NSO-2021080002',
                            no_referensi: 'SO-2021080002',
                            tgl_permintaan: '2021-08-02',
                            tujuan_permintaan: 'Lorem Ipsum',
                            tgl_kebutuhan: '2021-08-02',
                            status: 'menunggu_persetujuan_atasan',
                            durasi: null,
                            jenis: 'permintaan',
                            tujuan: 'Lorem Ipsum',
                        },
                        produk: [
                            {
                                no: 1,
                                nama: 'Produk 1',
                                jumlah: 1,
                            },
                            {
                                no: 2,
                                nama: 'Produk 2',
                                jumlah: 2,
                            }
                        ]
                    }
                    break;
                case 'permintaan_ditolak_atasan':
                    this.detail = {
                        headers: {
                            id: 3,
                            no_permintaan: 'NSO-2021080003',
                            no_referensi: 'SO-2021080003',
                            tgl_permintaan: '2021-08-03',
                            tujuan_permintaan: 'Lorem Ipsum',
                            tgl_kebutuhan: '2021-08-03',
                            tgl_close: '2024-08-04',
                            status: 'permintaan_ditolak_atasan',
                            durasi: '2 hari',
                            jenis: 'peminjaman',
                            alasan: 'Lorem Ipsum',
                            tujuan: 'Lorem Ipsum',
                        },
                        produk: [
                            {
                                no: 1,
                                nama: 'Produk 1',
                                jumlah: 1,
                            },
                            {
                                no: 2,
                                nama: 'Produk 2',
                                jumlah: 2,
                            }
                        ]
                    }
                    break;
                case 'menunggu_persetujuan_gudang':
                    this.detail = {
                        headers: {
                            id: 4,
                            no_permintaan: 'NSO-2021080004',
                            no_referensi: 'SO-2021080004',
                            tgl_permintaan: '2021-08-04',
                            tujuan_permintaan: 'Lorem Ipsum',
                            tgl_kebutuhan: '2021-08-04',
                            status: 'menunggu_persetujuan_gudang',
                            durasi: null,
                            jenis: 'permintaan',
                            tujuan: 'Lorem Ipsum',
                        },
                        produk: [
                            {
                                no: 1,
                                nama: 'Produk 1',
                                jumlah: 1,
                            },
                            {
                                no: 2,
                                nama: 'Produk 2',
                                jumlah: 2,
                            }
                        ]
                    }
                    break;
                case 'permintaan_gagal_diacc':
                    this.detail = {
                        headers: {
                            id: 5,
                            no_permintaan: 'NSO-2021080005',
                            no_referensi: 'SO-2021080005',
                            tgl_permintaan: '2021-08-05',
                            tujuan_permintaan: 'Lorem Ipsum',
                            tgl_kebutuhan: '2021-08-05',
                            tgl_close: '2024-08-06',
                            status: 'permintaan_gagal_diacc',
                            durasi: '3 hari',
                            jenis: 'peminjaman',
                            alasan: 'Lorem Ipsum',
                        },
                        produk: [
                            {
                                no: 1,
                                nama: 'Produk 1',
                                jumlah: 1,
                            },
                            {
                                no: 2,
                                nama: 'Produk 2',
                                jumlah: 2,
                            }
                        ]
                    }
                    break;
                case 'permintaan_ditolak_gudang':
                    this.detail = {
                        headers: {
                            id: 6,
                            no_permintaan: 'NSO-2021080006',
                            no_referensi: 'SO-2021080006',
                            tgl_permintaan: '2021-08-06',
                            tujuan_permintaan: 'Lorem Ipsum',
                            tgl_kebutuhan: '2021-08-06',
                            status: 'permintaan_ditolak_gudang',
                            durasi: '3 hari',
                            jenis: 'permintaan',
                            alasan: 'Lorem Ipsum',
                        },
                        produk: [
                            {
                                no: 1,
                                nama: 'Produk 1',
                                jumlah: 1,
                            },
                            {
                                no: 2,
                                nama: 'Produk 2',
                                jumlah: 2,
                            }
                        ]
                    }
                    break;
            }
        }
    },
    created() {
        this.getData();
    },
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <headerDetail :header="detail.headers" />
        <produk :detail="detail" />
    </div>
</template>