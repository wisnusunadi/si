<script>
import Header from '../../components/header.vue';
import Table from './table.vue';
export default {
    components: {
        Header,
        Table
    },
    data() {
        return {
            title: 'Kamus Produk',
            breadcumbs: [
                {
                    name: 'Beranda',
                    link: '/produksi/dashboard'
                },
                {
                    name: 'Kamus Produk',
                    link: '#'
                }
            ],
            years: new Date().getFullYear(),
            search: '',
            headers: [
                {
                    text: 'No',
                    value: 'no',
                },
                {
                    text: 'Kode Produk',
                    value: 'kode_produk',
                },
                {
                    text: 'Nama Produk',
                    value: 'nama_produk',
                },
                {
                    text: `Jumlah No. Seri Dirakit`,
                    value: 'jumlah_no_seri_dirakit',
                }
            ],
            // data produk tampil semua meskipun jumlah masih kosong, tujuannya untuk melihat kode produk
            items: [ 
                {
                    no: 1,
                    kode_produk: 'PM06',
                    nama_produk: 'ABPM50',
                    jumlah_no_seri_dirakit_terjadwal: 1,
                    jumlah_no_seri_dirakit_tidak_terjadwal: 1,
                    jumlah_no_seri_dirakit: 2,
                },
                {
                    no: 2,
                    kode_produk: 'AP01',
                    nama_produk: 'APRON (Full)',
                    jumlah_no_seri_dirakit_terjadwal: 1,
                    jumlah_no_seri_dirakit_tidak_terjadwal: 1,
                    jumlah_no_seri_dirakit: 2,
                },
            ],
        }
    },
    computed: {
        fiveYearsBefore() {
            let year = []
            for (let i = 0; i < 5; i++) {
                year.push(new Date().getFullYear() - i)
            }
            return year
        },
    }
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <div class="card">
            <div class="card-body">
                <div class="d-flex bd-highlight mb-3">
                    <div class="mr-auto p-2 bd-highlight">
                        <select v-model="years" class="form-control">
                            <option v-for="year in fiveYearsBefore" :key="year" :value="year">{{ year }}</option>
                        </select>
                    </div>
                    <div class="p-2 bd-highlight"> <input type="text" v-model="search" class="form-control"
                            placeholder="Cari...">
                    </div>
                </div>
                <Table :dataTable="items" :search="search" />
            </div>
        </div>
    </div>
</template>