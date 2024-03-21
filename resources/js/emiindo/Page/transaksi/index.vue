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
            penjualanData: [],
            jenisEkatStatus: ['semua'],
            jenisSpaStatus: ['semua'],
            jenisSpbStatus: ['semua'],
            jenisPenjualanTransaksiStatus: ['semua'],
            jenisPenjualanStatus: ['semua']
        }
    },
    methods: {
        async getData() {
            try {
                this.$store.dispatch('setLoading', true)
                const { data: ekat } = await axios.get(`/api/ekatalog/data/${this.jenisEkatStatus}/${this.$store.state.years}`, {
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

                const { data: spa } = await axios.get(`/api/spa/data/${this.jenisSpaStatus}/${this.$store.state.years}`, {
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

                const { data: spb } = await axios.get(`/api/spb/data/${this.jenisSpbStatus}/${this.$store.state.years}`, {
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

                const { data: penjualan } = await axios.post(`/api/penjualan/penjualan/data/${this.jenisPenjualanTransaksiStatus}/${this.jenisPenjualanStatus}/${this.$store.state.years}`, {}, {
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
        },
        filterDataStatusEkat(status) {
            if (this.jenisEkatStatus.includes(status)) {
                // If data exists in jenisEkatStatus, remove it
                this.jenisEkatStatus = this.jenisEkatStatus.filter(item => item !== status);
            } else {
                // If data is not defined in jenisEkatStatus, remove 'semua' and push data
                this.jenisEkatStatus = this.jenisEkatStatus.filter(item => item !== 'semua');
                this.jenisEkatStatus.push(status);
            }

            if (this.jenisEkatStatus.length === 0) {
                this.jenisEkatStatus.push('semua')
            }
            this.$nextTick(() => {
                this.getData()
            })
        },
        filterDataStatusSpa(status) {
            if (this.jenisSpaStatus.includes(status)) {
                this.jenisSpaStatus = this.jenisSpaStatus.filter(item => item !== status);
            } else {
                this.jenisSpaStatus = this.jenisSpaStatus.filter(item => item !== 'semua');
                this.jenisSpaStatus.push(status);
            }

            if (this.jenisSpaStatus.length === 0) {
                this.jenisSpaStatus.push('semua')
            }
            this.$nextTick(() => {
                this.getData()
            })
        },
        filterDataStatusSpb(status) {
            if (this.jenisSpbStatus.includes(status)) {
                this.jenisSpbStatus = this.jenisSpbStatus.filter(item => item !== status);
            } else {
                this.jenisSpbStatus = this.jenisSpbStatus.filter(item => item !== 'semua');
                this.jenisSpbStatus.push(status);
            }

            if (this.jenisSpbStatus.length === 0) {
                this.jenisSpbStatus.push('semua')
            }
            this.$nextTick(() => {
                this.getData()
            })
        },
        filterDataStatusPenjualan(status) {
            if (this.jenisPenjualanStatus.includes(status)) {
                this.jenisPenjualanStatus = this.jenisPenjualanStatus.filter(item => item !== status);
            } else {
                this.jenisPenjualanStatus = this.jenisPenjualanStatus.filter(item => item !== 'semua');
                this.jenisPenjualanStatus.push(status);
            }

            if (this.jenisPenjualanStatus.length === 0) {
                this.jenisPenjualanStatus.push('semua')
            }
            this.$nextTick(() => {
                this.getData()
            })
        },
        filterTransaksiPenjualan(status) {
            if (this.jenisPenjualanTransaksiStatus.includes(status)) {
                this.jenisPenjualanTransaksiStatus = this.jenisPenjualanTransaksiStatus.filter(item => item !== status);
            } else {
                this.jenisPenjualanTransaksiStatus = this.jenisPenjualanTransaksiStatus.filter(item => item !== 'semua');
                this.jenisPenjualanTransaksiStatus.push(status);
            }

            if (this.jenisPenjualanTransaksiStatus.length === 0) {
                this.jenisPenjualanTransaksiStatus.push('semua')
            }
            this.$nextTick(() => {
                this.getData()
            })
        },
    },
    created() {
        this.getData()
    },
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
                <ekat :ekat="ekatData" @filter="filterDataStatusEkat" @refresh="getData" />
            </div>
            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <spa :spa="spaData" @filter="filterDataStatusSpa" @refresh="getData" />
            </div>
            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                <spb :spb="spbData" @filter="filterDataStatusSpb" @refresh="getData" />
            </div>
            <div class="tab-pane fade" id="penjualan" role="tabpanel" aria-labelledby="penjualan-tab">
                <penjualan :penjualan="penjualanData" @filter="filterDataStatusPenjualan" @refresh="getData"
                    @filterTransaksi="filterTransaksiPenjualan" />
            </div>
        </div>
    </div>
</template>
<style>
.font-medium {
    font-size: 13px;
}
</style>