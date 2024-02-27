<script>
import axios from 'axios'
import dalamProsesComponent from './dalamproses'
import selesaiProsesComponent from './selesaiproses'
export default {
    components: {
        dalamProsesComponent,
        selesaiProsesComponent
    },
    data() {
        return {
            dalamProses: [],
            selesaiProses: []
        }
    },
    methods: {
        async getData() {
            try {
                this.$store.dispatch('setLoading', true)
                const { data: dalamProses } = await axios.get('/api/labs/kalibrasi').then(res => res.data)
                const { data:selesaiProses } = await axios.get(`/api/labs/kalibrasi/riwayat?years=${this.$store.state.years}`)
                this.dalamProses = dalamProses
                this.selesaiProses = selesaiProses.map(item => {
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
        }
    },
    created() {
        this.getData()
    }
}
</script>
<template>
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="pills-dalamproseskalibrasiinternal-tab" data-toggle="pill"
                        data-target="#pills-dalamproseskalibrasiinternal" type="button" role="tab"
                        aria-controls="pills-dalamproseskalibrasiinternal" aria-selected="true">Dalam Proses</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="pills-selesaiproseskalibrasiinternal-tab" data-toggle="pill"
                        data-target="#pills-selesaiproseskalibrasiinternal" type="button" role="tab"
                        aria-controls="pills-selesaiproseskalibrasiinternal" aria-selected="false">Selesai Proses</a>
                </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-dalamproseskalibrasiinternal" role="tabpanel"
                    aria-labelledby="pills-dalamproseskalibrasiinternal-tab">
                    <dalamProsesComponent :dataTable="dalamProses" />
                </div>
                <div class="tab-pane fade" id="pills-selesaiproseskalibrasiinternal" role="tabpanel"
                    aria-labelledby="pills-selesaiproseskalibrasiinternal-tab">
                    <selesaiProsesComponent :selesaiProses="selesaiProses" @changeYears="getData" />
                </div>
            </div>
        </div>
    </div>
</template>