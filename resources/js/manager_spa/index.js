import Vue from 'vue'
import router from './router'
import store from '../ppic_spa/store'
import plugins from '../ppic_spa/plugins'
import App from './App.vue'

import axios from 'axios'
import $ from 'jquery'

import "jquery/dist/jquery"
import "datatables.net/js/jquery.dataTables.min"
import "datatables-bulma/js/dataTables.bulma"

import VueSweetalert2 from "vue-sweetalert2"

$.fn.DataTable.ext.pager.simple_numbers_no_ellipses = plugins.simple_numbers_no_ellipses

const api_token = document.querySelector('meta[name="api-token"]').content
const csrf_token = document.querySelector('meta[name="csrf-token"]').content

store.state.csrf_token = csrf_token;

axios.defaults.headers.common = {
    'Accept': "application/json",
    'Authorization': "Bearer " + api_token,
    'X-CSRF-TOKEN': csrf_token
}
// axios.defaults.baseURL = "http://localhost:8000/"

Vue.use(VueSweetalert2)

new Vue({
    router,
    store,
    render: h => h(App)
}).$mount("#app")