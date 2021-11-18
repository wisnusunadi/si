@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
<style>
        .hapus {
        display: none;
    }
</style>
<section class="content-header">
    <div class="container-fluid">
        <h1>Penerimaan Produk Gudang Karantina</h1>
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
                                        <div class="form-group">
                                          <label for="">Tanggal Masuk</label>
                                          <input type="date" name="" id="datePicker" class="form-control" placeholder="">
                                        </div>
                                        <div class="form-group row top-min">
                                            <label for="" class="col-12 font-weight-bold col-form-label">Dari</label>
                                            <div class="col-12">
                                                <select class="form-control dari" name="dari">
                                                    <option value="Divisi IT">Divisi IT</option>
                                                    <option value="Divisi QC">Divisi QC</option>
                                                    <option value="Divisi Perakitan">Divisi Perakitan</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row top-min">
                                            <label for="" class="col-12 font-weight-bold col-form-label">Nama Produk</label>
                                            <div class="col-12">
                                                <select class="form-control nama_produk" name="">
                                                    <option value="Produk 1">Produk 1</option>
                                                    <option value="Produk 2">Produk 2</option>
                                                    <option value="Produk 3">Produk 3</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row top-min">
                                            <label for="" class="col-12 font-weight-bold col-form-label">Unit</label>
                                            <div class="col-12">
                                                <select class="form-control unit" name="">
                                                    <option value="Unit 1">Unit 1</option>
                                                    <option value="Unit 2">Unit 2</option>
                                                    <option value="Unit 3">Unit 3</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row top-min">
                                            <label for="" class="col-12 font-weight-bold col-form-label">Jumlah</label>
                                            <div class="col-12">
                                                <input type="text" name="stok" id="" class="form-control number-input input-notzero stok">
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
                                        <th>Tanggal Masuk</th>
                                        <th>Nama Produk</th>
                                        <th>Unit</th>
                                        <th>Dari</th>
                                        <th>Jumlah</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="tambah_data">
                                </tbody>
                            </table>
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <div class="btn-simpan hapus">
                                <button class="btn btn-success" type="button">Terima</button>&nbsp;
                                <button class="btn btn-info" type="button">Rancang</button>&nbsp;
                                <button class="btn btn-secondary " type="button">Batal</button>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade modalAddNoSeri" id="" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
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
                                <table class="table table-striped scan-produk">
                                    <thead>
                                        <tr>
                                            <th>Nomor Seri</th>
                                            <th>Nomor Seri</th>
                                            <th>Nomor Seri</th>
                                            <th>Nomor Seri</th>
                                            <th>Nomor Seri</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="text" name="" id="" class="form-control"></td>
                                            <td><input type="text" name="" id="" class="form-control"></td>
                                            <td><input type="text" name="" id="" class="form-control"></td>
                                            <td><input type="text" name="" id="" class="form-control"></td>
                                            <td><input type="text" name="" id="" class="form-control"></td>
                                        </tr>
                                        <tr>
                                            <td><input type="text" name="" id="" class="form-control"></td>
                                            <td><input type="text" name="" id="" class="form-control"></td>
                                            <td><input type="text" name="" id="" class="form-control"></td>
                                            <td><input type="text" name="" id="" class="form-control"></td>
                                            <td><input type="text" name="" id="" class="form-control"></td>
                                        </tr>
                                        <tr>
                                            <td><input type="text" name="" id="" class="form-control"></td>
                                            <td><input type="text" name="" id="" class="form-control"></td>
                                            <td><input type="text" name="" id="" class="form-control"></td>
                                            <td><input type="text" name="" id="" class="form-control"></td>
                                            <td><input type="text" name="" id="" class="form-control"></td>
                                        </tr>
                                        <tr>
                                            <td><input type="text" name="" id="" class="form-control"></td>
                                            <td><input type="text" name="" id="" class="form-control"></td>
                                            <td><input type="text" name="" id="" class="form-control"></td>
                                            <td><input type="text" name="" id="" class="form-control"></td>
                                            <td><input type="text" name="" id="" class="form-control"></td>
                                        </tr>
                                        <tr>
                                            <td><input type="text" name="" id="" class="form-control"></td>
                                            <td><input type="text" name="" id="" class="form-control"></td>
                                            <td><input type="text" name="" id="" class="form-control"></td>
                                            <td><input type="text" name="" id="" class="form-control"></td>
                                            <td><input type="text" name="" id="" class="form-control"></td>
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
@stop
@section('adminlte_js')
<script>
    document.getElementById('datePicker').valueAsDate = new Date();
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
    function addNomorSeri() { 
        $('.modalAddNoSeri').modal('show');
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

    $('.scan-produk').DataTable({
            "ordering":false,
            "autoWidth": false,
            searching: false,
            "lengthChange": false,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
    });
    $('.dari').select2({});
    $('.nama_produk').select2({});
    $('.unit').select2({});
    $(".number-input").inputFilter(function (value) {
            return /^\d*$/.test(value);
            var value = $(this).val();
    });
    $(document).on('click','.btn-tambah', function () {
        let tanggal = $('#datePicker').val();
        let dari = $('.dari').val();
        let nama_produk = $('.nama_produk').val();
        let unit = $('.unit').val();
        let jumlah = $('.jumlah').val();

        tambahData(tanggal, dari, nama_produk, unit, jumlah);
        $('.btn-simpan').removeClass('hapus');
    });
    function tambahData(tanggal, dari, nama_produk, unit, jumlah) { 
        let tambahData = '<tr><td>'+tanggal+'</td><td>'+nama_produk+'</td><td>'+unit+'</td><td>'+dari+'</td><td>'+jumlah+'</td><td><button class="btn btn-primary" onclick="addNomorSeri()"><i class="fas fa-qrcode"></i> Tambah No Seri</button> <button class="btn btn-danger btn-delete"><i class="fas fa-trash"></i> Delete</button></td></tr>';
        $('.addData tbody').append(tambahData);
    }
    
    $(document).on('click','.btn-delete', function () {
        $(this).parent().parent().remove();
        var check = $('tbody.tambah_data tr').length;
        if (check != 0) {
            $('.btn-simpan').removeClass('hapus');
        } else {
            $('.btn-simpan').addClass('hapus');
        }
    });
</script>
@stop