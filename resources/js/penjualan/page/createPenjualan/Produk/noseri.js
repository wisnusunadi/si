import React, {useState} from "react";

const ModalNoSeriDsb = ({ noseri, setShowModal, submit }) => {
    // jika array noseri kosong, maka set noSeri menjadi string kosong
    // jika array noseri tidak kosong, maka set noSeri menjadi array noseri yang di join
    const [noSeri, setNoSeri] = useState(
        noseri.length === 0 ? "" : noseri.join("\n")
    );

    const textUppercase = (e) => {
        e.target.value = e.target.value.toUpperCase();
    };

    const handleSimpan = () => {
        // jika noSeri tidak kosong
        if (noSeri !== "") {
            submit(noSeri);
            closeModal();
        } else {
            swal.fire('Peringatan', 'Nomor Seri tidak boleh kosong', 'warning');
        }
    }

    const closeModal = () => {
        setShowModal(false);
        $(".modalNoSeriDsb").modal("hide");
    }

    return (
        <div
            className="modal fade modalNoSeriDsb"
            id="staticBackdrop"
            data-backdrop="static"
            data-keyboard="false"
            tabIndex="-1"
            aria-labelledby="staticBackdropLabel"
            aria-hidden="true"
        >
            <div className="modal-dialog modal-lg">
                <div className="modal-content">
                    <div className="modal-header">
                        <h5 className="modal-title" id="staticBackdropLabel">
                            Nomor Seri Distributor
                        </h5>
                        <button
                            type="button"
                            className="close"
                            onClick={closeModal}
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div className="modal-body">
                        <div className="form-group">
                            {/* text uppercase */}
                            <textarea
                                className="form-control"
                                rows="3"
                                value={noSeri}
                                onKeyUp={(e) => textUppercase(e)}
                                onChange={(e) => setNoSeri(e.target.value)}
                            ></textarea>
                        </div>
                    </div>
                    <div className="modal-footer">
                        <button
                            type="button"
                            className="btn btn-secondary"
                            onClick={closeModal}
                        >
                            Keluar
                        </button>
                        <button type="button" className="btn btn-primary"
                            onClick={handleSimpan}
                        >
                            Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default ModalNoSeriDsb;
