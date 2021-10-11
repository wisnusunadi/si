import VueRouter from 'vue-router'
import Vue from 'vue'

import Show from './Show.vue'
import Create from './Create.vue'

Vue.use(VueRouter)

const router = new VueRouter({
    routes: [
        {
            path: '/gbj/stok',
            component: Show
        },
        {
            path: '/gbj/stok/create',
            component: Create
        }
    ]
})

export default router