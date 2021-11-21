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
    .font-weight{
        font-size: 13px;
    }
    .font-weight-transfer{
        font-size: 15px;
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
                            aria-controls="nav-contact" aria-selected="false">Penerimaan</a>
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
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                        Batas Transfer Produk
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="transferonedaytable">
                                        <table class="table table-produk-batas-transfer-one-day">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nomor SO</th>
                                                    <th>Customer</th>
                                                    <th>Batas Transfer</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td scope="row">1</td>
                                                    <td>68545465465</td>
                                                    <td>100 Unit</td>
                                                    <td>10-04-2021</td>
                                                    <td><a href="{{ url('gbj/so') }}" class="btn btn-outline-primary"><i
                                                                class="fas fa-paper-plane"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">2</td>
                                                    <td>4654564654654</td>
                                                    <td>59 Unit</td>
                                                    <td>23-09-2021</td>
                                                    <td><a href="{{ url('gbj/so') }}" class="btn btn-outline-primary"><i
                                                                class="fas fa-paper-plane"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">1</td>
                                                    <td>5456456155156</td>
                                                    <td>100 Unit</td>
                                                    <td>10-04-2021</td>
                                                    <td><a href="{{ url('gbj/so') }}" class="btn btn-outline-primary"><i
                                                                class="fas fa-paper-plane"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">2</td>
                                                    <td>4654564654654</td>
                                                    <td>59 Unit</td>
                                                    <td>23-09-2021</td>
                                                    <td><a href="{{ url('gbj/so') }}" class="btn btn-outline-primary"><i
                                                                class="fas fa-paper-plane"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">1</td>
                                                    <td>5456456155156</td>
                                                    <td>100 Unit</td>
                                                    <td>10-04-2021</td>
                                                    <td><a href="{{ url('gbj/so') }}" class="btn btn-outline-primary"><i
                                                                class="fas fa-paper-plane"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">2</td>
                                                    <td>4654564654654</td>
                                                    <td>59 Unit</td>
                                                    <td>23-09-2021</td>
                                                    <td><a href="{{ url('gbj/so') }}" class="btn btn-outline-primary"><i
                                                                class="fas fa-paper-plane"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">1</td>
                                                    <td>5456456155156</td>
                                                    <td>100 Unit</td>
                                                    <td>10-04-2021</td>
                                                    <td><a href="{{ url('gbj/so') }}" class="btn btn-outline-primary"><i
                                                                class="fas fa-paper-plane"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">2</td>
                                                    <td>4654564654654</td>
                                                    <td>59 Unit</td>
                                                    <td>23-09-2021</td>
                                                    <td><a href="{{ url('gbj/so') }}" class="btn btn-outline-primary"><i
                                                                class="fas fa-paper-plane"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">1</td>
                                                    <td>5456456155156</td>
                                                    <td>100 Unit</td>
                                                    <td>10-04-2021</td>
                                                    <td><a href="{{ url('gbj/so') }}" class="btn btn-outline-primary"><i
                                                                class="fas fa-paper-plane"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">2</td>
                                                    <td>4654564654654</td>
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
                                                    <td>Customer 1</td>
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
                                                    <td>Customer 1</td>
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
                                                    <td>Customer 1</td>
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
                                                    <td>Customer 1</td>
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
                                                    <td>Customer 1</td>
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
                                                    <td>
                                                        10-04-2021 <br>
                                                        <span class="badge badge-danger">Lewat 5 Hari</span>
                                                    </td>
                                                    <td><a href="{{ url('gbj/so') }}" class="btn btn-outline-primary"><i
                                                                class="fas fa-paper-plane"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">2</td>
                                                    <td>Produk 2</td>
                                                    <td>12 Unit</td>
                                                    <td>23-09-2021 <br>
                                                        <span class="badge badge-danger">Lewat 6 Hari</span>
                                                    </td>
                                                    <td><a href="{{ url('gbj/so') }}" class="btn btn-outline-primary"><i
                                                                class="fas fa-paper-plane"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">1</td>
                                                    <td>Produk 1</td>
                                                    <td>31 Unit</td>
                                                    <td>10-04-2021 <br>
                                                        <span class="badge badge-danger">Lewat 7 Hari</span>
                                                    </td>
                                                    <td><a href="{{ url('gbj/so') }}" class="btn btn-outline-primary"><i
                                                                class="fas fa-paper-plane"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">2</td>
                                                    <td>Produk 2</td>
                                                    <td>12 Unit</td>
                                                    <td>23-09-2021 <br>
                                                        <span class="badge badge-danger">Lewat 8 Hari</span>
                                                    </td>
                                                    <td><a href="{{ url('gbj/so') }}" class="btn btn-outline-primary"><i
                                                                class="fas fa-paper-plane"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">1</td>
                                                    <td>Produk 1</td>
                                                    <td>31 Unit</td>
                                                    <td>10-04-2021 <br>
                                                        <span class="badge badge-danger">Lewat 9 Hari</span>
                                                    </td>
                                                    <td><a href="{{ url('gbj/so') }}" class="btn btn-outline-primary"><i
                                                                class="fas fa-paper-plane"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">2</td>
                                                    <td>Produk 2</td>
                                                    <td>12 Unit</td>
                                                    <td>23-09-2021 <br>
                                                        <span class="badge badge-danger">Lewat 19 Hari</span>
                                                    </td>
                                                    <td><a href="{{ url('gbj/so') }}" class="btn btn-outline-primary"><i
                                                                class="fas fa-paper-plane"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">1</td>
                                                    <td>Produk 1</td>
                                                    <td>31 Unit</td>
                                                    <td>10-04-2021 <br>
                                                        <span class="badge badge-danger">Lewat 11 Hari</span>
                                                    </td>
                                                    <td><a href="{{ url('gbj/so') }}" class="btn btn-outline-primary"><i
                                                                class="fas fa-paper-plane"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">2</td>
                                                    <td>Produk 2</td>
                                                    <td>12 Unit</td>
                                                    <td>23-09-2021 <br>
                                                        <span class="badge badge-danger">Lewat 6 Hari</span>
                                                    </td>
                                                    <td><a href="{{ url('gbj/so') }}" class="btn btn-outline-primary"><i
                                                                class="fas fa-paper-plane"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">1</td>
                                                    <td>Produk 1</td>
                                                    <td>31 Unit</td>
                                                    <td>10-04-2021 <br>
                                                        <span class="badge badge-danger">Lewat 4 Hari</span>
                                                    </td>
                                                    <td><a href="{{ url('gbj/so') }}" class="btn btn-outline-primary"><i
                                                                class="fas fa-paper-plane"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">2</td>
                                                    <td>Produk 2</td>
                                                    <td>12 Unit</td>
                                                    <td>23-09-2021 <br>
                                                    <span class="badge badge-danger">Lewat 16 Hari</span>
                                                    </td>
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
                                                <th>Permintaan</th>
                                                <th>Stok Saat Ini</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td scope="row">1</td>
                                                <td>Produk 1</td>
                                                <td>200 Unit <br>
                                                    <span class="badge badge-danger">Kurang 100 Unit</span>
                                                </td>
                                                <td>100 Unit</td>
                                            </tr>
                                            <tr>
                                                <td scope="row">2</td>
                                                <td>Produk 2</td>
                                                <td>200 Unit <br>
                                                    <span class="badge badge-danger">Kurang 100 Unit</span>
                                                </td>
                                                <td>100 Unit</td>
                                            </tr>
                                            <tr>
                                                <td scope="row">1</td>
                                                <td>Produk 1</td>
                                                <td>200 Unit
                                                    <br>
                                                    <span class="badge badge-danger">Kurang 100 Unit</span>
                                                </td>
                                                <td>100 Unit</td>
                                            </tr>
                                            <tr>
                                                <td scope="row">2</td>
                                                <td>Produk 2</td>
                                                <td>200 Unit
                                                    <br>
                                                    <span class="badge badge-danger">Kurang 100 Unit</span>
                                                </td>
                                                <td>100 Unit</td>
                                            </tr>
                                            <tr>
                                                <td scope="row">1</td>
                                                <td>Produk 1</td>
                                                <td>200 Unit
                                                    <br>
                                                    <span class="badge badge-danger">Kurang 100 Unit</span>
                                                </td>
                                                <td>100 Unit</td>
                                            </tr>
                                            <tr>
                                                <td scope="row">1</td>
                                                <td>Produk 1</td>
                                                <td>200 Unit
                                                    <br>
                                                    <span class="badge badge-danger">Kurang 100 Unit</span>
                                                </td>
                                                <td>100 Unit</td>
                                            </tr>
                                            <tr>
                                                <td scope="row">2</td>
                                                <td>Produk 2</td>
                                                <td>200 Unit
                                                    <br>
                                                    <span class="badge badge-danger">Kurang 100 Unit</span>
                                                </td>
                                                <td>100 Unit</td>
                                            </tr>
                                            <tr>
                                                <td scope="row">1</td>
                                                <td>Produk 1</td>
                                                <td>200 Unit
                                                    <br>
                                                    <span class="badge badge-danger">Kurang 100 Unit</span>
                                                </td>
                                                <td>100 Unit</td>
                                            </tr>
                                            <tr>
                                                <td scope="row">2</td>
                                                <td>Produk 2</td>
                                                <td>200 Unit
                                                    <br>
                                                    <span class="badge badge-danger">Kurang 100 Unit</span>
                                                </td>
                                                <td>100 Unit</td>
                                            </tr>
                                            <tr>
                                                <td scope="row">1</td>
                                                <td>Produk 1</td>
                                                <td>200 Unit
                                                    <br>
                                                    <span class="badge badge-danger">Kurang 100 Unit</span>
                                                </td>
                                                <td>100 Unit</td>
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
                        <div class="col-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <div id="jml-produk-20" class="card active otg" style="background-color: #FEF7EA">
                                                <div class="card-body text-center">
                                                    <h4 id="prd1">10</h4>
                                                    <p class="card-text">Produk dengan jumlah stok 10 sampai 20</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div id="jml-produk-5" class="card otg" style="background-color: #FFBD67">
                                                <div class="card-body text-center">
                                                    <h4 id="prd2">10</h4>
                                                    <p class="card-text">Produk dengan jumlah stok 5 sampai 9</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div id="jml-produk-4" class="card otg" style="background-color: #FF6464">
                                                <div class="card-body text-center">
                                                    <h4 id="prd3">10</h4>
                                                    <p class="card-text">Produk dengan jumlah stok 1 sampai 4</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="jml-produk-20-table">
                                        <table class="table jml-produk-20-tab">
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
                                    <div class="jml-produk-5-table hidden">
                                        <table class="table jml-produk-5-tab">
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
                                                    <td>Produk 3</td>
                                                    <td>10 Unit</td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">2</td>
                                                    <td>Produk 3</td>
                                                    <td>20 Unit</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="jml-produk-4-table hidden">
                                        <table class="table jml-produk-4-tab">
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
                                                    <td>Produk 4</td>
                                                    <td>10 Unit</td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">2</td>
                                                    <td>Produk 5</td>
                                                    <td>20 Unit</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row row-cols-4">
                                        <div class="col">
                                            <div id="produk-masuk-3-bulan" class="card otg active" style="background-color: #FEF7EA">
                                                <div class="card-body text-center">
                                                    <h4 id="prd4">10</h4>
                                                    <p class="card-text font-weight">Produk masuk 3 bulan sampai 6 bulan</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div id="produk-masuk-6-bulan" class="card otg" style="background-color: #FFBD67">
                                                <div class="card-body text-center">
                                                    <h4 id="prd5">10</h4>
                                                    <p class="card-text font-weight">Produk masuk 6 bulan sampai 1 tahun</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div id="produk-masuk-1-tahun" class="card otg" style="background-color: #FA8282">
                                                <div class="card-body text-center">
                                                    <h4 id="prd6">10</h4>
                                                    <p class="card-text font-weight">Produk masuk 1 tahun sampai 3 tahun</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div id="produk-masuk-3-tahun" class="card otg" style="background-color: #FF6464">
                                                <div class="card-body text-center">
                                                    <h4 id="prd7">10</h4>
                                                    <p class="card-text font-weight">Produk masuk lebih dari 3 tahun</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Produk Masuk 3 Bulan --}}
                                    <div class="produk-masuk-3-bulan-table">
                                        <table class="table waktu-produk1" id="tab3bulan">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>No</th>
                                                    <th>Tanggal Masuk</th>
                                                    <th>Nama Produk</th>
                                                    <th>Jumlah</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td scope="row">1</td>
                                                    <td>10-04-2021</td>
                                                    <td>Produk 1</td>
                                                    <td>10 Unit</td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">2</td>
                                                    <td>23-09-2021</td>
                                                    <td>Produk 2</td>
                                                    <td>20 Unit</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    {{-- Produk Masuk 6 Bulan --}}
                                    <div class="produk-masuk-6-bulan-table hidden">
                                    <table class="table waktu-produk2" id="tab6bulan">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal Masuk</th>
                                                <th>Nama Produk</th>
                                                <th>Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td scope="row">1</td>
                                                <td>10-04-2021</td>
                                                <td>Produk 3</td>
                                                <td>10 Unit</td>
                                            </tr>
                                            <tr>
                                                <td scope="row">2</td>
                                                <td>23-09-2021</td>
                                                <td>Produk 3</td>
                                                <td>20 Unit</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    </div>
                                    {{-- Produk Masuk 1 Tahun --}}
                                    <div class="produk-masuk-1-tahun-table hidden">
                                    <table class="table waktu-produk3" id="tab1tahun">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal Masuk</th>
                                                <th>Nama Produk</th>
                                                <th>Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td scope="row">1</td>
                                                <td>10-04-2021</td>
                                                <td>Produk 4</td>
                                                <td>10 Unit</td>
                                            </tr>
                                            <tr>
                                                <td scope="row">2</td>
                                                <td>23-09-2021</td>
                                                <td>Produk 4</td>
                                                <td>20 Unit</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    </div>
                                    {{-- Produk Masuk 3 Tahun --}}
                                    <div class="produk-masuk-3-tahun-table hidden">
                                    <table class="table waktu-produk4" id="tab3tahun">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal Masuk</th>
                                                <th>Nama Produk</th>
                                                <th>Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td scope="row">1</td>
                                                <td>10-04-2021</td>
                                                <td>Produk 5</td>
                                                <td>10 Unit</td>
                                            </tr>
                                            <tr>
                                                <td scope="row">2</td>
                                                <td>23-09-2021</td>
                                                <td>Produk 5</td>
                                                <td>20 Unit</td>
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
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <i class="fas fa-dolly-flatbed"></i> Daftar Stok Layout
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-sm"><h5><b>Layout 1</b></h5></div>
                                        <div class="col-sm text-right">Layout :</div>
                                        <div class="col-sm">
                                            <select class="select2 form-control" multiple="multiple">
                                            <option selected>All Layout</option>
                                            <option>Layout 1</option>
                                            <option>Layout 2</option>
                                            <option>Layout 3</option>
                                            <option>Layout 4</option>
                                            <option>Layout 5</option>
                                            <option>Layout 6</option>
                                            <option>Layout 7</option>
                                          </select>
                                        </div>
                                    </div>
                                    <table class="table tableStokLayout">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Produk</th>
                                                <th>Jumlah</th>
                                                <th>Layout</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td scope="row">1</td>
                                                <td>Produk 1</td>
                                                <td>100 Unit</td>
                                                <td>Layout 1</td>
                                            </tr>
                                            <tr>
                                                <td scope="row">2</td>
                                                <td>Produk 2</td>
                                                <td>100 Unit</td>
                                                <td>Layout 2</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-6"></div>
                    </div>
                </div>
            </section>
        </div>
        <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-6 col-xxl-4">
                            <div class="row">
                                <div class="col-6 col-md-4">
                                    <div id="receiptoneday" class="card active otg" style="background-color: #FEF7EA">
                                        <div class="card-body text-center">
                                            <h4 id="r1">10</h4>
                                            <p class="card-text font-weight-transfer">Produk Melewati Batas Penerimaan Lebih Dari 1 Hari</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-4">
                                    <div id="receipttwoday" class="card otg" style="background-color: #FFBD67">
                                        <div class="card-body text-center">
                                            <h4 id="r2">50</h4>
                                            <p class="card-text font-weight-transfer">Produk Melewati Batas Penerimaan Lebih Dari 2 Hari</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-4">
                                    <div id="receiptthreeday" class="card otg" style="background-color: #FF6464">
                                        <div class="card-body text-center">
                                            <h4 id="r3">60</h4>
                                            <p class="card-text font-weight-transfer">Produk Melewati Batas Penerimaan Lebih Dari 3 Hari</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                        <h3 class="card-title">
                                            <i class="fas fa-calendar-alt mr-1"></i>
                                            Batas Penerimaan Produk
                                        </h3>
                                </div>
                                <div class="card-body">
                                    <div class="receiptonedaytable">
                                        <table class="table table-produk-batas-receipt-one-day">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Produk</th>
                                                    <th>Jumlah</th>
                                                    <th>Tanggal Masuk</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td scope="row">1</td>
                                                    <td>Produk 1</td>
                                                    <td>100 Unit</td>
                                                    <td>10-04-2021</td>
                                                    <td><a href="{{ url('gbj/dp') }}" class="btn btn-outline-primary"><i
                                                                class="fas fa-paper-plane"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">2</td>
                                                    <td>Produk 2</td>
                                                    <td>59 Unit</td>
                                                    <td>23-09-2021</td>
                                                    <td><a href="{{ url('gbj/dp') }}" class="btn btn-outline-primary"><i
                                                                class="fas fa-paper-plane"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">1</td>
                                                    <td>Produk 1</td>
                                                    <td>100 Unit</td>
                                                    <td>10-04-2021</td>
                                                    <td><a href="{{ url('gbj/dp') }}" class="btn btn-outline-primary"><i
                                                                class="fas fa-paper-plane"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">2</td>
                                                    <td>Produk 2</td>
                                                    <td>59 Unit</td>
                                                    <td>23-09-2021</td>
                                                    <td><a href="{{ url('gbj/dp') }}" class="btn btn-outline-primary"><i
                                                                class="fas fa-paper-plane"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">1</td>
                                                    <td>Produk 1</td>
                                                    <td>100 Unit</td>
                                                    <td>10-04-2021</td>
                                                    <td><a href="{{ url('gbj/dp') }}" class="btn btn-outline-primary"><i
                                                                class="fas fa-paper-plane"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">2</td>
                                                    <td>Produk 2</td>
                                                    <td>59 Unit</td>
                                                    <td>23-09-2021</td>
                                                    <td><a href="{{ url('gbj/dp') }}" class="btn btn-outline-primary"><i
                                                                class="fas fa-paper-plane"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">1</td>
                                                    <td>Produk 1</td>
                                                    <td>100 Unit</td>
                                                    <td>10-04-2021</td>
                                                    <td><a href="{{ url('gbj/dp') }}" class="btn btn-outline-primary"><i
                                                                class="fas fa-paper-plane"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">2</td>
                                                    <td>Produk 2</td>
                                                    <td>59 Unit</td>
                                                    <td>23-09-2021</td>
                                                    <td><a href="{{ url('gbj/dp') }}" class="btn btn-outline-primary"><i
                                                                class="fas fa-paper-plane"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">1</td>
                                                    <td>Produk 1</td>
                                                    <td>100 Unit</td>
                                                    <td>10-04-2021</td>
                                                    <td><a href="{{ url('gbj/dp') }}" class="btn btn-outline-primary"><i
                                                                class="fas fa-paper-plane"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">2</td>
                                                    <td>Produk 2</td>
                                                    <td>59 Unit</td>
                                                    <td>23-09-2021</td>
                                                    <td><a href="{{ url('gbj/dp') }}" class="btn btn-outline-primary"><i
                                                                class="fas fa-paper-plane"></i></a></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="receipttwodaytable hidden">
                                        <table class="table table-produk-batas-receipt-two-day">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Produk</th>
                                                    <th>Jumlah</th>
                                                    <th>Tanggal Masuk</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td scope="row">1</td>
                                                    <td>Produk 1</td>
                                                    <td>50 Unit</td>
                                                    <td>10-04-2021</td>
                                                    <td><a href="{{ url('gbj/dp') }}" class="btn btn-outline-primary"><i
                                                                class="fas fa-paper-plane"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">2</td>
                                                    <td>Produk 2</td>
                                                    <td>100 Unit</td>
                                                    <td>23-09-2021</td>
                                                    <td><a href="{{ url('gbj/dp') }}" class="btn btn-outline-primary"><i
                                                                class="fas fa-paper-plane"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">1</td>
                                                    <td>Produk 1</td>
                                                    <td>50 Unit</td>
                                                    <td>10-04-2021</td>
                                                    <td><a href="{{ url('gbj/dp') }}" class="btn btn-outline-primary"><i
                                                                class="fas fa-paper-plane"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">2</td>
                                                    <td>Produk 2</td>
                                                    <td>100 Unit</td>
                                                    <td>23-09-2021</td>
                                                    <td><a href="{{ url('gbj/dp') }}" class="btn btn-outline-primary"><i
                                                                class="fas fa-paper-plane"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">1</td>
                                                    <td>Produk 1</td>
                                                    <td>50 Unit</td>
                                                    <td>10-04-2021</td>
                                                    <td><a href="{{ url('gbj/dp') }}" class="btn btn-outline-primary"><i
                                                                class="fas fa-paper-plane"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">2</td>
                                                    <td>Produk 2</td>
                                                    <td>100 Unit</td>
                                                    <td>23-09-2021</td>
                                                    <td><a href="{{ url('gbj/dp') }}" class="btn btn-outline-primary"><i
                                                                class="fas fa-paper-plane"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">1</td>
                                                    <td>Produk 1</td>
                                                    <td>50 Unit</td>
                                                    <td>10-04-2021</td>
                                                    <td><a href="{{ url('gbj/dp') }}" class="btn btn-outline-primary"><i
                                                                class="fas fa-paper-plane"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">2</td>
                                                    <td>Produk 2</td>
                                                    <td>100 Unit</td>
                                                    <td>23-09-2021</td>
                                                    <td><a href="{{ url('gbj/dp') }}" class="btn btn-outline-primary"><i
                                                                class="fas fa-paper-plane"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">1</td>
                                                    <td>Produk 1</td>
                                                    <td>50 Unit</td>
                                                    <td>10-04-2021</td>
                                                    <td><a href="{{ url('gbj/dp') }}" class="btn btn-outline-primary"><i
                                                                class="fas fa-paper-plane"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">2</td>
                                                    <td>Produk 2</td>
                                                    <td>100 Unit</td>
                                                    <td>23-09-2021</td>
                                                    <td><a href="{{ url('gbj/dp') }}" class="btn btn-outline-primary"><i
                                                                class="fas fa-paper-plane"></i></a></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="receiptthreedaytable hidden">
                                        <table class="table table-produk-batas-receipt-three-day">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Produk</th>
                                                    <th>Jumlah</th>
                                                    <th>Tanggal Masuk</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td scope="row">1</td>
                                                    <td>Produk 1</td>
                                                    <td>31 Unit</td>
                                                    <td>10-04-2021
                                                        <span class="badge badge-danger">Lewat 15 Hari</span>
                                                    </td>
                                                    <td><a href="{{ url('gbj/dp') }}" class="btn btn-outline-primary"><i
                                                                class="fas fa-paper-plane"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">2</td>
                                                    <td>Produk 2</td>
                                                    <td>12 Unit</td>
                                                    <td>23-09-2021
                                                        <span class="badge badge-danger">Lewat 14 Hari</span>
                                                    </td>
                                                    <td><a href="{{ url('gbj/dp') }}" class="btn btn-outline-primary"><i
                                                                class="fas fa-paper-plane"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">1</td>
                                                    <td>Produk 1</td>
                                                    <td>31 Unit</td>
                                                    <td>10-04-2021
                                                        <span class="badge badge-danger">Lewat 12 Hari</span>
                                                    </td>
                                                    <td><a href="{{ url('gbj/dp') }}" class="btn btn-outline-primary"><i
                                                                class="fas fa-paper-plane"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">2</td>
                                                    <td>Produk 2</td>
                                                    <td>12 Unit</td>
                                                    <td>23-09-2021
                                                        <span class="badge badge-danger">Lewat 10 Hari</span>
                                                    </td>
                                                    <td><a href="{{ url('gbj/dp') }}" class="btn btn-outline-primary"><i
                                                                class="fas fa-paper-plane"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">1</td>
                                                    <td>Produk 1</td>
                                                    <td>31 Unit</td>
                                                    <td>10-04-2021
                                                        <span class="badge badge-danger">Lewat 4 Hari</span>
                                                    </td>
                                                    <td><a href="{{ url('gbj/dp') }}" class="btn btn-outline-primary"><i
                                                                class="fas fa-paper-plane"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">2</td>
                                                    <td>Produk 2</td>
                                                    <td>12 Unit</td>
                                                    <td>23-09-2021
                                                        <span class="badge badge-danger">Lewat 8 Hari</span>
                                                    </td>
                                                    <td><a href="{{ url('gbj/dp') }}" class="btn btn-outline-primary"><i
                                                                class="fas fa-paper-plane"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">1</td>
                                                    <td>Produk 1</td>
                                                    <td>31 Unit</td>
                                                    <td>10-04-2021
                                                        <span class="badge badge-danger">Lewat 8 Hari</span>
                                                    </td>
                                                    <td><a href="{{ url('gbj/dp') }}" class="btn btn-outline-primary"><i
                                                                class="fas fa-paper-plane"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">2</td>
                                                    <td>Produk 2</td>
                                                    <td>12 Unit</td>
                                                    <td>23-09-2021
                                                        <span class="badge badge-danger">Lewat 7 Hari</span>
                                                    </td>
                                                    <td><a href="{{ url('gbj/dp') }}" class="btn btn-outline-primary"><i
                                                                class="fas fa-paper-plane"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">1</td>
                                                    <td>Produk 1</td>
                                                    <td>31 Unit</td>
                                                    <td>10-04-2021
                                                        <span class="badge badge-danger">Lewat 6 Hari</span>
                                                    </td>
                                                    <td><a href="{{ url('gbj/dp') }}" class="btn btn-outline-primary"><i
                                                                class="fas fa-paper-plane"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">2</td>
                                                    <td>Produk 2</td>
                                                    <td>12 Unit</td>
                                                    <td>23-09-2021
                                                        <span class="badge badge-danger">Lewat 5 Hari</span>
                                                    </td>
                                                    <td><a href="{{ url('gbj/dp') }}" class="btn btn-outline-primary"><i
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
                                        Daftar Permintaan Transfer Produk
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-produk-batas-receipt-all">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Produk</th>
                                                <th>Jumlah</th>
                                                <th>Tanggal Masuk</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td scope="row">1</td>
                                                <td>Produk 1</td>
                                                <td>31 Unit</td>
                                                <td>10-04-2021 <br>
                                                    <span class="badge badge-info">Lewat 1 Hari</span>
                                                </td>
                                                <td><a href="{{ url('gbj/dp') }}" class="btn btn-outline-primary"><i
                                                            class="fas fa-paper-plane"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td scope="row">2</td>
                                                <td>Produk 2</td>
                                                <td>12 Unit</td>
                                                <td>23-09-2021 <br>
                                                    <span class="badge badge-warning">Lewat 2 Hari</span>
                                                </td>
                                                <td><a href="{{ url('gbj/dp') }}" class="btn btn-outline-primary"><i
                                                            class="fas fa-paper-plane"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td scope="row">1</td>
                                                <td>Produk 1</td>
                                                <td>31 Unit</td>
                                                <td>10-04-2021 <br>
                                                    <span class="badge badge-danger">Lewat 12 Hari</span>
                                                </td>
                                                <td><a href="{{ url('gbj/dp') }}" class="btn btn-outline-primary"><i
                                                            class="fas fa-paper-plane"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td scope="row">2</td>
                                                <td>Produk 2</td>
                                                <td>12 Unit</td>
                                                <td>23-09-2021 <br>
                                                    <span class="badge badge-danger">Lewat 10 Hari</span>
                                                </td>
                                                <td><a href="{{ url('gbj/dp') }}" class="btn btn-outline-primary"><i
                                                            class="fas fa-paper-plane"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td scope="row">1</td>
                                                <td>Produk 1</td>
                                                <td>31 Unit</td>
                                                <td>10-04-2021 <br>
                                                    <span class="badge badge-danger">Lewat 4 Hari</span>
                                                </td>
                                                <td><a href="{{ url('gbj/dp') }}" class="btn btn-outline-primary"><i
                                                            class="fas fa-paper-plane"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td scope="row">2</td>
                                                <td>Produk 2</td>
                                                <td>12 Unit</td>
                                                <td>23-09-2021 <br>
                                                    <span class="badge badge-danger">Lewat 8 Hari</span>
                                                </td>
                                                <td><a href="{{ url('gbj/dp') }}" class="btn btn-outline-primary"><i
                                                            class="fas fa-paper-plane"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td scope="row">1</td>
                                                <td>Produk 1</td>
                                                <td>31 Unit</td>
                                                <td>10-04-2021 <br>
                                                    <span class="badge badge-danger">Lewat 8 Hari</span>
                                                </td>
                                                <td><a href="{{ url('gbj/dp') }}" class="btn btn-outline-primary"><i
                                                            class="fas fa-paper-plane"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td scope="row">2</td>
                                                <td>Produk 2</td>
                                                <td>12 Unit</td>
                                                <td>23-09-2021 <br>
                                                    <span class="badge badge-danger">Lewat 7 Hari</span>
                                                </td>
                                                <td><a href="{{ url('gbj/dp') }}" class="btn btn-outline-primary"><i
                                                            class="fas fa-paper-plane"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td scope="row">1</td>
                                                <td>Produk 1</td>
                                                <td>31 Unit</td>
                                                <td>10-04-2021 <br>
                                                    <span class="badge badge-danger">Lewat 6 Hari</span>
                                                </td>
                                                <td><a href="{{ url('gbj/dp') }}" class="btn btn-outline-primary"><i
                                                            class="fas fa-paper-plane"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td scope="row">2</td>
                                                <td>Produk 2</td>
                                                <td>12 Unit</td>
                                                <td>23-09-2021 <br>
                                                    <span class="badge badge-danger">Lewat 5 Hari</span>
                                                </td>
                                                <td><a href="{{ url('gbj/dp') }}" class="btn btn-outline-primary"><i
                                                            class="fas fa-paper-plane"></i></a></td>
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
    // $('.waktu-produk').DataTable({});
    $('.table-produk-batas-receipt-all').DataTable({});
    $('.tableStokLayout').DataTable({
        searching: false,
        "lengthChange": false
    });

    $(document).ready(function () {
        // header
        $.ajax({
            url: "/api/dashboard-gbj/stok/1020/h",
            success: function(res) {
                console.log(res);
                $('h4#prd1').text(res);
            }
        })

        $.ajax({
            url: "/api/dashboard-gbj/stok/59/h",
            success: function(res) {
                console.log(res);
                $('h4#prd2').text(res);
            }
        })

        $.ajax({
            url: "/api/dashboard-gbj/stok/14/h",
            success: function(res) {
                console.log(res);
                $('h4#prd3').text(res);
            }
        })

        $.ajax({
            url: "/api/dashboard-gbj/in/36/h",
            type: "post",
            success: function(res) {
                console.log(res);
                $('h4#prd4').text(res);
            }
        })

        $.ajax({
            url: "/api/dashboard-gbj/in/612/h",
            type: "post",
            success: function(res) {
                console.log(res);
                $('h4#prd5').text(res);
            }
        })

        $.ajax({
            url: "/api/dashboard-gbj/in/1236/h",
            type: "post",
            success: function(res) {
                console.log(res);
                $('h4#prd6').text(res);
            }
        })

        $.ajax({
            url: "/api/dashboard-gbj/in/36plus/h",
            type: "post",
            success: function(res) {
                console.log(res);
                $('h4#prd7').text(res);
            }
        })

        $.ajax({
            url: "/api/dashboard-gbj/terimaproduk1/h",
            type: "post",
            success: function(res) {
                console.log(res);
                $('h4#r1').text(res);
            }
        })

        $.ajax({
            url: "/api/dashboard-gbj/terimaproduk2/h",
            type: "post",
            success: function(res) {
                console.log(res);
                $('h4#r2').text(res);
            }
        })

        $.ajax({
            url: "/api/dashboard-gbj/terimaproduk2/h",
            type: "post",
            success: function(res) {
                console.log(res);
                $('h4#r3').text(res);
            }
        })

        // data
        $('.jml-produk-20-tab').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/api/dashboard-gbj/stok/1020',
            },
            columns: [
                {data: 'DT_RowIndex'},
                {data: 'prd'},
                {data: 'jml'},
            ]
        });
        $('.jml-produk-5-tab').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/api/dashboard-gbj/stok/59',
            },
            columns: [
                {data: 'DT_RowIndex'},
                {data: 'prd'},
                {data: 'jml'},
            ]
        });
        $('.jml-produk-4-tab').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/api/dashboard-gbj/stok/14',
            },
            columns: [
                {data: 'DT_RowIndex'},
                {data: 'prd'},
                {data: 'jml'},
            ]
        });
        $('.table-produk-batas-receipt-one-day').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/api/dashboard-gbj/terimaproduk1',
                type: "post",
            },
            columns: [
                {data: 'DT_RowIndex'},
                {data: 'product'},
                {data: 'jumlah'},
                {data: 'tgl_masuk'},
                {data: 'action'},
            ]
        });
        $('.table-produk-batas-receipt-two-day').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/api/dashboard-gbj/terimaproduk2',
                type: "post",
            },
            columns: [
                {data: 'DT_RowIndex'},
                {data: 'product'},
                {data: 'jumlah'},
                {data: 'tgl_masuk'},
                {data: 'action'},
            ]
        });
        $('.table-produk-batas-receipt-three-day').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/api/dashboard-gbj/terimaproduk3',
                type: "post",
            },
            columns: [
                {data: 'DT_RowIndex'},
                {data: 'product'},
                {data: 'jumlah'},
                {data: 'tgl_masuk'},
                {data: 'action'},
            ]
        });
        $('.waktu-produk1').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/api/dashboard-gbj/in/36',
                type: "post",
            },
            columns: [
                {data: 'DT_RowIndex'},
                {data: 'product'},
                {data: 'jumlah'},
                {data: 'tgl_masuk'},
                // {data: 'action'},
            ]
        });
        $('.waktu-produk2').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/api/dashboard-gbj/in/612',
                type: "post",
            },
            columns: [
                {data: 'DT_RowIndex'},
                {data: 'product'},
                {data: 'jumlah'},
                {data: 'tgl_masuk'},
                // {data: 'action'},
            ]
        });
        $('.waktu-produk3').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/api/dashboard-gbj/in/1236',
                type: "post",
            },
            columns: [
                {data: 'DT_RowIndex'},
                {data: 'product'},
                {data: 'jumlah'},
                {data: 'tgl_masuk'},
                // {data: 'action'},
            ]
        });
        $('.waktu-produk4').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/api/dashboard-gbj/in/36plus',
                type: "post",
            },
            columns: [
                {data: 'DT_RowIndex'},
                {data: 'product'},
                {data: 'jumlah'},
                {data: 'tgl_masuk'},
                // {data: 'action'},
            ]
        });

        // Penjualan
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
        })
        // Produk Stok
        $(document).on('click', '#jml-produk-20', function () {
            $('#jml-produk-20').addClass('active');
            $('.jml-produk-20-table').removeClass('hidden');
            $('#jml-produk-5').removeClass('active');
            $('#jml-produk-4').removeClass('active');
            $('.jml-produk-5-table').addClass('hidden');
            $('.jml-produk-4-table').addClass('hidden');
        })
        $(document).on('click', '#jml-produk-5', function () {
            $('#jml-produk-5').addClass('active');
            $('.jml-produk-5-table').removeClass('hidden');
            $('#jml-produk-20').removeClass('active');
            $('#jml-produk-4').removeClass('active');
            $('.jml-produk-20-table').addClass('hidden');
            $('.jml-produk-4-table').addClass('hidden');
        })
        $(document).on('click', '#jml-produk-4', function () {
            $('#jml-produk-4').addClass('active');
            $('.jml-produk-4-table').removeClass('hidden');
            $('#jml-produk-5').removeClass('active');
            $('#jml-produk-20').removeClass('active');
            $('.jml-produk-5-table').addClass('hidden');
            $('.jml-produk-20-table').addClass('hidden');
        })
        // Produk Masuk
        $(document).on('click', '#produk-masuk-3-bulan', function () {
            $('#produk-masuk-3-bulan').addClass('active');
            $('.produk-masuk-3-bulan-table').removeClass('hidden');
            $('#produk-masuk-6-bulan').removeClass('active');
            $('#produk-masuk-1-tahun').removeClass('active');
            $('#produk-masuk-3-tahun').removeClass('active');
            $('.produk-masuk-6-bulan-table').addClass('hidden');
            $('.produk-masuk-1-tahun-table').addClass('hidden');
            $('.produk-masuk-3-tahun-table').addClass('hidden');
        })
        $(document).on('click', '#produk-masuk-6-bulan', function () {
            $('#produk-masuk-6-bulan').addClass('active');
            $('.produk-masuk-6-bulan-table').removeClass('hidden');
            $('#produk-masuk-3-bulan').removeClass('active');
            $('#produk-masuk-1-tahun').removeClass('active');
            $('#produk-masuk-3-tahun').removeClass('active');
            $('.produk-masuk-3-bulan-table').addClass('hidden');
            $('.produk-masuk-1-tahun-table').addClass('hidden');
            $('.produk-masuk-3-tahun-table').addClass('hidden');
        })
        $(document).on('click', '#produk-masuk-1-tahun', function () {
            $('#produk-masuk-1-tahun').addClass('active');
            $('.produk-masuk-1-tahun-table').removeClass('hidden');
            $('#produk-masuk-6-bulan').removeClass('active');
            $('#produk-masuk-3-bulan').removeClass('active');
            $('#produk-masuk-3-tahun').removeClass('active');
            $('.produk-masuk-6-bulan-table').addClass('hidden');
            $('.produk-masuk-3-bulan-table').addClass('hidden');
            $('.produk-masuk-3-tahun-table').addClass('hidden');
        })
        $(document).on('click', '#produk-masuk-3-tahun', function () {
            $('.produk-masuk-3-tahun-table').removeClass('hidden');
            $('#produk-masuk-3-tahun').addClass('active');
            $('#produk-masuk-1-tahun').removeClass('active');
            $('.produk-masuk-1-tahun-table').addClass('hidden');
            $('#produk-masuk-6-bulan').removeClass('active');
            $('#produk-masuk-3-bulan').removeClass('active');
            $('.produk-masuk-6-bulan-table').addClass('hidden');
            $('.produk-masuk-3-bulan-table').addClass('hidden');
        })
        // Transfer
        $(document).on('click', '#receiptoneday', function () {
            $('#receiptoneday').addClass('active');
            $('.receiptonedaytable').removeClass('hidden');
            $('#receipttwoday').removeClass('active');
            $('#receiptthreeday').removeClass('active');
            $('.receipttwodaytable').addClass('hidden');
            $('.receiptthreedaytable').addClass('hidden');
        })
        $(document).on('click', '#receipttwoday', function () {
            $('#receipttwoday').addClass('active');
            $('.receipttwodaytable').removeClass('hidden');
            $('#receiptoneday').removeClass('active');
            $('#receiptthreeday').removeClass('active');
            $('.receiptonedaytable').addClass('hidden');
            $('.receiptthreedaytable').addClass('hidden');
        })
        $(document).on('click', '#receiptthreeday', function () {
            $('#receiptthreeday').addClass('active');
            $('.receiptthreedaytable').removeClass('hidden');
            $('#receipttwoday').removeClass('active');
            $('#receiptoneday').removeClass('active');
            $('.receipttwodaytable').addClass('hidden');
            $('.receiptonedaytable').addClass('hidden');
        });
        $('.select2').select2({});

    });

        function filterFunction() {
        var input, filter, ul, li, a, i;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        div = document.getElementById("myDropdown");
        a = div.getElementsByTagName("a");
        for (i = 0; i < a.length; i++) {
            txtValue = a[i].textContent || a[i].innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
            a[i].style.display = "";
            } else {
            a[i].style.display = "none";
            }
        }
        }
</script>
@stop
