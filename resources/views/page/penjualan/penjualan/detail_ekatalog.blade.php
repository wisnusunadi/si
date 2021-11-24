<div class="row filter">
    <div class="col-12">
        <h4><b>E-Catalogue</b></h4>
        <div class="row">
            <div class="col-5">
                <div class="card">
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item bordertopnone">
                                <h5>Info</h5>
                            </li>
                            <li class="list-group-item bordertopnone">
                                <a>No PO</a>
                                <b class="float-right" id="no_po"> @if ($data->Pesanan)
                                    {{ $data->Pesanan->no_po}}

                                    @endif</b>
                            </li>
                            <li class="list-group-item bordertopnone">
                                <a>No AKN</a>
                                <b class="float-right" id="no_akn">{{$data->no_paket}}</b>
                            </li>
                            <li class="list-group-item bordertopnone">
                                <a>Tanggal Pemesanan</a>
                                <b class="float-right" id="tanggal_pemesanan">{{$data->tgl_buat}}</b>
                            </li>
                            <li class="list-group-item bordertopnone">
                                <a>Tanggal Batas Kontrak</a>
                                <b class="float-right" id="batas_kontrak">{{$data->tgl_kontrak}}</b>
                            </li>
                            <li class="list-group-item bordertopnone">
                                <a>Nama Customer</a>
                                <b class="float-right" id="nama_customer">{{$data->customer->nama}}</b>
                            </li>
                            <li class="list-group-item bordertopnone">
                                <a>Instansi</a>
                                <b class="float-right" id="instansi">{{$data->instansi}}</b>
                            </li>
                            <li class="list-group-item bordertopnone">
                                <a>Satuan</a>
                                <b class="float-right" id="satuan">{{$data->satuan}}</b>
                            </li>
                            <li class="list-group-item bordertopnone">
                                <a>Status</a>
                                <b class="float-right" id="status">
                                    @if($data->status == "sepakat")
                                    <span class="badge green-text">{{ucfirst($data->status)}}</span>
                                    @elseif($data->status == "negosiasi")
                                    <span class="badge yellow-text">{{ucfirst($data->status)}}</span>
                                    @elseif($data->status == "batal")
                                    <span class="badge red-text">{{ucfirst($data->status)}}</span>
                                    @elseif($data->status == "draft")
                                    <span class="badge blue-text">{{ucfirst($data->status)}}</span>
                                    @endif
                                </b>
                            </li>
                            <li class="list-group-item bordertopnone">
                                <a class="text-muted" id="keterangan">{{$data->ket}}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-7">
                <h5>Detail Pemesanan</h5>
                <div class="card overflowy" id="detailekat">
                    <div class="card-body">
                        <div class="table-responsive">
                            <?php $totalharga = 0; ?>
                            @foreach($data->pesanan->detailpesanan as $e)
                            <div class="card removeshadow">
                                <div class="card-body">
                                    <h6>{{$e->PenjualanProduk->nama}}</h6>
                                    <div class="row align-center">
                                        <div class="col-4">
                                            <div class="text-muted">Harga</div>
                                            <div><b>@currency($e->harga)</b></div>
                                        </div>
                                        <div class="col-4">
                                            <div class="text-muted">Jumlah</div>
                                            <div><b>{{$e->jumlah}}</b></div>
                                        </div>
                                        <div class="col-4">
                                            <div class="text-muted">Subtotal</div>
                                            <div><b>@currency($e->harga * $e->jumlah)</b></div>
                                            <?php $totalharga = $totalharga + ($e->harga * $e->jumlah); ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="text-muted">Variasi</div>
                                            <ul class="list-group">
                                                @foreach($e->DetailPesananProduk as $l)
                                                <li class="list-group-item">
                                                    <b>
                                                        @if($l->nama != "")
                                                        {{$l->GudangBarangJadi->Produk->nama}} {{$l->GudangBarangJadi->nama}}
                                                        @else
                                                        {{$l->GudangBarangJadi->Produk->nama}}
                                                        @endif
                                                    </b>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            <div style="font-size:16px;" class="filter"><span><b>Total Harga</b></span><span class="float-right"><b>@currency($totalharga)</b></span></div>
                            <!-- <table class="table" id="detailtabel"> -->
                            <!-- <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Produk</th>
                                        <th>Variasi</th>
                                        <th>Harga</th>
                                        <th>Jumlah</th>
                                        <th>Subtotal</th>
                                        <th>No Seri</th>
                                    </tr>
                                </thead> -->
                            <!-- <tbody> -->

                            <!-- <tr>
                                    <td>1</td>
                                    <td>FOX-BABY Yellow</td>
                                    <td>5</td>
                                    <td>Rp. 15.000</td>
                                    <td><i class="fas fa-search"></i></td>
                                </tr> -->
                            <!-- </tbody> -->
                            <!-- <tfoot>
                                <tr>
                                    <th colspan="3" style="text-align:right;">Total Harga</th>
                                    <th id="totalharga" style="text-align:center;">Rp. 15.000</th>
                                    <th></th>
                                </tr>
                                </tfoot> -->
                            <!-- <tfoot>
                                    <tr>
                                        <th width="15%" colspan="5">Total</th>
                                        <th width="15%" colspan="2"><input type="text" placeholder="Sub Total" class="form-control" id="subtotal" readonly></th>
                                    </tr>
                                </tfoot> -->
                            <!-- </table> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>