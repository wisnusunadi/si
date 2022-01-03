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
            path: '/penjualan/customer',
            component: Show
        },
        {
            name: 'create',
            path: '/penjualan/customer/create',
            component: Create
        },
    ]
})

export default router