<div class="row">
    <div class="col-lg-12 col-md-12">
        {{-- <div class="card">
            <div class="card-body"> --}}
                <h5>@if(!empty($data->nama))
                    {{$data->Produk->nama}} - {{$data->nama}}
                    @else
                    {{$data->Produk->nama}}
                    @endif</h5>
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <div class="info-box bg-warning">
                            <span class="info-box-icon"><i class="fas fa-file-invoice"></i></span>
                            <div class="info-box-content">
                            <span class="info-box-text">Pesanan</span>
                            <span class="info-box-number">{{$jumlah}} pcs</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="info-box bg-olive">
                            <span class="info-box-icon"><i class="fas fa-shipping-fast"></i></span>
                            <div class="info-box-content">
                            <span class="info-box-text">Selesai Dikirim</span>
                            <span class="info-box-number">{{$jumlahselesai}} pcs</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="info-box bg-maroon">
                            <span class="info-box-icon"><i class="fas fa-truck-loading"></i>
                            </span>
                            <div class="info-box-content">
                            <span class="info-box-text">Belum Dikirim</span>
                            <span class="info-box-number">{{$jumlahproses}} pcs</span>
                            </div>
                        </div>
                    </div>
                </div>
            {{-- </div>
        </div> --}}
    </div>
    <div class="col-lg-12 col-md-12">
        <hr>
        <div class="card shadow-none">
            <div class="card-body">
                <h5 class="mb-2">Laporan</h5>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-hover" id="detailtable" style="text-align: center; width:100%;">
                                <thead>
                                    <tr>
                                        <th rowspan="2">No</th>
                                        <th rowspan="2">No SO</th>
                                        <th rowspan="2">Customer</th>
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
