<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <span class="float-right filter">
                                <router-link :to="{ name: 'create' }"
                                    ><button class="btn btn-outline-info">
                                        <i class="fas fa-plus"></i> Tambah
                                    </button></router-link
                                >
                            </span>
                            <span class="float-right filter">
                                <button
                                    class="btn btn-outline-secondary"
                                    data-toggle="dropdown"
                                    aria-haspopup="true"
                                    aria-expanded="false"
                                >
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                                <div class="dropdown-menu">
                                    <div class="px-3 py-3">
                                        <div class="form-group">
                                            <label for="jenis_penjualan"
                                                >Jenis Penjualan</label
                                            >
                                        </div>
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    value="ekatalog"
                                                    id="defaultCheck1"
                                                />
                                                <label
                                                    class="form-check-label"
                                                    for="defaultCheck1"
                                                >
                                                    E-Catalogue
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    value="spa"
                                                    id="defaultCheck2"
                                                />
                                                <label
                                                    class="form-check-label"
                                                    for="defaultCheck2"
                                                >
                                                    SPA
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    value="spa"
                                                    id="defaultCheck2"
                                                />
                                                <label
                                                    class="form-check-label"
                                                    for="defaultCheck2"
                                                >
                                                    SPB
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <span class="float-right">
                                                <button class="btn btn-primary">
                                                    Cari
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table" style="text-align:center;">
                                    <thead>
                                        <th>No</th>
                                        <th>Jenis</th>
                                        <th>Customer</th>
                                        <th>Nomor</th>
                                        <th>Tanggal</th>
                                        <th>DO</th>
                                        <th>Tanggal DO</th>
                                        <th>Keterangan</th>
                                        <th>Aksi</th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>
                                                <span class="badge purple-text"
                                                    >E-Catalogue</span
                                                >
                                            </td>
                                            <td>PT. A</td>
                                            <td>PO/ON/10/2021/0001</td>
                                            <td>21-10-2021</td>
                                            <td>DO/31/10/2021</td>
                                            <td>31-10-2021</td>
                                            <td>-</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>
                                                <span class="badge blue-text"
                                                    >SPA</span
                                                >
                                            </td>
                                            <td>Pak K</td>
                                            <td>PO/ON/10/2021/0001</td>
                                            <td>21-10-2021</td>
                                            <td>DO/31/10/2021</td>
                                            <td>31-10-2021</td>
                                            <td>-</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>
                                                <span class="badge orange-text"
                                                    >SPB</span
                                                >
                                            </td>
                                            <td>Bu B</td>
                                            <td>PO/ON/10/2021/0001</td>
                                            <td>21-10-2021</td>
                                            <td>DO/31/10/2021</td>
                                            <td>31-10-2021</td>
                                            <td>-</td>
                                            <td></td>
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
</template>

<style>
.filter {
    margin: 5px;
}
</style>
