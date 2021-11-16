<div class="row">
    <div class="col-4">
        <div class="card">
            <div class="card-body">
                <h5 class="filter">Info</h5>
                <div class="filter">
                    <div><small class="text-muted">Nama Produk</small></div>
                    <div><b>-</b></div>
                </div>
                <div class="filter">
                    <div><small class="text-muted">No AKD</small></div>
                    <div><b>-</b></div>
                </div>
                <div class="filter">
                    <div><small class="text-muted">Jumlah</small></div>
                    <div><b>-</b></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-8">
        <form action="" id="form-create-coo">
            <div class="card">
                <div class="card-body">
                    <div class="form-horizontal">
                        <div class="form-group row">
                            <label for="" class="col-5 col-form-label" style="text-align:right;">Bulan</label>
                            <div class="col-3">
                                <select class="form-control bulan_edit col-form-label" name="bulan" id="bulan">
                                    <option value=""></option>
                                    <option value="I">Januari</option>
                                    <option value="II">Februari</option>
                                    <option value="III">Maret</option>
                                    <option value="IV">April</option>
                                    <option value="V">Mei</option>
                                    <option value="VI">Juni</option>
                                    <option value="VII">Juli</option>
                                    <option value="VIII">Agustus</option>
                                    <option value="IX">September</option>
                                    <option value="X">Oktober</option>
                                    <option value="XI">November</option>
                                    <option value="XII">Desember</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-5 col-form-label" style="text-align:right;">Diketahui Oleh</label>
                            <div class="col-5 col-form-label">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="diketahui" id="diketahui1" value="spa" />
                                    <label class="form-check-label" for="diketahui1">PT Sinko Prima Alloy</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="diketahui" id="diketahui2" value="custom" />
                                    <label class="form-check-label" for="diketahui2">Custom</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row hide" id="nama_label">
                            <label for="" class="col-5 col-form-label" style="text-align:right;">Nama</label>
                            <div class="col-5">
                                <input type="text" class="form-control col-form-label" id="nama" name="nama">
                            </div>
                        </div>
                        <div class="form-group row hide" id="jabatan_label">
                            <label for="" class="col-5 col-form-label" style="text-align:right;">Jabatan</label>
                            <div class="col-5">
                                <input type="text" class="form-control col-form-label" id="jabatan" name="jabatan">
                            </div>
                        </div>
                        <div class="form-group row">
                            <table class="table table-bordered" style="width: 100%; text-align:center;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No Seri</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>FX358085238401</td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>FX358085238390</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <span>
                        <button class="btn btn-danger float-left" data-dismiss="modal">Batal</button>
                    </span>
                    <span>
                        <button type="submit" class="btn btn-warning float-right disabled" id="btnsimpan">Simpan</button>
                    </span>
                </div>
            </div>
        </form>
    </div>
</div>