<template>
    <div class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row" style="margin-bottom:10px;">
                            <div class="col-12">
                                <span class="float-right">
                                    <router-link :to="{ name: 'create' }"><button class="btn btn-outline-info">Tambah</button></router-link>
                                </span>
                                <span class="dropdown float-right">
                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Filter</button>
                                    <div class="dropdown-menu">
                                    <form class="px-4" style="white-space:nowrap;">
                                        <div class="dropdown-header">Jenis</div>
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="dropdownJenis">
                                                <label class="form-check-label" for="dropdownJenis">
                                                    Distributor
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="dropdownJenis">
                                                <label class="form-check-label" for="dropdownJenis">
                                                    Pelanggan
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="dropdownJenis">
                                                <label class="form-check-label" for="dropdownJenis">
                                                    Vendor
                                                </label>
                                            </div>
                                        </div>
                                        <div class="dropdown-header">Status</div>
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="dropdownStatus">
                                                <label class="form-check-label" for="dropdownStatus">
                                                    E-Catalogue
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="dropdownStatus">
                                                <label class="form-check-label" for="dropdownStatus">
                                                    SPA
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="dropdownStatus">
                                                <label class="form-check-label" for="dropdownStatus">
                                                    SPB
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                        <button type="submit" class="btn btn-primary">Cari</button>
                                        </div>
                                    </form>
                                    </div>
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-hover">
                                    <thead style="text-align:center;">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Jenis</th>
                                            <th>Alamat</th>
                                            <th>Telp</th>
                                            <th>Keterangan</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>PT Dakai</td>
                                            <td>Distributor</td>
                                            <td>Jl. Tambak Osowilangun A7 Benowo</td>
                                            <td>08181828384</td>
                                            <td>-</td>
                                            <td><span class="badge blue-text">E-Catalogue</span></td>
                                            <td><div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a data-toggle="modal" data-target="#modaldetail">
                                                        <button class="dropdown-item" type="button">
                                                            <i class="fas fa-search"></i> Detail
                                                        </button>
                                                    </a>
                                                    <a data-toggle="modal" data-target="#modaledit">
                                                        <button class="dropdown-item" type="button">
                                                            <i class="fas fa-pencil-alt"></i> Edit
                                                        </button>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>PT Dakai</td>
                                            <td>Distributor</td>
                                            <td>Jl. Tambak Osowilangun A7 Benowo</td>
                                            <td>08181828384</td>
                                            <td>-</td>
                                            <td><span class="badge purple-text">SPA</span></td>
                                            <td><i class="fas fa-ellipsis-v"></i></td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>PT Dakai</td>
                                            <td>Distributor</td>
                                            <td>Jl. Tambak Osowilangun A7 Benowo</td>
                                            <td>08181828384</td>
                                            <td>-</td>
                                            <td><span class="badge orange-text">SPB</span></td>
                                            <td><i class="fas fa-ellipsis-v"></i></td>
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
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content" style="margin: 10px">
                    <div class="modal-header bg-warning"><h4>Edit</h4></div>
                    <div class="modal-body">
                        <form action="">
                            <div class="row d-flex justify-content-center">
                                <div class="col-11">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group row">
                                                        <label for="nama_produk" class="col-4 col-form-label" style="text-align:right;">Nama Customer</label>
                                                        <div class="col-6">
                                                            <input type="text" class="form-control" placeholder="Masukkan Nama Customer" v-model="nama_customer">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="nama_produk" class="col-4 col-form-label" style="text-align:right;">Jenis</label>
                                                        <div class="col-8">
                                                            <div class="form-check form-check-inline  col-form-label">
                                                                <input class="form-check-input" type="radio" v-model="jenis" name="jenis" id="jenis1" value="ekatalog">
                                                                <label class="form-check-label" for="jenis1">Distributor</label>
                                                            </div>
                                                            <div class="form-check form-check-inline  col-form-label">
                                                                <input class="form-check-input" type="radio" v-model="jenis" name="jenis" id="jenis2" value="spa">
                                                                <label class="form-check-label" for="jenis2">Pelanggan</label>
                                                            </div>
                                                            <div class="form-check form-check-inline  col-form-label">
                                                                <input class="form-check-input" type="radio" v-model="jenis" name="jenis" id="jenis3" value="spb">
                                                                <label class="form-check-label" for="jenis3">Vendor</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="alamat" class="col-4 col-form-label" style="text-align:right;">Alamat</label>
                                                        <div class="col-8">
                                                            <input type="text" class="form-control" placeholder="Masukkan Alamat" v-model="alamat">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="telepon" class="col-4 col-form-label" style="text-align:right;">No Telp</label>
                                                        <div class="col-5">
                                                            <input type="text" class="form-control"  pattern="[7-9]{1}[0-9]{9}" value="" placeholder="Masukkan Telepon" v-model="telepon">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="telepon" class="col-4 col-form-label" style="text-align:right;">Keterangan</label>
                                                        <div class="col-5">
                                                            <textarea class="form-control" name="keterangan" id="keterangan" v-model="keterangan"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <span class="float-left"><button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button></span>
                        <span class="float-right"><button type="button" class="btn btn-warning">Simpan</button></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {

    
}
</script>