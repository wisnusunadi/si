import VueRouter from "vue-router";
import Produk from "./page/produk";

const routes = [
    {
        path: "/administrator/produk",
        component: Produk,
        name: "produk"
    }
];

const router = new VueRouter({
    routes,
    mode: "history"
});

export default router;