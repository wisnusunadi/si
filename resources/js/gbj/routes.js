import VueRouter from "vue-router";
import PermintaanReworkGBJ from "./page/PermintaanReworkGBJ";
import penerimaanRework from "./page/penerimaanRework";

const routes = [
    {
        path: "/gbj/rework/permintaan-rework",
        component: PermintaanReworkGBJ
    },
    {
        path: "/gbj/rework/penerimaan-rework",
        component: penerimaanRework
    }
]

const router = new VueRouter({
    mode: "history",
    routes
});

export default router;