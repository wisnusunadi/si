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
        <h1>Penerimaan Produk Gudang Karantina</h1>
    </div><!-- /.container-fluid -->
</section>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <nav>
                    <input type="hidden" name="" id="user_id" value="{{ Auth::user()->id }}">
                    <div class="nav nav-tabs topnav" id="nav-tab" role="tablist">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                            aria-controls="home" aria-selected="true">Draft Penerimaan</a>
                        <a id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile"
                            aria-selected="false">Pembuatan Penerimaan</a>
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
                                <th style="width: 220px">Tanggal Masuk</th>
                                <th>Dari</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
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
                                    <label for="tanggal">Tanggal Masuk</label>
                                    <input type="date" name="date_in" id="datePicker" class="form-control" placeholder="">
                                </div>
                                <div class="form-group col">
                                    <label for="dari">Dari</label>
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
                    <button class="btn btn-success" onclick="terima()" type="button">Terima</button>&nbsp;
                    <button class="btn btn-info" onclick="rancang()" type="button">Rancang</button>&nbsp;
                    <button class="btn btn-secondary" onclick="batal()" type="button">Batal</button>
                </div>
            </div>
    </div>
    </section>
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
                                <label for="">Tanggal Masuk</label>
                                <div class="card" style="background-color: #C8E1A7">
                                    <div class="card-body in_spr">
                                        23-09-2021
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Nama Produk</label>
                                <div class="card" style="background-color: #F89F81">
                                    <div class="card-body spr">
                                        Produk 1
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm">
                                <label for="">Unit</label>
                                <div class="card" style="background-color: #FFCC83">
                                    <div class="card-body spru">
                                        -
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Dari</label>
                                <div class="card" style="background-color: #FFE0B4">
                                    <div class="card-body divisi_spr">
                                        Divisi IT
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Jumlah</label>
                                <div class="card" style="background-color: #FFECB2">
                                    <div class="card-body jml_spr">
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
@stop
@section('adminlte_js')
<script>
    $(document).ready(function () {

    });
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

    function clickSparepart(c,d) {
        // console.log(c);
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
            $.ajax({
                url: "/api/gk/cekseri",
                type: "post",
                data: {noseri:data},
                success: function(res) {
                    if(res.msg) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: res.msg,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(function () {
                            $('.scan-produk1 tbody tr').each((index, value) => {
                                const obj = {
                                    noseri: value.childNodes[0].firstChild.value,
                                    kerusakan: value.childNodes[1].firstChild.value,
                                    tingkat: value.childNodes[2].firstChild.value,
                                }

                                spr_arr.push(obj);
                            })
                            seri[d] = spr_arr;
                            spr_arr = [];
                            $('.modalAddSparepart').modal('hide');
                        })
                    } else {
                        // console.log(res);
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: res.error,
                        })
                    }
                },
            })

        }
    }

    function addSparepart(x, y, z) {
        // header
        // console.log($('select[name="sparepart_id"] option:selected').text());
        $('.jml_spr').text(x + ' Pcs')
        $('.in_spr').text(document.getElementsByName("date_in")[0].value)
        $('.divisi_spr').text(document.getElementsByName("dari")[0].selectedOptions[0].label)

        $('.modalAddSparepart').modal('show');
        $('.modalAddSparepart').find('#btnSeri').attr('onclick', 'clickSparepart(' + y + ','+z+')');
        $('.modalAddSparepart').on('shown.bs.modal', function () {
            $(this).find('tbody input.seri').first().focus();
        })
        $('.scan-produk1').DataTable().destroy();
        $('.scan-produk1 tbody').empty();
        for (let index = 0; index < x; index++) {
            ii++;
            $('.scan-produk1 tbody').append('<tr id="row' + ii + '"><td><input type="text" name="noseri[][' + ii +
                ']" id="noseri' + ii +
                '" maxlength="13" class="form-control seri"><div class="invalid-feedback">Nomor seri ada yang sama atau kosong.</div></td><td><input type="text" name="remark[][' +
                ii + ']" id="remark' + ii +
                '" class="form-control remark"><div class="invalid-feedback">Kerusakan Tidak Boleh Kosong.</div></td><td><select name="layout_id[][' +
                ii + ']" id="layout_id' + ii +
                '" class="form-control layout_id"><option value="" selected>Pilih Level</option><option value="1">Level 1</option><option value="2">Level 2</option><option value="3">Level 3</option></select><div class="invalid-feedback">Silahkan pilih tingkat kerusakan.</div></td></tr>'
                );
        }
        $('.scan-produk1').DataTable({
            "destroy": true,
            "ordering": false,
            "autoWidth": false,
            searching: false,
            "lengthChange": false,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            },
        });
    }

    // Unit
    function addUn(l) {
        var j = $(".btnPlus" + l).parent().prev().children().val();
        var k = $(".btnPlus" + l).parent().prev().prev().children().val();
        addUnit(j, k);
    }

    function clickUnit(c) {
        // console.log(c);
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

        if (duplicates(count(arrUnit)).length > 0 || duplicates(count(seriUnit)).length > 0 || duplicates(count(kerusakanUnit)).length > 0 || duplicates(count(tingkatUnit)).length > 0) {
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
                $('.seri').filter(function () {
                    for (let index = 0; index < duplicates(count(arrUnit)).length; index++) {
                    if ($(this).val() == duplicates(count(arrUnit))[index]) {
                        return true;
                    }
                }
                        }).addClass('is-invalid');
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

        if ((duplicates(count(kerusakanUnit)).length == 0 && duplicates(count(seriUnit)).length == 0 && duplicates(count(tingkatUnit)).length == 0 && duplicates(count(arrUnit)).length == 0) == true) {
            $('.seri').removeClass('is-invalid');
            $('.kerusakan').removeClass('is-invalid');
            $('.tingkat').removeClass('is-invalid');
            $.ajax({
                url: "/api/gk/cekseri",
                type: "post",
                data: {noseri: dataUnit},
                success: function(res) {
                    if(res.msg) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: res.msg,
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
                                        unit_arr = [];
                                        console.log(seri_unit)
                                        $('.modalAddUnit').modal('hide');
                        })
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: res.error,
                        })
                    }
                }
            })

        }
    }

    function addUnit(x,y,z) {
        $('.modalAddUnit').modal('show');
        $('.modalAddUnit').find('#btnAddUnit').attr('onclick', 'clickUnit(' + y + ')');
        $('.modalAddUnit').on('shown.bs.modal', function () {
            $(this).find('tbody input.seri').first().focus();
        })
        $('.scan-produk').DataTable().destroy();
        $('.scan-produk tbody').empty();
        for (let index = 0; index < x; index++) {
            kk++;
            $('.scan-produk tbody').append('<tr id="u' + kk + '"><td><input type="text" name="noseri[][' + kk +
                ']" id="noseri' + kk +
                '" class="form-control seri"><div class="invalid-feedback">Nomor seri ada yang sama.</div></td><td><input type="text" name="remark[][' +
                kk + ']" id="remark' + kk + '" class="form-control kerusakan"><div class="invalid-feedback">Kerusakan Tidak Boleh Kosong.</div></td><td><select name="tk_kerusakan[][' + kk +
                ']" id="tk_kerusakan' + kk +
                '" class="form-control tingkat"><option value="" selected>Pilih Level</option><option value="1">Level 1</option><option value="2">Level 2</option><option value="3">Level 3</option></select><div class="invalid-feedback">Silahkan pilih tingkat kerusakan.</div></td></tr>'
                );
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
                $(".dari").append('<option value="' + value.id + '">' + value.nama +
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
            url: '/api/gk/sel-spare',
            type: 'POST',
            dataType: 'json',
            success: function (res) {
            $.each(res, function (key, value) {
                // $("#change_layout").append('<option value="'+value.id+'">'+value.ruang+'</option');
                $('.produk').append('<option value="' + value.id + '">' + value.nama + '</option');
            });

            }
        });
        i++;
        let table_sparepart = '<tr id='+nmrspr+'><td><select name="sparepart_id[]" id="sparepart_id'+nmrspr+'" class="form-control produk"></select></td><td><input type="text" name="qty_spr[]" id="jml" class="form-control number"></td><td><button class="btn btn-primary btn_plus'+nmrspr+'" data-id="" data-jml="" id="" onclick=addSpare('+nmrspr+')><i class="fas fa-qrcode"></i> Tambah No Seri</button>&nbsp;<button class="btn btn-danger btn-delete"><i class="fas fa-trash"></i> Delete</button></td></tr>';
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
            url: '/api/gbj/sel-gbj',
            type: 'get',
            dataType: 'json',
            success: function (res) {
                // ii++;
                console.log(res);
                $.each(res, function (key, value) {
                    // $("#change_layout").append('<option value="'+value.id+'">'+value.ruang+'</option');
                    $(".produkk").append('<option value="' + value.id + '">' + value
                        .produk.nama + ' ' + value.nama + '</option');
                });
            }
        });
        i++;
        let table_unit = '<tr id='+nmrunt+'><td><select name="gbj_id[]" id="gbj_id'+nmrunt+'" class="form-control produkk"></select></td><td><input type="text" name="qty_unit[]" id="jum" class="form-control number"></td><td><button class="btn btn-primary btnPlus'+nmrunt+'" id="" onclick=addUn('+nmrunt+')><i class="fas fa-qrcode"></i> Tambah No Seri</button>&nbsp;<button class="btn btn-danger btn-delete"><i class="fas fa-trash"></i> Delete</button></td></tr>';
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
                let out = $('#datePicker').val();
                let to = $('.dari').val();

                console.log(out);
                console.log(to);

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

                $.ajax({
                    url: "/api/gk/in-final",
                    type: "post",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        userid: $('#user_id').val(),
                        date_in: out,
                        dari: to,
                        sparepart_id: spr1,
                        qty_spr: jml,
                        noseri: seri,
                        gbj_id: unit1,
                        qty_unit: jum,
                        seriunit: seri_unit,
                    },
                    success: function (res) {
                        console.log(res);
                        Swal.fire(
                            'Terima!',
                            'Data berhasil diterima!',
                            'success'
                        )
                        location.reload();
                    },
                })

            } else {
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
                let out = $('#datePicker').val();
                let to = $('.dari').val();

                console.log(out);
                console.log(to);

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

                $.ajax({
                    url: "/api/gk/in-draft",
                    type: "post",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        userid: $('#user_id').val(),
                        date_in: out,
                        dari: to,
                        sparepart_id: spr1,
                        qty_spr: jml,
                        noseri: seri,
                        gbj_id: unit1,
                        qty_unit: jum,
                        seriunit: seri_unit,
                    },
                    success: function (res) {
                        console.log(res);
                        Swal.fire(
                            'Rancang!',
                            'Data berhasil diterima!',
                            'success'
                        );
                        location.reload();
                    },
                })

            } else {
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
            } else {
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
