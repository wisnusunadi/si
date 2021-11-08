@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
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
                </ul>
                <div class="tab-content card" id="myTabContent">

                    {{-- Produk --}}
                    <div class="tab-pane fade show active card-body" id="dashboard-produk" role="tabpanel"
                        aria-labelledby="dashboard-produk-tab">
                        {{-- Stok Produk --}}
                        <p class="m-2 h3">Stok Produk</p>
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
                        <div class="row">
                          <div class="col-6 col-sm-4">
                            <div class="card">
                              <div class="card-body">
                                <table class="table">
                                  <thead>
                                    <tr>
                                      <th></th>
                                      <th></th>
                                      <th></th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td scope="row"></td>
                                      <td></td>
                                      <td></td>
                                    </tr>
                                    <tr>
                                      <td scope="row"></td>
                                      <td></td>
                                      <td></td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>
                          <div class="col-6 col-sm-4">
                            <h1>test</h1>
                          </div>
                        </div>
                    </div>
                    {{-- Transfer --}}
                    <div class="tab-pane fade show active card-body" id="dashboard-transfer" role="tabpanel"
                        aria-labelledby="dashboard-transfer-tab">
                        <div class="row"></div>
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
var ctx = document.getElementById('myChart1').getContext('2d');
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
</script>
@stop
