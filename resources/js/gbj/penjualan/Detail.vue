<template>
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <h4 class="float-left" style="margin:10px;">Info</h4>
                                <h6 class="badge danger-text float-left" style="margin:10px;">Urgent</h6>
                            </div>
                        </div>
                        <div class="row" style="margin-left:10px;">
                            <div class="col-lg-4">
                                <div class="row"><b>Rumah Sakit Daerah Dr Wahidin Soedirohusodo<br>Jl Ahmad Yani<br>Surabaya</b></div>
                            </div>
                            <div class="col-lg-3">
                                <div class="row text-muted">No Penjualan</div>
                                <div class="row"><b>AKN1-947957917501</b></div>
                            </div>
                            <div class="col-lg-3">
                                <div class="row text-muted">Batas Pengiriman</div>
                                <div class="row"><b>15-10-2021</b></div>
                            </div>
                            <div class="col-lg-2">
                                <div class="row">Status PO</div>
                                <div class="row"><b>Sepakat</b></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <h4>Daftar Barang</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Produk</th>
                                            <th>Keterangan</th>
                                            <th>Jumlah</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Elitech FOX-Baby</td>
                                            <td>Yellow 2, Pink 1, Blue 2</td>
                                            <td><span class="float-right">5</span></td>
                                            <td><i class="fas fa-ellipsis-v"></i></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Elitech Shoe Cover</td>
                                            <td>-</td>
                                            <td><span class="float-right">2</span></td>
                                            <td><i class="fas fa-ellipsis-v"></i></td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>Elitech Pocket Fetal Doppler</td>
                                            <td>-</td>
                                            <td><span style="color:red;"><i class="fas fa-exclamation-circle"></i></span><span class="float-right">1</span></td>
                                            <td><a data-toggle="modal" data-target="#modaledit"><i class="fas fa-ellipsis-v"></i></a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modaledit" tabindex="-1" role="dialog" aria-labelledby="modaledit" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content" style="margin:10px;">
                <div class="modal-body">
                    <h3>Info Barang</h3>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row d-flex ">
                                        <div class="col-lg-4">
                                            <div class="row">Elitech Pulse Oximeter FOX-Baby</div>
                                            <div class="row">Alat Kesehatan</div>
                                            <div class="row"><span class="success-text badge">Tersedia</span></div>
                                        </div>
                                        <div class="col-lg-3"><div class="row text-muted">Jumlah</div>
                                        <div class="row"><b>12.00</b></div>
                                        </div>
                                        <div class="col-lg-4"><div class="row">Keterangan</div>
                                        <div class="row"><b>Yellow 2, Pink 1, Blue 2</b></div></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-horizontal">
                                                <div class="form-group row">
                                                    <label class="col-form-label col-lg-4" style="text-align:right;">Pink</label>
                                                    <div class="form-input col-lg-4">
                                                        <input type="number" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <table class="table table-bordered center">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>No Seri</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td><input type="checkbox" class="form-check" v-model="no_seri"></td>
                                                                <td>FX010921A10000</td>
                                                            </tr>
                                                            <tr>
                                                                <td><input type="checkbox" class="form-check" v-model="no_seri"></td>
                                                                <td>FX010921A10001</td>
                                                            </tr>
                                                            <tr>
                                                                <td><input type="checkbox" class="form-check" v-model="no_seri"></td>
                                                                <td>FX010921A10002</td>
                                                            </tr>
                                                            <tr>
                                                                <td><input type="checkbox" class="form-check" v-model="no_seri"></td>
                                                                <td>FX010921A10003</td>
                                                            </tr>
                                                            <tr>
                                                                <td><input type="checkbox" class="form-check" v-model="no_seri"></td>
                                                                <td>FX010921A10004</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-horizontal">
                                                <div class="form-group row">
                                                    <label class="col-form-label col-lg-4" style="text-align:right;">Yellow</label>
                                                    <div class="form-input col-lg-4">
                                                        <input type="number" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <table class="table table-bordered center">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>No Seri</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td><input type="checkbox" class="form-check" v-model="no_seri"></td>
                                                                <td>FX010921A10000</td>
                                                            </tr>
                                                            <tr>
                                                                <td><input type="checkbox" class="form-check" v-model="no_seri"></td>
                                                                <td>FX010921A10001</td>
                                                            </tr>
                                                            <tr>
                                                                <td><input type="checkbox" class="form-check" v-model="no_seri"></td>
                                                                <td>FX010921A10002</td>
                                                            </tr>
                                                            <tr>
                                                                <td><input type="checkbox" class="form-check" v-model="no_seri"></td>
                                                                <td>FX010921A10003</td>
                                                            </tr>
                                                            <tr>
                                                                <td><input type="checkbox" class="form-check" v-model="no_seri"></td>
                                                                <td>FX010921A10004</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-horizontal">
                                                <div class="form-group row">
                                                    <label class="col-form-label col-lg-4" style="text-align:right;">Blue</label>
                                                    <div class="form-input col-lg-4">
                                                        <input type="number" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="form-group row">

                                                    <table class="table table-bordered center">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>No Seri</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td><input type="checkbox" class="form-check" v-model="no_seri"></td>
                                                                <td>FX010921A10000</td>
                                                            </tr>
                                                            <tr>
                                                                <td><input type="checkbox" class="form-check" v-model="no_seri"></td>
                                                                <td>FX010921A10001</td>
                                                            </tr>
                                                            <tr>
                                                                <td><input type="checkbox" class="form-check" v-model="no_seri"></td>
                                                                <td>FX010921A10002</td>
                                                            </tr>
                                                            <tr>
                                                                <td><input type="checkbox" class="form-check" v-model="no_seri"></td>
                                                                <td>FX010921A10003</td>
                                                            </tr>
                                                            <tr>
                                                                <td><input type="checkbox" class="form-check" v-model="no_seri"></td>
                                                                <td>FX010921A10004</td>
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
                    </div>
                </div>
                <div class="modal-footer">
                    <span class="float-left"><button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button></span>
                    <span class="float-right"><button type="button" class="btn btn-info">Simpan</button></span>
                </div>
                </div>
            </div>
        </div>

    </div>
</div>
</template>

<style>
thead{ text-align: center; }
td:nth-child(1), td:nth-child(3), td:nth-child(5) { text-align: center; }
.center{width:80%; margin-left:auto;margin-right:auto;}
</style>