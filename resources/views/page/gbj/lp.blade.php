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
                                                                                        class="form-control tanggal">
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group row top-min">
                                                                                <label for=""
                                                                                    class="col-12 font-weight-bold col-form-label">Dari</label>
                                                                                <div class="col-12">
                                                                                    <select class="form-control division"
                                                                                        name="division">
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
                                                                                    <textarea name="tujuan" id=""
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
                                                    <button class="btn btn-info" type="button">Rancang</button>&nbsp;
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
                                        10-04-2020
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
                <button type="button" class="btn btn-primary">Simpan</button>
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
                <table class="table table-striped scan-produk">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="head-cb"></th>
                            <th>Nomor Seri</th>
                            <th>Layout</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="checkbox" class="cb-child" value="1"></td>
                            <td><input type="text" name="" id="" class="form-control"></td>
                            <td><select name="" id="" class="form-control">
                                    <option value="1">Layout 1</option>
                                    <option value="2">Layout 2</option>
                                </select></td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" class="cb-child" value="2"></td>
                            <td><input type="text" name="" id="" class="form-control"></td>
                            <td><select name="" id="" class="form-control">
                                    <option value="1">Layout 1</option>
                                    <option value="2">Layout 2</option>
                                </select></td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" class="cb-child" value="3"></td>
                            <td><input type="text" name="" id="" class="form-control"></td>
                            <td><select name="" id="" class="form-control">
                                    <option value="1">Layout 1</option>
                                    <option value="2">Layout 2</option>
                                </select></td>
                        </tr>
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
                <h5 class="modal-title"><b>Detail Produk AMBULATORY BLOOD PRESSURE MONITOR</b></h5>
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
                        <tr>
                            <td><input type="checkbox" class="cb-child" value="1"></td>
                            <td>36541654654654564</td>
                            <td><select name="" id="" class="form-control">
                                    <option value="1">Layout 1</option>
                                    <option value="2">Layout 2</option>
                                </select></td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" class="cb-child" value="2"></td>
                            <td>36541654654654564</td>
                            <td><select name="" id="" class="form-control">
                                    <option value="1">Layout 1</option>
                                    <option value="2">Layout 2</option>
                                </select></td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" class="cb-child" value="3"></td>
                            <td>36541654654654564</td>
                            <td><select name="" id="" class="form-control">
                                    <option value="1">Layout 1</option>
                                    <option value="2">Layout 2</option>
                                </select></td>
                        </tr>
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

<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> 05f6ec0bc9795de2021471141f7ed12cf5f5cc51
    $(document).on('click', '.btn-tambah', function () {
        $.ajax({
            success: function () {
                addData()
<<<<<<< HEAD
=======
=======
    $(document).on('click','.btn-tambah', function () {
        let tanggal = $('.tanggal').val();
        let divisi = $('.division').val();
        let d_divisi = $('.division').find(':selected').text();
        let tujuan = $('.tujuan').val();
        let produk = $('.product').val();
        let d_produk = $('.product').find(':selected').text();
        let jumlah = parseInt($('.stok').val());
        $.ajax({
            success: function () {
                addData(tanggal, divisi, d_divisi, tujuan, produk, d_produk, jumlah)

                $('#post_tgl').val(tanggal);
                $('#post_ke').val(divisi);
                $('#post_tujuan').val(tujuan);
                $('#post_produk').val(produk);
                $('#post_qty').val(jumlah);
>>>>>>> origin/della
>>>>>>> 05f6ec0bc9795de2021471141f7ed12cf5f5cc51
                $('.btn-simpan').removeClass('hapus');
            }
        });
    });
<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> 05f6ec0bc9795de2021471141f7ed12cf5f5cc51

    function addData() {
        let tambah_data = '<tr><td><select name="" id="" class="form-control product"><option value="">Option 1</option><option value="">Option 2</option><option value="">Option 3</option></select></td><td><input type="text" class="form-control number-input" id=""></td><td><button class="btn btn-primary" data-toggle="modal" data-target=".modal-produk" onclick="tambahanPerakitan()"><i class="fas fa-qrcode"></i> Tambah</button>&nbsp;<button class="btn btn-danger btn-delete"><i class="fas fa-trash"></i> Hapus</button></td></tr>';
        $('tbody.tambah_data').append(tambah_data);
    }
    $(document).on('click', '.btn-delete', function (e) {
<<<<<<< HEAD
=======
=======
    var i = 0;
    var k = 0;
    function addData(tanggal, divisi, d_divisi, tujuan, produk, d_produk, jumlah) {
        if (tujuan.length > 30) {
            var a = tujuan.substring(0, 10) + '...';
        }else{
            var a = tujuan;
        }
        i++;
        let tambah_data = '<tr id=row'+i+'><td>'+tanggal+'<input type="hidden" name="tgl_masuk['+i+']" id="post_tgl'+i+'" value="'+tanggal+'"></td><td>'+d_divisi+'<input type="hidden" name="dari['+i+']" id="post_ke'+i+'" value="'+divisi+'"></td><td>'+tujuan+'<input type="hidden" name="tujuan['+i+']" id="post_tujuan'+i+'" value="'+tujuan+'"></td><td>'+d_produk+'<input type="hidden" name="gbj_id['+i+']" id="post_produk'+i+'" value="'+produk+'"></td><td>'+jumlah+'<input type="hidden" name="qty['+i+']" id="post_qty'+i+'" value="'+jumlah+'"></td><td><button class="btn btn-primary" data-toggle="modal" data-target=".modal-produk" data-id="'+produk+'" onclick="tambahanPerakitan()"><i class="fas fa-plus"></i> Tambah</button>&nbsp;<button class="btn btn-danger btn-delete"><i class="fas fa-trash"></i> Hapus</button></td></tr>'
        $('tbody.tambah_data').append(tambah_data);
    }

    $(document).on('click', '.btn-delete', function(e){
>>>>>>> origin/della
>>>>>>> 05f6ec0bc9795de2021471141f7ed12cf5f5cc51
        e.preventDefault();
        $(this).parent().parent().remove();
        var check = $('tbody.tambah_data tr').length;
        if (check != 0) {
            $('.btn-simpan').removeClass('hapus');
        } else {
            $('.btn-simpan').addClass('hapus');
        }
    });

<<<<<<< HEAD
    $(document).on('click', '.editmodal', function() {
        var id = $(this).data('id');
        console.log(id);
=======
<<<<<<< HEAD
=======
    // cancel
    $(document).on('click', '#btnCancel', function(e){

        // console.log('ok');
        location.reload();
    });

    // draft
    $(document).on('click', '#btnDraft', function(e){
        e.preventDefault();

        let a = $('#post_tgl').val();
        let b = $('#post_ke').val();
        let c = $('#post_tujuan').val();
        let d = $('#post_produk').val();
        let ee = parseInt($('#post_qty').val());

        let tgl = [];
        let dari = [];
        let tujuan = [];
        let prd = [];
        let jml = [];

        $('input[name^=tgl_masuk]').each(function() {
            tgl.push($(this).val());
        })

        $('input[name^=dari]').each(function() {
            dari.push($(this).val());
        })
>>>>>>> 05f6ec0bc9795de2021471141f7ed12cf5f5cc51

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

        tambahanRancangan();
    })

<<<<<<< HEAD
=======
>>>>>>> origin/della
>>>>>>> 05f6ec0bc9795de2021471141f7ed12cf5f5cc51

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

        var datee = new DateTime($('#datetimepicker1'), {
            format: 'dd mm YYYY' });

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

    function tambahanPerakitan() {
        $('.tambahan-perakitan').modal('show');
    }
<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> 05f6ec0bc9795de2021471141f7ed12cf5f5cc51
    $('.scan-produk').DataTable({
        "ordering": false,
        "autoWidth": false,
        searching: false,
        "lengthChange": false,
    });
<<<<<<< HEAD
=======
=======

>>>>>>> origin/della
>>>>>>> 05f6ec0bc9795de2021471141f7ed12cf5f5cc51

</script>
@stop
