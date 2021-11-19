import Vue from 'vue'

import VueSweetalert2 from 'vue-sweetalert2'
import 'sweetalert2/dist/sweetalert2.min.css'

import store from './store'

import App from './App.vue'

Vue.use(VueSweetalert2)

new Vue({
    store,
    el: '#app',
    render: h => h(App)
})