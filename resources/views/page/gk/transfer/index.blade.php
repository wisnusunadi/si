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
        height: 450px;
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
        <h1>Transfer Produk Gudang Karantina</h1>
    </div><!-- /.container-fluid -->
</section>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <nav>
                    <div class="nav nav-tabs topnav" id="nav-tab" role="tablist">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                            aria-controls="home" aria-selected="true">Daftar Transfer</a>
                        <a id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile"
                            aria-selected="false">Pembuatan Transfer</a>
                    </div>
                </nav>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>

<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered table-rancangan">
                        <thead class="">
                            <tr>
                                <th style="width: 220px">Tanggal Keluar</th>
                                <th>Ke</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- <tr>
                                <td scope="row">10-04-2021</td>
                                <td>Divisi IT</td>
                                <td><a href="{{ url('gk/transfer/1') }}" class="btn btn-outline-info"><i
                                            class="far fa-edit"></i>Edit Produk</a></td>
                            </tr> --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
        <section class="content">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group col">
                                    <label for="tanggal">Tanggal Keluar</label>
                                    <input type="date" name="date_in" id="datePicker" class="form-control"
                                        placeholder="">
                                </div>
                                <div class="form-group col">
                                    <label for="dari">Ke</label>
                                    <select class="form-control dari" name="dari">
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
</div>
</div>

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
                                    <div class="card-body date_out">
                                        23-09-2021
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Nama Produk</label>
                                <div class="card" style="background-color: #F89F81">
                                    <div class="card-body prd">
                                        Produk 1
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm">
                                <label for="">Unit</label>
                                <div class="card" style="background-color: #FFCC83">
                                    <div class="card-body unit">
                                        Unit 1
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Dari</label>
                                <div class="card" style="background-color: #FFE0B4">
                                    <div class="card-body divisi">
                                        Divisi IT
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Jumlah</label>
                                <div class="card" style="background-color: #FFECB2">
                                    <div class="card-body jumlah_spr">
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
                                            <th><input type="checkbox" id="head-cb"></th>
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
                <button type="button" class="btn btn-primary " id="btnSeri">Simpan</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Add Unit --}}
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
                                    <div class="card-body out_unit">
                                        23-09-2021
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Nama Produk</label>
                                <div class="card" style="background-color: #F89F81">
                                    <div class="card-body prd_unit">
                                        Produk 1
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm">
                                <label for="">Dari</label>
                                <div class="card" style="background-color: #FFE0B4">
                                    <div class="card-body unit_divisi">
                                        Divisi IT
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Jumlah</label>
                                <div class="card" style="background-color: #FFECB2">
                                    <div class="card-body jml1">
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
                                            <th><input type="checkbox" id="head-cb1"></th>
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
                <button type="button" class="btn btn-primary" id="btnAddUnit">Simpan</button>
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
                            <label for="">Tujuan</label>
                            <textarea name="tujuan" id="tujuan_draft" cols="10" rows="5"
                                class="form-control"></textarea>
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
                            <label for="">Tujuan</label>
                            <textarea name="tujuan" id="tujuan_tf" cols="10" rows="5" class="form-control"></textarea>
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

    document.getElementById('datePicker').valueAsDate = new Date();
    var i = 0;

    var ii = 0;
    var kk = 0;

    const seri = {};
    const seri_unit = {};
    let spr_arr = [];
    let unit_arr = [];

    function addSpare(a) {
        var b = $(".btn_plus" + a).parent().prev().children().val();
        var c = $(".btn_plus" + a).parent().prev().prev().children().val();
        addSparepart(b, a, c);
    }

    function clickSparepart(c, d, e) {
        // alert(e);
        console.log('spr '+d);
        var tableScan = $('.scan-produk1').dataTable({
            "destroy": true,
            "ordering": false,
            "autoWidth": false,
            searching: false,
            "lengthChange": false,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
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
            const ids = [];
            $('.cb-child').each(function() {
            if($(this).is(":checked")) {
                // cek validasi
                if ($('.cb-child').filter(':checked').length > e) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Melebihi Batas Maksimal'
                    })
                } else {
                    ids.push($(this).val());
                    seri[d] = ids;
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
        // console.log($('#sparepart_id :selected').text());
        $('.jumlah_spr').text(x + ' Unit')
        $('.date_out').text(document.getElementsByName("date_in")[0].value)
        $('.divisi').text(document.getElementsByName("dari")[0].selectedOptions[0].dataset.name)
        $('.modalAddSparepart').modal('show');
        $('.modalAddSparepart').find('#btnSeri').attr('onclick', 'clickSparepart(' + y + ',' + z + ',' + x + ')');
        $('.modalAddSparepart').on('shown.bs.modal', function () {
            $(this).find('tbody input.seri').first().focus();
        })
        $('.scan-produk1').DataTable().destroy();
        // $('.scan-produk1 tbody').empty();
        // for (let index = 0; index < x; index++) {
        //     ii++;
        //     $('.scan-produk1 tbody').append('<tr><td>' + ii +
        //         '</td><td>noseri1</td><td>srgrgrg</td><td>Level 1</td></tr>');
        // }
        $('.scan-produk1').DataTable({
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
                {data: 'kode'},
                {data: 'seri'},
                {data: 'note'},
                {data: 'tingkat'},
            ],

        });
    }

    // Unit
    function addUn(l) {
        var j = $(".btnPlus" + l).parent().prev().children().val();
        var k = $(".btnPlus" + l).parent().prev().prev().children().val();
        addUnit(j, k);
    }

    function clickUnit(c, p) {
        console.log('unit '+c);
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
            const uids = [];
            $('.cb-unit').each(function() {
            if($(this).is(":checked")) {
                // cek validasi
                if ($('.cb-unit').filter(':checked').length > p) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Melebihi Batas Maksimal'
                    })
                } else {
                    uids.push($(this).val());
                    seri_unit[c] = uids;
                    console.log(seri_unit);
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Nomor seri tersimpan',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    $('.modalAddUnit').modal('hide');
                }
            }

            })

        }
    }

    function addUnit(x, y, z) {
        // alert(x);
        console.log(y);
        $('.modalAddUnit').modal('show');
        $('.modalAddUnit').find('#btnAddUnit').attr('onclick', 'clickUnit(' + y + ', '+ x +')');
        $('.modalAddUnit').on('shown.bs.modal', function () {
            $(this).find('tbody input.seri').first().focus();
        })
        $('.scan-produk').DataTable().destroy();
        // $('.scan-produk tbody').empty();
        // for (let index = 0; index < x; index++) {
        //     kk++;
        //     $('.scan-produk tbody').append('<tr><td>' + ii +
        //         '</td><td>noseri1</td><td>srgrgrg</td><td>Level 1</td></tr>');
        // }
        $('.scan-produk').DataTable({
            "ordering": false,
            "autoWidth": false,
            searching: false,
            "lengthChange": false,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            },
            ajax: {
                url: "/api/gk/getseri/unit",
                data: {
                    gbj_id: y,
                },
                type: "post",
            },
            columns: [
                {data: 'kode'},
                {data: 'seri'},
                {data: 'note'},
                {data: 'tingkat'},
            ],

        });
    }

    $.ajax({
        url: '/api/gbj/sel-divisi',
        type: 'GET',
        dataType: 'json',
        success: function (res) {
            // ii++;
            console.log(res);
            $.each(res, function (key, value) {
                // $("#change_layout").append('<option value="'+value.id+'">'+value.ruang+'</option');
                $(".dari").append('<option value="' + value.id + '" data-name="'+ value.nama +'">' + value.nama +
                    '</option');
            });
        }
    });

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
        let table_sparepart =
            '<tr id='+nmrspr+'><td><select name="sparepart_id[]" id="sparepart_id'+nmrspr+'" class="form-control produk"></select></td><td><input type="text" name="qty_spr[]" id="jml" class="form-control number"></td><td><button class="btn btn-primary btn_plus' +
            nmrspr + '" data-id="" data-jml="" id="" onclick=addSpare(' + nmrspr +
            ')><i class="fas fa-qrcode"></i> Tambah No Seri</button>&nbsp;<button class="btn btn-danger btn-delete"><i class="fas fa-trash"></i> Delete</button></td></tr>';

        $('.add_sparepart_table tbody').append(table_sparepart);
        $('#sparepart_id'+nmrspr+'').select2();
        $(".number").inputFilter(function(value) {
            return /^\d*$/.test(value);    // Allow digits only, using a RegExp
        });
        nmrspr++;
    });

    var nmrunt = 1;
    $(document).on('click', '.add_unit', function () {
        $.ajax({
            url: '/api/gk/gkunit',
            type: 'post',
            dataType: 'json',
            success: function (res) {
                // ii++;
                console.log(res);
                $.each(res, function (key, value) {
                    // $("#change_layout").append('<option value="'+value.id+'">'+value.ruang+'</option');
                    $(".produkk").append('<option value="' + value.gbj_id + '">' + value
                        .name + '</option');
                });
            }
        });
        i++;
        let table_unit =
            '<tr id='+nmrunt+'><td><select name="gbj_id[]" id="gbj_id'+nmrunt+'" class="form-control produkk"></select></td><td><input type="text" name="qty_unit[]" id="jum" class="form-control number"></td><td><button class="btn btn-primary btnPlus' +
            nmrunt + '" id="" onclick=addUn(' + nmrunt +
            ')><i class="fas fa-qrcode"></i> Tambah No Seri</button>&nbsp;<button class="btn btn-danger btn-delete"><i class="fas fa-trash"></i> Delete</button></td></tr>';
        $('.add_unit_table tbody').append(table_unit);
        $('#gbj_id'+nmrunt+'').select2();
        $(".number").inputFilter(function(value) {
            return /^\d*$/.test(value);    // Allow digits only, using a RegExp
        });
        nmrunt++;
    });

    $(document).on('click', '.btn-delete', function (e) {
        $(this).parent().parent().remove();
        var check = $('tbody.tambah_data tr').length;
    });

    $(document).ready(function () {
        $("#head-cb").on('click', function () {
            var isChecked = $("#head-cb").prop('checked')
            $('.cb-child').prop('checked', isChecked)
        });

        $("#head-cb1").on('click', function () {
            var isChecked1 = $("#head-cb1").prop('checked')
            $('.cb-unit').prop('checked', isChecked1)
        });

        $('.table-rancangan').DataTable({
            "ordering": false,
            "autoWidth": false,
            searching: false,
            "lengthChange": false,
            processing: true,
            serverSide: true,
            destroy: true,
            ajax: {
                url: "/api/gk/draft-tf",
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
            let tujuan = $('#tujuan_tf').val();
            console.log(out);
            console.log(to);
            console.log(tujuan);
            const spr1 = [];
            const jml = [];
            const unit1 = [];
            const jum = [];
            $('select[name^="sparepart_id"]').each(function () {
                spr1.push($(this).val());
            });
            $('input[name^="qty_spr"]').each(function () {
                jml.push($(this).val());
            });
            // $('input[name^="noseri"]').each(function() {
            //     spr.push(seri_spr.push(seri1));
            // });
            $('select[name^="gbj_id"]').each(function () {
                unit1.push($(this).val());
            });
            $('input[name^="qty_unit"]').each(function () {
                jum.push($(this).val());
            });
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
                        url: "/api/gk/out-final",
                        type: "post",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            date_out: out,
                            ke: to,
                            deskripsi: tujuan,
                            sparepart_id: spr1,
                            qty_spr: jml,
                            noseri: seri,
                            gbj_id: unit1,
                            qty_unit: jum,
                            seriunit: seri_unit,
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

    // submit draft
    $(document).on('click', '.simpan', function () {
        let out = $('#datePicker').val();
        let to = $('.dari').val();
        let tujuan = $('#tujuan_draft').val();
        console.log(out);
        console.log(to);
        console.log(tujuan);
        const spr1 = [];
        const jml = [];
        const unit1 = [];
        const jum = [];
        $('select[name^="sparepart_id"]').each(function () {
            spr1.push($(this).val());
        });
        $('input[name^="qty_spr"]').each(function () {
            jml.push($(this).val());
        });
        $('select[name^="gbj_id"]').each(function () {
            unit1.push($(this).val());
        });
        $('input[name^="qty_unit"]').each(function () {
            jum.push($(this).val());
        });
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
                    url: "/api/gk/out-draft",
                    type: "post",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        date_out: out,
                        ke: to,
                        deskripsi: tujuan,
                        sparepart_id: spr1,
                        qty_spr: jml,
                        noseri: seri,
                        gbj_id: unit1,
                        qty_unit: jum,
                        seriunit: seri_unit,
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
                    location.reload();
                }, 1000);
            }
        });
    });

    function modalRancang() {
        $('.modal_transfer').modal('show');
        $('.list-group').children().remove();
        $('.judul_modal').text('Silahkan isi tujuan rancangan produk');
        $(document).on('click', '.remove', function () {
            $(this).parent().parent().remove();
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
                    location.reload();
                }, 1000);
            }
        });
    }

</script>
@stop
