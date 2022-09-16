<template>
    <div>
        <!-- <p v-if="events.length == 0">Data Kosong</p> -->
        <VueApexCharts type="donut" width="380" :options="options" :series="series"></VueApexCharts>
    </div>
</template>
<script>
    import VueApexCharts from 'vue-apexcharts';


    export default {
        components: {
            VueApexCharts
        },
        // props: {
        //     events: {
        //         type: Array,
        //         required: true,
        //     },
        // },

        data() {
            return {
                options: {},
                series: [44, 55, 41, 17, 15],
            }
        },
    }

</script>
