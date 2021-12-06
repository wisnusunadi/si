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
                            <tr>
                                <td scope="row">10-04-2021</td>
                                <td>Divisi IT</td>
                                <td><a href="{{ url('gk/transfer/1') }}" class="btn btn-outline-info"><i class="far fa-edit"></i>Edit Produk</a></td>
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
                                    <input type="date" name="date_in" id="datePicker" class="form-control" placeholder="">
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
                                        <tr>
                                            <td><input type="text" class="form-control"></td>
                                            <td><input type="text" class="form-control"></td>
                                            <td>
                                                <select name="" id="" class="form-control">
                                                    <option value="1">Level 1</option>
                                                    <option value="2">Level 2</option>
                                                    <option value="3">Level 3</option>
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
                <button type="button" class="btn btn-primary seri_spr" id="btnSpr">Simpan</button>
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
                <button type="button" class="btn btn-primary" id="btnUnit">Simpan</button>
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
                            <textarea name="tujuan" id="tujuan_draft" cols="10" rows="5" class="form-control"></textarea>
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

<div class="modal fade modal_transfer1" id="" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
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
    var k = 0;
    var ii = 0;
    var kk = 0;
    function addSparepart(x) {

        $('.modalAddSparepart').modal('show');
        $('.scan-produk1').DataTable().destroy();
        $('.scan-produk1 tbody').empty();
        for (let index = 0; index < x; index++) {
            ii++;
           $('.scan-produk1 tbody').append('<tr><td><input type="text" name="noseri[]" class="form-control seri_spr"></td><td><input type="text" name="remark[]" id="remark[]" class="form-control"></td><td><select name="tk_kerusakan[]" id="tk_kerusakan[]" class="form-control"><option value="1">Level 1</option><option value="2">Level 2</option><option value="3">Level 3</option></select></td></tr>');
        }
        $('.scan-produk1').DataTable({
            "ordering": false,
            "autoWidth": false,
            searching: false,
            "lengthChange": false,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
        });
    }

    function addUnit(x) {
        $('.modalAddUnit').modal('show');
        $('.scan-produk').DataTable().destroy();
        $('.scan-produk tbody').empty();
        for (let index = 0; index < x; index++) {
           $('.scan-produk tbody').append('<tr><td><input type="text" name="noseri[]" id="noseri[]" class="form-control"></td><td><input type="text" name="remark[]" id="remark[]" class="form-control"></td><td><select name="tk_kerusakan[]" id="tk_kerusakan[]" class="form-control"><option value="1">Level 1</option><option value="2">Level 2</option><option value="3">Level 3</option></select></td></tr>');
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
    var x = '';
    var id = '';
    $(document).on('click', '#btn_plus', function() {
        var tr = $(this).closest('tr');
        x = tr.find('#jml').val();
        id = tr.find('#sparepart_id').val();
        console.log(id);
        console.log(x);
        addSparepart(x);
    })
    $(document).on('click', '#btnPlus', function() {
        var tr = $(this).closest('tr');
        var x = tr.find('#jum').val();
        console.log(x);
        addUnit(x);
    })

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
        let table_sparepart = '<tr id="'+i+'"><td><select name="sparepart_id[]" id="sparepart_id" class="form-control produk"></select></td><td><select name="" id="" class="form-control unit"><option value="">Unit 1</option><option value="">Unit 2</option><option value="">Unit 3</option></select></td><td><input type="number" name="qty_spr[]" id="jml" class="form-control"></td><td><button class="btn btn-primary" data-id="" data-jml="" id="btn_plus"><i class="fas fa-qrcode"></i> Tambah No Seri</button>&nbsp;<button class="btn btn-danger btn-delete"><i class="fas fa-trash"></i> Delete</button></td></tr>';
        $('.add_sparepart_table tbody').append(table_sparepart);
        $('.produk').select2();
        $('.unit').select2();
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
        k++;
        let table_unit = '<tr id="'+k+'"><td><select name="gbj_id[]" id="gbj_id[]" class="form-control produkk"></td><td><input type="number" name="qty_unit[]" id="jum" class="form-control"></td><td><button class="btn btn-primary" id="btnPlus"><i class="fas fa-qrcode"></i> Tambah No Seri</button>&nbsp;<button class="btn btn-danger btn-delete"><i class="fas fa-trash"></i> Delete</button></td></tr>';
        $('.add_unit_table tbody').append(table_unit);
        $('.produk').select2();
    });
    $(document).on('click', '.btn-delete', function (e) {
        $(this).parent().parent().remove();
        var check = $('tbody.tambah_data tr').length;
    });

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

    $(document).ready(function () {
        $('.table-rancangan').DataTable({
            destroy: true,
            "ordering": false,
            "autoWidth": false,
            searching: false,
            "lengthChange": false,
            ajax: {
                url: "/api/gk/draft-tf",
                type: "post",
            },
            columns: [
                {data: "out"},
                {data: "too"},
                {data: "aksi"},
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

        $(document).on('click','.simpan1', function () {
            let out = $('#datePicker').val();
            let to = $('.dari').val();
            let tujuan = $('#tujuan_tf').val();

            let spr = [];
            let unit = [];
            let qty = [];

            console.log(out);
            console.log(to);
            console.log(tujuan);


            // Swal.fire({
            //     title: "Apakah anda yakin?",
            //     text: "Data yang sudah di transfer tidak dapat diubah!",
            //     icon: "warning",
            //     buttons: true,
            //     dangerMode: true,
            //     showCancelButton: true,
            // }).then((success) => {
            //     if (success) {
            //         Swal.fire(
            //             'Data berhasil di transfer!',
            //             '',
            //             'success'
            //         );
            //         setTimeout(() => {
            //             location.reload();
            //         }, 1000);
            //     }else{
            //         Swal.fire(
            //             'Data gagal di transfer!',
            //             '',
            //             'error'
            //         );
            //         setTimeout(() => {
            //             location.reload();
            //         }, 1000);
            //     }
            // });
        });
    }

    const spr = {};
    const sprr = {};
    const sprrr = {};

    const unit = {};
    const unitt = {};
    const unittt = {};
    // const jml = [];
    var t = 0;

    $(document).on('click', '#btnUnit', function(e) {
        console.log('unit');

        const seri_unit = [];
        const rusak_unit = [];
        const tk_unit = [];

        $('input[name^="noseri"]').each(function() {
            seri_unit.push($(this).val());
        });
        unit[id] = seri_unit;

        $('input[name^="remark"]').each(function() {
            rusak_unit.push($(this).val());
        });
        unit[id] = rusak_unit;

        $('select[name^="tk_kerusakan"]').each(function() {
            tk_unit.push($(this).val());
        });
        unit[id] = tk_unit;
    })

    $(document).on('click', '#btnSpr', function(e) {
        console.log('ok');

        const seri = [];
        const rusak = [];
        const tk = [];

        $('input[name^="noseri"]').each(function() {
            seri.push($(this).val());
        });
        spr[id] = seri;

        $('input[name^="remark"]').each(function() {
            rusak.push($(this).val());
        });
        spr[id] = rusak;

        $('select[name^="tk_kerusakan"]').each(function() {
            tk.push($(this).val());
        });
        spr[id] = tk;
        console.log(seri);
        t++;
    });

    $(document).on('click', '.simpan', function () {
        let out = $('#datePicker').val();
        let to = $('.dari').val();
        let tujuan = $('#tujuan_draft').val();
        // let seri1 = $('.seri_spr').val();

        console.log(out);
        console.log(to);
        console.log(tujuan);

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

        // $('input[name^="noseri"]').each(function() {
        //     spr.push(seri_spr.push(seri1));
        // });

        $('select[name^="gbj_id"]').each(function() {
            unit1.push($(this).val());
        });

        $('input[name^="qty_unit"]').each(function() {
            jum.push($(this).val());
        });

        $.ajax({
            url: "/api/gk/out-draft",
            type: "post",
            data: {
                "_token": "{{ csrf_token() }}",
                date_out : out,
                ke: to,
                deskripsi : tujuan,
                sparepart_id : spr1,
                qty_spr: jml,
                noseri : spr,
                // remark : sprr,
                // tk_spr : sprrr,
                gbj_id : unit1,
                qty_unit: jum,
                seri_u: unit,
                // re_unit: unitt,
                // tk_unit: unittt,
            },
            success: function(res) {
                console.log(res);
            },
        })
        // Swal.fire({
        //     title: "Apakah anda yakin?",
        //     text: "Data yang sudah di rancangan tidak dapat diubah!",
        //     icon: "warning",
        //     buttons: true,
        //     dangerMode: true,
        //     showCancelButton: true,
        // }).then((success) => {
        //     if (success) {
        //         Swal.fire(
        //             'Data berhasil di rancangan!',
        //             '',
        //             'success'
        //         );
        //         setTimeout(() => {
        //             location.reload();
        //         }, 1000);
        //     }else{
        //         Swal.fire(
        //             'Data gagal di rancangan!',
        //             '',
        //             'error'
        //         );
        //         setTimeout(() => {
        //             location.reload();
        //         }, 1000);
        //     }
        // });
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
