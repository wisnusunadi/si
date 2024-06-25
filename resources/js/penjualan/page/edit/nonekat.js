import React, { useEffect, useState } from "react";
import "./header.css";

const NoneKat = ({ data }) => {
    const jenisPaket = data?.jenis == "spa" ? "SPA" : "SPB";

    const checkIsExist = (data) => {
        return data !== undefined && data !== null ? data : "-";
    };

    const dateFormat = (date) => {
        // make date from yyyy-mm-dd format dd-mm-yyyy
        if (date === null || date === undefined) {
            return "-";
        }

        const dateArr = date.split("-");
        return `${dateArr[2]}-${dateArr[1]}-${dateArr[0]}`;
    };

    const cetakSPPB = (id) => {
        return `/penjualan/penjualan/cetak_surat_perintah/${id}`;
    };

    return (
        <div className="card">
            <div className="card-body">
                <div className="row">
                    <div className="col-lg-11 col-md-12">
                        <h5 className="margin">Info Penjualan {jenisPaket}</h5>
                        <div className="row d-flex justify-content-between">
                            <div className="p-2 cust">
                                <div className="margin">
                                    <small>Info Customer</small>
                                </div>
                                <div className="margin">
                                    <b>{checkIsExist(data?.nama)}</b>
                                </div>
                                <div className="margin">
                                    <b>{checkIsExist(data?.alamat)}</b>
                                </div>
                                <div className="margin">
                                    <b>
                                        {checkIsExist(data?.customer_provinsi)}
                                    </b>
                                </div>
                                <div className="margin">
                                    <b>{checkIsExist(data?.telepon)}</b>
                                </div>
                            </div>
                            <div className="p-2">
                                <div className="margin">
                                    <div>
                                        <small className="text-muted">
                                            No PO
                                        </small>
                                    </div>
                                    <div id="no_po">
                                        <b>{checkIsExist(data?.no_po)}</b>
                                    </div>
                                </div>
                                <div className="margin">
                                    <div>
                                        <small className="text-muted">
                                            Tanggal PO
                                        </small>
                                    </div>
                                    <b>
                                        <b>{dateFormat(data?.tgl_po)}</b>
                                    </b>
                                </div>
                            </div>
                            <div className="p-2">
                                <div className="margin">
                                    <div>
                                        <small className="text-muted">
                                            No DO
                                        </small>
                                    </div>
                                    <div id="no_po">
                                        <b>{checkIsExist(data?.nomor_do)}</b>
                                    </div>
                                </div>
                                <div className="margin">
                                    <div>
                                        <small className="text-muted">
                                            Tanggal DO
                                        </small>
                                    </div>
                                    <div id="no_po">
                                        <b>{dateFormat(data?.tgl_do)}</b>
                                    </div>
                                </div>
                            </div>
                            <div className="p-2">
                                <div className="margin">
                                    <div>
                                        <small className="text-muted">
                                            No SO
                                        </small>
                                    </div>
                                    <div id="no_so">
                                        <b>{checkIsExist(data?.so)}</b>
                                    </div>
                                </div>
                                <div className="margin">
                                    <div>
                                        <small className="text-muted">
                                            Status
                                        </small>
                                    </div>

                                    <div
                                        id="status"
                                        className="badge purple-text "
                                    >
                                        PO
                                    </div>
                                </div>
                            </div>
                            <div className="p-2">
                                <div className="margin">
                                    <div>
                                        <small className="text-muted">
                                            Cetak SPPB
                                        </small>
                                    </div>
                                    {data?.no_po && (
                                        <a
                                            target="_blank"
                                            href={cetakSPPB(data?.id)}
                                        >
                                            <button
                                                className="btn btn-outline-primary btn-sm"
                                                type="button"
                                            >
                                                <i className="fas fa-print"></i>
                                                SPPB
                                            </button>
                                        </a>
                                    )}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default NoneKat;
