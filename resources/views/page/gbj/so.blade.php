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
                        <table class="table table-bordered" id="gudang-barang">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nomor SO</th>
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
                                            <th><input type="checkbox" id="head-cb-so"></th>
                                            <th>Nama Produk</th>
                                            <th>Jumlah</th>
                                            <th>Tipe</th>
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
                                            <th>Nama Produk</th>
                                            <th>Jumlah</th>
                                            <th>Tipe</th>
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

        $('#gudang-barang').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: '/api/tfp/cek-so',
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'so', name: 'so'},
                { data: 'nama_customer', name: 'nama_customer'},
                { data: 'batas_out', name: 'batas_out'},
                { data: 'status1', name: 'status1'},
                { data: 'action', name: 'action'},
            ],
            "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
        },
            "columnDefs": [
                {
                    "targets": [5],
                    "visible": document.getElementById('auth').value == '2' ? false : true,
                    "width": "20%",
                }
            ]
        });
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
        var tab = $('.add-produk').DataTable({
            destroy: true,
            serverSide: false,
            autoWidth: false,
            processing: true,
            "ordering": false,
            stateSave: true,
            'bPaginate': true,
            ajax: {
                url: "/api/tfp/detail-so/" +id+"/"+x,
            },
            columns: [
                { data: 'ids', name: 'ids'},
                { data: 'produk', name: 'produk'},
                { data: 'qty', name: 'qty'},
                { data: 'merk', name: 'merk'},
                { data: 'status', name: 'status'},
            ],
            'select': {
                'style': 'multi'
            },
            'order': [
                [1, 'asc']
            ],
            "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
        });
        // tab.column(2).data().sum();
        $('#addProdukModal').modal('show');
    })

    $(document).on('click', '#btnSave', function(e) {
            e.preventDefault();
            const ids = [];

            $('.cb-child-so').each(function() {
                if ($(this).is(":checked")) {
                    ids.push($(this).val());
                }
            })
            // console.log(ids);
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
                            gbj_id: ids,
                        },
                        success: function(res) {
                            location.reload();
                            console.log(res);
                        }
                    })
                }
            })



            console.log(ids);
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

        var table = $('#view-produk').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            autoWidth: false,
            ajax: {
                url: "/api/tfp/detail-so/" +id+"/"+x,
            },
            columns: [
                { data: 'produk', name: 'produk'},
                { data: 'qty', name: 'qty'},
                { data: 'merk', name: 'merk'},
                { data: 'status', name: 'status'},
            ],
            "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
        }
        })
        $('#viewProdukModal').modal('show');
    })
</script>
@stop
