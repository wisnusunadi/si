@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
<div class="content-header">
    <input type="hidden" name="" id="authid" value="{{ Auth::user()->divisi_id }}">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Persetujuan Perubahan Produk Nomor Seri</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/gbj/dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Produk</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-end">
            <button class="btn btn-outline-info dropdown-toggle" type="button" id="semuaprodukfilter"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-filter"></i>&nbsp;
                Filter
            </button>
            <div class="dropdown-menu p-3 text-nowrap" aria-labelledby="semuaprodukfilter">
                <div class="dropdown-header">Kelompok Produk</div>
                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="alkes" value="Alat Kesehatan" />
                        <label class="form-check-label" for="sp_kelompok">
                            Alat Kesehatan
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="sarkes" value="Sarana Kesehatan" />
                        <label class="form-check-label" for="sp_kelompok">
                            Sarana Kesehatan
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="water" value="Water Treatment" />
                        <label class="form-check-label" for="sp_kelompok">
                            Water Treatment
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered" id="gudang-barang" style="width: 100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Produk</th>
                    <th>Merk</th>
                    <th>Nama Produk</th>
                    <th>Jumlah Pengajuan</th>
                    <th>Kelompok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1.</td>
                    <td>9458794856</td>
                    <td>Elitech</td>
                    <td>ABPM50</td>
                    <td>50 Unit</td>
                    <td>Water Treatment</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary btnProdukModal"><i class="far fa-eye"></i> Detail</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
  
  <!-- Modal -->
  <div class="modal fade" id="produkModal" tabindex="-1" aria-labelledby="produkModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="produkModalLabel">Pengajuan Nomor Seri ABPM50</h5>
        </div>
        <div class="modal-body">
          <div class="card">
            <div class="card card-primary card-outline card-outline-tabs">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill"
                                href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home"
                                aria-selected="true">Belum Digunakan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-four-profile-tab" data-toggle="pill"
                                href="#custom-tabs-four-profile" role="tab"
                                aria-controls="custom-tabs-four-profile" aria-selected="false">Sudah
                                Digunakan</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-four-tabContent">
                        <div class="tab-pane fade active show" id="custom-tabs-four-home" role="tabpanel"
                            aria-labelledby="custom-tabs-four-home-tab">
                            <form action="" id="noseriForm" name="noseriForm">
                                <input type="hidden" name="action_by" id="actionby" value="{{ Auth::user()->id }}">
                                <table class="table scan-produk">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="head-cb"></th>
                                            <th>No. Seri</th>
                                            <th>Layout</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="checkbox" class="cb-produk" name="noseri[]" value="1"></td>
                                            <td>1</td>
                                            <td>E8</td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" class="cb-produk" name="noseri[]" value="2"></td>
                                            <td>2</td>
                                            <td>E8</td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" class="cb-produk" name="noseri[]" value="3"></td>
                                            <td>3</td>
                                            <td>E8</td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" class="cb-produk" name="noseri[]" value="4"></td>
                                            <td>4</td>
                                            <td>E8</td>
                                        </tr>
                                    </tbody>
                                </table>
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-four-profile" role="tabpanel"
                            aria-labelledby="custom-tabs-four-profile-tab">
                            <table class="table scan-produk1">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="head-cb1"></th>
                                        <th>No. Seri</th>
                                        <th>Digunakan</th>
                                        <th>Layout</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="checkbox" class="cb-produk1" name="noseri[]" value="1"></td>
                                        <td>1</td>
                                        <td>SO - 1</td>
                                        <td>E8</td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" class="cb-produk1" name="noseri[]" value="2"></td>
                                        <td>2</td>
                                        <td>SO - 2</td>
                                        <td>E8</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary">Setujui Perubahan</button>
          <button type="button" class="btn btn-danger">Tidak Setujui Perubahan</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>
  

@stop

@section('adminlte_js')
<script>
        var authid = $('#authid').val();
        // Filter
        $('#alkes').click(function () {
        if ($(this).prop('checked') == true) {
            datatable.column(5).search($(this).val()).draw();
        } else {
            datatable.column(5).search('').draw();
        }
    })

    $('#sarkes').click(function () {
        if ($(this).prop('checked') == true) {
            datatable.column(5).search($(this).val()).draw();
        } else {
            datatable.column(5).search('').draw();
        }
    })

    $('#water').click(function () {
        if ($(this).prop('checked') == true) {
            datatable.column(5).search($(this).val()).draw();
        } else {
            datatable.column(5).search('').draw();
        }
    })
        // Datatable
        var datatable = $('#gudang-barang').DataTable({
        processing: true,
        language: {
            search: "Cari:"
        }
    });

    $('.btnProdukModal').click(function () {
        $('#produkModal').modal('show');
    });
    $('.scan-produk').DataTable();
    $('.scan-produk1').DataTable();

    $('#head-cb').click(function () {
        if ($(this).prop('checked') == true) {
            $('.scan-produk').DataTable()
                .column(0)
                .nodes()
                .to$()
                .find('input[type=checkbox]')
                .prop('checked', true);
        } else {
            $('.scan-produk').DataTable()
                .column(0)
                .nodes()
                .to$()
                .find('input[type=checkbox]')
                .prop('checked', false);
        }
    })

    $('#head-cb1').click(function () {
        if ($(this).prop('checked') == true) {
            $('.scan-produk1').DataTable()
                .column(0)
                .nodes()
                .to$()
                .find('input[type=checkbox]')
                .prop('checked', true);
        } else {
            $('.scan-produk1').DataTable()
                .column(0)
                .nodes()
                .to$()
                .find('input[type=checkbox]')
                .prop('checked', false);
        }
    });

    
</script>
@stop