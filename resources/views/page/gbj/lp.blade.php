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

    .float {
        width: 60px;
        height: 60px;
        bottom: 40px;
        right: 40px;
        background-color: #6aadff;
        color: #FFF;
        border-radius: 50px;
        text-align: center;
        box-shadow: 2px 2px 3px #999;
    }

    .my-float {
        margin-top: -10px;
    }
    #DataTables_Table_0_filter{
        display: none;
    }
</style>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Penerimaan Selain Perakitan</h1>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<section class="content">
    <input type="hidden" name="userid" id="userid" value="{{ Auth::user()->id }}">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="tab-content card" id="myTabContent">
                            <div class="tab-pane fade show active card-body" id="semua-produk" role="tabpanel"
                                aria-labelledby="semua-produk-tab">
                                <div class="row">
                                    <div class="col-lg-4 col-md-12 col-sm-12 mb-4">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card card-primary b-radius">
                                                    <div class="card-body">
                                                        <form method="post">
                                                            <div class="form-group row top-min">
                                                                <label for=""
                                                                    class="col-12 font-weight-bold col-form-label">Tanggal
                                                                    Masuk</label>
                                                                <div class="col-12">
                                                                    <input type="date"
                                                                        class="form-control tgl_masuk"
                                                                        id="tgl_masuk" name="tgl_masuk">
                                                                        <div class="invalid-feedback">Form Tanggal Masuk harus diisi.</div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row top-min ">
                                                                <label for=""
                                                                    class="col-12 font-weight-bold col-form-label">Dari</label>
                                                                <div class="col-12">
                                                                    <select
                                                                        class="custom-select division"
                                                                        id="divisi" name="dari">
                                                                    </select>
                                                                    <div class="invalid-feedback">Form Dari harus diisi.</div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row top-min">
                                                                <label for=""
                                                                    class="col-12 font-weight-bold col-form-label">Keterangan</label>
                                                                <div class="col-12">
                                                                    <textarea name="deskripsi"
                                                                        id="deskripsi"
                                                                        class="form-control tujuan"></textarea>
                                                                    <div class="invalid-feedback">Form Keterangan harus diisi.</div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                <div class="card card-primary b-radius">
                                                    <div class="card-body">
                                                        <div class="form-group row top-min">
                                                            <label for=""
                                                                class="col-12 font-weight-bold col-form-label">Produk</label>
                                                            <div class="col-12">
                                                                <select name="" id="gdg_brg_jadi_id" class="form-control product"></select>
                                                                <div class="invalid-feedback">Form Produk harus diisi.</div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row top-min">
                                                            <label for=""
                                                                class="col-12 font-weight-bold col-form-label">Jumlah</label>
                                                            <div class="col-12">
                                                                <input type="text" class="form-control" id="jumlah">
                                                                <div class="invalid-feedback">Form Jumlah harus diisi.</div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12 d-flex justify-content-end">
                                                                <button type="button" class="btn btn-primary btn-tambah">Tambah</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-8 col-md-12 col-sm-12">
                                        <div class="row">
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
                                                    <button class="btn btn-primary btn-simpan" hidden type="button" >Simpan</button>&nbsp;
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

<!-- Modal Tambahan Perakitan-->
<!-- Modal Detail-->
<div class="modal fade tambahan-perakitan" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>Detail Produk <span id="titlee"></span></b>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-striped scan-produk1">
                    <thead>
                        <tr>
                            <th class="checkboxremove"><input type="checkbox" id="head-cb"></th>
                            <th  style="min-width: 500px">Nomor Seri</th>
                            <th style="min-width: 500px">Layout</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                <br>
                <button type="button" class="btn btn-primary float-right" id="btnSeri">Simpan</button>
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
                    <select name="" id="change_layout" class="form-control">
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                <button type="button" class="btn btn-primary ubahData">Simpan</button>
            </div>
        </div>
    </div>
</div>

@stop

@section('adminlte_js')
<script>
    let produk = [];
    let layout = [];
    $(document).ready(function () {
        // Data Divisi
        $('.division').select2({
            placeholder: "Pilih Tujuan",
            allowClear: true
        });
        $.ajax({
            url: '/api/gbj/sel-divisi',
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if(res) {
                    $("#divisi").empty();
                    $("#divisi").append('<option selected></option>');
                    $.each(res, function(key, value) {
                        $("#divisi").append('<option value="'+value.id+'">'+value.nama+'</option');
                    });
                } else {
                    $("#divisi").empty();
                }
            }
        });
        // Data Produk
        $('.product').select2({
            placeholder: "Pilih Produk",
            allowClear: true
        });
        $.ajax({
            url: '/api/gbj/sel-gbj',
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if(res) {
                    $("#gdg_brg_jadi_id").empty();
                    $("#gdg_brg_jadi_id").append('<option selected></option>');
                    $.each(res, function(key, value) {
                        $("#gdg_brg_jadi_id").append('<option value="'+value.id+'">'+value.produk.nama+' '+value.nama+'</option');
                    });
                } else {
                    $("#gdg_brg_jadi_id").empty();
                }
            }
        });
        // Data Layout
        $.ajax({
            url: '/api/gbj/sel-layout',
            type: 'GET',
            dataType: 'json',
            success: function (res) {
                layout.push(res);
                $("#change_layout").empty();
                res.map(function(value) {
                    $("#change_layout").append('<option value="'+value.id+'">'+value.ruang+'</option');
                });
            }
        });
    });
    $(document).on('click', '.btn-tambah', function() {
        let prd = $('#gdg_brg_jadi_id').val();
        let namaprd = $('#gdg_brg_jadi_id option:selected').text();
        let jml = $('#jumlah').val();
        if (prd == '') return $('#gdg_brg_jadi_id').addClass('is-invalid');
        if (jml == '') return $('#jumlah').addClass('is-invalid');
        if (produk.length > 0) return produk.find(function(value) {
            if (value.prd == prd) return Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Produk sudah ada',
                type: 'error',
                confirmButtonText: 'Ok'
            });
            if(value.prd != prd) return produk.push({
                prd: prd,
                namaprd: namaprd,
                jml: jml,
                noseri: [],
                layout: []
            }) && addData(prd, namaprd, jml);
        });
        if(produk.length == 0) return produk.push({
            prd: prd,
            namaprd: namaprd,
            jml: jml,
            noseri: [],
            layout: []
        }) && addData(prd, namaprd, jml);
    });

    $(document).on('click', '.btn-add-seri', function() {
        let namaprd = $(this).data('namaprd');
        let jml = $(this).data('jml');
        let prd = $(this).data('prd');
        $('.scan-produk1').DataTable().destroy();
        $('.scan-produk1 tbody').empty();
        $('#titlee').text(namaprd);
        // Data Kosong
        let data_noseri = '<tr>'+
                            '<td>'+
                                '<input type="checkbox" class="cb-child">'+
                            '</td>'+
                            '<td>'+
                                '<input type="text" class="form-control" id="noseri" style="text-transform:uppercase">'+
                                '<div class="invalid-feedback">Nomor seri ada yang sama atau kosong.</div>'+
                            '</td>'+
                            '<td>'+
                                '<select class="form-control layout"></select>'+
                            '</td>'+
                        '</tr>';
        for(let i = 0; i < jml; i++) {
            $('.scan-produk1 tbody').append(data_noseri)
            layout[0].forEach(element => {
                $('.scan-produk1 tbody tr:last-child td:last-child select').append('<option value="'+element.id+'">'+element.ruang+'</option>');
            });
        }
        $('.scan-produk1').DataTable({
            scrollY: '500px',
            scrollCollapse: true,
            paging: false,
            ordering: false,
            searching: false,
            "lengthChange": false,
            autoWidth: false,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            },
        });
        $('.checkboxremove').removeClass('sorting_asc');
        $('#btnSeri').removeData('prd', 'jml');
        $('#btnSeri').attr('data-prd', prd);
        $('#btnSeri').attr('data-jml', jml);
        // Data Isi
        produk.forEach(function(element) {
            if(element.prd == prd) return element.noseri.forEach(function(item, index) {
                $('.scan-produk1 tbody tr').each(function(i, v) {
                    if(i == index) return $(this).find('td:nth-child(2) input').val(item) && $(this).find('td:nth-child(3) select').val(element.layout[index]);
                });
            });
        });
        $('.tambahan-perakitan').modal('show');
    });

    $(document).on('click', '.btn-hapus', function () {
        let prd = $(this).data('prd');
        let index = produk.findIndex(x => x.prd == prd);
        produk.splice(index, 1);
        $(this).parents('tr').remove();
        if (produk.length == 0) return $('.btn-simpan').attr('hidden', true);
    })

    function addData(prd, namaprd, jml) {
        let tambah_data = '<tr>'+
                            '<td>'+namaprd+'</td>'+
                            '<td>'+jml+'</td>'+
                            '<td><button class="btn btn-primary btn-add-seri" data-prd="'+prd+'" data-namaprd="'+namaprd+'" data-jml="'+jml+'"><i class="fas fa-qrcode"></i> Tambah</button>&nbsp;<button class="btn btn-danger btn-hapus" data-prd="'+prd+'"><i class="fas fa-trash"></i> Hapus</button></td>'+
                        '</tr>';
        $('.tambah_data').append(tambah_data);
        $('.btn-simpan').attr('hidden', false);
        $('#gdg_brg_jadi_id').removeClass('is-invalid');
        $('#jumlah').removeClass('is-invalid');
    }
    $(document).on('click', '#btnSeri', function () {
        let prd = $(this).data('prd');
        let jml = $(this).data('jml');
        let noseri = [];
        let layout = [];
        // Validasi Scanning
        $('.scan-produk1 tbody tr').each(function(i, v) {
            $(this).find('td:nth-child(2) input').removeClass('is-invalid');
            if($(this).find('td:nth-child(2) input').val() == '') return $(this).find('td:nth-child(2) input').addClass('is-invalid') && Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Nomor seri tidak boleh kosong!',
            });
            if(noseri.includes($(this).find('td:nth-child(2) input').val())) return $(this).find('td:nth-child(2) input').addClass('is-invalid') && Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Nomor seri tidak boleh sama!',
            });
            noseri.push($(this).find('td:nth-child(2) input').val());
            layout.push($(this).find('td:nth-child(3) select').val());
            // console.log(noseri);
            $.ajax({
                url: "/api/gbj/ceknoseri",
                type: "post",
                data: {
                    noseri: noseri
                },
                success: function(res) {
                    if (res.msg) {
                        if(noseri.length == jml) return produk.find(function(element, index) {
                            if(element.prd == prd ) return element.noseri = noseri, element.layout = layout,
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Data berhasil disimpan!',
                            }).then(function() {
                                $('.tambahan-perakitan').modal('hide');
                            });
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: res.error,
                        });
                    }
                }
            })

        });
    });
    $(document).on('click', '#head-cb', function() {
        let cb = $(this).is(':checked');
        $('.scan-produk1 tbody tr').each(function(index, element) {
            $(this).find('td:nth-child(1) input').prop('checked', cb);
        });
    });
    $(document).on('click', '.ubahData', function () {
        let checkbox_terpilih = $('.scan-produk1 tbody .cb-child:checked');
        let layout = $('#change_layout').val();
        $.each(checkbox_terpilih, function (index, elm) {
            let b = $(checkbox_terpilih).parent().next().next().children().val(layout);
        });
        $('#ubah-layout').modal('hide');
    })
    $(document).on('click', '.btn-simpan', function () {
        $('#tgl_masuk').removeClass('is-invalid');
        $('#divisi').removeClass('is-invalid');
        $('#deskripsi').removeClass('is-invalid');
        let data = {
            tgl_masuk: $('#tgl_masuk').val(),
            divisi: $('#divisi').val(),
            deskripsi: $('#deskripsi').val(),
            produk: produk,
            userid: $('#userid').val()
        }
        if(data.tgl_masuk == '' || data.divisi == '' || data.deskripsi == '' || data.produk.length == 0) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Data tidak boleh kosong!',
            });
            $('#tgl_masuk').addClass('is-invalid');
            $('#divisi').addClass('is-invalid');
            $('#deskripsi').addClass('is-invalid');
        } else {
            $.ajax({
                url: '/api/tfp/create-final',
                type: 'POST',
                dataType: 'json',
                data: data,
                success: function (res) {
                    console.log(res);
                    if(res.error == false) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Data berhasil disimpan!',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(function() {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Data gagal disimpan!',
                        });
                    }
                }
            });
        }
    })
</script>
@stop
