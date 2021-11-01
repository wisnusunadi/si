import VueRouter from 'vue-router'
import Vue from 'vue'

import Show from './Show.vue';
import Edit from './Edit.vue'
import Create from './Create.vue'
import Detail from './Detail.vue';

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            name: 'show',
            path: '/gbj/produk',
            component: Show
        },
        {
            name: 'create',
            path: '/gbj/produk/create',
            component: Edit
        },
        {
            name: 'edit',
            path: '/gbj/produk/edit',
            component: Create
        },
        {
            name: 'detail',
            path: '/gbj/produk/detail',
            component: Detail
        }
    ]
})

export default router