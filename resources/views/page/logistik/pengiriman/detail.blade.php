@extends('adminlte.page')

@section('title', 'ERP')


@section('adminlte_css')
<style>
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
        max-width: 30ch;
    }

    .wb {
        word-break: break-all;
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
<h1 class="m-0 text-dark">Surat Jalan</h1>
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
                            <div class="col-5">
                                <div class="margin">
                                    <div><small class="text-muted">Subjek Pengiriman</small></div>
                                </div>
                                <div class="margin">
                                    <b id="customer">CIPTAJAYA RETAIL INDONESIA PT </b>
                                </div>
                                <div class="margin">
                                    <b id="alamat">Komplek Pergudangan Citra Raya Graha Blok E no 19 - 20 Bandung</b>
                                </div>
                                <div class="margin">
                                    <b id="provinsi">Jawa Barat</b>
                                </div>
                                <div class="margin">
                                    <b id="telepon">0819090567256</b>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="margin">
                                    <div><small class="text-muted">Ekspedisi</small></div>
                                    <div><b id="ekspedisi">MULIA BAKTI EXPRESS (BAYAR TUJUAN)</b></div>
                                </div>
                                <div class="margin">
                                    <div><small class="text-muted">No Surat Jalan</small></div>
                                    <div><b id="no_sj">SJ/09/09/21/5001</b></div>
                                </div>
                                <div class="margin">
                                    <div><small class="text-muted">Tanggal Kirim</small></div>
                                    <div><b id="no_sj">20-10-2021</b></div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="margin">
                                    <div><small class="text-muted">No Sales Order</small></div>
                                    <div><b id="no_so">SO/09/21/00001</b></div>
                                </div>
                                <div class="margin">
                                    <div><small class="text-muted">No PO</small></div>
                                    <div><b id="no_so">PO/ON/09/21/00001</b></div>
                                </div>
                                <div class="margin">
                                    <div><small class="text-muted">Tanggal PO</small></div>
                                    <div><b id="no_so">29-09-2021</b></div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="margin">
                                    <div><small class="text-muted">No Invoice</small></div>
                                    <div><b id="no_resi">9831937492</b></div>
                                </div>
                                <div class="margin">
                                    <div><small class="text-muted">Status</small></div>
                                    <div><span class="badge blue-text">Dalam Pengiriman</span></div>
                                </div>
                                <div class="margin">
                                    <div><small class="text-muted">Resi</small></div>
                                    <div><b id="no_resi">JP3183810381</b></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-center" id="showtable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Produk</th>
                                        <th>Jumlah</th>
                                        <th>No Seri</th>
                                        <th>Keterangan</th>
                                        @if(Auth::user()->divisi->id == "15")
                                        <th>Aksi</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Elitech MTB 2 MTR</td>
                                        <td>10</td>
                                        <td class="minimizechar wb">MTB0129021803, MTB0129021801, MTB0129021802, MTB0129021804, MTB0129021805, MTB0129021807, MTB0129021811, MTB0129021816, MTB0129021823, MTB0129021817</td>
                                        <td>-</td>
                                        @if(Auth::user()->divisi->id == "15")
                                        <td><a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr="{{route('logistik.pengiriman.noseri', ['id' => '1'])}}" data-id="1">
                                                <div><i class="fas fa-eye"></i></div>
                                                <div><small>No Seri</small></div>
                                            </a></td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Elitech Pocket Fetal Doppler</td>
                                        <td>1</td>
                                        <td class="minimizechar wb">PFD0129021803</td>
                                        <td>-</td>
                                        @if(Auth::user()->divisi->id == "15")
                                        <td><a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr="{{route('logistik.pengiriman.noseri', ['id' => '1'])}}" data-id="1">
                                                <div><i class="fas fa-eye"></i></div>
                                                <div><small>No Seri</small></div>
                                            </a>
                                        </td>
                                        @endif
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="detailmodal" role="dialog" aria-labelledby="detailmodal" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content" style="margin: 10px">
                    <div class="modal-header bg-info">
                        <h4 class="modal-title">Info</h4>
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
</section>
@stop

@section('adminlte_js')
<script>
    $(function() {
        var showtable = $('#showtable').DataTable({});

        $(document).on('click', '.detailmodal', function(event) {
            event.preventDefault();
            var href = $(this).attr('data-attr');
            var id = $(this).data('id');
            $.ajax({
                url: href,
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