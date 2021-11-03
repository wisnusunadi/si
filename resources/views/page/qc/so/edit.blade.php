<form method="POST" action="">
    {{ method_field('PUT') }}
    {{csrf_field()}}
    <div class="form-horizontal">
        <div class="row d-flex justify-content-center">
            <div class="col-11">
                @if(session()->has('error'))
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
                @endif

                <div class="form-group row">
                    <label for="" class="col-form-label col-5" style="text-align: right">Hasil Cek</label>
                    <div class="col-5 col-form-label">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="cek" id="yes" value="ok" />
                            <label class="form-check-label" for="yes"><i class="fas fa-check-circle ok"></i> OK</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="cek" id="no" value="nok" />
                            <label class="form-check-label" for="no"><i class="fas fa-times-circle nok"></i> Tidak OK</label>
                        </div>
                    </div>
                </div>
                <h5>No Seri </h5>
                <div class="form-group row">
                    <div class="table-responsive">
                        <table class="table table-striped align-center" id="listnoseri">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No Seri</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>TD0201210001</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>TD0201210002</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-6 float-left">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                    </div>
                    <div class="col-6">
                        <button type="submit" class="btn btn-warning float-right" id="btnsimpan" disabled>Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>