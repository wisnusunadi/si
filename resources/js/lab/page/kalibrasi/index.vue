<script>
import Header from '../../components/header.vue';
import KalibrasiInternal from './kalibrasi_internal'
import KalibrasiEksternal from './kalibrasi_eksternal'
import riwayat from './riwayat';
import axios from 'axios';
export default {
    components: {
        Header,
        KalibrasiInternal,
        KalibrasiEksternal,
        riwayat
    },
    data() {
        return {
            title: 'DAFTAR UNIT KALIBRASI',
            breadcumbs: [
                {
                    name: 'Home',
                    link: '/'
                },
                {
                    name: 'Kalibrasi',
                    link: '/kalibrasi'
                }
            ],
            kalibrasiInternal: [],
            riwayat_kalibrasi: [
                { "id": 40, "so": "SO/EKAT/II/2024/15", "no_po": "potestlab", "no_order": "LAB-0001", "pemilik": "PERUSAHAAN", "pemilik_sertif": "Dinas Kesehatan", "tgl_transfer": "01 Februari 2024", "customer": "PT. EMIINDO Jaya Bersama", "jenis_transaksi": "Internal", "detail": [{ "id": 28802, "nama": "BLOOD PRESSURE MONITOR", "tipe": "ABPM50", "jumlah": 2, "noseri": [{ "id": 104, "no_seri": "PM622AB0071", "tgl_masuk": "2024-02-01", "status": "ok" }, { "id": 105, "no_seri": "PM622AB0050", "tgl_masuk": "2024-02-01", "status": "ok" }] }], "tgl": "01 Februari 2024", "jenis_transfer": "Internal" }
            ],
        }
    },
    methods: {
        async getData() {
            try {
                this.$store.dispatch('setLoading', true)
                const { data: kalibrasiInternal } = await axios.get('/api/labs/kalibrasi').then(res => res.data)
                this.kalibrasiInternal = kalibrasiInternal
            } catch (error) {
                console.log(error)
            } finally {
                this.$store.dispatch('setLoading', false)
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
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist" v-if="!$store.state.loading">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="pills-home-tab" data-toggle="pill" data-target="#pills-home" type="button"
                    role="tab" aria-controls="pills-home" aria-selected="true">Internal</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="pills-profile-tab" data-toggle="pill" data-target="#pills-profile" type="button"
                    role="tab" aria-controls="pills-profile" aria-selected="false">Eksternal</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="pills-contact-tab" data-toggle="pill" data-target="#pills-contact" type="button"
                    role="tab" aria-controls="pills-contact" aria-selected="false">Riwayat</a>
            </li>
        </ul>
        <div class="d-flex justify-content-center" v-if="$store.state.loading">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                <KalibrasiInternal :dataTable="kalibrasiInternal" />
            </div>
            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                <KalibrasiEksternal />
            </div>
            <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                <riwayat :produk="riwayat_kalibrasi" />
            </div>
        </div>
    </div>
</template>