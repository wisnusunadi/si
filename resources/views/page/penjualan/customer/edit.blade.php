<form data-attr="{{ route('penjualan.customer.update', $customer->id) }}" data-id="{{$customer->id}}" method="POST" id="form-customer-update">
    @method('PUT')
    <div class="row d-flex justify-content-center">
        <div class="col-11">
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
                    <div class="row d-flex justify-content-center">
                        <div class="col-11">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card removeboxshadow">
                                        <div class="card-body">
                                            <div class="form-group row">
                                                <label for="nama_produk" class="col-lg-4 col-md-12 col-form-label labelket">Nama Customer</label>
                                                <div class="col-lg-6 col-md-12">
                                                    <input type="text" class="form-control " placeholder="Masukkan Nama Customer" id="nama_customer" name="nama_customer" value="{{old('nama_customer', $customer->nama)}}" />
                                                    <div class="invalid-feedback" id="msgnama_customer">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="telepon" class="col-lg-4 col-md-12 col-form-label labelket">No Telp</label>
                                                <div class="col-lg-5 col-md-12">
                                                    <input type="text" class="form-control" placeholder="Masukkan Telepon" id="telepon" name="telepon" value="{{old('telepon', $customer->telp)}}" />
                                                    <div class="invalid-feedback" id="msgtelepon">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="email" class="col-4 col-form-label labelket">Email</label>
                                                <div class="col-lg-8 col-md-12">
                                                    <input type="text" class="form-control" placeholder="Masukkan Email" id="email" name="email" value="{{old('email', $customer->email)}}" />
                                                    <div class="invalid-feedback" id="msgemail">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="card removeboxshadow">
                                        <div class="card-header"></div>
                                        <div class="card-body">
                                            <div class="form-group row">
                                                <label for="alamat" class="col-lg-4 col-md-12 col-form-label labelket">Alamat</label>
                                                <div class="col-lg-8 col-md-12">
                                                    <textarea  class="form-control" placeholder="Masukkan Alamat" id="alamat" name="alamat">{{old('alamat', $customer->alamat)}}</textarea>
                                                    <div class="invalid-feedback" id="msgalamat">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="provinsi" class="col-4 col-form-label labelket">Provinsi</label>
                                                <div class="col-lg-8 col-md-12">
                                                    <select class="select2 select-info form-control custom-select provinsi" name="provinsi" id="provinsi" style="width:100%">
                                                        <option value="{{$customer->id_provinsi}}" selected>{{$customer->provinsi->nama}}</option>
                                                    </select>
                                                    <div class="invalid-feedback" id="msgprovinsi">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="card removeboxshadow">
                                        <div class="card-header"></div>
                                        <div class="card-body">
                                            <div class="form-group row">
                                                <label for="telepon" class="col-lg-4 col-md-12 col-form-label labelket">PIC</label>
                                                <div class="col-lg-5 col-md-12">
                                                    <input type="text" class="form-control" placeholder="Masukkan Nama PIC" id="pic" name="pic" value="{{old('pic', $customer->pic)}}" />
                                                    <div class="invalid-feedback" id="msgbatas">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="ktp" class="col-lg-4 col-md-12 col-form-label labelket">KTP</label>
                                                <div class="col-lg-8 col-md-12">
                                                    <input type="text" class="form-control" placeholder="Masukkan KTP" id="ktp" name="ktp" value="{{old('ktp', $customer->ktp)}}" />
                                                    <div class="invalid-feedback" id="msgktp">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="npwp" class="col-lg-4 col-md-12 col-form-label labelket">NPWP</label>
                                                <div class="col-lg-5 col-md-12">
                                                    <input type="text" class="form-control" placeholder="Masukkan NPWP" id="npwp" name="npwp" value="{{old('npwp', $customer->npwp)}}" />
                                                    <div class="invalid-feedback" id="msg_npwp">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="batas" class="col-form-label col-lg-4 col-md-12 labelket">Batas Pembayaran</label>
                                                <div class="col-lg-4 col-md-12 input-group">
                                                    <input type="text" class="form-control" name="batas" id="batas" aria-label="batas" placeholder="Batas hari pembayaran" value="{{old('batas', $customer->batas)}}" />
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="ket_no_paket">Hari</span>
                                                    </div>
                                                    <div class="invalid-feedback" id="msgno_batas">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="telepon" class="col-lg-4 col-md-12 col-form-label labelket">Keterangan</label>
                                                <div class="col-lg-5 col-md-12">
                                                    <textarea class="form-control" name="keterangan" id="keterangan">{{$customer->ket}}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer"><span class="float-right filter"><button type="submit" class="btn btn-warning" id="btnsimpan">
                            Simpan
                        </button></span>
                    <span class="float-left filter"><button type="button" class="btn btn-danger" data-dismiss="modal">
                            Batal
                        </button></span>
                </div>
            </div>
        </div>
    </div>
</form>
