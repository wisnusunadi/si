import React, { useState, useEffect } from "react";

const CurrencyInput = ({ value = 0, onChange = () => {}, disabled }) => {
    const [isInputActive, setIsInputActive] = useState(false);
    const [displayValue, setDisplayValue] = useState("");

    useEffect(() => {
        if (isInputActive) {
            setDisplayValue(value.toString());
        } else {
            const formattedValue = new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR",
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            }).format(value || 0);
            setDisplayValue(formattedValue);
        }
    }, [isInputActive, value]);

    const isNumber = (evt) => {
        const charCode = evt.which ? evt.which : evt.keyCode;
        const charStr = String.fromCharCode(charCode);
        if (!/^[0-9]*$/.test(charStr) && charStr !== ".") {
            evt.preventDefault();
        }
    };

    const changeSplitInt = (evt) => {
        let value = evt.target.value;
        value = value.replace(/\./g, "");
        value = value.replace(/\,/g, ".");
        value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
        setDisplayValue(value);
    };

    const handleBlur = () => {
        setIsInputActive(false);
        const newValue = parseFloat(
            displayValue.replace(/\./g, "").replace(/\,/g, ".")
        );
        onChange(isNaN(newValue) ? 0 : newValue);
    };

    const handleFocus = () => {
        setIsInputActive(true);
    };

    const handleChange = (evt) => {
        const newValue = evt.target.value;
        setDisplayValue(newValue);
    };

    return (
        <div>
            <input
                type="text"
                className="form-control"
                value={displayValue}
                onBlur={handleBlur}
                onFocus={handleFocus}
                onKeyPress={isNumber}
                onKeyUp={changeSplitInt}
                onChange={handleChange}
                disabled={disabled ?? false}
            />
        </div>
    );
};

export default CurrencyInput;
