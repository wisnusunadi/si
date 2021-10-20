<template>
    <div class="row">
        <div class="col-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a
                        class="nav-link active"
                        id="semua-penjualan-tab"
                        data-toggle="tab"
                        href="#semua-penjualan"
                        role="tab"
                        aria-controls="semua-penjualan"
                        aria-selected="true"
                        >Penjualan</a
                    >
                </li>
                <li class="nav-item" role="presentation">
                    <a
                        class="nav-link"
                        id="ekatalog-tab"
                        data-toggle="tab"
                        href="#ekatalog"
                        role="tab"
                        aria-controls="ekatalog"
                        aria-selected="false"
                        >E-Catalogue</a
                    >
                </li>
                <li class="nav-item" role="presentation">
                    <a
                        class="nav-link"
                        id="spa-tab"
                        data-toggle="tab"
                        href="#spa"
                        role="tab"
                        aria-controls="spa"
                        aria-selected="false"
                        >SPA</a
                    >
                </li>
                <li class="nav-item" role="presentation">
                    <a
                        class="nav-link"
                        id="spb-tab"
                        data-toggle="tab"
                        href="#spb"
                        role="tab"
                        aria-controls="spb"
                        aria-selected="false"
                        >SPB</a
                    >
                </li>
            </ul>
            <div class="tab-content card" id="myTabContent">
                <div
                    class="tab-pane fade show active card-body"
                    id="semua-penjualan"
                    role="tabpanel"
                    aria-labelledby="semua-penjualan-tab"
                >
                    <div class="row">
                        <div class="col-12">
                            <span class="float-right">
                                <button class="btn btn-outline-secondary">
                                    Filter
                                </button>
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal Order</th>
                                        <th>Batas Kontrak</th>
                                        <th>Customer</th>
                                        <th>Jenis</th>
                                        <th>Nomor AKN</th>
                                        <th>Nomor PO</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>05-10-2021</td>
                                        <td>
                                            <span style="color:red;"
                                                >19-10-2021</span
                                            >
                                        </td>
                                        <td>RS Soeryadi Kendal</td>
                                        <td>E-Catalogue</td>
                                        <td>AKN1-79479274207</td>
                                        <td>PO/ON/51/10/21</td>
                                        <td>
                                            <span class="red-text badge"
                                                >Batal</span
                                            >
                                        </td>
                                        <td>
                                            <i class="fas fa-ellipsis-v"></i>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>14-10-2021</td>
                                        <td>
                                            <span style="color:#FFC700;"
                                                >28-10-2021</span
                                            >
                                        </td>
                                        <td>Pak Amin Pasuruan</td>
                                        <td>SPB</td>
                                        <td>-</td>
                                        <td>PO/ON/45/10/21</td>
                                        <td>
                                            <span class="green-text badge"
                                                >Pengiriman</span
                                            >
                                        </td>
                                        <td>
                                            <i class="fas fa-ellipsis-v"></i>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>15-10-2021</td>
                                        <td>29-10-2021</td>
                                        <td>PT Emiindo Jakarta</td>
                                        <td>SPA</td>
                                        <td>-</td>
                                        <td>PO/ON/37/10/21</td>
                                        <td>
                                            <span class="yellow-text badge"
                                                >Gudang</span
                                            >
                                        </td>
                                        <td>
                                            <i class="fas fa-ellipsis-v"></i>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div
                    class="tab-pane fade card-body"
                    id="ekatalog"
                    role="tabpanel"
                    aria-labelledby="ekatalog-tab"
                >
                    <div class="row">
                        <div class="col-12">
                            <span class="float-right"
                                ><router-link :to="{ name: 'create_akn' }"
                                    ><button class="btn btn-outline-info">
                                        Tambah
                                    </button></router-link
                                ></span
                            >
                            <span class="float-right">
                                <button class="btn btn-outline-secondary">
                                    Filter
                                </button>
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal Order</th>
                                        <th>Batas Kontrak</th>
                                        <th>Customer</th>
                                        <th>Nomor AKN</th>
                                        <th>Nomor PO</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>05-10-2021</td>
                                        <td>
                                            <span style="color:red;"
                                                >19-10-2021</span
                                            >
                                        </td>
                                        <td>RS Soeryadi Kendal</td>
                                        <td>AKN1-79479274207</td>
                                        <td>PO/ON/51/10/21</td>
                                        <td>
                                            <span class="red-text badge"
                                                >Batal</span
                                            >
                                        </td>
                                        <td>
                                            <i class="fas fa-ellipsis-v"></i>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>14-10-2021</td>
                                        <td>
                                            <span style="color:#FFC700;"
                                                >28-10-2021</span
                                            >
                                        </td>
                                        <td>PT Cipta Medika Pasuruan</td>
                                        <td>-</td>
                                        <td>PO/ON/45/10/21</td>
                                        <td>
                                            <span class="green-text badge"
                                                >Sepakat</span
                                            >
                                        </td>
                                        <td>
                                            <i class="fas fa-ellipsis-v"></i>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>15-10-2021</td>
                                        <td>29-10-2021</td>
                                        <td>PT Emiindo Jakarta</td>
                                        <td>-</td>
                                        <td>PO/ON/37/10/21</td>
                                        <td>
                                            <span class="yellow-text badge"
                                                >Negosiasi</span
                                            >
                                        </td>
                                        <td>
                                            <i class="fas fa-ellipsis-v"></i>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div
                    class="tab-pane fade card-body"
                    id="spa"
                    role="tabpanel"
                    aria-labelledby="spa-tab"
                >
                    <div class="row">
                        <div class="col-12">
                            <span class="float-right">
                                <button class="btn btn-outline-secondary">
                                    Filter
                                </button>
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal Order</th>
                                        <th>Batas Kontrak</th>
                                        <th>Customer</th>
                                        <th>Nomor PO</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>05-10-2021</td>
                                        <td>
                                            <span style="color:red;"
                                                >19-10-2021</span
                                            >
                                        </td>
                                        <td>RS Soeryadi Kendal</td>
                                        <td>PO/ON/51/10/21</td>
                                        <td>
                                            <span class="red-text badge"
                                                >PO</span
                                            >
                                        </td>
                                        <td>
                                            <i class="fas fa-ellipsis-v"></i>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>14-10-2021</td>
                                        <td>
                                            <span style="color:#FFC700;"
                                                >28-10-2021</span
                                            >
                                        </td>
                                        <td>PT Cipta Medika Pasuruan</td>
                                        <td>PO/ON/51/10/21</td>
                                        <td>
                                            <span class="yellow-text badge"
                                                >Gudang</span
                                            >
                                        </td>
                                        <td>
                                            <i class="fas fa-ellipsis-v"></i>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>15-10-2021</td>
                                        <td>29-10-2021</td>
                                        <td>PT Emiindo Jakarta</td>
                                        <td>PO/ON/37/10/21</td>
                                        <td>
                                            <span class="green-text badge"
                                                >Pengiriman</span
                                            >
                                        </td>
                                        <td>
                                            <i class="fas fa-ellipsis-v"></i>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div
                    class="tab-pane fade card-body"
                    id="spb"
                    role="tabpanel"
                    aria-labelledby="spb-tab"
                >
                    <div class="row">
                        <div class="col-12">
                            <span class="float-right">
                                <button class="btn btn-outline-secondary">
                                    Filter
                                </button>
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal Order</th>
                                        <th>Batas Kontrak</th>
                                        <th>Customer</th>
                                        <th>Nomor PO</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>05-10-2021</td>
                                        <td>
                                            <span style="color:red;"
                                                >19-10-2021</span
                                            >
                                        </td>
                                        <td>RS Soeryadi Kendal</td>
                                        <td>PO/ON/51/10/21</td>
                                        <td>
                                            <span class="yellow-text badge"
                                                >Gudang</span
                                            >
                                        </td>
                                        <td>
                                            <i class="fas fa-ellipsis-v"></i>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>14-10-2021</td>
                                        <td>
                                            <span style="color:#FFC700;"
                                                >28-10-2021</span
                                            >
                                        </td>
                                        <td>PT Cipta Medika Pasuruan</td>
                                        <td>PO/ON/45/10/21</td>
                                        <td>
                                            <span class="yellow-text badge"
                                                >Gudang</span
                                            >
                                        </td>
                                        <td>
                                            <i class="fas fa-ellipsis-v"></i>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>15-10-2021</td>
                                        <td>29-10-2021</td>
                                        <td>PT Emiindo Jakarta</td>
                                        <td>PO/ON/37/10/21</td>
                                        <td>
                                            <span class="green-text badge"
                                                >Pengiriman</span
                                            >
                                        </td>
                                        <td>
                                            <i class="fas fa-ellipsis-v"></i>
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
</template>
