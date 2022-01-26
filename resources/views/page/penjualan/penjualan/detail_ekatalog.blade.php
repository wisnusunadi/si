<div class="row filter">
    <div class="col-12">
        <div class="row">
            <div class="col-4">
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
                                <div><b>{{$data->satuan}}</b></div>
                                <div>{{$data->alamat}}</div>
                                <div>
                                        @if(!empty($data->provinsi))
                                        {{$data->Provinsi->nama}}
                                        @endif
                                    </div>

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
                            <b class="float-right">@if ($data->Pesanan->so) {{ $data->pesanan->so}} @else - @endif</b>
                        </div>
                        <div class="margin">
                            <a class="text-muted">No AKN</a>
                            <b class="float-right">{{ $data->no_paket}}</b>
                        </div>
                        <div class="margin">
                            <a class="text-muted">Tgl Order</a>
                            <b class="float-right">{{ date('d-m-Y', strtotime($data->tgl_buat)) }}</b>
                        </div>
                        <div class="margin">
                            <a class="text-muted">Tgl Kontrak</a>
                            <b class="float-right">
                                @if(!empty($data->tgl_kontrak))
                                {{ date('d-m-Y', strtotime($data->tgl_kontrak)) }}

                                @endif
                            </b>
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
                                {{ date('d-m-Y', strtotime($data->Pesanan->tgl_po)) }}
                                @else
                                -
                                @endif</b>
                        </div>
                        <div class="margin">
                            <a class="text-muted">Status</a>
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
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-8">
                <h5>Detail Pemesanan</h5>
                <div class="card overflowy" id="detailekat">
                    <div class="card-body">
                        <div class="table-responsive">
                            <?php $totalharga = 0; ?>
                            <?php $no = 0; ?>
                            @if(isset($data->Pesanan))
                            <div class="card removeshadow">
                                <div class="card-body" id="detailekat">
                                    <table class="table" style="max-width:100%; overflow-x: hidden; background-color:white;" id="tabledetailpesan">
                                        <thead>
                                            <tr>
                                                <th rowspan="2">No</th>
                                                <th rowspan="2">Produk</th>
                                                <th colspan="2">Qty</th>
                                                <th rowspan="2">Harga</th>
                                                <th rowspan="2">Ongkir</th>
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
                                                <td rowspan="{{count($e->DetailPesananProduk) + 1}}" class="nowraptxt">@currency($e->ongkir)</td>
                                                <td rowspan="{{count($e->DetailPesananProduk) + 1}}" class="nowraptxt">@currency(($e->harga * $e->jumlah )+ $e->ongkir)</td>
                                                <?php $totalharga = $totalharga + (($e->harga * $e->jumlah) + $e->ongkir); ?>
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
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="6">Total Harga</td>
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
