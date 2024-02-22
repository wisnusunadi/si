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
        component: () => import("./page/barangmasuk/incoming/detail"),
        name: "barangMasukDetail",
    },
];

const router = new VueRouter({
    mode: "history",
    routes,
});

export default router;
