import VueRouter from 'vue-router'
import Vue from 'vue'

import Show from './Show.vue'
import Detail from './Detail.vue'
import Edit from './Edit.vue'

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            name: 'Detail',
            path: '/gbj/penjualan',
            component: Show
        },
        {
            name: 'detail',
            path: '/gbj/penjualan/detail/:id',
            component: Detail
        },
        {
            name: 'edit',
            path: '/gbj/penjualan/edit/:id',
            component: Edit
        }
    ]
})

export default router