<script>
import axios from 'axios';
import Header from '../../components/header.vue';
import proses from './proses'
// import riwayat from './riwayat'
import kardus from './kardus'
export default {
    components: {
        Header,
        proses,
        kardus,
        // riwayat,
    },
    data() {
        return {
            title: 'Set Pemetian',
            breadcumbs: [
                {
                    name: 'Beranda',
                    link: '/logistik/dashboard'
                },
                {
                    name: 'Set Pemetian',
                    link: '#'
                }
            ],
            proses: [],
            riwayat: [],
            kardus: [{ "id": 2, "urutan": "PRD-2", "sudah": 1, "belum": 3349, "nama": "ANTROPOMETRI KIT-10" }],
        }
    },
    methods: {
        async getData() {
            try {
                this.$store.dispatch('setLoading', true)
                const { data } = await axios.get('/api/logistik/rw/')
                const { data: kardus } = await axios.get('/api/logistik/rw/pack/show')
                this.proses = data.map(item => {
                    return {
                        ...item,
                        sudah: parseInt(item.sudah) / 3,
                        belum: parseInt(item.belum) / 3,
                    }
                })
                this.kardus = kardus
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
        <div class="card">
            <div class="card-body">
                <kardus :dataTable="kardus" v-if="!$store.state.loading" />
                <div class="spinner-border" role="status" v-else>
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</template>