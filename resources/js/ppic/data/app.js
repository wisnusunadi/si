import Vue from 'vue';
import App from './App.vue'
import router from './router';

const vue = new Vue({
    router,
    el: '#app',
    render: h => h(App)
})