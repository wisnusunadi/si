import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";

new Vue({
    router,
    el: "#app",
    render: h => h(App)
});
Vue.component("v-select", vSelect);
