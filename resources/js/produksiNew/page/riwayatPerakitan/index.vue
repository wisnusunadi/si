<script>
import axios from 'axios'
import Header from '../../components/header.vue'
import riwayat from './riwayat/index.vue'
import transfer from './transfer'
import moment from 'moment'
import perBPPB from './perBPPB'
export default {
    components: {
        Header,
        riwayat,
        transfer,
        perBPPB
    },
    data() {
        return {
            title: 'Riwayat Perakitan',
            breadcumbs: [
                {
                    name: 'Beranda',
                    link: '/produksi/dashboard'
                },
                {
                    name: 'Riwayat Perakitan',
                    link: '#'
                }
            ],
            riwayatRakit: [],
            transferSisa: [],
            riwayatBPPB: []
        }
    },
    methods: {
        async getData() {
            try {
                this.$store.dispatch('setLoading', true)
                const { data: riwayat } = await axios.get('/api/prd/riwayat_rakit')
                this.riwayatRakit = riwayat.map(item => {
                    return {
                        ...item,
                        tgl_rakit: this.dateFormat(item.date_in),
                        wkt_rakit: this.timeFormat(item.date_in),
                    }
                })
                const { data: transfer } = await axios.get('/api/prd/ajax_sisa')
                this.transferSisa = transfer.map(item => {
                    return {
                        ...item,
                        tgl_mulai: this.dateFormat(item.tanggal_mulai),
                        tgl_selesai: this.dateFormat(item.tanggal_selesai),
                    }
                })
                const { data: bppb } = await axios.get('/api/prd/fg/riwayat_bppb')
                this.riwayatBPPB = bppb.map(item => {
                    return {
                        ...item,
                        periode: this.periode(item.tanggal_mulai),
                        tgl_mulai: this.dateFormat(item.tanggal_mulai),
                        tgl_selesai: this.dateFormat(item.tanggal_selesai),
                        kurang_rakit: `Kurang ${item.jumlah - item.jumlah_rakit}`,
                        kurang: item.jumlah - item.jumlah_rakit,
                        jumlah_unit: `${item.jumlah} Unit`,
                        keterangan: item.keterangan ? item.keterangan : '-'
                    }
                })
            } catch (error) {
                console.log(error)
            } finally {
                this.$store.dispatch('setLoading', false)
            }
        },
        periode(date) {
            // change to yyyy-mm-dd format
            date = date.split(' ').reverse().join('-');
            return moment(date).lang('id').format('MMMM');
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
        <div class="card">
            <div class="card-body" v-if="!$store.state.loading">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="pills-home-tab" data-toggle="pill" data-target="#pills-home"
                            type="button" role="tab" aria-controls="pills-home" aria-selected="true">Riwayat
                            Perakitan</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-contact-tab" data-toggle="pill" data-target="#pills-contact"
                            type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Per
                            BPPB</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-profile-tab" data-toggle="pill" data-target="#pills-profile"
                            type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Transfer
                            Lain-lain</a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                        aria-labelledby="pills-home-tab">
                        <riwayat :riwayatRakit="riwayatRakit" />
                    </div>
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                        <transfer :transferSisa="transferSisa" />
                    </div>
                    <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                        <perBPPB :items="riwayatBPPB" />
                    </div>
                </div>
            </div>
            <div class="card-body" v-else>
                <div class="d-flex justify-content-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div></template>