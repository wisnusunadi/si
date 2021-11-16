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
                                </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-dark text-bold">
                                        <td scope="row" colspan="4">Senin 10-04-2021</td>
                                    </tr>
                                    <tr>
                                        <td>07.00</td>
                                        <td>564564641654</td>
                                        <td>Produk 1</td>
                                        <td>100 Unit</td>
                                    </tr>
                                    <tr>
                                        <td>10.00</td>
                                        <td>564564641654</td>
                                        <td>Produk 2</td>
                                        <td>100 Unit</td>
                                    </tr>
                                    <tr class="table-dark text-bold">
                                        <td scope="row" colspan="4">Selasa 23-09-2021</td>
                                    </tr>
                                    <tr>
                                        <td>13.00</td>
                                        <td>564564641654</td>
                                        <td>Produk 3</td>
                                        <td>100 Unit</td>
                                    </tr>
                                    <tr>
                                        <td>15.00</td>
                                        <td>564564641654</td>
                                        <td>Produk 4</td>
                                        <td>100 Unit</td>
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
    })
</script>
@stop
