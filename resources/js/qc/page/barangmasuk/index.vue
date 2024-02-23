<script>
import axios from 'axios';
import Header from '../../components/header.vue';
import incoming from './dalamproses';
export default {
    components: {
        Header,
        incoming,
    },
    data() {
        return {
            title: 'Barang Masuk',
            breadcumbs: [
                {
                    name: 'Dashboard',
                    link: '/',
                },
                {
                    name: 'Barang Masuk',
                    link: '#',
                },
            ],
            dataIncoming: [
                {
                    id: 1,
                    bppb: 'BPPB-001',
                    produk: 'Produk 1',
                    jumlah: 500,
                    terisi: 80,
                    lolos: 40,
                    persentase_lolos: 50,
                    tidak_lolos: 20,
                    persentase_tidak_lolos: 25,
                }
            ],
        }
    },
    methods: {
        async getData() {
            // try {
            //     this.$store.dispatch('setLoading', true);
            //     const { data } = await axios.get('/api/prd/kirim')
            //     this.dataIncoming = data.map(item => {
            //         return {
            //             ...item,
            //             tgl_mulai: this.dateFormat(item.tanggal_mulai),
            //             tgl_selesai: this.dateFormat(item.tanggal_selesai),
            //             jumlah: `${item.jumlah} Unit`,
            //         }
            //     })
            // } catch (error) {
            //     console.log(error)
            // } finally {
            //     this.$store.dispatch('setLoading', false);
            // }
        }
    },
    created() {
        this.getData()
    }
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />

        <div class="card">
            <div class="card-body">
                <incoming :data="dataIncoming" @refresh="getData" />
            </div>
        </div>
    </div>
</template>