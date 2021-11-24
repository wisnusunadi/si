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
    .my-custom-scrollbar {
        position: relative;
        height: 550px;
        overflow: auto;
    }
    .table-wrapper-scroll-y {
    display: block;
    }
    table thead th {
    position: -webkit-sticky;
    position: sticky;
    top: 0;
    z-index: 30;
    }
    
</style>
<section class="content-header">
    <div class="container-fluid">
        <h1>Edit Produk Gudang Karantina</h1>
    </div><!-- /.container-fluid -->
</section>

<section class="content">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col">
                            <label for="tanggal">Tanggal Masuk</label>
                            <input type="date" name="" id="datePicker" class="form-control" placeholder="" readonly>
                        </div>
                        <div class="form-group col">
                            <label for="dari">Dari</label>
                            <select class="form-control dari" name="dari" disabled>
                                <option value="Divisi IT">Divisi IT</option>
                                <option value="Divisi QC">Divisi QC</option>
                                <option value="Divisi Perakitan">Divisi Perakitan</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-12">
            <div class="row">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fab fa-whmcs"></i> Sparepart Karantina</h3>
                            <div class="card-tools">
                                <button type="button" class="btn" id="" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col-12 d-flex justify-content-end mb-2"><button
                                    class="btn btn-outline-info add_sparepart"><i class="fas fa-plus"></i>
                                    Tambah</button></div>
                            <div class="table-wrapper-scroll-y my-custom-scrollbar">
                                <table class="table table-hover add_sparepart_table">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th style="width: 200px">Nama Produk</th>
                                            <th style="width: 200px">Unit</th>
                                            <th style="width: 150px">Jumlah</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tambah_data">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-tools"></i> Unit Karantina</h3>
                            <div class="card-tools">
                                <button type="button" class="btn" id="" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col-12 d-flex justify-content-end mb-2"><button
                                    class="btn btn-outline-info add_unit"><i class="fas fa-plus"></i>
                                    Tambah</button></div>
                            <div class="table-wrapper-scroll-y my-custom-scrollbar">
                                <table class="table table-hover add_unit_table">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th style="width: 220px">Nama Produk</th>
                                            <th>Jumlah</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tambah_data">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-12 d-flex justify-content-end">
        <div class="btn-simpan mb-3">
            <button class="btn btn-success" type="button" onclick="modalTerima()">Transfer</button>&nbsp;
            <button class="btn btn-info" type="button" onclick="modalRancang()">Rancang</button>&nbsp;
            <button class="btn btn-secondary " type="button" onclick="batal()">Batal</button>
        </div>
    </div>
     </div>
</section>


<div class="modal fade modalAddSparepart" id="" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm">
                                <label for="">Tanggal Keluar</label>
                                <div class="card" style="background-color: #C8E1A7">
                                    <div class="card-body">
                                        23-09-2021
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Nama Produk</label>
                                <div class="card" style="background-color: #F89F81">
                                    <div class="card-body">
                                        Produk 1
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm">
                                <label for="">Unit</label>
                                <div class="card" style="background-color: #FFCC83">
                                    <div class="card-body">
                                        Unit 1
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Dari</label>
                                <div class="card" style="background-color: #FFE0B4">
                                    <div class="card-body">
                                        Divisi IT
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Jumlah</label>
                                <div class="card" style="background-color: #FFECB2">
                                    <div class="card-body">
                                        100 pcs
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-striped scan-produk">
                                    <thead>
                                        <tr>
                                            <th>No Seri</th>
                                            <th>Kerusakan</th>
                                            <th>Tingkat Kerusakan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="text" class="form-control"></td>
                                            <td><input type="text" class="form-control"></td>
                                            <td>
                                                <select name="" id="" class="form-control">
                                                    <option value="">Level 1</option>
                                                    <option value="">Level 1</option>
                                                    <option value="">Level 1</option>
                                                </select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modalAddUnit" id="" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm">
                                <label for="">Tanggal Keluar</label>
                                <div class="card" style="background-color: #C8E1A7">
                                    <div class="card-body">
                                        23-09-2021
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Nama Produk</label>
                                <div class="card" style="background-color: #F89F81">
                                    <div class="card-body">
                                        Produk 1
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm">
                                <label for="">Dari</label>
                                <div class="card" style="background-color: #FFE0B4">
                                    <div class="card-body">
                                        Divisi IT
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Jumlah</label>
                                <div class="card" style="background-color: #FFECB2">
                                    <div class="card-body">
                                        100 pcs
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-striped scan-produk">
                                    <thead>
                                        <tr>
                                            <th>No Seri</th>
                                            <th>Kerusakan</th>
                                            <th>Tingkat Kerusakan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="text" class="form-control"></td>
                                            <td><input type="text" class="form-control"></td>
                                            <td>
                                                <select name="" id="" class="form-control">
                                                    <option value="">Level 1</option>
                                                    <option value="">Level 1</option>
                                                    <option value="">Level 1</option>
                                                </select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Index --}}
<div class="modal fade modal_transfer" id="" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title judul_modal">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="form-group">
                            <label for="">Tujuan</label>
                            <textarea name="" id="" cols="10" rows="5" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary simpan">Simpan</button>
            </div>
        </div>
    </div>
</div>
@stop
@section('adminlte_js')
<script>
    document.getElementById('datePicker').valueAsDate = new Date();

    function addSparepart() {
        $('.modalAddSparepart').modal('show');
    }

    function addUnit() {
        $('.modalAddUnit').modal('show');
    }

    $('.scan-produk').DataTable({
        "ordering": false,
        "autoWidth": false,
        searching: false,
        "lengthChange": false,
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
        }
    });
    $(document).on('click','.add_sparepart', function () {
        let table_sparepart = '<tr><td><select name="" id="" class="form-control produk"><option value="">Produk 1</option><option value="">Produk 2</option><option value="">Produk 3</option></select></td><td><select name="" id="" class="form-control unit"><option value="">Unit 1</option><option value="">Unit 2</option><option value="">Unit 3</option></select></td><td><input type="number" name="" id="" class="form-control"></td><td><button class="btn btn-primary" onclick="addSparepart()"><i class="fas fa-qrcode"></i> Tambah No Seri</button>&nbsp;<button class="btn btn-danger btn-delete"><i class="fas fa-trash"></i> Delete</button></td></tr>';
        $('.add_sparepart_table tbody').append(table_sparepart);
        $('.produk').select2({});
        $('.unit').select2();
    });
    $(document).on('click','.add_unit', function () {
        let table_unit = '<tr><td><select name="" id="" class="form-control produk"><option value="">Produk 1</option><option value="">Produk 2</option><option value="">Produk 3</option></select></td><td><input type="number" name="" id="" class="form-control"></td><td><button class="btn btn-primary" onclick="addUnit()"><i class="fas fa-qrcode"></i> Tambah No Seri</button>&nbsp;<button class="btn btn-danger btn-delete"><i class="fas fa-trash"></i> Delete</button></td></tr>';
        $('.add_unit_table tbody').append(table_unit);
        $('.produk').select2();
    });
    $(document).on('click', '.btn-delete', function (e) {
        $(this).parent().parent().remove();
        var check = $('tbody.tambah_data tr').length;
    });
    $(document).ready(function () {
        $('.dari').select2({});
    });

    function modalTerima() {
        $('.modal_transfer').modal('show');
        $('.catatan').val('');
        $('.list-group').children().remove();
        $('.judul_modal').text('Silahkan isi tujuan transfer produk');
        $(document).on('click','.tambah_catatan', function () {
            var catatan = $('.catatan').val();
            $.ajax({
                success: function (response) {
                    $('.list-group').append('<li class="list-group-item d-flex justify-content-between">'+catatan+'<div class="d-flex justify-content-end"><a href="#" class="remove">x</a></div></li>');
                    $('.catatan').val('');
                }
            });
        });
        $(document).on('click', '.remove', function () {
            $(this).parent().parent().remove();
        });

        $(document).on('click','.simpan', function () {
            Swal.fire({
                title: "Apakah anda yakin?",
                text: "Data yang sudah di transfer tidak dapat diubah!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                showCancelButton: true,
            }).then((success) => {
                if (success) {
                    Swal.fire(
                        'Data berhasil di transfer!',
                        '',
                        'success'
                    );
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                }else{
                    Swal.fire(
                        'Data gagal di transfer!',
                        '',
                        'error'
                    );
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                }
            });
        });
    }
    function modalRancang() {
        $('.modal_transfer').modal('show');
        $('.list-group').children().remove();
        $('.judul_modal').text('Silahkan isi tujuan rancangan produk');
        $(document).on('click','.tambah_catatan', function () {
            var catatan = $('.catatan').val();
            $('.list-group').append('<li class="list-group-item d-flex justify-content-between">'+catatan+'<div class="d-flex justify-content-end"><a href="#" class="remove">x</a></div></li>');
            $('.catatan').val('');
        });
        $(document).on('click', '.remove', function () {
            $(this).parent().parent().remove();
        });
        $(document).on('click', '.simpan', function () {
            Swal.fire({
                title: "Apakah anda yakin?",
                text: "Data yang sudah di rancangan tidak dapat diubah!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                showCancelButton: true,
            }).then((success) => {
                if (success) {
                    Swal.fire(
                        'Data berhasil di rancangan!',
                        '',
                        'success'
                    );
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                }else{
                    Swal.fire(
                        'Data gagal di rancangan!',
                        '',
                        'error'
                    );
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                }
            });
        });
    }   
    function batal() {
        Swal.fire({
            title: "Apakah anda yakin?",
            text: "Data yang sudah di batalkan tidak dapat dikembalikan!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            showCancelButton: true,
        }).then((result) => {
            if (result.value) {
                Swal.fire(
                    'Batal!',
                    'Data berhasil dibatalkan!',
                    'success'
                    );
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
            }else{
                Swal.fire(
                    'Batal!',
                    'Data tidak berhasil dibatalkan!',
                    'error'
                    );
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
            }
        });
    }
</script>
@stop
