import React, { useState, useEffect } from "react";
import DeskripsiEkatalog from "./deskripsiEkatalog";
import PurchaseOrder from "./purchaseOrder";
import Instansi from "./instansi";
import Pengiriman from "./pengiriman";

const InfoAKN = ({ formAKN, setFormAKN, isEdit = false, dataCopy }) => {
    const [tabs, setTabs] = useState("deskripsiEkatalog");

    useEffect(() => {
        setTabs(formAKN.jenis === "ekatalog" ? "deskripsiEkatalog" : "purchaseOrder");
    }, [formAKN.jenis]);

    return (
        <div>
            <h4>
                Info
                {formAKN.jenis === "ekatalog" ? " AKN" : " Penjualan"}
            </h4>
            <div className="card">
                <div className="card-body">
                    <ul
                        className="nav nav-pills mb-3 nav-justified"
                        id="pills-tab"
                        role="tablist"
                    >
                        {formAKN.jenis === "ekatalog" && (
                            <li className="nav-item" role="presentation">
                                <a
                                    className={
                                        tabs === "deskripsiEkatalog"
                                            ? "nav-link active"
                                            : "nav-link"
                                    }
                                    id="pills-penjualan-tab"
                                    data-toggle="pill"
                                    href="#pills-penjualan"
                                    role="tab"
                                    aria-controls="pills-penjualan"
                                    aria-selected="true"
                                    onClick={() => setTabs("deskripsiEkatalog")}
                                >
                                    Deskripsi Ekatalog
                                </a>
                            </li>
                        )}
                        <li className="nav-item" role="presentation">
                            <a
                                className={
                                    tabs === "purchaseOrder"
                                        ? "nav-link active"
                                        : "nav-link"
                                }
                                id="pills-po-ekat-tab"
                                data-toggle="pill"
                                href="#pills-po-ekat"
                                role="tab"
                                aria-controls="pills-po-ekat"
                                aria-selected="false"
                                onClick={() => setTabs("purchaseOrder")}
                            >
                                Purchase Order
                            </a>
                        </li>
                        {formAKN.jenis === "ekatalog" && (
                            <li className="nav-item" role="presentation">
                                <a
                                    className={
                                        tabs === "instansi"
                                            ? "nav-link active"
                                            : "nav-link"
                                    }
                                    id="pills-instansi-tab"
                                    data-toggle="pill"
                                    href="#pills-instansi"
                                    role="tab"
                                    aria-controls="pills-instansi"
                                    aria-selected="false"
                                    onClick={() => setTabs("instansi")}
                                >
                                    Instansi
                                </a>
                            </li>
                        )}
                        <li className="nav-item" role="presentation">
                            <a
                                className={
                                    tabs === "pengiriman"
                                        ? "nav-link active"
                                        : "nav-link"
                                }
                                id="pills-pengiriman-tab"
                                data-toggle="pill"
                                href="#pills-pengiriman"
                                role="tab"
                                aria-controls="pills-pengiriman"
                                aria-selected="false"
                                onClick={() => setTabs("pengiriman")}
                            >
                                Pengiriman
                            </a>
                        </li>
                    </ul>
                    <div className="tab-content" id="pills-tabContent">
                        <div
                            className={
                                tabs === "deskripsiEkatalog"
                                    ? "tab-pane fade show active"
                                    : "tab-pane fade"
                            }
                            id="pills-penjualan"
                            role="tabpanel"
                            aria-labelledby="pills-penjualan-tab"
                        >
                            <DeskripsiEkatalog
                                formAKN={formAKN}
                                setFormAKN={setFormAKN}
                                isEdit={isEdit}
                                dataCopy={dataCopy}
                            />
                        </div>
                        <div
                            className={
                                tabs === "purchaseOrder"
                                    ? "tab-pane fade show active"
                                    : "tab-pane fade"
                            }
                            id="pills-po-ekat"
                            role="tabpanel"
                            aria-labelledby="pills-po-ekat-tab"
                        >
                            <PurchaseOrder
                                formAKN={formAKN}
                                setFormAKN={setFormAKN}
                            />
                        </div>
                        <div
                            className={
                                tabs === "instansi"
                                    ? "tab-pane fade show active"
                                    : "tab-pane fade"
                            }
                            id="pills-instansi"
                            role="tabpanel"
                            aria-labelledby="pills-instansi-tab"
                        >
                            {formAKN.jenis === "ekatalog" && (
                                <Instansi
                                    formAKN={formAKN}
                                    setFormAKN={setFormAKN}
                                />
                            )}
                        </div>

                        <div
                            className={
                                tabs === "pengiriman"
                                    ? "tab-pane fade show active"
                                    : "tab-pane fade"
                            }
                            id="pills-pengiriman"
                            role="tabpanel"
                            aria-labelledby="pills-pengiriman-tab"
                        >
                            <Pengiriman
                                formAKN={formAKN}
                                setFormAKN={setFormAKN}
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default InfoAKN;
