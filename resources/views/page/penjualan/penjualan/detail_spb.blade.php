<div class="row filter">
    <div class="col-12">
        <div class="row filter">
            <div class="col-5">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Info Customer</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 align-center">
                                <!-- <div id="profileImage" class="center margin-all"></div> -->
                                <div>
                                    <h6><b>{{$data->Customer->nama}}</b></h6>
                                </div>
                                <div><b>{{$data->Customer->alamat}}</b></div>
                                <div><b>{{$data->Customer->Provinsi->nama}}</b></div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Info Penjualan</h5>
                    </div>
                    <div class="card-body">
                        <div class="margin">
                            <a class="text-muted">No SO</a>
                            <b class="float-right">@if ($data->Pesanan->so)
                                {{ $data->Pesanan->so}}
                                @else
                                -
                                @endif</b>
                        </div>
                        <div class="margin">
                            <a class="text-muted">No PO</a>
                            <b class="float-right">
                                @if ($data->Pesanan->no_po)
                                {{ $data->Pesanan->no_po}}
                                @else
                                -
                                @endif</b>
                        </div>
                        <div class="margin">
                            <a class="text-muted">Tanggal PO</a>
                            <b class="float-right">@if ($data->Pesanan->tgl_po)
                                {{ $data->Pesanan->tgl_po}}
                                @else
                                -
                                @endif</b>
                        </div>
                        <div class="margin">
                            <a class="text-muted">Status</a>
                            <b class="float-right" id="status">
                                <b class="float-right" id="status">
                                    @if (!empty($data->Pesanan->log_id))
                                    @if ($data->Pesanan->State->nama == "Penjualan")
                                    <span class="red-text badge">
                                        @elseif ($data->Pesanan->State->nama == "PO")
                                        <span class="purple-text badge">
                                            @elseif ($data->Pesanan->State->nama == "Gudang")
                                            <span class="orange-text badge">
                                                @elseif ($data->Pesanan->State->nama == "QC")
                                                <span class="yellow-text badge">
                                                    @elseif ($data->Pesanan->State->nama == "Terkirim Sebagian")
                                                    <span class="blue-text badge">
                                                        @elseif ($data->Pesanan->State->nama == "Kirim")
                                                        <span class="green-text badge">
                                                            @endif
                                                            {{ucfirst($data->Pesanan->State->nama)}}</span>
                                                        @endif
                                </b>
                            </b>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-7">
                <h5>Detail Pemesanan</h5>
                <div class="card overflowy" id="detailspb">
                    <div class="card-body">
                        <div class="table-responsive">
                            <?php $totalharga = 0; ?>
                            @if(isset($data->Pesanan))
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
                                                @isset($e->DetailPesananProduk)
                                                @foreach($e->DetailPesananProduk as $l)
                                                <li class="list-group-item">

                                                    @if(!empty($l->GudangBarangJadi->nama))
                                                    {{$l->GudangBarangJadi->Produk->nama}} - <b>{{$l->GudangBarangJadi->nama}}</b>
                                                    @else
                                                    {{$l->GudangBarangJadi->Produk->nama}}
                                                    @endif
                                                </li>
                                                @endforeach
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                            <div style="font-size:16px;" class="filter"><span><b>Total Harga</b></span><span class="float-right"><b>@currency($totalharga)</b></span></div>
                            @else
                            <div class="align-center"><i>Detail Pesanan Belum Tersedia</i></div>
                            @endif

                            <!-- <table class="table" id="detailtabel_spb">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Produk</th>
                                        <th>Harga</th>
                                        <th>Jumlah</th>
                                        <th>Subtotal</th>
                                        <th>No Seri</th>
                                    </tr>
                                </thead>
                                <tbody> -->
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
                                    <th width="15%" colspan="4">Total</th>
                                    <th width="15%" colspan="2"><input type="text" placeholder="Sub Total" class="form-control" id="subtotal" readonly></th>
                                </tr>
                            </tfoot>
                            </table> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>