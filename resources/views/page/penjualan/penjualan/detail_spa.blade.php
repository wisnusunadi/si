<div class="row filter">
    <div class="col-12">
        <div class="row">
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
                            <b class="float-right">@if ($data->Pesanan->tgl_po != "" && $data->Pesanan->tgl_po != "0000-00-00")
                                {{ date('d-m-Y', strtotime($data->Pesanan->tgl_po)) }}
                                @else
                                -
                                @endif</b>
                        </div>
                        <div class="margin">
                            <a class="text-muted">Status</a>
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
                                                    @else
                                                    -
                                                    @endif
                            </b>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-7">
                <h5>Detail Pemesanan</h5>
                <div class="card overflowy" id="detailspa">
                    <div class="card-body">
                        <div class="table-responsive">
                            <!-- <div class="form-horizontal"> -->
                            <?php $totalharga = 0; ?>
                            <?php $no = 0; ?>
                            @if(isset($data->Pesanan))
                            <div class="card removeshadow" style="background-color:white;">
                                <div class="card-body">
                                    <table class="table" style="max-width:100%; overflow-x: hidden; background-color:white;" id="tabledetailpesan">
                                        <thead>
                                            <tr>
                                                <th rowspan="2">No</th>
                                                <th rowspan="2">Produk</th>
                                                <th colspan="2">Qty</th>
                                                <th rowspan="2">Harga</th>
                                                <th rowspan="2">Subtotal</th>
                                            </tr>
                                            <tr>
                                                <th><i class="fas fa-shopping-cart"></i></th>
                                                <th><i class="fas fa-truck"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(isset($data->Pesanan->detailpesanan))
                                            @foreach($data->pesanan->detailpesanan as $e)
                                            <?php $no = $no + 1; ?>
                                            <tr>
                                                <td rowspan="{{count($e->DetailPesananProduk) + 1}}" class="nowraptxt">{{$no}}</td>
                                                <td><b class="wb">{{$e->PenjualanProduk->nama}}</b></td>
                                                <td colspan="2" class="nowraptxt">{{$e->jumlah}}</td>
                                                <td rowspan="{{count($e->DetailPesananProduk) + 1}}" class="nowraptxt">@currency($e->harga)</td>
                                                <td rowspan="{{count($e->DetailPesananProduk) + 1}}" class="nowraptxt">@currency($e->harga * $e->jumlah)</td>
                                                <?php $totalharga = $totalharga + ($e->harga * $e->jumlah); ?>
                                            </tr>
                                            @if(isset($e->DetailPesananProduk))
                                            @foreach($e->DetailPesananProduk as $l)
                                            <tr>
                                                <td><span class="text-muted">@if(!empty($l->GudangBarangJadi->nama))
                                                        {{$l->GudangBarangJadi->Produk->nama}} - <b>{{$l->GudangBarangJadi->nama}}</b>
                                                        @else
                                                        {{$l->GudangBarangJadi->Produk->nama}}
                                                        @endif</span>
                                                </td>
                                                <td>
                                                    {{$l->getJumlahPesanan()}}
                                                </td>
                                                <td>{{$l->getJumlahKirim()}}</td>
                                            </tr>
                                            @endforeach
                                            @endif
                                            @endforeach
                                            @endif

                                            @if(isset($data->Pesanan->detailpesananpart))
                                            @foreach($data->pesanan->detailpesananpart as $e)
                                            <?php $no = $no + 1; ?>
                                            <tr>
                                                <td class="nowraptxt">{{$no}}</td>
                                                <td class="wb"><b>{{$e->Sparepart->nama}}</b></td>
                                                <td class="nowraptxt"><span class="text-muted">{{$e->jumlah}}</span></td>
                                                <td class="nowraptxt">@if(isset($e->detaillogistikpart)) {{$e->jumlah}} @else 0 @endif</td>
                                                <td class="nowraptxt">@currency($e->harga)</td>
                                                <td class="nowraptxt">@currency($e->harga * $e->jumlah)</td>
                                                <?php $totalharga = $totalharga + ($e->harga * $e->jumlah); ?>
                                            </tr>
                                            @endforeach
                                            @endif
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="5">Total Harga</td>
                                                <td>@currency($totalharga)</td>
                                            </tr>
                                        </tfoot>

                                    </table>
                                </div>
                            </div>
                            @else
                            <div class="align-center"><i>Detail Pesanan Belum Tersedia</i></div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>