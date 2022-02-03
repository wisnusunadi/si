<div class="row">
    <div class="col-lg-4 col-md-12">
        <div class="card">
            <div class="card-body">
                <h5>Detail</h5>
                <div class="row">
                    <div class="col-lg-12 col-md-8">
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
                    <div class="col-lg-12 col-md-4">
                        <div><small class="text-muted">Jumlah</small></div>
                        <div><b>{{$jumlah}} pcs</b></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8 col-md-12">
        <div class="card">
            <div class="card-body">
                <h5>Laporan</h5>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-hover" id="detailtable" style="text-align: center;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No SO</th>
                                        <th>Tanggal Order</th>
                                        <th>Tanggal Delivery</th>
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
