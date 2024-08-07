import Vue from 'vue'
import Index from './Index.vue'
import router from './routes'
import VueRouter from 'vue-router';
import Vuetify from 'vuetify'
import VueSweetalert2 from "vue-sweetalert2"
import Vuex from 'vuex';
import storeData from './store'
import 'vuetify/dist/vuetify.min.css'
import '@mdi/font/css/materialdesignicons.css'
import dateFormat from "./plugins/dateFormat";
import numberOnly from "./plugins/numberOnly";
import dateTimeFormat from "./plugins/dateTimeFormat";

window.Vue = require('vue').default;
Vue.use(VueRouter);
Vue.use(Vuetify)
Vue.use(VueSweetalert2);
Vue.use(dateFormat);
Vue.use(dateTimeFormat);
Vue.use(numberOnly);

const store = new Vuex.Store(storeData);

const app = new Vue({
    el: '#app',
    store,
    router,
    vuetify: new Vuetify(),
    components: {
        "index": Index
    }
}); 