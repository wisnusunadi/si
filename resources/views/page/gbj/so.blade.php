@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
<style>
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

</style>
<input type="hidden" name="" id="auth" value="{{ Auth::user()->divisi_id }}">
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Daftar Sales Order</h1>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="col-lg-12">
                <!-- Card -->
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-pills mb-5" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="pills-proses_kirim-tab" data-toggle="pill" href="#pills-proses_kirim" role="tab" aria-controls="pills-proses_kirim" aria-selected="true">Proses Dicek</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-selesai_kirim-tab" data-toggle="pill" href="#pills-selesai_kirim" role="tab" aria-controls="pills-selesai_kirim" aria-selected="false">Sudah Dicek</a>
                            </li>
                            {{-- <li class="nav-item">
                                <a class="nav-link" id="pills-batal-tab" data-toggle="pill" href="#pills-batal" role="tab" aria-controls="pills-selesai_kirim" aria-selected="false">Batal</a>
                            </li> --}}
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-proses_kirim" role="tabpanel" aria-labelledby="pills-proses_kirim-tab">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered" style="width: 100%" id="belum-dicek">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nomor SO</th>
                                                        <th>Nomor PO</th>
                                                        <th>Customer</th>
                                                        <th>Batas Transfer</th>
                                                        <th>Status</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show" id="pills-selesai_kirim" role="tabpanel" aria-labelledby="pills-selesai_kirim-tab">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered" style="width: 100%" id="sudah-dicek">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nomor SO</th>
                                                        <th>Nomor PO</th>
                                                        <th>Customer</th>
                                                        <th>Batas Transfer</th>
                                                        <th>Status</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                            </table>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="tab-pane fade show" id="pills-batal" role="tabpanel" aria-labelledby="pills-batal-tab">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered" style="width: 100%" id="batal-table">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nomor SO</th>
                                                        <th>Nomor PO</th>
                                                        <th>Customer</th>
                                                        <th>Batas Transfer</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                            </table>

                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addProdukModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
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
                    <form action="" method="post">
                        <input type="hidden" name="pesanan_id" id="ids">
                        <input type="hidden" name="userid" id="userid" value="{{ Auth::user()->id }}">
                        <div class="card">
                            <div class="card-header">
                                <div class="row row-cols-2">
                                    {{-- col --}}
                                    <div class="col"> <label for="">Nomor SO</label>
                                        <div class="card nomor-so">
                                            <div class="card-body">
                                                <span id="soo"></span>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- col --}}
                                    <div class="col"> <label for="">Nomor AKN</label>
                                        <div class="card nomor-akn">
                                            <div class="card-body">
                                                <span id="aknn"></span>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- col --}}
                                    <div class="col"> <label for="">Nomor PO</label>
                                        <div class="card nomor-po">
                                            <div class="card-body">
                                                <span id="poo"></span>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- col --}}
                                    <div class="col"> <label for="">Customer</label>
                                        <div class="card instansi">
                                            <div class="card-body">
                                                <span id="instansii"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped add-produk">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>ID</th>
                                            <th><input type="checkbox" name="" id="head-cb-so"></th>
                                            <th>Produk</th>
                                            <th>Jumlah</th>
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
                {{-- Tambahkan DataTable --}}

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                <button type="button" class="btn btn-primary" id="btnSave">Simpan</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="viewProdukModal" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
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
                                                <span id="so"></span>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- col --}}
                                    <div class="col"> <label for="">Nomor AKN</label>
                                        <div class="card nomor-akn">
                                            <div class="card-body">
                                                <span id="akn"></span>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- col --}}
                                    <div class="col"> <label for="">Nomor PO</label>
                                        <div class="card nomor-po">
                                            <div class="card-body">
                                                <span id="po"></span>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- col --}}
                                    <div class="col"> <label for="">Instansi</label>
                                        <div class="card instansi">
                                            <div class="card-body">
                                                <span id="instansi"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped" id="view-produk">
                                    <thead>
                                        <tr>
                                            <th>Paket</th>
                                            <th>Paket</th>
                                            <th>Produk</th>
                                            <th>Jumlah</th>
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
    $(document).ready(function () {
        $('#head-cb-so').prop('checked', false);
        $('.addProduk').click(function (e) {
            $('#addProdukModal').modal('show');
        });
        $('.viewProduk').click(function (e) {
            $('#viewProdukModal').modal('show');
        });

        $("#head-cb-so").on('click', function () {
            var isChecked = $("#head-cb-so").prop('checked')
            $('.cb-child-so').prop('checked', isChecked)
        });

        $('#belum-dicek').DataTable({
            destroy: true,
            processing: true,
            serverSide: false,
            ajax: {
                url: '/api/tfp/belum-dicek',
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'so', name: 'so'},
                {data: 'po'},
                { data: 'nama_customer', name: 'nama_customer'},
                { data: 'batas_out', name: 'batas_out'},
                {data: 'logs'},
                { data: 'action', name: 'action'},
            ],
            "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
        },
            "columnDefs": [
                {
                    "targets": [6],
                    "visible": document.getElementById('auth').value == '2' ? false : true,
                    "width": "20%",
                },
                { "width": "10%", "targets": 5 }
            ]
        });

        $('#sudah-dicek').DataTable({
            destroy: true,
            processing: true,
            serverSide: false,
            ajax: {
                url: '/api/tfp/sudah-dicek',
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'so', name: 'so'},
                {data: 'po'},
                { data: 'nama_customer', name: 'nama_customer'},
                { data: 'batas_out', name: 'batas_out'},
                {data: 'logs'},
                { data: 'action', name: 'action'},
            ],
            "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
        },
            "columnDefs": [
                {
                    "targets": [6],
                    "visible": document.getElementById('auth').value == '2' ? false : true,
                    "width": "20%",
                },
                { "width": "10%", "targets": 5 }
            ]
        });

        $('#batal-table').DataTable({
            destroy: true,
            processing: true,
            serverSide: false,
            "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            },
        })
    });

    var id = '';
    $(document).on('click', '.editmodal', function(e) {
        var x = $(this).data('value');
        console.log(x);
        id = $(this).data('id');
        console.log(id);
        $.ajax({
            url: "/api/tfp/header-so/" +id+"/"+x,
            success: function(res) {
                console.log(res);
                $('span#soo').text(res.so);
                $('span#poo').text(res.po);
                $('span#aknn').text(res.akn);
                $('span#instansii').text(res.customer);
            }
        });

        var table = $('.add-produk').DataTable({
            destroy: true,
            autoWidth: false,
            bPaginate: false,
            "scrollY": 300,
            ajax: {
                url: "/api/tfp/detail-so/" +id+"/"+x
            },
            "columns": [
                { "data": "detail_pesanan_id" },
                { "data": "paket" },
                { "data": "ids" },
                { "data": "produk" },
                { "data": "qty" },
                { "data": "status" },
            ],
            "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last=null;

            api.column(0, {page:'current'} ).data().each( function ( group, i ) {

                if (last !== group) {
                    var rowData = api.row(i).data();
                    $(rows).eq(i).before(
                    '<tr class="table-dark text-bold"><td style="display:none;">'+group+'</td><td colspan="4">' + rowData.paket + '</td></tr>'
                );
                    last = group;
                }
            });
        },
            "columnDefs":[
                {"targets": [0], "visible": false},
                {"targets": [1], "visible": false},
            ],
            "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
        });
        // tab.column(2).data().sum();
        $('#addProdukModal').modal('show');
    })
    let so = {};
    let so_dpp = {};
    $(document).on('click', '#btnSave', function(e) {
            e.preventDefault();
            let ids = {};
            let dpp_id = [];

            $('.cb-child-so').each(function() {
                if ($(this).is(":checked")) {
                    // so_dpp.gbj = ids;
                    if (ids[$(this).val()] === undefined){
                        ids[$(this).val()] = [];
                        ids[$(this).val()].push($(this).next().val())
                    }
                    else {
                        ids[$(this).val()].push($(this).next().val())
                    }
                }
            })

            Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, save it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).prop('disabled', true);
                    Swal.fire({
                        title: 'Please wait',
                        text: 'Data is transferring...',
                        allowOutsideClick: false,
                        showConfirmButton: false
                    });
                    Swal.fire(
                    'Sukses!',
                    'Data Berhasil Disimpan',
                    'success'
                    )
                    $.ajax({
                        url: "/api/so/cek",
                        type: "post",
                        data: {
                            pesanan_id : id,
                            userid: $('#userid').val(),
                            data: ids,
                        },
                        success: function(res) {
                            location.reload();
                            console.log(res);
                        }
                    })
                }
            })

        })

    $(document).on('click', '.detailmodal', function(e) {
        var x = $(this).data('value');
        console.log(x);
        var id = $(this).data('id');

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
        console.log(id+ " " +x);
        var table = $('#view-produk').DataTable({
            destroy: true,
            processing: true,
            autoWidth: false,
            bPaginate: false,
            scrollY: 300,
            ajax: {
                url: "/api/tfp/detail-so/" +id+"/"+x,
                // url: "/api/testingJson",
            },
            columns: [
                {data: 'detail_pesanan_id'},
                { data: 'paket' },
                { data: 'produk' },
                { data: 'qty' },
                { data: 'status' },
            ],
            "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last=null;

            api.column(0, {page:'current'} ).data().each( function ( group, i ) {

                if (last !== group) {
                    var rowData = api.row(i).data();

                    $(rows).eq(i).before(
                    '<tr class="table-dark text-bold"><td style="display:none;">'+group+'</td><td colspan="3">' + rowData.paket + '</td></tr>'
                );
                    last = group;
                }
            });
        },
        "columnDefs":[
                {"targets": [0], "visible": false},
                {"targets": [1], "visible": false},
            ],
            "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
        }
        })
        $('#viewProdukModal').modal('show');
    })

</script>
@stop
