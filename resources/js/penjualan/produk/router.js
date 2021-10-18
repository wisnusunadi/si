import VueRouter from 'vue-router'
import Vue from 'vue'

import Show from './Show.vue'
import Detail from './Detail.vue'
import Create from './Create.vue'
import Edit from './Edit.vue'

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            name: 'show',
            path: '/penjualan/produk',
            component: Show
        },
        {
            name: 'detail',
            path: '/penjualan/produk/detail/:id',
            component: Detail
        },
        {
            name: 'create',
            path: '/penjualan/produk/create',
            component: Create
        },
        {
            name: 'edit',
            path: '/penjualan/produk/edit/:id',
            component: Edit
        }
    ]
})

export default router