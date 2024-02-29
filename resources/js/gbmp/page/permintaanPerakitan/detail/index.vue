<script>
import Header from '../../../components/header.vue';
import headerDetail from './header.vue';
export default {
    components: {
        Header,
        headerDetail
    },
    data() {
        return {
            title: 'Detail Permintaan Perakitan',
            breadcumbs: [
                {
                    name: 'Home',
                    link: '/'
                },
                {
                    name: 'Permintaan Perakitan',
                    link: '/gbmp/perakitan'
                },
                {
                    name: 'Detail Permintaan Perakitan',
                    link: '/permintaan-perakitan/detail'
                }
            ],
            produk: {
                header: {
                    id: 1,
                    no_permintaan: '20210621001',
                    no_bppb: '20210621001',
                    tgl_permintaan: '2023-09-23',
                    tanggal_permintaan: '23 September 2023',
                    tgl_akhir: '2023-10-23',
                    tanggal_akhir_persiapan: '23 Oktober 2023',
                    persentase: 25
                },
                produk: [
                    {
                        no: 1,
                        nama: 'Produk 1',
                        jumlah: 10
                    },
                    {
                        no: 2,
                        nama: 'Produk 2',
                        jumlah: 20
                    },
                    {
                        no: 3,
                        nama: 'Produk 3',
                        jumlah: 30
                    }
                ]
            },
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
                    text: 'Aksi',
                    value: 'aksi'
                }
            ],
            search: '',
        }
    },
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <headerDetail :header="produk.header" />
        <div class="card">
            <div class="card-body">
                <div class="d-flex bd-highlight">
                    <div class="p-2 flex-grow-1 bd-highlight"></div>
                    <div class="p-2 bd-highlight">
                        <input type="text" class="form-control" placeholder="Cari..." v-model="search">
                    </div>
                </div>
                <data-table :headers="headersProduk" :items="produk.produk" :search="search">
                    <template #item.aksi="{ item }">
                        <div>
                            <button class="btn btn-outline-primary btn-sm">
                                Transfer
                            </button>
                        </div>
                    </template>
                </data-table>
            </div>
        </div>
    </div>
</template>