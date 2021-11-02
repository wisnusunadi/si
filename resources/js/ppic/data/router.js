import VueRouter from 'vue-router'
import Vue from 'vue'

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/ppic/data/gbmp',
            component: () => import("./components/Gbmp.vue")
        },
        {
            path: '/ppic/data/gbj',
            component: () => import("./components/Gbj.vue")
        },
        {
            path: '/ppic/data/gk',
            component: () => import("./components/Gk.vue")
        },
        {
            path: '/ppic/data/perakitan',
            component: () => import('./components/Perakitan.vue')
        },
        {
            path: '/ppic/data/so',
            component: () => import('./components/SO.vue')
        }
    ]
})

export default router