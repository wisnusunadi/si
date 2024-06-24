import React from "react";

const InputText = ({ type, value, onChange, className, disabled }) => {
    return (
        <input
            type={type ?? "text"}
            className={className ?? "form-control"}
            value={value ?? ""}
            onChange={onChange}
            disabled={disabled ?? false}
        />
    );
}

export default InputText;