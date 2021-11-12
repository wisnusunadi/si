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
        <div class="col-lg-8 col-12">
            <div class="card">
                <div class="card-body">
                    <h5>Daftar Barang</h5>
                    <div class="table-responsive">
                        <table class="table table-hover" style="text-align: center; width:100%;" id="showtable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th class="nowrap-text">Tgl Surat Jalan</th>
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
                                    <td class="nowrap-text">1</td>
                                    <td class="nowrap-text">19-10-2021</td>
                                    <td class="nowrap-text">B-ULTRASOUND DIAGNOSTIC SYSTEM CMS-600 PLUS PRINTER TROLLEY UPS</td>
                                    <td class="nowrap-text">21102900256</td>
                                    <td class="nowrap-text">X</td>
                                    <td class="nowrap-text">5</td>
                                    <td class="nowrap-text"><span class="badge green-text">Tersedia</span></td>
                                    <td class="nowrap-text"></a>
                                        <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="noserishow dropdown-item" type="button">
                                                <i class="fas fa-eye"></i>
                                                Detail
                                            </a>
                                            <a data-toggle="modal" data-target="#editmodal" class="editmodal" data-id="1">
                                                <button class="dropdown-item" type="button">
                                                    <i class="fas fa-pencil-alt"></i>
                                                    Edit
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
                                    <td>21-10-2021</td>
                                    <td>ELITECH PULSE OXIMETER/OXYMETER FOX-2</td>
                                    <td>20502210102</td>
                                    <td></td>
                                    <td>2</td>
                                    <td><span class="badge red-text">Belum Tersedia</span></td>
                                    <td><a type="button" class="noserishow"><i class="fas fa-search"></i></a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-12 hide" id="noseri">
            <div class="card">
                <div class="card-body">
                    <div>
                        <h5 style="display: inline;" class="filter">No Seri</h5>
                        <a data-toggle="modal" data-target="#createmodal" class="createmodal float-right" data-attr="" data-id="1">
                            <button type="button" class="btn btn-sm btn-info"><i class="fas fa-plus"></i> Tambah COO</button>
                        </a>
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
                        <h4 class="modal-title">Tambah COO</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="create">

                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editmodal" role="dialog" aria-labelledby="editmodal" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content" style="margin: 10px">
                    <div class="modal-header bg-warning">
                        <h4 class="modal-title">Edit COO</h4>
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
</section>
@endsection

@section('adminlte_js')
<script>
    $(function() {
        $('#showtable').DataTable({
            scrollX: true
        });
        $('#showtable').on('click', '.noserishow', function() {
            var data = $(this).attr('data-id');
            $('#showtable').find('tr').removeClass('bgcolor');
            $(this).closest('tr').addClass('bgcolor');
            $('#noseri').removeClass('hide');
            console.log(data);
        })
        $(document).on('click', '.createmodal', function(event) {
            event.preventDefault();
            var href = $(this).attr('data-attr');
            var id = $(this).data('id');
            $.ajax({
                url: "/dc/coo/create/" + id,
                beforeSend: function() {
                    $('#loader').show();
                },
                // return the result
                success: function(result) {
                    $('#createmodal').modal("show");
                    $('#create').html(result).show();
                    $('.bulan').select2({
                        placeholder: 'Pilih Bulan',
                        allowClear: true
                    });
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

        $(document).on('click', '.editmodal', function(event) {
            event.preventDefault();
            var href = $(this).attr('data-attr');
            var id = $(this).data('id');
            $.ajax({
                url: "/dc/coo/edit/" + id,
                beforeSend: function() {
                    $('#loader').show();
                },
                // return the result
                success: function(result) {
                    $('#editmodal').modal("show");
                    $('#edit').html(result).show();
                    $('.bulan_edit').select2({
                        placeholder: 'Pilih Bulan',
                        allowClear: true
                    });
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

        $(document).on('keyup change', 'select[name="bulan"]', function() {
            if ($(this).val() != "") {
                $('#btntambah').removeClass('disabled');
            } else {
                $('#btntambah').addClass('disabled');
            }
        });

        $(document).on('keyup change', 'select[name="bulan_edit"]', function() {
            if ($(this).val() != "") {
                $('#btnsimpan').removeClass('disabled');
            } else {
                $('#btnsimpan').addClass('disabled');
            }
        });
    })
</script>

@endsection