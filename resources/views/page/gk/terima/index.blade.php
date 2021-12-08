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
                    <div class="nav nav-tabs topnav" id="nav-tab" role="tablist">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                            aria-controls="home" aria-selected="true">Daftar Penerimaan</a>
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
                        <tbody>
                            <tr>
                                <td scope="row">10-04-2021</td>
                                <td>Divisi IT</td>
                                <td><a href="{{ url('gk/terimaProduk/1') }}" class="btn btn-outline-info"><i
                                            class="far fa-edit"></i>Edit Produk</a></td>
                            </tr>
                            <tr>
                                <td scope="row"></td>
                                <td></td>
                                <td></td>
                            </tr>
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
                                    <label for="tanggal">Tanggal Masuk</label>
                                    <input type="date" name="" id="datePicker" class="form-control" placeholder="">
                                </div>
                                <div class="form-group col">
                                    <label for="dari">Dari</label>
                                    <select class="form-control dari" name="dari">
                                        {{-- <option value="Divisi IT">Divisi IT</option>
                                        <option value="Divisi QC">Divisi QC</option>
                                        <option value="Divisi Perakitan">Divisi Perakitan</option> --}}
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

            // Date
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth()+1; //January is 0 so need to add 1 to make it 1!
        var yyyy = today.getFullYear();
        if(dd<10){
        dd='0'+dd
        }
        if(mm<10){
        mm='0'+mm
        }

        today = yyyy+'-'+mm+'-'+dd;
        document.getElementById("datePicker").setAttribute("max", today);

        document.getElementById('datePicker').valueAsDate = new Date();
        var i = 0;

        var ii = 0;
        var kk = 0;

        const seri = {};
        const seri_unit = {};
        let spr_arr = [];
        let unit_arr = [];
        function addSparepart(x) {
            $('.modalAddSparepart').modal('show');
            $('.modalAddSparepart').on('shown.bs.modal', function () { 
                $(this).find('tbody input.seri').first().focus();
            })
            $('.scan-produk1').DataTable().destroy();
            $('.scan-produk1 tbody').empty();
            for (let index = 0; index < x; index++) {
                ii++;
                $('.scan-produk1 tbody').append('<tr id="row'+ii+'"><td><input type="text" name="noseri[]['+ii+']" id="noseri'+ii+'" maxlength="13" class="form-control seri"><div class="invalid-feedback">Nomor seri ada yang sama atau kosong.</div></td><td><input type="text" name="remark[]['+ii+']" id="remark'+ii+'" class="form-control remark"><div class="invalid-feedback">Kerusakan Tidak Boleh Kosong.</div></td><td><select name="layout_id[]['+ii+']" id="layout_id'+ii+'" class="form-control layout_id"><option value="" selected>Pilih Level</option><option value="1">Level 1</option><option value="2">Level 2</option><option value="3">Level 3</option></select><div class="invalid-feedback">Silahkan pilih tingkat kerusakan.</div></td></tr>');
            }
            var tableScan = $('.scan-produk1').DataTable({
                "destroy": true,
                "ordering": false,
                "autoWidth": false,
                searching: false,
                "lengthChange": false,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
                },
            });
            // $(".form-control").keyup(function () {
            //     if (this.value.length == this.maxLength) {
            //     $(this).next('.form-control').focus();
            //     }
            // });

            $(document).on('click', "#btnSeri", function(e) {
                var arrSparepart = []
                var seriSparepart = [];
                var kerusakanSparepart = [];
                var tingkatSparepart = [];
                e.preventDefault();
                // No Seri
                const data = tableScan.$('.seri').map(function() {
                    return $(this).val();
                }).get();

                data.forEach(function(item) {
                    if (item != '') {
                        arrSparepart.push(item);
                    }
                })

                // Data Null
                const ker = tableScan.$('.remark').map(function() {
                    return $(this).val();
                }).get();

                const ting = tableScan.$('.layout_id').map(function() {
                    return $(this).val();
                }).get();

                // No Seri
                data.forEach(function(item) {
                    if (item == '') {
                        seriSparepart.push(item);
                    }
                })

                // Kerusakan
                ker.forEach(function(item) {
                    if (item == '') {
                        kerusakanSparepart.push(item);
                    }
                })

                // Tingkat
                ting.forEach(function(item) {
                    if (item == '') {
                        tingkatSparepart.push(item);
                    }
                })

                const count = arr =>
                    arr.reduce((a, b) => ({ ...a,
                        [b]: (a[b] || 0) + 1
                    }), {})

                const duplicates = dict =>
                Object.keys(dict).filter((a) => dict[a] > 1)

                if (duplicates(count(kerusakan)).length > 0  || duplicates(count(seri)).length > 0 || duplicates(count(tingkat)).length > 0) {
                    $('.seri').removeClass('is-invalid');
                    $('.remark').removeClass('is-invalid');
                    $('.layout_id').removeClass('is-invalid');

                    $('.seri').filter(function() {
                        return $(this).val() == '';
                    }).addClass('is-invalid');

                    $('.remark').filter(function() {
                        return $(this).val() == '';
                    }).addClass('is-invalid');

                    $('.layout_id').filter(function() {
                        return $(this).val() == '';
                    }).addClass('is-invalid');
                }

                    if (duplicates(count(arr)).length > 0 || duplicates(count(seri)).length > 0) {
                    $('.seri').removeClass('is-invalid');
                        $('.seri').filter(function () {
                            return $(this).val() == duplicates(count(arr))[0];
                        }).addClass('is-invalid');

                        $('.seri').filter(function() {
                            return $(this).val() == '';
                        }).addClass('is-invalid');

                        if (duplicates(count(arr)).length > 0) {
                            Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Nomor seri '+ duplicates(count(arr)) +' ada yang sama.',
                        })   
                        }
                    }

                    if ((duplicates(count(kerusakan)).length == 0 && duplicates(count(seri)).length == 0 && duplicates(count(tingkat)).length == 0 && duplicates(count(arr)).length == 0) == true ) {
                        $('.seri').removeClass('is-invalid');
                        $('.remark').removeClass('is-invalid');
                        $('.layout_id').removeClass('is-invalid');
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Nomor seri tersimpan',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(function() {
                            // $('.scan-produk1 tbody tr').each((index, value) => {
                            //     const obj = {
                            //         noseri: value.childNodes[0].firstChild.value,
                            //         kerusakan: value.childNodes[1].firstChild.value,
                            //         tingkat: value.childNodes[2].firstChild.value,
                            //     }
                            //     spr_arr.push(obj);
                            // })
                            // seri[id] = spr_arr;
                            // spr_arr = [];
                        })
                    }
            });
        }

        function addUnit(x) {
            $('.modalAddUnit').modal('show');
            $('.modalAddUnit').on('shown.bs.modal', function () {
                $(this).find('tbody input.seri').first().focus();
            })
            $('.scan-produk').DataTable().destroy();
            $('.scan-produk tbody').empty();
            for (let index = 0; index < x; index++) {
                kk++;
            $('.scan-produk tbody').append('<tr id="u'+kk+'"><td><input type="text" name="noseri[]['+kk+']" id="noseri'+kk+'" class="form-control seri"><div class="invalid-feedback">Nomor seri ada yang sama atau kosong.</div></td><td><input type="text" name="remark[]['+kk+']" id="remark'+kk+'" class="form-control remark"><div class="invalid-feedback">Kerusakan Tidak Boleh Kosong.</div></td><td><select name="tk_kerusakan[]['+kk+']" id="tk_kerusakan'+kk+'" class="form-control layout_id"><option value="" selected>Pilih Level</option><option value="1">Level 1</option><option value="2">Level 2</option><option value="3">Level 3</option></select><div class="invalid-feedback">Silahkan pilih tingkat kerusakan.</div></td></tr>');
            }
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

                // Data Null
                let seri = [];
                let remark = [];
                let layout_arr = [];

                const rem = tableUnit.$('.remark').map(function() {
                    return $(this).val();
                }).get();

                const layout_id = tableUnit.$('.layout_id').map(function() {
                    return $(this).val();
                }).get();

                // No Seri
                data.forEach(function(item) {
                    if (item == '') {
                        seri.push(item);
                    }
                });

                rem.forEach(function(item) {
                    if (item == '') {
                        remark.push(item);
                    }
                });

                layout_id.forEach(function(item) {
                    if (item == '') {
                        layout_arr.push(item);
                    }
                })

                const count = arr =>
                    arr.reduce((a, b) => ({ ...a,
                        [b]: (a[b] || 0) + 1
                    }), {})

                    const duplicates = dict =>
                    Object.keys(dict).filter((a) => dict[a] > 1)

                if (duplicates(count(arr)).length > 0 || duplicates(count(seri)).length > 0 || duplicates(count(remark)).length > 0 || duplicates(count(layout_id)).length > 0) {
                    $('.seri').removeClass('is-invalid');
                    $('.remark').removeClass('is-invalid');
                    $('.layout_id').removeClass('is-invalid');

                    $('.seri').filter(function() {
                        return $(this).val() == '';
                    }).addClass('is-invalid');

                    $('.remark').filter(function() {
                        return $(this).val() == '';
                    }).addClass('is-invalid');

                    $('.layout_id').filter(function() {
                        return $(this).val() == '';
                    }).addClass('is-invalid');
                }

                if (duplicates(count(arr)).length > 0 || duplicates(count(seri)).length > 0) {
                    $('.seri').removeClass('is-invalid');
                    $('.seri').filter(function() {
                        return $(this).val() == duplicates(count(arr))[0];
                    }).addClass('is-invalid');

                    $('.seri').filter(function() {
                        return $(this).val() == '';
                    }).addClass('is-invalid');

                    if (duplicates(count(arr)).length > 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Nomor seri '+ duplicates(count(arr)) +' ada yang sama.',
                        })   
                    }
                }
                if ((duplicates(count(arr)).length == 0 && duplicates(count(seri)).length == 0 && duplicates(count(remark)).length == 0 && duplicates(count(layout_arr)).length == 0) == true ) {
                    $('.seri').removeClass('is-invalid');
                    $('.remark').removeClass('is-invalid');
                    $('.layout_id').removeClass('is-invalid');

                            Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Nomor seri tersimpan',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(function() {
                            $('.scan-produk tbody tr').each((index, value) => {
                                const obj1 = {
                                    noseri: value.childNodes[0].firstChild.value,
                                    kerusakan: value.childNodes[1].firstChild.value,
                                    tingkat: value.childNodes[2].firstChild.value,
                                }

                                unit_arr.push(obj1);
                            })
                            seri_unit[idd] = unit_arr;
                            unit_arr = [];
                            console.log(seri_unit)
                            $('.scan-produk tbody').empty();
                            $('.scan-produk').DataTable().destroy();
                            $('.modalAddUnit').modal('hide');
                        })
                }
            });
        }
        var nmrspr = 1;
        $(document).on('click','.add_sparepart', function () {
            $.ajax({
                url: '/api/gk/sel-spare',
                type: 'POST',
                dataType: 'json',
                success: function(res) {
                    // ii++;
                    console.log(res);
                    $.each(res, function(key, value) {
                        // $("#change_layout").append('<option value="'+value.id+'">'+value.ruang+'</option');
                        $(".produk").append('<option value="'+value.id+'">'+value.nama+'</option');
                    });
                }
            });
            i++;
            let table_sparepart = '<tr><td><select name="sparepart_id[]" id="sparepart_id" class="form-control produk"></select></td><td><select name="" id="" class="form-control unit"><option value="">Unit 1</option><option value="">Unit 2</option><option value="">Unit 3</option></select></td><td><input type="number" name="qty_spr[]" id="jml" class="form-control"></td><td><button class="btn btn-primary btn_plus'+nmrspr+'" data-id="" data-jml="" id=""><i class="fas fa-qrcode"></i> Tambah No Seri</button>&nbsp;<button class="btn btn-danger btn-delete"><i class="fas fa-trash"></i> Delete</button></td></tr>';
            $('.add_sparepart_table tbody').append(table_sparepart);
            $('.produk').select2();
            $('.unit').select2();
            nmrspr++;
        });

        $(document).on('click', '.btn_plus'+nmrspr, function() {
            var tr = $(this).closest('tr');
            var x = tr.find('#jml').val();
            id = tr.find('#sparepart_id').val();
            console.log(id);
            console.log(x);
            addSparepart(x);
        })
        $(document).on('click', '#btnPlus', function() {
            var tr = $(this).closest('tr');
            var x = tr.find('#jum').val();
            idd = tr.find('#gbj_id').val();
            console.log(idd);
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
        
        $(document).on('click','.add_unit', function () {
            $.ajax({
                url: '/api/gbj/sel-gbj',
                type: 'get',
                dataType: 'json',
                success: function(res) {
                    // ii++;
                    console.log(res);
                    $.each(res, function(key, value) {
                        // $("#change_layout").append('<option value="'+value.id+'">'+value.ruang+'</option');
                        $(".produkk").append('<option value="'+value.id+'">'+value.produk.nama+' '+value.nama+'</option');
                    });
                }
            });
            i++;
            let table_unit = '<tr><td><select name="gbj_id[]" id="gbj_id" class="form-control produkk"></select></td><td><input type="number" name="qty_unit[]" id="jum" class="form-control"></td><td><button class="btn btn-primary" id="btnPlus"><i class="fas fa-qrcode"></i> Tambah No Seri</button>&nbsp;<button class="btn btn-danger btn-delete"><i class="fas fa-trash"></i> Delete</button></td></tr>';
            $('.add_unit_table tbody').append(table_unit);
            $('.produk').select2();
        });
        $(document).on('click', '.btn-delete', function (e) {
            $(this).parent().parent().remove();
            var check = $('tbody.tambah_data tr').length;
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
                url: "/api/gk/draft-terima",
                type: "post",
            },
            columns: [
                {data: "in"},
                {data: "from"},
                {data: "aksi"},
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

                $('select[name^="sparepart_id"]').each(function() {
                    spr1.push($(this).val());
                });

                $('input[name^="qty_spr"]').each(function() {
                    jml.push($(this).val());
                });

                $('select[name^="gbj_id"]').each(function() {
                    unit1.push($(this).val());
                });

                $('input[name^="qty_unit"]').each(function() {
                    jum.push($(this).val());
                });

                $.ajax({
                    url: "/api/gk/in-final",
                    type: "post",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        date_in : out,
                        dari: to,
                        sparepart_id : spr1,
                        qty_spr: jml,
                        noseri : seri,
                        gbj_id : unit1,
                        qty_unit: jum,
                        seriunit : seri_unit,
                    },
                    success: function(res) {
                        console.log(res);
                        Swal.fire(
                            'Terima!',
                            'Data berhasil diterima!',
                            'success'
                        )
                        location.reload();
                    },
                })

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
                let out = $('#datePicker').val();
                let to = $('.dari').val();

                console.log(out);
                console.log(to);

                const spr1 = [];
                const jml = [];
                const unit1 = [];
                const jum = [];

                $('select[name^="sparepart_id"]').each(function() {
                    spr1.push($(this).val());
                });

                $('input[name^="qty_spr"]').each(function() {
                    jml.push($(this).val());
                });

                $('select[name^="gbj_id"]').each(function() {
                    unit1.push($(this).val());
                });

                $('input[name^="qty_unit"]').each(function() {
                    jum.push($(this).val());
                });

                $.ajax({
                    url: "/api/gk/in-draft",
                    type: "post",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        date_in : out,
                        dari: to,
                        sparepart_id : spr1,
                        qty_spr: jml,
                        noseri : seri,
                        gbj_id : unit1,
                        qty_unit: jum,
                        seriunit : seri_unit,
                    },
                    success: function(res) {
                        console.log(res);
                        Swal.fire(
                            'Rancang!',
                            'Data berhasil diterima!',
                            'success'
                        );
                        location.reload();
                    },
                })

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
