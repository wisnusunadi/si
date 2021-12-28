<div class="row">
    <div class="col-3">
        <div class="card">
            <div class="card-body">
                <h5>Detail</h5>
                <div class="row">
                    <div class="col-12">
                        <div><small class="text-muted">Nama Produk</small></div>
                        <div>
                            <b>
                                @if(!empty($data->nama))
                                {{$data->Produk->nama}} - {{$data->nama}}
                                @else
                                {{$data->Produk->nama}}
                                @endif
                            </b>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div><small class="text-muted">Jumlah Pesanan</small></div>
                        <div><b>{{$jumlah}} pcs</b></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div><small class="text-muted">Jumlah Selesi Dikirim</small></div>
                        <div><b>{{$jumlahselesai}} pcs</b></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div><small class="text-muted">Jumlah Belum Dikirim</small></div>
                        <div><b>{{$jumlahproses}} pcs</b></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-9">
        <div class="card">
            <div class="card-body">
                <h5>Laporan</h5>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-hover" id="detailtable" style="text-align: center; width:100%;">
                                <thead>
                                    <tr>
                                        <th rowspan="2">No</th>
                                        <th rowspan="2">No SO</th>
                                        <th rowspan="2">Tanggal Delivery</th>
                                        <th rowspan="2">Jumlah</th>
                                        <th colspan="2">Pengiriman</th>
                                    </tr>
                                    <tr>
                                        <th>Terkirim</th>
                                        <th>Sisa</th>
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