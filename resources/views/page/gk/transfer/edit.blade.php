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
        <h1>Edit Transfer Gudang Karantina</h1>
    </div><!-- /.container-fluid -->
</section>

<section class="content">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    @foreach ($data as $d)
                    <div class="form-row">
                        <div class="form-group col">
                            <label for="tanggal">Tanggal Masuk</label>
                            <input type="text" name="date_out" value="{{ $d->date_out }}" id="datePicker" class="form-control" placeholder="" readonly>
                        </div>
                        <div class="form-group col">
                            <label for="dari">Dari</label>
                            <input type="hidden" name="dari" value="{{ $d->to }}">
                            <select class="form-control dari" name="dari" disabled>
                                <option value="Divisi IT">{{ $d->to->nama }}</option>
                            </select>
                        </div>
                    </div>
                    @endforeach
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
                                            <th style="width: 150px">Nama Produk</th>
                                            <th style="width: 150px">Unit</th>
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
                                            <th style="width: 180px">Jumlah</th>
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
                            <label for="">Tanggal Masuk</label>
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
                            <table class="table table-striped scan-produk1">
                                <thead>
                                    <tr>
                                        <th>No Seri</th>
                                        <th>Kerusakan</th>
                                        <th>Tingkat Kerusakan</th>
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
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="button" id="btnSeri" class="btn btn-primary">Simpan</button>
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
                            <label for="">Tanggal Masuk</label>
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
                                    {{-- <tr>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td>
                                            <select name="" id="" class="form-control">
                                                <option value="">Level 1</option>
                                                <option value="">Level 1</option>
                                                <option value="">Level 1</option>
                                            </select>
                                        </td>
                                    </tr> --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="button" id="btnAddUnit" class="btn btn-primary">Simpan</button>
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
    var i = 0;
    function addSparepart(x) {
        $('.modalAddSparepart').modal('show');
        $('.scan-produk1').DataTable().destroy();
        $('.scan-produk1 tbody').empty();
        for (let index = 0; index < x; index++) {
        $('.scan-produk1 tbody').append('<tr><td><input type="text" name="noseri[]" id="noseri" maxlength="13" class="form-control seri"><div class="invalid-feedback">Nomor seri ada yang sama.</div></td><td><input type="text" class="form-control"></td><td><select name="" id="" class="form-control"><option value="">Level 1</option><option value="">Level 1</option><option value="">Level 1</option></select></td></tr>');
        }
        var tableScan = $('.scan-produk1').DataTable({
            "destroy": true,
            "ordering": false,
            "autoWidth": false,
            searching: false,
            "lengthChange": false,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
        });

        $(".form-control").keyup(function () {
            if (this.value.length == this.maxLength) {
            $(this).next('.form-control').focus();
            }
        });

        $(document).on('click', '#btnSeri', function(e) {
            e.preventDefault();

            let arr = [];
            const data = tableScan.$('.seri').map(function() {
                return $(this).val();
            }).get();

            data.forEach(function(item) {
                if (item != '') {
                    arr.push(item);
                }
            })

            const count = arr =>
                arr.reduce((a, b) => ({ ...a,
                    [b]: (a[b] || 0) + 1
                }), {})

                const duplicates = dict =>
                Object.keys(dict).filter((a) => dict[a] > 1)
            
                if (duplicates(count(arr)).length > 0) {
                            $('.seri').filter(function () {
                                return $(this).val() == duplicates(count(arr))[0];
                            }).addClass('is-invalid');

                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Nomor seri '+ duplicates(count(arr)) +' ada yang sama.',
                            })
                }else{
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Nomor seri tersimpan',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        $('.modalAddSparepart').modal('hide');
                    })

                }
        });
    }

    function addUnit(x) {
        $('.modalAddUnit').modal('show');
        $('.scan-produk').DataTable().destroy();
        $('.scan-produk tbody').empty();
        for (let index = 0; index < x; index++) {
        $('.scan-produk tbody').append('<tr><td><input type="text" class="form-control seri"><div class="invalid-feedback">Nomor seri ada yang sama.</div></td><td><input type="text" class="form-control"></td><td><select name="" id="" class="form-control"><option value="">Level 1</option><option value="">Level 1</option><option value="">Level 1</option></select></td></tr>');
        }
        var tableUnit = $('.scan-produk').DataTable({
            "ordering": false,
            "autoWidth": false,
            searching: false,
            "lengthChange": false,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
        });

        $(document).on('click', '#btnAddUnit', function(e) {
            e.preventDefault();

            let arr = [];
            const data = tableUnit.$('.seri').map(function() {
                return $(this).val();
            }).get();

            data.forEach(function(item) {
                if (item != '') {
                    arr.push(item);
                }
            })

            const count = arr =>
                arr.reduce((a, b) => ({ ...a,
                    [b]: (a[b] || 0) + 1
                }), {})

                const duplicates = dict =>
                Object.keys(dict).filter((a) => dict[a] > 1)
            
                if (duplicates(count(arr)).length > 0) {
                            $('.seri').filter(function () {
                                return $(this).val() == duplicates(count(arr))[0];
                            }).addClass('is-invalid');

                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Nomor seri '+ duplicates(count(arr)) +' ada yang sama.',
                            })
                }else{
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Nomor seri tersimpan',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        $('.modalAddUnit').modal('hide');
                    })
                }
        });
    }

    $(document).on('click', '#btn_plus', function() {
        var tr = $(this).closest('tr');
        var x = tr.find('#jml').val();
        console.log(x);
        addSparepart(x);
    })
    $(document).on('click', '#btnPlus', function() {
        var tr = $(this).closest('tr');
        var x = tr.find('#jum').val();
        console.log(x);
        addUnit(x);
    })

    // function select_divisi() {
        $.ajax({
            url: '/api/gbj/sel-divisi',
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                // ii++;
                console.log(res);
                $.each(res, function(key, value) {
                    // $("#change_layout").append('<option value="'+value.id+'">'+value.ruang+'</option');
                    $(".dari").append('<option value="'+value.id+'">'+value.nama+'</option');
                });
            }
        });
    // }

    function transfer() {
        Swal.fire({
            title: "Apakah anda yakin?",
            text: "Data yang sudah di transfer tidak dapat diubah!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        });
    };

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
        i++;
        let table_sparepart = '<tr><td><select name="" id="" class="form-control produk"><option value="">Produk 1</option><option value="">Produk 2</option><option value="">Produk 3</option></select></td><td><select name="" id="" class="form-control unit"><option value="">Unit 1</option><option value="">Unit 2</option><option value="">Unit 3</option></select></td><td><input type="number" name="jml['+i+']" id="jml" class="form-control"></td><td><button class="btn btn-primary" data-id="" data-jml="" id="btn_plus"><i class="fas fa-qrcode"></i> Tambah No Seri</button>&nbsp;<button class="btn btn-danger btn-delete"><i class="fas fa-trash"></i> Delete</button></td></tr>';
        $('.add_sparepart_table tbody').append(table_sparepart);
        $('.produk').select2();
        $('.unit').select2();
    });
    $(document).on('click','.add_unit', function () {
        i++;
        let table_unit = '<tr><td><select name="" id="" class="form-control produk"><option value="">Produk 1</option><option value="">Produk 2</option><option value="">Produk 3</option></select></td><td><input type="number" name="" id="jum" class="form-control"></td><td><button class="btn btn-primary" id="btnPlus"><i class="fas fa-qrcode"></i> Tambah No Seri</button>&nbsp;<button class="btn btn-danger btn-delete"><i class="fas fa-trash"></i> Delete</button></td></tr>';
        $('.add_unit_table tbody').append(table_unit);
        $('.produk').select2();
    });
    $(document).on('click', '.btn-delete', function (e) {
        $(this).parent().parent().remove();
        var check = $('tbody.tambah_data tr').length;
    });

    $(document).ready(function () {
        $('.table-rancangan').DataTable({
            "ordering": false,
            "autoWidth": false,
            searching: false,
            "lengthChange": false,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
        });
        $('.dari').select2({});
    });
    function terima() {
    Swal.fire({
        title: "Apakah anda yakin?",
        text: "Data yang sudah di terima tidak dapat diubah!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        showCancelButton: true,
    }).then((result) => {
        if (result.value) {
            Swal.fire(
                'Terima!',
                'Data berhasil diterima!',
                'success'
            )
        }else{
            Swal.fire(
                'Batal!',
                'Data tidak berhasil diterima!',
                'error'
            )
        }
    });
 }
function rancang() {
    Swal.fire({
        title: "Apakah anda yakin?",
        text: "Data yang sudah di rancang tidak dapat diubah!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        showCancelButton: true,
    }).then((result) => {
        if (result.value) {
            Swal.fire(
                'Rancang!',
                'Data berhasil diterima!',
                'success'
            );
        }else{
            Swal.fire(
                'Batal!',
                'Data tidak berhasil diterima!',
                'error'
            );
        }
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
        }else{
            Swal.fire(
                'Batal!',
                'Data tidak berhasil dibatalkan!',
                'error'
                );
        }
    });
}
</script>
@stop