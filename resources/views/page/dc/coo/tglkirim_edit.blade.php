<div class="row">
    <div class="col-4">
        <div class="card">
            <div class="card-body">
                <h5 class="filter">Info</h5>
                <div class="filter">
                    <div><small class="text-muted">Nama Produk</small></div>
                    <div><b>
                            @if($data->detaillogistik->DetailPesananProduk->GudangBarangJadi->nama == '')
                            {{$data->detaillogistik->DetailPesananProduk->GudangBarangJadi->produk->nama}}
                            @else
                            {{$data->detaillogistik->DetailPesananProduk->GudangBarangJadi->nama}}
                            @endif
                        </b></div>
                </div>
                <div class="filter">
                    <div><small class="text-muted">No AKD</small></div>
                    <div><b>
                            @if($data->detaillogistik->DetailPesananProduk->GudangBarangJadi->produk->no_akd != '')
                            {{ $data->detaillogistik->DetailPesananProduk->GudangBarangJadi->produk->no_akd}}
                            @endif
                        </b></div>
                </div>
                <div class="filter">
                    <div><small class="text-muted">No Seri</small></div>
                    <div><b>{{$data->NoseriDetailPesanan->NoseriTGbj->NoseriBarangJadi->noseri}}</b></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-8">
        <form action="/api/dc/so/update/{{$data->NoseriCoo->id}}" id="form-update-coo" method="POST">
            @method('PUT')
            <div class="card">
                <div class="card-body">
                    <div class="form-horizontal">
                        <div class="form-group row">
                            <label for="" class="col-12 col-form-label">Diketahui Oleh</label>
                            <div class="col-12 col-form-label">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="diketahui" id="diketahui1" value="spa" {{ ($data->NoseriCoo->ket=="spa")? "checked" : "" }} disabled />
                                    <label class="form-check-label" for="diketahui1">PT Sinko Prima Alloy</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="diketahui" id="diketahui1" value="emiindo" {{ ($data->NoseriCoo->ket=="emiindo")? "checked" : "" }} disabled />
                                    <label class="form-check-label" for="diketahui1">PT. EMIINDO Jaya Bersama</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="diketahui" id="diketahui2" value="custom" {{ ($data->NoseriCoo->ket!="emiindo" && $data->NoseriCoo->ket!="spa")? "checked" : "" }} disabled />
                                    <label class="form-check-label" for="diketahui2">Custom</label>
                                </div>
                            </div>
                        </div>
                        @if($data->Nosericoo->nama != '')
                        <div class="form-row" id="nama_label">
                            {{-- <div class="card" style="box-shadow:none;">
                                <div class="card-body" style="background-color: #ffc107">
                                    <div class="row"> --}}
                                        <div class="form-group col-6">
                                            <label for="nama" class="col-form-label" style="text-align:right;">Nama</label>
                                            <input type="text" class="form-control col-form-label" id="nama" name="nama" value="{{$data->NoseriCoo->nama}}" disabled>
                                        </div>
                                        <div class="form-group col-6">
                                            <label for="jabatan" class="col-form-label" style="text-align:right;">Jabatan</label>
                                            <input type="text" class="form-control col-form-label" id="jabatan" name="jabatan"  value="{{$data->NoseriCoo->jabatan}}" disabled>
                                        </div>
                                    {{-- </div>
                                </div>
                            </div> --}}
                            {{-- <label for="" class="col-12 col-form-label">Nama</label>
                            <div class="col-12">
                                <input type="text" class="form-control col-form-label" id="nama" name="nama">
                            </div> --}}
                        </div>
                        {{-- <div class="form-group row " id="jabatan_label">
                            <label for="" class="col-12 col-form-label">Jabatan</label>
                            <div class="col-12">
                                <input type="text" class="form-control col-form-label" id="jabatan" name="jabatan">
                            </div>
                        </div> --}}
                        @endif
                        <div class="form-group row" id="tgl_kirim">
                            <label for="" class="col-12 col-form-label">Tgl Kirim</label>
                            <div class="col-5">
                                @if($data->NoseriCoo->tgl_kirim != '')
                                <input type="date" class="form-control col-form-label" name="tgl_kirim" value="{{$data->NoseriCoo->tgl_kirim}}">
                                @else
                                <input type="date" class="form-control col-form-label" name="tgl_kirim" value="">
                                @endif
                                <div class="feedback" id="msgpart_id">
                                    <small class="text-muted">*Boleh dikosongi jika tidak ada</small>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row" id="tgl_kirim">
                            <label for="" class="col-12 col-form-label">Tanda Terima</label>
                            <div class="col-5">
                                @if($data->NoseriCoo->catatan != '')
                                <textarea class="form-control col-form-label" name="keterangan">{{$data->NoseriCoo->catatan}}</textarea>
                                @else
                                <textarea class="form-control col-form-label" name="keterangan"></textarea>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <span>
                        <button class="btn btn-danger float-left" data-dismiss="modal">Batal</button>
                    </span>
                    <span>
                        <button type="submit" class="btn btn-warning float-right " id="btnsimpan">Ubah</button>
                    </span>
                </div>
            </div>
        </form>
    </div>
</div>
