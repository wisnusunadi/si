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

    .hidden {
        display: none !important;
    }

    .active {
        box-shadow: 12px 4px 8px 0 rgba(0, 0, 0, 0.2), 12px 6px 20px 0 rgba(0, 0, 0, 0.19);
    }

    .otg:hover {
        box-shadow: 12px 4px 8px 0 rgba(0, 0, 0, 0.2), 12px 6px 20px 0 rgba(0, 0, 0, 0.19);
    }

    .nav-border {
        border-bottom: 2px solid black;
        content: "";
    }

</style>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <nav>
                    <div class="nav nav-tabs topnav" id="nav-tab" role="tablist">
                        <a class="nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab"
                            aria-controls="nav-home" aria-selected="true">Penjualan</a>
                        <a class="nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab"
                            aria-controls="nav-profile" aria-selected="false">Produk</a>
                        <a class="nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab"
                            aria-controls="nav-contact" aria-selected="false">Transfer</a>
                    </div>
                </nav>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-6 col-xxl-4">
                        <div class="row">
                            <div class="col-6 col-md-4">
                                <div id="transferoneday" class="card active otg" style="background-color: #E6EFFA">
                                    <div class="card-body text-center">
                                        <h4>10</h4>
                                        <p class="card-text">Produk Melewati Batas Transfer Lebih Dari 1 Hari</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div id="transfertwoday" class="card otg" style="background-color: #FEF7EA">
                                    <div class="card-body text-center">
                                        <h4>50</h4>
                                        <p class="card-text">Produk Melewati Batas Transfer Lebih Dari 2 Hari</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div id="transferthreeday" class="card otg" style="background-color: #FCEDE9">
                                    <div class="card-body text-center">
                                        <h4>60</h4>
                                        <p class="card-text">Produk Melewati Batas Transfer Lebih Dari 3 Hari</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="transferonedaytable">
                                    <table class="table table-produk-batas-transfer-one-day">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Produk</th>
                                                <th>Jumlah</th>
                                                <th>Batas Transfer</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td scope="row">1</td>
                                                <td>Produk 1</td>
                                                <td>100 Unit</td>
                                                <td>10-04-2021</td>
                                                <td><a href="{{ url('gbj/so') }}" class="btn btn-outline-primary"><i
                                                            class="fas fa-paper-plane"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td scope="row">2</td>
                                                <td>Produk 2</td>
                                                <td>59 Unit</td>
                                                <td>23-09-2021</td>
                                                <td><a href="{{ url('gbj/so') }}" class="btn btn-outline-primary"><i
                                                            class="fas fa-paper-plane"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td scope="row">1</td>
                                                <td>Produk 1</td>
                                                <td>100 Unit</td>
                                                <td>10-04-2021</td>
                                                <td><a href="{{ url('gbj/so') }}" class="btn btn-outline-primary"><i
                                                            class="fas fa-paper-plane"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td scope="row">2</td>
                                                <td>Produk 2</td>
                                                <td>59 Unit</td>
                                                <td>23-09-2021</td>
                                                <td><a href="{{ url('gbj/so') }}" class="btn btn-outline-primary"><i
                                                            class="fas fa-paper-plane"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td scope="row">1</td>
                                                <td>Produk 1</td>
                                                <td>100 Unit</td>
                                                <td>10-04-2021</td>
                                                <td><a href="{{ url('gbj/so') }}" class="btn btn-outline-primary"><i
                                                            class="fas fa-paper-plane"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td scope="row">2</td>
                                                <td>Produk 2</td>
                                                <td>59 Unit</td>
                                                <td>23-09-2021</td>
                                                <td><a href="{{ url('gbj/so') }}" class="btn btn-outline-primary"><i
                                                            class="fas fa-paper-plane"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td scope="row">1</td>
                                                <td>Produk 1</td>
                                                <td>100 Unit</td>
                                                <td>10-04-2021</td>
                                                <td><a href="{{ url('gbj/so') }}" class="btn btn-outline-primary"><i
                                                            class="fas fa-paper-plane"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td scope="row">2</td>
                                                <td>Produk 2</td>
                                                <td>59 Unit</td>
                                                <td>23-09-2021</td>
                                                <td><a href="{{ url('gbj/so') }}" class="btn btn-outline-primary"><i
                                                            class="fas fa-paper-plane"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td scope="row">1</td>
                                                <td>Produk 1</td>
                                                <td>100 Unit</td>
                                                <td>10-04-2021</td>
                                                <td><a href="{{ url('gbj/so') }}" class="btn btn-outline-primary"><i
                                                            class="fas fa-paper-plane"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td scope="row">2</td>
                                                <td>Produk 2</td>
                                                <td>59 Unit</td>
                                                <td>23-09-2021</td>
                                                <td><a href="{{ url('gbj/so') }}" class="btn btn-outline-primary"><i
                                                            class="fas fa-paper-plane"></i></a></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="transfertwodaytable hidden">
                                    <table class="table table-produk-batas-transfer-two-day">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Produk</th>
                                                <th>Jumlah</th>
                                                <th>Batas Transfer</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td scope="row">1</td>
                                                <td>Produk 1</td>
                                                <td>50 Unit</td>
                                                <td>10-04-2021</td>
                                                <td><a href="{{ url('gbj/so') }}" class="btn btn-outline-primary"><i
                                                            class="fas fa-paper-plane"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td scope="row">2</td>
                                                <td>Produk 2</td>
                                                <td>100 Unit</td>
                                                <td>23-09-2021</td>
                                                <td><a href="{{ url('gbj/so') }}" class="btn btn-outline-primary"><i
                                                            class="fas fa-paper-plane"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td scope="row">1</td>
                                                <td>Produk 1</td>
                                                <td>50 Unit</td>
                                                <td>10-04-2021</td>
                                                <td><a href="{{ url('gbj/so') }}" class="btn btn-outline-primary"><i
                                                            class="fas fa-paper-plane"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td scope="row">2</td>
                                                <td>Produk 2</td>
                                                <td>100 Unit</td>
                                                <td>23-09-2021</td>
                                                <td><a href="{{ url('gbj/so') }}" class="btn btn-outline-primary"><i
                                                            class="fas fa-paper-plane"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td scope="row">1</td>
                                                <td>Produk 1</td>
                                                <td>50 Unit</td>
                                                <td>10-04-2021</td>
                                                <td><a href="{{ url('gbj/so') }}" class="btn btn-outline-primary"><i
                                                            class="fas fa-paper-plane"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td scope="row">2</td>
                                                <td>Produk 2</td>
                                                <td>100 Unit</td>
                                                <td>23-09-2021</td>
                                                <td><a href="{{ url('gbj/so') }}" class="btn btn-outline-primary"><i
                                                            class="fas fa-paper-plane"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td scope="row">1</td>
                                                <td>Produk 1</td>
                                                <td>50 Unit</td>
                                                <td>10-04-2021</td>
                                                <td><a href="{{ url('gbj/so') }}" class="btn btn-outline-primary"><i
                                                            class="fas fa-paper-plane"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td scope="row">2</td>
                                                <td>Produk 2</td>
                                                <td>100 Unit</td>
                                                <td>23-09-2021</td>
                                                <td><a href="{{ url('gbj/so') }}" class="btn btn-outline-primary"><i
                                                            class="fas fa-paper-plane"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td scope="row">1</td>
                                                <td>Produk 1</td>
                                                <td>50 Unit</td>
                                                <td>10-04-2021</td>
                                                <td><a href="{{ url('gbj/so') }}" class="btn btn-outline-primary"><i
                                                            class="fas fa-paper-plane"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td scope="row">2</td>
                                                <td>Produk 2</td>
                                                <td>100 Unit</td>
                                                <td>23-09-2021</td>
                                                <td><a href="{{ url('gbj/so') }}" class="btn btn-outline-primary"><i
                                                            class="fas fa-paper-plane"></i></a></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="transferthreedaytable hidden">
                                    <table class="table table-produk-batas-transfer-three-day">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Produk</th>
                                                <th>Jumlah</th>
                                                <th>Batas Transfer</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td scope="row">1</td>
                                                <td>Produk 1</td>
                                                <td>31 Unit</td>
                                                <td>10-04-2021</td>
                                                <td><a href="{{ url('gbj/so') }}" class="btn btn-outline-primary"><i
                                                            class="fas fa-paper-plane"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td scope="row">2</td>
                                                <td>Produk 2</td>
                                                <td>12 Unit</td>
                                                <td>23-09-2021</td>
                                                <td><a href="{{ url('gbj/so') }}" class="btn btn-outline-primary"><i
                                                            class="fas fa-paper-plane"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td scope="row">1</td>
                                                <td>Produk 1</td>
                                                <td>31 Unit</td>
                                                <td>10-04-2021</td>
                                                <td><a href="{{ url('gbj/so') }}" class="btn btn-outline-primary"><i
                                                            class="fas fa-paper-plane"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td scope="row">2</td>
                                                <td>Produk 2</td>
                                                <td>12 Unit</td>
                                                <td>23-09-2021</td>
                                                <td><a href="{{ url('gbj/so') }}" class="btn btn-outline-primary"><i
                                                            class="fas fa-paper-plane"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td scope="row">1</td>
                                                <td>Produk 1</td>
                                                <td>31 Unit</td>
                                                <td>10-04-2021</td>
                                                <td><a href="{{ url('gbj/so') }}" class="btn btn-outline-primary"><i
                                                            class="fas fa-paper-plane"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td scope="row">2</td>
                                                <td>Produk 2</td>
                                                <td>12 Unit</td>
                                                <td>23-09-2021</td>
                                                <td><a href="{{ url('gbj/so') }}" class="btn btn-outline-primary"><i
                                                            class="fas fa-paper-plane"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td scope="row">1</td>
                                                <td>Produk 1</td>
                                                <td>31 Unit</td>
                                                <td>10-04-2021</td>
                                                <td><a href="{{ url('gbj/so') }}" class="btn btn-outline-primary"><i
                                                            class="fas fa-paper-plane"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td scope="row">2</td>
                                                <td>Produk 2</td>
                                                <td>12 Unit</td>
                                                <td>23-09-2021</td>
                                                <td><a href="{{ url('gbj/so') }}" class="btn btn-outline-primary"><i
                                                            class="fas fa-paper-plane"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td scope="row">1</td>
                                                <td>Produk 1</td>
                                                <td>31 Unit</td>
                                                <td>10-04-2021</td>
                                                <td><a href="{{ url('gbj/so') }}" class="btn btn-outline-primary"><i
                                                            class="fas fa-paper-plane"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td scope="row">2</td>
                                                <td>Produk 2</td>
                                                <td>12 Unit</td>
                                                <td>23-09-2021</td>
                                                <td><a href="{{ url('gbj/so') }}" class="btn btn-outline-primary"><i
                                                            class="fas fa-paper-plane"></i></a></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xxl-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <i class="fas fa-chart-pie mr-1"></i>
                                    Produk yang tidak tersedia sesuai SO
                                </h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped table-jml-stok">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Produk</th>
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
                                            <td>100 Unit</td>
                                        </tr>
                                        <tr>
                                            <td scope="row">1</td>
                                            <td>Produk 1</td>
                                            <td>50 Unit</td>
                                        </tr>
                                        <tr>
                                            <td scope="row">2</td>
                                            <td>Produk 2</td>
                                            <td>100 Unit</td>
                                        </tr>
                                        <tr>
                                            <td scope="row">1</td>
                                            <td>Produk 1</td>
                                            <td>50 Unit</td>
                                        </tr>
                                        <tr>
                                            <td scope="row">1</td>
                                            <td>Produk 1</td>
                                            <td>50 Unit</td>
                                        </tr>
                                        <tr>
                                            <td scope="row">2</td>
                                            <td>Produk 2</td>
                                            <td>100 Unit</td>
                                        </tr>
                                        <tr>
                                            <td scope="row">1</td>
                                            <td>Produk 1</td>
                                            <td>50 Unit</td>
                                        </tr>
                                        <tr>
                                            <td scope="row">2</td>
                                            <td>Produk 2</td>
                                            <td>100 Unit</td>
                                        </tr>
                                        <tr>
                                            <td scope="row">1</td>
                                            <td>Produk 1</td>
                                            <td>50 Unit</td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div id="transferoneday" class="card active otg" style="background-color: #FEF7EA">
                            <div class="card-body text-center">
                                <h4>10</h4>
                                <p class="card-text">Produk dengan jumlah stok 20 sampai 10</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div id="transferoneday" class="card otg" style="background-color: #FFBD67">
                            <div class="card-body text-center">
                                <h4>10</h4>
                                <p class="card-text">Produk dengan jumlah stok 9 sampai 5</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div id="transferoneday" class="card otg" style="background-color: #FF6464">
                            <div class="card-body text-center">
                                <h4>10</h4>
                                <p class="card-text">Produk dengan jumlah stok 4 sampai 1</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div id="transferoneday" class="card otg" style="background-color: #FEF7EA">
                            <div class="card-body text-center">
                                <h4>10</h4>
                                <p class="card-text">Produk masuk 6 bulan sampai 3 bulan</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div id="transferoneday" class="card otg" style="background-color: #FFBD67">
                            <div class="card-body text-center">
                                <h4>10</h4>
                                <p class="card-text">Produk masuk 1 tahun sampai 6 bulan</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div id="transferoneday" class="card otg" style="background-color: #FA8282">
                            <div class="card-body text-center">
                                <h4>10</h4>
                                <p class="card-text">Produk masuk 1 tahun sampai 3 tahun</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div id="transferoneday" class="card otg" style="background-color: #FF6464">
                            <div class="card-body text-center">
                                <h4>10</h4>
                                <p class="card-text">Produk masuk lebih dari 3 tahun</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 col-md-5">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Berdasarkan Jumlah Produk</h3>
                                <div class="card-tools">
                                    <!-- button with a dropdown -->
                                    <div class="btn-group">
                                      <button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" data-offset="-52">
                                        <i class="fas fa-ellipsis-h"></i>
                                      </button>
                                      <div class="dropdown-menu" role="menu">
                                        <a href="#" class="dropdown-item">Layout 1</a>
                                        <a href="#" class="dropdown-item">Layout 2</a>
                                        <a href="#" class="dropdown-item">Layout 2</a>
                                      </div>
                                    </div>
                                  </div>
                            </div>
                            <div class="card-body">
                                <table class="table jml-produk">
                                    <thead class="thead-dark">
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
                                            <td>10 Unit</td>
                                        </tr>
                                        <tr>
                                            <td scope="row">2</td>
                                            <td>Produk 2</td>
                                            <td>20 Unit</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Berdasarkan Waktu Masuk</h3>
                            </div>
                            <div class="card-body">
                                <table class="table waktu-produk">
                                    <thead class="thead-light">
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
                                            <td>10 Unit</td>
                                        </tr>
                                        <tr>
                                            <td scope="row">2</td>
                                            <td>Produk 2</td>
                                            <td>20 Unit</td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">...</div>
    </div>
</div>


@stop

@section('adminlte_js')
<script>
    $('.table-produk-batas-transfer-one-day').DataTable({});
    $('.table-produk-batas-transfer-two-day').DataTable({});
    $('.table-produk-batas-transfer-three-day').DataTable({});
    $('.table-jml-stok').DataTable({});
    $('.jml-produk').DataTable({});
    $('.waktu-produk').DataTable({});

    $(document).ready(function () {
        $(document).on('click', '#transferoneday', function () {
            $('#transferoneday').addClass('active');
            $('.transferonedaytable').removeClass('hidden');
            $('#transfertwoday').removeClass('active');
            $('#transferthreeday').removeClass('active');
            $('.transfertwodaytable').addClass('hidden');
            $('.transferthreedaytable').addClass('hidden');
        })
        $(document).on('click', '#transfertwoday', function () {
            $('#transfertwoday').addClass('active');
            $('.transfertwodaytable').removeClass('hidden');
            $('#transferoneday').removeClass('active');
            $('#transferthreeday').removeClass('active');
            $('.transferonedaytable').addClass('hidden');
            $('.transferthreedaytable').addClass('hidden');
        })
        $(document).on('click', '#transferthreeday', function () {
            $('#transferthreeday').addClass('active');
            $('.transferthreedaytable').removeClass('hidden');
            $('#transfertwoday').removeClass('active');
            $('#transferoneday').removeClass('active');
            $('.transfertwodaytable').addClass('hidden');
            $('.transferonedaytable').addClass('hidden');
            console.log("ok");
        })
    });

</script>
@stop
