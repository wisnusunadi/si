@extends('adminlte.page')

@section('title', 'ERP')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <h1>Transfer Produk Gudang Barang Jadi Tanpa SO</h1>
    </div><!-- /.container-fluid -->
</section>

<section class="content">
    <div class="row">
        <div class="col-lg-4 col-md-12 col-sm-12 mb-4">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary b-radius">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 mt-3">
                                    <form method="post">
                                        <div class="form-group row top-min">
                                            <label for="" class="col-12 font-weight-bold col-form-label">Tujuan</label>
                                            <div class="col-12">
                                                <select class="form-control ke" name="ke" id="ke">
                                                    <option value="Divisi IT">Divisi IT</option>
                                                    <option value="Divisi QC">Divisi QC</option>
                                                    <option value="Divisi Perakitan">Divisi Perakitan</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row top-min">
                                            <label for="" class="col-12 font-weight-bold col-form-label">Keterangan</label>
                                            <div class="col-12">
                                                <textarea name="deskripsi" id="deskripsi" class="form-control deskripsi"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row top-min">
                                            <label for="" class="col-12 font-weight-bold col-form-label">Produk</label>
                                            <div class="col-12">
                                                <select class="form-control product" name="gdg_brg_jadi_id" id="gdg_brg_jadi_id">
                                                    <option value="AMBULATORY BLOOD PRESSURE MONITOR">AMBULATORY BLOOD PRESSURE MONITOR</option>
                                                    <option value="AIR STERILIZER AND PURIFIER">AIR STERILIZER AND PURIFIER</option>
                                                    <option value="BACKUP POWER">BACKUP POWER</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row top-min">
                                            <label for="" class="col-12 font-weight-bold col-form-label">Stok</label>
                                            <div class="col-12">
                                                <input type="text" id="qtyy"
                                                    class="form-control number-input input-notzero qtyy">
                                                {{-- <span class="form-text text-muted">Stok Input Maks. 20</span> --}}
                                                <input type="text" class="stok-gudang" value="20" hidden>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 d-flex justify-content-end">
                                                <button type="button" class="btn btn-primary btn-tambah">Tambah</button>
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
        <div class="col-lg-8 col-md-12 col-sm-12">
            <div class="card card-noborder b-radius">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 table-responsive mb-4">
                            <table class="table table-hover addData">
                                <thead>
                                    <tr>
                                        <th>Tujuan</th>
                                        <th>Keterangan</th>
                                        <th>Produk</th>
                                        <th>Stok</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="tambah_data">
                                </tbody>
                            </table>
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button class="btn btn-primary btn-simpan" type="submit" data-toggle="modal"
                                data-target="#modalNotes" hidden>Simpan</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Modal Scan Product --}}
<!-- Modal -->
<div class="modal fade modal-produk" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Scan Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-striped scan-produk">
                    <thead>
                        <tr>
                            <th>Nomor Seri</th>
                            <th><input type="checkbox" id="head-cb"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>36541654654654564</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>65654564646545455</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>

            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" type="button" id="btnSave">Simpan</button>
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
        $('.ke').select2();
        $('.product').select2();

        $(".number-input").inputFilter(function (value) {
            return /^\d*$/.test(value);
            var value = $(this).val();
        });

        $("#head-cb").on('click', function () {
            var isChecked = $("#head-cb").prop('checked')
            $('.cb-child').prop('checked', isChecked)
        });

        // load produk
        $.ajax({
            url: '/api/gbj/sel-gbj',
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if(res) {
                    console.log(res);
                    $("#gdg_brg_jadi_id").empty();
                    $("#gdg_brg_jadi_id").append('<option value="">Pilih Item</option>');
                    $.each(res, function(key, value) {
                        $("#gdg_brg_jadi_id").append('<option value="'+value.id+'">'+value.produk.nama+' '+value.nama+'</option');
                    });
                } else {
                    $("#gdg_brg_jadi_id").empty();
                }
            }
        });

        // load divisi
        $.ajax({
            url: '/api/gbj/sel-divisi',
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if(res) {
                    console.log(res);
                    $("#ke").empty();
                    $("#ke").append('<option value="">Pilih Item</option>');
                    $.each(res, function(key, value) {
                        $("#ke").append('<option value="'+value.id+'">'+value.nama+'</option');
                    });
                } else {
                    $("#ke").empty();
                }
            }
        });
    });

    $(document).on('click','.btn-tambah', function (e) {
        e.preventDefault();

        let divisi = $('.ke').val();
        let d_divisi = $('.ke').find(':selected').text();
        let deskripsi = $('.deskripsi').val();
        let produk = $('.product').val();
        let d_produk = $('.product').find(':selected').text();
        let stok = parseInt($('.qtyy').val());
        // let stok_gudang = parseInt($('.stok-gudang').val());

        $.ajax({
            url: "/api/tfp/cekStok",
            type:"POST",
            data: {
                "_token": "{{ csrf_token() }}",
                ke: divisi,
                deskripsi: deskripsi,
                gdg_brg_jadi_id: produk,
                qty: stok,
            },
            success: function (res) {
                console.log(res);
                if(res.stok < stok) {
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'Stok Tidak Mencukupi',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    // console.log('tidak');
                } else {
                    // console.log('ok');
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Successfully',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    addData(divisi, d_divisi, deskripsi, d_produk, produk, stok);
                    // $('#post_ke').val(divisi);
                    // $('#post_deskripsi').val(deskripsi);
                    // $('#post_produk').val(produk);
                    // $('#post_qty').val(stok);
                }
            $('.btn-simpan').prop('hidden', false);
            }
        });
    });

    var i = 0;
    function addData(divisi,d_divisi, deskripsi, d_produk, produk, stok) {
        if (deskripsi.length > 30) {
            var a = deskripsi.substring(0, 10) + '...';
        }else{
            var a = deskripsi;
        }

        i++;
        // console.log(deskripsi.length);
        let tambah_data = '<tr id=row'+i+'><td>'+d_divisi+'<div id="hidden"><input type="hidden" name="ke['+i+']" id="post_ke'+i+'" value="'+divisi+'"></div></td><td>'+a+'<input type="hidden" name="deskripsi['+i+']" id="post_deskripsi'+i+'" value="'+deskripsi+'"></td><td>'+d_produk+'<input type="hidden" name="gdg_brg_jadi_id['+i+']" id="post_produk'+i+'" value="'+produk+'"></td><td>'+stok+'<input type="hidden" name="qty['+i+']" id="post_qty'+i+'" value="'+stok+'"></td><td><button class="btn btn-primary noseriModal" data-toggle="modal" data-id="'+produk+'" ><i class="fas fa-qrcode"></i> Scan Produk</button>&nbsp;<button class="btn btn-danger btn-delete"><i class="fas fa-trash"></i> Hapus</button></td></tr>'
        $('tbody.tambah_data').append(tambah_data);


    }
    $(document).on('click', '.btn-delete', function(e){
        e.preventDefault();
        $(this).parent().parent().remove();
        var check = $('tbody.tambah_data tr').length;
        if(check != 0){
            $('.btn-simpan').prop('hidden', false);
        }else{
            $('.btn-simpan').prop('hidden', true);
        }
    });


    var prd = '';
    var jml = '';
    var id = '';
    $(document).on('click', '.noseriModal', function(e) {
        var tr = $(this).closest('tr');
        jml = tr.find('#post_qty').val();
        console.log(jml);
        id = $(this).data('id');
        console.log(id);

        $.ajax({
            url: "/api/tfp/noseri/" + id,
            type: "get",
            // data : {id : id},
            dataType: 'json',
            success: function(res) {
                console.log(res);

                $('.scan-produk').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    autoWidth: false,
                    ajax: {
                        url: '/api/tfp/noseri/' + id,
                    },
                    columns: [
                        { data: 'noseri', name: 'noseri'},
                        { data: 'checkbox', name: 'checkbox', orderable: 'false'},
                    ],
                    // 'columnDefs': [{
                    //     'targets': 1,
                    //     'checkboxes': {
                    //         'selectRow': true
                    //     },
                    // }],
                    'select': {
                        'style': 'multi'
                    },
                    // 'order': [
                    //     [0, 'asc']
                    // ],
                    "oLanguage": {
                    "sSearch": "Scan Nomor Seri:"
                    }
                });
            }
        })
        $('.modal-produk').modal('show');
    })
    const seri = {};
    $(document).on('click', '#btnSave', function() {
        console.log('ok');
        const ids = [];
        $('.cb-child').each(function() {
            if ($(this).is(":checked")) {
                ids.push($(this).val());
            }
        })
        seri[id] = ids;
        console.log(seri);
    })

    $(document).on('click', '.btn-simpan', function(e) {
        e.preventDefault();

        let a = $('#post_ke').val();
        let b = $('#post_deskripsi').val();
        let c = $('#post_produk').val();
        let d = parseInt($('#post_qty').val());
        let stok_gudang = parseInt($('.stok-gudang').val());

        let ke = [];
        let desk = [];
        let gdg = [];
        let stok_push = [];

        $('input[name^="ke"]').each(function() {
            ke.push($(this).val());
        });
        // console.log(ke);

        $('input[name^="deskripsi"]').each(function() {
            desk.push($(this).val());
        });

        $('input[name^="gdg_brg_jadi_id"]').each(function() {
            gdg.push($(this).val());
        });

        $('input[name^="qty"]').each(function() {
            stok_push.push($(this).val());
        });
        console.log(stok_push);

        $.ajax({
            url: "/api/tfp/create",
            type:"POST",
            data: {
                "_token": "{{ csrf_token() }}",
                ke: ke,
                deskripsi: desk,
                gdg_brg_jadi_id: gdg,
                qty: stok_push,
                noseri_id : seri,
            },
            success: function (res) {
                console.log(res);
                // Swal.fire({
                //     position: 'center',
                //     icon: 'success',
                //     title: res.msg,
                //     showConfirmButton: false,
                //     timer: 1500
                // })
                // location.reload();
            }
        });
    })


</script>
@stop
