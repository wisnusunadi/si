<form action="/api/customer/update/{{$customer->id}}" method="post">
    {{ csrf_field() }}
    {{ method_field('PUT') }}
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
                                <label for="nama_produk" class="col-4 col-form-label" style="text-align:right;">Nama Customer</label>
                                <div class="col-6">
                                    <input type="text" class="form-control " placeholder="Masukkan Nama Customer" id="nama_customer" name="nama_customer" value="{{$customer->nama}}" />
                                    <div class="invalid-feedback" id="msgnama_customer">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="npwp" class="col-4 col-form-label" style="text-align:right;">NPWP</label>
                                <div class="col-5">
                                    <input type="text" class="form-control" placeholder="Masukkan NPWP" id="npwp" name="npwp" value="{{$customer->npwp}}" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="alamat" class="col-4 col-form-label" style="text-align:right;">Alamat</label>
                                <div class="col-8">
                                    <input type="text" class="form-control" placeholder="Masukkan Alamat" id="alamat" name="alamat" value="{{$customer->alamat}}" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="provinsi" class="col-4 col-form-label" style="text-align:right;">Provinsi</label>
                                <div class="col-8">
                                    <select class="select2 select-info form-control custom-select provinsi" name="provinsi" id="provinsi" style="width:100%">
                                        <option value="{{$customer->id_provinsi}}" selected>{{$customer->provinsi->nama}}</option>
                                    </select>

                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="email" class="col-4 col-form-label" style="text-align:right;">Email</label>
                                <div class="col-8">
                                    <input type="text" class="form-control" placeholder="Masukkan Email" id="email" name="email" value="{{$customer->email}}" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="telepon" class="col-4 col-form-label" style="text-align:right;">No Telp</label>
                                <div class="col-5">
                                    <input type="text" class="form-control" placeholder="Masukkan Telepon" id="telepon" name="telepon" value="{{$customer->telp}}" />

                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="telepon" class="col-4 col-form-label" style="text-align:right;">Keterangan</label>
                                <div class="col-5">
                                    <textarea class="form-control" name="keterangan" id="keterangan">{{$customer->ket}}</textarea>
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