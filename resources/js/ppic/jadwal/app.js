import Vue from 'vue'
import VueApexCharts from 'vue-apexcharts';

import router from './router'
import store from './store'

import App from './App.vue'
// import Select2 from 'v-select2-component'

// Vue.component('Select2', Select2)
Vue.component('apexchart', VueApexCharts)

new Vue({
    router,
    store,
    el: '#app',
    render: h => h(App),
})