<script>
import axios from 'axios'
import Header from '../../components/header.vue'
import ekat from './ekat'
import spa from './spa'
import spb from './spb'
import penjualan from './penjualan'
export default {
    components: {
        Header,
        ekat,
        spa,
        spb,
        penjualan
    },
    data() {
        return {
            title: 'Penjualan',
            breadcumbs: [
                {
                    name: 'Beranda',
                    link: '/'
                },
                {
                    name: 'Penjualan',
                    link: '/transaksi'
                }
            ],
            ekatData: [],
            spaData: [],
            spbData: [],
            penjualanData: []
        }
    },
    methods: {
        async getData() {
            try {
                this.$store.dispatch('setLoading', true)
                const { data: ekat } = await axios.get(`/api/ekatalog/data/semua/${this.$store.state.years}`, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('lokal_token')}`
                    }
                })
                this.ekatData = ekat.map((item, idx) => {
                    return {
                        ...item,
                        no: idx + 1,
                        urutan: item.no_urut ?? '-',
                        so: item.pesanan.so ?? '-',
                        no_po: item.pesanan.no_po ?? '-',
                        tgl_buat: this.dateFormat(item.tgl_buat),
                        tgl_edit: this.dateFormat(item.tgl_edit),
                        nama_customer: item.customer.nama,
                        persentase: 0,
                        jenis: 'ekatalog',
                    }
                })

                const { data: spa } = await axios.get(`/api/spa/data/semua/${this.$store.state.years}`, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('lokal_token')}`
                    }
                })

                this.spaData = spa.map((item, idx) => {
                    return {
                        ...item,
                        no: idx + 1,
                        so: item.pesanan.so ?? '-',
                        no_po: item.pesanan.no_po ?? '-',
                        tgl_order: this.dateFormat(item.pesanan.tgl_po),
                        nama_customer: item.customer.nama,
                        persentase: 0,
                        jenis: 'spa'
                    }
                })

                const { data: spb } = await axios.get(`/api/spb/data/semua/${this.$store.state.years}`, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('lokal_token')}`
                    }
                })

                this.spbData = spb.map((item, idx) => {
                    return {
                        ...item,
                        no: idx + 1,
                        so: item.pesanan.so ?? '-',
                        no_po: item.pesanan.no_po ?? '-',
                        tgl_order: this.dateFormat(item.pesanan.tgl_po),
                        nama_customer: item.customer.nama,
                        persentase: 0,
                        jenis: 'spb'
                    }
                })

                const { data: penjualan } = await axios.post(`/api/penjualan/penjualan/data/semua/semua/${this.$store.state.years}`, {}, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('lokal_token')}`
                    }
                })

                this.penjualanData = penjualan.map((item, idx) => {
                    return {
                        ...item,
                        no: idx + 1,
                        so: item.pesanan.so ?? '-',
                        no_paket: item.no_paket ?? '-',
                        no_po: item.pesanan.no_po ?? '-',
                        tgl_po: this.dateFormat(item.pesanan.tgl_po),
                        nama_customer: item.customer.nama,
                        persentase: 0,
                    }
                })

            } catch (error) {
                console.error(error)
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
        <ul class="nav nav-tabs font-medium" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" href="#" id="home-tab" data-toggle="tab" data-target="#home" role="tab"
                    aria-controls="home" aria-selected="true">E-catalogue</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="#" id="profile-tab" data-toggle="tab" data-target="#profile" role="tab"
                    aria-controls="profile" aria-selected="false">SPA</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="#" id="contact-tab" data-toggle="tab" data-target="#contact" role="tab"
                    aria-controls="contact" aria-selected="false">SPB</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="#" id="penjualan-tab" data-toggle="tab" data-target="#penjualan" role="tab"
                    aria-controls="penjualan" aria-selected="false">Penjualan</a>
            </li>
        </ul>
        <div class="tab-content font-medium" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <ekat :ekat="ekatData" />
            </div>
            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <spa :spa="spaData" />
            </div>
            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                <spb :spb="spbData" />
            </div>
            <div class="tab-pane fade" id="penjualan" role="tabpanel" aria-labelledby="penjualan-tab">
                <penjualan :penjualan="penjualanData" />
            </div>
        </div>
    </div>
</template>
<style>
.font-medium {
    font-size: 13px;
}
</style>