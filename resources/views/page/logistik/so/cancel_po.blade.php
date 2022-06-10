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
        <div class="card">
            <div class="card-body"></div>
        </div>
    </div>
</div>
