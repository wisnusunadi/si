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

    .warning {
        color: #FFC700;
        font-weight: 600;
    }

    .info {
        color: #3a7bb0;
    }

    .filter {
        margin: 5px;
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

@section('content')
<section class="content">
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
                                                <label for="jenis_penjualan">Pengiriman</label>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="ekatalog" id="defaultCheck1" />
                                                    <label class="form-check-label" for="defaultCheck1">
                                                        Belum Dikirim
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="spa" id="defaultCheck2" />
                                                    <label class="form-check-label" for="defaultCheck2">
                                                        Sebagian Dikirim
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="spa" id="defaultCheck2" />
                                                    <label class="form-check-label" for="defaultCheck2">
                                                        Sudah Dikirim
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
                                    <table class="table" style="text-align:center;" id="showtable">
                                        <thead>
                                            <th>No</th>
                                            <th>No SO</th>
                                            <th>Tanggal Pengiriman</th>
                                            <th>Customer</th>
                                            <th>Alamat</th>
                                            <th>Telepon</th>

                                            <th>Status</th>
                                            <th>Keterangan</th>
                                            <th>Aksi</th>
                                        </thead>
                                        <tbody>
                                            <!-- <tr>
                                                <td>1</td>
                                                <td>SO/EKAT/X/02/98</td>
                                                <td>
                                                    31-10-2021
                                                </td>
                                                <td>CV. Cipta Jaya Mandiri</td>
                                                <td>Jl Dr Wahidin Sudirohusodo</td>
                                                <td>0841641741979</td>

                                                <td><span class="badge green-text">Selesai</span></td>
                                                <td>-</td>
                                                <td>
                                                    <a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr="{{route('as.so.list', ['id' => '1'])}}" data-id="1">
                                                        <i class="fas fa-search"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>SO/SPA/X/02/75</td>
                                                <td>
                                                    08-11-2021
                                                </td>
                                                <td>PT. Emiindo Jaya Bersama</td>
                                                <td>Jl Jaksa Agung Suprapto</td>
                                                <td>0841641741979</td>

                                                <td><span class="badge green-text">Selesai</span></td>
                                                <td>-</td>
                                                <td><a href=""></td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>SO/SPB/X/21/75</td>
                                                <td>03-11-2021</td>
                                                <td>Bapak Muhajir</td>
                                                <td>Jl RA Kartini</td>
                                                <td>0841641741979</td>

                                                <td><span class="badge green-text">Selesai</span></td>
                                                <td>-</td>
                                                <td><a href=""></td>
                                            </tr> -->
                                        </tbody>
                                    </table>
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