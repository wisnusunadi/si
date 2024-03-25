import VueRouter from "vue-router";
import BarangMasuk from "./page/barangmasuk";

const routes = [
    {
        path: "/qc/incoming/show",
        component: BarangMasuk,
        name: "barangMasuk",
    },
    {
        path: "/qc/incoming/show/:id",
        component: () => import("./page/barangmasuk/dalamproses/detail"),
        name: "barangMasukDetail",
    },
    {
        path: "/qc/riwayatUji",
        component: () => import("./page/riwayat"),
        name: "riwayatUji",
    },
    {
        path: "/qc/so",
        component: () => import("./page/salesorder"),
        name: "salesOrder",
    },
];

const router = new VueRouter({
    mode: "history",
    routes,
});

export default router;
