<div class="row filter">
    <div class="col-12">
        <div class="card card-detail removeshadow">
            {{-- <img src="https://picsum.photos/200/200" class="card-img-top" alt="..."> --}}
            {{-- <div id="profileImage" class="center card-img-top"></div> --}}
            <div class="card-body border-0">
                <h5 class="card-title pl-2 py-2"><b>{{ $data->Customer->nama }}</b></h5>
                <ul class="fa-ul card-text">
                    <li class="py-2"><span class="fa-li"><i class="fas fa-user-alt fa-fw"></i></span>
                        @if ($data->satuan != '')
                            {{ $data->satuan }}
                        @else
                            <em class="text-muted">Belum Tersedia</em>
                        @endif
                    </li>
                    <li class="py-2"><span class="fa-li"><i
                                class="fas fa-address-card fa-fw"></i></span>
                        @if ($data->alamat != '')
                            {{ $data->alamat }}
                        @else
                            <em class="text-muted">Belum Tersedia</em>
                        @endif
                    </li>
                    <li class="py-2"><span class="fa-li"><i
                                class="fas fa-map-marker-alt fa-fw"></i></span>
                        @if (!empty($data->provinsi))
                            {{ $data->Provinsi->nama }}
                        @else
                            <em class="text-muted">Belum Tersedia</em>
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-12">
        @if ($data->ket != '')
            <div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-triangle"></i> <strong>Catatan: </strong>{{ $data->ket }}
            </div>
        @endif
        <div class="card card-purple card-outline card-tabs">
            <div class="card-header p-0 pt-1 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="tabs-detail-tab" data-toggle="pill" href="#tabs-detail"
                            role="tab" aria-controls="tabs-detail" aria-selected="true">Informasi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tabs-produk-tab" data-toggle="pill" href="#tabs-produk" role="tab"
                            aria-controls="tabs-produk" aria-selected="false">Produk</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-three-tabContent">
                    <div class="tab-pane fade active show" id="tabs-detail" role="tabpanel"
                        aria-labelledby="tabs-detail-tab">

                        <div class="row d-flex justify-content-between">

                            <div class="p-2">
                                <div class="margin">
                                    <div><small class="text-muted">No SO</small></div>
                                    <div><b>
                                            @if ($data->Pesanan->so)
                                                {{ $data->pesanan->so }}
                                            @else
                                                <em class="text-muted">Belum Tersedia</em>
                                            @endif
                                        </b>
                                    </div>
                                </div>
                                <div class="margin">
                                    <div><small class="text-muted">No AKN</small></div>
                                    <div><b>
                                            @if ($data->no_paket)
                                                {{ $data->no_paket }}
                                            @else
                                                <em class="text-muted">Belum Tersedia</em>
                                            @endif
                                        </b>
                                    </div>
                                </div>
                                <div class="margin">
                                    <div><small class="text-muted">No Urut</small></div>
                                    <div><b>{{ $data->no_urut }}</b></div>
                                </div>
                            </div>
                            <div class="p-2">
                                <div class="margin">
                                    <div><small class="text-muted">Tgl Buat</small></div>
                                    <div><b>{{ date('d-m-Y', strtotime($data->tgl_buat)) }}</b></div>
                                </div>
                                <div class="margin">
                                    <div><small class="text-muted">Tgl Edit</small></div>
                                    <div><b>@if (!empty($data->edit))
                                        {{ date('d-m-Y', strtotime($data->tgl_edit)) }}
                                    @else
                                     -
                                    @endif</b></div>
                                </div>
                            </div>
                            <div class="p-2">
                                <div class="margin">
                                    <div><small class="text-muted">No PO</small></div>
                                    <div><b>
                                            @if ($data->Pesanan->no_po)
                                                {{ $data->Pesanan->no_po }}
                                            @else
                                                <em class="text-muted">Belum Tersedia</em>
                                            @endif
                                        </b>
                                    </div>
                                </div>
                                <div class="margin">
                                    <div><small class="text-muted">Tanggal PO</small></div>
                                    <div><b>
                                            @if ($data->Pesanan->tgl_po)
                                                {{ date('d-m-Y', strtotime($data->Pesanan->tgl_po)) }}
                                            @else
                                                <em class="text-muted">Belum Tersedia</em>
                                            @endif
                                        </b>
                                    </div>
                                </div>
                                <div class="margin">
                                    <div><small class="text-muted">Status</small></div>
                                    <div id="status"><b>
                                            @if ($data->status == 'sepakat')
                                                <span class="badge green-text">{{ ucfirst($data->status) }}</span>
                                            @elseif($data->status == 'negosiasi')
                                                <span class="badge yellow-text">{{ ucfirst($data->status) }}</span>
                                            @elseif($data->status == 'batal')
                                                <span class="badge red-text">{{ ucfirst($data->status) }}</span>
                                            @elseif($data->status == 'draft')
                                                <span class="badge blue-text">{{ ucfirst($data->status) }}</span>
                                            @endif
                                        </b>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tabs-produk" role="tabpanel" aria-labelledby="tabs-produk-tab">
                        <div class="table-responsive">
                            <?php $totalharga = 0; ?>
                            <?php $no = 0; ?>
                            @if(count($data->Pesanan->DetailPesanan) > 0)

                                <div class="card removeshadow overflowy">
                                    <div class="card-body">
                                        <div class="row">
                                        <div class="col-lg-4 col-md-12 mb-3 hide">
                                            <h6><b>Status Barang</b></h6>
                                            <div id="chartproduk"></div>
                                            <div class="row">
                                                <div class="col-12">
                                                     <div class="info-box bg-light removeshadow" {{--style="background-color:#5F7A90; color:white;" --}}>
                                                        <div class="info-box-content">
                                                        <span class="info-box-text">Produk</span>
                                                        <span class="info-box-number">ONE STATION + UPS + TROLLEY + CMS600 PLUS</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4 col-md-6">
                                                    <div class="info-box removeshadow" style="background-color:#EA8B1B; color:white;">
                                                        <div class="info-box-content">
                                                        <span class="info-box-text">Gudang</span>
                                                        <span class="info-box-number">500</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-6">
                                                    <div class="info-box removeshadow" style="background-color:#FFC700;">
                                                        <div class="info-box-content">
                                                        <span class="info-box-text">QC</span>
                                                        <span class="info-box-number">1000</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-6">
                                                    <div class="info-box removeshadow" style="background-color:#456600; color:white;">
                                                        <div class="info-box-content">
                                                        <span class="info-box-text">Logistik</span>
                                                        <span class="info-box-number">100</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- <div class="container mt-2">
                                                <ul id="status" class="d-flex justify-content-between">
                                                    <li><b style="color:#EA8B1B;">Gudang: 500</b></li>
                                                    <li><b style="color:#FFC700;">QC: 1000</b></li>
                                                    <li><b style="color:#456600;">Logistik: 100</b></li>
                                                </ul>
                                            </div> --}}
                                        </div>
                                        <div class="col-lg-12 col-md-12">
                                            <h6><b>Detail Produk</b></h6>
                                            <table class="table"
                                                style="max-width:100%; overflow-x: hidden; background-color:white;"
                                                id="tabledetailpesan">
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
                                                    @if (isset($data->Pesanan->detailpesanan))
                                                        @foreach ($data->pesanan->detailpesanan as $e)
                                                            <?php $no = $no + 1; ?>
                                                            <tr>
                                                                <td rowspan="{{ count($e->DetailPesananProduk) + 1 }}"
                                                                    class="nowraptxt">{{ $no }}</td>
                                                                <td><b
                                                                        class="wb">{{ $e->PenjualanProduk->nama }}</b>
                                                                </td>
                                                                <td colspan="2" class="nowraptxt">{{ $e->jumlah }}
                                                                </td>
                                                                <td rowspan="{{ count($e->DetailPesananProduk) + 1 }}"
                                                                    class="nowraptxt tabnum">@currency($e->harga)</td>
                                                                <td rowspan="{{ count($e->DetailPesananProduk) + 1 }}"
                                                                    class="nowraptxt tabnum">@currency($e->ongkir)</td>
                                                                <td rowspan="{{ count($e->DetailPesananProduk) + 1 }}"
                                                                    class="nowraptxt tabnum">@currency($e->harga * $e->jumlah + $e->ongkir)</td>
                                                                <?php $totalharga = $totalharga + ($e->harga * $e->jumlah + $e->ongkir); ?>
                                                            </tr>
                                                            @if (isset($e->DetailPesananProduk))
                                                                @foreach ($e->DetailPesananProduk as $l)
                                                                    <tr>
                                                                        <td><span class="text-muted">
                                                                                @if (!empty($l->GudangBarangJadi->nama))
                                                                                    {{ $l->GudangBarangJadi->Produk->nama }}
                                                                                    -
                                                                                    <b>{{ $l->GudangBarangJadi->nama }}</b>
                                                                                @else
                                                                                    {{ $l->GudangBarangJadi->Produk->nama }}
                                                                                @endif
                                                                            </span>
                                                                        </td>
                                                                        <td>
                                                                            {{ $l->getJumlahPesanan() }}
                                                                        </td>
                                                                        <td>{{ $l->getJumlahKirim() }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="6">Total Harga</td>
                                                        <td class="nowraptxt tabnum">@currency($totalharga)</td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="align-center text-danger"><i>Detail Pesanan Belum Tersedia</i></div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    {{-- <div class="col-12">
        <div class="row">
            <div class="col-lg-4 col-md-12">
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
                                        @if (!empty($data->provinsi))
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
                            <a class="text-muted">No Urut</a>
                            <b class="float-right">{{ $data->no_urut}}</b>
                        </div>
                        <div class="margin">
                            <a class="text-muted">Tgl Buat</a>
                            <b class="float-right">{{ date('d-m-Y', strtotime($data->tgl_buat)) }}</b>
                        </div>
                        <div class="margin">
                            <a class="text-muted">Tgl Edit</a>
                            <b class="float-right">{{ date('d-m-Y', strtotime($data->tgl_edit)) }}</b>
                        </div>
                        <div class="margin">
                            <a class="text-muted">Tgl Kontrak</a>
                            <b class="float-right">
                                @if (!empty($data->tgl_kontrak))
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
                                @if ($data->status == 'sepakat')
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

            <div class="col-lg-8 col-md-12">
                @if ($data->ket != '')
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> <strong>Catatan: </strong>{{$data->ket}}
                </div>
                @endif
                <h5>Detail Pemesanan</h5>
                <div class="card overflowy" id="detailekat">
                    <div class="card-body">
                        <div class="table-responsive">
                            <?php $totalharga = 0; ?>
                            <?php $no = 0; ?>
                            @if (isset($data->Pesanan))
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
                                            @if (isset($data->Pesanan->detailpesanan))
                                            @foreach ($data->pesanan->detailpesanan as $e)
                                            <?php $no = $no + 1; ?>
                                            <tr>
                                                <td rowspan="{{count($e->DetailPesananProduk) + 1}}" class="nowraptxt">{{$no}}</td>
                                                <td><b class="wb">{{$e->PenjualanProduk->nama}}</b></td>
                                                <td colspan="2" class="nowraptxt">{{$e->jumlah}}</td>
                                                <td rowspan="{{count($e->DetailPesananProduk) + 1}}" class="nowraptxt tabnum">@currency($e->harga)</td>
                                                <td rowspan="{{count($e->DetailPesananProduk) + 1}}" class="nowraptxt tabnum">@currency($e->ongkir)</td>
                                                <td rowspan="{{count($e->DetailPesananProduk) + 1}}" class="nowraptxt tabnum">@currency(($e->harga * $e->jumlah )+ $e->ongkir)</td>
                                                <?php $totalharga = $totalharga + ($e->harga * $e->jumlah + $e->ongkir); ?>
                                            </tr>
                                            @if (isset($e->DetailPesananProduk))
                                            @foreach ($e->DetailPesananProduk as $l)
                                            <tr>
                                                <td><span class="text-muted">@if (!empty($l->GudangBarangJadi->nama))
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
                                                <td class="nowraptxt tabnum">@currency($totalharga)</td>
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
    </div> --}}
</div>
