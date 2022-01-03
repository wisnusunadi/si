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
            path: '/gbj/stok',
            component: Show
        },
        {
            name: 'create',
            path: '/gbj/stok/create',
            component: Create
        }
    ]
})

export default router