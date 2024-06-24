import React, { useEffect } from "react";
import AutoComplete from "../../../components/autoComplete";
import Select from "react-select";
import {
    getInstansi,
    getSatuanKerja,
    getAlamat,
    getProvinsi,
    getDeskripsi,
} from "../../../service/create";

const Instansi = ({ formAKN, setFormAKN }) => {
    const [instansi, setInstansi] = React.useState([]);
    const [satuanKerja, setSatuanKerja] = React.useState([]);
    const [alamatInstansi, setAlamatInstansi] = React.useState([]);
    const [provinsi, setProvinsi] = React.useState([]);
    const [deskripsi, setDeskripsi] = React.useState([]);

    const getData = async () => {
        try {
            const instansi = await getInstansi();
            const satuanKerja = await getSatuanKerja();
            const alamat = await getAlamat();
            const provinsi = await getProvinsi();
            const deskripsi = await getDeskripsi();

            const mappingSatuanKerja = satuanKerja.map((item) => item.satuan);

            const mappingAlamat = alamat.map((item) => item.alamat);

            const dataProvinsi = provinsi.map((item) => ({
                label: item.nama,
                value: item.id,
                ...item,
            }));

            const mappingDeskripsi = deskripsi.map((item) => item.deskripsi);

            setInstansi(instansi);
            setSatuanKerja(mappingSatuanKerja);
            setAlamatInstansi(mappingAlamat);
            setProvinsi(dataProvinsi);
            setDeskripsi(mappingDeskripsi);
        } catch (error) {
            console.error(error);
        }
    };

    useEffect(() => {
        getData();
    }, []);

    return (
        <div className="card">
            <div className="card-body">
                <div className="form-group row">
                    <label className="col-5 text-right">Instansi</label>
                    <div className="col-5">
                        <AutoComplete
                            value={formAKN.instansi}
                            onChange={(e) =>
                                setFormAKN({
                                    ...formAKN,
                                    instansi: e.target.value,
                                })
                            }
                            data={instansi}
                        />
                    </div>
                </div>
                <div className="form-group row">
                    <label className="col-5 text-right">Satuan Kerja</label>
                    <div className="col-5">
                        <AutoComplete
                            value={formAKN.satuan_kerja}
                            onChange={(e) =>
                                setFormAKN({
                                    ...formAKN,
                                    satuan_kerja: e.target.value,
                                })
                            }
                            data={satuanKerja}
                        />
                    </div>
                </div>
                <div className="form-group row">
                    <label className="col-5 text-right">Alamat Instansi</label>
                    <div className="col-5">
                        <AutoComplete
                            type="textarea"
                            value={formAKN.alamat_instansi}
                            onChange={(e) =>
                                setFormAKN({
                                    ...formAKN,
                                    alamat_instansi: e.target.value,
                                })
                            }
                            data={alamatInstansi}
                        />
                    </div>
                </div>
                <div className="form-group row">
                    <label className="col-5 text-right">Provinsi</label>
                    <div className="col-5">
                        <Select
                            options={provinsi}
                            value={provinsi.find(
                                (item) => item.value === formAKN.provinsi
                            )}
                            placeholder="Pilih Provinsi"
                            onChange={(e) =>
                                setFormAKN({
                                    ...formAKN,
                                    provinsi: e.value,
                                })
                            }
                        />
                    </div>
                </div>
                <div className="form-group row">
                    <label className="col-5 text-right">Deskripsi</label>
                    <div className="col-5">
                        <AutoComplete
                            type="textarea"
                            value={formAKN.deskripsi}
                            onChange={(e) =>
                                setFormAKN({
                                    ...formAKN,
                                    deskripsi: e.target.value,
                                })
                            }
                            data={deskripsi}
                        />
                    </div>
                </div>
                <div className="form-group row">
                    <label className="col-5 text-right">Keterangan</label>
                    <div className="col-5">
                        <textarea
                            className="form-control"
                            value={formAKN.keterangan}
                            onChange={(e) =>
                                setFormAKN({
                                    ...formAKN,
                                    keterangan: e.target.value,
                                })
                            }
                        ></textarea>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Instansi;
