@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
<link rel="stylesheet" href="{{ asset('vendor/fullcalendar/main.css') }}">
<script src="{{ asset('vendor/fullcalendar/main.js') }}"></script>
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Transfer Gudang</h1>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<div class="row ml-2">
    <div class="col-sm-4">
        <div class="form-group">
            <label for="">Tanggal Perakitan</label>
            <input type="text" name="" id="" class="form-control daterange">
        </div>
    </div>
</div>
<div class="row ml-2">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">              
                <div class="row">
                    <div class="col-lg-12">
                        <table class="table table_produk_perakitan">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Tgl Mulai</th>
                                    <th>Tgl Selesai</th>
                                    <th>Produk</th>
                                    <th>Jumlah</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td scope="row">16-06-2021 <br><span class="badge badge-primary">Baru</span></td>
                                    <td>18-06-2021 <br> <span class="badge badge-warning">Kurang 5 Hari</span></td>
                                    <td>Produk 1</td>
                                    <td>100 Unit <br> <span class="badge badge-dark">Kurang 50 Unit</span></td>
                                    <td>
                                        <button class="btn btn-outline-success" onclick="modalRakit()"><i class="far fa-edit"></i> Transfer</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td scope="row">18-06-2021 <br><span class="badge badge-info">Revisi</span></td>
                                    <td>21-06-2021 <br> <span class="badge badge-danger">Lebih 10 Hari</span></td>
                                    <td>Produk 2</td>
                                    <td>200 Unit</td>
                                    <td>
                                        <button class="btn btn-outline-success" onclick="modalRakit()"><i class="far fa-edit"></i> Transfer</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td scope="row">20-06-2021</td>
                                    <td>25-06-2021</td>
                                    <td>Produk 3</td>
                                    <td>300 Unit</td>
                                    <td>
                                        <button class="btn btn-outline-success" onclick="modalRakit()"><i class="far fa-edit"></i> Transfer</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade modalRakit" id="" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
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
                                <label for="">Nomor BPPB</label>
                                    <div class="card" style="background-color: #C8E1A7">
                                        <div class="card-body">
                                            89798797856456
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
                            <div class="col-sm">
                                <label for="">Kategori</label>
                                <div class="card" style="background-color: #FCF9C4">
                                    <div class="card-body">
                                        Kategori 1
                                    </div>
                                  </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm">
                                <label for="">Jumlah Rakit</label>
                                <div class="card" style="background-color: #FFCC83">
                                    <div class="card-body">
                                        100 Unit
                                    </div>
                                  </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Tanggal Mulai</label>
                                <div class="card" style="background-color: #FFE0B4">
                                    <div class="card-body">
                                        10-06-2021
                                    </div>
                                  </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Tanggal Selesai</label>
                                <div class="card" style="background-color: #FFECB2">
                                    <div class="card-body">
                                        13-06-2021
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
                                            <th><input type="checkbox" name="" id="head-cb"></th>
                                            <th>Nomor Seri</th>
                                            <th><input type="checkbox" name="" id="head-cb-1"></th>
                                            <th>Nomor Seri</th>
                                            <th><input type="checkbox" name="" id="head-cb-2"></th>
                                            <th>Nomor Seri</th>
                                            <th><input type="checkbox" name="" id="head-cb-3"></th>
                                            <th>Nomor Seri</th>
                                            <th><input type="checkbox" name="" id="head-cb-4"></th>
                                            <th>Nomor Seri</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="checkbox" name="" id="" class="cb-child"></td>
                                            <td>65462136516515</td>
                                            <td><input type="checkbox" name="" id="" class="cb-child-1"></td>
                                            <td>84651651651562</td>
                                            <td><input type="checkbox" name="" id="" class="cb-child-2"></td>
                                            <td>89784946512123</td>
                                            <td><input type="checkbox" name="" id="" class="cb-child-3"></td>
                                            <td>65666654545465</td>
                                            <td><input type="checkbox" name="" id="" class="cb-child-4"></td>
                                            <td>59689498484548</td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" name="" id="" class="cb-child"></td>
                                            <td>65462136516515</td>
                                            <td><input type="checkbox" name="" id="" class="cb-child-1"></td>
                                            <td>84651651651562</td>
                                            <td><input type="checkbox" name="" id="" class="cb-child-2"></td>
                                            <td>89784946512123</td>
                                            <td><input type="checkbox" name="" id="" class="cb-child-3"></td>
                                            <td>65666654545465</td>
                                            <td><input type="checkbox" name="" id="" class="cb-child-4"></td>
                                            <td>59689498484548</td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" name="" id="" class="cb-child"></td>
                                            <td>65462136516515</td>
                                            <td><input type="checkbox" name="" id="" class="cb-child-1"></td>
                                            <td>84651651651562</td>
                                            <td><input type="checkbox" name="" id="" class="cb-child-2"></td>
                                            <td>89784946512123</td>
                                            <td><input type="checkbox" name="" id="" class="cb-child-3"></td>
                                            <td>65666654545465</td>
                                            <td><input type="checkbox" name="" id="" class="cb-child-4"></td>
                                            <td>59689498484548</td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" name="" id="" class="cb-child"></td>
                                            <td>65462136516515</td>
                                            <td><input type="checkbox" name="" id="" class="cb-child-1"></td>
                                            <td>84651651651562</td>
                                            <td><input type="checkbox" name="" id="" class="cb-child-2"></td>
                                            <td>89784946512123</td>
                                            <td><input type="checkbox" name="" id="" class="cb-child-3"></td>
                                            <td>65666654545465</td>
                                            <td><input type="checkbox" name="" id="" class="cb-child-4"></td>
                                            <td>59689498484548</td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" name="" id="" class="cb-child"></td>
                                            <td>65462136516515</td>
                                            <td><input type="checkbox" name="" id="" class="cb-child-1"></td>
                                            <td>84651651651562</td>
                                            <td><input type="checkbox" name="" id="" class="cb-child-2"></td>
                                            <td>89784946512123</td>
                                            <td><input type="checkbox" name="" id="" class="cb-child-3"></td>
                                            <td>65666654545465</td>
                                            <td><input type="checkbox" name="" id="" class="cb-child-4"></td>
                                            <td>59689498484548</td>
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
                <button type="button" class="btn btn-primary" onclick="transfer()">Simpan</button>
            </div>
        </div>
    </div>
</div>
@stop

@section('adminlte_js')
<script>
    $('.table_produk_perakitan').DataTable({
        "ordering": false,
        "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            },
        "lengthChange": false,
        searching: false,
        });

        $('.scan-produk').DataTable({
            ordering: false,
            "autoWidth": false,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            },
            "lengthChange": false,
        });
    function modalRakit() { 
        $('.modalRakit').modal('show');
        $("#head-cb").on('click', function () {
            var isChecked = $("#head-cb").prop('checked')
            $('.cb-child').prop('checked', isChecked)
        });

        $("#head-cb-1").on('click', function () {
            var isChecked = $("#head-cb-1").prop('checked')
            $('.cb-child-1').prop('checked', isChecked)
        });

        $("#head-cb-2").on('click', function () {
            var isChecked = $("#head-cb-2").prop('checked')
            $('.cb-child-2').prop('checked', isChecked)
        });
        $("#head-cb-3").on('click', function () {
            var isChecked = $("#head-cb-3").prop('checked')
            $('.cb-child-3').prop('checked', isChecked)
        });
        $("#head-cb-4").on('click', function () {
            var isChecked = $("#head-cb-4").prop('checked')
            $('.cb-child-4').prop('checked', isChecked)
        });
    }
    function transfer() {
        Swal.fire({
            title: "Apakah anda yakin?",
            text: "Data yang sudah di transfer tidak dapat diubah!",
            icon: "warning",
            buttons: true,
            showCancelButton: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                Swal.fire(
                    'Berhasil!',
                    'Data berhasil di transfer!',
                    'success'
                );
                $('.modalRakit').modal('hide');
            } else {
                Swal.fire(
                    'Batal!',
                    'Data tidak berhasil di transfer!',
                    'error'
                );
            }
        });
    }
    $('.daterange').daterangepicker({
        locale: {
            format: 'DD/MM/YYYY'
        }
    });
</script>
@stop
