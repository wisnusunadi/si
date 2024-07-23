<script>
import Header from '../../components/header.vue'
import dalamProses from './dalamProses'
import sudahProses from './sudahProses'
import permintaanBarang from './permintaanBarang'
import batalTransfer from './batalTransfer'
import axios from 'axios'
export default {
    components: {
        Header,
        dalamProses,
        sudahProses,
        permintaanBarang,
        batalTransfer
    },
    data() {
        return {
            title: 'Daftar Sales Order',
            breadcumbs: [
                { name: 'Home', link: '/' },
                { name: 'Daftar Sales Order', link: '/gbj/so' },
            ],
            dataDalamProses: [],
            dataSudahProses: [],
            dataPermintaanBarang: [],
            dataBatal: [],
            tabs: 'dalam-proses',
            dalamProsesSelected: {
                label: 'Semua',
                value: 'semua'
            },
        }
    },
    methods: {
        async getData() {
            try {
                this.$store.dispatch('setLoading', true)
                const { data: dataDalamProses } = await axios.get(`/api/tfp/belum-dicek/${this.dalamProsesSelected.value}`, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('lokal_token')}`
                    }
                })
                const { data: dataSudahProses } = await axios.get(`/api/tfp/sudah-dicek`, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('lokal_token')}`
                    }
                })
                const { data: dataPermintaanBarang } = await axios.get('/api/v2/gbj/get_rekap_so_produk')
                this.dataDalamProses = dataDalamProses.map((item, index) => {
                    return {
                        no: index + 1,
                        ...item,
                        batas_transfer: this.dateFormat(item.batas)
                    }
                })
                this.dataSudahProses = dataSudahProses.map((item, index) => {
                    return {
                        no: index + 1,
                        ...item,
                        batas_transfer: this.dateFormat(item.batas)
                    }
                })
                this.dataPermintaanBarang = dataPermintaanBarang.map((item, index) => {
                    return {
                        no: index + 1,
                        ...item,
                    }
                })

                const { data: dataBatal } = await axios.get('/api/gbj/batal_po/show', {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('lokal_token')}`
                    }
                })

                this.dataBatal = dataBatal.map((item, index) => {
                    return {
                        no: index + 1,
                        ...item,
                    }
                })
            } catch (error) {
                console.log(error)
            } finally {
                this.$store.dispatch('setLoading', false)
            }
        }
    },
    created() {
        this.getData()
    },
    watch: {
        dalamProsesSelected() {
            this.getData()
        }
    },
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="pills-home-tab" data-toggle="pill" data-target="#pills-home"
                    @click="tabs = 'dalam-proses'"
                    :class="{ active: tabs == 'dalam-proses' }" type="button" role="tab" aria-controls="pills-home"
                    aria-selected="true">Dalam Proses</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="pills-profile-tab" data-toggle="pill" data-target="#pills-profile" type="button"
                    @click="tabs = 'sudah-proses'"
                    :class="{ active: tabs == 'sudah-proses' }" role="tab" aria-controls="pills-profile"
                    aria-selected="false">Sudah Proses</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="pills-contact-tab" data-toggle="pill" data-target="#pills-contact" type="button"
                    @click="tabs = 'permintaan-barang'"
                    :class="{ active: tabs == 'permintaan-barang' }" role="tab" aria-controls="pills-contact"
                    aria-selected="false">Permintaan Barang</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="pills-batal-tab" data-toggle="pill" data-target="#pills-batal" type="button"
                    @click="tabs = 'batal-so'"
                    :class="{ active: tabs == 'batal-so' }" role="tab" aria-controls="pills-batal"
                    aria-selected="false">Batal SO</a>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent" v-if="!$store.state.loading">
            <div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab"
                :class="{ 'show active': tabs == 'dalam-proses' }">
                <dalamProses
                @filter="dalamProsesSelected = $event"
                @refresh="getData" :items="dataDalamProses" :prosesSelected="dalamProsesSelected" v-if="tabs == 'dalam-proses'" />
            </div>
            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab"
                :class="{ 'show active': tabs == 'sudah-proses' }">
                <sudahProses :items="dataSudahProses" v-if="tabs == 'sudah-proses'" />
            </div>
            <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab"
                :class="{ 'show active': tabs == 'permintaan-barang' }">
                <permintaanBarang :items="dataPermintaanBarang" v-if="tabs == 'permintaan-barang'" />
            </div>
            <div class="tab-pane fade" id="pills-batal" role="tabpanel" aria-labelledby="pills-batal-tab"
                :class="{ 'show active': tabs == 'batal-so' }">
                <batalTransfer :items="dataBatal" @refresh="getData" v-if="tabs == 'batal-so'" />
            </div>
        </div>
        <div class="d-flex justify-content-center" v-else>
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
</template>
<style>
.nomor-so {
    background-color: #717FE1;
    color: #fff;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 18px
}

.nomor-akn {
    background-color: #DF7458;
    color: #fff;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 18px
}

.nomor-po {
    background-color: #85D296;
    color: #fff;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 18px
}

.instansi {
    background-color: #36425E;
    color: #fff;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 18px
}
</style>