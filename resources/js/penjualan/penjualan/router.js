import VueRouter from 'vue-router'
import Vue from 'vue'

import Show from './Show.vue'
import CreateAkn from './CreateAkn.vue'

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            name: 'show',
            path: '/penjualan/penjualan',
            component: Show
        },
        {
            name: 'create_akn',
            path: '/penjualan/create_akn',
            component: CreateAkn
        },
    ]
})

export default router