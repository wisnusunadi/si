@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro' rel='stylesheet' type='text/css'>
<style>
    .foo {
        float: left;
        width: 20px;
        height: 20px;
        margin: 5px;
        border: 1px solid rgba(0, 0, 0, .2);
    }

    .green {
        background: #28A745;
    }

    .blue {
        background: #17A2B8;
    }

    .topnav a {
        float: left;
        display: block;
        color: black;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
        font-size: 17px;
        border-bottom: 3px solid transparent;
    }

    .topnav a:hover {
        border-bottom: 3px solid red;
    }

    .topnav a.active {
        border-bottom: 3px solid red;
    }

    .active-link {
        box-shadow: 12px 4px 8px 0 rgba(0, 0, 0, 0.2), 12px 6px 20px 0 rgba(0, 0, 0, 0.19);
    }

    .nav-border {
        border-bottom: 2px solid black;
        content: "";
    }

    section {
        font-family: "Source Sans Pro"
    }
    img{
        /* Jika Gambar Disamping */
        width: 330px;
        /* Jika Gambar Diatas */
        /* width: 100px; */
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-5">
                @foreach($data as $d)
                <input type="hidden" name="id" id="ids" value="{{ $d->id }}">
                <div class="card" style="width: 40rem">
                    <div class="row no-gutters">
                      <div class="col-md-5">
                        <img src="https://elitech.id/img/products-img/cardiology/ecg300g/gambara.jpg" alt="...">
                      </div>
                      <div class="col-md-1"></div>
                      <div class="col-md-6">
                        <div class="card-body">
                                  <h5 class="card-title text-bold">{{ $d->produk->nama }} {{ $d->nama }}</h5>
                                  <p class="card-text"><small class="text-muted">{{ $d->produk->product->kode . '' . $d->produk->kode ? $d->produk->product->kode . '' . $d->produk->kode : '-' }}</small></p>
                                  <p class="card-text">Deskripsi</p>
                                    <p class="card-text">{{ $d->deskripsi }}</p>
                                    <p class="card-text">Dimensi</p>
                                    <p class="card-text">Panjang x Lebar x Tinggi</p>
                                    <p class="card-text">{{ $d->dim_p }} x {{ $d->dim_l }} x {{ $d->dim_t }}</p>
                        </div>
                      </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="col-xl-7">
                <div class="card">
                    <div class="card-title">
                        <div class="ml-3 mr-3">
                            <div class="row align-items-center">
                                <div class="col-lg-9 col-xl-8">
                                    <div class="row align-items-center">
                                        <div class="col-md-4">
                                            <div class="input-icon">
                                                <input type="text" class="form-control" placeholder="Cari..."
                                                    id="kt_datatable_search_query">
                                                <span>
                                                    <i class="flaticon2-search-1 text-muted"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex align-items-center">
                                                <label class="mr-3 mb-0 d-none d-md-block" for="">Tanggal</label>
                                                <input type="text" name="" id="tanggalmasuk" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <a href="#" class="btn btn-outline-primary">Search</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-xl-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <p class="card-text">Keterangan Kolom <b>Dari/Ke:</b></p>
                                            <p class="card-text">
                                                <div class="foo green"></div> : Dari
                                            </p>
                                            <p class="card-text">
                                                <div class="foo blue"></div> : Ke
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-7">
                            <table class="table tableProdukView">
                                <thead>
                                    <tr>
                                        <th>Nomor SO</th>
                                        <th>Tanggal Masuk</th>
                                        <th>Tanggal Keluar</th>
                                        <th>Dari/Ke</th>
                                        <th>Tujuan</th>
                                        <th>Jumlah</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>652146416541654</td>
                                        <td scope="row">10-04-2021</td>
                                        <td>23-09-2021</td>
                                        <td><span class="badge badge-success">Divisi IT</span></td>
                                        <td>Untuk Uji Coba</td>
                                        <td>100 Unit</td>
                                        <td><button type="button" class="btn btn-outline-info"
                                                onclick="detailProduk()"><i class="far fa-eye"> Detail</i></button></td>
                                    </tr>
                                    <tr>
                                        <td>652146416541654</td>
                                        <td scope="row">10-04-2021</td>
                                        <td>23-09-2021</td>
                                        <td><span class="badge badge-info">Divisi QC</span></td>
                                        <td>Untuk Uji Coba</td>
                                        <td>100 Unit</td>
                                        <td><button type="button" class="btn btn-outline-info"
                                                onclick="detailProduk()"><i class="far fa-eye"> Detail</i></button></td>
                                    </tr>
                                    <tr>
                                        <td>652146416541654</td>
                                        <td scope="row">10-04-2021</td>
                                        <td>23-09-2021</td>
                                        <td><span class="badge badge-success">Divisi IT</span></td>
                                        <td>Untuk Uji Coba</td>
                                        <td>100 Unit</td>
                                        <td><button type="button" class="btn btn-outline-info"
                                                onclick="detailProduk()"><i class="far fa-eye"> Detail</i></button></td>
                                    </tr>
                                    <tr>
                                        <td>652146416541654</td>
                                        <td scope="row">10-04-2021</td>
                                        <td>23-09-2021</td>
                                        <td><span class="badge badge-info">Divisi QC</span></td>
                                        <td>Untuk Uji Coba</td>
                                        <td>100 Unit</td>
                                        <td><button type="button" class="btn btn-outline-info"
                                                onclick="detailProduk()"><i class="far fa-eye"> Detail</i></button></td>
                                    </tr>
                                    <tr>
                                        <td>652146416541654</td>
                                        <td scope="row">10-04-2021</td>
                                        <td>23-09-2021</td>
                                        <td><span class="badge badge-success">Divisi IT</span></td>
                                        <td>Untuk Uji Coba</td>
                                        <td>100 Unit</td>
                                        <td><button type="button" class="btn btn-outline-info"
                                                onclick="detailProduk()"><i class="far fa-eye"> Detail</i></button></td>
                                    </tr>
                                    <tr>
                                        <td>652146416541654</td>
                                        <td scope="row">10-04-2021</td>
                                        <td>23-09-2021</td>
                                        <td><span class="badge badge-info">Divisi QC</span></td>
                                        <td>Untuk Uji Coba</td>
                                        <td>100 Unit</td>
                                        <td><button type="button" class="btn btn-outline-info"
                                                onclick="detailProduk()"><i class="far fa-eye"> Detail</i></button></td>
                                    </tr>
                                    <tr>
                                        <td>652146416541654</td>
                                        <td scope="row">10-04-2021</td>
                                        <td>23-09-2021</td>
                                        <td><span class="badge badge-success">Divisi IT</span></td>
                                        <td>Untuk Uji Coba</td>
                                        <td>100 Unit</td>
                                        <td><button type="button" class="btn btn-outline-info"
                                                onclick="detailProduk()"><i class="far fa-eye"> Detail</i></button></td>
                                    </tr>
                                    <tr>
                                        <td>652146416541654</td>
                                        <td scope="row">10-04-2021</td>
                                        <td>23-09-2021</td>
                                        <td><span class="badge badge-info">Divisi QC</span></td>
                                        <td>Untuk Uji Coba</td>
                                        <td>100 Unit</td>
                                        <td><button type="button" class="btn btn-outline-info"
                                                onclick="detailProduk()"><i class="far fa-eye"> Detail</i></button></td>
                                    </tr>
                                    <tr>
                                        <td>652146416541654</td>
                                        <td scope="row">10-04-2021</td>
                                        <td>23-09-2021</td>
                                        <td><span class="badge badge-success">Divisi IT</span></td>
                                        <td>Untuk Uji Coba</td>
                                        <td>100 Unit</td>
                                        <td><button type="button" class="btn btn-outline-info"
                                                onclick="detailProduk()"><i class="far fa-eye"> Detail</i></button></td>
                                    </tr>
                                    <tr>
                                        <td>652146416541654</td>
                                        <td scope="row">10-04-2021</td>
                                        <td>23-09-2021</td>
                                        <td><span class="badge badge-info">Divisi QC</span></td>
                                        <td>Untuk Uji Coba</td>
                                        <td>100 Unit</td>
                                        <td><button type="button" class="btn btn-outline-info"
                                                onclick="detailProduk()"><i class="far fa-eye"> Detail</i></button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
{{-- Modal --}}
<div class="modal fade modalDetail" id="" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Produk Ambulatory</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-seri">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nomor Seri</th>
                            <th>Layout</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td scope="row">1</td>
                            <td>54131313151</td>
                            <td>Layout 1</td>
                        </tr>
                        <tr>
                            <td scope="row">2</td>
                            <td>54131313151</td>
                            <td>Layout 1</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop
@section('adminlte_js')
<script>

    // $('.tableProdukView').DataTable({
    //     searching: false,
    //     "lengthChange": false
    // });
    $('#nav-deskripsi-tab').click(function (e) {
        e.preventDefault();
        $('.is-active').addClass('font-weight-bold');
        $('.is-active').removeClass('font-weight-light');
        $('.is-disable').addClass('font-weight-light');
        $('.is-disable').removeClass('font-weight-bold');
    });
    $('#nav-dimensi-tab').click(function (e) {
        e.preventDefault();
        $('.is-active').removeClass('font-weight-bold');
        $('.is-active').addClass('font-weight-light');
        $('.is-disable').removeClass('font-weight-light');
        $('.is-disable').addClass('font-weight-bold');
    });
    $('#tanggalmasuk').daterangepicker({});

    function detailProduk() {
        $('.modalDetail').modal('show');
    }

    $(document).on('click', '.editmodal', function() {
        var id = $(this).data('id');
        console.log(id);
        $('.table-seri').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            ajax: {
                url: "/api/transaksi/history-detail-seri/" + id,
            },
            columns: [
                {data: 'DT_RowIndex'},
                {data: 'noser'},
                {data: 'posisi'},
            ]
        });
        detailProduk();
    })

    $(document).ready(function () {
        var id = $('#ids').val();
        console.log(id);
        $('.tableProdukView').DataTable().destroy();
        $('.tableProdukView').dataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            searching: false,
            "lengthChange": false,
            autoWidth: false,
            ajax: {
                url: "/api/transaksi/history-detail/" + id,
                // data: {id: id},
                // type: "post",
                // dataType: "json",
            },
            columns: [
                // { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'so', name: 'so'},
                { data: 'date_in', name: 'date_in'},
                { data: 'date_out', name: 'date_out'},
                { data: 'divisi', name: 'divisi'},
                { data: 'tujuan', name: 'tujuan'},
                { data: 'jumlah', name: 'jumlah'},
                { data: 'action', name: 'action'},
            ],
            "oLanguage": {
                "sSearch": "Cari:"
            }
        });

    })

</script>
@stop
