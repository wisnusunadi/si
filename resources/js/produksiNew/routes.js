import VueRouter from "vue-router";
import PermintaanReworks from "./Page/permintaan";

const routes = [
    {
        path: "/produksi/permintaanreworks",
        component: PermintaanReworks,
        name: "permintaanreworks"
    }
]

const router = new VueRouter({
    mode: "history",
    routes
});

export default router;