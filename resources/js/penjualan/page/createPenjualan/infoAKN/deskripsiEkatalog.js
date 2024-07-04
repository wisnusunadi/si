import React, { useState, useEffect } from "react";
import Select from "react-select";
import InputText from "../../../components/inputText";
import InputNumber from "../../../components/inputNumber";
import { noUrutCheck } from "../../../service/create";

const DeskripsiEkatalog = ({ formAKN, setFormAKN, isEdit }) => {
    const [noUrutError, setNoUrutError] = useState(null);
    const pilihanPaket = [
        {
            value: "AK1-",
            label: "AK1-",
        },
        {
            value: "FKS-",
            label: "FKS-",
        },
        {
            value: "KLK-",
            label: "KLK-",
        },
    ];

    const checkNoUrut = (no_urut) => {
        if (no_urut) {
            noUrutCheck(no_urut).then((data) => {
                if (data == 0) setNoUrutError(null);
                else {
                    setNoUrutError("No Urut sudah digunakan");
                }
            });
        } else {
            setNoUrutError("No Urut tidak boleh kosong");
        }
    };

    return (
        <div className="card">
            <div className="card-body">
                <div className="form-group row">
                    <label className="col-5 text-right">No Urut</label>
                    <div className="col-2">
                        <InputNumber
                            className={`form-control ${
                                noUrutError ? "is-invalid" : ""
                            }`}
                            value={formAKN?.no_urut || ""}
                            onChange={(e) => {
                                setFormAKN({
                                    ...formAKN,
                                    no_urut: e.target.value,
                                });
                                checkNoUrut(e.target.value);
                            }}
                        />
                        {noUrutError && (
                            <div className="invalid-feedback">
                                {noUrutError}
                            </div>
                        )}
                    </div>
                </div>
                <div className="form-group row">
                    <label className="col-5 text-right">No Paket</label>
                    <div className="col-5">
                        <div className="input-group">
                            <div className="input-group-prepend">
                                <Select
                                    value={pilihanPaket.find(
                                        (item) =>
                                            item.value === formAKN.no_paket_awal
                                    )}
                                    placeholder="Pilih Paket"
                                    options={pilihanPaket}
                                    onChange={(e) =>
                                        setFormAKN({
                                            ...formAKN,
                                            no_paket_awal: e.value,
                                        })
                                    }
                                />
                            </div>
                            <InputText
                                value={formAKN?.no_paket_akhir || ""}
                                disabled={formAKN.is_no_paket_disabled}
                                className={`form-control ${
                                    (formAKN.no_paket_awal !== null &&
                                        formAKN.no_paket_awal !== "" &&
                                        (formAKN.no_paket_akhir === null ||
                                            formAKN.no_paket_akhir === "")) ||
                                    (formAKN.no_paket_akhir !== null &&
                                        formAKN.no_paket_akhir !== "" &&
                                        (formAKN.no_paket_awal === null ||
                                            formAKN.no_paket_awal === ""))
                                        ? "is-invalid"
                                        : ""
                                }`}
                                onChange={(e) =>
                                    setFormAKN({
                                        ...formAKN,
                                        no_paket_akhir: e.target.value,
                                    })
                                }
                            />
                            {formAKN.status == "batal" ||
                            formAKN.status == "draft" ? (
                                <div className="input-group-append">
                                    <span className="input-group-text">
                                        <div className="form-check form-check-inline">
                                            <input
                                                type="checkbox"
                                                checked={
                                                    !formAKN.is_no_paket_disabled
                                                }
                                                onChange={(e) => {
                                                    setFormAKN({
                                                        ...formAKN,
                                                        is_no_paket_disabled:
                                                            !e.target.checked,
                                                    });
                                                }}
                                            />
                                        </div>
                                    </span>
                                </div>
                            ) : (
                                ""
                            )}
                        </div>
                        {(formAKN.no_paket_awal !== null &&
                            formAKN.no_paket_awal !== "" &&
                            (formAKN.no_paket_akhir === null ||
                                formAKN.no_paket_akhir === "")) ||
                        (formAKN.no_paket_akhir !== null &&
                            formAKN.no_paket_akhir !== "" &&
                            (formAKN.no_paket_awal === null ||
                                formAKN.no_paket_awal === "")) ? (
                            <div className="invalid-feedback d-block">
                                No Paket tidak boleh kosong
                            </div>
                        ) : null}
                    </div>
                </div>
                <div className="form-group row">
                    <label className="col-5 text-right">Status</label>
                    <div className="col-7">
                        <div className="form-check form-check-inline">
                            <input
                                className="form-check-input"
                                type="radio"
                                name="status"
                                id="sepakat"
                                value="sepakat"
                                onChange={(e) => {
                                    const newFormAKN = { ...formAKN };

                                    newFormAKN.status = e.target.value;
                                    newFormAKN.isi_produk = true;
                                    newFormAKN.is_no_paket_disabled = isEdit
                                        ? true
                                        : false;

                                    if (isEdit) {
                                        newFormAKN.barang = ["produk"];
                                    }

                                    setFormAKN(newFormAKN);
                                }}
                                checked={formAKN.status === "sepakat"}
                            />
                            <label
                                className="form-check-label"
                                htmlFor="sepakat"
                            >
                                Sepakat
                            </label>
                        </div>
                        <div className="form-check form-check-inline">
                            <input
                                className="form-check-input"
                                type="radio"
                                name="status"
                                id="negosiasi"
                                value="negosiasi"
                                onChange={(e) => {
                                    const newFormAKN = { ...formAKN };

                                    newFormAKN.status = e.target.value;
                                    newFormAKN.isi_produk = true;
                                    newFormAKN.is_no_paket_disabled = isEdit
                                        ? true
                                        : false;
                                    newFormAKN.tgl_delivery = "";

                                    if (isEdit) {
                                        newFormAKN.barang = ["produk"];
                                    }

                                    setFormAKN(newFormAKN);
                                }}
                                checked={formAKN.status === "negosiasi"}
                            />
                            <label
                                className="form-check-label"
                                htmlFor="negosiasi"
                            >
                                Negosiasi
                            </label>
                        </div>
                        <div className="form-check form-check-inline">
                            <input
                                className="form-check-input"
                                type="radio"
                                name="status"
                                id="batal"
                                value="batal"
                                onChange={(e) => {
                                    const newFormAKN = { ...formAKN };

                                    newFormAKN.status = e.target.value;
                                    newFormAKN.isi_produk = false;
                                    newFormAKN.is_no_paket_disabled = isEdit
                                        ? true
                                        : false;
                                    newFormAKN.tgl_delivery = "";

                                    if (newFormAKN.produk !== undefined) {
                                        delete newFormAKN.produk;
                                    }

                                    setFormAKN(newFormAKN);

                                    // setFormAKN(newFormAKN);
                                }}
                                checked={formAKN.status === "batal"}
                            />
                            <label className="form-check-label" htmlFor="batal">
                                Batal
                            </label>
                        </div>
                        <div className="form-check form-check-inline">
                            <input
                                className="form-check-input"
                                type="radio"
                                name="status"
                                id="draft"
                                value="draft"
                                onChange={(e) => {
                                    const newFormAKN = { ...formAKN };
                                    newFormAKN.status = e.target.value;
                                    newFormAKN.isi_produk = false;
                                    newFormAKN.is_no_paket_disabled = true;
                                    newFormAKN.tgl_delivery = "";

                                    if (newFormAKN?.produk !== undefined) {
                                        delete newFormAKN?.produk;
                                    }

                                    setFormAKN(newFormAKN);
                                }}
                                checked={formAKN.status === "draft"}
                            />
                            <label className="form-check-label" htmlFor="draft">
                                Draft
                            </label>
                        </div>
                    </div>
                </div>
                {(formAKN.status === "batal" || formAKN.status === "draft") && (
                    <div className="form-group row">
                        <label className="col-5 text-right"></label>
                        <div className="custom-control custom-checkbox">
                            <input
                                type="checkbox"
                                className="custom-control-input"
                                id="isiProduk"
                                checked={formAKN.isi_produk}
                                onChange={(e) => {
                                    const newFormAKN = { ...formAKN };
                                    newFormAKN.isi_produk = e.target.checked;
                                    if (e.target.checked == true && isEdit) {
                                        newFormAKN.barang = ["produk"];
                                    } else if (
                                        e.target.checked == true &&
                                        !isEdit
                                    ) {
                                        newFormAKN.barang = ["produk"];
                                    } else {
                                        newFormAKN.barang = [];
                                    }
                                    setFormAKN(newFormAKN);
                                }}
                            />
                            <label
                                className="custom-control-label"
                                htmlFor="isiProduk"
                            >
                                Isi Produk
                            </label>
                        </div>
                    </div>
                )}

                <div className="form-group row">
                    <label className="col-5 text-right">Tanggal Buat</label>
                    <div className="col-4">
                        <input
                            type="date"
                            max={new Date().toISOString().split("T")[0]}
                            className="form-control"
                            value={formAKN?.tgl_buat || ""}
                            onChange={(e) =>
                                setFormAKN({
                                    ...formAKN,
                                    tgl_buat: e.target.value,
                                })
                            }
                        />
                    </div>
                </div>

                <div className="form-group row">
                    <label className="col-5 text-right">Tanggal Edit</label>
                    <div className="col-4">
                        <input
                            type="date"
                            max={new Date().toISOString().split("T")[0]}
                            className="form-control"
                            value={formAKN?.tgl_edit || ""}
                            onChange={(e) =>
                                setFormAKN({
                                    ...formAKN,
                                    tgl_edit: e.target.value,
                                })
                            }
                        />
                    </div>
                </div>

                <div className="form-group row">
                    <label className="col-5 text-right">Tanggal Delivery</label>
                    <div className="col-4">
                        <input
                            type="date"
                            className="form-control"
                            value={formAKN?.tgl_delivery || ""}
                            disabled={
                                formAKN.status === "negosiasi" ||
                                formAKN.status === "batal" ||
                                formAKN.status === "draft"
                            }
                            onChange={(e) =>
                                setFormAKN({
                                    ...formAKN,
                                    tgl_delivery: e.target.value,
                                })
                            }
                            min={formAKN.tgl_buat}
                        />
                    </div>
                </div>
            </div>
        </div>
    );
};

export default DeskripsiEkatalog;
