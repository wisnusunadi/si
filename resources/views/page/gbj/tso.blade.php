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
                                    <form method="post" id="tsoForm" name="tsoForm">
                                        <div class="form-group row top-min">
                                            <label for="" class="col-12 font-weight-bold col-form-label">Ke</label>
                                            <div class="col-12">
                                                <select class="form-control division" name="ke" id="ke">

                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row top-min">
                                            <label for="" class="col-12 font-weight-bold col-form-label">Keterangan</label>
                                            <div class="col-12">
                                                <textarea name="ket" id="ket" class="form-control tujuan"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row top-min">
                                            <label for="" class="col-12 font-weight-bold col-form-label">Produk</label>
                                            <div class="col-12">
                                                <select class="form-control product" name="gdg_brg_jadi_id" id="gdg_brg_jadi_id">

                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row top-min">
                                            <label for="" class="col-12 font-weight-bold col-form-label">Stok</label>
                                            <div class="col-12">
                                                <input type="text" name="qty" id="qty"
                                                    class="form-control number-input input-notzero stok">
                                                <span class="form-text text-muted">Stok Input Maks. 20</span>
                                                {{-- <input type="text" class="stok-gudang" value="20" hidden> --}}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 d-flex justify-content-end">
                                                <button type="button" class="btn btn-primary btn-tambah" id="btnSave">Tambah</button>
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
                                        <th>Ke</th>
                                        <th>Tujuan</th>
                                        <th>Produk</th>
                                        <th>Stok</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="tambah_data">
                                </tbody>
                            </table>
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button class="btn btn-primary btn-simpan" type="button" data-toggle="modal"
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
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>36541654654654564</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>36541654654654564</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
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
        $('.product').select2();

        $(".number-input").inputFilter(function (value) {
            return /^\d*$/.test(value);
            var value = $(this).val();
        });

    });

    $(document).on('click','.btn-tambah', function (e) {
        // let divisi = $('.division').val();
        // let tujuan = $('.tujuan').val();
        // let produk = $('.product').val();
        // let stok = $('.stok').val();
        // let stok_gudang = $('.stok-gudang').val();
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '{{ route("tfp.postnon") }}',
            type: "POST",
            data: {
                ke: $('#ke').val(),
                ket: $('#ket').val(),
                gdg_brg_jadi_id: $('#gdg_brg_jadi_id').val(),
                qty: $('#qty').val(),
            },
            success: function (res) {
                if(res.errors) {
                    console.log('error');
                } else {
                    // if (stok < stok_gudang) {
                    //     addData(divisi, tujuan, produk, stok)
                    // }else{
                    //     Swal.fire(
                    //         'Stok Tidak Mencukupi',
                    //         '',
                    //         'error'
                    //     )
                    // }
                    Swal.fire(
                        'Good job!',
                        'You clicked the button!',
                        'success'
                        )
                    // console.log('ok');
                    // $('.datatable').DataTable().ajax.reload();
                    location.reload();
                }
            $('.btn-simpan').prop('hidden', false);
            }
        });
    });

    function addData(divisi, tujuan, produk, stok) {
        let tambah_data = '<tr><td>'+divisi+'</td><td>'+tujuan+'</td><td>'+produk+'</td><td>'+stok+'</td><td><button class="btn btn-primary" data-toggle="modal" data-target=".modal-produk"><i class="fas fa-qrcode"></i> Scan Produk</button>&nbsp;<button class="btn btn-danger btn-delete"><i class="fas fa-trash"></i> Hapus</button></td></tr>'
        $('tbody.tambah_data').append(tambah_data);
    }
    $(document).on('click', '.btn-delete', function(e){
        e.preventDefault();
        $(this).parent().parent().remove();
        var check = $('.kd-barang-field').length;
        if(check != 0){
            $('.btn-simpan').prop('hidden', false);
        }else{
            $('.btn-simpan').prop('hidden', true);
        }
    });


    $('.scan-produk').DataTable({
            'columnDefs': [{
                'targets': 1,
                'checkboxes': {
                    'selectRow': true
                }
            }],
            'select': {
                'style': 'multi'
            },
            'order': [
                [0, 'asc']
            ]
        });

    $.ajax({
        url: '{{ route('sel.divisi') }}',
        type: 'GET',
        dataType: 'json',
        success: function(res) {
            if(res) {
                console.log(res);
                $("#ke").empty();
                $("#ke").append('<option value="">-- Pilih Tujuan--</option>');
                $.each(res, function(key, value) {
                    $("#ke").append('<option value="'+value.id+'">'+value.nama+'</option');
                });
            } else {
                $("#ke").empty();
            }
        }
    });

    $.ajax({
        url: '{{ route('sel.gbj') }}',
        type: 'GET',
        dataType: 'json',
        success: function(res) {
            if(res) {
                console.log(res);
                $("#gdg_brg_jadi_id").empty();
                $("#gdg_brg_jadi_id").append('<option value="">-- Pilih Produk--</option>');
                $.each(res, function(key, value) {
                    $("#gdg_brg_jadi_id").append('<option value="'+value.id+'">'+value.nama+'</option');
                });
            } else {
                $("#gdg_brg_jadi_id").empty();
            }
        }
    });
</script>
@stop
