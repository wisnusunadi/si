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
<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="col-lg-12">
                <!-- Card -->
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-8">
                                <h3 class="card-title">Daftar SO Gudang Barang Jadi</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered" id="gudang-barang">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nomor SO</th>
                                    <th>Customer</th>
                                    <th>Batas Transfer</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>2</td>
                                    <td>8457938475938475</td>
                                    <td>Rumah Sakit Dr. Soetomo</td>
                                    <td>10 Oktober 2021</td>
                                    {{-- Menggunakan Perkondisian Jika Data Sudah Dicek Maka Tampil Seperti ini --}}
                                    <td><span class="badge badge-primary">Sudah Dicek</span></td>
                                    <td>
                                        <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                            <div class="dropdown-menu">
                                                <button type="button" class="dropdown-item addProduk" id="">
                                                    <i class="fas fa-plus"></i>&nbsp;Siapkan Produk
                                                </button>
                                                <button type="button" class="dropdown-item viewProduk" id="">
                                                    <i class="far fa-eye"></i>&nbsp;Detail
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>8457938475938475</td>
                                    <td>Rumah Sakit Dr. Soetomo</td>
                                    <td>10 Oktober 2021</td>
                                    {{-- Menggunakan Perkondisian Jika Data Belum Dicek Maka Tampil Seperti ini --}}
                                    <td><span class="badge badge-danger">Belum Dicek</span></td>
                                    <td>
                                        <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                            <div class="dropdown-menu">
                                                <button type="button" class="dropdown-item addProduk" id="">
                                                    <i class="fas fa-plus"></i>&nbsp;Add Produk
                                                </button>
                                                <button type="button" class="dropdown-item viewProduk" id="">
                                                    <i class="far fa-eye"></i>&nbsp;View
                                                </button>
                                            </div>
                                        </div>
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
<<<<<<< HEAD
                    <form action="" method="post">
                        <input type="hidden" name="pesanan_id" id="ids">
=======
>>>>>>> 24b2266da4db4106d53d405bebadfc91e3549658
                        <div class="card">
                            <div class="card-header">
                                <div class="row row-cols-2">
                                    {{-- col --}}
                                    <div class="col"> <label for="">Nomor SO</label>
                                        <div class="card nomor-so">
                                            <div class="card-body">
                                                89798797856456
                                            </div>
                                        </div>
                                    </div>
                                    {{-- col --}}
                                    <div class="col"> <label for="">Nomor AKN</label>
                                        <div class="card nomor-akn">
                                            <div class="card-body">
                                                89798797856456
                                            </div>
                                        </div>
                                    </div>
                                    {{-- col --}}
                                    <div class="col"> <label for="">Nomor PO</label>
                                        <div class="card nomor-po">
                                            <div class="card-body">
                                                89798797856456
                                            </div>
                                        </div>
                                    </div>
                                    {{-- col --}}
                                    <div class="col"> <label for="">Customer</label>
                                        <div class="card instansi">
                                            <div class="card-body">
                                                RS. Dr. Soetomo
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped add-produk">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="head-cb"></th>
                                            <th>Nama Produk</th>
                                            <th>Jumlah</th>
                                            <th>Tipe</th>
                                            <th>Merk</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="checkbox" class="cb-child" value="2"></td>
                                            <td>AMBULATORY BLOOD PRESSURE MONITOR</td>
                                            <td>100 Unit</td>
                                            <td>ABPM50</td>
                                            <td>ELITECH</td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" class="cb-child" value="2"></td>
                                            <td>AMBULATORY BLOOD PRESSURE MONITOR</td>
                                            <td>100 Unit</td>
                                            <td>RGB</td>
                                            <td>ELITECH</td>
                                        </tr>
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
                <button type="button" class="btn btn-primary">Simpan</button>
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
                                                89798797856456
                                            </div>
                                        </div>
                                    </div>
                                    {{-- col --}}
                                    <div class="col"> <label for="">Nomor AKN</label>
                                        <div class="card nomor-akn">
                                            <div class="card-body">
                                                89798797856456
                                            </div>
                                        </div>
                                    </div>
                                    {{-- col --}}
                                    <div class="col"> <label for="">Nomor PO</label>
                                        <div class="card nomor-po">
                                            <div class="card-body">
                                                89798797856456
                                            </div>
                                        </div>
                                    </div>
                                    {{-- col --}}
                                    <div class="col"> <label for="">Instansi</label>
                                        <div class="card instansi">
                                            <div class="card-body">
                                                RS. Dr. Soetomo
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
                                            <th>Merk</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>AMBULATORY BLOOD PRESSURE MONITOR</td>
                                            <td>100</td>
                                            <td>ABPM50</td>
                                            <td>ELITECH</td>
                                            <td><span class="badge badge-success">Sudah Diinput</span></td>
                                        </tr>
                                        <tr>
                                            <td>AMBULATORY BLOOD PRESSURE MONITOR</td>
                                            <td>100</td>
                                            <td>RGB</td>
                                            <td>ELITECH</td>
                                            <td><span class="badge badge-danger">Belum Diinput</span></td>
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
</div>
@stop

@section('adminlte_js')
<script>
    $(document).ready(function () {
        $('.addProduk').click(function (e) {
            $('#addProdukModal').modal('show');
        });
        $('.viewProduk').click(function (e) {
            $('#viewProdukModal').modal('show');
        });
    });

    $('.add-produk').DataTable({
        'columnDefs': [{
            'targets': 0,
            'checkboxes': {
                'selectRow': true
            }
        }],
        'select': {
            'style': 'multi'
        },
        'order': [
            [1, 'asc']
        ],
        "oLanguage": {
            "sSearch": "Cari:"
        }
    });
    $('#view-produk').DataTable({
        "oLanguage": {
            "sSearch": "Cari:"
        }
    });
    $('#gudang-barang').DataTable({
        "oLanguage": {
            "sSearch": "Cari:"
        },
                "columnDefs": [
        {
            "targets": [5],
            "visible": document.getElementById('auth').value == '2' ? false : true
        }
    ]
    });
    var id = '';
    $(document).on('click', '.editmodal', function(e) {
        id = $(this).data('id');
        console.log(id);
        $.ajax({
            url: "/api/tfp/header-so/" +id,
            success: function(res) {
                console.log(res);
                $('span#soo').text(res.so);
                $('span#poo').text(res.po);
                $('span#aknn').text(res.akn);
                $('span#instansii').text(res.customer);
            }
        });
        $('.add-produk').DataTable().destroy();
        var tab = $('.add-produk').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "/api/tfp/detail-so/" +id,
                // data: {id: id},
                // type: "post",
                // dataType: "json",
            },
            columns: [
                { data: 'ids', name: 'ids'},
                // { data: 'status', name: 'status'},
                { data: 'produk', name: 'produk'},
                { data: 'qty', name: 'qty'},
                { data: 'tipe', name: 'tipe'},
                { data: 'merk', name: 'merk'},

            ],
            'columnDefs': [{
                'targets': 0,
                'checkboxes': {
                    'selectRow': true
                },
                // 'render': function(data, type, row, meta) {
                //     return '<input type="checkbox" class="cb-child">';
                // }
            }],
            'select': {
                'style': 'multi'
            },
            'order': [
                [1, 'asc']
            ],
            "oLanguage": {
                "sSearch": "Cari:"
            }
        });

        $(document).on('click', '#btnSave', function(e) {
            e.preventDefault();
            // var idd = $('#ids').val(id);
            const ids = [];

            var rowsel = tab.column(0).checkboxes.selected();
            // console.log(rowsel);

            $.each(rowsel, function(i, val) {
                ids.push(val);
            });

            $.ajax({
                url: "/api/so/cek",
                type: "post",
                data: {
                    pesanan_id : id,
                    gbj_id: ids,
                },
                success: function(res) {
                   console.log('res ' + res);
                }
            })

            console.log(ids);
        })
        $('#addProdukModal').modal('show');
    })

    $(document).on('click', '.detailmodal', function(e) {
        var id = $(this).data('id');
        $.ajax({
            url: "/api/tfp/header-so/" +id,
            success: function(res) {
                console.log(res);
                $('span#so').text(res.so);
                $('span#po').text(res.po);
                $('span#akn').text(res.akn);
                $('span#instansi').text(res.customer);
            }
        });
        $('#view-produk').DataTable().destroy();
        var table = $('#view-produk').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "/api/tfp/detail-so/" +id,
                // data: {id: id},
                // type: "post",
                // dataType: "json",
            },
            columns: [
                { data: 'produk', name: 'produk'},
                { data: 'qty', name: 'qty'},
                { data: 'tipe', name: 'tipe'},
                { data: 'merk', name: 'merk'},
                { data: 'status', name: 'status'},
            ],
        })
        $('#viewProdukModal').modal('show');
    })
</script>
@stop
