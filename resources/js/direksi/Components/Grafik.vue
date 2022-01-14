<template>
    <div v-if="series.data == null">
        Belum ada Perencanaan Perakitan
    </div>
    <div v-else>
        <apexchart type="rangeBar" :options="options" :series="series" :height="this.series[0].data.length * 50"/>
    </div>
</template>

<script>
import VueApexCharts from 'vue-apexcharts';
import axios from 'axios';
export default {
    name: "Chart",
    components: {
        apexchart: VueApexCharts
    },
    data() {
        return {
            options: {
                chart: {
                    id: 'basic-bar-chart',
                },
                plotOptions: {
                    bar: {
                        horizontal: true
                    }
                },
               xaxis: {
                type: "datetime",
                },
            },
            series: [{
                name: 'series-1',
                data: []
            }],
        }
    },

    methods: {
        mappingData(data) {
            return data.map((event) => ({
                    x: `${event.produk.produk.nama} ${event.produk.nama}`,
                    y: [
                        new Date(event.tanggal_mulai).getTime(),
                        new Date(event.tanggal_selesai).getTime(),
                    ],
                }));
        },

        dataSeries() {
            axios.get("/api/ppic/data/perakitan/penyusunan").then(response => {
                this.series[0].data = this.mappingData(response.data);
            })
        },
    },
    mounted() {
        this.dataSeries();
    },
}
</script>

<style>

</style>
