import Vue from 'vue'

import VueSweetalert2 from 'vue-sweetalert2'
import 'sweetalert2/dist/sweetalert2.min.css'

import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css';

import VueApexCharts from 'vue-apexcharts'


import router from './router'
import store from './store'

import App from './App.vue'

Vue.component('v-select', vSelect)
Vue.component('apexchart', VueApexCharts)

Vue.use(VueSweetalert2)

new Vue({
    router,
    store,
    el: '#app',
    components: {
        "calendar": App
    }
})