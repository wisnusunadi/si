import Vue from 'vue'
import Index from './Index.vue'

window.Vue = require('vue').default;

new Vue({
    render: h => h(Index)
}).$mount('#app')