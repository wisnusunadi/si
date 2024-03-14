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
    {
        path: "/gbmp/perakitan/transfer/:id",
        component: () =>
            import("./page/permintaanPerakitan/detail/permintaanTransfer"),
        name: "permintaan-perakitan-transfer",
    },
    {
        path: "/gbmp/perakitan/transfer/:id/:route",
        component: () =>
            import("./page/permintaanPerakitan/detail/detailPengiriman"),
        name: "detail-perakitan-transfer",
    },
];

const router = new VueRouter({
    mode: "history",
    routes,
});

export default router;
