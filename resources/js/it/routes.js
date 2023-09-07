import VueRouter from "vue-router";
import Produk from "./page/produk";
import Kategori from "./page/kategori";

const routes = [
    {
        path: "/administrator/produk",
        component: Produk,
        name: "produk"
    },
    {
        path: "/administrator/kategori_produk",
        component: Kategori,
        name: "kategori"
    }
];

const router = new VueRouter({
    routes,
    mode: "history"
});

export default router;