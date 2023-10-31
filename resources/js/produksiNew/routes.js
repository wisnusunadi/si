import VueRouter from "vue-router";
import PermintaanReworks from "./Page/permintaan/index.vue";
import prosesSetReworks from './Page/prosesSet/index.vue'
import prosesSetReworksDetail from './Page/prosesSet/proses/detail/index.vue'
import perakitanBerlangsung from './Page/perakitanBerlangsung/index.vue'

const routes = [
    {
        path: "/produksi/permintaanreworks",
        component: PermintaanReworks,
        name: "permintaanreworks"
    },
    {
        path: "/produksi/prosesSetReworks",
        component: prosesSetReworks,
        name: "prosesSetReworks"
    },
    {
        path: "/produksi/prosesSetReworks/:id",
        component: prosesSetReworksDetail,
        name: "prosesSetReworksDetail"
    },
    // {
    //     path: "/produksi/jadwal_perakitan",
    //     component: perakitanBerlangsung,
    //     name: "perakitanBerlangsung"
    // }
]

const router = new VueRouter({
    mode: "history",
    routes
});

export default router;