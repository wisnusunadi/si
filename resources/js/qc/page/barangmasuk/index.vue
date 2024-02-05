<script>
import axios from 'axios';
import Header from '../../components/header.vue';
import incoming from './incoming';
import riwayat from './riwayat/index.vue';
export default {
    components: {
        Header,
        incoming,
        riwayat,
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
            dataIncoming: [],
            dataRiwayat: [{
                tgl_penyerahan: '24 Februari 2024',
                wkt_penyerahan: '12:00',
                no_bppb: 'BPPB/2020/02/001',
                produk: 'Produk 1',
                jml: '100',
            }],    
        }
    },
    methods: {
        async getData() {
            try {
                this.$store.dispatch('setLoading', true);
                const { data } = await axios.get('/api/prd/kirim')
                this.dataIncoming = data.map(item => {
                    return {
                        ...item,
                        tgl_mulai: this.dateFormat(item.tanggal_mulai),
                        tgl_selesai: this.dateFormat(item.tanggal_selesai),
                        jumlah: `${item.jumlah} Unit`,
                    }
                })
            } catch (error) {
                console.log(error)
            } finally {
                this.$store.dispatch('setLoading', false);
            }
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
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="pills-home-tab" data-toggle="pill" data-target="#pills-home"
                            type="button" role="tab" aria-controls="pills-home" aria-selected="true">Barang Masuk</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-profile-tab" data-toggle="pill" data-target="#pills-profile"
                            type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Riwayat</a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                        <incoming :data="dataIncoming" @refresh="getData" />
                    </div>
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                        <riwayat :data="dataRiwayat" />
                    </div>
            </div>
        </div>
    </div>
</div></template>