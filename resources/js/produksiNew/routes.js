import VueRouter from "vue-router";
import PermintaanReworks from './page/permintaan'
import prosesSetReworks from './page/prosesSet'
import prosesSetReworksDetail from './page/prosesSet/proses/detail'
import perakitanBerlangsung from './page/perakitanBerlangsung'
import Transfer from './page/transfer'
import RiwayatPerakitan from './page/riwayatPerakitan'

const routes = [
    {
        path: "/produksi/permintaanreworks",
        component: PermintaanReworks,
        name: "permintaanreworks",
    },
    {
        path: "/produksi/prosesSetReworks",
        component: prosesSetReworks,
        name: "prosesSetReworks",
    },
    {
        path: "/produksi/prosesSetReworks/:id",
        component: prosesSetReworksDetail,
        name: "prosesSetReworksDetail",
    },
    {
        path: "/produksi/jadwal_perakitan",
        component: perakitanBerlangsung,
        name: "perakitanBerlangsung",
    },
    {
        path: "/produksi/pengiriman",
        component: Transfer,
        name: "pengiriman",
    },
    {
        path: "/produksi/riwayat_perakitan",
        component: RiwayatPerakitan,
        name: "riwayatPerakitan",
    },
    {
        path: "/produksi/kamus_produk",
        name: "kamusProduk",
        component: () => import("./page/kamusProduk"),
    },
    {
        path: "/produksi/cetak_nomor_seri",
        name: "cetakNomorSeri",
        component: () => import("./page/cetaknoseri"),
    },
    {
        path: "/produksi/permintaan_goods",
        name: "permintaanGoods",
        component: () => import("./page/permintaanGoods"),
    },
    {
        path: "/produksi/permintaan_goods/:id",
        name: "permintaanGoodsDetail",
        component: () => import("./page/permintaanGoods/daftarProses/detail"),
    },
    {
        path: "/produksi/permintaan_goods_mgr",
        name: "permintaanGoodsMgr",
        component: () => import("./page/atasan/permintaanGoods"),
    },
    {
        path: "/produksi/permintaan_goods_mgr/:id",
        name: "permintaanGoodsMgrDetail",
        component: () =>
            import("./page/atasan/permintaanGoods/daftarProses/detail"),
    },
    {
        path: "/produksi/permintaan_goods_detail/:id",
        name: "permintaanGoodsDetailLain",
        component: () => import("./page/permintaanGoods/selesaiProses/detail"),
    }
];
const router = new VueRouter({
    mode: "history",
    routes
});

export default router;