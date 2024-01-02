<script>
import Header from '../../components/header.vue';
import perakitan from './perakitan';
import riwayat from './riwayat';
import axios from 'axios';
import moment from 'moment'
import fleksibel from './fleksibel'
export default {
    components: {
        Header,
        perakitan,
        riwayat,
        fleksibel
    },
    data() {
        return {
            title: 'Perakitan Berlangsung',
            breadcumbs: [
                {
                    name: 'Beranda',
                    link: '/produksi/dashboard'
                },
                {
                    name: 'Perakitan Berlangsung',
                    link: '#'
                },
            ],
            dataPerakitan: [],
            dataRiwayat: [],
            tanggalAwal: moment().startOf('month').format('YYYY-MM-DD'),
            tanggalAkhir: moment().endOf('month').format('YYYY-MM-DD'),
            loadingRiwayat: false,
        }
    },
    methods: {
        async getData() {
            try {
                this.$store.dispatch('setLoading', true)
                const { data: perakitan } = await axios.get('/api/prd/ongoing')
                this.dataPerakitan = perakitan.map(item => {
                    return {
                        ...item,
                        periode: this.periode(item.tanggal_mulai),
                        tgl_mulai: this.dateFormat(item.tanggal_mulai),
                        tgl_selesai: this.dateFormat(item.tanggal_selesai),
                        kurang_rakit: `Kurang ${item.jumlah - item.jumlah_rakit}`,
                        kurang: item.jumlah - item.jumlah_rakit,
                        jumlah_unit: `${item.jumlah} Unit`
                    }
                })

                const { data: riwayat } = await axios.get('/api/prd/fg/riwayat?tanggalAwal=' + this.tanggalAwal + '&tanggalAkhir=' + this.tanggalAkhir)
                this.dataRiwayat = riwayat.map(item => {
                    return {
                        ...item,
                        tgl_buat: this.dateFormat(item.tgl_buat),
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
        async updateRiwayat() {
            try {
                this.loadingRiwayat = true
                const { data } = await axios.get('/api/prd/fg/riwayat?tanggalAwal=' + this.tanggalAwal + '&tanggalAkhir=' + this.tanggalAkhir)
                this.dataRiwayat = data.map(item => {
                    return {
                        ...item,
                        tgl_buat: this.dateFormat(item.tgl_buat),
                    }
                })
            } catch (error) {
                console.log(error)
            } finally {
                this.loadingRiwayat = false
            }
        },
        updateTanggal(tanggal) {
            const { tanggalAwal, tanggalAkhir } = tanggal
            this.tanggalAwal = tanggalAwal
            this.tanggalAkhir = tanggalAkhir
            this.updateRiwayat()
        },
    },
    mounted() {
        this.getData()
    },
}
</script>
<template>
    <div>
        <Header :breadcumbs="breadcumbs" :title="title" />
        <!-- <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="pills-terjadwal-tab" data-toggle="pill" data-target="#pills-terjadwal" type="button"
                    role="tab" aria-controls="pills-terjadwal" aria-selected="true">Perakitan Terjadwal</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="pills-fleksibel-tab" data-toggle="pill" data-target="#pills-fleksibel" type="button"
                    role="tab" aria-controls="pills-fleksibel" aria-selected="false">Perakitan Fleksibel</a>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-terjadwal" role="tabpanel" aria-labelledby="pills-terjadwal-tab">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist" v-if="!$store.state.loading">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="pills-home-tab" data-toggle="pill" data-target="#pills-home"
                                    type="button" role="tab" aria-controls="pills-home" aria-selected="true">Perakitan</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="pills-profile-tab" data-toggle="pill" data-target="#pills-profile"
                                    type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Riwayat</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent" v-if="!$store.state.loading">
                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                aria-labelledby="pills-home-tab">
                                <perakitan :dataTable="dataPerakitan" @refresh="getData" />
                            </div>
                            <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                                aria-labelledby="pills-profile-tab">
                                <riwayat :dataRiwayat="dataRiwayat" :tanggalAwal="tanggalAwal" :tanggalAkhir="tanggalAkhir"
                                    @updateTanggal="updateTanggal" v-if="!loadingRiwayat" />
                                <div class="spinner-border" role="status" v-else>
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>
                        <div v-else>
                            <div class="spinner-border" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="pills-fleksibel" role="tabpanel" aria-labelledby="pills-fleksibel-tab">
                <div class="card">
                    <div class="card-body">
                        <fleksibel />
                    </div>
                </div>
            </div>
        </div> -->
                        <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist" v-if="!$store.state.loading">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" id="pills-home-tab" data-toggle="pill" data-target="#pills-home"
                                        type="button" role="tab" aria-controls="pills-home" aria-selected="true">Perakitan</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="pills-profile-tab" data-toggle="pill" data-target="#pills-profile"
                                        type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Riwayat</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="pills-tabContent" v-if="!$store.state.loading">
                                <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                    aria-labelledby="pills-home-tab">
                                    <perakitan :dataTable="dataPerakitan" @refresh="getData" />
                                </div>
                                <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                                    aria-labelledby="pills-profile-tab">
                                    <riwayat :dataRiwayat="dataRiwayat" :tanggalAwal="tanggalAwal" :tanggalAkhir="tanggalAkhir"
                                        @updateTanggal="updateTanggal" v-if="!loadingRiwayat" />
                                    <div class="spinner-border" role="status" v-else>
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                            </div>
                            <div v-else>
                                <div class="spinner-border" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
    </div>
</template>