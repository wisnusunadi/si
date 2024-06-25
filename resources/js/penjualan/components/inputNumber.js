import React, { useEffect } from "react";

const InputNumber = ({ value, onChange, className, disabled }) => {
    // Function to check if a value is a number
    const isNumber = (value) => {
        return /^[0-9]*$/.test(value);
    };

    // Handle key press to allow only numbers
    const handleKeyPress = (e) => {
        const charCode = e.which ? e.which : e.keyCode;
        const charStr = String.fromCharCode(charCode);
        if (!isNumber(charStr)) {
            e.preventDefault();
        }
    };

    // Ensure the value is a number when the component mounts or updates
    useEffect(() => {
        if (!isNumber(value.toString())) {
            onChange({ target: { value: "" } });
        }
    }, [value, onChange]);

    return (
        <input
            type="text"
            className={className ?? "form-control"}
            value={value}
            onChange={(e) => {
                const newValue = e.target.value;
                if (isNumber(newValue)) {
                    onChange({
                        target: {
                            value:
                                newValue === "" ? "" : parseInt(newValue, 10),
                        },
                    });
                }
            }}
            onKeyPress={handleKeyPress}
            disabled={disabled ?? false}
        />
    );
};

export default InputNumber;
