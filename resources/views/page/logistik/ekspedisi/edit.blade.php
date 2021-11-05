<form action="" method="post">
    {{ csrf_field() }}
    <div class="row d-flex justify-content-center">
        <div class="col-11">
            <h5>Info Customer</h5>
            <div class="card">
                <div class="card-body">
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
                    <div class="row">
                        <div class="col-11">
                            <div class="form-group row">
                                <label for="nama_ekspedisi" class="col-4 col-form-label" style="text-align:right;">Nama Ekspedisi</label>
                                <div class="col-6">
                                    <input type="text" class="form-control @error('nama_ekspedisi') is-invalid @enderror" placeholder="Masukkan Nama Ekspedisi" id="nama_ekspedisi" name="nama_ekspedisi" />
                                    <div class="invalid-feedback" id="msgnama_ekspedisi">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="alamat" class="col-4 col-form-label" style="text-align:right;">Alamat</label>
                                <div class="col-8">
                                    <input type="text" class="form-control @error('alamat') is-invalid @enderror" placeholder="Masukkan Alamat" id="alamat" name="alamat" />
                                    <div class="invalid-feedback" id="msgalamat">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="email" class="col-4 col-form-label" style="text-align:right;">Email</label>
                                <div class="col-8">
                                    <input type="text" class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan Email" id="email" name="email" />
                                    <div class="invalid-feedback" id="msgemail">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="telepon" class="col-4 col-form-label" style="text-align:right;">No Telp</label>
                                <div class="col-5">
                                    <input type="text" class="form-control @error('telepon') is-invalid @enderror" value="" placeholder="Masukkan Telepon" id="telepon" name="telepon" />
                                    <div class="invalid-feedback" id="msgtelepon">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-form-label col-4" style="text-align: right">Via</label>
                                <div class="col-5 col-form-label">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="via" id="via4" value="lain" />
                                        <label class="form-check-label" for="via4">Lain</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="via" id="via1" value="darat" />
                                        <label class="form-check-label" for="via1">Darat</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="via" id="via2" value="laut" />
                                        <label class="form-check-label" for="via2">Laut</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="via" id="via3" value="" />
                                        <label class="form-check-label" for="via3">Udara</label>
                                    </div>

                                    <div class="invalid-feedback" id="msgvia">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="jurusan" class="col-4 col-form-label" style="text-align:right;">Jurusan</label>
                                <div class="col-5">
                                    <textarea class="form-control" name="jurusan" id="jurusan"></textarea>
                                    <div class="invalid-feedback" id="msgjurusan">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="keterangan" class="col-4 col-form-label" style="text-align:right;">Keterangan</label>
                                <div class="col-5">
                                    <textarea class="form-control" name="keterangan" id="keterangan"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer"><span class="float-right filter"><button type="submit" class="btn btn-warning" id="btnsimpan">
                            Simpan
                        </button></span>
                    <span class="float-right filter"><button type="button" class="btn btn-danger" data-dismiss="modal">
                            Batal
                        </button></span>
                </div>
            </div>
        </div>
    </div>
</form>