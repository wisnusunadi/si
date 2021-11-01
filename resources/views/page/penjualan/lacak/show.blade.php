@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Lacak</h1>
@stop

@section('adminlte_css')
<style>
    .filter {
        margin: 5px;
    }

    .hide {
        display: none !important;
    }
</style>
@stop

@section('content')
<div class="content">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-secondary">
                    <div class="card-title">Pencarian</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-horizontal">
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-5" style="text-align: right">Pilih</label>
                                    <div class="col-5 col-form-label">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="pilih" id="pilih1" value="no_seri" />
                                            <label class="form-check-label" for="pilih1">No Seri</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="pilih" id="pilih2" value="purchase_order" />
                                            <label class="form-check-label" for="pilih2">Purchase Order</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-5" style="text-align: right">Masukkan Data</label>
                                    <div class="col-4">
                                        <input type="text" class="form-control col-form-label @error('data') is-invalid @enderror" id="data" name="data" disabled />
                                        <div class="invalid-feedback" id="msgdata">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-5"></div>
                                    <div class="col-4">
                                        <span class="float-right filter"><button type="button" class="btn btn-success" id="btncari" disabled>Cari</button></span>
                                        <span class="float-right filter"><button type="button" class="btn btn-outline-danger" id="btnbatal">Batal</button></span>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card hide" id="po">
                <div class="card-body">
                    <h4>Hasil Pencarian</h4>
                    <div class="table-responsive">
                        <table class="table table-hover" id="potable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No PO</th>
                                    <th>Tanggal</th>
                                    <th>Customer</th>
                                    <th>No Seri</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card hide" id="noseri">
                <div class="card-body">
                    <h4>Hasil Pencarian No Seri</h4>
                    <div class="table-responsive">
                        <table class="table table-hover" id="noseritable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Posisi</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('adminlte_js')
<script>
    $(function() {
        $('#potable').DataTable();
        $('#noseritable').DataTable();
        $('input[type="radio"][name="pilih"]').on('change', function() {
            if ($(this).val() != "") {
                $('#data').removeAttr('disabled');
                if ($('#data').val() != "") {
                    $('#btncari').removeAttr('disabled');
                } else {
                    $('#btncari').attr('disabled', true);
                }
            } else {
                $('#btncari').attr('disabled', true);
            }
        });

        $('#data').on('keyup change', function() {
            if ($(this).val() != "") {
                if ($('input[type="radio"][name="pilih"]').val() != "") {
                    $('#btncari').removeAttr('disabled');
                } else {
                    $('#btncari').attr('disabled', true);
                }
            } else {
                $('#btncari').attr('disabled', true);
            }
        });

        $('#btncari').on('click', function() {
            if ($('input[type="radio"][name="pilih"]:checked').val() == "no_seri") {
                $('#noseri').removeClass('hide');
                $('#po').addClass('hide');
            } else if ($('input[type="radio"][name="pilih"]:checked').val() == "purchase_order") {
                $('#po').removeClass('hide');
                $('#noseri').addClass('hide');
            }
        });

        $('#btnbatal').on('click', function() {
            $('input[type="radio"][name="pilih"]').prop('checked', false);
            $('#data').val('');
            $('#data').attr('disabled', true);
            $('#po').addClass('hide');
            $('#noseri').addClass('hide');
            $('#btncari').attr('disabled', true);
        });
    })
</script>
@stop