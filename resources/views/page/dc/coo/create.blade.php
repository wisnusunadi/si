
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
                            {{$data->nama}}
                            </b></div>
                    </div>
                    <div class="col-4">
                        <div><small class="text-muted">No AKD</small></div>
                        <div><b>
                                {{-- @if($data->detaillogistik->DetailPesananProduk->GudangBarangJadi->produk->no_akd != '' || $data->detaillogistik->DetailPesananProduk->GudangBarangJadi->produk->no_akd != NULL)
                                {{ $data->detaillogistik->DetailPesananProduk->GudangBarangJadi->produk->no_akd}}
                                @endif --}}
                                {{$data->no_akd != NULL ? $data->no_akd : '-' }}
                            </b></div>
                    </div>
                    <div class="col-3">
                        <div><small class="text-muted">Jumlah</small></div>
                        <div><b>{{$jumlah}}</b></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-7 col-md-6">
        <form action="/api/dc/so/store" id="form-create-coo" method="POST">
            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
            <input type="hidden" name="id" value="{{$noseri_id}}">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">Tambah</h6>
                </div>
                <div class="card-body">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label for="" class="col-form-label">Diketahui Oleh</label>
                            <div class="col-form-label d-flex justify-content-between">
                                <div class="form-check form-check-inline ">
                                    <input class="form-check-input" type="radio" name="diketahui" id="diketahui1" value="spa" />
                                    <label class="form-check-label" for="diketahui1">PT Sinko Prima Alloy</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="diketahui" id="diketahui1" value="emiindo" />
                                    <label class="form-check-label" for="diketahui1">PT. EMIINDO Jaya Bersama</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="diketahui" id="diketahui2" value="custom" />
                                    <label class="form-check-label" for="diketahui2">Custom</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-row hide" id="nama_label">
                            {{-- <div class="card" style="box-shadow:none;">
                                <div class="card-body" style="background-color: #17a2b8">
                                    <div class="row"> --}}
                                        <div class="form-group col-6">
                                            <label for="" class="col-form-label" style="text-align:right;">Nama</label>
                                            <input type="text" class="form-control col-form-label" id="nama" name="nama">
                                        </div>
                                        <div class="form-group col-6">
                                            <label for="" class="col-form-label" style="text-align:right;">Jabatan</label>
                                            <input type="text" class="form-control col-form-label" id="jabatan" name="jabatan">
                                        </div>
                                    {{-- </div> --}}
                                {{-- </div>
                            </div> --}}
                            {{-- <label for="" class="col-5 col-form-label" style="text-align:right;">Nama</label>
                            <div class="col-5">
                                <input type="text" class="form-control col-form-label" id="nama" name="nama">
                            </div> --}}
                        </div>
                        {{-- <div class="form-group row hide" id="jabatan_label">
                            <label for="" class="col-5 col-form-label" style="text-align:right;">Jabatan</label>
                            <div class="col-5">
                                <input type="text" class="form-control col-form-label" id="jabatan" name="jabatan">
                            </div>
                        </div> --}}
                        <div class="form-group" for="tgl_kirim">
                            <label for="" class="col-form-label" style="text-align:right;">Tgl Kirim</label>
                            <input type="date" class="form-control col-form-label col-lg-4 col-md-6" name="tgl_kirim">
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
                            <textarea class="form-control col-form-label" name="keterangan"></textarea>
                        </div>

                    </div>
                </div>
                <div class="card-footer">
                    <span>
                        <button class="btn btn-danger float-left" data-dismiss="modal">Batal</button>
                    </span>
                    <span>
                        <button type="submit" class="btn btn-info float-right disabled" id="btnsimpan">Simpan</button>
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
                        <table class="table" style="width: 100%; text-align:center;" id="listnoseribelum">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No Seri</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($series as  $d)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$d}}</td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>