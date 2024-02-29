import VueRouter from "vue-router";

const routes = [
    {
        path: "/gbmp/perakitan",
        component: () => import("./page/permintaanperakitan"),
        name: "permintaanperakitan",
    },
    {
        path: "/gbmp/perakitan/:id",
        component: () => import("./page/permintaanperakitan/detail"),
        name: "permintaan-perakitan-detail",
    },
];

const router = new VueRouter({
    mode: "history",
    routes,
});

export default router;
