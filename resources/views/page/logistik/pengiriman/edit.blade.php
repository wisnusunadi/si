<form action="/api/logistik/pengiriman/update/{{$data->id}}" method="POST" id="form-pengiriman-update">
    @method('PUT')
    @csrf
    <div class="content">
        <div class=" d-flex justify-content-center">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>Info</h5>
                                <div class="row">

                                    <div class="col-4">
                                        <div><small class="text-muted">Tujuan</small></div>
                                        @if(isset($data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog))
                                        <div><b class="smtxt">{{$data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->instansi}}</b></div>
                                        <div><b class="smtxt">{{$data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->alamat}}</b></div>
                                        <div><b class="smtxt">-</b></div>
                                        @elseif(isset($data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Spa))
                                        <div><b class="smtxt">{{$data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->nama}}</b></div>
                                        <div><b class="smtxt">{{$data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->alamat}}</b></div>
                                        <div><b class="smtxt">-</b></div>
                                        @elseif(isset($data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Spb))
                                        <div><b class="smtxt">{{$data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->nama}}</b></div>
                                        <div><b class="smtxt">{{$data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->alamat}}</b></div>
                                        <div><b class="smtxt">-</b></div>
                                        @endif
                                    </div>


                                    <div class="col-3">
                                        <div><small class="text-muted">No SO</small></div>
                                        <div><b class="smtxt">{{$data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->so}}</b></div>
                                        <div><small class="text-muted">No PO</small></div>
                                        <div><b class="smtxt">{{$data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->no_po}}</b></div>
                                    </div>

                                    <div class="col-2">
                                        <div><small class="text-muted">No Surat Jalan</small></div>
                                        <div><b class="smtxt">{{$data->nosurat}}</b></div>
                                        <div><small class="text-muted">Tanggal Kirim</small></div>
                                        <div><b class="smtxt">{{$data->tgl_kirim}}</b></div>
                                    </div>

                                    <div class="col-2">
                                        <div><small class="text-muted">Status</small></div>
                                        @if($data->status_id == "11")
                                        <div><span class="badge red-text">Draft Pengiriman</span></div>
                                        @else
                                        <div><span class="badge blue-text">Dalam Pengiriman</span></div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>Data Pengiriman</h5>
                                <div class="form-horizontal">
                                    @if($data->status_id == "11")
                                    <div class="form-group row">
                                        <label for="" class="col-form-label col-5 align-right">Pengiriman</label>
                                        <div class="col-5 col-form-label">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="pengiriman" id="pengiriman1" value="ekspedisi" @if(!empty($data->ekspedisi_id)) checked @endif/>
                                                <label class="form-check-label" for="pengiriman1">Ekspedisi</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="pengiriman" id="pengiriman2" value="nonekspedisi" @if(!empty($data->nama_pengirim)) checked @endif/>
                                                <label class="form-check-label" for="pengiriman2">Non Ekspedisi</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="card col-12 @if(empty($data->ekspedisi_id)) hide @endif" id="ekspedisi">
                                            <div class="card-body">
                                                <h6><b>Ekspedisi</b></h6>
                                                <div class="form-group row">
                                                    <label class="col-form-label col-5 align-right" for="ekspedisi_id">Jasa Pengiriman</label>
                                                    <div class="col-7">
                                                        <select class="select2 select-info form-control ekspedisi_id" name="ekspedisi_id" id="ekspedisi_id" style="width: 100%;">
                                                            @if(!empty($data->ekspedisi_id))
                                                            <option value="{{$data->Ekspedisi->id}}">{{$data->Ekspedisi->nama}}</option>
                                                            @endif
                                                        </select>
                                                        <div class="invalid-feedback" id="msgekspedisi_id"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card col-12 @if(empty($data->nama_pengirim)) hide @endif" id="nonekspedisi">
                                            <div class="card-body">
                                                <h6><b>Non Ekspedisi</b></h6>
                                                <div class="form-group row">
                                                    <label class="col-form-label col-5 align-right" for="nama_pengirim">Nama Pengirim</label>
                                                    <div class="col-7">
                                                        <input type="text" class="form-control col-form-label" name="nama_pengirim" id="nama_pengirim" value="@if(!empty($data->nama_pengirim)) {{$data->nama_pengirim}} @endif">
                                                        <div class="invalid-feedback" id="msgnama_pengirim"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @else
                                    <div class="form-group row">
                                        <label class="col-form-label col-5 align-right" for="no_resi">No Resi</label>
                                        <div class="col-6">
                                            <input type="text" class="form-control col-form-label" name="no_resi" id="no_resi">
                                            <div class="invalid-feedback" id="msgno_resi"></div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>Data Barang</h5>
                                <div class="form-group row">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table class="table table-hover align-center" style="width:100%;" id="barangtable">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nama Barang</th>
                                                        <th>Jumlah</th>
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
                </div>
                <div class="row">
                    <div class="col-6 float-left">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                    </div>
                    <div class="col-6">
                        <button type="submit" class="btn btn-warning float-right" id="btnsimpan">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>