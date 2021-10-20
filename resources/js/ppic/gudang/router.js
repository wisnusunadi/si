import VueRouter from 'vue-router'
import Vue from 'vue'

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/ppic/gudang/gbmp',
            component: () => import("./Gbmp.vue")
        },
        {
            path: '/ppic/gudang/gbj',
            component: () => import("./Gbj.vue")
        },
        {
            path: '/ppic/gudang/gk',
            component: () => import("./Gk.vue")
        }
    ]
})

export default router