<div class="row">
    <div class="col-12">
        @if(session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show col-12" role="alert">
            <strong>Gagal mengubah!</strong> Periksa
            kembali data yang diinput
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @elseif(session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show col-12" role="alert">
            <strong>Berhasil mengubah data</strong>,
            Terima kasih
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif
        @foreach($penjualanproduk as $p)
        <form action="" method="post">
            <div class="row d-flex justify-content-center">

                <div class="col-11">
                    <h5>Info Umum Paket</h5>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group row">
                                        <label for="nama_produk" class="col-4 col-form-label" style="text-align: right">Nama Paket</label>
                                        <div class="col-6">
                                            <input type="text" class="form-control" name="nama_paket" id="nama_paket" placeholder="Masukkan Nama Paket" value="{{$p->nama}}" />
                                            <div class="invalid-feedback" id="msgnama_paket">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="nama_produk" class="col-4 col-form-label" style="text-align: right">Harga</label>
                                        <div class="input-group col-5">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="text" class="form-control" name="harga" id="harga" placeholder="Masukkan Harga" value="{{ number_format($p->harga)}}" />
                                            <div class="invalid-feedback" id="msgharga">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row d-flex justify-content-center">
                <div class="col-11">
                    <h5>Detail Produk Paket</h5>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table" style="text-align: center;" id="createtable">
                                            <thead>
                                                <tr>
                                                    <th colspan="5">
                                                        <button type="button" class="btn btn-primary float-right" id="addrow">
                                                            <i class="fas fa-plus"></i>
                                                            Produk
                                                        </button>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>No</th>
                                                    <th width="50%">Nama Produk</th>
                                                    <th>Kelompok</th>
                                                    <th width="20%">Jumlah</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($p->produk as $s)
                                                <tr>
                                                    <td>{{$loop->iteration}}</td>
                                                    <td>
                                                        <div class="form-group row">
                                                            <div class="col-12">
                                                                <select class="select-info select2 form-control produk_id" name="produk_id[]" id="produk_id" style="width:100%">
                                                                    <option value=""></option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><span class="badge" id="kelompok_produk"></span>{{$s->kelompokproduk->nama}}</td>
                                                    <td>
                                                        <div class="form-group d-flex justify-content-center">

                                                            <input type="text" class="form-control" name="jumlah[]" id="jumlah{{$loop->iteration}}" style="width: 50%" value="{{$s->pivot->jumlah}}" />

                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a id="removerow"><i class="fas fa-minus" style="color: red"></i></a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row d-flex justify-content-center">
                <div class="col-11">
                    <span>
                        <button class="btn btn-danger float-left" data-dismiss="modal">Batal</button>
                    </span>
                    <span class="float-right">
                        <button type="submit" class="btn btn-warning float-right disabled" id="btnsimpan">Simpan</button>
                    </span>
                </div>
            </div>
        </form>
    </div>
</div>