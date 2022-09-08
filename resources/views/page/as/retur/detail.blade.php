<div class="row filter">
    <div class="col-12">
        <div class="card card-detail removeshadow">
            {{-- <img src="https://picsum.photos/200/200" class="card-img-top" alt="..."> --}}
            {{-- <div id="profileImage" class="center card-img-top"></div> --}}
            <div class="card-body border-0">
                <h5></h5>
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <div class="info-box bg-warning">
                            <span class="info-box-icon"><i class="fas fa-file-invoice"></i></span>
                            <div class="info-box-content">
                            <span class="info-box-text">No Retur</span>
                            <span class="info-box-number">REF/0001/2002/2892/2126</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="info-box bg-olive">
                            <span class="info-box-icon"><i class="fas fa-calendar"></i></span>
                            <div class="info-box-content">
                            <span class="info-box-text">Tanggal Retur</span>
                            <span class="info-box-number">20 Juni 2022</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="info-box bg-maroon">
                            <span class="info-box-icon"><i class="fas fa-user"></i>
                            </span>
                            <div class="info-box-content">
                            <span class="info-box-text">No Ref Penjualan / Retur</span>
                            <span class="info-box-number">SO/EKAT/2022/IV/9210</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="alert alert-danger" role="alert">
            <i class="fas fa-exclamation-triangle"></i> <strong>Catatan: Retur karena Part Hilang</strong>
        </div>
        <div class="card card-info card-outline card-tabs">
            <div class="card-header p-0 pt-1 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="tabs-detail-tab" data-toggle="pill" href="#tabs-detail"
                            role="tab" aria-controls="tabs-detail" aria-selected="true">Ref Retur / Penjualan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tabs-produk-tab" data-toggle="pill" href="#tabs-produk" role="tab"
                            aria-controls="tabs-produk" aria-selected="false">Data Produk</a>
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
                                    <div><small class="text-muted">Customer</small></div>
                                    <div><b>
                                            <em class="text-muted">Belum Tersedia</em>
                                        </b>
                                    </div>
                                </div>
                                <div class="margin">
                                    <div><small class="text-muted">Alamat</small></div>
                                    <div><b>
                                            <em class="text-muted">Belum Tersedia</em>
                                        </b>
                                    </div>
                                </div>
                                <div class="margin">
                                    <div><small class="text-muted">No Telp</small></div>
                                    <div><b>
                                            <em class="text-muted">Belum Tersedia</em>
                                        </b>
                                    </div>
                                </div>
                            </div>
                            <div class="p-2">
                                <div class="margin">
                                    <div><small class="text-muted">No SO</small></div>
                                    <div><b>Belum Tersedia</b></div>
                                </div>
                                <div class="margin">
                                    <div><small class="text-muted">No PO / NO Paket</small></div>
                                    <div><b>Belum Tersedia</b></div>
                                </div>
                                <div class="margin">
                                    <div><small class="text-muted">Tgl Order</small></div>
                                    <div>
                                        <b>Belum Tersedia</b>
                                    </div>
                                </div>
                            </div>
                            <div class="p-2">
                                <div class="margin">
                                    <div><small class="text-muted">No PO</small></div>
                                    <div><b>
                                            <em class="text-muted">Belum Tersedia</em>
                                        </b>
                                    </div>
                                </div>
                                <div class="margin">
                                    <div><small class="text-muted">Tanggal PO</small></div>
                                    <div><b>
                                            <em class="text-muted">Belum Tersedia</em>
                                        </b>
                                    </div>
                                </div>
                                <div class="margin">
                                    <div><small class="text-muted">Status</small></div>
                                    <div id="status">
                                        <b>
                                        </b>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tabs-produk" role="tabpanel" aria-labelledby="tabs-produk-tab">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Paket</th>
                                        <th>Produk</th>
                                        <th>No Seri</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>ONE STATION + ANTROPOMETRI</td>
                                        <td>Timbangan BMI</td>
                                        <td>BMI783A39010, BMI783A39012, BMI783A39015</td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>CMS-600 PLUS</td>
                                        <td>USG CMS 600 PLUS</td>
                                        <td>CMS0122B00078</td>
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
