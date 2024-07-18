import React, { useState, useEffect } from "react";
import Breadcrumb from "../../components/Header";
import { useParams } from "react-router-dom";
import { getPenjualanById } from "../../service/edit";
import Ekatalog from "./ekatalog";
import NoneKat from "./nonekat";
import Customer from "../createPenjualan/infoCustomer";
import InfoAKN from "../createPenjualan/infoAKN";
import ProdukComponent from "../createPenjualan/Produk";
import PartComponent from "../createPenjualan/Part";
import JasaComponent from "../createPenjualan/Jasa";
import { storeEditPenjualan, getYears } from "../../service/create";

const EditPenjualan = () => {
    const [title, setTitle] = useState("Edit Penjualan");
    const [breadcumbs, setBreadcumbs] = useState([
        { name: "Beranda", link: "/" },
        { name: "Penjualan", link: "/penjualan/transaksi" },
        { name: "Edit Penjualan", link: "/penjualan/transaksi/create" },
    ]);
    const [periodePenjualan, setPeriodePenjualan] = useState(null);
    const { id } = useParams();
    const [dataEdit, setDataEdit] = useState(null);
    const [dataCopy, setDataCopy] = useState(null);

    const getData = async () => {
        const response = await getPenjualanById(id);
        // add id to data
        const penjualanData = {
            ...response,
            id: id,
            is_customer_diketahui: response.customer_id ? true : false,
            delivery_order: response.tgl_do ? true : false,
            is_no_paket_disabled: false,
            telepon: response.telepon ? response.telepon : "",
            isi_produk:
                response.produk !== undefined && response.produk.length > 0
                    ? true
                    : false,
        };
        setDataEdit(penjualanData);
        setDataCopy(penjualanData);

        const { data } = await getYears();
        setPeriodePenjualan(data);
    };

    useEffect(() => {
        getData();
    }, [id]);

    const disabledValidation = () => {
        // Cek jika jenis form adalah "ekatalog"
        if (dataEdit.jenis === "ekatalog") {
            if (dataEdit.no_paket_awal !== null && dataEdit.no_paket_awal !== "" &&
                (dataEdit.no_paket_akhir === null || dataEdit.no_paket_akhir === "") ||
                dataEdit.no_paket_akhir !== null && dataEdit.no_paket_akhir !== "" &&
                (dataEdit.no_paket_awal === null || dataEdit.no_paket_awal === "")) {
                return true;
            }
                

            if (dataEdit.status == "draft") {
                return false;
            }
            // Cek jika no_urut, status, atau produk kosong atau produk tidak ada
            if (
                dataEdit.no_urut === "" ||
                dataEdit.status === "" ||
                dataEdit?.produk?.length === 0
            ) {
                return true;
            }

            // Cek jika status adalah "ekatalog" dan validasi tambahan
            if (dataEdit.status === "sepakat") {
                if (
                    dataEdit.tgl_buat === "" ||
                    dataEdit.tgl_edit === "" ||
                    dataEdit.tgl_delivery === "" ||
                    dataEdit.tgl_po === "" ||
                    dataEdit.no_po === ""
                ) {
                    return true;
                }
            }

            if (dataEdit.produk !== undefined && dataEdit?.produk?.length > 0) {
                // Cek jika produk memiliki nama, jumlah, dan harga yang tidak kosong
                if (
                    Object.values(dataEdit.produk).some(
                        (produk) =>
                            produk.id_produk === "" ||
                            produk.jumlah === 0 ||
                            produk.harga === 0
                    )
                ) {
                    return true;
                }
            }
        } else if (dataEdit.jenis == "spa" || dataEdit.jenis == "spb") {
            if (dataEdit.produk !== undefined && dataEdit.produk.length > 0) {
                // Cek jika produk memiliki nama, jumlah, dan harga yang tidak kosong
                if (
                    Object.values(dataEdit.produk).some(
                        (produk) =>
                            produk.id_produk === "" ||
                            produk.jumlah === 0 ||
                            produk.harga === 0
                    )
                ) {
                    return true;
                }
            }

            if (
                dataEdit.sparepart !== undefined &&
                dataEdit.sparepart.length > 0
            ) {
                // Cek jika sparepart memiliki nama, jumlah, dan harga yang tidak kosong
                if (
                    Object.values(dataEdit.sparepart).some(
                        (sparepart) =>
                            sparepart?.id_sparepart === "" ||
                            sparepart.jumlah === 0 ||
                            sparepart.harga === 0
                    )
                ) {
                    return true;
                }
            }

            if (dataEdit.jasa !== undefined && dataEdit.jasa.length > 0) {
                // Cek jika jasa memiliki nama, jumlah, dan harga yang tidak kosong
                if (
                    Object.values(dataEdit.jasa).some(
                        (jasa) => jasa.id_jasa === "" || jasa.harga === 0
                    )
                ) {
                    return true;
                }
            }
        }

        // Jika semua kondisi terpenuhi, kembalikan false
        return false;
    };

    const submitForm = async () => {
        try {
            const { success } = await storeEditPenjualan(dataEdit);
            if (!success) {
                swal.fire("Gagal", "Data gagal disimpan", "error");
                return;
            }

            swal.fire("Berhasil", "Data berhasil disimpan", "success");
            getData();
        } catch (error) {
            console.log(error);
        }
    };

    return (
        <>
            <Breadcrumb title={title} breadcumbs={breadcumbs} />

            {periodePenjualan !== new Date().getFullYear() && (
                <div className="alert alert-danger" role="alert">
                    <i className="fas fa-exclamation-triangle"></i> Periode yang
                    dibuka saat ini adalah periode {periodePenjualan}
                </div>
            )}

            {dataEdit?.jenis == "ekatalog" ? (
                <Ekatalog data={dataEdit} />
            ) : (
                <NoneKat data={dataEdit} />
            )}

            {dataEdit && (
                <div className="card">
                    <div className="card-header bg-warning">
                        <div className="card-title">Form Ubah Data</div>
                    </div>
                    <div className="card-body">
                        <div className="row d-flex justify-content-center">
                            <div className="col-lg-11 col-md-12">
                                <Customer
                                    isEdit={true}
                                    formCustomer={dataEdit}
                                    setFormCustomer={setDataEdit}
                                />

                                <InfoAKN
                                    isEdit={true}
                                    formAKN={dataEdit}
                                    setFormAKN={setDataEdit}
                                />

                                {dataEdit &&
                                    dataEdit?.barang?.includes("produk") &&
                                    dataEdit.isi_produk && (
                                        <ProdukComponent
                                            formProduk={dataEdit}
                                            setFormProduk={setDataEdit}
                                            dataCopy={dataCopy}
                                        />
                                    )}
                                {dataEdit &&
                                    dataEdit?.barang?.includes("sparepart") && (
                                        <PartComponent
                                            formPart={dataEdit}
                                        setFormPart={setDataEdit}
                                        dataCopy={dataCopy}
                                        />
                                    )}

                                {dataEdit &&
                                    dataEdit?.barang?.includes("jasa") && (
                                        <JasaComponent
                                            formJasa={dataEdit}
                                        setFormJasa={setDataEdit}
                                        dataCopy={dataCopy}
                                        />
                                    )}

                                <div className="d-flex bd-highlight mb-3">
                                    <div className="p-2 bd-highlight">
                                        <a
                                            href="/penjualan/transaksi"
                                            className="btn btn-danger"
                                        >
                                            Batal
                                        </a>
                                    </div>
                                    <div className="ml-auto p-2 bd-highlight">
                                        <button
                                            onClick={submitForm}
                                            className="btn btn-warning"
                                            disabled={disabledValidation()}
                                        >
                                            Simpan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            )}
        </>
    );
};

export default EditPenjualan;
