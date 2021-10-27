
<div class="row">
    <div class="col-12">
        <form action="/api/penjualan_produk/create" method="post">
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
                                            <input type="text" class="form-control @error('nama_paket') is-invalid @enderror" name="nama_paket" id="nama_paket" placeholder="Masukkan Nama Paket" />
                                            <div class="invalid-feedback" id="msgnama_paket">
                                                @if($errors->has('nama_paket'))
                                                {{ $errors->first('nama_paket')}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="nama_produk" class="col-4 col-form-label" style="text-align: right">Harga</label>
                                        <div class="input-group col-5">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="text" class="form-control" name="harga" id="harga" placeholder="Masukkan Harga" />
                                            <div class="invalid-feedback" id="msgharga">
                                                @if($errors->has('harga'))
                                                {{ $errors->first('harga')}}
                                                @endif
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
                                                    <th width="15%">Nama Produk</th>
                                                    <th>Kelompok</th>
                                                    <th>Jumlah</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>
                                                        <div class="form-group row">
                                                            <div class="col-12">
                                                                <select class="select-info form-control produk_id " name="produk_id[]" id="0">
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><span class="badge" id="kelompok_produk"></span></td>
                                                    <td>
                                                        <div class="form-group d-flex justify-content-center">
                                                            <input type="number" class="form-control" name="jumlah[]" id="jumlah" style="width: 50%" />
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a id="removerow"><i class="fas fa-minus" style="color: red"></i></a>
                                                    </td>
                                                </tr>
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
                        <button class="btn btn-danger float-left">Batal</button>
                    </span>
                    <span class="float-right">
                        <button type="submit" class="btn btn-warning float-right disabled" id="btnsubmit">Simpan</button>
                    </span>
                </div>
            </div>
        </form>
    </div>
</div>