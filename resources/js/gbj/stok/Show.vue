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
                            <span class="float-right">
                                <router-link :to="{ name: 'create' }"><button type="button" class="btn btn-info"><i class="fas fa-plus"></i> Tambah</button></router-link>
                            </span>
                            <span class="dropdown float-right" id="semuaprodukfilter" style="margin-right:5px;">
                                <button class=" btn btn-outline-info dropdown-toggle" type="button" id="semuaprodukfilter" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Filter
                                </button>
                                <div class="dropdown-menu p-3 text-nowrap" aria-labelledby="semuaprodukfilter">
                                    <div class="dropdown-header">Kelompok Produk</div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="sp_kelompok" value="alat_kesehatan">
                                            <label class="form-check-label" for="sp_kelompok">
                                                Alat Kesehatan
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="sp_kelompok" value="sarana_kesehatan">
                                            <label class="form-check-label" for="sp_kelompok">
                                                Sarana Kesehatan
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="sp_kelompok" value="aksesoris">
                                            <label class="form-check-label" for="sp_kelompok">
                                                Aksesoris
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="sp_kelompok" value="lain">
                                            <label class="form-check-label" for="sp_kelompok">
                                                Lain-lain
                                            </label>
                                        </div>
                                    </div>
                                    <div class="dropdown-header">Ketersediaan Stok</div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="sp_stok" value="tersedia">
                                            <label class="form-check-label" for="sp_stok">
                                                Tersedia
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="sp_stok" value="hampir_habis">
                                            <label class="form-check-label" for="sp_stok">
                                                Hampir Habis
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="sp_stok" value="kosong">
                                            <label class="form-check-label" for="sp_stok">
                                                Kosong
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-primary float-right">Cari</button>
                                    </div>
                                </div>
                            </span>
                        </div>
                    </div>

                    <div class="row" style="margin-top:5px;">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-hover semuaproduk" width="100%">
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
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>BJAA01NB001</td>
                                            <td>Elitech</td>
                                            <td>Anesthesia Nebulizer Promist 1</td>
                                            <td><span class="badge blue-text">Alat Kesehatan</span></td>
                                            <td><span class="float-right" style="color:green;">1000</span></td>
                                            <td>unit</td>
                                            <td><i class="fas fa-search"></i></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>BJOZ03G0001</td>
                                            <td>Elitech</td>
                                            <td>Ozone Generator OZ - 3G</td>
                                            <td><span class="badge orange-text">Sarana Kesehatan</span></td>
                                            <td><span class="float-right" style="color:red;">5</span></td>
                                            <td>unit</td>
                                            <td><i class="fas fa-search"></i></td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>BJZZ03T0010</td>
                                            <td>Elitech</td>
                                            <td>Trolley Get 388UO</td>
                                            <td><span class="badge purple-text">Aksesoris</span></td>
                                            <td><span class="float-right" style="color:rgba(0, 0, 0, 0.4);">0</span></td>
                                            <td>unit</td>
                                            <td><i class="fas fa-search"></i></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade card-body" id="produk" role="tabpanel" aria-labelledby="produk-tab">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-horizontal">
                                <div class="form-group row">
                                    <div class="col-sm-7">
                                        <div class="select2-info">
                                            <select class="select2 custom-select form-control produk" data-dropdown-css-class="select2-info" style="width:40%;" name="produk" id="produk">
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
                            <span class="dropdown float-right" id="perprodukfilter" style="margin-right:5px;">
                                <button class=" btn btn-outline-info dropdown-toggle" type="button" id="perprodukfilter" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Filter
                                </button>
                                <div class="dropdown-menu p-3 text-nowrap" aria-labelledby="perprodukfilter">
                                    <div class="form-group">
                                        <label for="p_tanggal">Tanggal</label>
                                        <input type="date" class="form-control" id="p_tanggal" placeholder="">
                                    </div>
                                    <div class="form-group">
                                        <label for="p_divisiid">Asal/Tujuan</label>
                                        <select name="p_divisiid" id="p_divisiid" class="form-control">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Cari</button>
                                </div>
                            </span>
                        </div>
                    </div>

                    <div class="row" style="margin-top:5px;">
                        <div class="col-3 col-md-3 col-sm-3">
                            <div class="card">
                                <div class="card-body">
                                    <ul class="list-group">
                                        <li class="list-group-item"><span style="font-size:24px;"><b>Info</b></span><span class="float-right green-text col-form-label"><b>Tersedia</b></span></li>
                                        <li class="list-group-item"><a>Nama Produk</a><span id="nama_produk">tes1</span><b class="float-right">FOX-BABY</b></li>
                                        <li class="list-group-item"><a>Kelompok Produk</a><span id="kelompok_produk">tes2</span><b class="float-right">Alat Kesehatan</b></li>
                                        <li class="list-group-item"><a>Stok Terakhir</a><span id="stok"></span>tes3<b class="float-right">1000</b></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-9 col-md-9 col-sm-9">
                            <div class="table-responsive">
                                <table
                                    class="table table-hover table-striped perproduk"
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
                            <span class="dropdown float-right" id="pertanggalfilter" style="margin-right:5px;">
                                <button class="btn btn-outline-info dropdown-toggle" type="button" id="pertanggalfilter" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Filter
                                </button>
                                <div class="dropdown-menu dropdown-menu-right text-nowrap p-3 " aria-labelledby="pertanggalfilter">
                                    <div class="dropdown-header">Asal / Tujuan</div>
                                    <div class="form-group">
                                        <select name="divisi_id" class="form-control custom-select" id="">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-primary float-right">Cari</button>
                                    </div>
                                </div>
                            </span>
                        </div>
                    </div>

                    <div class="row" style="margin-top:5px;">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-hover pertanggal" width="100%">
                                    <thead style="text-align: center; font-size: 15px;">
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

<script>
import axios from "axios";
export default {
    data(){
    },
    methods: {
        semuaproduk: function(status){
            $('.semuaproduk').DataTable({
                ajax: "http://localhost:8000/api/gbj/stok/all_produk/"+status,
                columns: [
                    {
                        data: "DT_RowIndex",
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: "kode_produk",
                        name: "kode_produk"
                    },
                    {
                        data: "merk",
                        name: "merk"
                    },
                    {
                        data: "nama_produk",
                        name: "nama_produk"
                    },
                    {
                        data: null,
                        render: function (data) {
                            if(data.kelompok_produk == "Alat Kesehatan")
                            {
                                return '<span class="badge blue-text">'+data.kelompok_produk+'</span>'
                            }
                            else if(data.kelompok_produk == "Sarana Kesehatan")
                            {
                                return '<span class="badge blue-text">'+data.kelompok_produk+'</span>'
                            }
                            else if(data.kelompok_produk == "Aksesori")
                            {
                                return '<span class="badge purple-text">'+data.kelompok_produk+'</span>'
                            }
                        },
                    },
                    {
                        data: satuan,
                        nama: satuan,
                        orderable:false, 
                        searchable:false
                    },
                    {
                        data: null,
                        render: function(data){
                            return `<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <router-link :to="{ path: '/gbj/stok/no_seri/`+data.id+`' }"><button class="dropdown-item" type="button"><i class="fas fa-search"></i> No Seri</button></router-link>
                                <router-link :to="{ path: '/gbj/stok/mutasi/`+data.id+`' }"><button class="dropdown-item" type="button"><i class="fas fa-search"></i> Mutasi</button></router-link>
                            </div>`;
                        },
                        orderable:false, 
                        searchable:false
                    }
                ],
            });
        },
        perproduk: function(produk_id){
            $('.perproduk').DataTable({
                ajax: "http://localhost:8000/api/gbj/stok/perproduk/"+produk_id,
                columns: [
                    {
                        data: "DT_RowIndex",
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: "tanggal",
                        name: "tanggal"
                    },
                    {
                        data: "divisi_id",
                        name: "divisi_id"
                    },
                    {
                        data: "keterangan",
                        name: "keterangan"
                    },
                    {
                        data: null,
                        render: function (data) {
                            if(data.jumlah_masuk > 0)
                            {
                                return '<span style="color:green;"><i class="fas fa-plus"></i><span class="float-right">'+data.jumlah_masuk+'</span></span>'
                            }
                            else if(data.jumlah_keluar > 0)
                            {
                                return '<span style="color:red;"><i class="fas fa-minus"></i><span class="float-right">'+data.jumlah_keluar+'</span></span>'
                            }
                        },
                    },
                    {
                        data: null,
                        render: function(data){
                            return `<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <router-link :to="{ path: '/gbj/stok/no_seri/`+data.id+`' }"><button class="dropdown-item" type="button"><i class="fas fa-search"></i> No Seri</button></router-link>
                                <router-link :to="{ path: '/gbj/stok/mutasi/`+data.id+`' }"><button class="dropdown-item" type="button"><i class="fas fa-search"></i> Mutasi</button></router-link>
                            </div>`
                        },
                        orderable:false, 
                        searchable:false
                    }
                ],
            });
        },
        pertanggal: function(tanggal){
            $('.perproduk').DataTable({
                ajax: "http://localhost:8000/api/gbj/stok/pertanggal/"+tanggal,
                columns: [
                    {
                        data: "DT_RowIndex",
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: "tanggal",
                        name: "tanggal"
                    },
                    {
                        data: "nama_produk",
                        name: "nama_produk"
                    },
                    {
                        data: "divisi_id",
                        name: "divisi_id"
                    },
                    {
                        data: "keterangan",
                        name: "keterangan"
                    },
                    {
                        data: null,
                        render: function (data) {
                            if(data.jumlah_masuk > 0)
                            {
                                return '<span style="color:green;"><i class="fas fa-plus"></i><span class="float-right">'+data.jumlah_masuk+'</span></span>'
                            }
                            else if(data.jumlah_keluar > 0)
                            {
                                return '<span style="color:red;"><i class="fas fa-minus"></i><span class="float-right">'+data.jumlah_keluar+'</span></span>'
                            }
                        },
                    },
                    {
                        data: null,
                        render: function(data){
                            return `<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <router-link :to="{ path: '/gbj/stok/no_seri/`+data.id+`' }"><button class="dropdown-item" type="button"><i class="fas fa-search"></i> No Seri</button></router-link>
                                <router-link :to="{ path: '/gbj/stok/mutasi/`+data.id+`' }"><button class="dropdown-item" type="button"><i class="fas fa-search"></i> Mutasi</button></router-link>
                            </div>`
                        },
                        orderable:false, 
                        searchable:false
                    }
                ],
            });
        },
        changeProduk: function(produk_id){
            axios.get(`http://localhost:8000/api/gbj/stok/`+produk_id).then((res) => {
                $('#nama_produk').value(res.nama_produk)
                $('#kelompok_produk').value(res.kelompok_produk)

            });
        }
    },
    mounted(){}
}
</script>

<style lang="scss">
thead{ text-align: center; }
.semuaproduk { 
    td:nth-child(1), td:nth-child(2), td:nth-child(3), td:nth-child(5), td:nth-child(7), td:nth-child(8) { 
        text-align: center; 
    }
}

.perproduk { 
    td:nth-child(1), td:nth-child(2), td:nth-child(3), td:nth-child(6) { 
        text-align: center; 
    }
}

.pertanggal { 
    td:nth-child(1), td:nth-child(2), td:nth-child(4), td:nth-child(7) { 
        text-align: center; 
    }
}

.list-group li{
  border: 0;
}

.center{width:80%; margin-left:auto;margin-right:auto;}
</style>

