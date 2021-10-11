import VueRouter from 'vue-router'
import Vue from 'vue'

import Calendar from './Calendar.vue'

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/ppic/schedule/:status',
            component: Calendar
        }
    ]
})

export default router