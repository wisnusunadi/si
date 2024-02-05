import Vue from 'vue'
import Index from './Index.vue'
import router from './routes'
import VueRouter from 'vue-router';
import VueSweetalert2 from "vue-sweetalert2"
import Vuex from 'vuex';
import vSelect from "vue-select";
import storeData from './store'
import formatDate from './plugins/formatDate'
import DataTable from './components/DataTable.vue'
import "vue-select/dist/vue-select.css";

window.Vue = require('vue').default;
Vue.use(VueRouter);
Vue.use(VueSweetalert2);
Vue.use(Vuex);
Vue.use(formatDate);
Vue.component("v-select", vSelect);
Vue.component("data-table", DataTable);

const store = new Vuex.Store(storeData);

const app = new Vue({
    el: '#app',
    store,
    router,
    components: {
        "index": Index
    }
}); 