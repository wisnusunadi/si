import Vue from 'vue'
import Index from './Index.vue'
import router from './routes'
import VueRouter from 'vue-router';
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import '@mdi/font/css/materialdesignicons.css'

import 'jquery/dist/jquery'
window.Vue = require('vue').default;
Vue.use(VueRouter);
Vue.use(Vuetify)
 
const app = new Vue({
    el: '#app',
    router,
    vuetify: new Vuetify(),
    components: {
        "index": Index
    }
}); 
