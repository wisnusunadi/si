@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
<style>
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

    .active {
        box-shadow: 12px 4px 8px 0 rgba(0, 0, 0, 0.2), 12px 6px 20px 0 rgba(0, 0, 0, 0.19);
    }
    .dataTables_filter {
        display: none;
    }
</style>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Produk Gudang Karantina
                </h1>

            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>

<div class="ml-3">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <nav>
                        <div class="nav nav-tabs topnav" id="nav-tab" role="tablist">
                            {{-- <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                                aria-controls="home" aria-selected="true">Sparepart</a> --}}
                            <a id="profile-tab active" data-toggle="tab" href="#profile" role="tab" aria-controls="profile"
                                aria-selected="false">Unit</a>
                        </div>
                    </nav>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fab fa-whmcs"></i> Sparepart Karantina</h3>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <input type="text" class="form-control" placeholder="Cari..." id="search-sparepart">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex justify-content-end"><a href="{{ route('gk.export-produk') }}" class="btn btn-outline-success"><i class="far fa-file-excel"></i> Export</a></div>
                                </div>
                            </div>
                            <table class="table tableSparepart">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Kode Sparepart</th>
                                        <th>Nama</th>
                                        {{-- <th>Unit</th> --}}
                                        <th>Jumlah</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-tools"></i> Unit Karantina</h3>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <input type="text" class="form-control" placeholder="Cari..." id="search-unit">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex justify-content-end"><a href="{{ route('gk.export-unit') }}" class="btn btn-outline-success"><i class="far fa-file-excel"></i> Export</a></div>
                                </div>
                            </div>
                            <table class="table tableUnit">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Kode Unit</th>
                                        <th>Merk</th>
                                        <th>Nama</th>
                                        <th>Jumlah</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                               <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('adminlte_js')
<script>
    var access_token = localStorage.getItem('lokal_token');
    if (access_token == null) {
        Swal.fire({
            title: 'Session Expired',
            text: 'Silahkan login kembali',
            icon: 'warning',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                event.preventDefault();
                document.getElementById('logout-form').submit();
            }
        })
    }
    // var tableSparepart = $('.tableSparepart').dataTable({
    //     destroy: true,
    //     "paging": true,
    //     lengthChange: false,
    //     "ordering": true,
    //     "info": true,
    //     "autoWidth": false,
    //     "responsive": true,
    //     bSortClasses: true,
    //     bDeferRender: true,
    //     processing: true,
    //     ajax: {
    //         url: "/api/spr/data",
    //     },
    //     columns: [
    //         {data: 'kode'},
    //         {data: 'produk'},
    //         // {data: 'unit'},
    //         {data: 'jml'},
    //         {data: 'button'},
    //     ],
    //     "language": {
    //         "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
    //     }
    // });
    $('#search-sparepart').on('keyup', function () {
        tableSparepart.api().search(this.value).draw();
    });
    var tableUnit = $('.tableUnit').dataTable({
        destroy: true,
        "paging": true,
        lengthChange: false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        processing: true,
        bSortClasses: true,
        bDeferRender: true,
        ajax: {
            url: "/api/gk/unit",
            beforeSend : function(xhr){
                xhr.setRequestHeader('Authorization', 'Bearer ' + access_token);
            }
        },
        columns: [
            {data: 'kode_produk'},
            {data: 'merk'},
            {data: 'nama_produk'},
            {data: 'jumlah'},
            {data: 'button'},
        ],
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
        }
    });
    $('#search-unit').on('keyup', function () {
        tableUnit.api().search(this.value).draw();
    });

    function sparepartDetail() {
        $('.sparepartDetail').modal('show');
    }

    function unitDetail() {
        $('.unitDetail').modal('show');
    }

</script>
@stop
