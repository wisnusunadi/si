import Vue from 'vue'

import BootstrapVue from 'bootstrap-vue'

import VueSweetalert2 from 'vue-sweetalert2'
import 'sweetalert2/dist/sweetalert2.min.css'

import store from './store'

import App from './App.vue'

Vue.use(VueSweetalert2)
Vue.use(BootstrapVue)

new Vue({
    store,
    el: '#app',
    components: {
        "app": App
    }
})