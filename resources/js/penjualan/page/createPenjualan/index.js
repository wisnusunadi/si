import React, { useState, useEffect } from "react";
import Breadcrumb from "../../components/Header";
import Customer from "./infoCustomer";
import InfoAKN from "./infoAKN";
import ProdukComponent from "./Produk";
import PartComponent from "./Part";
import JasaComponent from "./Jasa";
import "../../css/globalCSS.css";
import { storePenjualan, getYears } from "../../service/create";

const CreatePenjualan = () => {
    const [title, setTitle] = useState("Penjualan");
    const [breadcumbs, setBreadcumbs] = useState([
        { name: "Beranda", link: "/" },
        { name: "Penjualan", link: "/penjualan/transaksi" },
        { name: "Tambah Penjualan", link: "/penjualan/transaksi/create" },
    ]);

    const [pesananId, setPesananId] = useState(null);

    const [periodePenjualan, setPeriodePenjualan] = useState(null);

    const [formCustomer, setFormCustomer] = useState({
        jenis: null,
        barang: [],
        is_customer_diketahui: null,
        nama: "",
        alamat: "",
        telepon: "",
    });

    const disabledValidation = () => {
        // Cek jika jenis form adalah "ekatalog"
        if (formCustomer.jenis == null) {
            return true;
        }

        if (formCustomer.jenis === "ekatalog") {
            if (
                (formCustomer.no_paket_awal !== null &&
                    formCustomer.no_paket_awal !== "" &&
                    (formCustomer.no_paket_akhir === null ||
                        formCustomer.no_paket_akhir === "")) ||
                (formCustomer.no_paket_akhir !== null &&
                    formCustomer.no_paket_akhir !== "" &&
                    (formCustomer.no_paket_awal === null ||
                        formCustomer.no_paket_awal === ""))
            ) {
                return true;
            }
            // Cek jika no_urut, status, atau produk kosong atau produk tidak ada
            if (formCustomer.status == "draft") {
                return false;
            }
            if (
                formCustomer.no_urut === "" ||
                formCustomer.status === "" ||
                formCustomer.produk.length === 0
            ) {
                return true;
            }

            // Cek jika status adalah "ekatalog" dan validasi tambahan
            if (formCustomer.status === "sepakat") {
                if (
                    formCustomer.tgl_buat === "" ||
                    formCustomer.tgl_edit === "" ||
                    formCustomer.tgl_delivery === "" ||
                    formCustomer.tgl_po === "" ||
                    formCustomer.no_po === ""
                ) {
                    return true;
                }
            }

            if (
                formCustomer.produk !== undefined &&
                formCustomer.produk.length > 0
            ) {
                // Cek jika produk memiliki nama, jumlah, dan harga yang tidak kosong
                if (
                    Object.values(formCustomer.produk).some(
                        (produk) =>
                            produk.id_produk === "" ||
                            produk.jumlah === 0 ||
                            produk.harga === 0
                    )
                ) {
                    return true;
                }
            }
        } else if (formCustomer.jenis == "spa" || formCustomer.jenis == "spb") {
            if (formCustomer.nama == "") {
                return true;
            }

            if (
                formCustomer.produk !== undefined &&
                formCustomer.produk.length > 0
            ) {
                // Cek jika produk memiliki nama, jumlah, dan harga yang tidak kosong
                if (
                    Object.values(formCustomer.produk).some(
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
                formCustomer.sparepart !== undefined &&
                formCustomer.sparepart.length > 0
            ) {
                // Cek jika sparepart memiliki nama, jumlah, dan harga yang tidak kosong
                if (
                    Object.values(formCustomer.sparepart).some(
                        (sparepart) =>
                            sparepart?.id_sparepart === "" ||
                            sparepart.jumlah === 0 ||
                            sparepart.harga === 0
                    )
                ) {
                    return true;
                }
            }

            if (
                formCustomer.jasa !== undefined &&
                formCustomer.jasa.length > 0
            ) {
                // Cek jika jasa memiliki nama, jumlah, dan harga yang tidak kosong
                if (
                    Object.values(formCustomer.jasa).some(
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
            const { success, data, message } = await storePenjualan(
                formCustomer
            );
            if (!success) {
                swal.fire("Gagal", message, "error");
                return;
            }

            swal.fire("Berhasil", "Data berhasil disimpan", "success");
            const { pesanan_id } = data;
            setPesananId(pesanan_id);
            setFormCustomer({
                jenis: null,
                barang: [],
                is_customer_diketahui: null,
                nama: "",
                alamat: "",
                telepon: "",
            });
        } catch (error) {
            console.log(error);
        }
    };

    useEffect(() => {
        const fetchYears = async () => {
            const { success, data } = await getYears();
            if (success) {
                setPeriodePenjualan(data);
            }
        };
        fetchYears();
    }, []);

    const cetakSPPB = (id) => {
        return `/penjualan/penjualan/cetak_surat_perintah/${id}`;
    };

    return (
        <div>
            <Breadcrumb title={title} breadcumbs={breadcumbs} />

            {periodePenjualan !== new Date().getFullYear() && (
                <div className="alert alert-danger" role="alert">
                    <i className="fas fa-exclamation-triangle"></i> Periode yang
                    dibuka saat ini adalah periode {periodePenjualan}
                </div>
            )}
            {pesananId && (
                <div className="alert alert-success" role="alert">
                    <div className="d-flex bd-highlight">
                        <div className="p-2 flex-grow-1 bd-highlight card-title">
                            Berhasil Menambahkan Data
                        </div>
                        <div className="p-2 bd-highlight">
                            <a
                                href={cetakSPPB(pesananId)}
                                target="_blank"
                                className="btn btn-light"
                                style={{ color: "#000000" }}
                            >
                                <i class="fas fa-print"></i> Cetak SPPB
                            </a>
                        </div>
                    </div>
                </div>
            )}

            <div className="card">
                <div className="card-header bg-info">
                    <div className="card-title">Form Tambah Data</div>
                </div>

                <div className="card-body">
                    <div className="row d-flex justify-content-center">
                        <div className="col-lg-11 col-md-12">
                            <Customer
                                formCustomer={formCustomer}
                                setFormCustomer={setFormCustomer}
                            />

                            {formCustomer.jenis !== null && (
                                <InfoAKN
                                    formAKN={formCustomer}
                                    setFormAKN={setFormCustomer}
                                />
                            )}

                            {formCustomer &&
                                formCustomer.barang.includes("produk") &&
                                formCustomer.isi_produk && (
                                    <ProdukComponent
                                        formProduk={formCustomer}
                                        setFormProduk={setFormCustomer}
                                    />
                                )}

                            {formCustomer &&
                                formCustomer.barang.includes("sparepart") && (
                                    <PartComponent
                                        formPart={formCustomer}
                                        setFormPart={setFormCustomer}
                                    />
                                )}

                            {formCustomer &&
                                formCustomer.barang.includes("jasa") && (
                                    <JasaComponent
                                        formJasa={formCustomer}
                                        setFormJasa={setFormCustomer}
                                    />
                                )}
                            <div className="d-flex bd-highlight mb-3">
                                <div className="p-2 bd-highlight">
                                    <a href="/penjualan/transaksi"
                                        className="btn btn-danger"
                                    >
                                        Batal
                                    </a>
                                </div>
                                <div className="ml-auto p-2 bd-highlight">
                                    <button
                                        onClick={submitForm}
                                        className="btn btn-info"
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
        </div>
    );
};

export default CreatePenjualan;
