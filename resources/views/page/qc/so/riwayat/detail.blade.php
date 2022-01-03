<div class="content">
    <div class="row d-flex justify-content">
        <div class="col-5">
            <div class="card">
                <div class="card-body">
                    <h4>Info Produk</h4>
                    <div class="margin">
                        <div><small class="text-muted">Nama Produk</small></div>
                        <div><b>{{$res->PenjualanProduk->nama}}</b></div>
                    </div>

                    <div class="margin">
                        <div><small class="text-muted">No SO</small></div>
                        <div><b>{{$res->Pesanan->so}}</b></div>
                    </div>
                    <div class="margin">
                        <div><small class="text-muted">Jumlah</small></div>
                        <div><b>{{$res->jumlah}}</b></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-7">
            <div class="card">
                <div class="card-body">
                    <div class="form-horizontal">
                        <div class="form-group row">
                            <label for="" class="col-5 align-right col-form-label">Detail Produk</label>
                            <div class="col-5">
                                <select class="select form-control detail_produk" name="detail_produk" id="detail_produk">

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-center" id="noseritable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No Seri</th>
                                    <th>Hasil</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>