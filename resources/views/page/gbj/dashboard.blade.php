@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
<style>
    .hidden {
        display: none;
    }

</style>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard</h1>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
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
                role="tab" aria-controls="semua-produk" aria-selected="true">Lihat Detail <i
                    class="fas fa-arrow-circle-right"></i></a>
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
            <a href="#transfer-produk-one-day" class="small-box-footer" id="transfer-produk-one-day-tab"
                data-toggle="tab" role="tab" aria-controls="semua-produk" aria-selected="true">Lihat Detail <i
                    class="fas fa-arrow-circle-right"></i></a>
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
            <a href="#" class="small-box-footer" id="transfer-produk-two-day-tab" data-toggle="tab" role="tab"
                aria-controls="semua-produk" aria-selected="true">Lihat Detail <i
                    class="fas fa-arrow-circle-right"></i></a>
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
            <a href="#" class="small-box-footer" id="transfer-produk-three-day-tab" data-toggle="tab" role="tab"
                aria-controls="semua-produk" aria-selected="true">Lihat Detail <i
                    class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="chartdetail">
                    <canvas id="chartdetailtransferall" class="" width="400" height="100"></canvas>
                </div>
                <div class="chartdetailone hidden">
                    <canvas id="chartdetailtransferminusoneday" class="" width="400" height="100"></canvas>
                </div>
                <div class="chartdetailtwo hidden">
                    <canvas id="chartdetailtransferminustwoday" class="" width="400" height="100"></canvas>
                </div>
                <div class="chartdetailthree hidden">
                    <canvas id="chartdetailtransferminusthreeday" class="" width="400" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="far fa-calendar-alt mr-1"></i>
                    Berdasarkan Waktu Masuk Produk
                </h3>
                <div class="card-tools">
                    <ul class="nav nav-pills ml-auto" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" href="#three-months" id="three-months-tab" data-toggle="tab"
                                aria-controls="three-months" aria-selected="true">3 Bulan Sampai 6 Bulan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#six-months" id="six-months-tab" data-toggle="tab"
                                aria-controls="six-months" aria-selected="false">6 Bulan Sampai 1 Tahun</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#one-years" id="one-years-tab" data-toggle="tab"
                                aria-controls="one-years" aria-selected="false">1 Tahun Sampai 3 Tahun</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#three-years" id="three-years-tab" data-toggle="tab"
                                aria-controls="three-years" aria-selected="false">Lebih dari 3 Tahun</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="three-months" role="tabpanel"
                        aria-labelledby="three-months-tab">
                        <canvas id="myChart4" width="400" height="100"></canvas>
                    </div>
                    <div class="tab-pane fade" id="six-months" role="tabpanel" aria-labelledby="six-months-tab">
                        <canvas id="myChart5" width="400" height="100"></canvas>
                    </div>
                    <div class="tab-pane fade" id="one-years" role="tabpanel" aria-labelledby="one-years-tab">
                        <canvas id="myChart6" width="400" height="100"></canvas>
                    </div>
                    <div class="tab-pane fade" id="three-years" role="tabpanel" aria-labelledby="three-years-tab">
                        <canvas id="myChart7" width="400" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Stok Produk</div>
                <div class="card-tools">
                    <div class="btn-group">
                        <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" data-offset="-52"
                            aria-expanded="false">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                        <div class="dropdown-menu" role="menu" style="">
                            <a href="#" class="dropdown-item">Layout 1</a>
                            <a href="#" class="dropdown-item">Layout 2 </a>
                            <a href="#" class="dropdown-item">Layout 3</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart">
                    <canvas id="myChart8" width="1000" height="1000"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Jumlah Stok</div>
                <div class="card-tools">
                    <ul class="nav nav-pills ml-auto" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" href="#ten-stock" id="ten-stock-tab" data-toggle="tab"
                                aria-controls="ten-stock" aria-selected="true">Stok 10 sampai 20 Unit</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#five-stock" id="five-stock-tab" data-toggle="tab"
                                aria-controls="five-stock" aria-selected="false">Stok 5 sampai 9 Unit</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#one-stock" id="one-stock-tab" data-toggle="tab"
                                aria-controls="one-stock" aria-selected="false">Stok 1 sampai 4 Unit</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="ten-stock" role="tabpanel"
                        aria-labelledby="ten-stock-tab">
                        <canvas id="myChart1" width="400" height="190"></canvas>
                    </div>
                    <div class="tab-pane fade" id="five-stock" role="tabpanel" aria-labelledby="five-stock-tab">
                        <canvas id="myChart2" width="400" height="190"></canvas>
                    </div>
                    <div class="tab-pane fade" id="one-stock" role="tabpanel" aria-labelledby="one-stock-tab">
                        <canvas id="myChart3" width="400" height="190"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-md-8">
              <canvas id="myChart9" width="400" height="100"></canvas>
          </div>
          <div class="col-md-4">
            <div class="card">
              <div class="card-body">
              <canvas id="myChart10" width="400" height="200"></canvas>
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
<script src="{{ asset('native/js/gbj/dashboard.js') }}"></script>
@stop
