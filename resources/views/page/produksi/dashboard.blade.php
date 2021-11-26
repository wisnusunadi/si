@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
<style>
        .active {
        box-shadow: 12px 4px 8px 0 rgba(0, 0, 0, 0.2), 12px 6px 20px 0 rgba(0, 0, 0, 0.19);
    }
    .hidden {
        display: none !important;
    }

    .otg:hover {
        box-shadow: 12px 4px 8px 0 rgba(0, 0, 0, 0.2), 12px 6px 20px 0 rgba(0, 0, 0, 0.19);
    }
    .nomor-so{
        background-color: #717FE1;
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 18px
    }
    .nomor-akn{
        background-color: #DF7458;
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 18px
    }
    .nomor-po{
        background-color: #85D296;
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 18px
    }
    .instansi{
        background-color: #36425E;
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 18px
    }
    body{
        font-size: 14px;
    }
</style>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard</h1>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="far fa-paper-plane mr-1"></i>
                            Batas Waktu Pengiriman SO
                        </h5>
                    </div>
                    <div class="card-body">
                       <div class="row">
                            <div class="col-6 col-md-4">
                                <div id="transferoneday" class="card active otg" style="background-color: #E6EFFA">
                                    <div class="card-body text-center">
                                        <h4 id="m1">10</h4>
                                        <p class="card-text">Produk Mendekati Batas Pengiriman Kurang Dari 10 Hari</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div id="transfertwoday" class="card otg" style="background-color: #FEF7EA">
                                    <div class="card-body text-center">
                                        <h4 id="m2">50</h4>
                                        <p class="card-text">Produk Mendekati Batas Pengiriman Kurang Dari 5 Hari</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div id="transferthreeday" class="card otg" style="background-color: #FCEDE9">
                                    <div class="card-body text-center">
                                        <h4 id="m3">60</h4>
                                        <p class="card-text">Produk Melewati Batas Pengiriman</p>
                                    </div>
                                </div>
                            </div>
                       </div>

                       <div class="transferonedaytable">
                        <table class="table table-produk-batas-transfer-one-day">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nomor SO</th>
                                    <th>Customer</th>
                                    <th>Batas Pengiriman</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- <tr>
                                    <td scope="row">1</td>
                                    <td>654654654654</td>
                                    <td>Rumkital Dr. Ramelan</td>
                                    <td>18-06-2021 </td>
                                    <td><button onclick="modalSO()" class="btn btn-outline-primary"><i
                                        class="fas fa-paper-plane"></i></button></td>
                                </tr> --}}
                            </tbody>
                        </table>
                    </div>

                    <div class="transfertwodaytable hidden">
                        <table class="table table-produk-batas-transfer-two-day">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nomor SO</th>
                                    <th>Customer</th>
                                    <th>Batas Pengiriman</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td scope="row">1</td>
                                    <td>654654654654</td>
                                    <td>Rumkital Dr. Ramelan</td>
                                    <td>18-06-2021 </td>
                                    <td><button onclick="modalSO()" class="btn btn-outline-primary"><i
                                        class="fas fa-paper-plane"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="transferthreedaytable hidden">
                        <table class="table table-produk-batas-transfer-three-day">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nomor SO</th>
                                    <th>Customer</th>
                                    <th>Batas Pengiriman</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td scope="row">1</td>
                                    <td>654654654654</td>
                                    <td>Rumkital Dr. Ramelan</td>
                                    <td>18-06-2021 <br> <span class="badge badge-danger">Lebih dari 5 hari</span></td>
                                    <td><button onclick="modalSO()" class="btn btn-outline-primary"><i
                                                class="fas fa-paper-plane"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-cogs mr-1"></i>
                            Perakitan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 col-md-4">
                                <div id="bataswaktupenyerahan" class="card active otg" style="background-color: #E6EFFA">
                                    <div class="card-body text-center">
                                        <h4 id="m4">10</h4>
                                        <p class="card-text">Produk Mendekati Batas Waktu Penyerahan ke GBJ</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div id="bataswaktuperakitan" class="card otg" style="background-color: #FEF7EA">
                                    <div class="card-body text-center">
                                        <h4 id="m5">50</h4>
                                        <p class="card-text">Produk Mendekati Batas Waktu Perakitan</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div id="perubahanperakitan" class="card otg" style="background-color: #FCEDE9">
                                    <div class="card-body text-center">
                                        <h4 id="m6">60</h4>
                                        <p class="card-text">Produk Mengalami Perubahan Jadwal Perakitan</p>
                                    </div>
                                </div>
                            </div>
                       </div>
                       <div class="produkGbj">
                        <table class="table table-produk-gbj">
                            <thead>
                                <tr>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Nomor BPPB</th>
                                    <th>Produk</th>
                                    <th>Jumlah</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td scope="row">10-05-2021</td>
                                    <td>10-06-2021 <br> <span class="badge badge-info">Kurang 10 Hari</span></td>
                                    <td>3513654365456</td>
                                    <td>Produk 1</td>
                                    <td>100 Unit</td>
                                    <td><a href="{{ url('produksi/jadwal_perakitan') }}" class="btn btn-outline-primary"><i
                                     class="fas fa-paper-plane"></i></a></td>
                                </tr>
                                <tr>
                                    <td scope="row">10-05-2021</td>
                                    <td>10-06-2021 <br> <span class="badge badge-warning">Kurang 5 Hari</span></td>
                                    <td>3513654365456</td>
                                    <td>Produk 1</td>
                                    <td>100 Unit</td>
                                    <td><a href="{{ url('produksi/pengiriman') }}" class="btn btn-outline-primary"><i
                                     class="fas fa-paper-plane"></i></a></td>
                                </tr>
                                <tr>
                                    <td scope="row">10-05-2021</td>
                                    <td>10-06-2021 <br> <span class="badge badge-danger">Kurang 1 Hari</span></td>
                                    <td>3513654365456</td>
                                    <td>Produk 1</td>
                                    <td>100 Unit</td>
                                    <td><a href="{{ url('produksi/jadwal_perakitan') }}" class="btn btn-outline-primary"><i
                                     class="fas fa-paper-plane"></i></a></td>
                                </tr>
                            </tbody>
                        </table>
                       </div>
                       <div class="produkPerakitan hidden">
                           <table class="table table-waktu-perakitan">
                            <thead>
                                <tr>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Nomor BPPB</th>
                                    <th>Produk</th>
                                    <th>Jumlah</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td scope="row">10-05-2021</td>
                                    <td>10-06-2021 <br> <span class="badge badge-info">Kurang 10 Hari</span></td>
                                    <td>3513654365456</td>
                                    <td>Produk 2</td>
                                    <td>100 Unit</td>
                                    <td><a href="{{ url('produksi/jadwal_perakitan') }}" class="btn btn-outline-primary"><i
                                     class="fas fa-paper-plane"></i></a></td>
                                </tr>
                                <tr>
                                    <td scope="row">10-05-2021</td>
                                    <td>10-06-2021 <br> <span class="badge badge-warning">Kurang 5 Hari</span></td>
                                    <td>64586545464654</td>
                                    <td>Produk 2</td>
                                    <td>100 Unit</td>
                                    <td><a href="{{ url('produksi/jadwal_perakitan') }}" class="btn btn-outline-primary"><i
                                     class="fas fa-paper-plane"></i></a></td>
                                </tr>
                                <tr>
                                    <td scope="row">10-05-2021</td>
                                    <td>10-06-2021 <br> <span class="badge badge-danger">Kurang 1 Hari</span></td>
                                    <td>985654564654654</td>
                                    <td>Produk 2</td>
                                    <td>100 Unit</td>
                                    <td><a href="{{ url('produksi/jadwal_perakitan') }}" class="btn btn-outline-primary"><i
                                     class="fas fa-paper-plane"></i></a></td>
                                </tr>
                            </tbody>
                           </table>
                       </div>
                       <div class="perubahanPerakitan hidden">
                           <table class="table table-perubahan-perakitan">
                            <thead>
                                <tr>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Nomor BPPB</th>
                                    <th>Produk</th>
                                    <th>Jumlah</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td scope="row">10-05-2021</td>
                                    <td>10-06-2021 <br> <span class="badge badge-info">09-06-2021</span></td>
                                    <td>654351351553541354</td>
                                    <td>Produk 1</td>
                                    <td>100 Unit</td>
                                    <td><a href="{{ url('produksi/jadwal_perakitan') }}" class="btn btn-outline-primary"><i
                                     class="fas fa-paper-plane"></i></a></td>
                                </tr>
                                <tr>
                                    <td scope="row">10-05-2021 <br> <span class="badge badge-info">11-06-2021</span></td>
                                    <td>10-06-2021</td>
                                    <td>354354354321564</td>
                                    <td>Produk 1</td>
                                    <td>100 Unit</td>
                                    <td><a href="{{ url('produksi/jadwal_perakitan') }}" class="btn btn-outline-primary"><i
                                     class="fas fa-paper-plane"></i></a></td>
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

<!-- Modal -->
<div class="modal fade showDetail" id="" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            {{-- col --}}
                            <div class="col"> <label for="">Nama Produk</label>
                                <div class="card nomor-so">
                                    <div class="card-body">
                                        Ambulatory Blood Pressure Monitor
                                    </div>
                                </div>
                            </div>
                            {{-- col --}}
                            <div class="col"> <label for="">Jumlah</label>
                                <div class="card nomor-akn">
                                    <div class="card-body">
                                        100 Unit
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped scan-produk">
                            <thead>
                                <tr>
                                    <th>Nomor Seri</th>
                                    <th>Nomor Seri</th>
                                    <th>Nomor Seri</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>846464654654</td>
                                    <td>654654654654</td>
                                    <td>957489688845</td>
                                </tr>
                                <tr>
                                    <td>846464654654</td>
                                    <td>654654654654</td>
                                    <td>957489688845</td>
                                </tr>
                                <tr>
                                    <td>846464654654</td>
                                    <td>654654654654</td>
                                    <td>957489688845</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal SO --}}
<div class="modal fade viewProdukModal" id="" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row row-cols-2">
                                    {{-- col --}}
                                    <div class="col"> <label for="">Nomor SO</label>
                                        <div class="card nomor-so">
                                            <div class="card-body">
                                                <span id="so">89798797856456</span>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- col --}}
                                    <div class="col"> <label for="">Nomor AKN</label>
                                        <div class="card nomor-akn">
                                            <div class="card-body">
                                                <span id="akn">89798797856456</span>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- col --}}
                                    <div class="col"> <label for="">Nomor PO</label>
                                        <div class="card nomor-po">
                                            <div class="card-body">
                                                <span id="po">89798797856456</span>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- col --}}
                                    <div class="col"> <label for="">Customer</label>
                                        <div class="card instansi">
                                            <div class="card-body">
                                                <span id="instansi">RS. Dr. Soetomo</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped" id="view-produk">
                                    <thead>
                                        <tr>
                                            <th>Nama Produk</th>
                                            <th>Jumlah</th>
                                            {{-- <th>Tipe</th> --}}
                                            <th>Merk</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>

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
@stop

@section('adminlte_js')
<script>
    // sale
    $.ajax({
        url: "/api/prd/minus5/h",
        type: "post",
        success: function(res) {
            console.log(res);
            $('h4#m1').text(res);
        }
    })

    $('.table-produk-batas-transfer-two-day').DataTable({
        destroy: true,
        "paging": true,
        "lengthChange": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        processing: true,
        serverSide: true,
        ajax: {
            url: '/api/prd/minus5',
            type: "post",
        },
        columns: [
            {data: 'DT_RowIndex'},
            {data: 'so'},
            {data: 'nama_customer'},
            {data: 'batas_out'},
            {data: 'button'},
        ],
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
        }
    });

    $(document).on('click', '.minus5', function() {
        var x = $(this).data('value');
        console.log(x);
        var id = $(this).data('id');
        console.log(id);

        $.ajax({
            url: "/api/tfp/header-so/" +id+"/"+x,
            success: function(res) {
                console.log(res);
                $('span#so').text(res.so);
                $('span#po').text(res.po);
                $('span#akn').text(res.akn);
                $('span#instansi').text(res.customer);
            }
        });

        $('#view-produk').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "/api/tfp/detail-so/" +id+"/"+x,
            },
            columns: [
                { data: 'produk', name: 'produk'},
                { data: 'qty', name: 'qty'},
                { data: 'merk', name: 'merk'},
                { data: 'status_prd', name: 'status_prd'},
            ],
            "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
                }
        });

        modalSO();
    })


    $.ajax({
        url: "/api/prd/minus10/h",
        type: "post",
        success: function(res) {
            console.log(res);
            $('h4#m2').text(res);
        }
    })

    $('.table-produk-batas-transfer-one-day').DataTable({
        destroy: true,
        "paging": true,
        "lengthChange": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        processing: true,
        serverSide: true,
        ajax: {
            url: '/api/prd/minus10',
            type: "post",
        },
        columns: [
            {data: 'DT_RowIndex'},
            {data: 'so'},
            {data: 'nama_customer'},
            {data: 'batas_out'},
            {data: 'button'},
        ],
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
        }
    });

    $(document).on('click', '.minus10', function() {
        var x = $(this).data('value');
        console.log(x);
        var id = $(this).data('id');
        console.log(id);

        $.ajax({
            url: "/api/tfp/header-so/" +id+"/"+x,
            success: function(res) {
                console.log(res);
                $('span#so').text(res.so);
                $('span#po').text(res.po);
                $('span#akn').text(res.akn);
                $('span#instansi').text(res.customer);
            }
        });

        $('#view-produk').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "/api/tfp/detail-so/" +id+"/"+x,
            },
            columns: [
                { data: 'produk', name: 'produk'},
                { data: 'qty', name: 'qty'},
                { data: 'merk', name: 'merk'},
                { data: 'status_prd', name: 'status_prd'},
            ],
            "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
                }
        });

        modalSO();
    })

    $.ajax({
        url: "/api/prd/exp/h",
        type: "post",
        success: function(res) {
            console.log(res);
            $('h4#m3').text(res);
        }
    })

    $('.table-produk-batas-transfer-three-day').DataTable({
        destroy: true,
        "paging": true,
        "lengthChange": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        processing: true,
        serverSide: true,
        ajax: {
            url: '/api/prd/exp',
            type: "post",
        },
        columns: [
            {data: 'DT_RowIndex'},
            {data: 'so'},
            {data: 'nama_customer'},
            {data: 'batas_out'},
            {data: 'button'},
        ],
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
        }
    });

    $(document).on('click', '.expired', function() {
        var x = $(this).data('value');
        console.log(x);
        var id = $(this).data('id');
        console.log(id);

        $.ajax({
            url: "/api/tfp/header-so/" +id+"/"+x,
            success: function(res) {
                console.log(res);
                $('span#so').text(res.so);
                $('span#po').text(res.po);
                $('span#akn').text(res.akn);
                $('span#instansi').text(res.customer);
            }
        });

        $('#view-produk').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "/api/tfp/detail-so/" +id+"/"+x,
            },
            columns: [
                { data: 'produk', name: 'produk'},
                { data: 'qty', name: 'qty'},
                { data: 'merk', name: 'merk'},
                { data: 'status_prd', name: 'status_prd'},
            ],
            "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
                }
        });

        modalSO();
    })

    // rakit
    $.ajax({
        url: "/api/prd/exp_rakit/h",
        type: "post",
        success: function(res) {
            console.log(res);
            $('h4#m5').text(res);
        }
    })

    $('.table-waktu-perakitan').DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "/api/prd/exp_rakit",
            type: "post",
        },
        columns: [
            {data: 'start'},
            {data: 'end'},
            {data: 'no_bppb'},
            {data: 'produk'},
            {data: 'jml'},
            {data: 'button'}
        ],
            "ordering":false,
            "autoWidth": false,
            "lengthChange": false,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
    });

    $(document).on('click','#bataswaktupenyerahan', function () {
        $('#bataswaktupenyerahan').addClass('active');
        $('#bataswaktuperakitan').removeClass('active');
        $('#perubahanperakitan').removeClass('active');
        $('.produkGbj').removeClass('hidden');
        $('.produkPerakitan').addClass('hidden');
        $('.perubahanPerakitan').addClass('hidden');
    });

    $(document).on('click','#bataswaktuperakitan', function () {
        $('#bataswaktuperakitan').addClass('active');
        $('#bataswaktupenyerahan').removeClass('active');
        $('#perubahanperakitan').removeClass('active');
        $('.produkPerakitan').removeClass('hidden');
        $('.produkGbj').addClass('hidden');
        $('.perubahanPerakitan').addClass('hidden');
    });

    $(document).on('click','#perubahanperakitan', function () {
        $('#perubahanperakitan').addClass('active');
        $('#bataswaktupenyerahan').removeClass('active');
        $('#bataswaktuperakitan').removeClass('active');
        $('.perubahanPerakitan').removeClass('hidden');
        $('.produkGbj').addClass('hidden');
        $('.produkPerakitan').addClass('hidden');
    });

        $(document).on('click', '#transferoneday', function () {
            $('#transferoneday').addClass('active');
            $('.transferonedaytable').removeClass('hidden');
            $('#transfertwoday').removeClass('active');
            $('#transferthreeday').removeClass('active');
            $('.transfertwodaytable').addClass('hidden');
            $('.transferthreedaytable').addClass('hidden');
        })
        $(document).on('click', '#transfertwoday', function () {
            $('#transfertwoday').addClass('active');
            $('.transfertwodaytable').removeClass('hidden');
            $('#transferoneday').removeClass('active');
            $('#transferthreeday').removeClass('active');
            $('.transferonedaytable').addClass('hidden');
            $('.transferthreedaytable').addClass('hidden');
        })
        $(document).on('click', '#transferthreeday', function () {
            $('#transferthreeday').addClass('active');
            $('.transferthreedaytable').removeClass('hidden');
            $('#transfertwoday').removeClass('active');
            $('#transferoneday').removeClass('active');
            $('.transfertwodaytable').addClass('hidden');
            $('.transferonedaytable').addClass('hidden');
        });

        function showDetail() {
            $('.showDetail').modal('show');
        }
        $('.scan-produk').DataTable({
            "ordering":false,
            "autoWidth": false,
            "lengthChange": false,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
    });
    $('.table-produk-gbj').DataTable({
            "ordering":false,
            "autoWidth": false,
            "lengthChange": false,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
    });

    $('.table-perubahan-perakitan').DataTable({
            "ordering":false,
            "autoWidth": false,
            "lengthChange": false,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
    });
    function modalSO() {
        $('.viewProdukModal').modal('show');
    }
</script>
@stop
