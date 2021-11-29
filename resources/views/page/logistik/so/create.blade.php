<div class="content">
    <div class=" d-flex justify-content-center">
        <div class="col-11">
            <form action="" method="POST">
                <div class="d-flex justify-content-center">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-horizontal">
                                    <h5>Data Pengiriman</h5>
                                    <div class="form-group row">
                                        <label class="col-form-label col-5 align-right" for="no_invoice">No Surat Jalan</label>
                                        <div class="col-6">
                                            <input type="text" class="form-control col-form-label" name="no_invoice" id="no_invoice">
                                            <div class="invalid-feedback" id="msgno_invoice"></div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-5 align-right" for="tgl_kirim">Tanggal Pengiriman</label>
                                        <div class="col-4">
                                            <input type="date" class="form-control col-form-label" name="tgl_kirim" id="tgl_kirim">
                                            <div class="invalid-feedback" id="msgtgl_kirim"></div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="" class="col-form-label col-5 align-right">Pengiriman</label>
                                        <div class="col-5 col-form-label">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="pengiriman" id="pengiriman1" value="ekspedisi" />
                                                <label class="form-check-label" for="pengiriman1">Ekspedisi</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="pengiriman" id="pengiriman2" value="nonekspedisi" />
                                                <label class="form-check-label" for="pengiriman2">Non Ekspedisi</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="card col-12 hide" id="ekspedisi">
                                            <div class="card-body">
                                                <h6><b>Ekspedisi</b></h6>
                                                <div class="form-group row">
                                                    <label class="col-form-label col-5 align-right" for="ekspedisi_id">Jasa Pengiriman</label>
                                                    <div class="col-7">
                                                        <select class="select2 select-info form-control ekspedisi_id" name="ekspedisi_id" id="ekspedisi_id" style="width: 100%;">
                                                            <option value=""></option>
                                                        </select>
                                                        <div class="invalid-feedback" id="msgekspedisi_id"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card col-12 hide" id="nonekspedisi">
                                            <div class="card-body">
                                                <h6><b>Non Ekspedisi</b></h6>
                                                <div class="form-group row">
                                                    <label class="col-form-label col-5 align-right" for="nama_pengirim">Nama Pengirim</label>
                                                    <div class="col-7">
                                                        <input type="text" class="form-control col-form-label" name="nama_pengirim" id="nama_pengirim">
                                                        <div class="invalid-feedback" id="msgnama_pengirim"></div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-form-label col-5 align-right" for="no_polisi">No Kendaraan</label>
                                                    <div class="col-7">
                                                        <input type="text" class="form-control col-form-label" name="no_polisi" id="no_polisi">
                                                        <div class="invalid-feedback" id="msgno_polisi"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5>Data Barang</h5>
                            <div class="form-group row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-center" style="width:100%;" id="detailpesanan">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Barang</th>
                                                    <th>Jumlah</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- <tr>
                                                    <td>1</td>
                                                    <td>MTB 2 MTR</td>
                                                    <td>10</td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>CENTRAL MONITOR PM-9000+ + PC + INSTALASI</td>
                                                    <td>1</td>
                                                </tr> -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6 float-left">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                </div>
                <div class="col-6">
                    <button type="submit" class="btn btn-info float-right" id="btnsimpan" disabled>Simpan</button>
                </div>
            </div>
        </div>
    </div>
</div>
{{$id}}