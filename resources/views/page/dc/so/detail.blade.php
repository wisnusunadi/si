@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Sales Order</h1>
@stop

@section('adminlte_css')
<style>
    .urgent {
        color: #dc3545;
        font-weight: 600;
    }

    .info {
        color: #FFC700;
        font-weight: 600;
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
        float: right;
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
</style>
@stop

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5>Info</h5>
                        <div class="row">
                            <div class="col-4">
                                <div class="filter">
                                    <div><small class="text-muted">Distributor</small></div>
                                    <div><b>CV. Cipta Jaya Medika</b></div>
                                </div>
                                <div class="filter">
                                    <div><small class="text-muted">Customer</small></div>
                                    <div><b>Pemerintah Daerah Sulawesi Utara</b></div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="filter">
                                    <div><small class="text-muted">No SO</small></div>
                                    <div><b>SOEKAT0920210001</b></div>
                                </div>
                                <div class="filter">
                                    <div><small class="text-muted">No AKN</small></div>
                                    <div><b>AK1-909218-891271</b></div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="filter">
                                    <div><small class="text-muted">Deskripsi</small></div>
                                    <div><b>Paket Alat Kesehatan Modal Pemerintah</b></div>
                                </div>
                                <div class="filter">
                                    <div><small class="text-muted">Status</small></div>
                                    <div><b><span class="badge yellow-text">Sebagian Diproses</span></b></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-8">
            <div class="card">
                <div class="card-body">
                    <h5>Daftar Barang</h5>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" style="text-align: center; width:100%;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>No AKD</th>
                                    <th>Bulan</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>B-ULTRASOUND DIAGNOSTIC SYSTEM CMS-600 PLUS PRINTER TROLLEY UPS</td>
                                    <td>21102900256</td>
                                    <td>X</td>
                                    <td>5</td>
                                    <td><span class="badge green-text">Tersedia</span></td>
                                    <td><a href="{{route('dc.so.produk', ['id' => 1])}}"><i class="fas fa-search"></i></a></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>ELITECH PULSE OXIMETER/OXYMETER FOX-2</td>
                                    <td>20502210102</td>
                                    <td></td>
                                    <td>2</td>
                                    <td><span class="badge red-text">Belum Tersedia</span></td>
                                    <td><a href="{{route('dc.so.produk', ['id' => 1])}}"><i class="fas fa-search"></i></a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-4" id="noseri">
            <div class="card">
                <div class="card-body">
                    <div>
                        <h5 style="display: inline;" class="filter">No Seri</h5>
                        <span class="float-right" class="filter"><button type="button" class="btn btn-sm btn-warning"><i class="fas fa-plus"></i> Tambah COO</button></span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" style="text-align: center; width:100%;" id="noseritable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No COO</th>
                                    <th>No Seri</th>
                                    <th>Laporan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>-</td>
                                    <td>FX012183103841</td>
                                    <td>-</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>-</td>
                                    <td>FX012183103826</td>
                                    <td>-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="createmodal" role="dialog" aria-labelledby="createmodal" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content" style="margin: 10px">
                    <div class="modal-header bg-info">
                        <h4 class="modal-title">Info</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="create">

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('adminlte_js')
<script>
    $(function() {
        $(document).on('click', '.createmodal', function(event) {
            event.preventDefault();
            var href = $(this).attr('data-attr');
            var id = $(this).data('id');
            $.ajax({
                url: "/dc/so/create/" + id,
                beforeSend: function() {
                    $('#loader').show();
                },
                // return the result
                success: function(result) {
                    $('#createmodal').modal("show");
                    $('#create').html(result).show();
                    console.log(id);
                    // $("#editform").attr("action", href);
                },
                complete: function() {
                    $('#loader').hide();
                },
                error: function(jqXHR, testStatus, error) {
                    console.log(error);
                    alert("Page " + href + " cannot open. Error:" + error);
                    $('#loader').hide();
                },
                timeout: 8000
            })
        });
    })
</script>

@endsection