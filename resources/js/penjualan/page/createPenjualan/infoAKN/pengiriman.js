import React from "react";
import Select from "react-select";
import InputText from "../../../components/inputText";
import { getEkspedisiAll } from "../../../service/create";


const Pengiriman = ({ formAKN, setFormAKN }) => {
    const [ekspedisi, setEkspedisi] = React.useState([]);

    const getEkspedisi = async () => {
        try {
            const data = await getEkspedisiAll();
            const mappingData = data.map((item) => ({
                label: item.nama,
                value: item.id,
                ...item,
            }));

            setEkspedisi(mappingData);
        } catch (error) {
            console.error(error);
        }
    };

    const selectAlamatPengiriman = (e) => {
        const newFormAKN = { ...formAKN };

        if (e.target.value === "distributor") {
            newFormAKN.nama_perusahaan = newFormAKN?.nama;
            newFormAKN.alamat_perusahaan = newFormAKN?.alamat;
        } else if (e.target.value === "instansi") {
            newFormAKN.nama_perusahaan = newFormAKN?.satuan_kerja;
            newFormAKN.alamat_perusahaan = newFormAKN?.alamat_instansi;
        }

        setFormAKN({
            ...newFormAKN,
            alamat_pengiriman: e.target.value,
        });
    };

    React.useEffect(() => {
        getEkspedisi();
    }, []);

    return (
        <div className="card">
            <div className="card-body">
                <div className="form-group row">
                    <label className="col-5 text-right">
                        Alamat Pengiriman
                    </label>
                    <div className="col-5">
                        <div className="form-check form-check-inline">
                            <input
                                className="form-check-input"
                                type="radio"
                                name="alamatPengiriman"
                                id="distributor"
                                value="distributor"
                                checked={
                                    formAKN.alamat_pengiriman === "distributor"
                                }
                                onChange={(e) => selectAlamatPengiriman(e)}
                            />
                            <label
                                className="form-check-label"
                                htmlFor="distributor"
                            >
                                Sama dengan distributor
                            </label>
                        </div>
                        {formAKN.jenis == "ekatalog" && (
                            <div className="form-check form-check-inline">
                                <input
                                    className="form-check-input"
                                    type="radio"
                                    name="alamatPengiriman"
                                    id="instansi"
                                    value="instansi"
                                    checked={
                                        formAKN.alamat_pengiriman === "instansi"
                                    }
                                    onChange={(e) => selectAlamatPengiriman(e)}
                                />
                                <label
                                    className="form-check-label"
                                    htmlFor="instansi"
                                >
                                    Sama dengan instansi
                                </label>
                            </div>
                        )}
                        <div className="form-check form-check-inline">
                            <input
                                className="form-check-input"
                                type="radio"
                                name="alamatPengiriman"
                                id="lainnya"
                                value="lainnya"
                                checked={
                                    formAKN.alamat_pengiriman === "lainnya"
                                }
                                onChange={(e) => selectAlamatPengiriman(e)}
                            />
                            <label
                                className="form-check-label"
                                htmlFor="lainnya"
                            >
                                Lainnya
                            </label>
                        </div>
                    </div>
                </div>

                <div className="form-group row">
                    <label className="col-5 text-right"></label>
                    <div className="col-5">
                        <InputText
                            disabled={
                                formAKN.alamat_pengiriman === "distributor" ||
                                formAKN.alamat_pengiriman === "instansi" ||
                                formAKN.alamat_pengiriman === ""
                            }
                            value={formAKN.nama_perusahaan}
                            onChange={(e) =>
                                setFormAKN({
                                    ...formAKN,
                                    nama_perusahaan: e.target.value,
                                })
                            }
                        />
                    </div>
                </div>
                <div className="form-group row">
                    <label className="col-5 text-right"></label>
                    <div className="col-5">
                        <InputText
                            disabled={
                                formAKN.alamat_pengiriman === "distributor" ||
                                formAKN.alamat_pengiriman === "instansi" ||
                                formAKN.alamat_pengiriman === ""
                            }
                            className={`form-control 
                                ${
                                    formAKN.alamat_pengiriman ===
                                        "distributor" && formAKN.nama === ""
                                        ? "is-invalid"
                                        : ""
                                        ? formAKN.alamat_pengiriman ===
                                              "instansi" &&
                                          formAKN.satuan_kerja === ""
                                            ? "is-invalid"
                                            : ""
                                        : ""
                                }`}
                            value={formAKN.alamat_perusahaan}
                            onChange={(e) =>
                                setFormAKN({
                                    ...formAKN,
                                    alamat_perusahaan: e.target.value,
                                })
                            }
                        />

                        <div className="invalid-feedback">
                            {formAKN.alamat_pengiriman === "distributor"
                                ? "Nama distributor harus diisi"
                                : "Nama instansi harus diisi"}
                        </div>
                    </div>
                </div>
                <div className="form-group row">
                    <label className="col-5 text-right">Kemasan</label>
                    <div className="col-5">
                        <div className="col-5">
                            <div className="form-check form-check-inline">
                                <input
                                    className="form-check-input"
                                    type="radio"
                                    name="kemasan"
                                    id="peti"
                                    value="peti"
                                    checked={formAKN.kemasan === "peti"}
                                    onChange={(e) =>
                                        setFormAKN({
                                            ...formAKN,
                                            kemasan: e.target.value,
                                        })
                                    }
                                />
                                <label
                                    className="form-check-label"
                                    htmlFor="peti"
                                >
                                    PETI
                                </label>
                            </div>
                            <div className="form-check form-check-inline">
                                <input
                                    className="form-check-input"
                                    type="radio"
                                    name="kemasan"
                                    id="nonpeti"
                                    value="nonpeti"
                                    checked={formAKN.kemasan === "nonpeti"}
                                    onChange={(e) =>
                                        setFormAKN({
                                            ...formAKN,
                                            kemasan: e.target.value,
                                        })
                                    }
                                />
                                <label
                                    className="form-check-label"
                                    htmlFor="nonpeti"
                                >
                                    NON PETI
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div className="form-group row">
                    <label className="col-5 text-right">Ekspedisi</label>
                    <div className="col-5">
                        <div className="col-5">
                            <Select
                                placeholder="Pilih Ekspedisi"
                                value={ekspedisi.find(
                                    (item) => item.value === formAKN.ekspedisi
                                )}
                                options={ekspedisi}
                                onChange={(e) =>
                                    setFormAKN({
                                        ...formAKN,
                                        ekspedisi: e.value,
                                    })
                                }
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Pengiriman;
