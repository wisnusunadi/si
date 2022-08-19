import Home from './Page/Home'
import VueRouter from 'vue-router';
import Detail from './Page/DetailEkat'
import Penerimaan from './Page/Penerimaan'

const routes = [
    {
        path: '/penjualan/request_emiindo/akn_po',
        name: 'Index',
        component: Home
    },
    {
        path: '/penjualan/request_emiindo/akn_po/:id/:jenis',
        name: 'detail',
        component: Detail
    },
    {
        path: '/penjualan/request_emiindo/daftar_penerimaan',
        name: 'penerimaan',
        component: Penerimaan
    }
];

const router = new VueRouter({
    routes,
    mode: 'history'
});

export default router;