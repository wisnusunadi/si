@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
<style>
    .hapus{
        display: none;
    }
</style>
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="nav nav-tabs" id="myTab" role="tabList">
                        <li class="nav-item" role="presentation">
                            <a href="#produk" class="nav-link active" id="produk-tab" data-toggle="tab" role="tab" aria-controls="produk" aria-selected="true">Rancangan Perakitan</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="#semua-produk" class="nav-link" id="semua-produk-tab" data-toggle="tab" role="tab" aria-controls="semua-produk" aria-selected="true">Buat Perakitan</a>
                        </li>
                    </ul>
                    <div class="tab-content card" id="myTabContent">
                        <div class="tab-pane fade show active card-body" id="produk" role="tabpanel" aria-labelledby="produk-tab">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-horizontal">
                                        <div class="form-group row">
                                            <label for="" id="tanggal" class="col-sm-5 text-right">Tanggal</label>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control" id="datetimepicker1">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="table-responsive">
                                        {{-- Tanggal Masuk dan Tanggal Keluar --}}
                                        <table class="table table-hover pertanggal" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Tanggal Masuk</th>
                                                    <th>Dari</th>
                                                    <th>Tujuan</th>
                                                    <td>Aksi</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>10-09-2022</td>
                                                    <td>Divisi IT</td>
                                                    <td>Uji Coba Produk</td>
                                                    <td>Ambulatory</td>
                                                    <td>100 Unit</td>
                                                    <td><button class="btn btn-info" onclick="detailtanggal()"><i class="far fa-eye"></i> Detail</button></td>
                                                </tr>
                                                <tr>
                                                    <td>10-09-2022</td>
                                                    <td>10-09-2022</td>
                                                    <td>Divisi IT</td>
                                                    <td>Uji Coba Produk</td>
                                                    <td>Ambulatory</td>
                                                    <td>100 Unit</td>
                                                    <td><button class="btn btn-info" onclick="detailtanggal()"><i class="far fa-eye"></i> Detail</button></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade show card-body" id="semua-produk" role="tabpanel" aria-labelledby="semua-produk-tab">
                            <div class="row">
                                <div class="col-lg-4 col-md-12 col-sm-12 mb-4">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card card-primary b-radius">
                                                <div class="card-body">
                                                    <ul class="list-group list-group-flush">
                                                        <li class="list-group-item">
                                                            <div class="row">
                                                                <div class="col-12 mt-3">
                                                                    <form method="post">
                                                                        <div class="form-group row top-min">
                                                                            <label for="" class="col-12 font-weight-bold col-form-label">Tanggal Masuk</label>
                                                                            <div class="col-12">
                                                                                <input type="date" class="form-control" id="tanggal">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row top-min">
                                                                            <label for="" class="col-12 font-weight-bold col-form-label">Tujuan</label>
                                                                            <div class="col-12">
                                                                                <select class="form-control division" name="division">
                                                                                    <option value="Divisi IT">Divisi IT</option>
                                                                                    <option value="Divisi QC">Divisi QC</option>
                                                                                    <option value="Divisi Perakitan">Divisi Perakitan</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row top-min">
                                                                            <label for="" class="col-12 font-weight-bold col-form-label">Keterangan</label>
                                                                            <div class="col-12">
                                                                                <textarea name="tujuan" id="" class="form-control tujuan"></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <div class="row">
                                                                <div class="col-12 mt-3">
                                                                    <form method="post">
                                                                        <div class="form-group row top-min">
                                                                            <label for="" class="col-12 font-weight-bold col-form-label">Produk</label>
                                                                            <div class="col-12">
                                                                                <select class="form-control product" name="produk">
                                                                                    <option value="AMBULATORY BLOOD PRESSURE MONITOR">AMBULATORY BLOOD PRESSURE MONITOR</option>
                                                                                    <option value="AIR STERILIZER AND PURIFIER">AIR STERILIZER AND PURIFIER</option>
                                                                                    <option value="BACKUP POWER">BACKUP POWER</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row top-min">
                                                                            <label for="" class="col-12 font-weight-bold col-form-label">Jumlah</label>
                                                                            <div class="col-12">
                                                                                <input type="text" name="stok" id=""
                                                                                    class="form-control number-input input-notzero stok">
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
                                                        </li>
                                                      </ul>
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
                                                                <th>Tujuan</th>
                                                                <th>Keterangan</th>
                                                                <th>Produk</th>
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
                                                        <button class="btn btn-success" type="button">Transfer</button>&nbsp;
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
                        </div>
                    </div>
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
                <h5 class="modal-title">Tambah Produk AIR STERILIZER AND PURIFIER</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-striped scan-produk">
                    <thead>
                        <tr>
                            <th>Nomor Seri</th>
                            <th>Layout</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>36541654654654564</td>
                            <td><select name="" id="" class="form-control">
                                <option value="1">Layout 1</option>    
                                <option value="2">Layout 2</option>    
                            </select></td>
                            <td><button class="btn btn-success addproduk"><i class="fas fa-plus"></i></button>&nbsp;<button class="btn btn-danger addproduk"><i class="fas fa-minus"></i></button></td>
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

    $(document).on('click','.btn-tambah', function () {
        let tanggal = $('#tanggal').val();
        let divisi = $('.division').val();
        let tujuan = $('.tujuan').val();
        let produk = $('.product').val();
        let jumlah = parseInt($('.stok').val());
        $.ajax({
            success: function () { 
                addData(tanggal, divisi, tujuan, produk, jumlah)
            $('.btn-simpan').removeClass('hapus');
            }
        });
    });

    function addData(tanggal, divisi, tujuan, produk, jumlah) { 
        let tambah_data = '<tr><td>'+tanggal+'</td><td>'+divisi+'</td><td>'+tujuan+'</td><td>'+produk+'</td><td>'+jumlah+'</td><td><button class="btn btn-primary" data-toggle="modal" data-target=".modal-produk"><i class="fas fa-plus"></i> Tambah Produk</button>&nbsp;<button class="btn btn-danger btn-delete"><i class="fas fa-trash"></i> Hapus</button></td></tr>'
        $('tbody.tambah_data').append(tambah_data);
    }
    $(document).on('click', '.btn-delete', function(e){
        e.preventDefault();
        $(this).parent().parent().remove();
        var check = $('.kd-barang-field').length;
        if(check != 0){
            $('.btn-simpan').addClass('hapus');
        }else{
            $('.btn-simpan').removeClass('hapus');
        }
    });
    
    $('.scan-produk').DataTable({
            "oLanguage": {
            "sSearch": "Scan Nomor Seri:"
            }
    });
</script>
@stop
