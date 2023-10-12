import VueRouter from "vue-router";
import PermintaanReworkGBJ from "./page/PermintaanReworkGBJ";

const routes = [
    {
        path: "/gbj/rework/permintaan-rework",
        component: PermintaanReworkGBJ
    }
]

const router = new VueRouter({
    mode: "history",
    routes
});

export default router;