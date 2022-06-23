<div class="row">
    <div class="col-lg-12 col-md-12">
        {{-- <div class="card shadow-none"> --}}
            {{-- <div class="card-body"> --}}
                <h5>@if(!empty($data->nama))
                    {{$data->Produk->nama}} - {{$data->nama}}
                    @else
                    {{$data->Produk->nama}}
                    @endif</h5>
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <div class="info-box bg-warning">
                            <span class="info-box-icon"><i class="fas fa-warehouse"></i></span>
                            <div class="info-box-content">
                            <span class="info-box-text">Gudang</span>
                            <span class="info-box-number">{{$data->stok}} pcs</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="info-box bg-olive">
                            <span class="info-box-icon"><i class="fas fa-cart-arrow-down"></i></span>
                            <div class="info-box-content">
                            <span class="info-box-text">Permintaan</span>
                            <span class="info-box-number">{{$jumlah}} pcs</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="info-box bg-maroon">
                            <span class="info-box-icon"><i class="fas fa-clipboard-list"></i>
                            </span>
                            <div class="info-box-content">
                            <span class="info-box-text">Sisa</span>
                            <span class="info-box-number">{{$data->stok - $jumlah}} pcs</span>
                            </div>
                        </div>
                    </div>
                </div>
            {{-- </div> --}}
        {{-- </div> --}}
    </div>

    <div class="col-lg-12 col-md-12">
        <hr>
        <div class="card shadow-none">
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
                                        <th>Customer</th>
                                        <th>Tanggal Order</th>
                                        <th>Tanggal Delivery</th>
                                        <th>Jumlah</th>
                                        <th>Status</th>
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
