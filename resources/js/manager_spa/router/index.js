import Vue from 'vue'
import VueRouter from 'vue-router'

import Home from '../views/Home.vue'
import Perakitan from "../views/Perakitan.vue"
import SalesOrder from "../views/SalesOrder.vue"
import JadwalPerencanaan from "../views/JadwalPerencanaan.vue"
import JadwalPelaksanaan from "../views/JadwalPelaksanaan.vue"
import PersetujuanPerencanaan from "../views/PersetujuanPerencanaan.vue"
import PersetujuanPelaksanaan from "../views/PersetujuanPelaksanaan.vue"

import Calendar from "../components/Calendar.vue"

Vue.use(VueRouter)

const routes = [
    {
        path: '/manager-teknik',
        name: 'Home',
        component: Home
    },
    {
        path: '/manager-teknik/perakitan',
        name: 'Perakitan',
        component: Perakitan,
    },
    {
        path: '/manager-teknik/so',
        name: 'SalesOrder',
        component: SalesOrder,
    },
    {
        path: '/manager-teknik/jadwal_perencanaan',
        name: 'JadwalPerencanaan',
        component: JadwalPerencanaan,
    },
    {
        path: '/manager-teknik/jadwal_pelaksanaan',
        name: 'JadwalPelaksanaan',
        component: JadwalPelaksanaan,
    },
    {
        path: '/manager-teknik/persetujuan_perencanaan',
        name: 'PersetujuanPerencanaan',
        component: PersetujuanPerencanaan,
    },
    {
        path: '/manager-teknik/persetujuan_pelaksanaan',
        name: 'PersetujuanPelaksanaan',
        component: PersetujuanPelaksanaan,
    },
]

const router = new VueRouter({
    mode: 'history',
    base: process.env.BASE_URL,
    routes
})

export default router
