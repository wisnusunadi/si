<script>
import Header from '../../components/header.vue';
import KalibrasiInternal from './kalibrasi_internal'
import KalibrasiEksternal from './kalibrasi_eksternal'
import dalamProsesComponent from './kalibrasi_internal/dalamproses'
import selesaiProsesComponent from './kalibrasi_internal/selesaiproses'
import riwayat from './riwayat';
import axios from 'axios';
export default {
    components: {
        Header,
        KalibrasiInternal,
        KalibrasiEksternal,
        dalamProsesComponent,
        selesaiProsesComponent,
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
            dalamProses: [],
            selesaiProses: [
                {
                    order: 'LAB-001',
                    nama: 'PT. ABC',
                    jenis_pemilik: 'PT. ABC',
                    customer: 'PT. ABC',
                    produk: [
                        {
                            nama: 'BLOOD PRESSURE MONITOR',
                            tipe: 'ABPM50',
                            jumlah: 1,
                            noseri: [
                                {
                                    no_seri: '1234567890',
                                    hasil: 'ok',
                                    penguji: 'Budi',
                                    tanggal: '21 Februari 2024',
                                }
                            ]
                        }
                    ]
                },
            ],
            riwayat_kalibrasi: [],
            showTabs: 'dalamProses'
        }
    },
    methods: {
        async getData() {
            try {
                this.$store.dispatch('setLoading', true)
                const { data: dalamProses } = await axios.get('/api/labs/kalibrasi').then(res => res.data)
                // const { data: selesaiProses } = await axios.get(`/api/labs/kalibrasi/riwayat?years=${this.$store.state.years}`)
                const { data: riwayat_kalibrasi } = await axios.get(`/api/labs/riwayat_uji?years=${this.$store.state.years}`)
                this.dalamProses = dalamProses
                // this.selesaiProses = selesaiProses.map(item => {
                //     return {
                //         ...item,
                //         tanggal: this.formatDate(item.tgl_kalibrasi),
                //         produk: item.produk.map(produk => {
                //             return {
                //                 ...produk,
                //                 hasil: item.hasil == 'ok' ? 'Lolos Kalibrasi' : 'Tidak Lolos Kalibrasi'
                //             }
                //         })
                //     }
                // })
                this.riwayat_kalibrasi = riwayat_kalibrasi.map(item => {
                    return {
                        ...item,
                        tanggal: this.formatDate(item.tgl_kalibrasi),
                    }
                })
            } catch (error) {
                console.log(error)
            } finally {
                this.$store.dispatch('setLoading', false)
            }
        },
        changeYears() {
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
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="pills-home-tab" data-toggle="pill" data-target="#pills-home" type="button"
                    @click="showTabs = 'dalamProses'" role="tab" aria-controls="pills-home" aria-selected="true">Dalam
                    Proses</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="pills-profile-tab" data-toggle="pill" data-target="#pills-profile" type="button"
                @click="showTabs = 'selesaiProses'"
                    role="tab" aria-controls="pills-profile" aria-selected="false">Selesai Proses</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="pills-contact-tab" data-toggle="pill" data-target="#pills-contact" type="button"
                    @click="showTabs = 'riwayat'" role="tab" aria-controls="pills-contact" aria-selected="false">Riwayat</a>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                <!-- <KalibrasiInternal v-if="showTabs == 'kalibrasi'" /> -->
                <dalamProsesComponent :dataTable="dalamProses" v-if="showTabs == 'dalamProses'" />

            </div>
            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                <selesaiProsesComponent :selesaiProses="selesaiProses" v-if="showTabs == 'selesaiProses'"  @changeYears="getData" />
            </div>
            <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                <riwayat :produk="riwayat_kalibrasi" @changeYears="changeYears" v-if="showTabs == 'riwayat'" />
            </div>
        </div>
    </div>
</template>