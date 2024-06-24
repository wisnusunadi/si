import React from "react";
import { BrowserRouter as Router, Route, Routes } from "react-router-dom";
import CreatePenjualan from "../page/createPenjualan";
import EditPenjualan from "../page/edit";

const Rute = () => {
    return (
        <Router>
            <Routes>
                <Route
                    path="/penjualanv2/create"
                    element={<CreatePenjualan />}
                />
                <Route path="/penjualanv2/edit/:id" element={<EditPenjualan />} />
            </Routes>
        </Router>
    );
};

export default Rute;
