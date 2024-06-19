<script>
import Header from '../../components/header.vue'
import DalamProses from './dalamproses'
import SelesaiProses from './selesaiproses'
import BatalPo from './batalPo'
import axios from 'axios'
export default {
    components: {
        Header,
        DalamProses,
        SelesaiProses,
        BatalPo
    },
    data() {
        return {
            title: 'Sales Order',
            breadcumbs: [
                {
                    name: 'Beranda',
                    link: '/'
                },
                {
                    name: 'Sales Order',
                    link: '/salesorder'
                }
            ],
            dalamProsesData: [],
            jenisDalamProsesStatus: ["semua"],
            selesaiProsesData: [],
            batalPOData: []
        }
    },
    methods: {
        async getData() {
            try {
                this.$store.dispatch('setLoading', true)
                const { data: dalamProses } = await axios.get(`/api/logistik/so/data/${this.jenisDalamProsesStatus}/${this.$store.state.years}`)
                this.dalamProsesData = dalamProses.map((item, index) => {
                    return {
                        no: index + 1,
                        ...item
                    }
                })

                const { data: selesaiProses } = await axios.get(`/api/logistik/so/data/selesai/${this.$store.state.years}`)

                this.selesaiProsesData = selesaiProses.map((item, index) => {
                    return {
                        no: index + 1,
                        jenis_id: this.jenisId(item).id,
                        jenis: this.jenisId(item).jenis,
                        ...item
                    }
                })

                const { data: batalPO } = await axios.get(`/api/penjualan/batal_po/logistik/show`)
                this.batalPOData = batalPO.map((item, index) => {
                    return {
                        no: index + 1,
                        ...item
                    }
                })
            } catch (error) {
                console.log(error)
            } finally {
                this.$store.dispatch('setLoading', false)
            }
        },
        jenisId(item) {
            if (item.ekatalog) {
                return {
                    id: item.ekatalog.id,
                    jenis: 'EKAT'
                }
            } else if (item.spa) {
                return {
                    id: item.spa.id,
                    jenis: 'SPA'
                }
            } else {
                return {
                    id: item.spb.id,
                    jenis: 'SPB'
                }
            }
        },
        filterDataStatusDalamProses(status) {
            if (this.jenisDalamProsesStatus.includes(status)) {
                this.jenisDalamProsesStatus = this.jenisDalamProsesStatus.filter(item => item !== status)
            } else {
                this.jenisDalamProsesStatus = this.jenisDalamProsesStatus.filter(item => item !== 'semua')
                this.jenisDalamProsesStatus.push(status)
            }

            if (this.jenisDalamProsesStatus.length === 0) {
                this.jenisDalamProsesStatus.push('semua')
            }

            this.$nextTick(() => {
                this.getData()
            })
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
        <div class="card" v-if="!$store.state.loading">
            <div class="card-body">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="pills-home-tab" data-toggle="pill" data-target="#pills-home"
                            type="button" role="tab" aria-controls="pills-home" aria-selected="true">Dalam Proses</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-profile-tab" data-toggle="pill" data-target="#pills-profile"
                            type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Selesai
                            Proses</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-contact-tab" data-toggle="pill" data-target="#pills-contact"
                            type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Batal PO</a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                        aria-labelledby="pills-home-tab">
                        <DalamProses :dalam="dalamProsesData" @refresh="getData"
                            @filter="filterDataStatusDalamProses" />
                    </div>
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                        <SelesaiProses :selesai="selesaiProsesData" @refresh="getData" />
                    </div>
                    <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                        <BatalPo :items="batalPOData" @refresh="getData" />
                    </div>
                </div>
            </div>
        </div>
        <div v-else>
            <div class="d-flex justify-content-center align-items-center" style="height: 100vh">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</template>