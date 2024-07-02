import React, { useEffect, useState } from "react";
import Select from "react-select";
import { getCustomer } from "../../service/create";

const Customer = ({ formCustomer, setFormCustomer, isEdit = false }) => {
    const [dataCustomer, setDataCustomer] = useState([]);

    const selectedCustomer =
        dataCustomer.find((item) => item.id === formCustomer.customer_id) ||
        null;

    const objectKeyEkatalogAdd = {
        customer_id: null,
        nama: "",
        alamat: "",
        telepon: "",
        barang: ["produk"],
        is_customer_diketahui: false,
        no_urut: "",
        no_paket_awal: "",
        no_paket_akhir: "",
        status: "",
        isi_produk: true,
        is_no_paket_disabled: false,
        tgl_buat: null,
        tgl_edit: null,
        tgl_delivery: null,
        no_po: "",
        tgl_po: "",
        delivery_order: null,
        ket_do: "",
        instansi: "",
        satuan_kerja: "",
        alamat_instansi: "",
        provinsi: "",
        deskripsi: "",
        keterangan: "",
        alamat_pengiriman: "",
        nama_perusahaan: "",
        alamat_perusahaan: "",
        kemasan: null,
        ekspedisi: "",
    };

    const objectKeyNonEkatalogAdd = {
        isi_produk: true,
        no_po: "",
        tgl_po: "",
        delivery_order: null,
        ket_do: "",
        alamat_pengiriman: "",
        nama_perusahaan: "",
        alamat_perusahaan: "",
        kemasan: null,
        ekspedisi: "",
        nama: "",
        alamat: "",
        telepon: "",
        is_customer_diketahui: true,
        barang: ["produk"],
        customer_id: null,
    };

    const implementFormEkatalog = (e) => {
        const { ...rest } = formCustomer;

        // delete all object key objectKeyNoNEkatalogAdd when is not undefined
        const { ...restNoNEkatalog } = objectKeyNonEkatalogAdd;
        const { ...restEkatalog } = objectKeyEkatalogAdd;
        Object.keys(restNoNEkatalog).forEach((key) => {
            if (rest[key] !== undefined) {
                delete rest[key];
            }
        });

        delete rest.sparepart;
        delete rest.jasa;

        setFormCustomer({
            ...rest,
            jenis: e.target.value,
            ...restEkatalog,
        });
    };

    const implementFormNonEkatalog = (e) => {
        const { ...rest } = formCustomer;
        const { ...restNoNEkatalog } = objectKeyNonEkatalogAdd;
        const { ...restEkatalog } = objectKeyEkatalogAdd;
        Object.keys(restEkatalog).forEach((key) => {
            if (rest[key] !== undefined) {
                delete rest[key];
            }
        });

        if (e.target.value === "spb") {
            restNoNEkatalog.barang = ["sparepart"]
            delete rest.jasa;
            delete rest.produk;
        } else {
            restNoNEkatalog.barang = ["produk"]
            delete rest.sparepart;
            delete rest.jasa;
        }

        setFormCustomer({
            ...rest,
            jenis: e.target.value,
            ...restNoNEkatalog,
        });
    };

    useEffect(() => {
        getCustomer().then((data) => {
            const modifiedData = data.map((item) => ({
                label: item.nama,
                value: item.id,
                ...item,
            }));

            setDataCustomer(modifiedData);
        });
    }, []);

    return (
        <div>
            <h4>Info Customer</h4>
            <div className="card">
                <div className="card-body">
                    {!isEdit && (
                        <>
                            <div className="form-group row">
                                <label className="col-5 text-right">
                                    Jenis Penjualan
                                </label>
                                <div className="col-5">
                                    <div className="form-check form-check-inline">
                                        <input
                                            className="form-check-input"
                                            type="radio"
                                            name="customerOptions"
                                            id="inlineRadio1"
                                            value="ekatalog"
                                            onChange={(e) =>
                                                implementFormEkatalog(e)
                                            }
                                            checked={
                                                formCustomer.jenis ===
                                                "ekatalog"
                                            }
                                        />
                                        <label
                                            className="form-check-label"
                                            htmlFor="inlineRadio1"
                                        >
                                            E-Catalogue
                                        </label>
                                    </div>
                                    <div className="form-check form-check-inline">
                                        <input
                                            className="form-check-input"
                                            type="radio"
                                            name="customerOptions"
                                            id="inlineRadio2"
                                            value="spa"
                                            onChange={(e) =>
                                                implementFormNonEkatalog(e)
                                            }
                                            checked={
                                                formCustomer.jenis === "spa"
                                            }
                                        />
                                        <label
                                            className="form-check-label"
                                            htmlFor="inlineRadio2"
                                        >
                                            SPA
                                        </label>
                                    </div>
                                    <div className="form-check form-check-inline">
                                        <input
                                            className="form-check-input"
                                            type="radio"
                                            name="customerOptions"
                                            id="inlineRadio3"
                                            value="spb"
                                            onChange={(e) =>
                                                implementFormNonEkatalog(e)
                                            }
                                            checked={
                                                formCustomer.jenis === "spb"
                                            }
                                        />
                                        <label
                                            className="form-check-label"
                                            htmlFor="inlineRadio3"
                                        >
                                            SPB
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </>
                    )}
                    {(!isEdit ||
                        (isEdit && formCustomer.jenis !== "ekatalog")) && (
                        <>
                            <div className="form-group row">
                                <label className="col-5 text-right">
                                    Barang
                                </label>
                                <div className="col-5">
                                    <div className="form-check form-check-inline">
                                        <input
                                            className="form-check-input"
                                            type="checkbox"
                                            id="produk"
                                            value="produk"
                                            disabled={
                                                formCustomer.jenis === null
                                            }
                                            onChange={(e) => {
                                                const isChecked =
                                                    e.target.checked;
                                                const updatedBarang = isChecked
                                                    ? [
                                                          ...(formCustomer.barang ||
                                                              []),
                                                          e.target.value,
                                                      ]
                                                    : (
                                                          formCustomer.barang ||
                                                          []
                                                      ).filter(
                                                          (item) =>
                                                              item !==
                                                              e.target.value
                                                      );

                                                const newForm = {
                                                    ...formCustomer,
                                                };
                                                // jika unchecked maka hapus object key produk
                                                if (
                                                    !isChecked &&
                                                    newForm.produk !== undefined
                                                ) {
                                                    delete newForm.produk;
                                                } else {
                                                    newForm.produk = [
                                                        {
                                                            jumlah: 0,
                                                            harga: 0,
                                                            ongkir: 0,
                                                            pajak: true,
                                                            kalibrasi: false,
                                                            stok_distributor:
                                                                "nondsb",
                                                        },
                                                    ];
                                                }

                                                setFormCustomer({
                                                    ...newForm,
                                                    barang: updatedBarang,
                                                });
                                            }}
                                            checked={
                                                formCustomer.barang?.includes(
                                                    "produk"
                                                ) || false
                                            }
                                        />
                                        <label
                                            className="form-check-label"
                                            htmlFor="produk"
                                        >
                                            Produk
                                        </label>
                                    </div>

                                    <div className="form-check form-check-inline">
                                        <input
                                            className="form-check-input"
                                            type="checkbox"
                                            id="sparepart"
                                            value="sparepart"
                                            disabled={
                                                formCustomer.jenis ===
                                                    "ekatalog" ||
                                                formCustomer.jenis === null
                                            }
                                            onChange={(e) => {
                                                const isChecked =
                                                    e.target.checked;
                                                const updatedBarang = isChecked
                                                    ? [
                                                          ...(formCustomer.barang ||
                                                              []),
                                                          e.target.value,
                                                      ]
                                                    : (
                                                          formCustomer.barang ||
                                                          []
                                                      ).filter(
                                                          (item) =>
                                                              item !==
                                                              e.target.value
                                                      );

                                                const newForm = {
                                                    ...formCustomer,
                                                };
                                                // jika unchecked maka hapus object key sparepart
                                                if (
                                                    !isChecked &&
                                                    newForm.sparepart !==
                                                        undefined
                                                ) {
                                                    delete newForm.sparepart;
                                                } else {
                                                    newForm.sparepart = [
                                                        {
                                                            sparepart_id: null,
                                                            harga: 0,
                                                            jumlah: 0,
                                                            pajak: true,
                                                        },
                                                    ];
                                                }

                                                setFormCustomer({
                                                    ...newForm,
                                                    barang: updatedBarang,
                                                });
                                            }}
                                            checked={
                                                formCustomer.barang?.includes(
                                                    "sparepart"
                                                ) || false
                                            }
                                        />
                                        <label
                                            className="form-check-label"
                                            htmlFor="sparepart"
                                        >
                                            Sparepart
                                        </label>
                                    </div>

                                    <div className="form-check form-check-inline">
                                        <input
                                            className="form-check-input"
                                            type="checkbox"
                                            id="jasa"
                                            value="jasa"
                                            disabled={
                                                formCustomer.jenis ===
                                                    "ekatalog" ||
                                                formCustomer.jenis === null ||
                                                isEdit
                                            }
                                            onChange={(e) => {
                                                const isChecked =
                                                    e.target.checked;
                                                const updatedBarang = isChecked
                                                    ? [
                                                          ...(formCustomer.barang ||
                                                              []),
                                                          e.target.value,
                                                      ]
                                                    : (
                                                          formCustomer.barang ||
                                                          []
                                                      ).filter(
                                                          (item) =>
                                                              item !==
                                                              e.target.value
                                                      );

                                                const newForm = {
                                                    ...formCustomer,
                                                };
                                                // jika unchecked maka hapus object key jasa
                                                if (
                                                    !isChecked &&
                                                    newForm.jasa !== undefined
                                                ) {
                                                    delete newForm.jasa;
                                                } else {
                                                    newForm.jasa = [
                                                        {
                                                            jasa_id: null,
                                                            harga: 0,
                                                            pajak: true,
                                                        }
                                                    ]
                                                }
                                                setFormCustomer({
                                                    ...newForm,
                                                    barang: updatedBarang,
                                                });
                                            }}
                                            checked={
                                                formCustomer.barang?.includes(
                                                    "jasa"
                                                ) || false
                                            }
                                        />
                                        <label
                                            className="form-check-label"
                                            htmlFor="jasa"
                                        >
                                            Jasa
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </>
                    )}
                    <div className="form-group row">
                        <label className="col-5 text-right">
                            Nama Customer / Distributor
                        </label>
                        <div className="col-5">
                            <div className="form-check form-check-inline">
                                <input
                                    className="form-check-input"
                                    type="radio"
                                    name="isCustomerDiketahui"
                                    id="isCustomerDiketahui1"
                                    value="diketahui"
                                    checked={
                                        formCustomer.is_customer_diketahui ===
                                        true
                                    }
                                    onChange={(e) =>
                                        setFormCustomer({
                                            ...formCustomer,
                                            is_customer_diketahui: true,
                                            customer_id: 213,
                                            nama: "PT. EMIINDO Jaya Bersama",
                                            alamat: "Komplek Perkantoran Pulomas Jalan Perintis Kemerdekaan 10 No. 8, pulo Gadung, Jakarta Timur, DKI Jak",
                                        })
                                    }
                                />
                                <label
                                    className="form-check-label"
                                    htmlFor="isCustomerDiketahui1"
                                >
                                    Sudah Diketahui
                                </label>
                            </div>
                            {(formCustomer.jenis == "ekatalog" ||
                                formCustomer.jenis == null) && (
                                <div className="form-check form-check-inline">
                                    <input
                                        className="form-check-input"
                                        type="radio"
                                        name="isCustomerDiketahui"
                                        id="isCustomerDiketahui2"
                                        value="tidak_diketahui"
                                        checked={
                                            formCustomer.is_customer_diketahui ==
                                            false
                                        }
                                        onChange={(e) => {
                                            const newForm = { ...formCustomer };
                                            newForm.customer_id = null;
                                            newForm.nama = "";
                                            newForm.alamat = "";
                                            newForm.telepon = "";

                                            if (
                                                newForm.alamat_pengiriman ==
                                                "distributor"
                                            ) {
                                                newForm.alamat_perusahaan = "";
                                                newForm.nama_perusahaan = "";
                                            }

                                            setFormCustomer({
                                                ...newForm,
                                                is_customer_diketahui: false,
                                            });
                                        }}
                                    />
                                    <label
                                        className="form-check-label"
                                        htmlFor="isCustomerDiketahui2"
                                    >
                                        Belum Diketahui
                                    </label>
                                </div>
                            )}
                        </div>
                    </div>
                    <div className="form-group row">
                        <label className="col-5 text-right"></label>
                        <div className="col-5">
                            <Select
                                isDisabled={
                                    formCustomer.is_customer_diketahui ===
                                        false ||
                                    formCustomer.is_customer_diketahui === null
                                }
                                value={selectedCustomer}
                                options={dataCustomer}
                                placeholder="Pilih Customer"
                                onChange={(e) => {
                                    const newForm = JSON.parse(
                                        JSON.stringify(formCustomer)
                                    );
                                    newForm.customer_id = e.value;
                                    newForm.nama = e.label;
                                    newForm.alamat = e.alamat;
                                    newForm.telepon = e.telp ?? "";

                                    if (
                                        formCustomer.alamat_pengiriman ==
                                        "distributor"
                                    ) {
                                        newForm.alamat_perusahaan = e.alamat;
                                        newForm.nama_perusahaan = e.label;
                                    }

                                    setFormCustomer(newForm);
                                }}
                            />
                        </div>
                    </div>
                    <div className="form-group row">
                        <label className="col-5 text-right">Alamat</label>
                        <div className="col-5">
                            <textarea
                                className="form-control"
                                rows="3"
                                value={formCustomer.alamat}
                                disabled
                            />
                        </div>
                    </div>
                    <div className="form-group row">
                        <label className="col-5 text-right">Telepon</label>
                        <div className="col-5">
                            <input
                                className="form-control"
                                value={formCustomer.telepon}
                                disabled
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Customer;
