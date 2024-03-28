import Vue from 'vue'
import Index from './Index.vue'
import router from './routes'
import VueRouter from 'vue-router';
import Store from './store'
import Vuex from 'vuex'
import "jquery/dist/jquery"
import "datatables.net/js/jquery.dataTables.min"
import "vue-select/dist/vue-select.css";
import VueSweetalert2 from 'vue-sweetalert2';
import vSelect from 'vue-select'
import numberOnly from './plugins/numberOnly'
import dateFormat from './plugins/dateFormat'
import dateTimeFormat from './plugins/dateTimeFormat'
import timeFormat from './plugins/timeFormat'
import rupiahFormat from './plugins/rupiahFormat';
import DataTable from './components/DataTable.vue'
import persentase from "./components/persentase.vue";

window.Vue = require('vue').default;
Vue.use(VueRouter);
Vue.use(Vuex);
Vue.use(VueSweetalert2);
Vue.component('v-select', vSelect)
Vue.use(numberOnly);
Vue.use(dateFormat);
Vue.use(dateTimeFormat);
Vue.use(timeFormat);
Vue.use(rupiahFormat);
Vue.component("persentase", persentase);
Vue.component('data-table', DataTable);

const store = new Vuex.Store(Store);

const app = new Vue({
    el: '#app',
    router,
    store,
    components: {
        "index": Index
    },
});
