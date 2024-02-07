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
            riwayat_kalibrasi: [],
            years: new Date().getFullYear()
        }
    },
    methods: {
        async getData() {
            try {
                this.$store.dispatch('setLoading', true)
                const { data: kalibrasiInternal } = await axios.get('/api/labs/kalibrasi').then(res => res.data)
                const { data: riwayat_kalibrasi } = await axios.get(`/api/labs/riwayat_uji?years=${this.years}`)
                this.kalibrasiInternal = kalibrasiInternal
                this.riwayat_kalibrasi = riwayat_kalibrasi.map(item => {
                    return {
                        ...item,
                        tanggal: this.formatDate(item.tgl_kalibrasi),
                        produk: item.produk.map(produk => {
                            return {
                                ...produk,
                                hasil: item.hasil == 'ok' ? 'Lolos Kalibrasi' : 'Tidak Lolos Kalibrasi'
                            }
                        })
                    }
                })
            } catch (error) {
                console.log(error)
            } finally {
                this.$store.dispatch('setLoading', false)
            }
        },
        changeYears(years) {
            this.years = years
            this.getData()
        },

    },
    created() {
        this.getData()
    }
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist" >
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
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                <KalibrasiInternal :dataTable="kalibrasiInternal" />
            </div>
            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                <KalibrasiEksternal />
            </div>
            <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                <riwayat :produk="riwayat_kalibrasi" @changeYears="changeYears" />
            </div>
        </div>
    </div>
</template>