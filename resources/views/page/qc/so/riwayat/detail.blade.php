<div class="content">
    <div class="row d-flex justify-content">
        <div class="col-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Info Customer</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 align-center">
                            @if(isset($res->Pesanan->Ekatalog))
                            <!-- <div id="profileImage" class="center margin-all"></div> -->
                            <div class="margin">
                                <h6><b>{{$res->Pesanan->Ekatalog->Customer->nama}}</b></h6>
                            </div>
                            <div class="margin"><b>{{$res->Pesanan->Ekatalog->satuan}}</b></div>
                            <div class="margin"><b>{{$res->Pesanan->Ekatalog->alamat}}</b></div>
                            <div class="margin"><b>{{$res->Pesanan->Ekatalog->Provinsi->nama}}</b></div>
                            @elseif(isset($res->Pesanan->Spa))
                            <!-- <div id="profileImage" class="center margin-all"></div> -->
                            <div class="margin">
                                <h6><b>{{$res->Pesanan->Spa->Customer->nama}}</b></h6>
                            </div>
                            <div class="margin">{{$res->Pesanan->Spa->Customer->alamat}}</div>
                            <div class="margin">{{$res->Pesanan->Spa->Customer->Provinsi->nama}}</div>
                            @elseif(isset($res->Pesanan->Spb))
                            <!-- <div id="profileImage" class="center margin-all"></div> -->
                            <div class="margin">
                                <h6><b>{{$res->Pesanan->Spb->Customer->nama}}</b></h6>
                            </div>
                            <div class="margin">{{$res->Pesanan->Spb->Customer->alamat}}</div>
                            <div class="margin">{{$res->Pesanan->Spb->Customer->Provinsi->nama}}</div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Info Produk</h5>
                </div>
                <div class="card-body">
                    <div class="margin">
                        <div><small class="text-muted">Nama Produk</small></div>
                        <div><b>{{$res->PenjualanProduk->nama}}</b></div>
                    </div>
                    @if(count($res->DetailPesananProduk) <= 1) @if($res->DetailPesananProduk->first()->GudangBarangJadi->nama != "")
                        <div class="margin">
                            <div><small class="text-muted">Variasi</small></div>
                            <div>
                                <b> {{$res->DetailPesananProduk->first()->GudangBarangJadi->nama}} </b>

                            </div>
                        </div>
                        @endif
                        @endif
                        <div class="margin">
                            <div><small class="text-muted">No SO</small></div>
                            <div><b>{{$res->Pesanan->so}}</b></div>
                        </div>
                        <div class="margin">
                            <div><small class="text-muted">Jumlah</small></div>
                            <div><b>{{$res->jumlah}}</b></div>
                        </div>
                </div>
            </div>
        </div>
        <div class="col-7">
            <div class="card">
                <div class="card-body">
                    <div class="form-horizontal">
                        <div class="form-group row">
                            <label for="" class="col-5 align-right col-form-label">Detail Produk</label>
                            <div class="col-7">
                                @if(count($res->DetailPesananProduk) <= 1) <label for="" class="col-form-label">{{$res->PenjualanProduk->nama}}
                                    @if($res->DetailPesananProduk->first()->GudangBarangJadi->nama != "") - {{$res->DetailPesananProduk->first()->GudangBarangJadi->nama}} @endif</label>
                                    @else <select class="select form-control detail_produk" name="detail_produk" id="detail_produk">
                                    </select>
                                    @endif
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-center" id="noseritable" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No Seri</th>
                                    <th>Hasil</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>