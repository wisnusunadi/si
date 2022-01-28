<form data-attr="{{ route('penjualan.customer.update', $customer->id) }}" data-id="{{$customer->id}}" method="POST" id="form-customer-update">
    @method('PUT')
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
                                    <input type="text" class="form-control " placeholder="Masukkan Nama Customer" id="nama_customer" name="nama_customer" value="{{old('nama_customer', $customer->nama)}}" />
                                    <div class="invalid-feedback" id="msgnama_customer">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="npwp" class="col-4 col-form-label" style="text-align:right;">NPWP</label>
                                <div class="col-5">
                                    <input type="text" class="form-control" placeholder="Masukkan NPWP" id="npwp" name="npwp" value="{{old('npwp', $customer->npwp)}}" />
                                    <div class="invalid-feedback" id="msg_npwp">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="ktp" class="col-4 col-form-label" style="text-align:right;">KTP</label>
                                <div class="col-8">
                                    <input type="text" class="form-control" placeholder="Masukkan KTP" id="ktp" name="ktp" value="{{old('ktp', $customer->ktp)}}" />
                                    <div class="invalid-feedback" id="msgktp">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="alamat" class="col-4 col-form-label" style="text-align:right;">Alamat</label>
                                <div class="col-8">
                                    <input type="text" class="form-control" placeholder="Masukkan Alamat" id="alamat" name="alamat" value="{{old('alamat', $customer->alamat)}}" />
                                    <div class="invalid-feedback" id="msgalamat">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="provinsi" class="col-4 col-form-label" style="text-align:right;">Provinsi</label>
                                <div class="col-8">
                                    <select class="select2 select-info form-control custom-select provinsi" name="provinsi" id="provinsi" style="width:100%">
                                        <option value="{{$customer->id_provinsi}}" selected>{{$customer->provinsi->nama}}</option>
                                    </select>
                                    <div class="invalid-feedback" id="msgprovinsi">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="email" class="col-4 col-form-label" style="text-align:right;">Email</label>
                                <div class="col-8">
                                    <input type="text" class="form-control" placeholder="Masukkan Email" id="email" name="email" value="{{old('email', $customer->email)}}" />
                                    <div class="invalid-feedback" id="msgemail">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="telepon" class="col-4 col-form-label" style="text-align:right;">No Telp</label>
                                <div class="col-5">
                                    <input type="text" class="form-control" placeholder="Masukkan Telepon" id="telepon" name="telepon" value="{{old('telepon', $customer->telp)}}" />
                                    <div class="invalid-feedback" id="msgtelepon">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="telepon" class="col-4 col-form-label" style="text-align:right;">PIC</label>
                                <div class="col-5">
                                    <input type="text" class="form-control" placeholder="Masukkan Nama PIC" id="pic" name="pic" value="{{old('pic', $customer->pic)}}" />
                                    <div class="invalid-feedback" id="msgbatas">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="batas" class="col-form-label col-4" style="text-align: right">Batas Pembayaran</label>
                                <div class="col-4 input-group">
                                    <input type="text" class="form-control" name="batas" id="batas" aria-label="batas" placeholder="Batas hari pembayaran" value="{{old('batas', $customer->batas)}}" />
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="ket_no_paket">Hari</span>
                                    </div>
                                    <div class="invalid-feedback" id="msgno_batas">
                                    </div>
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