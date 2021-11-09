@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
<style>
    .hapus{
        display: none;
    }
   .nomor-so{
        background-color: #717FE1;
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 18px
    }
    .nomor-akn{
        background-color: #DF7458;
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 18px
    }
    .nomor-po{
        background-color: #85D296;
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 18px
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
                                            <label for="" id="tanggal" class="col-sm-5 text-right">Tanggal Masuk</label>
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
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>10-09-2022</td>
                                                    <td>Divisi IT</td>
                                                    <td>Uji Coba Produk</td>
                                                    <td><button class="btn btn-info" onclick="modalRancangan()"><i class="far fa-eye"></i> Detail</button></td>
                                                </tr>
                                                <tr>
                                                    <td>10-09-2022</td>
                                                    <td>Divisi IT</td>
                                                    <td>Uji Coba Produk</td>
                                                    <td><button class="btn btn-info" onclick="modalRancangan()"><i class="far fa-eye"></i> Detail</button></td>
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
                                                                                <input type="date" class="form-control tanggal">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row top-min">
                                                                            <label for="" class="col-12 font-weight-bold col-form-label">Dari</label>
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
                                                                <th>Dari</th>
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

<!-- Modal Detail Rancangan-->
<div class="modal fade modal-rancangan" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Rancangan</h5>
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
                                    <div class="card nomor-so">
                                        <div class="card-body">
                                            10-04-2020
                                        </div>
                                      </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Dari</label>
                                <div class="card nomor-akn">
                                    <div class="card-body">
                                        Divisi IT
                                    </div>
                                  </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Tujuan</label>
                                <div class="card nomor-po">
                                    <div class="card-body">
                                        Uji Coba Produk
                                    </div>
                                  </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-rancangan">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Produk</th>
                                    <th>Jumlah</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td scope="row">1</td>
                                    <td>Ambulatory</td>
                                    <td>100 Unit</td>
                                    <td><button class="btn btn-info" onclick="tambahanRancangan()"><i class="far fa-edit"></i> Detail</button></td>
                                </tr>
                                <tr>
                                    <td scope="row">2</td>
                                    <td>Ambulatory</td>
                                    <td>100 Unit</td>
                                    <td><button class="btn btn-info" onclick="tambahanRancangan()"><i class="far fa-edit"></i> Detail</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                <button type="button" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambahan Perakitan-->
  <!-- Modal Detail-->
  <div class="modal fade tambahan-perakitan" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title"><b>Detail Produk AMBULATORY BLOOD PRESSURE MONITOR</b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
              <table class="table table-striped scan-produk">
                  <thead>
                      <tr>
                          <th><input type="checkbox" id="head-cb"></th>
                          <th>Nomor Seri</th>
                          <th>Layout</th>
                          <th>Aksi</th>
                      </tr>
                  </thead>
                  <tbody>
                      <tr>
                          <td><input type="checkbox" class="cb-child" value="1"></td>
                          <td><input type="text" name="" id="" class="form-control"></td>
                          <td><select name="" id="" class="form-control">
                              <option value="1">Layout 1</option>
                              <option value="2">Layout 2</option>
                              </select></td>
                              <td>
                                <button class="btn btn-success"><i class="fas fa-plus"></i></button>&nbsp;
                                <button class="btn btn-danger"><i class="fas fa-minus"></i></button>
                            </td>
                      </tr>
                      <tr>
                          <td><input type="checkbox" class="cb-child" value="2"></td>
                          <td><input type="text" name="" id="" class="form-control"></td>
                          <td><select name="" id="" class="form-control">
                              <option value="1">Layout 1</option>
                              <option value="2">Layout 2</option>
                              </select></td>
                              <td>
                                <button class="btn btn-success"><i class="fas fa-plus"></i></button>&nbsp;
                                <button class="btn btn-danger"><i class="fas fa-minus"></i></button>
                            </td>
                      </tr>
                      <tr>
                          <td><input type="checkbox" class="cb-child" value="3"></td>
                          <td><input type="text" name="" id="" class="form-control"></td>
                          <td><select name="" id="" class="form-control">
                              <option value="1">Layout 1</option>
                              <option value="2">Layout 2</option>
                              </select></td>
                              <td>
                                <button class="btn btn-success"><i class="fas fa-plus"></i></button>&nbsp;
                                <button class="btn btn-danger"><i class="fas fa-minus"></i></button>
                            </td>
                      </tr>
                  </tbody>
              </table>
              <button class="btn btn-info" data-toggle="modal" data-target="#ubah-layout">Ubah Layout</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambahan Rancangan-->
  <!-- Modal Detail-->
  <div class="modal fade tambahan-rancangan" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title"><b>Detail Produk AMBULATORY BLOOD PRESSURE MONITOR</b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
              <table class="table table-striped scan-produk">
                  <thead>
                      <tr>
                          <th><input type="checkbox" id="head-cb"></th>
                          <th>Nomor Seri</th>
                          <th>Layout</th>
                          <th>Aksi</th>
                      </tr>
                  </thead>
                  <tbody>
                      <tr>
                          <td><input type="checkbox" class="cb-child" value="1"></td>
                          <td>36541654654654564</td>
                          <td><select name="" id="" class="form-control">
                              <option value="1">Layout 1</option>
                              <option value="2">Layout 2</option>
                              </select></td>
                              <td>
                                <button class="btn btn-success"><i class="fas fa-plus"></i></button>&nbsp;
                                <button class="btn btn-danger"><i class="fas fa-minus"></i></button>
                            </td>
                      </tr>
                      <tr>
                          <td><input type="checkbox" class="cb-child" value="2"></td>
                          <td>36541654654654564</td>
                          <td><select name="" id="" class="form-control">
                              <option value="1">Layout 1</option>
                              <option value="2">Layout 2</option>
                              </select></td>
                              <td>
                                <button class="btn btn-success"><i class="fas fa-plus"></i></button>&nbsp;
                                <button class="btn btn-danger"><i class="fas fa-minus"></i></button>
                            </td>
                      </tr>
                      <tr>
                          <td><input type="checkbox" class="cb-child" value="3"></td>
                          <td>36541654654654564</td>
                          <td><select name="" id="" class="form-control">
                              <option value="1">Layout 1</option>
                              <option value="2">Layout 2</option>
                              </select></td>
                              <td>
                                <button class="btn btn-success"><i class="fas fa-plus"></i></button>&nbsp;
                                <button class="btn btn-danger"><i class="fas fa-minus"></i></button>
                            </td>
                      </tr>
                  </tbody>
              </table>
              <button class="btn btn-info" data-toggle="modal" data-target="#ubah-layout">Ubah Layout</button>
            </div>
        </div>
    </div>
</div>

  <!-- Modal Ubah Layout-->
  <div class="modal fade" id="ubah-layout" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah Layout</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Layout</label>
                    <select name="" id="change-layout" class="form-control">
                      <option value="1">Layout 1</option>
                      <option value="2">Layout 2</option>
                  </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                <button type="button" class="btn btn-primary" onclick="ubahData()">Simpan</button>
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
        let tanggal = $('.tanggal').val();
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
        if (tujuan.length > 30) {
            var a = tujuan.substring(0, 10) + '...';
        }else{
            var a = tujuan;
        }
        let tambah_data = '<tr><td>'+tanggal+'</td><td>'+divisi+'</td><td>'+a+'</td><td>'+produk+'</td><td>'+jumlah+'</td><td><button class="btn btn-primary" data-toggle="modal" data-target=".modal-produk" onclick="tambahanPerakitan()"><i class="fas fa-plus"></i> Tambah</button>&nbsp;<button class="btn btn-danger btn-delete"><i class="fas fa-trash"></i> Hapus</button></td></tr>'
        $('tbody.tambah_data').append(tambah_data);
    }
    $(document).on('click', '.btn-delete', function(e){
        e.preventDefault();
        $(this).parent().parent().remove();
        var check = $('tbody.tambah_data tr').length;
        if(check != 0){
            $('.btn-simpan').removeClass('hapus');
        }else{
            $('.btn-simpan').addClass('hapus');
        }
    });
    

    $(document).ready(function () {
        $('.table-rancangan').DataTable({});

        $("#head-cb").on('click', function () {
            var isChecked = $("#head-cb").prop('checked')
            $('.cb-child').prop('checked', isChecked)
        });
    });

    function ubahData() { 
        let checkbox_terpilih = $('.scan-produk tbody .cb-child:checked');
        let layout = $('#change-layout').val();
        $.each(checkbox_terpilih, function (index, elm) {
            let b = $(checkbox_terpilih).parent().next().next().children().val(layout);
        });
        $('#ubah-layout').modal('hide');
    }

    $('#datetimepicker1').daterangepicker({});

    function modalRancangan() { 
        $('.modal-rancangan').modal('show');
    }

    function tambahanRancangan() { 
        $('.tambahan-rancangan').modal('show');
    }

    function tambahanPerakitan() { 
        $('.tambahan-perakitan').modal('show');
    }
</script>
@stop
