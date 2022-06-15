<div class="row filter">
    <div class="col-12">
        <div class="card removeshadow">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <div class="info-box bg-maroon">
                            <span class="info-box-icon"><i class="fas fa-receipt"></i></span>
                            <div class="info-box-content">
                            <span class="info-box-text">No SO</span>
                            <span class="info-box-number">{{$data->Pesanan->so}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="info-box bg-warning">
                            <span class="info-box-icon"><i class="fas fa-receipt"></i></span>
                            <div class="info-box-content">
                            <span class="info-box-text">No PO</span>
                            <span class="info-box-number">{{$data->Pesanan->no_po}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="info-box bg-olive">
                            <span class="info-box-icon"><i class="far fa-user"></i></span>
                            <div class="info-box-content">
                            <span class="info-box-text">Nama Customer</span>
                            <span class="info-box-number">{{$data->Customer->nama}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="info-box bg-light">
                            <span class="info-box-icon"><i class="fas fa-exclamation-circle"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Status</span>
                                <span class="info-box-number">
                                    @if (!empty($data->Pesanan->log_id))
                                        @if ($data->Pesanan->State->nama == 'Penjualan')
                                        <span class="red-text badge">
                                        @elseif ($data->Pesanan->State->nama == 'PO')
                                        <span class="purple-text badge">
                                        @elseif ($data->Pesanan->State->nama == 'Gudang')
                                        <span class="orange-text badge">
                                        @elseif ($data->Pesanan->State->nama == 'QC')
                                        <span class="yellow-text badge">
                                        @elseif ($data->Pesanan->State->nama == 'Belum Terkirim')
                                        <span class="red-text badge">
                                        @elseif ($data->Pesanan->State->nama == 'Terkirim Sebagian')
                                        <span class="blue-text badge">
                                        @elseif ($data->Pesanan->State->nama == 'Kirim')
                                        <span class="green-text badge">
                                        @endif
                                    {{ ucfirst($data->Pesanan->State->nama) }}</span>
                                    @else
                                    -
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-navy card-outline card-tabs">
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
                    <div class="tab-pane fade active show" id="tabs-detail" role="tabpanel" aria-labelledby="tabs-detail-tab">
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
                                    <div><b>{{ date('d-m-Y', strtotime($data->tgl_edit)) }}</b></div>
                                </div>
                                <div class="margin">
                                    <div><small class="text-muted">Tgl Kontrak</small></div>
                                    <div><b>
                                            @if (!empty($data->tgl_kontrak))
                                                {{ date('d-m-Y', strtotime($data->tgl_kontrak)) }}
                                            @else
                                                <em class="text-muted">Belum Tersedia</em>
                                            @endif
                                        </b>
                                    </div>
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
                            <table class="table table-hover" id="produktable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Produk</th>
                                        <th>Jumlah</th>
                                        <th>No Seri</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
