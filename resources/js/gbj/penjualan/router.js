import VueRouter from 'vue-router'
import Vue from 'vue'

import Show from './Show.vue'
import Edit from './Edit.vue'

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            name: 'show',
            path: '/gbj/penjualan',
            component: Show
        },
        {
            name: 'create',
            path: '/gbj/penjualan/edit',
            component: Edit
        }
    ]
})

export default router