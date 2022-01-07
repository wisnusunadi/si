import Vue from 'vue'
import VueRouter from 'vue-router'

import Home from '../views/Home.vue'
import GudangBarangJadi from "../views/GudangBarangJadi.vue"
import GudangKarantina from "../views/GudangKarantina.vue"
import Perakitan from "../views/Perakitan.vue"
import SalesOrder from "../views/SalesOrder.vue"
import SalesOrderDetail from "../views/SalesOrderDetail.vue"
import JadwalPerencanaan from "../views/JadwalPerencanaan.vue"
import JadwalPelaksanaan from "../views/JadwalPelaksanaan.vue"

Vue.use(VueRouter)

const routes = [
    {
        path: '/ppic',
        name: 'Home',
        component: Home
    },
    {
        path: '/ppic/gbj',
        name: 'GBJ',
        component: GudangBarangJadi,
    },
    {
        path: '/ppic/gk',
        name: 'GK',
        component: GudangKarantina,
    },
    {
        path: '/ppic/perakitan',
        name: 'Perakitan',
        component: Perakitan,
    },
    {
        path: '/ppic/so',
        name: 'SalesOrder',
        component: SalesOrder,
    },
    {
        path: '/ppic/jadwal_perencanaan',
        name: 'JadwalPerencanaan',
        component: JadwalPerencanaan,
    },
    {
        path: '/ppic/jadwal_pelaksanaan',
        name: 'JadwalPelaksanaan',
        component: JadwalPelaksanaan,
    },
    {
        path: '/ppic/so_detail',
        name: 'SalesOrderDetail',
        component: SalesOrderDetail,
    },

]

const router = new VueRouter({
    mode: 'history',
    base: process.env.BASE_URL,
    routes
})

export default router
