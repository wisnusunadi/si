<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5>Info Produk</h5>
                    <div class="row">
                        <div class="col-8">
                            <div><small class="text-muted">Nama Produk</small></div>
                            <div><b>{{$res->DetailPesananProduk->GudangBarangJadi->produk->nama}}</b></div>
                        </div>
                        <div class="col-1"></div>
                        <div class="col-3">
                            <div><small class="text-muted">Jumlah</small></div>
                            <div><b>{{$res->NoseriDetailLogistik->count()}}</b></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5>Data No Seri</h5>
                    <div class="table-responsive">
                        <table class="table table-hover" style="text-align: center;" id="showtable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No Seri</th>
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