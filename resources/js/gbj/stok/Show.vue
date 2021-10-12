<template>
    <div class="row">
        <div class="col-lg-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a
                        class="nav-link active"
                        id="semua-produk-tab"
                        data-toggle="tab"
                        href="#semua-produk"
                        role="tab"
                        aria-controls="semua-produk"
                        aria-selected="true"
                        >Semua Produk</a
                    >
                </li>
                <li class="nav-item" role="presentation">
                    <a
                        class="nav-link"
                        id="produk-tab"
                        data-toggle="tab"
                        href="#produk"
                        role="tab"
                        aria-controls="produk"
                        aria-selected="false"
                        >Per Produk</a
                    >
                </li>
                <li class="nav-item" role="presentation">
                    <a
                        class="nav-link"
                        id="tanggal-tab"
                        data-toggle="tab"
                        href="#tanggal"
                        role="tab"
                        aria-controls="tanggal"
                        aria-selected="false"
                        >Per Tanggal</a
                    >
                </li>
            </ul>
            <div class="tab-content card" id="myTabContent">
                <div
                    class="tab-pane fade show active card-body"
                    id="semua-produk"
                    role="tabpanel"
                    aria-labelledby="semua-produk-tab"
                >
                    <div class="row">
                        <div class="col-lg-12">
                            <span
                                class="btn-group  float-right"
                                role="group"
                                aria-label="Button group with nested dropdown"
                            >
                                <button
                                    type="button"
                                    class="btn btn-outline-info active"
                                    id="tablebtn"
                                >
                                    <i class="fas fa-list"></i>
                                </button>
                                <button
                                    type="button"
                                    class="btn btn-outline-info"
                                    id="gridbtn"
                                >
                                    <i class="fas fa-th"></i>
                                </button>
                            </span>
                            <span class="float-right">
                                <router-link :to="{ name: 'create' }"
                                    ><button type="button" class="btn btn-info">
                                        <i class="fas fa-plus"></i> Tambah
                                    </button></router-link
                                >
                            </span>
                            <span
                                class="dropdown float-right"
                                id="filter"
                                style="margin-right:5px;"
                            >
                                <button
                                    class=" btn btn-outline-info dropdown-toggle"
                                    type="button"
                                    id="dropdownFilter"
                                    data-toggle="dropdown"
                                    aria-haspopup="true"
                                    aria-expanded="false"
                                >
                                    Filter
                                </button>
                                <ul
                                    id="filter_dd"
                                    class="dropdown-menu dropdown-menu-right"
                                    aria-labelledby="dropdownFilter"
                                >
                                    <li>
                                        <span class="dropdown-header"
                                            >Kelompok Produk</span
                                        >
                                    </li>
                                    <li>
                                        <span
                                            class="dropdown-item jenis_po po_online"
                                            id="jenis_po"
                                            name="jenis_po"
                                            ><input
                                                type="checkbox"
                                                value="alat_kesehatan"
                                            />
                                            Alat Kesehatan</span
                                        >
                                    </li>
                                    <li>
                                        <span
                                            class="dropdown-item jenis_po po_offline"
                                            id="jenis_po"
                                            name="jenis_po"
                                            ><input
                                                type="checkbox"
                                                value="sarana_kesehatan"
                                            />
                                            Sarana Kesehatan</span
                                        >
                                    </li>
                                    <li>
                                        <span
                                            class="dropdown-item jenis_po po_offline"
                                            id="jenis_po"
                                            name="jenis_po"
                                            ><input
                                                type="checkbox"
                                                value="aksesoris"
                                            />
                                            Aksesoris</span
                                        >
                                    </li>
                                    <li>
                                        <span
                                            class="dropdown-item jenis_po po_offline"
                                            id="jenis_po"
                                            name="jenis_po"
                                            ><input
                                                type="checkbox"
                                                value="lain"
                                            />
                                            Lain-lain</span
                                        >
                                    </li>
                                </ul>
                            </span>
                        </div>
                    </div>

                    <div class="row" style="margin-top:5px;">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table
                                    id="semuaproduk"
                                    class="table table-hover table-striped"
                                    width="100%"
                                >
                                    <thead
                                        style="text-align: center; font-size: 15px; "
                                    >
                                        <tr>
                                            <th>No</th>
                                            <th>Kode Produk</th>
                                            <th>Merk</th>
                                            <th>Nama Produk</th>
                                            <th>Kelompok Produk</th>
                                            <th>Stok</th>
                                            <th>Satuan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodies"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="tab-pane fade card-body"
                    id="produk"
                    role="tabpanel"
                    aria-labelledby="produk-tab"
                >
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-horizontal">
                                <div class="form-group row">
                                    <label
                                        for="produk"
                                        class="col-sm-4 col-form-label"
                                        style="text-align:right;"
                                        >Cari Produk</label
                                    >
                                    <div class="col-sm-8">
                                        <div class="select2-info">
                                            <select
                                                class="select2 custom-select form-control @error('produk') is-invalid @enderror produk"
                                                data-dropdown-css-class="select2-info"
                                                style="width: 50%;"
                                                name="produk"
                                                id="produk"
                                            >
                                                <option value="">Tes</option>
                                                <option value="">ERP</option>
                                                <option value="">SPA</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <span
                                class="btn-group  float-right"
                                role="group"
                                aria-label="Button group with nested dropdown"
                            >
                                <button
                                    type="button"
                                    class="btn btn-outline-info active"
                                    id="tablebtn"
                                >
                                    <i class="fas fa-list"></i>
                                </button>
                                <button
                                    type="button"
                                    class="btn btn-outline-info"
                                    id="gridbtn"
                                >
                                    <i class="fas fa-th"></i>
                                </button>
                            </span>
                            <span
                                class="dropdown float-right"
                                id="filter"
                                style="margin-right:5px;"
                            >
                                <button
                                    class=" btn btn-outline-info dropdown-toggle"
                                    type="button"
                                    id="dropdownFilter"
                                    data-toggle="dropdown"
                                    aria-haspopup="true"
                                    aria-expanded="false"
                                >
                                    Filter
                                </button>
                                <ul
                                    id="filter_dd"
                                    class="dropdown-menu dropdown-menu-right"
                                    aria-labelledby="dropdownFilter"
                                >
                                    <li>
                                        <span class="dropdown-header"
                                            >Asal / Tujuan</span
                                        >
                                    </li>
                                    <li>
                                        <span
                                            class="dropdown-item jenis_po po_online"
                                            id="jenis_po"
                                            name="jenis_po"
                                            ><input
                                                type="checkbox"
                                                value="alat_kesehatan"
                                            />
                                            Produksi</span
                                        >
                                    </li>
                                    <li>
                                        <span
                                            class="dropdown-item jenis_po po_offline"
                                            id="jenis_po"
                                            name="jenis_po"
                                            ><input
                                                type="checkbox"
                                                value="sarana_kesehatan"
                                            />
                                            QC</span
                                        >
                                    </li>
                                    <li>
                                        <span
                                            class="dropdown-item jenis_po po_offline"
                                            id="jenis_po"
                                            name="jenis_po"
                                            ><input
                                                type="checkbox"
                                                value="aksesoris"
                                            />
                                            Sarana Kesehatan</span
                                        >
                                    </li>
                                    <li>
                                        <span
                                            class="dropdown-item jenis_po po_offline"
                                            id="jenis_po"
                                            name="jenis_po"
                                            ><input
                                                type="checkbox"
                                                value="lain"
                                            />
                                            Teknik</span
                                        >
                                    </li>
                                </ul>
                            </span>
                        </div>
                    </div>

                    <div class="row" style="margin-top:5px;">
                        <div class="col-lg-3">
                            <div class="card">
                                <div class="card-body"></div>
                            </div>
                        </div>
                        <div class="col-lg-9">
                            <div class="table-responsive">
                                <table
                                    id="semuaproduk"
                                    class="table table-hover table-striped"
                                    width="100%"
                                >
                                    <thead
                                        style="text-align: center; font-size: 15px; "
                                    >
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Asal / Tujuan</th>
                                            <th>Keterangan</th>
                                            <th>Jumlah</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodies">
                                        <tr>
                                            <td>1</td>
                                            <td>24-09-2021</td>
                                            <td>Produksi</td>
                                            <td>
                                                Ref Hasil Produksi
                                                0001/BPPB/09/21
                                            </td>
                                            <td>
                                                <span style="color:green;"
                                                    ><i class="fas fa-plus"></i
                                                    ><span class="float-right"
                                                        >1000</span
                                                    ></span
                                                >
                                            </td>
                                            <td>
                                                <a href=""
                                                    ><i
                                                        class="fas fa-search"
                                                    ></i
                                                ></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="tab-pane fade card-body"
                    id="tanggal"
                    role="tabpanel"
                    aria-labelledby="tanggal-tab"
                >
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-horizontal">
                                <div class="form-group row">
                                    <label
                                        for="detail_produk_id"
                                        class="col-sm-5 col-form-label"
                                        style="text-align:right;"
                                        >Tanggal</label
                                    >
                                    <div class="col-sm-2">
                                        <input
                                            type="date"
                                            class="form-control"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <span
                                class="btn-group  float-right"
                                role="group"
                                aria-label="Button group with nested dropdown"
                            >
                                <button
                                    type="button"
                                    class="btn btn-outline-info active"
                                    id="tablebtn"
                                >
                                    <i class="fas fa-list"></i>
                                </button>
                                <button
                                    type="button"
                                    class="btn btn-outline-info"
                                    id="gridbtn"
                                >
                                    <i class="fas fa-th"></i>
                                </button>
                            </span>
                            <span
                                class="dropdown float-right"
                                id="filter"
                                style="margin-right:5px;"
                            >
                                <button
                                    class=" btn btn-outline-info dropdown-toggle"
                                    type="button"
                                    id="dropdownFilter"
                                    data-toggle="dropdown"
                                    aria-haspopup="true"
                                    aria-expanded="false"
                                >
                                    Filter
                                </button>
                                <ul
                                    id="filter_dd"
                                    class="dropdown-menu dropdown-menu-right"
                                    aria-labelledby="dropdownFilter"
                                >
                                    <li>
                                        <span class="dropdown-header"
                                            >Pilih Produk</span
                                        >
                                    </li>
                                    <li>
                                        <span
                                            class="dropdown-item jenis_po po_online"
                                            id="jenis_po"
                                            name="jenis_po"
                                        >
                                            <select
                                                class="select2 custom-select form-control @error('produk') is-invalid @enderror produk"
                                                data-dropdown-css-class="select2-info"
                                                style="width: 50%;"
                                                name="produk"
                                                id="produk"
                                            >
                                                <option value="Tes">Tes</option>
                                            </select>
                                        </span>
                                    </li>
                                </ul>
                            </span>
                        </div>
                    </div>

                    <div class="row" style="margin-top:5px;">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table
                                    id="tanggalproduk"
                                    class="table table-hover"
                                    width="100%"
                                >
                                    <thead
                                        style="text-align: center; font-size: 15px; "
                                    >
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Nama Produk</th>
                                            <th>Asal / Tujuan</th>
                                            <th>Keterangan</th>
                                            <th>Jumlah</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodies">
                                        <tr>
                                            <td>1</td>
                                            <td>24-09-2021</td>
                                            <td>FOX-BABY</td>
                                            <td>Produksi</td>
                                            <td>
                                                Ref Hasil Produksi
                                                0001/BPPB/09/21
                                            </td>
                                            <td>
                                                <span style="color:green;"
                                                    ><i class="fas fa-plus"></i
                                                    ><span class="float-right"
                                                        >1000</span
                                                    ></span
                                                >
                                            </td>
                                            <td>
                                                <a href=""
                                                    ><i
                                                        class="fas fa-search"
                                                    ></i
                                                ></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>24-09-2021</td>
                                            <td>CMS-600 PLUS</td>
                                            <td>Produksi</td>
                                            <td>
                                                Ref Hasil Produksi
                                                0001/BPPB/09/21
                                            </td>
                                            <td>
                                                <span style="color:red;"
                                                    ><i class="fas fa-plus"></i
                                                    ><span class="float-right"
                                                        >10</span
                                                    ></span
                                                >
                                            </td>
                                            <td>
                                                <a href=""
                                                    ><i
                                                        class="fas fa-search"
                                                    ></i
                                                ></a>
                                            </td>
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
