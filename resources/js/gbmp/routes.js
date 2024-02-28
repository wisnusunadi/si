import VueRouter from "vue-router";

const routes = [
    {
        path: "/gbmp/perakitan",
        component: () => import('./page/permintaanperakitan'),
        name: "permintaanperakitan"
    },
];

const router = new VueRouter({
    mode: "history",
    routes
})

export default router;
