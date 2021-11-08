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
                        <div class="row">
                            <div class="col-sm">
                              <div class="card">
                                <div class="card-header bg-yellow">
                                  <h3 class="card-title">Jumlah Stok 10 sampai 20 Unit</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                  <canvas id="myChart" width="400" height="400"></canvas>
                                </div>
                                <!-- /.card-body -->
                              </div>
                              <!-- /.card -->

                              <!-- /.card -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm">
                                <div class="card">
                                  <div class="card-header bg-orange">
                                    <h3 class="card-title">Jumlah Stok 9 sampai 5 Unit</h3>
                                  </div>
                                  <!-- /.card-header -->
                                  <div class="card-body">
                                    <table class="table table-bordered">
                                      <thead>
                                        <tr>
                                          <th style="width: 10px">No</th>
                                          <th>Produk</th>
                                          <th>Stok</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <tr>
                                          <td>1.</td>
                                          <td>Ambulatory</td>
                                          <td>15 Unit</td>
                                        </tr>
                                        <tr>
                                          <td>1.</td>
                                          <td>Ambulatory</td>
                                          <td>15 Unit</td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </div>
                                  <!-- /.card-body -->
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
                                <!-- /.card -->
                                <!-- /.card -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm">
                                <div class="card">
                                    <div class="card-header bg-danger">
                                      <h3 class="card-title">Jumlah Stok 1 sampai 4 Unit</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                      <table class="table table-bordered">
                                        <thead>
                                          <tr>
                                            <th style="width: 10px">No</th>
                                            <th>Produk</th>
                                            <th>Stok</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <tr>
                                            <td>1.</td>
                                            <td>Ambulatory</td>
                                            <td>15 Unit</td>
                                          </tr>
                                          <tr>
                                            <td>1.</td>
                                            <td>Ambulatory</td>
                                            <td>15 Unit</td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </div>
                                    <!-- /.card-body -->
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
                                <!-- /.card -->
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

const ctx = document.getElementById('myChart').getContext('2d');
const myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
        datasets: [{
            label: '# of Votes',
            data: [12, 19, 3, 5, 2, 3],
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
