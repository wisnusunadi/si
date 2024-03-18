<script>
import axios from 'axios'
import Header from '../../components/header.vue'
import ekat from './ekat'
import spa from './spa'
import spb from './spb'
export default {
    components: {
        Header,
        ekat,
        spa,
        spb
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
        }
    },
    methods: {
        async getData() {
            try {
                this.$store.dispatch('setLoading', true)
                const { data: ekat } = await axios.post(`/penjualan/penjualan/ekatalog/data/semua/${this.$store.state.years}`).then(res => res.data.data)
                this.ekatData = ekat.map((item, idx) => {
                    return {
                        ...item,
                        no: idx + 1,
                        no_so: item.pesanan.so ?? '-',
                        no_po: item.pesanan.no_po ?? '-',
                        tgl_buat: this.dateFormat(item.tgl_buat),
                        tgl_edit: this.dateFormat(item.tgl_edit),

                    }
                })
            } catch (error) {
                console.error(error)
            } finally {
                this.$store.dispatch('setLoading', false)
            }
        }
    },
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-toggle="tab" data-target="#home" type="button"
                    role="tab" aria-controls="home" aria-selected="true">E-catalogue</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab" data-toggle="tab" data-target="#profile" type="button"
                    role="tab" aria-controls="profile" aria-selected="false">SPA</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="contact-tab" data-toggle="tab" data-target="#contact" type="button"
                    role="tab" aria-controls="contact" aria-selected="false">SPB</button>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <ekat :data="ekatData" />
            </div>
            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <spa :data="spaData" />
            </div>
            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                <spb :data="spbData" />
            </div>
        </div>
    </div>
</template>