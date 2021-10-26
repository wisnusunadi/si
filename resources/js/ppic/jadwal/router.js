import VueRouter from 'vue-router'
import Vue from 'vue'

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/ppic/jadwal/:status',
            component: () => import('./components/Container.vue')
        },
    ]
})

export default router