import './data'
import './script'

import Vue from 'vue'

import VueSweetalert2 from 'vue-sweetalert2'
import 'sweetalert2/dist/sweetalert2.min.css'

import App from './App.vue'

Vue.use(VueSweetalert2)

new Vue({
    el: '#app',
    render: h => h(App)
})