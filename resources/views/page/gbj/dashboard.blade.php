@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
<style>
  .card-hidden{
    display: none;
  }
  .foo {
        float: left;
        width: 20px;
        height: 20px;
        margin: 5px;
        border: 1px solid rgba(0, 0, 0, .2);
    }

    .green {
        background: #28A745;
    }

    .blue {
        background: #17A2B8;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="col-lg-12">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="#dashboard-produk" class="nav-link active" id="dashboard-produk-tab" data-toggle="tab"
                            role="tab" aria-controls="semua-produk" aria-selected="true">Produk</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#dashboard-transfer" class="nav-link" id="dashboard-transfer-tab" data-toggle="tab"
                            role="tab" aria-controls="semua-produk" aria-selected="true">Transfer</a>
                    </li>
                    <li class="nav-item" role="presentation">
                      <a href="#dashboard-penjualan" class="nav-link" id="dashboard-penjualan-tab" data-toggle="tab"
                          role="tab" aria-controls="semua-produk" aria-selected="true">Penjualan</a>
                  </li>
                </ul>
                <div class="tab-content card" id="myTabContent">
                    {{-- Produk --}}
                    <div class="tab-pane fade show active card-body" id="dashboard-produk" role="tabpanel"
                        aria-labelledby="dashboard-produk-tab">
                        {{-- Stok Produk --}}
                        <p class="m-2 h3">Dashboard Produk</p>
                        <div class="card">
                          <div class="card-header">
                            <h5 class="card-title"><i class="fas fa-cubes"></i> Berdasarkan Stok Produk</h5>
                          </div>
                          <div class="card-body">
                            <div class="row">
                              <div class="col-6 col-md-4">
                                <div class="card">
                                  <div class="card-header bg-yellow">
                                    <h3 class="card-title">Jumlah Stok 10 sampai 20 Unit</h3>
                                  </div>
                                  <!-- /.card-header -->
                                  <div class="card-body">
                                    <canvas id="myChart1" width="400" height="400"></canvas>
                                  </div>
                                  <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                              </div>
                              <!-- /.col -->
                              <div class="col-6 col-md-4">
                                  <div class="card">
                                    <div class="card-header bg-orange">
                                      <h3 class="card-title">Jumlah Stok 9 sampai 5 Unit</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                    <canvas id="myChart2" width="400" height="400"></canvas>
                                    </div>
                                    <!-- /.card-body -->
                                  </div>
                                  <!-- /.card -->
                                  <!-- /.card -->
                              </div>
                              <!-- /.col -->
                              <div class="col-6 col-md-4">
                                <div class="card">
                                  <div class="card-header bg-danger">
                                    <h3 class="card-title">Jumlah Stok 9 sampai 5 Unit</h3>
                                  </div>
                                  <!-- /.card-header -->
                                  <div class="card-body">
                                  <canvas id="myChart3" width="400" height="400"></canvas>
                                  </div>
                                  <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                                <!-- /.card -->
                              </div>
                          </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-6 col-md-4">
                            <div class="card">
                              <div class="card-header">
                                <h5 class="card-title"><i class="fas fa-boxes"></i> Daftar Stok Produk</h5>
                              </div>
                              <div class="card-body">
                                <div class="row row-cols-3">
                                  <div class="col">
                                    <div class="form-group">
                                      <select name="" id="produk-pilih" class="form-control">
                                        <option value="">Produk 1</option>
                                        <option value="">Produk 2</option>
                                      </select>
                                    </div>
                                  </div>
                                  <div class="col">
                                    <div class="form-group">
                                      <select name="" id="layout-pilih" class="form-control">
                                        <option value="">Layout 1</option>
                                        <option value="">Layout 2</option>
                                      </select>
                                    </div>
                                  </div>
                                  <div class="col">
                                      <a href="#" class="btn btn-outline-primary">Search</a>
                                  </div>
                                </div>
                                <div class="row">
                                  <table class="table">
                                    <thead>
                                      <tr>
                                        <th>Produk</th>
                                        <th>Layout</th>
                                        <th>Jumlah Stok</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <tr>
                                        <td scope="row">Produk 1</td>
                                        <td>Layout 1</td>
                                        <td>12 Unit</td>
                                      </tr>
                                      <tr>
                                        <td scope="row">Produk 2</td>
                                        <td>Layout 2</td>
                                        <td>12 Unit</td>
                                      </tr>
                                      <tr>
                                        <td scope="row">Produk 1</td>
                                        <td>Layout 1</td>
                                        <td>12 Unit</td>
                                      </tr>
                                      <tr>
                                        <td scope="row">Produk 2</td>
                                        <td>Layout 2</td>
                                        <td>12 Unit</td>
                                      </tr>
                                      <tr>
                                        <td scope="row">Produk 1</td>
                                        <td>Layout 1</td>
                                        <td>12 Unit</td>
                                      </tr>
                                      <tr>
                                        <td scope="row">Produk 2</td>
                                        <td>Layout 2</td>
                                        <td>12 Unit</td>
                                      </tr>
                                      <tr>
                                        <td scope="row">Produk 1</td>
                                        <td>Layout 1</td>
                                        <td>12 Unit</td>
                                      </tr>
                                      <tr>
                                        <td scope="row">Produk 2</td>
                                        <td>Layout 2</td>
                                        <td>12 Unit</td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </div>
                              </div>
                              <div class="card-footer clearfix">
                                <ul class="pagination pagination-sm m-0 float-right">
                                    <li class="page-item"><a class="page-link" href="#">«</a></li>
                                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item"><a class="page-link" href="#">»</a></li>
                                </ul>
                            </div>
                            </div>
                          </div>
                          <div class="col-sm-6 col-md-8">
                              <div class="card">
                                <div class="card-header">
                                  <h5 class="card-title">
                                    <i class="far fa-calendar-alt"></i> Berdasarkan Waktu Masuk Produk
                                  </h5>
                                </div>
                                <div class="card-body">
                                  <div class="row">
                                    <div class="col-6">
                                      <div class="card">
                                        <div class="card-header bg-yellow">
                                          <h3 class="card-title">Produk Masuk 3 Bulan sampai 6 Bulan</h3>
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                          <canvas id="myChart4" width="400" height="400"></canvas>
                                        </div>
                                        <!-- /.card-body -->
                                      </div>
                                    </div>
    
                                    <div class="col-6">
                                      <div class="card">
                                        <div class="card-header bg-orange">
                                          <h3 class="card-title">Produk Masuk 6 Bulan sampai 1 Tahun</h3>
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                          <canvas id="myChart5" width="400" height="400"></canvas>
                                        </div>
                                        <!-- /.card-body -->
                                      </div>
                                    </div>
                                  </div>
    
                                  <div class="row">
                                    <div class="col-6">
                                      <div class="card">
                                        <div class="card-header bg-pink">
                                          <h3 class="card-title">Produk Masuk 3 Tahun sampai 1 Tahun</h3>
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                          <canvas id="myChart6" width="400" height="400"></canvas>
                                        </div>
                                        <!-- /.card-body -->
                                      </div>
                                    </div>
    
                                    <div class="col-6">
                                      <div class="card">
                                        <div class="card-header bg-danger">
                                          <h3 class="card-title">Produk Masuk Lebih dari 3 Tahun</h3>
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                          <canvas id="myChart7" width="400" height="400"></canvas>
                                        </div>
                                        <!-- /.card-body -->
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                          </div>
                        </div>
                    </div>
                    {{-- Transfer --}}
                    <div class="tab-pane fade show card-body" id="dashboard-transfer" role="tabpanel" aria-labelledby="dashboard-transfer-tab">
                      <p class="m-2 h3">Dashboard Transfer Permintaan</p>
                        <div class="row">
                          <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-info">
                              <div class="inner">
                                <h3>150</h3>
                                <p>Permintaan Transfer Produk</p>
                              </div>
                              <div class="icon">
                                <i class="fas fa-cart-arrow-down"></i>
                              </div>
                              <a href="#transfer-produk-all" class="small-box-footer" id="transfer-produk-all-tab" data-toggle="tab"
                              role="tab" aria-controls="semua-produk" aria-selected="true">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                          </div>
                          <!-- ./col -->
                          <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-warning">
                              <div class="inner">
                                <h3>50</h3>
                                <p>Permintaan Transfer Produk Lebih Dari 1 Hari</p>
                              </div>
                              <div class="icon">
                                <i class="fas fa-clipboard-list"></i>
                              </div>
                              <a href="#transfer-produk-one-day" class="small-box-footer" id="transfer-produk-one-day-tab" data-toggle="tab"
                              role="tab" aria-controls="semua-produk" aria-selected="true">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                          </div>
                          <!-- ./col -->
                          <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-orange">
                              <div class="inner">
                                <h3>50</h3>
                                <p>Permintaan Transfer Produk Lebih Dari 2 Hari</p>
                              </div>
                              <div class="icon">
                                <i class="far fa-list-alt"></i>
                              </div>
                              <a href="#" class="small-box-footer" id="transfer-produk-two-day-tab" data-toggle="tab"
                              role="tab" aria-controls="semua-produk" aria-selected="true">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                          </div>
                          <!-- ./col -->
                          <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-danger">
                              <div class="inner">
                                <h3>25</h3>
                                <p>Permintaan Transfer Produk Lebih Dari 3 Hari</p>
                              </div>
                              <div class="icon">
                                <i class="fas fa-window-close"></i>
                              </div>
                              <a href="#" class="small-box-footer" id="transfer-produk-three-day-tab" data-toggle="tab"
                              role="tab" aria-controls="semua-produk" aria-selected="true">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                          </div>
                          <!-- ./col -->
                        </div>
                          <div class="card card-custom-transfer-all">
                            <div class="card-body">
                              <table class="table table-transfer-all">
                                <thead>
                                  <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Produk</th>
                                    <th>Dari</th>
                                    <th>Jumlah</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td scope="row">1</td>
                                    <td>11-04-2021</td>
                                    <td>Produk 1</td>
                                    <td>Divisi IT</td>
                                    <td>100 Unit</td>
                                  </tr>
                                  <tr>
                                    <td scope="row">2</td>
                                    <td>11-04-2021</td>
                                    <td>Produk 2</td>
                                    <td>Divisi IT</td>
                                    <td>100 Unit</td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                          </div>
                          <div class="card card-custom-transfer-one card-hidden">
                            <div class="card-body">
                              <table class="table table-transfer-one">
                                <thead>
                                  <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Produk</th>
                                    <th>Dari</th>
                                    <th>Jumlah</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td scope="row">1</td>
                                    <td>11-05-2022</td>
                                    <td>Produk 2</td>
                                    <td>Divisi QC</td>
                                    <td>100 Unit</td>
                                  </tr>
                                  <tr>
                                    <td scope="row">2</td>
                                    <td>11-05-2022</td>
                                    <td>Produk 2</td>
                                    <td>Divisi QC</td>
                                    <td>100 Unit</td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                          </div>
                          <div class="card card-custom-transfer-two card-hidden">
                            <div class="card-body">
                              <table class="table table-transfer-two">
                                <thead>
                                  <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Produk</th>
                                    <th>Dari</th>
                                    <th>Jumlah</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td scope="row">1</td>
                                    <td>11-05-2022</td>
                                    <td>Produk 2</td>
                                    <td>Divisi Produksi</td>
                                    <td>100 Unit</td>
                                  </tr>
                                  <tr>
                                    <td scope="row">2</td>
                                    <td>11-05-2022</td>
                                    <td>Produk 2</td>
                                    <td>Divisi Produksi</td>
                                    <td>100 Unit</td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                          </div>
                          <div class="card card-custom-transfer-three card-hidden">
                            <div class="card-body">
                              <table class="table table-transfer-three">
                                <thead>
                                  <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Produk</th>
                                    <th>Dari</th>
                                    <th>Jumlah</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td scope="row">1</td>
                                    <td>11-05-2022</td>
                                    <td>Produk 2</td>
                                    <td>Divisi Pusat</td>
                                    <td>100 Unit</td>
                                  </tr>
                                  <tr>
                                    <td scope="row">2</td>
                                    <td>11-05-2022</td>
                                    <td>Produk 2</td>
                                    <td>Divisi Pusat</td>
                                    <td>100 Unit</td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                          </div>
                    </div>
                    {{-- Penjualan --}}
                    <div class="tab-pane fade show card-body" id="dashboard-penjualan" role="tabpanel" aria-labelledby="dashboard-penjualan-tab">
                      <p class="m-2 h3">Dashboard Penjualan Produk</p>
                      <div class="row">
                       <div class="col-sm-8">
                          <div class="card">
                            <div class="card-header">
                              <h5 class="card-title">
                                <i class="fas fa-stream mr-1"></i>
                                Daftar Batas Transfer Produk
                              </h5>
                            </div>
                            <div class="card-body">
                              <div class="row">
                                <div class="col-md-8">
                                  <table class="table table-striped">
                                    <thead>
                                      <tr>
                                        <th>No</th>
                                        <th>Nama Produk</th>
                                        <th>Dari/Ke</th>
                                        <th>Jumlah</th>
                                        <th>Batas Transfer</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <tr>
                                        <td scope="row">1</td>
                                        <td>Produk 1</td>
                                        <td><span class="badge badge-success">Divisi IT</span></td>
                                        <td>100 Unit</td>
                                        <td>15-06-2021</td>
                                      </tr>
                                      <tr>
                                        <td scope="row">2</td>
                                        <td>Produk 2</td>
                                        <td><span class="badge badge-info">Divisi QC</span></td>
                                        <td>50 Unit</td>
                                        <td>15-10-2021</td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </div>
                                <div class="col-6 col-md-4">
                                  <div class="card">
                                    <div class="card-body">
                                        <p class="card-text">Keterangan Kolom <b>Dari/Ke:</b></p>
                                        <p class="card-text">
                                            <div class="foo green"></div> : Dari
                                        </p>
                                        <p class="card-text">
                                            <div class="foo blue"></div> : Ke
                                        </p>
                                    </div>
                                </div>
                                </div>
                              </div>
                            </div>
                          </div>
                       </div>
                       <div class="col-sm-4">
                          <div class="card">
                            <div class="card-header">
                              <h5 class="card-title">
                                <i class="fas fa-pallet mr-1"></i>
                                Jumlah Produk Tidak Tersedia sesuai SO
                              </h5>
                            </div>
                            <div class="card-body">
                              <table class="table table-hover">
                                <thead>
                                  <tr>
                                    <th>No</th>
                                    <th>Nama Produk</th>
                                    <th>Jumlah</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td scope="row">1</td>
                                    <td>Produk 1</td>
                                    <td>50 Unit</td>
                                  </tr>
                                  <tr>
                                    <td scope="row">2</td>
                                    <td>Produk 2</td>
                                    <td>50 Unit</td>
                                  </tr>
                                </tbody>
                              </table>
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
@stop

@section('adminlte_js')
<script>
  // Jumlah Stok 10 sampai 20 Unit
  const DATA_COUNT = 7;
const NUMBER_CFG = {count: DATA_COUNT, min: 0, max: 100};
const data = {
  labels: Utils.months({count: DATA_COUNT}),
  datasets: [
    {
      label: 'Dataset 1',
      data: Utils.numbers(NUMBER_CFG),
      fill: false,
      borderColor: Utils.CHART_COLORS.red,
      backgroundColor: Utils.transparentize(Utils.CHART_COLORS.red, 0.5),
    },
    {
      label: 'Dataset 2',
      data: Utils.numbers(NUMBER_CFG),
      fill: false,
      borderColor: Utils.CHART_COLORS.blue,
      backgroundColor: Utils.transparentize(Utils.CHART_COLORS.blue, 0.5),
    }
  ]
};
var ctx = document.getElementById('myChart1').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: data
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

 // Jumlah Stok 9 sampai 5 Unit
 var ctx = document.getElementById('myChart2').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Nama Produk 1', 'Nama Produk 2', 'Nama Produk 3', 'Nama Produk 4', 'Nama Produk 5', 'Nama Produk 6'],
        datasets: [{
            data: [12, 19, 13, 15, 12, 13],
            label: 'Stok',
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

 // Jumlah Stok 1 sampai 4 Unit
 var ctx = document.getElementById('myChart3').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Nama Produk 1', 'Nama Produk 2', 'Nama Produk 3', 'Nama Produk 4', 'Nama Produk 5', 'Nama Produk 6'],
        datasets: [{
            data: [12, 19, 13, 15, 12, 13],
            label: 'Stok',
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});


 // Jumlah Stok 1 sampai 4 Unit
 var ctx = document.getElementById('myChart4').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Nama Produk 1', 'Nama Produk 2', 'Nama Produk 3', 'Nama Produk 4', 'Nama Produk 5', 'Nama Produk 6'],
        datasets: [{
            data: [12, 19, 13, 15, 12, 13],
            label: 'Stok',
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

 // Jumlah Stok 1 sampai 4 Unit
 var ctx = document.getElementById('myChart5').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Nama Produk 1', 'Nama Produk 2', 'Nama Produk 3', 'Nama Produk 4', 'Nama Produk 5', 'Nama Produk 6'],
        datasets: [{
            data: [12, 19, 13, 15, 12, 13],
            label: 'Stok',
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

 // Jumlah Stok 1 sampai 4 Unit
 var ctx = document.getElementById('myChart6').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Nama Produk 1', 'Nama Produk 2', 'Nama Produk 3', 'Nama Produk 4', 'Nama Produk 5', 'Nama Produk 6'],
        datasets: [{
            data: [12, 19, 13, 15, 12, 13],
            label: 'Stok',
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

 // Jumlah Stok 1 sampai 4 Unit
 var ctx = document.getElementById('myChart7').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Nama Produk 1', 'Nama Produk 2', 'Nama Produk 3', 'Nama Produk 4', 'Nama Produk 5', 'Nama Produk 6'],
        datasets: [{
            data: [12, 19, 13, 15, 12, 13],
            label: 'Stok',
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
$(document).ready(function () {
  $('#produk-pilih').select2({
    placeholder: 'Pilih Produk'
  });
  $('#layout-pilih').select2({
    placeholder: 'Pilih Layout'
  });

  $('#transfer-produk-all-tab').on("click", function () {
      $('.card-custom-transfer-all').removeClass('card-hidden');
      $('.card-custom-transfer-one').addClass('card-hidden');
      $('.card-custom-transfer-two').addClass('card-hidden');
      $('.card-custom-transfer-three').addClass('card-hidden');
  });

  $('#transfer-produk-one-day-tab').on("click", function () {
      $('.card-custom-transfer-all').addClass('card-hidden');
      $('.card-custom-transfer-one').removeClass('card-hidden');
      $('.card-custom-transfer-two').addClass('card-hidden');
      $('.card-custom-transfer-three').addClass('card-hidden');
  });

  $('#transfer-produk-two-day-tab').on("click", function () {
      $('.card-custom-transfer-all').addClass('card-hidden');
      $('.card-custom-transfer-one').addClass('card-hidden');
      $('.card-custom-transfer-two').removeClass('card-hidden');
      $('.card-custom-transfer-three').addClass('card-hidden');
  });

  $('#transfer-produk-three-day-tab').on("click", function () {
      $('.card-custom-transfer-all').addClass('card-hidden');
      $('.card-custom-transfer-one').addClass('card-hidden');
      $('.card-custom-transfer-two').addClass('card-hidden');
      $('.card-custom-transfer-three').removeClass('card-hidden');
  });

  $('.table-transfer-all').DataTable({});
  $('.table-transfer-one').DataTable({});
  $('.table-transfer-two').DataTable({});
  $('.table-transfer-three').DataTable({});
});
</script>
@stop
