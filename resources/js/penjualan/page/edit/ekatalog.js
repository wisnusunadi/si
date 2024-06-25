import React from "react";
import "./header.css";

const Ekatalog = ({ data }) => {
    const checkIsExist = (data) => {
        return data !== undefined && data !== null ? data : "-";
    };

    const dateFormat = (date) => {
        // make date from yyyy-mm-dd format dd-mm-yyyy
        if (date === null || date === undefined || date === "") {
            return "-";
        }

        const dateArr = date.split("-");
        return `${dateArr[2]}-${dateArr[1]}-${dateArr[0]}`;
    };

    const cetakSPPB = (id) => {
        return `/penjualan/penjualan/cetak_surat_perintah/${id}`;
    }

    const statusBadge = (status) => {
        switch (status) {
            case "sepakat":
                return <span className="green-text">Sepakat</span>;
            case "negosiasi":
                return <span className="yellow-text">Negosiasi</span>;
            case "batal":
                return <span className="red-text">Batal</span>;
            default:
                return <span className="blue-text">-</span>;
        }
    }

    return (
        <div className="card" id="ekatalog">
            <div className="card-body">
                <h4 className="margin">Data Penjualan </h4>
                <div className="row">
                    <div className="col-lg-11 col-md-12">
                        <div className="row d-flex justify-content-between">
                            <div className="p-2 cust">
                                <div>
                                    <div className="margin">
                                        <small className="text-muted">
                                            Info Instansi
                                        </small>
                                    </div>
                                    <div className="margin">
                                        <b>{checkIsExist(data?.instansi)}</b>
                                    </div>
                                    <div className="margin">
                                        <b>
                                            {checkIsExist(data?.satuan_kerja)}
                                        </b>
                                    </div>
                                    <div className="margin">
                                        <b>
                                            {checkIsExist(data?.provinsi_nama)}
                                        </b>
                                    </div>
                                </div>
                                <div className="margin-top">
                                    <div className="margin">
                                        <small className="text-muted">
                                            Info Customer
                                        </small>
                                    </div>
                                    <div className="margin">
                                        <b>{checkIsExist(data?.nama)}</b>
                                    </div>
                                    <div className="margin">
                                        <b>{checkIsExist(data?.alamat)}</b>
                                    </div>
                                    <div className="margin">
                                        <b>
                                            {checkIsExist(
                                                data?.customer_provinsi
                                            )}
                                        </b>
                                    </div>
                                    <div className="margin">
                                        <b>{checkIsExist(data?.telepon)}</b>
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
                                    <div>
                                        <b>{checkIsExist(data?.so)}</b>
                                    </div>
                                </div>
                                <div className="margin">
                                    <div>
                                        <small className="text-muted">
                                            No AKN
                                        </small>
                                    </div>
                                    <div>
                                        <b>
                                            {checkIsExist(data?.no_paket_awal)}
                                            {checkIsExist(data?.no_paket_akhir)}
                                        </b>
                                    </div>
                                </div>
                                <div className="margin">
                                    <div>
                                        <small className="text-muted">
                                            Tanggal Order
                                        </small>
                                    </div>
                                    <div>
                                        <b>{dateFormat(data?.tanggal_order)}</b>
                                    </div>
                                </div>
                                <div className="margin">
                                    <div>
                                        <small className="text-muted">
                                            Tanggal Batas Kontrak
                                        </small>
                                    </div>
                                    <div>
                                        <b>{dateFormat(data?.tgl_delivery)}</b>
                                    </div>
                                </div>
                                <div className="margin">
                                    <div>
                                        <small className="text-muted">
                                            Status
                                        </small>
                                    </div>
                                    <div className="margin-top">
                                        <b>{statusBadge(data?.status)}</b>
                                    </div>
                                </div>
                            </div>
                            <div className="p-2">
                                <div className="margin">
                                    <div>
                                        <small className="text-muted">
                                            No PO
                                        </small>
                                    </div>
                                    <div>
                                        <b>{checkIsExist(data?.no_po)}</b>
                                    </div>
                                </div>
                                <div className="margin">
                                    <div>
                                        <small className="text-muted">
                                            Tanggal PO
                                        </small>
                                    </div>
                                    <div>
                                        <b>{dateFormat(data?.tgl_po)}</b>
                                    </div>
                                </div>
                                <div className="margin">
                                    <div>
                                        <small className="text-muted">
                                            No DO
                                        </small>
                                    </div>
                                    <div>
                                        <b>{checkIsExist(data?.nomor_do)}</b>
                                    </div>
                                </div>
                                <div className="margin">
                                    <div>
                                        <small className="text-muted">
                                            Tanggal DO
                                        </small>
                                    </div>
                                    <div>
                                        <b>{dateFormat(data?.tgl_do)}</b>
                                    </div>
                                </div>
                                <div className="margin">
                                    <div>
                                        <small className="text-muted">
                                            Cetak SPPB
                                        </small>
                                    </div>
                                    <div>
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
        </div>
    );
};

export default Ekatalog;
