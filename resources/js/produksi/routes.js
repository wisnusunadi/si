import VueRouter from 'vue-router';
import Riwayat from './Page/riwayatTransfer';

const routes = [
    {
        path: "/produksi/riwayat_transfer",
        component: Riwayat,
        name: "riwayatTransfer"
    }
];

const router = new VueRouter({
    routes,
    mode: 'history'
});

export default router;

