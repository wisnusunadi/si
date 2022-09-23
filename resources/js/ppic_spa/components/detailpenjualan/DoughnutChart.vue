<template>
    <div>
        <VueApexCharts width="380" type="donut" :options="options" :series="series"></VueApexCharts>
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
