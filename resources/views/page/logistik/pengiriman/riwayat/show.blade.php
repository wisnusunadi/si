@extends('adminlte.page')

@section('title', 'ERP')

@section('adminlte_css')
<style>
    li.list-group-item {
        border: 0 none;
    }

    .smtxt {
        font-size: 13px;
    }

    #showtable {
        text-align: center;
        white-space: nowrap;
    }

    .ok {
        color: green;
        font-weight: 600;
    }

    .nok {
        color: #dc3545;
        font-weight: 600;
    }

    .warning {
        color: #FFC700;
        font-weight: 600;
    }

    .list-group-item {
        border: 0 none;
    }

    .align-right {
        text-align: right;
    }

    .align-center {
        text-align: center;
    }

    .margin {
        margin-bottom: 5px;
    }

    .filter {
        margin: 5px;
    }

    .hide {
        display: none !important;
    }

    .bgcolor {
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    }

    .fa-search:hover {
        color: #ADD8E6;
    }

    .fa-search:active {
        color: #808080;
    }

    .nowrap-text {
        white-space: nowrap;
    }

    .minimizechar {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 25ch;
    }

    .dropdown-toggle:hover {
        color: #4682B4;
    }

    .dropdown-toggle:active {
        color: #C0C0C0;
    }

    @media screen and (min-width: 1440px) {
        section {
            font-size: 14px;
        }

        .dropdown-item {
            font-size: 14px;
        }
    }

    @media screen and (max-width: 1439px) {
        section {
            font-size: 12px;
        }

        .dropdown-item {
            font-size: 12px;
        }
    }
</style>
@stop

@section('content_header')
<h1 class="m-0 text-dark">Riwayat Pengiriman</h1>
@stop

@section('content')
<section class="section">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <span class="float-right filter">
                                    <button class="btn btn-outline-secondary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                    <div class="dropdown-menu">
                                        <div class="px-3 py-3">
                                            <div class="form-group">
                                                <label for="jenis_penjualan">Status</label>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="selesai" id="status1" name="status" />
                                                    <label class="form-check-label" for="status1">
                                                        Sudah Dikirim
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="sebagian" id="status2" name="status" />
                                                    <label class="form-check-label" for="status2">
                                                        Sebagian Dikirim
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="belum" id="status3" name="status" />
                                                    <label class="form-check-label" for="status3">
                                                        Belum Dikirim
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <span class="float-right">
                                                    <button class="btn btn-primary">
                                                        Cari
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </span>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table" id="showtable" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>No SO</th>
                                                <th>No SJ</th>
                                                <th>Ekspedisi</th>
                                                <th>No Resi</th>
                                                <th>Tanggal Kirim</th>
                                                <th>Tanggal Sampai</th>
                                                <th>Nama Customer</th>
                                                <th>Provinsi</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>SO-SPA10210001</td>
                                                <td>SJ/10/20/2001</td>
                                                <td class="minimizechar">J&T</td>
                                                <td>JT793719379</td>
                                                <td>09-10-2021</td>
                                                <td>11-10-2021</td>
                                                <td class="minimizechar">RS Nurul Ikhsan</td>
                                                <td class="minimizechar">Jawa Barat</td>
                                                <td><span class="badge green-text">Selesai</span></td>
                                                <td>
                                                    <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <a href="{{route('logistik.pengiriman.detail', ['id' => '1'])}}">
                                                            <button class="dropdown-item" type="button">
                                                                <i class="fas fa-search"></i>
                                                                Detail
                                                            </button>
                                                        </a>
                                                        <a href="{{route('logistik.pengiriman.print')}}">
                                                            <button class="dropdown-item" type="button">
                                                                <i class="fas fa-file"></i>
                                                                Laporan PDF
                                                            </button>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>SO-EKAT08210005</td>
                                                <td>SJ/08/21/0986</td>
                                                <td class="minimizechar">Safari Dharma Raya</td>
                                                <td>JT793719379</td>
                                                <td>02-08-2021</td>
                                                <td>09-08-2021</td>
                                                <td class="minimizechar">Bapak Hutapea</td>
                                                <td class="minimizechar">Sumatera Utara</td>
                                                <td><span class="badge green-text">Selesai</span>
                                                </td>
                                                <td>
                                                    <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <a href="{{route('logistik.pengiriman.detail', ['id' => '1'])}}">
                                                            <button class="dropdown-item" type="button">
                                                                <i class="fas fa-search"></i>
                                                                Detail
                                                            </button>
                                                        </a>
                                                        <a href="{{route('logistik.pengiriman.print')}}">
                                                            <button class="dropdown-item" type="button">
                                                                <i class="fas fa-file"></i>
                                                                Laporan PDF
                                                            </button>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>SO-SPB08210005</td>
                                                <td>SJ/01/20/1927</td>
                                                <td class="minimizechar">Si Cepat</td>
                                                <td>JT793719379</td>
                                                <td>02-08-2021</td>
                                                <td>12-08-2021</td>
                                                <td class="minimizechar">Pemerintah Kota Kupang</td>
                                                <td class="minimizechar">Nusa Tenggara Timur</td>
                                                <td><span class="badge green-text">Selesai</span>
                                                </td>
                                                <td>
                                                    <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <a href="{{route('logistik.pengiriman.detail', ['id' => '1'])}}">
                                                            <button class="dropdown-item" type="button">
                                                                <i class="fas fa-search"></i>
                                                                Detail
                                                            </button>
                                                        </a>
                                                        <a href="{{route('logistik.pengiriman.print')}}">
                                                            <button class="dropdown-item" type="button">
                                                                <i class="fas fa-file"></i>
                                                                Laporan PDF
                                                            </button>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="editmodal" role="dialog" aria-labelledby="editmodal" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content" style="margin: 10px">
                        <div class="modal-header bg-warning">
                            <h4 class="modal-title">Edit</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" id="edit">

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
    $(function() {
        $('#showtable').DataTable({});

    })
</script>
@endsection