import VueRouter from 'vue-router'
import Vue from 'vue'

import Show from './Show.vue'

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            name: 'show',
            path: '/penjualan/penjualan',
            component: Show
        },
    ]
})

export default router