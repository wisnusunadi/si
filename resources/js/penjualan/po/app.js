import Vue from 'vue';
import App from './App.vue'
import router from './router'
import vSelect from "vue-select";
import 'vue-select/dist/vue-select.css';

Vue.component('v-select', vSelect)

new Vue({
    router,
    el: '#app',
    render: h => h(App)
})