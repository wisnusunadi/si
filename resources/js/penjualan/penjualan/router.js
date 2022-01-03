import VueRouter from 'vue-router'
import Vue from 'vue'

import Show from './Show.vue'
import Create from './Create.vue'

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
            name: 'create',
            path: '/penjualan/penjualan/create',
            component: Create
        },
    ]
})

export default router