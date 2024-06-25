import React, { useEffect, useState } from "react";

const AmountInput = ({ value, onChange, className, disabled }) => {
    const [formattedValue, setFormattedValue] = useState(value);

    const isNumber = (value) => /^[0-9]*$/.test(value);

    const formatNumber = (num) => {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    };

    const parseNumber = (value) => {
        return value.replace(/\./g, "");
    };

    const handleChange = (e) => {
        const newValue = e.target.value;
        if (isNumber(parseNumber(newValue))) {
            const parsedValue = parseInt(parseNumber(newValue), 10) || "";
            setFormattedValue(formatNumber(parsedValue));
            onChange({
                target: {
                    value: parsedValue,
                },
            });
        }
    };

    const handleBlur = () => {
        const parsedValue = parseInt(parseNumber(formattedValue), 10) || "";
        setFormattedValue(formatNumber(parsedValue));
        onChange({
            target: {
                value: parsedValue,
            },
        });
    };

    useEffect(() => {
        setFormattedValue(formatNumber(value));
    }, [value]);

    return (
        <input
            type="text"
            className={className ?? "form-control"}
            value={formattedValue}
            onChange={handleChange}
            onBlur={handleBlur}
            disabled={disabled ?? false}
        />
    );
};

export default AmountInput;
