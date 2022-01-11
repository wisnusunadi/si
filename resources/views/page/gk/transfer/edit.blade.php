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
            <input type="hidden" name="" id="user_id" value="{{ Auth::user()->id }}">
            {{-- <input type="hidden" name="" id="kode" value="{{ $did->id }}"> --}}
            <div class="card">
                <div class="card-body">
                    <div class="form-row">
                        <input type="hidden" name="" id="kode" value="{{ $data->id }}">
                        <div class="form-group col">
                            <label for="tanggal">Tanggal Keluar</label>
                            <input type="text" name="date_out" id="datePicker" class="form-control" placeholder=""
                                readonly>
                        </div>
                        <div class="form-group col">
                            <label for="dari">Dari</label>
                            <select class="form-control dari" name="dari" disabled>
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
                                            <th style="width: 150px">Nama Produk</th>
                                            {{-- <th style="width: 150px">Unit</th> --}}
                                            <th style="width: 200px">Jumlah</th>
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
                                <table class="table table-striped scan-produk1">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" name="select_all" id="head-all"></th>
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

<div class="modal fade modalAddSparepartEdit" id="" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
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
                                <table class="table table-striped scan-produk1-edit">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" name="select_all" id="example-select-all"></th>
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
                <button type="button" id="btnSeriEdit" class="btn btn-primary">Simpan</button>
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

<div class="modal fade modalAddUnitEdit" id="" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
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
                                <table class="table table-striped scan-produk-edit">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" name="select_all" id="unit-all"></th>
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
                <button type="button" id="btnEditUnit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Index --}}
<div class="modal fade modal_transfer" id="" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
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
                            <label for="">Keterangan</label>
                            <textarea name="deskripsi" id="tujuan" cols="10" rows="5" class="form-control"></textarea>
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

<div class="modal fade modal_transfer1" id="" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
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
                            <label for="">Keterangan</label>
                            <textarea name="deskripsi" id="tujuan" cols="10" rows="5" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary simpan1">Simpan</button>
            </div>
        </div>
    </div>
</div>
@stop

@section('adminlte_js')
<script>
    var kodee = $('#kode').val();
    // console.log(kodee);
    $.ajax({
        url: '/api/gbj/sel-divisi',
        type: 'GET',
        dataType: 'json',
        success: function (res) {
            $.each(res, function (key, value) {
                $(".dari").append('<option value="' + value.id + '">' + value.nama +
                    '</option');
            });
        }
    });
    getData();
    // Date
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0 so need to add 1 to make it 1!
    var yyyy = today.getFullYear();
    if (dd < 10) {
        dd = '0' + dd
    }
    if (mm < 10) {
        mm = '0' + mm
    }

    today = yyyy + '-' + mm + '-' + dd;
    document.getElementById("datePicker").setAttribute("max", today);

    // document.getElementById('datePicker').valueAsDate = new Date();
    var i = 0;

    var ii = 0;
    var kk = 0;

    const seri = {};
    const seri_unit = {};
    let spr_arr = [];
    let unit_arr = [];

    $(document).on('click', '.pluss', function (e) {
        e.preventDefault();
        var jumlah = parseInt($(this).next().val());
        jumlah += 1;
        $(this).parent().find('input.jumlah').val(jumlah);
        $(this).next().val(jumlah);
    });

    $(document).on('click','.minuss', function (e) {
        e.preventDefault();
        var batas = parseInt($(this).parent().find('input.batas').val());
        var jumlah = parseInt($(this).prev().val());
        if (jumlah > batas) {
            jumlah -= 1;
            $(this).prev().val(jumlah);
        }
    });
    function addSpare(a) {
        var b = $(".btn_plus" + a).parent().prev().children().find('input.jumlah').val();
        var c = $(".btn_plus" + a).parent().prev().prev().children().val();
        addSparepart(b, a, c);
    }

    function clickSparepart(c, d, e) {
        console.log(d);
        var tableScan = $('.scan-produk1').dataTable({
            "destroy": true,
            "ordering": false,
            "autoWidth": false,
            searching: false,
            "lengthChange": false,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            },
        });

        var arrSparepart = []
        var seriSparepart = [];
        var kerusakanSparepart = [];
        var tingkatSparepart = [];
        // No Seri
        const data = tableScan.$('.seri').map(function () {
            return $(this).val();
        }).get();

        data.forEach(function (item) {
            if (item != '') {
                arrSparepart.push(item);
            }
        })

        // Data Null
        const ker = tableScan.$('.remark').map(function () {
            return $(this).val();
        }).get();

        const ting = tableScan.$('.layout_id').map(function () {
            return $(this).val();
        }).get();

        // No Seri
        data.forEach(function (item) {
            if (item == '') {
                seriSparepart.push(item);
            }
        })

        // Kerusakan
        ker.forEach(function (item) {
            if (item == '') {
                kerusakanSparepart.push(item);
            }
        })

        // Tingkat
        ting.forEach(function (item) {
            if (item == '') {
                tingkatSparepart.push(item);
            }
        })

        const count = arr =>
            arr.reduce((a, b) => ({
                ...a,
                [b]: (a[b] || 0) + 1
            }), {})

        const duplicates = dict =>
            Object.keys(dict).filter((a) => dict[a] > 1)

        if (duplicates(count(arrSparepart)).length > 0 || duplicates(count(seriSparepart)).length > 0 || duplicates(
                count(tingkatSparepart)).length > 0 || duplicates(count(kerusakanSparepart)).length > 0) {
            $('.seri').removeClass('is-invalid');
            $('.remark').removeClass('is-invalid');
            $('.layout_id').removeClass('is-invalid');
            $('.seri').filter(function () {
                return $(this).val() == '';
            }).addClass('is-invalid');
            $('.remark').filter(function () {
                return $(this).val() == '';
            }).addClass('is-invalid');
            $('.layout_id').filter(function () {
                return $(this).val() == '';
            }).addClass('is-invalid');
        }
        if (duplicates(count(arrSparepart)).length > 0 || duplicates(count(seriSparepart)).length > 0) {
            $('.seri').removeClass('is-invalid');
            $('.seri').filter(function () {
                $('.seri').filter(function () {
                    for (let index = 0; index < duplicates(count(arrSparepart)).length; index++) {
                        if ($(this).val() == duplicates(count(arrSparepart))[index]) {
                            return true;
                        }
                    }
                }).addClass('is-invalid');
            }).addClass('is-invalid');
            $('.seri').filter(function () {
                return $(this).val() == '';
            }).addClass('is-invalid');
            if (duplicates(count(arrSparepart)).length > 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Nomor seri ' + duplicates(count(arrSparepart)) + ' ada yang sama.',
                })
            }
        }
        if ((duplicates(count(kerusakanSparepart)).length == 0 && duplicates(count(seriSparepart)).length == 0 &&
                duplicates(count(tingkatSparepart)).length == 0 && duplicates(count(arrSparepart)).length == 0) ==
            true) {
            $('.seri').removeClass('is-invalid');
            $('.remark').removeClass('is-invalid');
            $('.layout_id').removeClass('is-invalid');
            seri[d] = {"jumlah" : e, "noseri": []};
            const ids = [];
            $('.cb-child-new').each(function() {
                if ($(this).is(':checked')) {
                    if ($('.cb-child-new').filter(':checked').length > e) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Ubah Terlebih Dahulu Jumlah yang Ditransfer'
                        })
                    } else {
                        ids.push($(this).val());
                        seri[d].noseri = ids;
                        console.log(seri);
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Nomor seri tersimpan',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        $('.modalAddSparepart').modal('hide');
                    }
                }
            })
        }
    }

    function addSparepart(x, y, z) {
        $('.modalAddSparepart').modal('show');
        $('.modalAddSparepart').find('#btnSeri').attr('onclick', 'clickSparepart(' + y + ',' + z + ',' + x + ')');
        $('.modalAddSparepart').on('shown.bs.modal', function () {
            $(this).find('tbody input.seri').first().focus();
        })
        $('.scan-produk1').DataTable().destroy();
        // $('.scan-produk1 tbody').empty();
        // for (let index = 0; index < x; index++) {
        //     ii++;
        //     $('.scan-produk1 tbody').append('<tr id="row' + ii + '"><td><input type="text" name="noseri[][' + ii +
        //         ']" id="noseri' + ii +
        //         '" maxlength="13" class="form-control seri"><div class="invalid-feedback">Nomor seri ada yang sama atau kosong.</div></td><td><input type="text" name="remark[][' +
        //         ii + ']" id="remark' + ii +
        //         '" class="form-control remark"><div class="invalid-feedback">Kerusakan Tidak Boleh Kosong.</div></td><td><select name="layout_id[][' +
        //         ii + ']" id="layout_id' + ii +
        //         '" class="form-control layout_id"><option value="" selected>Pilih Level</option><option value="1">Level 1</option><option value="2">Level 2</option><option value="3">Level 3</option></select><div class="invalid-feedback">Silahkan pilih tingkat kerusakan.</div></td></tr>'
        //     );
        // }
        $('.scan-produk1').DataTable({
            "destroy": true,
            "ordering": false,
            "autoWidth": false,
            searching: false,
            "lengthChange": false,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            },
            ajax: {
                url: "/api/gk/getseri/spr",
                data: {
                    sparepart_id: z,
                },
                type: "post",
            },
            columns: [
                {data: 'kodenew'},
                {data: 'seri'},
                {data: 'note'},
                {data: 'tingkat'},
            ],
        });

        setTimeout(() => {
            let panjang_table1 = $('.scan-produk1 input.cb-child').length;
            console.log(panjang_table1);
            if (x > panjang_table1) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Melebihi Batas Maksimal atau Data Kosong'
                }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $('.modalAddSparepart').modal('hide');
                    }
                })
            }
        }, 800);
    }

    // Unit
    function addUn(l) {
        var j = $(".btnPlus" + l).parent().prev().children().find('.jumlah').val();
        var k = $(".btnPlus" + l).parent().prev().prev().children().val();
        addUnit(j, k);
    }

    function clickUnit(c) {
        console.log(c);
        var tableUnit = $('.scan-produk').DataTable({
            "destroy": true,
            "ordering": false,
            "autoWidth": false,
            searching: false,
            "lengthChange": false,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
        });

        if ((duplicates(count(kerusakanUnit)).length == 0 && duplicates(count(seriUnit)).length == 0 && duplicates(
                count(tingkatUnit)).length == 0 && duplicates(count(arrUnit)).length == 0) == true) {
            $('.seri').removeClass('is-invalid');
            $('.kerusakan').removeClass('is-invalid');
            $('.tingkat').removeClass('is-invalid');
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Nomor seri tersimpan',
                showConfirmButton: false,
                timer: 1500
            }).then(function () {
                $('.scan-produk tbody tr').each((index, value) => {
                    const obj1 = {
                        noseri: value.childNodes[0].firstChild.value,
                        kerusakan: value.childNodes[1].firstChild.value,
                        tingkat: value.childNodes[2].firstChild.value,
                    }
                    unit_arr.push(obj1);
                })
                seri_unit[c] = unit_arr;
                console.log(seri_unit)
                $('.modalAddUnit').modal('hide');
            })
        }
    }

    function addUnit(x, y, z) {
        $('.modalAddUnit').modal('show');
        $('.modalAddUnit').find('#btnAddUnit').attr('onclick', 'clickUnit(' + y + ')');
        $('.modalAddUnit').on('shown.bs.modal', function () {
            $(this).find('tbody input.seri').first().focus();
        })
        $('.scan-produk').DataTable().destroy();
        // $('.scan-produk tbody').empty();
        // for (let index = 0; index < x; index++) {
        //     kk++;
        //     $('.scan-produk tbody').append('<tr id="u' + kk + '"><td><input type="text" name="noseri[][' + kk +
        //         ']" id="noseri' + kk +
        //         '" class="form-control seri"><div class="invalid-feedback">Nomor seri ada yang sama.</div></td><td><input type="text" name="remark[][' +
        //         kk + ']" id="remark' + kk +
        //         '" class="form-control kerusakan"><div class="invalid-feedback">Kerusakan Tidak Boleh Kosong.</div></td><td><select name="tk_kerusakan[][' +
        //         kk +
        //         ']" id="tk_kerusakan' + kk +
        //         '" class="form-control tingkat"><option value="" selected>Pilih Level</option><option value="1">Level 1</option><option value="2">Level 2</option><option value="3">Level 3</option></select><div class="invalid-feedback">Silahkan pilih tingkat kerusakan.</div></td></tr>'
        //     );
        // }
        $('.scan-produk').DataTable({
            "ordering": false,
            "autoWidth": false,
            searching: false,
            "lengthChange": false,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
        });

        setTimeout(() => {
            let panjang_table2 = $('.scan-produk input.cb-unit').length;
            console.log(panjang_table2);
            if (x > panjang_table2) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Melebihi Batas Maksimal atau Data Kosong'
                }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $('.modalAddSparepart').modal('hide');
                    }
                })
            }
        }, 800);
    }

    function transfer() {
        Swal.fire({
            title: "Apakah anda yakin?",
            text: "Data yang sudah di transfer tidak dapat diubah!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        });
    };

    var nmrspr = 1;
    $(document).on('click', '.add_sparepart', function () {
        $.ajax({
            url: '/api/gk/gkspr',
            type: 'POST',
            dataType: 'json',
            success: function (res) {
                // ii++;
                console.log(res);
                $.each(res, function (key, value) {
                    // $("#change_layout").append('<option value="'+value.id+'">'+value.ruang+'</option');
                    $(".produk").append('<option value="' + value.sparepart_id + '">' +
                        value
                        .nama + '</option');
                });
            }
        });
        i++;
        let table_sparepart = '<tr id=' + nmrspr + '><td><select name="sparepart_id[]" id="sparepart_idd' +
            nmrspr +
            '" class="form-control produk"></select></td><td><div class="d-flex"><button type="button" class="btn btn-secondary pluss"><i class="fas fa-plus"></i></button>&nbsp;<input type="text" name="qty_spr[]" id="jml" class="form-control jumlah" value="1" readonly>&nbsp;<button type="button" class="btn btn-secondary minuss"><i class="fas fa-minus"></i></button><input type="text" name="" id="" class="batas" value="1" hidden></div></td><td><button class="btn btn-primary btn_plus' +
            nmrspr + '" data-id="" data-jml="" id="" onclick=addSpare(' + nmrspr +
            ')><i class="fas fa-qrcode"></i> Tambah No Seri</button>&nbsp;<button class="btn btn-danger btn-delete"><i class="fas fa-trash"></i> Delete</button></td></tr>';

        $('.add_sparepart_table tbody').append(table_sparepart);
        $('#sparepart_idd' + nmrspr).select2();
        nmrspr++;
    });

    var nmrunt = 1;
    $(document).on('click', '.add_unit', function () {
        $.ajax({
            url: '/api/gk/gkunit',
            type: 'post',
            dataType: 'json',
            success: function (res) {
                console.log(res);
                $.each(res, function (key, value) {
                    $(".produkk").append('<option value="' + value.gbj_id + '">' + value
                        .name + '</option');
                });
            }
        });
        i++;
        let table_unit = '<tr id=' + nmrunt + '><td><select name="gbj_id[]" id="gbj_idd' + nmrunt +
            '" class="form-control produkk"></select></td><td><div class="d-flex"><button type="button" class="btn btn-secondary pluss"><i class="fas fa-plus"></i></button>&nbsp;<input type="text" name="qty_unit[]" id="jum" class="form-control jumlah" value="1" readonly>&nbsp;<button type="button" class="btn btn-secondary minuss"><i class="fas fa-minus"></i></button><input type="text" name="" id="" class="batas" value="1" hidden></div></td><td><button class="btn btn-primary btnPlus' +
            nmrunt + '" id="" onclick=addUn(' + nmrunt +
            ')><i class="fas fa-qrcode"></i> Tambah No Seri</button>&nbsp;<button class="btn btn-danger btn-delete"><i class="fas fa-trash"></i> Delete</button></td></tr>';
        $('.add_unit_table tbody').append(table_unit);
        $('#gbj_idd' + nmrunt).select2();
        nmrunt++;
    });

    $(document).on('click', '.btn-delete', function (e) {
        $(this).parent().parent().remove();
        var check = $('tbody.tambah_data tr').length;
        // delete seri[$(this).val()].noseriv
    });

    $(document).ready(function () {
        $('.table-rancangan').DataTable({
            "ordering": false,
            "autoWidth": false,
            searching: false,
            "lengthChange": false,
            processing: true,
            serverSide: true,
            destroy: true,
            ajax: {
                url: "/api/gk/draft-terima",
                type: "post",
            },
            columns: [{
                    data: "in"
                },
                {
                    data: "from"
                },
                {
                    data: "aksi"
                },
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
        });
        $('.dari').select2({});

        $("#example-select-all").on('click', function () {
            var isChecked = $("#example-select-all").prop('checked')
            $('.cb-child').prop('checked', isChecked)
        });

        $("#head-all").on('click', function () {
            var isChecked = $("#head-all").prop('checked')
            $('.cb-child-new').prop('checked', isChecked)
        });

        $("#unit-all").on('click', function () {
            var isChecked = $("#unit-all").prop('checked')
            $('.cb-unit').prop('checked', isChecked)
        });
    });

    function modalTerima() {
        $('.modal_transfer1').modal('show');
        $('.catatan').val('');
        $('.list-group').children().remove();
        $('.judul_modal').text('Silahkan isi tujuan transfer produk');
        $(document).on('click', '.remove', function () {
            $(this).parent().parent().remove();
        });
        $(document).on('click', '.simpan1', function () {
            let out = $('#datePicker').val();
            let to = $('.dari').val();
            let tujuan = $('#tujuan').val();
            console.log(out);
            console.log(to);
            console.log(tujuan);
            const spr1 = [];
            const jml = [];
            const unit1 = [];
            const jum = [];
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
                    $.ajax({
                        url: "/api/gk/updateTransfer",
                        type: "post",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            userid : $('#user_id').val(),
                            id: kodee,
                            date_out: out,
                            ke: to,
                            deskripsi: tujuan,
                            kodespr: $('#kodespr').val(),
                            data: seri,
                            kodeunit: $('#kodeunit').val(),
                            dataunit: seri_unit,
                        },
                        success: function (res) {
                            console.log(res);
                        },
                    })
                    setTimeout(() => {
                        window.location.href = "/gk/transfer"
                    }, 1000);
                } else {
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
        $(document).on('click', '.remove', function () {
            $(this).parent().parent().remove();
        });
        $(document).on('click', '.simpan', function () {
        let out = $('#datePicker').val();
        let to = $('.dari').val();
        let tujuan = $('#tujuan').val();
        console.log(out);
        console.log(to);
        console.log(tujuan);
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
                $.ajax({
                    url: "/api/gk/updateTransferDraft",
                    type: "post",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        userid : $('#user_id').val(),
                        id: kodee,
                        date_out: out,
                        ke: to,
                        deskripsi: tujuan,
                        kodespr: $('#kodespr').val(),
                        data: seri,
                        kodeunit: $('#kodeunit').val(),
                        dataunit: seri_unit,
                    },
                    success: function (res) {
                        console.log(res);
                    },
                })
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                Swal.fire(
                    'Data gagal di rancangan!',
                    '',
                    'error'
                );
                setTimeout(() => {
                    window.location.href = "/gk/transfer"
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
            } else {
                Swal.fire(
                    'Batal!',
                    'Data tidak berhasil dibatalkan!',
                    'error'
                );
                setTimeout(() => {
                    window.location.href = "/gk/transfer"
                }, 1000);
            }
        });
    }

    $(document).on('click', '.btn-delete-edit', function (e) {
        let id = $(this).parent().prev().prev().prev().val();
        console.log(id);
        // urutspr++;
        // console.log($('#kodespr'+urutspr)[0].value);
        // kodespr = $('#kodespr'+urutspr)[0].value;

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/api/gk/deleteDraftTransfer",
                    type: "post",
                    data: { id: id},
                    success: function(res) {
                        console.log(res);
                        Swal.fire(
                            'Deleted!',
                            'Your data has been deleted.',
                            'success'
                        )

                        // location.reload();
                    }
                })
                $(this).parent().parent().remove();
                var check = $('tbody.tambah_data tr').length;

            }
        })

    });

    // edit
    function getData() {
        var kode = $('#kode').val();
        $.ajax({
            url: "/api/gk/edit-out",
            data: {
                id: kode
            },
            type: "post",
            dataType: "json",
            success: function (res) {
                console.log(res);
                $('#datePicker').val(res.header.date_out);
                $('.dari').val(res.header.ke).change();
                var o = 0;
                $.each(res.spr, function (i, val) {
                    console.log(val.kode);
                    $.ajax({
                        url: '/api/gk/gkspr',
                        type: 'POST',
                        dataType: 'json',
                        success: function (res) {
                            $('#sparepart_id' + i).append(new Option(val.nama, val
                                .sparepart_id));
                            $.each(res, function (key, value) {
                                $("#sparepart_id" + i).append(
                                    '<option value="' + value.sparepart_id +
                                    '">' +
                                    value.nama + '</option');
                            });
                        }
                    });

                    $('.add_sparepart_table tbody').append('<tr id=' + i +
                        '><input type="hidden" name="id" id="kodespr" value="' + val
                        .kode +
                        '" class="kodespr"><td><select name="sparepart_id[]" id="sparepart_id' +
                        i +
                        '" class="form-control produk"></select></td><td><div class="d-flex"><button type="button" class="btn btn-secondary pluss"><i class="fas fa-plus"></i></button>&nbsp;<input type="text" name="qty_spr[]" id="jml" class="form-control jumlah" value="'+val.qty+'" readonly="">&nbsp;<button type="button" class="btn btn-secondary minuss"><i class="fas fa-minus"></i></button><input type="text" name="" id="" class="batas" value="'+val.qty+'" hidden=""></div></td><td><button class="btn btn-primary btn_edit' + i +'" data-id="" data-jml="" id="" onclick=editSpare(' + i +
                        ')><i class="fas fa-qrcode"></i> Tambah No Seri</button>&nbsp;<button class="btn btn-danger btn-delete-edit"><i class="fas fa-trash"></i> Delete</button></td></tr>'
                        );
                    $('#sparepart_id' + i).select2();
                })

                var oo = 0
                $.each(res.unit, function (i, val) {
                    oo++;
                    $.ajax({
                        url: '/api/gk/gkunit',
                        type: 'post',
                        dataType: 'json',
                        success: function (res) {
                            $('#gbj_id' + i).append(new Option(val.nama, val.gbj_id));
                            $.each(res, function (key, value) {
                                $('#gbj_id' + i).append('<option value="' +
                                    value.gbj_id + '">' + value.name +
                                    '</option');
                            });
                        }
                    });
                    $('.add_unit_table tbody').append('<tr id=' + i +
                        '><input type="hidden" name="id" id="kodeunit" value="' + val.kode +
                        '" class="kodeunit"><td><select name="gbj_id[]" id="gbj_id' + i +
                        '" class="form-control produkk"></select></td><td><div class="d-flex"><button type="button" class="btn btn-secondary pluss"><i class="fas fa-plus"></i></button>&nbsp;<input type="text" name="qty_unit[]" id="jum" class="form-control jumlah" value="'+val.qty+'" readonly="">&nbsp;<button type="button" class="btn btn-secondary minuss"><i class="fas fa-minus"></i></button><input type="text" name="" id="" class="batas" value="'+val.qty+'" hidden=""></div></td><td><button class="btn btn-primary btnEdit' + i +
                        '" id="" onclick=editUn(' + i +
                        ')><i class="fas fa-qrcode"></i> Tambah No Seri</button>&nbsp;<button class="btn btn-danger btn-delete-edit"><i class="fas fa-trash"></i> Delete</button></td></tr>'
                        );
                    $('#gbj_id' + i).select2();
                })
            }
        })
    }

    function editSpare(a) {
        var b = $(".btn_edit" + a).parent().prev().children().find('input.jumlah').val();
        var c = $(".btn_edit" + a).parent().prev().prev().children().val();
        editSparepart(b, a, c);
    }

    function clickSparepartEdit(c, d, e) {
        // console.log(e);
        console.log("test");
        console.log(c);
        var tableScan = $('.scan-produk1-edit').dataTable({
            "destroy": true,
            "ordering": false,
            "autoWidth": false,
            searching: false,
            "lengthChange": false,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            },
        });

        var arrSparepart = []
        var seriSparepart = [];
        var kerusakanSparepart = [];
        var tingkatSparepart = [];

        const data = tableScan.$('.seri').map(function () {
            return $(this).val();
        }).get();

        data.forEach(function (item) {
            if (item != '') {
                arrSparepart.push(item);
            }
        })

        const ker = tableScan.$('.remark').map(function () {
            return $(this).val();
        }).get();

        const ting = tableScan.$('.layout_id').map(function () {
            return $(this).val();
        }).get();

        data.forEach(function (item) {
            if (item == '') {
                seriSparepart.push(item);
            }
        })

        ker.forEach(function (item) {
            if (item == '') {
                kerusakanSparepart.push(item);
            }
        })

        ting.forEach(function (item) {
            if (item == '') {
                tingkatSparepart.push(item);
            }
        })

        const count = arr =>
            arr.reduce((a, b) => ({
                ...a,
                [b]: (a[b] || 0) + 1
            }), {})

        const duplicates = dict =>
            Object.keys(dict).filter((a) => dict[a] > 1)


        if (duplicates(count(arrSparepart)).length > 0 || duplicates(count(seriSparepart)).length > 0 || duplicates(
                count(tingkatSparepart)).length > 0 || duplicates(count(kerusakanSparepart)).length > 0) {
            $('.seri').removeClass('is-invalid');
            $('.remark').removeClass('is-invalid');
            $('.layout_id').removeClass('is-invalid');
            $('.seri').filter(function () {
                return $(this).val() == '';
            }).addClass('is-invalid');
            $('.remark').filter(function () {
                return $(this).val() == '';
            }).addClass('is-invalid');
            $('.layout_id').filter(function () {
                return $(this).val() == '';
            }).addClass('is-invalid');
        }

        if (duplicates(count(arrSparepart)).length > 0 || duplicates(count(seriSparepart)).length > 0) {
            $('.seri').removeClass('is-invalid');
            $('.seri').filter(function () {
                for (let index = 0; index < duplicates(count(arrSparepart)).length; index++) {
                    if ($(this).val() == duplicates(count(arrSparepart))[index]) {
                        return true;
                    }
                }
            }).addClass('is-invalid');
            $('.seri').filter(function () {
                return $(this).val() == '';
            }).addClass('is-invalid');
            if (duplicates(count(arrSparepart)).length > 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Nomor seri ' + duplicates(count(arrSparepart)) + ' ada yang sama.',
                })
            }
        }


        if ((duplicates(count(kerusakanSparepart)).length == 0 && duplicates(count(seriSparepart)).length == 0 &&
                duplicates(count(tingkatSparepart)).length == 0 && duplicates(count(arrSparepart)).length == 0) ==
            true) {
            $('.seri').removeClass('is-invalid');
            $('.remark').removeClass('is-invalid');
            $('.layout_id').removeClass('is-invalid');
            seri[d] = {"jumlah" : e, "noseri": []};
            const ids = [];
            $('.cb-child').each(function() {
                if ($(this).is(':checked')) {
                    if ($('.cb-child').filter(':checked').length > e) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Ubah Terlebih Dahulu Jumlah yang Ditransfer'
                        })
                    } else {
                        ids.push($(this).val());
                        seri[d].noseri = ids;
                        console.log(seri);
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Nomor seri tersimpan',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        $('.modalAddSparepartEdit').modal('hide');
                    }
                }
            })

            if (ids.length == 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Pilih Nomor Seri yang akan ditransfer'
                })
            }
        }
    }

    function editSparepart(x, y, z) {
        console.log("test");
        console.log(x);
        $('.modalAddSparepartEdit').modal('show');
        $('.modalAddSparepartEdit').find('#btnSeriEdit').attr('onclick', 'clickSparepartEdit(' + y + ',' + z + ',' + x + ')');
        $('.modalAddSparepartEdit').on('shown.bs.modal', function () {
            $(this).find('tbody input.seri').first().focus();
        })
        $('.scan-produk1-edit').DataTable({
            destroy: true,
            "ordering": false,
            "autoWidth": false,
            searching: false,
            "lengthChange": false,
            ajax: {
                url: "/api/gk/editseri-out",
                type: "post",
                data: {
                    id: $('#kode').val(),
                    sparepart_id: z
                },
            },
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            },
            columns: [
                {data: 'kode'},
                {data: 'seri'},
                {data: 'note'},
                {data: 'tingkat'},
            ]
        });

        setTimeout(() => {
            let panjang_table1 = $('.scan-produk1-edit input.cb-child').length;
            console.log(panjang_table1);
            if (x > panjang_table1) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Melebihi Batas Maksimal atau Data Kosong'
                }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $('.modalAddSparepart').modal('hide');
                    }
                })
            }
        }, 800);
    }

    // Unit
    function editUn(l) {
        var j = $(".btnEdit" + l).parent().prev().children().find('input.jumlah').val();
        var k = $(".btnEdit" + l).parent().prev().prev().children().val();
        editUnit(j, k, l);
    }

    function clickUnitEdit(c, e) {
        console.log('jumlah ' +e);
        var tableUnit = $('.scan-produk-edit').DataTable({
            "destroy": true,
            "ordering": false,
            "autoWidth": false,
            searching: false,
            "lengthChange": false,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
        });

        var arrUnit = [];
        var seriUnit = [];
        var kerusakanUnit = [];
        var tingkatUnit = [];

        const dataUnit = tableUnit.$('.seri').map(function () {
            return $(this).val();
        }).get();

        const ker = tableUnit.$('.kerusakan').map(function () {
            return $(this).val();
        }).get();

        const ting = tableUnit.$('.tingkat').map(function () {
            return $(this).val();
        }).get();

        dataUnit.forEach(function (item) {
            if (item != '') {
                arrUnit.push(item);
            }
        });

        dataUnit.forEach(function (item) {
            if (item == '') {
                seriUnit.push(item);
            }
        });

        ker.forEach(function (item) {
            if (item == '') {
                kerusakanUnit.push(item);
            }
        });

        ting.forEach(function (item) {
            if (item == '') {
                tingkatUnit.push(item);
            }
        });

        const count = arr =>
            arr.reduce((a, b) => ({
                ...a,
                [b]: (a[b] || 0) + 1
            }), {})

        const duplicates = dict =>
            Object.keys(dict).filter((a) => dict[a] > 1)

        if (duplicates(count(arrUnit)).length > 0 || duplicates(count(seriUnit)).length > 0 || duplicates(count(
                kerusakanUnit)).length > 0 || duplicates(count(tingkatUnit)).length > 0) {
            $('.seri').removeClass('is-invalid');
            $('.kerusakan').removeClass('is-invalid');
            $('.tingkat').removeClass('is-invalid');
            $('.seri').filter(function () {
                return $(this).val() == '';
            }).addClass('is-invalid');
            $('.kerusakan').filter(function () {
                return $(this).val() == '';
            }).addClass('is-invalid');
            $('.tingkat').filter(function () {
                return $(this).val() == '';
            }).addClass('is-invalid');
        }

        if (duplicates(count(arrUnit)).length > 0 || duplicates(count(seriUnit)).length > 0) {
            $('.seri').removeClass('is-invalid');
            $('.seri').filter(function () {
                for (let index = 0; index < duplicates(count(arrUnit)).length; index++) {
                    if ($(this).val() == duplicates(count(arrUnit))[index]) {
                        return true;
                    }
                }
            }).addClass('is-invalid');
            $('.seri').filter(function () {
                return $(this).val() == '';
            }).addClass('is-invalid');
            if (duplicates(count(arrUnit)).length > 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Nomor seri ' + duplicates(count(arrUnit)) + ' ada yang sama.',
                })
            }
        }

        if ((duplicates(count(kerusakanUnit)).length == 0 && duplicates(count(seriUnit)).length == 0 && duplicates(
                count(tingkatUnit)).length == 0 && duplicates(count(arrUnit)).length == 0) == true) {
            $('.seri').removeClass('is-invalid');
            $('.kerusakan').removeClass('is-invalid');
            $('.tingkat').removeClass('is-invalid');
            seri_unit[c] = {"jumlah" : e, "noseri": []};
            const ids = [];
            $('.cb-unit').each(function() {
                if ($(this).is(':checked')) {
                    if ($('.cb-unit').filter(':checked').length > e) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Ubah Terlebih Dahulu Jumlah yang Ditransfer'
                        })
                    } else {
                        ids.push($(this).val());
                        seri_unit[c].noseri = ids;
                        console.log(seri_unit);
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Nomor seri tersimpan',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        $('.modalAddUnitEdit').modal('hide');
                    }
                }
            })

            if (ids.length == 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Pilih Nomor Seri yang akan ditransfer'
                })
            }
                
            }
            // Swal.fire({
            //     position: 'center',
            //     icon: 'success',
            //     title: 'Nomor seri tersimpan',
            //     showConfirmButton: false,
            //     timer: 1500
            // }).then(function () {
            //     console.log('ok');
            //     $('.scan-produk-edit tbody tr').each((index, value) => {
            //         const obj1 = {
            //             noseri: value.childNodes[0].firstChild.value,
            //             kerusakan: value.childNodes[1].firstChild.value,
            //             tingkat: value.childNodes[2].firstChild.value,
            //         }
            //         unit_arr.push(obj1);
            //     })
            //     seri_unit[c] = unit_arr;
            //     unit_arr = [];
            //     console.log(seri_unit)
            //     $('.modalAddUnitEdit').modal('hide');
            // })
        }
    }

    function editUnit(x, y, z) {
        console.log("edit unit");
        console.log(x, y);
        $('.modalAddUnitEdit').modal('show');
        $('.modalAddUnitEdit').find('#btnEditUnit').attr('onclick', 'clickUnitEdit(' + y + ',' + x + ')');
        $('.modalAddUnitEdit').on('shown.bs.modal', function () {
            $(this).find('tbody input.seri').first().focus();
        })
        $('.scan-produk-edit').DataTable({
            destroy: true,
            "ordering": false,
            "autoWidth": false,
            searching: false,
            "lengthChange": false,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            },
            ajax: {
                url: "/api/gk/editseriunit-out",
                type: "post",
                data: {
                    id: $('#kode').val(),
                    gbj_id: y
                },
            },
            columns: [
                {data: 'kode'},
                {data: 'seri'},
                {data: 'note'},
                {data: 'tingkat'},
            ]
        });

        setTimeout(() => {
            let panjang_table2 = $('.scan-produk-edit input.cb-unit').length;
            console.log(panjang_table2);
            if (x > panjang_table2) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Melebihi Batas Maksimal atau Data Kosong'
                }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $('.modalAddUnitEdit').modal('hide');
                    }
                })
            }
        }, 800);
    }

    $(document).on('click', '.cb-child', function() {
        if ($(this).is(':checked')) {
            $.ajax({
                url: "/api/gk/check",
                type: "post",
                data: { id: $(this).val() },
                success: function(res) {

                }
            })
        } else {
            $.ajax({
                url: "/api/gk/uncheck",
                type: "post",
                data: { id: $(this).val() },
                success: function(res) {

                }
            })
        }
    })

    $(document).on('click', '.cb-unit', function() {
        if ($(this).is(':checked')) {
            $.ajax({
                url: "/api/gk/check",
                type: "post",
                data: { id: $(this).val() },
                success: function(res) {

                }
            })
        } else {
            $.ajax({
                url: "/api/gk/uncheck",
                type: "post",
                data: { id: $(this).val() },
                success: function(res) {

                }
            })
        }
    })
</script>
@stop
