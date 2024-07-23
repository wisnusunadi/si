import React, { useEffect, useState } from "react";
import Select from "react-select";
import CurrencyInput from "../../../components/inputPrice";
import InputNumber from "../../../components/inputNumber";
import AmountInput from "../../../components/inputAmount";

import { getPart } from "../../../service/create";
import MenuList from "./menuList";
import Option from "./option";

const PartComponent = ({ formPart, setFormPart, dataCopy }) => {
    const [part, setPart] = useState([]);

    const fetchData = async () => {
        try {
            const data = await getPart();
            const addPart = data.map((item) => ({
                value: item.id,
                label: item.nama,
                ...item,
            }));

            setPart(addPart);
        } catch (error) {
            console.log(error);
        }
    };

    const addPart = () => {
        setFormPart({
            ...formPart,
            sparepart: [
                ...formPart.sparepart,
                {
                    sparepart_id: null,
                    harga: 0,
                    jumlah: 0,
                    pajak: true,
                },
            ],
        });
    };

    const handleInputPart = (value, index, key) => {
        const newFormPart = formPart.sparepart.map((item, i) => {
            if (i === index) {
                return {
                    ...item,
                    [key]: value,
                };
            }
            return item;
        });

        setFormPart({
            ...formPart,
            sparepart: newFormPart,
        });
    };

    useEffect(() => {
        if (formPart.barang.includes("sparepart")) {
            if (dataCopy?.sparepart !== undefined) {
                setFormPart({
                    ...formPart,
                    sparepart: dataCopy.sparepart,
                });
            } else {
                const sparepart = formPart.sparepart ?? [
                    {
                        sparepart_id: null,
                        harga: 0,
                        jumlah: 0,
                        pajak: true,
                    },
                ];
                setFormPart({
                    ...formPart,
                    sparepart,
                });
            }
        } else {
            setFormPart((prevFormPart) => {
                const { part, ...newFormPart } = prevFormPart;
                return newFormPart;
            });
        }
    }, [formPart.barang]);

    useEffect(() => {
        fetchData();
    }, []);

    return (
        <>
            <h4>Data Part</h4>
            <div className="card">
                <div className="card-body overflow-auto">
                    <div className="d-flex flex-row-reverse bd-highlight">
                        <div className="p-2 bd-highlight">
                            <button
                                onClick={addPart}
                                className="btn btn-primary"
                            >
                                <i className="fas fa-plus"></i> Part
                            </button>
                        </div>
                    </div>
                        <table className="table text-center">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th
                                        style={{
                                            width: "40%",
                                        }}
                                    >
                                        Nama Part
                                    </th>
                                    <th>Jumlah</th>
                                    <th>Harga</th>
                                    <th>Subtotal</th>
                                    <th>Pajak</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {(formPart.sparepart ?? []).map(
                                    (item, index) => (
                                        <tr key={index}>
                                            <td>{index + 1}</td>
                                            <td>
                                                <Select
                                                    value={part.find(
                                                        (part) =>
                                                            part.value ===
                                                            item.sparepart_id
                                                    )}
                                                    options={part}
                                                    placeholder="Pilih Part"
                                                    onChange={(e) =>
                                                        handleInputPart(
                                                            e.value,
                                                            index,
                                                            "sparepart_id"
                                                        )
                                                    }
                                                    styles={{
                                                        option: (
                                                            provided,
                                                            state
                                                        ) => ({
                                                            ...provided,
                                                            textAlign: "left",
                                                        }),
                                                    }}
                                                    components={{ Option }}
                                                />
                                            </td>
                                            <td>
                                                <AmountInput
                                                    value={item.jumlah}
                                                    onChange={(e) =>
                                                        handleInputPart(
                                                            e.target.value,
                                                            index,
                                                            "jumlah"
                                                        )
                                                    }
                                                />
                                            </td>
                                            <td>
                                                <CurrencyInput
                                                    value={item.harga}
                                                    onChange={(e) =>
                                                        handleInputPart(
                                                            e,
                                                            index,
                                                            "harga"
                                                        )
                                                    }
                                                />
                                            </td>
                                            <td>
                                                <CurrencyInput
                                                    disabled={true}
                                                    value={
                                                        item.jumlah * item.harga
                                                    }
                                                />
                                            </td>
                                            <td>
                                                <div className="custom-control custom-switch">
                                                    <input
                                                        type="checkbox"
                                                        className="custom-control-input"
                                                        id={`pajakPart-${index}`}
                                                        checked={item.pajak}
                                                        onChange={(e) =>
                                                            handleInputPart(
                                                                e.target
                                                                    .checked,
                                                                index,
                                                                "pajak"
                                                            )
                                                        }
                                                    />
                                                    <label
                                                        className="custom-control-label"
                                                        htmlFor={`pajakPart-${index}`}
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
                                                        const newFormPart =
                                                            formPart.sparepart.filter(
                                                                (item, i) =>
                                                                    i !== index
                                                            );

                                                        setFormPart({
                                                            ...formPart,
                                                            sparepart:
                                                                newFormPart,
                                                        });
                                                    }}
                                                ></i>
                                            </td>
                                        </tr>
                                    )
                                )}
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
                                                (
                                                    formPart.sparepart ?? []
                                                ).reduce(
                                                    (acc, item) =>
                                                        acc +
                                                        item.jumlah *
                                                            item.harga,
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

export default PartComponent;
