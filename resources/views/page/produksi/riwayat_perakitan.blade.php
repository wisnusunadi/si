@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Riwayat Perakitan</h1>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<section class="content">
    <div class="container-fluid">
        <div class="row ml-1">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="">Pilih Produk</label>
                    <select name="" id="" class="form-control produk" multiple>
                        <option value="" selected>All Produk</option>
                        <option value="">Produk 1</option>
                        <option value="">Produk 2</option>
                        <option value="">Produk 3</option>
                        <option value="">Produk 4</option>
                        <option value="">Produk 5</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="">Tanggal Perakitan</label>
                    <input type="text" name="" id="" class="form-control daterange">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row text-center">
                            <div class="col-6">
                                <h3 class="font-weight-bold">50</h3>
                                <h4 class="font-weight-normal text-muted">Perakitan</h4>
                            </div>
                            <div class="col-6">
                                <h3 class="font-weight-bold">1000</h3>
                                <h4 class="font-weight-normal text-muted">Produk yang Dibuat</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                        <table class="table table-history">
                            <thead class="thead-light">
                                <tr>
                                    <th>Tanggal & Waktu</th>
                                    <th>Nomor BPPB</th>
                                    <th>Produk</th>
                                    <th>Jumlah</th>
                                    <th>Aksi</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-dark text-bold">
                                        <td scope="row">Senin 10-04-2021</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>07.00</td>
                                        <td>564564641654</td>
                                        <td>Produk 1</td>
                                        <td>100 Unit</td>
                                        <td><button class="btn btn-outline-secondary" data-toggle="modal" data-target=".modal_id"><i class="far fa-eye"></i> Detail</button></td>
                                    </tr>
                                    <tr>
                                        <td>10.00</td>
                                        <td>564564641654</td>
                                        <td>Produk 2</td>
                                        <td>100 Unit</td>
                                        <td><button class="btn btn-outline-secondary" data-toggle="modal" data-target=".modal_id"><i class="far fa-eye"></i> Detail</button></td>
                                    </tr>
                                    <tr class="table-dark text-bold">
                                        <td scope="row">Selasa 23-09-2021</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>13.00</td>
                                        <td>564564641654</td>
                                        <td>Produk 3</td>
                                        <td>100 Unit</td>
                                        <td><button class="btn btn-outline-secondary" data-toggle="modal" data-target=".modal_id"><i class="far fa-eye"></i> Detail</button></td>
                                    </tr>
                                    <tr>
                                        <td>15.00</td>
                                        <td>564564641654</td>
                                        <td>Produk 4</td>
                                        <td>100 Unit</td>
                                        <td><button class="btn btn-outline-secondary" data-toggle="modal" data-target=".modal_id"><i class="far fa-eye"></i> Detail</button></td>
                                    </tr>
                                </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal -->
<div class="modal fade modal_id" id="" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm">
                                <label for="">Tanggal & Waktu</label>
                                <div class="card-group">
                                    <div class="card" style="background-color: #C8E1A7">
                                        <div class="card-body">
                                            <p class="card-text">Senin 10-04-2021</p>
                                        </div>
                                    </div>
                                    <div class="card" style="background-color: #C8E1A7">
                                        <div class="card-body">
                                            <p class="card-text">08.00 WIB</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Nomor BPPB</label>
                                <div class="card" style="background-color: #F89F81">
                                    <div class="card-body">
                                        516546546546546
                                    </div>
                                  </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm">
                                <label for="">Nama Produk</label>
                                <div class="card" style="background-color: #FCF9C4">
                                    <div class="card-body">
                                        Produk 1
                                    </div>
                                  </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Jumlah</label>
                                <div class="card" style="background-color: #FFCC83">
                                    <div class="card-body">
                                        100 Unit
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>846464654654</td>
                                            <td>654654654654</td>
                                            <td>957489688845</td>
                                        </tr>
                                        <tr>
                                            <td>846464654654</td>
                                            <td>654654654654</td>
                                            <td>957489688845</td>
                                        </tr>
                                        <tr>
                                            <td>846464654654</td>
                                            <td>654654654654</td>
                                            <td>957489688845</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>
@stop

@section('adminlte_js')
<script>
    $('.produk').select2({});
    $('.daterange').daterangepicker({
        locale: {
            format: 'DD/MM/YYYY'
        }
    });
    $('.table-history').DataTable({
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": false,
        "responsive": true,
    });
    $('.scan-produk').DataTable({
            "ordering":false,
            "autoWidth": false,
            "lengthChange": false,
            "oLanguage":{
                "sSearch": "Cari: "
            }
    });
</script>
@stop
