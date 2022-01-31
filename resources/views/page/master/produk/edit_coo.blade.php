<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Info Produk</h6>
                    </div>
                    <div class="card-body">
                        <div class="row align-md">
                            <div class="col-lg-12 col-md-4 margin-info">
                                <div class="text-muted">Jenis Produk</div>
                                <div class="margin-info">
                                    <b>{{$data->product->nama}}</b>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-4 margin-info">
                                <div class="text-muted">Tipe</div>
                                <div class="margin-info">
                                    <b>{{$data->nama}}</b>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-4 margin-info">
                                <div class="text-muted">Kelompok Produk</div>
                                <div class="margin-info">
                                    @if($data->KelompokProduk->nama == 'Alat Kesehatan')
                                        <span class="badge blue-text">
                                    @elseif($data->KelompokProduk->nama == 'Water Treatment')
                                        <span class="badge orange-text">
                                    @elseif($data->KelompokProduk->nama == 'Aksesoris')
                                        <span class="badge purple-text">
                                    @elseif($data->KelompokProduk->nama == 'Lain Lain')
                                        <span class="badge red-text">
                                    @elseif($data->KelompokProduk->nama == 'Sparepart')
                                        <span class="badge green-text">
                                    @endif
                                    {{$data->KelompokProduk->nama}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-12">
                <form method="POST" action="/api/master/produk/update_coo/{{$data->id}}" id="form-update">
                    @method('PUT')
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title">Ubah Data</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-form-label col-lg-4 col-md-12 labelket" for="nama_coo">Nama COO</label>
                                <div class="col-lg-7 col-md-12">
                                    <input type="text" class="form-control col-form-label" name="nama_coo" id="nama_coo" value="{{$data->nama_coo}}">
                                    <div class="invalid-feedback" id="msgnama_coo"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-lg-4 col-md-12 labelket" for="no_akd">No AKD</label>
                                <div class="col-lg-4 col-md-7">
                                    <input type="number" class="form-control col-form-label" name="no_akd" id="no_akd" value="{{$data->no_akd}}">
                                    <div class="invalid-feedback" id="msgno_akd"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-form-label col-lg-4 col-md-12 labelket">COO</label>
                                <div class="col-lg-7 col-md-12 col-form-label">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="coo" id="coo1" value="1" @if($data->coo == '1') checked @endif/>
                                        <label class="form-check-label" for="coo1">Produk Utama</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="coo" id="coo2" value="0" @if($data->coo != '1') checked @endif/>
                                        <label class="form-check-label" for="coo2">Bukan Produk Utama</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <span><button class="btn btn-danger" type="button" id="btncancel" data-dismiss="modal">Batal</button></span>
                            <span class="float-right"><button class="btn btn-warning" type="submit" id="btnsimpan">Simpan</button></span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
