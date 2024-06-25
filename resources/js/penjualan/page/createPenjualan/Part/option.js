import React from "react";
import './Option.css';
import cx from "classnames";

const Option = ({ children, isSelected, innerProps }) => (
    <div
        className={cx("react-select__option", {
            "react-select__option_selected": isSelected,
        })}
        id={innerProps.id}
        tabIndex={innerProps.tabIndex}
        onClick={innerProps.onClick}
        onMouseMove={innerProps.onMouseMove} // Ensure hover effects work
        onMouseOver={innerProps.onMouseOver} // Ensure hover effects work
    >
        {children}
    </div>
);

export default Option;
