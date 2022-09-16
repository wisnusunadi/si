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
        data() {
            return {
                options: {},
                series: [44, 55, 41, 17, 15]
            }
        },
    }
</script>