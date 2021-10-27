<form action="">
    <div class="row d-flex justify-content-center">
        <div class="col-11">
            <h5>Info Customer</h5>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-11">
                            <div class="form-group row">
                                @if(session()->has('error') || count($errors) > 0 )
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
                            </div>
                            <div class="form-group row">
                                <label for="nama_produk" class="col-4 col-form-label" style="text-align:right;">Nama Customer</label>
                                <div class="col-6">
                                    <input type="text" class="form-control @error('nama_customer') is-invalid @enderror" placeholder="Masukkan Nama Customer" id="nama_customer" name="nama_customer" />
                                    <div class="invalid-feedback" id="msgnama_customer">
                                        @if($errors->has('nama_customer'))
                                        {{ $errors->first('nama_customer')}}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="npwp" class="col-4 col-form-label" style="text-align:right;">NPWP</label>
                                <div class="col-5">
                                    <input type="text" class="form-control @error('npwp') is-invalid @enderror" value="" placeholder="Masukkan NPWP" id="npwp" name="npwp" />
                                    <div class="invalid-feedback" id="msgnpwp">
                                        @if($errors->has('npwp'))
                                        {{ $errors->first('npwp')}}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="alamat" class="col-4 col-form-label" style="text-align:right;">Alamat</label>
                                <div class="col-8">
                                    <input type="text" class="form-control @error('alamat') is-invalid @enderror" placeholder="Masukkan Alamat" id="alamat" name="alamat" />
                                    <div class="invalid-feedback" id="msgalamat">
                                        @if($errors->has('alamat'))
                                        {{ $errors->first('alamat')}}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="provinsi" class="col-4 col-form-label" style="text-align:right;">provinsi</label>
                                <div class="col-8">
                                    <select class="select2 select-info form-control custom-select" name="provinsi" id="provinsi">

                                    </select>
                                    <div class="invalid-feedback" id="msgprovinsi">
                                        @if($errors->has('provinsi'))
                                        {{ $errors->first('provinsi')}}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="email" class="col-4 col-form-label" style="text-align:right;">Email</label>
                                <div class="col-8">
                                    <input type="text" class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan Email" id="email" name="email" />
                                    <div class="invalid-feedback" id="msgemail">
                                        @if($errors->has('email'))
                                        {{ $errors->first('email')}}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="telepon" class="col-4 col-form-label" style="text-align:right;">No Telp</label>
                                <div class="col-5">
                                    <input type="text" class="form-control @error('telepon') is-invalid @enderror" value="" placeholder="Masukkan Telepon" id="telepon" name="telepon" />
                                    <div class="invalid-feedback" id="msgtelepon">
                                        @if($errors->has('telepon'))
                                        {{ $errors->first('telepon')}}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="telepon" class="col-4 col-form-label" style="text-align:right;">Keterangan</label>
                                <div class="col-5">
                                    <textarea class="form-control" name="keterangan" id="keterangan"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer"><span class="float-right filter"><button type="submit" class="btn btn-warning" disabled>
                            Simpan
                        </button></span>
                    <span class="float-right filter"><button type="button" class="btn btn-danger" data-dismiss="modal" id="btnsimpan">
                            Batal
                        </button></span>
                </div>
            </div>
        </div>
    </div>
</form>