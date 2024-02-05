import VueRouter from "vue-router";
import Kalibrasi from "./page/kalibrasi";
import DetailKalibrasi from "./page/kalibrasi/kalibrasi_internal/detail";
import DetailKalibrasiEksternal from "./page/kalibrasi/kalibrasi_eksternal/detail";
import SertifikasiPerPO from "./page/sertifikasiperpo";
import DetailSertifikasiPerPO from "./page/sertifikasiperpo/detail";
import SertifikasiPerNoSeri from "./page/sertifikasipernoseri";
import DetailSertifikasiPerNoSeri from "./page/sertifikasiperpo/detailnoseri";
import TransferLab from "./page/transferlab";
import TransferLabRiwayat from "./page/transferlab/qc";
// master
import MasterAlat from "./page/master/alat";
import MasterAlatDetail from "./page/master/alat/detail";
import MasterDokumen from "./page/master/dokumen";
import MasterDokumenDetail from "./page/master/dokumen/detail";
import MasterProduk from "./page/master/produk";
import MasterRuangan from "./page/master/ruangan";
import MasterRuanganDetail from "./page/master/ruangan/detail";
import MasterKode from "./page/master/kode";
// qc transfer
import TransferQC from "./page/transferqc";
import Monitoring from "./page/monitoring";
import MonitoringDetail from "./page/monitoring/detail";
// gbj transfer
import ChangeUnit from "./page/transfergbj";

const routes = [
    {
        path: "/lab/kalibrasi",
        component: Kalibrasi,
        name: "kalibrasi"
    },
    {
        path: "/lab/kalibrasiInternal/:id",
        component: DetailKalibrasi,
        name: "detail-kalibrasi-internal"
    },
        {
        path: "/lab/kalibrasiEksternal/:id",
        component: DetailKalibrasiEksternal,
        name: "detail-kalibrasi-eksternal"
    },
    {
        path: "/lab/sertifikasiperpo",
        component: SertifikasiPerPO,
        name: "sertifikasiperpo"
    },
    {   
        path: "/lab/detail-sertifikasiperpo/:id",
        component: DetailSertifikasiPerPO,
        name: "detail-sertifikasiperpo"
    },
    {
        path: "/lab/sertifikasipernoseri",
        component: SertifikasiPerNoSeri,
        name: "sertifikasipernoseri"
    },
    {
        path: "/lab/detailsertifikasipernoseri/:id",
        component: DetailSertifikasiPerNoSeri,
        name: "detailsertifikasipernoseri"
    },
    // master
    {
        path: "/lab/master/alat",
        component: MasterAlat,
        name: "master-alat"
    },
    {
        path: "/lab/master/alat/:id",
        component: MasterAlatDetail,
        name: "master-alat-detail"
    },
    {
        path: "/lab/master/dokumen",
        component: MasterDokumen,
        name: "master-dokumen"
    },
    {
        path: "/lab/master/dokumen/:id",
        component: MasterDokumenDetail,
        name: "master-dokumen-detail"
    },
    {
        path: "/lab/master/produk",
        component: MasterProduk,
        name: "master-produk"
    },
    {
        path: "/lab/master/ruangan",
        component: MasterRuangan,
        name: "master-ruangan"
    },
    {
        path: "/lab/master/ruangan/:id",
        component: MasterRuanganDetail,
        name: "master-ruangan-detail"
    },
    {
        path: "/lab/master/kode",
        component: MasterKode,
        name: "master-kode"
    },
    {
        path: "/lab/transfer",
        component: TransferLab,
        name: "transfer-lab"
    },
    {
        path: "/lab/riwayatTransfer",
        component: TransferLabRiwayat,
        name: "transfer-lab-riwayat"
    },
        {
        path: "/qc/riwayatTransfer",
        component: TransferLabRiwayat,
        name: "transfer-qc-riwayat"
    },
    // qc
    {
        path: "/qc/monitoring_kalibrasi",
        component: Monitoring,
        name: "monitoring"
    },
    {
        path: "/qc/monitoring_kalibrasi/:id",
        component: MonitoringDetail,
        name: "monitoring-detail"
    },
    {
        path: "/qc/transfer",
        component: TransferQC,
        name: "transfer"
    },
    // gbj
    {
        path: "/gbj/changeunit",
        component: ChangeUnit,
        name: "changeunit"
    }
];

const router = new VueRouter({
    routes,
    mode: "history"
});

export default router;