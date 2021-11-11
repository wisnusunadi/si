@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
<div class="card">
    <div class="card-header">
      <h3 class="card-title">Produk dari Perakitan </h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
      <table class="table table-bordered dalam-perakitan">
        <thead>
          <tr>
            <th>No</th>
            <th>Tanggal Masuk</th>
            <th>Produk</th>
            <th>Jumlah</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>10-11-2021</td>
                <td>AMBULATORY BLOOD PRESSURE MONITOR</td>
                <td>100 Unit</td>
                <td><div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton"
                    aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v"></i>
                    <div class="dropdown-menu">
                        <button type="button" class="dropdown-item terimaProduk" onclick="openModalTerima()">
                            <i class="far fa-edit"></i>&nbsp;Terima
                          </button>
                          <button type="button" class="dropdown-item detailProduk" onclick="openModalView()">
                            <i class="far fa-eye"></i>&nbsp;Detail
                          </button>
                    </div>
                </div></td>
            </tr>
            <tr>
                <td>2</td>
                <td>11-11-2021</td>
                <td>AMBULATORY BLOOD PRESSURE TESTING</td>
                <td>100</td>
                <td><div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton"
                    aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v"></i>
                    <div class="dropdown-menu">
                        <button type="button" class="dropdown-item terimaProduk" onclick="openModalTerima()">
                            <i class="far fa-edit"></i>&nbsp;Terima
                          </button>
                          <button type="button" class="dropdown-item detailProduk" onclick="openModalView()">
                            <i class="far fa-eye"></i>&nbsp;Detail
                          </button>
                    </div>
                </div></td>
            </tr>
        </tbody>
      </table>
    </div>
  </div>
  <!-- Modal Detail-->
  <div class="modal fade terima-produk" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
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
                        </tr>
                        <tr>
                            <td><input type="checkbox" class="cb-child" value="2"></td>
                            <td>36541654654654564</td>
                            <td><select name="" id="" class="form-control">
                                <option value="1">Layout 1</option>
                                <option value="2">Layout 2</option>
                                </select></td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" class="cb-child" value="3"></td>
                            <td>36541654654654564</td>
                            <td><select name="" id="" class="form-control">
                                <option value="1">Layout 1</option>
                                <option value="2">Layout 2</option>
                                </select></td>
                        </tr>
                    </tbody>
                </table>
                <button class="btn btn-info" data-toggle="modal" data-target="#ubah-layout">Ubah Layout</button>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                  <button type="button" class="btn btn-primary">Simpan</button>
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
  
  <!-- Modal -->
  <div class="modal fade detail-layout" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title"><b>Detail Produk AMBULATORY BLOOD PRESSURE MONITOR</b></h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
              </div>
              <div class="modal-body">
                  <table class="table table-seri">
                      <thead>
                          <tr>
                              <th>No Seri</th>
                              <th>Layout</th>
                          </tr>
                      </thead>
                      <tbody>
                          <tr>
                              <td>654165654</td>
                              <td>Layout 1</td>
                          </tr>
                          <tr>
                            <td>654165654</td>
                            <td>Layout 1</td>
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
    $('.table-seri').DataTable({
        "oLanguage": {
        "sSearch": "Cari:"}
    });
    $('.dalam-perakitan').DataTable({
        "oLanguage": {
        "sSearch": "Cari:"}
    });
    $('.scan-produk').DataTable({
            "oLanguage": {
        "sSearch": "Cari:"
        }
    });
    $(document).ready(function () {
        $('.terimaProduk').click(function (e) { 
            $('.terima-produk').modal('show');
        });

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

    function openModalTerima() {
        $('.terima-produk').modal('show');
    }
    function openModalView() { 
        $('.detail-layout').modal('show');
    }
</script>
@stop
