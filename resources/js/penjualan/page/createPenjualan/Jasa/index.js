import React, { useEffect, useState } from "react";
import Select from "react-select";
import CurrencyInput from "../../../components/inputPrice";
import { getJasa } from "../../../service/create";
import { data } from "jquery";

const JasaComponent = ({ formJasa, setFormJasa, dataCopy }) => {
    const [jasa, setJasa] = useState([]);

    const addJasa = () => {
        const jasa = formJasa.jasa ?? [
            {
                jasa_id: null,
                harga: 0,
                pajak: true,
            },
        ];
        setFormJasa({
            ...formJasa,
            jasa
        });
    };

    const handleInputJasa = (value, index, key) => {
        const newFormJasa = formJasa.jasa.map((item, i) => {
            if (i === index) {
                return {
                    ...item,
                    [key]: value,
                };
            }
            return item;
        });

        setFormJasa({
            ...formJasa,
            jasa: newFormJasa,
        });
    };

    useEffect(() => {
        const fetchData = async () => {
            const data = await getJasa();
            const addJasa = data.map((item) => ({
                value: item.id,
                label: item.nama,
                ...item,
            }));

            setJasa(addJasa);
        };

        fetchData();
    }, []);

    useEffect(() => {
        if (formJasa.barang.includes("jasa")) {
            if (dataCopy?.jasa) {
                setFormJasa({
                    ...formJasa,
                    jasa: dataCopy.jasa
                });
            } else {
                setFormJasa({
                    ...formJasa,
                    jasa: [
                        {
                            jasa_id: null,
                            harga: 0,
                            pajak: true,
                        },
                    ],
                });
            }
        } else {
            setFormJasa((prevFormJasa) => {
                const { jasa, ...newFormJasa } = prevFormJasa;
                return newFormJasa;
            });
        }
    }, [formJasa.barang]);

    return (
        <>
            <h4>Data Jasa</h4>
            <div className="card">
                <div className="card-body overflow-auto">
                    <div className="d-flex flex-row-reverse bd-highlight">
                        <div className="p-2 bd-highlight">
                            <button
                                onClick={addJasa}
                                className="btn btn-primary"
                            >
                                <i className="fas fa-plus"></i> Jasa
                            </button>
                        </div>
                    </div>

                    <table className="table text-center">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th
                                    style={{
                                        width: "30%",
                                    }}
                                >
                                    Nama Jasa
                                </th>
                                <th>Harga</th>
                                <th>Subtotal</th>
                                <th>Pajak</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {(formJasa.jasa ?? []).map((item, index) => (
                                <tr key={index}>
                                    <td>{index + 1}</td>
                                    <td>
                                        <Select
                                            value={jasa.find(
                                                (j) => j.value === item.jasa_id
                                            )}
                                            onChange={(e) =>
                                                handleInputJasa(
                                                    e.value,
                                                    index,
                                                    "jasa_id"
                                                )
                                            }
                                            placeholder="Pilih Jasa"
                                            options={jasa}
                                            styles={{
                                                option: (provided, state) => ({
                                                    ...provided,
                                                    textAlign: "left",
                                                }),
                                            }}
                                        />
                                    </td>
                                    <td>
                                        <CurrencyInput
                                            value={item.harga}
                                            onChange={(e) =>
                                                handleInputJasa(
                                                    e,
                                                    index,
                                                    "harga"
                                                )
                                            }
                                        />
                                    </td>
                                    <td>
                                        <CurrencyInput
                                            value={item.harga}
                                            disabled={true}
                                        />
                                    </td>
                                    <td>
                                        <div className="custom-control custom-switch">
                                            <input
                                                type="checkbox"
                                                className="custom-control-input"
                                                id={`pajakJasa-${index}`}
                                                checked={item.pajak}
                                                onChange={(e) =>
                                                    handleInputJasa(
                                                        e.target.checked,
                                                        index,
                                                        "pajak"
                                                    )
                                                }
                                            />
                                            <label
                                                className="custom-control-label"
                                                htmlFor={`pajakJasa-${index}`}
                                            >
                                                {item.pajak === true
                                                    ? "PPN"
                                                    : "Non PPN"}
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <i
                                            className="fas fa-minus text-danger"
                                            onClick={() => {
                                                const newJasa =
                                                    formJasa.jasa.filter(
                                                        (j, i) => i !== index
                                                    );

                                                setFormJasa({
                                                    ...formJasa,
                                                    jasa: newJasa,
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
                                            (formJasa.jasa ?? []).reduce(
                                                (acc, item) =>
                                                    acc + parseInt(item.harga),
                                                0
                                            )
                                        )}
                                    </b>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </>
    );
};

export default JasaComponent;
