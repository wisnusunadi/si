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
            path: '/penjualan/po',
            component: Show
        },
        {
            name: 'create',
            path: '/penjualan/po/create',
            component: Create
        },
    ]
})

export default router