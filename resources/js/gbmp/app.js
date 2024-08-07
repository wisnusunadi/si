import Vue from "vue";
import Index from "./Index.vue";
import router from "./routes";
import VueRouter from "vue-router";
import VueSweetalert2 from "vue-sweetalert2";
import Vuex from "vuex";
import vSelect from "vue-select";
import storeData from "./store";
import DataTable from "./components/DataTable.vue";
import numberOnly from "./plugins/numberOnly";
import dateFormat from "./plugins/dateFormat";
import persentase from "./components/persentase.vue";
import "vue-select/dist/vue-select.css";

window.Vue = Vue;
Vue.use(VueRouter);
Vue.use(VueSweetalert2);
Vue.use(numberOnly);
Vue.use(dateFormat);
Vue.use(Vuex);
Vue.component("v-select", vSelect);
Vue.component("data-table", DataTable);
Vue.component("persentase", persentase);

const store = new Vuex.Store(storeData);

const app = new Vue({
    el: "#app",
    router,
    store,
    components: {
        index: Index,
    },
});
