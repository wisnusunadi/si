<template>
    <div>
        <VueApexCharts width="380" type="donut" :options="options" :series="series"></VueApexCharts>
        <div class="card">
            <header class="card-header">
                <p class="card-header-title has-text-centered">
                    {{ chartData.detail.penjualan_produk.nama }}
                </p>
            </header>
            <div class="card-content">
                <div class="content">
                    <div class="box">
                        <p class="has-text-centered">Gudang</p>
                        <p class="has-text-centered"><span class="has-text-danger">{{ series[0] }}</span> dari
                            {{ chartData.detail.count_gudang }}</p>
                    </div>
                    <div class="box">
                        <p class="has-text-centered">Quality Control</p>
                        <p class="has-text-centered"><span class="has-text-danger">{{ series[1] }}</span> dari
                            {{ chartData.detail.count_qc_ok }}</p>
                    </div>

                    <div class="box">
                        <p class="has-text-centered">Logistik</p>
                        <p class="has-text-centered"><span class="has-text-danger">{{ series[2] }}</span> dari
                            {{ chartData.detail.count_log }}</p>
                    </div>

                    <div class="box">
                        <p class="has-text-centered">Kirim</p>
                        <p class="has-text-centered"><span class="has-text-danger">{{ series[3] }}</span> Unit</p>
                    </div>
                    <div class="box">
                        <small>
                            <i class="fas fa-info-circle"></i> <strong>Catatan: </strong>
                            <ol style="list-item-style:none; margin-left:0px;padding-left:15px;">
                                <li>Angka warna <b class="text-danger">merah</b> menunjukkan jumlah unit yang <i>belum
                                        diproses</i> oleh divisi tersebut</li>
                                <li>Angka warna <b class="text-dark">hitam</b> menunjukkan total yang <i>telah diberikan
                                        dan harus diproses</i> oleh divisi tersebut</li>
                                <li>Angka pada Kirim merupakan total unit yang <i>telah terkirim</i></li>
                            </ol>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import VueApexCharts from 'vue-apexcharts';
    export default {
        components: {
            VueApexCharts
        },
        props: {
            chartData: {
                type: Object,
                required: true,
                default: null
            }
        },
        computed: {
            options() {
                return {
                    labels: ['Gudang', 'Quality Control', 'Logistik', 'Kirim']
                }
            },
            series() {
                let dataChart = Object.values(this.chartData)
                dataChart.shift()
                return dataChart
            },
        }
    }

</script>
