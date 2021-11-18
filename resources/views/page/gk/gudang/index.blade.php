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

.topnav a:hover {
  border-bottom: 3px solid red;
}

.topnav a.active {
  border-bottom: 3px solid red;
}
        .active {
        box-shadow: 12px 4px 8px 0 rgba(0, 0, 0, 0.2), 12px 6px 20px 0 rgba(0, 0, 0, 0.19);
    }

</style>
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Produk Gudang Karantina</h1>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
  
<div class="ml-3">
  <div class="row">
    <div class="col-xl-6">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title"><i class="fab fa-whmcs"></i> Sparepart</h3>
        </div>
        <div class="card-body">
          <table class="table tableSparepart">
            <thead class="thead-dark">
              <tr>
                <th>Kode Sparepart</th>
                <th>Nama</th>
                <th>Unit</th>
                <th>Jumlah</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td scope="row">Kode 1</td>
                <td>Sparepart 1</td>
                <td>Unit 1</td>
                <td>100 pcs</td>
                <td>
                  <a class="btn btn-outline-info" href="{{ url('gk/gudang/sparepart/1') }}"><i class="far fa-eye"></i> Detail</a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="col-xl-6">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-tools"></i> Unit</h3>
        </div>
        <div class="card-body">
            <table class="table tableUnit">
              <thead class="thead-light">
                <tr>
                  <th>Kode Unit</th>
                  <th>Nama</th>
                  <th>Jumlah</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td scope="row">Kode 1</td>
                  <td>Unit 1</td>
                  <td>100 Unit</td>
                  <td>
                    <a class="btn btn-outline-info" href="{{ url('gk/gudang/unit/1') }}"><i class="far fa-eye"></i> Detail</a>
                  </td>
                </tr>
              </tbody>
            </table>
        </div>
      </div>
    </div>
  </div>
</div>

@stop
@section('adminlte_js')
<script>
  $('.tableSparepart').dataTable({
    "paging": true,
    "lengthChange": true,
    "searching": true,
    "ordering": true,
    "info": true,
    "autoWidth": false,
    "responsive": true,
    "language": {
      "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
    }
  });
  $('.tableUnit').dataTable({
    "paging": true,
    "lengthChange": true,
    "searching": true,
    "ordering": true,
    "info": true,
    "autoWidth": false,
    "responsive": true,
    "language": {
      "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
    }
  });

  function sparepartDetail() {
    $('.sparepartDetail').modal('show');
  }

  function unitDetail() {
    $('.unitDetail').modal('show');
  }
</script>
@stop