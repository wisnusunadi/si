import VueRouter from 'vue-router'
import Vue from 'vue'

import Show from './Show.vue';

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            name: 'show',
            path: '/gbj/so',
            component: Show
        },
    ]
})

export default router