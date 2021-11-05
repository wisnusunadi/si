<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="col-5">
                <div class="card">
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <span style="font-size: 24px"><b>SPA</b></span>
                            </li>
                            <li class="list-group-item">
                                <a>Nama Customer</a>
                                <b class="float-right" id="nama_customer">{{$data->customer->nama}}</b>
                            </li>
                            <li class="list-group-item">
                                <a>No PO</a>
                                <b class="float-right" id="no_po"></b>
                            </li>
                            <li class="list-group-item">
                                <a>Tanggal PO</a>
                                <b class="float-right" id="tanggal_pemesanan"></b>
                            </li>
                            <li class="list-group-item">
                                <a>Status</a>
                                <b class="float-right" id="status"></b>
                            </li>
                            <li class="list-group-item">
                                <a class="text-muted" id="keterangan">{{$data->ket}}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-7">
                <h5>Detail Pemesanan</h5>
                <div class="card">
                    <div class="card-body">
                        <table class="table" id="detailtabel_spa">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                    <th>No Seri</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- <tr>
                                    <td>1</td>
                                    <td>FOX-BABY Yellow</td>
                                    <td>5</td>
                                    <td>Rp. 15.000</td>
                                    <td><i class="fas fa-search"></i></td>
                                </tr> -->
                            </tbody>
                            <!-- <tfoot>
                                <tr>
                                    <th colspan="3" style="text-align:right;">Total Harga</th>
                                    <th id="totalharga" style="text-align:center;">Rp. 15.000</th>
                                    <th></th>
                                </tr>
                            </tfoot> -->
                            <tfoot>
                                <tr>
                                    <th width="15%" colspan="4">Total</th>
                                    <th width="15%" colspan="2"><input type="text" placeholder="Sub Total" class="form-control" id="subtotal" readonly></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>