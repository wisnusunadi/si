import React, { useEffect, useState } from "react";
import Select from "react-select";
import CurrencyInput from "../../../components/inputPrice";
import AmountInput from "../../../components/inputAmount";
import ModalNoSeriDsb from "./noseri";
import { getProduk, getVariasi } from "../../../service/create";
import "./style.css";
import Editor from "react-simple-wysiwyg";

const ProdukComponent = ({ formProduk, setFormProduk }) => {
    const [dataProduk, setDataProduk] = useState([]);
    const [isCheckedAll, setIsCheckedAll] = useState(false);
    const [detailProduk, setDetailProduk] = useState(null);
    const [showModal, setShowModal] = useState(false);

    const fetchData = async () => {
        try {
            if (formProduk.jenis == "ekatalog") {
                const ekatalog = await getProduk("ekatalog");
                const addProduk = ekatalog.map((item) => ({
                    value: item.id,
                    label: item.nama,
                    ...item,
                }));

                setDataProduk(addProduk);
            } else {
                const nonekat = await getProduk("spa");
                const addProduk = nonekat.map((item) => ({
                    value: item.id,
                    label: item.nama,
                    ...item,
                }));

                setDataProduk(addProduk);
            }
        } catch (error) {
            console.error(error);
        }
    };

    const handleInputProduk = async (value, index, key) => {
        const newProduk = formProduk.produk.map((item, idx) => {
            if (idx === index) {
                return {
                    ...item,
                    [key]: value,
                };
            }
            return item;
        });

        if (value == "nondsb") {
            delete newProduk[index].noseridsb;
            if (newProduk[index].id_produk) {
                newProduk[index].catatan = "";
            }
        }

        if (value == "dsb") {
            newProduk[index].noseridsb = [];
            if (newProduk[index].id_produk) {
                delete newProduk[index].catatan;
            }
        }

        if (key == "id_produk") {
            newProduk[index].id_produk = value;
            newProduk[index].catatan = "";
            newProduk[index].kalibrasi = false;
            if (newProduk[index].stok_distributor == "dsb") {
                delete newProduk[index].catatan;
            }
            // find produk by id
            const { data: variasi } = await getVariasi(value);
            const produk = dataProduk.find((item) => item.value === value);
            newProduk[index].variasi = mappingDataVariasiProduk(
                variasi[0]?.produk
            );
            newProduk[index].harga = produk.harga;
            newProduk[index].is_kalibrasi = produk.is_kalibrasi;
        }
        setFormProduk({
            ...formProduk,
            produk: newProduk,
        });
    };

    const mappingDataVariasiProduk = (item) => {
        if (item !== undefined) {
            // mapping item array include guang_barang_jadi
            return item.map((item) => ({
                label: item.nama,
                gudang_barang_jadi: item.gudang_barang_jadi.map((gudang) => ({
                    value: gudang.id,
                    label: gudang.nama.trim() == "" ? item.nama : gudang.nama,
                    stok: gudang.stok,
                })),
                variasiSelected: item.gudang_barang_jadi[0].id,
            }));
        }
    };

    const formatOptionLabel = ({ label, stok }, { context }) => (
        <div
            style={{
                display: "flex",
                justifyContent: "space-between",
                alignItems: "center",
            }}
        >
            <span
                style={{
                    whiteSpace: context === "menu" ? "normal" : "nowrap",
                    overflow: context === "menu" ? "visible" : "hidden",
                    textOverflow: context === "menu" ? "clip" : "ellipsis",
                }}
            >
                {label}
            </span>
            <span className="badge blue-text" style={{ whiteSpace: "nowrap" }}>
                {stok}
            </span>
        </div>
    );
    const addNmrSeriDsb = (index) => {
        const produkSelected = {
            ...formProduk.produk[index],
            noseridsb: formProduk.produk[index].noseridsb ?? [],
            index,
        };
        setDetailProduk(produkSelected);
        // open modal
        setShowModal(true);
    };

    const setNoSeriDsb = (noseri) => {
        let noseriArray = noseri.split(/[\n, \t]/);

        // remove empty string
        noseriArray = noseriArray.filter((item) => item !== "");

        // remove duplicate
        noseriArray = [...new Set(noseriArray)];

        // change to array
        noseriArray = noseriArray.map((item) => item.trim());

        const newProduk = formProduk.produk.map((item, index) => {
            if (index === detailProduk.index) {
                return {
                    ...item,
                    noseridsb: noseriArray,
                };
            }
            return item;
        });

        setFormProduk({
            ...formProduk,
            produk: newProduk,
        });
    };

    const handleCheckAll = () => {
        const updatedProduk = formProduk.produk.map((item) => ({
            ...item,
            stok_distributor: isCheckedAll ? "nondsb" : "dsb",
        }));

        // jika semua stok distributor tidak diceklis, maka noseridsb dihapus
        updatedProduk.forEach((item) => {
            if (item.stok_distributor === "nondsb") {
                delete item.noseridsb;
                if (item.id_produk) {
                    item.catatan = "";
                }
            } else {
                item.noseridsb = [];
                if (item.id_produk) {
                    delete item.catatan;
                }
            }
        });

        setFormProduk((prevFormProduk) => ({
            ...prevFormProduk,
            produk: updatedProduk,
        }));

        setIsCheckedAll((prevIsCheckedAll) => !prevIsCheckedAll);
    };

    const addProduk = () => {
        setFormProduk({
            ...formProduk,
            produk: [
                ...formProduk.produk,
                {
                    jumlah: 0,
                    harga: 0,
                    ongkir: 0,
                    pajak: true,
                    kalibrasi: false,
                    stok_distributor: "nondsb",
                },
            ],
        });
    };

    // check apakah stok distributor sudah di ceklis semua
    useEffect(() => {
        const isAllChecked = (formProduk.produk ?? []).every(
            (item) => item.stok_distributor === "dsb"
        );
        setIsCheckedAll(isAllChecked);
    }, [formProduk.produk]);

    // show modal no seri dsb
    useEffect(() => {
        if (showModal) {
            $(".modalNoSeriDsb").modal("show");
        }
    }, [showModal]);

    // get produk
    useEffect(() => {
        fetchData();
    }, [formProduk.jenis]);

    useEffect(() => {
        if (formProduk.isi_produk && formProduk.barang.includes("produk")) {
            const produk = formProduk.produk ?? [
                {
                    jumlah: 0,
                    harga: 0,
                    ongkir: 0,
                    pajak: true,
                    kalibrasi: false,
                    stok_distributor: "nondsb",
                },
            ];
            setFormProduk({
                ...formProduk,
                produk,
            });
        } else {
            setFormProduk((prevFormProduk) => {
                const { produk, ...newFormProduk } = prevFormProduk;
                return newFormProduk;
            });
        }
    }, [formProduk.isi_produk, formProduk.barang]);

    return (
        <>
            {showModal && (
                <ModalNoSeriDsb
                    noseri={detailProduk.noseridsb}
                    setShowModal={setShowModal}
                    submit={setNoSeriDsb}
                />
            )}
            <h4>Data Produk</h4>
            <div className="card">
                <div className="card-body">
                    <div className="d-flex flex-row-reverse bd-highlight">
                        <div className="p-2 bd-highlight">
                            <button
                                className="btn btn-primary"
                                onClick={addProduk}
                            >
                                <i className="fas fa-plus"></i> Produk
                            </button>
                        </div>
                    </div>

                    {/* <div className="table-responsive"> */}
                    <table className="table text-center">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th
                                    style={{
                                        width: "30%",
                                    }}
                                >
                                    Nama Paket
                                </th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>Ongkir</th>
                                <th>Subtotal</th>
                                <th>Pajak</th>
                                <th>Kalibrasi</th>
                                <th>
                                    Stok Distributor <br />{" "}
                                    <input
                                        type="checkbox"
                                        checked={isCheckedAll}
                                        onChange={handleCheckAll}
                                    />{" "}
                                </th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {/* mapping array produk */}
                            {(formProduk.produk ?? []).map((item, index) => (
                                <tr key={index}>
                                    <td>{index + 1}</td>
                                    <td>
                                        <Select
                                            value={
                                                item.id_produk !== undefined
                                                    ? dataProduk.find(
                                                          (produk) =>
                                                              produk.value ===
                                                              item.id_produk
                                                      )
                                                    : null
                                            }
                                            placeholder="Pilih Produk"
                                            options={dataProduk}
                                            onChange={(e) => {
                                                handleInputProduk(
                                                    e.value,
                                                    index,
                                                    "id_produk"
                                                );
                                            }}
                                            styles={{
                                                option: (provided, state) => ({
                                                    ...provided,
                                                    textAlign: "left",
                                                }),
                                            }}
                                        />
                                        {/* detect object key catatan */}
                                        {/* {item.catatan !== undefined &&
                                                    item.stok_distributor ==
                                                        "nondsb" && (
                                                        <div className="pt-4">
                                                            <b>
                                                                Req. Fitur dan
                                                                Varian
                                                            </b>
                                                            <div className="form-group text-left">
                                                                <Editor
                                                                    value={
                                                                        item.catatan
                                                                    }
                                                                    onChange={(
                                                                        e
                                                                    ) => {
                                                                        handleInputProduk(
                                                                            e
                                                                                .target
                                                                                .value,
                                                                            index,
                                                                            "catatan"
                                                                        );
                                                                    }}
                                                                />
                                                            </div>
                                                        </div>
                                                    )} */}
                                        {item.id_produk !== undefined && (
                                            <div className="pt-4">
                                                <p className="text-bold">
                                                    Detail Produk
                                                </p>
                                                {item?.variasi?.map(
                                                    (variasi, index) => (
                                                        <div
                                                            key={index}
                                                            className="card-body bg-blue-produk"
                                                        >
                                                            <h6>
                                                                {variasi.label}
                                                            </h6>
                                                            <Select
                                                                options={
                                                                    variasi.gudang_barang_jadi
                                                                }
                                                                placeholder="Pilih Variasi"
                                                                value={variasi.gudang_barang_jadi.find(
                                                                    (item) =>
                                                                        item.value ===
                                                                        variasi.variasiSelected
                                                                )}
                                                                onChange={(
                                                                    e
                                                                ) => {
                                                                    const newProduk =
                                                                        formProduk.produk.map(
                                                                            (
                                                                                item,
                                                                                idx
                                                                            ) => {
                                                                                if (
                                                                                    idx ===
                                                                                    index
                                                                                ) {
                                                                                    return {
                                                                                        ...item,
                                                                                        variasi:
                                                                                            item.variasi.map(
                                                                                                (
                                                                                                    variasi,
                                                                                                    idx
                                                                                                ) => {
                                                                                                    if (
                                                                                                        idx ===
                                                                                                        index
                                                                                                    ) {
                                                                                                        return {
                                                                                                            ...variasi,
                                                                                                            variasiSelected:
                                                                                                                e.value,
                                                                                                        };
                                                                                                    }
                                                                                                    return variasi;
                                                                                                }
                                                                                            ),
                                                                                    };
                                                                                }
                                                                                return item;
                                                                            }
                                                                        );
                                                                    setFormProduk(
                                                                        {
                                                                            ...formProduk,
                                                                            produk: newProduk,
                                                                        }
                                                                    );
                                                                }}
                                                                formatOptionLabel={
                                                                    formatOptionLabel
                                                                }
                                                            />
                                                        </div>
                                                    )
                                                )}
                                            </div>
                                        )}
                                    </td>
                                    <td>
                                        <AmountInput
                                            value={item.jumlah}
                                            onChange={(e) => {
                                                handleInputProduk(
                                                    e.target.value,
                                                    index,
                                                    "jumlah"
                                                );
                                            }}
                                        />
                                    </td>
                                    <td>
                                        <CurrencyInput
                                            value={item.harga}
                                            onChange={(e) => {
                                                handleInputProduk(
                                                    e,
                                                    index,
                                                    "harga"
                                                );
                                            }}
                                        />
                                    </td>
                                    <td>
                                        <CurrencyInput
                                            value={item.ongkir}
                                            onChange={(e) => {
                                                handleInputProduk(
                                                    e,
                                                    index,
                                                    "ongkir"
                                                );
                                            }}
                                        />
                                    </td>
                                    <td>
                                        <CurrencyInput
                                            disabled={true}
                                            value={
                                                item.harga * item.jumlah +
                                                item.ongkir
                                            }
                                        />
                                    </td>
                                    <td>
                                        <div className="custom-control custom-switch">
                                            <input
                                                type="checkbox"
                                                className="custom-control-input"
                                                id={`customSwitch1-${index}`}
                                                checked={item.pajak}
                                                onChange={(e) => {
                                                    handleInputProduk(
                                                        e.target.checked,
                                                        index,
                                                        "pajak"
                                                    );
                                                }}
                                            />
                                            <label
                                                className="custom-control-label"
                                                htmlFor={`customSwitch1-${index}`}
                                            >
                                                {item.pajak === true
                                                    ? "PPN"
                                                    : "Non PPN"}
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="custom-control custom-switch">
                                            <input
                                                type="checkbox"
                                                className="custom-control-input"
                                                id={`customSwitchKalibrasi-${index}`}
                                                disabled={
                                                    item.is_kalibrasi == false
                                                }
                                                checked={item.kalibrasi}
                                                onChange={(e) => {
                                                    handleInputProduk(
                                                        e.target.checked,
                                                        index,
                                                        "kalibrasi"
                                                    );
                                                }}
                                            />
                                            <label
                                                className="custom-control-label"
                                                htmlFor={`customSwitchKalibrasi-${index}`}
                                            >
                                                {item.kalibrasi === true
                                                    ? "Ya"
                                                    : "Tidak"}
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <input
                                            type="checkbox"
                                            checked={
                                                item.stok_distributor == "dsb"
                                            }
                                            onChange={(e) => {
                                                handleInputProduk(
                                                    e.target.checked
                                                        ? "dsb"
                                                        : "nondsb",
                                                    index,
                                                    "stok_distributor"
                                                );
                                            }}
                                        />
                                        <br />
                                        {item.stok_distributor == "dsb" && (
                                            <button
                                                className="btn btn-outline-primary btn-sm"
                                                onClick={() => {
                                                    addNmrSeriDsb(index);
                                                }}
                                            >
                                                No Seri
                                            </button>
                                        )}
                                    </td>
                                    <td>
                                        <i
                                            className="fas fa-minus text-danger"
                                            onClick={() => {
                                                const newProduk =
                                                    formProduk.produk.filter(
                                                        (item, idx) =>
                                                            idx !== index
                                                    );
                                                setFormProduk({
                                                    ...formProduk,
                                                    produk: newProduk,
                                                });
                                            }}
                                        ></i>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colSpan={100} className="text-right">
                                    <b>
                                        Total Harga{" "}
                                        {new Intl.NumberFormat("id-ID", {
                                            style: "currency",
                                            currency: "IDR",
                                        }).format(
                                            (formProduk.produk ?? []).reduce(
                                                (acc, item) =>
                                                    acc +
                                                    item.harga * item.jumlah +
                                                    item.ongkir,
                                                0
                                            )
                                        )}
                                    </b>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    {/* </div> */}
                </div>
            </div>
        </>
    );
};

export default ProdukComponent;
