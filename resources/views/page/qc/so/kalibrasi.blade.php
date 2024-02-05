<form method="POST" action="/api/qc/kalibrasi/store" id="form-kalibrasi-update">
    @method('POST')
    @csrf
    <div class="row d-flex justify-content-center">
        <div class="col-lg-4 col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Info Produk</h5>
                </div>
                @if($jenis == "produk")
                <div class="card-body">
                    <div class="margin">
                        <input type="hidden" name="user_idd" value="{{ Auth::user()->id }}">
                        <div><small class="text-muted">Nama Produk</small></div>
                        <div><b>{{$data->GudangBarangJadi->Produk->nama}} {{$data->GudangBarangJadi->nama}}</b></div>
                    </div>
                    <div class="margin">
                        <div><small class="text-muted">No SO</small></div>
                        <div><b>{{$data->DetailPesanan->Pesanan->so}}</b></div>
                    </div>
                </div>
                @elseif($jenis == "part")
                <div class="card-body">
                    <div class="margin">
                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                        <div><small class="text-muted">Nama Part</small></div>
                        <div><b>{{$data->Sparepart->nama}}</b></div>
                    </div>
                    <div class="margin">
                        <div><small class="text-muted">No SO</small></div>
                        <div><b>{{$data->Pesanan->so}}</b></div>
                    </div>
                    <div class="margin">
                        <div><small class="text-muted">Jumlah</small></div>
                        <div><b>{{$data->jumlah}}</b></div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        <div class="col-lg-8 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-horizontal">
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
                        <input type="hidden" name="pesanan_id" value="{{ $pesanan_id }}">
                        <input type="hidden" name="detail_pesanan_produk_id" value="{{ $produk_id }}">
                        <div class="form-group row">
                            <label for="" class="col-form-label col-5" style="text-align: right">Tanggal Masuk</label>
                            <div class="col-5">
                                <input type="date" class="form-control  col-form-label" name="tanggal_kirim" id="tanggal_kirim">
                            </div>
                        </div>
                        @if($jenis == "produk")
                        <h5>No Seri </h5>
                        <div class="form-group row">
                            <div class="table-responsive overflowy">
                                <table class="table table-striped align-center" id="listnoserikalibrasi" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>No Seri</th>
                                            <th>No Seri ID</th>
                                            <th>No Detail Produk ID</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- <tr>
                                            <td>1</td>
                                            <td>TD0201210001</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>TD0201210002</td>
                                        </tr> -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @else
                        <div class="form-group row">
                            <label for="" class="col-form-label col-5" style="text-align: right">Jumlah OK</label>
                            <div class="col-3">
                                <input type="number" class="form-control  col-form-label" name="jumlah_ok" id="jumlah_ok">
                                <div class="invalid-feedback" id="msgjumlah_ok"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-form-label col-5" style="text-align: right">Jumlah NOK</label>
                            <div class="col-3">
                                <input type="number" class="form-control  col-form-label" name="jumlah_nok" id="jumlah_nok">
                                <div class="invalid-feedback" id="msgjumlah_nok"></div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="card-footer">
                    <span class="float-left">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                    </span>
                    <span class="float-right">
                        <button type="submit" class="btn btn-info " id="btnsimpanKalibrasi" disabled>Simpan</button>
                    </span>
                </div>
            </div>
        </div>
    </div>
</form>
