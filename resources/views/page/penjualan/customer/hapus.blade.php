<form data-attr="" id="form-customer-hapus">
    @method('PUT')
    <div class="row d-flex justify-content-center">
        <div class="col-11">
            <div class="card">
                <div class="card-body">
                    <!-- @if(session()->has('error'))
                    <div class="alert alert-danger alert-dismissible fade show col-12" role="alert">
                        <strong>Gagal menambahkan!</strong> Periksa
                        kembali data yang diinput
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @elseif(session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show col-12" role="alert">
                        <strong>Berhasil menambahkan data</strong>,
                        Terima kasih
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif -->
                    <div class="row">
                        <div class="col-11">
                            <div class="form-group row">
                                <label for="nama_produk" class="col-12 col-form-label" style="text-align:left;">Berikan alasan untuk menghapus data ini :</label>
                                <div class="col-12">
                                    <textarea type="text" class="form-control " placeholder="Masukkan Alasan" id="nama_customer" name="nama_customer" value="" /></textarea>
                                    <div class="invalid-feedback" id="msgnama_customer">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer"><span class="float-right filter"><button type="submit" class="btn btn-warning" id="btnsimpan">
                            Hapus
                        </button></span>
                    <span class="float-right filter"><button type="button" class="btn btn-danger" data-dismiss="modal">
                            Batal
                        </button></span>
                </div>
            </div>
        </div>
    </div>
</form>