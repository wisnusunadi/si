@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
<style>
    .hapus {
        display: none;
    }

    .nomor-so {
        background-color: #717FE1;
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 18px
    }

    .nomor-akn {
        background-color: #DF7458;
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 18px
    }

    .nomor-po {
        background-color: #85D296;
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 18px
    }

.float{
	width:60px;
	height:60px;
	bottom:40px;
	right:40px;
	background-color:#6aadff;
	color:#FFF;
	border-radius:50px;
	text-align:center;
	box-shadow: 2px 2px 3px #999;
}

.my-float{
	margin-top:22px;
}

</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-lg-12">
                        <ul class="nav nav-tabs" id="myTab" role="tabList">
                            <li class="nav-item" role="presentation">
                                <a href="#produk" class="nav-link active" id="produk-tab" data-toggle="tab" role="tab"
                                    aria-controls="produk" aria-selected="true">Rancangan Perakitan</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#semua-produk" class="nav-link" id="semua-produk-tab" data-toggle="tab" role="tab"
                                    aria-controls="semua-produk" aria-selected="true">Buat Perakitan</a>
                            </li>
                        </ul>
                        <div class="tab-content card" id="myTabContent">
                            <div class="tab-pane fade show active card-body" id="produk" role="tabpanel"
                                aria-labelledby="produk-tab">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-horizontal">
                                            <div class="form-group row">
                                                <label for="" id="tanggal" class="col-sm-5 text-right">Tanggal Masuk</label>
                                                <div class="col-sm-2">
                                                    <input type="text" class="form-control" id="datetimepicker1">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="table-responsive ">
                                            {{-- Tanggal Masuk dan Tanggal Keluar --}}
                                            <table class="table table-hover pertanggal" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>Tanggal Masuk</th>
                                                        <th>Dari</th>
                                                        <th>Tujuan</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>10-09-2022</td>
                                                        <td>Divisi IT</td>
                                                        <td>Uji Coba Produk</td>
                                                        <td><button class="btn btn-info" onclick="modalRancangan()"><i
                                                                    class="far fa-eye"></i> Detail</button></td>
                                                    </tr>
                                                    <tr>
                                                        <td>10-09-2022</td>
                                                        <td>Divisi IT</td>
                                                        <td>Uji Coba Produk</td>
                                                        <td><button class="btn btn-info" onclick="modalRancangan()"><i
                                                                    class="far fa-eye"></i> Detail</button></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show card-body" id="semua-produk" role="tabpanel"
                                aria-labelledby="semua-produk-tab">
                                <div class="row">
                                    <div class="col-lg-4 col-md-12 col-sm-12 mb-4">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card card-primary b-radius">
                                                    <div class="card-body">
                                                        <ul class="list-group list-group-flush">
                                                            <li class="list-group-item">
                                                                <div class="row">
                                                                    <div class="col-12 mt-3">
                                                                        <form method="post">
                                                                            <div class="form-group row top-min">
                                                                                <label for=""
                                                                                    class="col-12 font-weight-bold col-form-label">Tanggal
                                                                                    Masuk</label>
                                                                                <div class="col-12">
                                                                                    <input type="date"
                                                                                        class="form-control tanggal" name="tgl_masuk">
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group row top-min">
                                                                                <label for=""
                                                                                    class="col-12 font-weight-bold col-form-label">Dari</label>
                                                                                <div class="col-12">
                                                                                    <select class="form-control division"
                                                                                        name="dari" id="dari">
                                                                                        <option value="Divisi IT">Divisi IT
                                                                                        </option>
                                                                                        <option value="Divisi QC">Divisi QC
                                                                                        </option>
                                                                                        <option value="Divisi Perakitan">
                                                                                            Divisi Perakitan</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group row top-min">
                                                                                <label for=""
                                                                                    class="col-12 font-weight-bold col-form-label">Keterangan</label>
                                                                                <div class="col-12">
                                                                                    <textarea name="deskripsi" id="deskripsi"
                                                                                        class="form-control tujuan"></textarea>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-8 col-md-12 col-sm-12">
                                        <div class="row">
                                            <div class="col-12 d-flex justify-content-end mb-3">
                                                <a href="#" class="float btn-tambah">
                                                    <i class="fa fa-plus my-float"></i>
                                                </a>
                                            </div>
                                            <div class="col-12 table-responsive mb-4">
                                                <table class="table table-hover addData">
                                                    <thead>
                                                        <tr>
                                                            <th>Produk</th>
                                                            <th>Jumlah</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="tambah_data">
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-12 d-flex justify-content-end">
                                                <div class="btn-simpan hapus">
                                                    <button class="btn btn-success" type="button">Terima</button>&nbsp;
                                                    <button class="btn btn-info" type="button" id="btnDraft">Rancang</button>&nbsp;
                                                    <button class="btn btn-secondary " type="button">Batal</button>
                                                </div>
                                            </div>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Detail Rancangan-->
<div class="modal fade modal-rancangan" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Rancangan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm">
                                <label for="">Tanggal Masuk</label>
                                <div class="card nomor-so">
                                    <div class="card-body">
                                        <span id="in">10-04-2020</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Dari</label>
                                <div class="card nomor-akn">
                                    <div class="card-body">
                                        <span id="from">Divisi IT</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Tujuan</label>
                                <div class="card nomor-po">
                                    <div class="card-body">
                                       <span id="tujuan">Uji Coba Produk</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-rancangan">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Produk</th>
                                    <th>Jumlah</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td scope="row">1</td>
                                    <td>Ambulatory</td>
                                    <td>100 Unit</td>
                                    <td><button class="btn btn-info" onclick="tambahanRancangan()"><i
                                                class="far fa-edit"></i> Detail</button></td>
                                </tr>
                                <tr>
                                    <td scope="row">2</td>
                                    <td>Ambulatory</td>
                                    <td>100 Unit</td>
                                    <td><button class="btn btn-info" onclick="tambahanRancangan()"><i
                                                class="far fa-edit"></i> Detail</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                <button type="button" class="btn btn-primary" id="btnSave">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambahan Perakitan-->
<!-- Modal Detail-->
<div class="modal fade tambahan-perakitan" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>Detail Produk AMBULATORY BLOOD PRESSURE MONITOR</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-striped scan-produk1">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="head-cb"></th>
                            <th>Nomor Seri</th>
                            <th>Layout</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                <button class="btn btn-info" data-toggle="modal" data-target="#ubah-layout">Ubah Layout</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambahan Rancangan-->
<!-- Modal Detail-->
<div class="modal fade tambahan-rancangan" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>Detail Produk <span id="title">AMBULATORY BLOOD PRESSURE MONITOR</span></b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-striped scan-produk">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="head-cb"></th>
                            <th>Nomor Seri</th>
                            <th>Layout</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                <button class="btn btn-info" data-toggle="modal" data-target="#ubah-layout">Ubah Layout</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ubah Layout-->
<div class="modal fade" id="ubah-layout" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah Layout</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Layout</label>
                    <select name="" id="change-layout" class="form-control">
                        <option value="1">Layout 1</option>
                        <option value="2">Layout 2</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                <button type="button" class="btn btn-primary" onclick="ubahData()">Simpan</button>
            </div>
        </div>
    </div>
</div>

@stop

@section('adminlte_js')
<script>
    // import swal from 'sweetalert2/src/sweetalert2.js'
    // Restricts input for each element in the set of matched elements to the given inputFilter.
    (function ($) {
        $.fn.inputFilter = function (inputFilter) {
            return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function () {
                if (inputFilter(this.value)) {
                    this.oldValue = this.value;
                    this.oldSelectionStart = this.selectionStart;
                    this.oldSelectionEnd = this.selectionEnd;
                } else if (this.hasOwnProperty("oldValue")) {
                    this.value = this.oldValue;
                    this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
                } else {
                    this.value = "";
                }
            });
        };
    }(jQuery));
    $(document).ready(function () {
        $('.division').select2();
        $('.productt').select2();

        $(".number-input").inputFilter(function (value) {
            return /^\d*$/.test(value);
            var value = $(this).val();
        });

    });

    $(document).on('click', '.btn-tambah', function () {
        $.ajax({
            success: function () {
                addData()
                $('.btn-simpan').removeClass('hapus');
            }
        });
    });
    var i = 0;
    function addData() {
        $.ajax({
            url: '/api/gbj/sel-gbj',
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if(res) {
                    console.log(res);
                    $(".productt").empty();
                    $(".productt").append('<option value="">Pilih Item</option>');
                    $.each(res, function(key, value) {
                        $(".productt").append('<option value="'+value.id+'">'+value.produk.nama+' '+value.nama+'</option');
                    });
                } else {
                    $(".productt").empty();
                }
            }
        });
        i++;
        let tambah_data = '<tr id="row'+i+'"><td><select name="gdg_brg_jadi_id['+i+']" id="gdg['+i+']" class="form-control productt"><option value="">Option 1</option><option value="">Option 2</option><option value="">Option 3</option></select></td><td><input type="text" class="form-control number-input" id="qty['+i+']" name="qty'+i+'"></td><td><button class="btn btn-primary" onclick="tambahanPerakitan()"><i class="fas fa-qrcode"></i> Tambah</button>&nbsp;<button class="btn btn-danger btn-delete"><i class="fas fa-trash"></i> Hapus</button></td></tr>';
        $('tbody.tambah_data').append(tambah_data);
    }

    function addSeri() {
        // i++;
        // let seri_data = '<tr id="seri'+i+'"><td><input type="checkbox" class="cb-child" value="'+i+'"></td><td><input type="text" name="noseri['+i+']" id="noseri['+i+']" class="form-control"></td><td><select name="layout_id['+i+']" id="layout_idd['+i+']" class="form-control"><option value="1">Layout 1</option><option value="2">Layout 2</option></select></td></tr>'
    }
    $(document).on('click', '.btn-delete', function (e) {
        e.preventDefault();
        $(this).parent().parent().remove();
        var check = $('tbody.tambah_data tr').length;
        if (check != 0) {
            $('.btn-simpan').removeClass('hapus');
        } else {
            $('.btn-simpan').addClass('hapus');
        }
    });
    var id = '';
    $(document).on('click', '.editmodal', function() {
        id = $(this).data('id');
        console.log(id);

        $.ajax({
            url: '/api/draft/data',
            type: "post",
            data: {
                id: id,
            },
            success: function(res) {
                console.log(res);
                $('span#in').text(res.data[0].in);
                $('span#from').text(res.data[0].from);
                $('span#tujuan').text(res.data[0].tujuan);
            },

        });
        $('.table-rancangan').DataTable().destroy();
        $('.table-rancangan').DataTable({
            processing: true,
            serverSide: true,
            "lengthChange": false,
            "searching": false,
            ajax: {
                url: '/api/draft/data',
                type: "post",
                data: {
                    id: id,
                },
            },
            columns: [
                {data: 'DT_RowIndex'},
                {data: 'nama_produk'},
                {data: 'jml'},
                {data: 'action'},
            ]
        });
        modalRancangan();
    });

    $(document).on('click', '.detail', function(e) {
        var id = $(this).data('id');
        console.log(id);

        var int = $('.number-input').val();
        console.log(int);

        tambahanRancangan();
    })


    $(document).ready(function () {

        var table = $('.pertanggal').DataTable({
            processing: true,
            serverSide: true,
            "lengthChange": false,
            "searching": false,
            ajax: {
                url: '/api/draft/data',
                type: "post",
            },
            columns: [
                // { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'in', name: 'in'},
                { data: 'from', name: 'from'},
                { data: 'tujuan'},
                { data: 'action'}
            ],

        });

        // var datee = new DateTime($('#datetimepicker1'), {
        //     format: 'dd mm YYYY' });

        $('#datetimepicker1').on('change', function() {
            table.draw();
        });

        $("#head-cb").on('click', function () {
            var isChecked = $("#head-cb").prop('checked')
            $('.cb-child').prop('checked', isChecked)
        });

        // divisi
        $.ajax({
            url: '/api/gbj/sel-divisi',
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if(res) {
                    console.log(res);
                    $(".division").empty();
                    $(".division").append('<option value="">Pilih Item</option>');
                    $.each(res, function(key, value) {
                        $(".division").append('<option value="'+value.id+'">'+value.nama+'</option');
                    });
                } else {
                    $(".division").empty();
                }
            }
        });

    });

    $(document).on('click', '#btnDraft', function(e) {
        e.preventDefault();

        const prd = [];
        const jml = [];

        $('select[name^="gdg_brg_jadi_id"]').each(function() {
            prd.push($(this).val());
        });

        $('input[name^="qty"]').each(function() {
            jml.push($(this).val());
        });

        $.ajax({
            url: "/api/draft/rancang",
            type: "post",
            data: {
                "_token" : "{{ csrf_token() }}",
                tgl_masuk : $('.tanggal').val(),
                dari: $('#dari').val(),
                deskripsi: $('#deskripsi').val(),
                gdg_brg_jadi_id: prd,
                qty: jml,
            },
            success: function(res) {
                console.log(res);
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: res.msg,
                    showConfirmButton: false,
                    timer: 1500
                })
                location.reload();
            }
        })
    });

    function ubahData() {
        let checkbox_terpilih = $('.scan-produk tbody .cb-child:checked');
        let layout = $('#change-layout').val();
        $.each(checkbox_terpilih, function (index, elm) {
            let b = $(checkbox_terpilih).parent().next().next().children().val(layout);
        });
        $('#ubah-layout').modal('hide');
    }

    $('#datetimepicker1').daterangepicker({});

    function modalRancangan() {
        $('.modal-rancangan').modal('show');
    }

    function tambahanRancangan() {
        $('.tambahan-rancangan').modal('show');
    }

    function tambahanPerakitan(tambah) {
        $('.tambahan-perakitan').modal('show');
        for (let index = 0; index < tambah; index++) {
           $('.scan-produk1 tbody').append('<tr><td><input type="checkbox" class="cb-child" value="1"></td><td><input type="text" name="" id="" class="form-control"></td><td><select name="" id="" class="form-control"><option value="1">Layout 1</option><option value="2">Layout 2</option></select></td></tr>');
        }
        $('.scan-produk1').DataTable({}).destroy();
        $('.scan-produk1').DataTable({
            "ordering": false,
            "autoWidth": false,
            searching: false,
            "lengthChange": false,
        });
    }

    $(document).on('click', '.btnPlus', function() {
        const prd = [];

        var prdid = $('.productt').val();
        var jml = $('.number-input').val();

        tambahanPerakitan(jml);
    })

    $(document).on('click', '#btnSave', function() {
        // console.log(id);

        $.ajax({
            url: "/api/tfp/create-final",
            type: "post",
            data: {
                "_token": "{{csrf_token()}}",
                id: id,
            },
            success: function(res) {
                console.log(res);
            }
        })
    })

</script>
@stop
