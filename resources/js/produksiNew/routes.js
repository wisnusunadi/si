import VueRouter from "vue-router";
import PermintaanReworks from "./Page/permintaan";
import prosesSetReworks from './Page/prosesSet'
import prosesSetReworksDetail from './Page/prosesSet/proses/detail'

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
    }
]

const router = new VueRouter({
    mode: "history",
    routes
});

export default router;