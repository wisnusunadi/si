import React, { useState, useEffect, useRef } from "react";
import "../css/autocomplete.css"; // Import a CSS file for styles

const AutoComplete = ({ type, value, onChange, data }) => {
    const [filteredData, setFilteredData] = useState([]);
    const [showSuggestions, setShowSuggestions] = useState(false);
    const wrapperRef = useRef(null);

    const handleChange = (e) => {
        const value = e.target.value;
        const filtered = data.filter((item) =>
            item.toLowerCase().includes(value.toLowerCase())
        );
        setFilteredData(filtered);
        setShowSuggestions(true);
        onChange(e);
    };

    const handleSuggestionClick = (suggestion) => {
        onChange({ target: { value: suggestion } });
        setShowSuggestions(false);
    };

    const handleClickOutside = (event) => {
        if (wrapperRef.current && !wrapperRef.current.contains(event.target)) {
            setShowSuggestions(false);
        }
    };

    useEffect(() => {
        document.addEventListener("mousedown", handleClickOutside);
        return () => {
            document.removeEventListener("mousedown", handleClickOutside);
        };
    }, []);

    return (
        <div className="autocomplete-wrapper" ref={wrapperRef}>
            {type === "textarea" ? (
                <textarea
                    className="form-control"
                    value={value}
                    onChange={handleChange}
                ></textarea>
            ) : (
                <input
                    type="text"
                    className="form-control"
                    value={value}
                    onChange={handleChange}
                />
            )}
            {showSuggestions && filteredData.length > 0 && (
                <div className="autocomplete-suggestions">
                    {filteredData.map((item, index) => (
                        <div
                            key={index}
                            className="suggestion"
                            onClick={() => handleSuggestionClick(item)}
                        >
                            {item}
                        </div>
                    ))}
                </div>
            )}
        </div>
    );
};

export default AutoComplete;
