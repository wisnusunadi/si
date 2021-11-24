@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Riwayat Pengujian</h1>
@stop

@section('adminlte_css')
<style>
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

    #detmodal:hover {
        color: #4682B4;
        text-shadow: 2px 2px 4px #4682B4;
    }

    #detmodal:active {
        color: #708090;
    }

    .nowrap-text {
        white-space: nowrap;
    }
</style>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <!-- <div class="row">
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
                                                    <input class="form-check-input" type="checkbox" value="ekatalog" id="defaultCheck1" />
                                                    <label class="form-check-label" for="defaultCheck1">
                                                        E-Catalogue
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="spa" id="defaultCheck2" />
                                                    <label class="form-check-label" for="defaultCheck2">
                                                        SPA
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="spa" id="defaultCheck2" />
                                                    <label class="form-check-label" for="defaultCheck2">
                                                        SPB
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
                        </div> -->

                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table" style="text-align:center;" id="showtable">
                                        <thead>
                                            <tr>
                                                <th rowspan="2" class="nowrap">No</th>
                                                <th rowspan="2" class="nowrap">No SO</th>
                                                <th rowspan="2">Nama Produk</th>
                                                <th rowspan="2" class="nowrap">Tanggal Pengujian</th>
                                                <th rowspan="2" class="nowrap">Tanggal Selesai</th>
                                                <th rowspan="2" class="nowrap">Jumlah</th>
                                                <th colspan="2" class="nowrap">Hasil</th>
                                                <th rowspan="2" class="nowrap">Aksi</th>
                                            </tr>
                                            <tr>
                                                <th><i class="fas fa-check ok"></i></th>
                                                <th><i class="fas fa-times nok"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>SO/EKAT/X/02/98</td>
                                                <td>CMS-600 PLUS + PRINTER + LINEAR PROBE + TROLLEY + UPS</td>
                                                <td>26-10-2021</td>
                                                <td>29-10-2021</td>
                                                <td>2</td>
                                                <td>2</td>
                                                <td>0</td>
                                                <td>
                                                    <a data-toggle="detailmodal" data-target="#detailmodal" class="detailmodal" data-attr="" data-id="1" id="detmodal">
                                                        <div><i class="fas fa-search"></i></div>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>SO/EKAT/X/02/98</td>
                                                <td>ELITECH MINI/MEDICAL COMPRESSOR NEBULIZER PROMIST 2</td>
                                                <td>28-10-2021</td>
                                                <td>30-10-2021</td>
                                                <td>1</td>
                                                <td>1</td>
                                                <td>0</td>
                                                <td>
                                                    <a data-toggle="detailmodal" data-target="#detailmodal" class="detailmodal" data-attr="" data-id="1" id="detmodal">
                                                        <div><i class="fas fa-search"></i></div>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>SO/EKAT/X/02/98</td>
                                                <td>ELITECH ULTRASONIC POCKET DOPPLER</td>
                                                <td>29-10-2021</td>
                                                <td>29-10-2021</td>
                                                <td>1</td>
                                                <td>1</td>
                                                <td>0</td>
                                                <td>
                                                    <a data-toggle="detailmodal" data-target="#detailmodal" class="detailmodal" data-attr="" data-id="1" id="detmodal">
                                                        <div><i class="fas fa-search"></i></div>
                                                    </a>
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
        </div>
    </div>
    <div class="modal fade" id="detailmodal" role="dialog" aria-labelledby="detailmodal" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content" style="margin: 10px">
                <div class="modal-header bg-info">
                    <h4 class="modal-title">Detail</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="detail">

                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('adminlte_js')
<script>
    $(function() {
        var showtable = $('#showtable').DataTable({})
        $(document).on('click', '.detailmodal', function(event) {
            event.preventDefault();
            var href = $(this).attr('data-attr');
            var id = $(this).data('id');
            $.ajax({
                url: "/api/qc/so/riwayat/detail_modal",
                beforeSend: function() {
                    $('#loader').show();
                },
                // return the result
                success: function(result) {
                    $('#detailmodal').modal("show");
                    $('#detail').html(result).show();
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
@stop