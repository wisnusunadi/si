<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">Info</h6>
            </div>
            <div class="card-body">
                <div class="row d-flex justify-content-center">
                    <div class="col-5">
                        <div><small class="text-muted">Nama Produk</small></div>
                        <div><b>
                                @if($data->detaillogistik->DetailPesananProduk->GudangBarangJadi->nama == '')
                                {{$data->detaillogistik->DetailPesananProduk->GudangBarangJadi->produk->nama}}
                                @else
                                {{$data->detaillogistik->DetailPesananProduk->GudangBarangJadi->nama}}
                                @endif</b></div>
                    </div>
                    <div class="col-4">
                        <div><small class="text-muted">No AKD</small></div>
                        <div><b>
                                @if($data->detaillogistik->DetailPesananProduk->GudangBarangJadi->produk->no_akd != '')
                                {{ $data->detaillogistik->DetailPesananProduk->GudangBarangJadi->produk->no_akd}}
                                @endif
                            </b></div>
                    </div>
                    <div class="col-3">
                        <div><small class="text-muted">Jumlah</small></div>
                        <div><b>{{$data->detaillogistik->DetailPesananProduk->DetailPesanan->jumlah}}</b></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-7 col-md-6">
        <form action="/api/dc/so/update/{{$noseri_id}}" id="form-update-coo" method="POST">
            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">Tambah</h6>
                </div>
                <div class="card-body">
                    <div class="form-horizontal">
                        {{-- <div class="form-group row hide" id="jabatan_label">
                            <label for="" class="col-5 col-form-label" style="text-align:right;">Jabatan</label>
                            <div class="col-5">
                                <input type="text" class="form-control col-form-label" id="jabatan" name="jabatan">
                            </div>
                        </div> --}}
                        <div class="form-group" for="tgl_kirim">
                            <label for="" class="col-form-label" style="text-align:right;">Tgl Kirim</label>
                            <input type="date" class="form-control col-form-label col-lg-4 col-md-6" name="edit_tgl_kirim">
                            <div class="feedback" id="msgpart_id">
                                <small class="text-muted">*Boleh dikosongi jika tidak ada</small>
                            </div>
                        </div>
                        {{-- <div class="form-group row" id="tgl_kirim">
                            <label for="" class="col-5 col-form-label" style="text-align:right;">Tgl Kirim</label>
                            <div class="col-5">
                                <input type="date" class="form-control col-form-label" name="tgl_kirim">
                                <div class="feedback" id="msgpart_id">
                                    <small class="text-muted">*Boleh dikosongi jika tidak ada</small>
                                </div>
                            </div>
                        </div> --}}
                        <div class="form-group" for="keterangan">
                            <label for="" class="col-form-label">Tanda Terima</label>
                            <textarea class="form-control col-form-label" name="edit_keterangan"></textarea>
                        </div>

                    </div>
                </div>
                <div class="card-footer">
                    <span>
                        <button class="btn btn-danger float-left" data-dismiss="modal">Batal</button>
                    </span>
                    <span>
                        <button type="submit" class="btn btn-warning float-right" id="btnsimpan">Simpan</button>
                    </span>
                </div>
            </div>
        </form>
    </div>
    <div class="col-lg-5 col-md-6">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">List No Seri</h6>
            </div>
            <div class="card-body overflowy">
                <div class="form-group">
                    <div class="table-responsive">
                        <table class="table" style="width: 100%; text-align:center;" id="listnoseriselesai">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No Seri</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
