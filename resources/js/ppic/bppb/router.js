import VueRouter from 'vue-router'
import Vue from 'vue'

import Container from './Container.vue'

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/ppic/bppb/:status',
            component: Container,
        }
    ]
})

export default router