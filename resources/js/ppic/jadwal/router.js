import VueRouter from 'vue-router'
import Vue from 'vue'

import ViewContainer from './ViewContainer.vue'

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/ppic/schedule/:status',
            component: ViewContainer
        }
    ]
})

export default router