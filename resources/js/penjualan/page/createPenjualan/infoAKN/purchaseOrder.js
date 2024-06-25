import React, { useState } from "react";
import InputText from "../../../components/inputText";

const PurchaseOrder = ({ formAKN, setFormAKN }) => {
    return (
        <div className="card">
            <div className="card-body">
                <div className="form-group row">
                    <label className="col-5 text-right">No PO</label>
                    <div className="col-5">
                        <InputText
                            value={formAKN.no_po ?? ""}
                            onChange={(e) =>
                                setFormAKN({
                                    ...formAKN,
                                    no_po: e.target.value,
                                })
                            }
                        />
                    </div>
                </div>
                <div className="form-group row">
                    <label className="col-5 text-right">Tanggal PO</label>
                    <div className="col-5">
                        <input
                            max={new Date().toISOString().split("T")[0]}
                            value={formAKN.tgl_po ?? ""}
                            onChange={(e) =>
                                setFormAKN({
                                    ...formAKN,
                                    tgl_po: e.target.value,
                                })
                            }
                            type="date"
                            className="form-control"
                        />
                    </div>
                </div>
                <div className="form-group row">
                    <label className="col-5 text-right">Delivery Order</label>
                    <div className="col-5">
                        <div className="form-check form-check-inline">
                            <input
                                className="form-check-input"
                                type="radio"
                                name="deliveryOrder"
                                id="doTersedia"
                                value="tersedia"
                                checked={formAKN.delivery_order === true}
                                onChange={(e) => {
                                    setFormAKN({
                                        ...formAKN,
                                        delivery_order: true,
                                        nomor_do: "",
                                        tgl_do: "",
                                    });
                                }}
                            />
                            <label
                                className="form-check-label"
                                htmlFor="doTersedia"
                            >
                                Tersedia
                            </label>
                        </div>
                        <div className="form-check form-check-inline">
                            <input
                                className="form-check-input"
                                type="radio"
                                name="deliveryOrder"
                                id="doTidakTersedia"
                                value="tidak_tersedia"
                                checked={formAKN.delivery_order === false}
                                onChange={(e) => {
                                    const newFormAKN = { ...formAKN };
                                    newFormAKN.delivery_order = false;

                                    delete newFormAKN.nomor_do;
                                    delete newFormAKN.tgl_do;

                                    setFormAKN(newFormAKN);
                                }}
                            />
                            <label
                                className="form-check-label"
                                htmlFor="doTidakTersedia"
                            >
                                Tidak Tersedia
                            </label>
                        </div>
                    </div>
                </div>
                {formAKN.delivery_order == true && (
                    <>
                        <div className="form-group row">
                            <label className="col-5 text-right">Nomor DO</label>
                            <div className="col-5">
                                <InputText
                                    className="form-control"
                                    value={formAKN.nomor_do}
                                    placeholder="Masukkan Nomor DO"
                                    onChange={(e) =>
                                        setFormAKN({
                                            ...formAKN,
                                            nomor_do: e.target.value,
                                        })
                                    }
                                ></InputText>
                            </div>
                        </div>
                        <div className="form-group row">
                            <label className="col-5 text-right">
                                Tanggal DO
                            </label>
                            <div className="col-5">
                                <InputText
                                    type={"date"}
                                    value={formAKN.tgl_do}
                                    onChange={(e) =>
                                        setFormAKN({
                                            ...formAKN,
                                            tgl_do: e.target.value,
                                        })
                                    }
                                />
                            </div>
                        </div>
                    </>
                )}
                <div className="form-group row">
                    <label className="col-5 text-right">Keterangan</label>
                    <div className="col-5">
                        <textarea
                            className="form-control"
                            value={formAKN.ket_do}
                            placeholder="Masukkan Keterangan"
                            onChange={(e) =>
                                setFormAKN({
                                    ...formAKN,
                                    ket_do: e.target.value,
                                })
                            }
                        ></textarea>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default PurchaseOrder;
