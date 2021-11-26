@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Laporan</h1>
@stop

@section('adminlte_css')
<style>
    .filter {
        margin: 5px;
    }

    .hide {
        display: none !important;
    }

    .align-center {
        text-align: center;
    }

    .nowrap-text {
        white-space: nowrap;
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
                            <!-- <form method="POST" action="/api/qc/so/laporan/create"> -->
                            <div class="form-horizontal">
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-5" style="text-align: right">Produk</label>
                                    <div class="col-4">
                                        <select class="select2 select-info form-control produk_id" name="produk_id" id="produk_id">
                                            <option value=""></option>
                                        </select>
                                        <div class="feedback" id="msgproduk_id">
                                            <small class="text-muted">Produk boleh dikosongi</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-5" style="text-align: right">No SO</label>
                                    <div class="col-4">
                                        <input type="text" class="form-control no_so" id="no_so" name="no_so" placeholder="Masukkan Nomor SO">
                                        <div class="feedback" id="msgno_so">
                                            <small class="text-muted">No SO boleh dikosongi</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-5" style="text-align: right">Hasil Pengujian</label>
                                    <div class="col-5 col-form-label">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="hasil_uji" id="hasil_uji1" value="semua" />
                                            <label class="form-check-label" for="hasil_uji1">Semua</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="hasil_uji" id="hasil_uji2" value="ok" />
                                            <label class="form-check-label" for="hasil_uji2">OK</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="hasil_uji" id="hasil_uji3" value="nok" />
                                            <label class="form-check-label" for="hasil_uji3">Tidak OK</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tanggal_mulai" class="col-form-label col-5" style="text-align: right">Tanggal Awal</label>
                                    <div class="col-2">
                                        <input type="date" class="form-control col-form-label @error('tanggal_mulai') is-invalid @enderror" id="tanggal_mulai" name="tanggal_mulai" readonly />
                                        <div class="invalid-feedback" id="msgtanggal_mulai">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tanggal_akhir" class="col-form-label col-5" style="text-align: right">Tanggal Akhir</label>
                                    <div class="col-2">
                                        <input type="date" class="form-control col-form-label @error('tanggal_akhir') is-invalid @enderror" id="tanggal_akhir" name="tanggal_akhir" readonly />
                                        <div class="invalid-feedback" id="msgtanggal_akhir">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-5"></div>
                                    <div class="col-4">
                                        <span class="float-right filter"><button type="submit" class="btn btn-success" id="btncetak" disabled>Cetak</button></span>
                                        <span class="float-right filter"><button type="button" class="btn btn-outline-danger" id="btnbatal">Batal</button></span>
                                    </div>
                                </div>
                            </div>
                            <!-- </form> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row hide" id="lapform">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5>Laporan Pengujian</h5>
                    <div class="table-responsive">
                        <table class="table table-hover" id="qctable" style="width:100%">
                            <thead style="text-align: center;">
                                <tr>
                                    <th>No</th>
                                    <th>No SO</th>
                                    <th>Nama Produk</th>
                                    <th>No Seri</th>
                                    <th>Tanggal Uji</th>
                                    <th>Hasil</th>
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
<script src="https://cdn.datatables.net/rowgroup/1.1.3/js/dataTables.rowGroup.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.1.3/css/rowGroup.bootstrap4.min.css">

<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js "></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/css/buttons.bootstrap4.min.css "></script>
<script>
    $(function() {
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();

        today = yyyy + '-' + mm + '-' + dd;
        //console.log(today);
        $("#tanggal_mulai").attr("max", today);
        $("#tanggal_akhir").attr("max", today);

        $('.produk_id').on('keyup change', function() {
            if ($(this).val() != "") {
                $('input[type="radio"][name="hasil_uji"]').removeAttr('disabled');
                if ($('input[type="radio"][name="hasil_uji"]').val() != '' && $('#tanggal_mulai').val() != "" && $('#tanggal_akhir').val() != "") {
                    $("#btncetak").removeAttr('disabled');
                } else {
                    $("#btncetak").attr('disabled', true);
                }
            } else {
                $("#btncetak").attr('disabled', true);
            }
        });

        $('input[type="radio"][name="hasil_uji"]').on('change', function() {
            if ($(this).val() != "") {
                $('#tanggal_mulai').removeAttr('readonly');
                if ($('#tanggal_mulai').val() != "" && $('#tanggal_akhir').val() != "") {
                    $("#btncetak").removeAttr('disabled');
                } else {
                    $("#btncetak").attr('disabled', true);
                }
            } else {
                $("#btncetak").attr('disabled', true);
            }
        });

        $('#tanggal_mulai').on('keyup change', function() {
            $("#tanggal_akhir").val("");
            $("#btncetak").removeAttr('disabled');
            if ($(this).val() != "") {
                $('#tanggal_akhir').removeAttr('readonly');
                $("#tanggal_akhir").attr("min", $(this).val())
                if ($('input[type="radio"][name="hasil_uji"]').val() != '' && $('#tanggal_akhir').val() != "") {
                    $("#btncetak").removeAttr('disabled');
                } else {

                    $("#btncetak").attr('disabled', true);
                }
            } else {
                $("#tanggal_akhir").val("");
                $("#btncetak").attr('disabled', true);
            }
        });

        $('#tanggal_akhir').on('keyup change', function() {
            if ($(this).val() != "") {
                if ($('input[type="radio"][name="hasil_uji"]').val() != '' && $('#tanggal_mulai').val() != "") {
                    $("#btncetak").removeAttr('disabled');
                } else {
                    $("#btncetak").attr('disabled', true);
                }
            } else {
                $("#btncetak").attr('disabled', true);
            }
        });

        $('.produk_id').select2({
            allowClear: true,
            placeholder: 'Pilih Data Produk',
            ajax: {
                tags: [],
                dataType: 'json',
                delay: 250,
                type: 'GET',
                url: '/api/penjualan_produk/select/',
                processResults: function(data) {
                    //console.log(data);
                    return {
                        results: $.map(data, function(obj) {
                            return {
                                id: obj.id,
                                text: obj.nama
                            };
                        })
                    };
                },
            }
        });

        $("#btnbatal").on('click', function() {
            $("#btncetak").attr('disabled', true);
            $(".produk_id").val(null).trigger("change");
            $(".no_so").val("");
            $('input[type="radio"][name="hasil_uji"]').prop('checked', false);
            $('#tanggal_mulai').val('');
            $('#tanggal_mulai').attr('readonly', true);
            $('#tanggal_akhir').val('');
            $('#tanggal_akhir').attr('readonly', true);
            $('#lapform').addClass('hide');
        });

        $("#btncetak").on('click', function() {
            $("#btncetak").attr('disabled', true);
            $('#lapform').removeClass('hide');
            var produk = "";
            if ($(".produk_id").val() != "") {
                produk = $(".produk_id").val();
            } else {
                produk = "0";
            }

            var so = "";
            if ($(".no_so").val() != "") {
                var sos = $(".no_so").val();
                so = sos.replaceAll("/", "_")
            } else {
                so = "0";
            }
            var hasil = $('input[type="radio"][name="hasil_uji"]:checked').val();
            var tgl_awal = $('#tanggal_mulai').val();
            var tgl_akhir = $('#tanggal_akhir').val();
            table(produk, so, hasil, tgl_awal, tgl_akhir);
        });


        function table(produk, so, hasil, tgl_awal, tgl_akhir) {
            //console.log('/api/laporan/qc/' + produk + '/' + so + '/' + hasil + '/' + tgl_awal + '/' + tgl_akhir);
            $('#qctable').DataTable({
                destroy: true,
                processing: true,
                dom: 'Bfrtip',
                serverSide: false,
                language: {
                    processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                },
                ajax: {
                    'url': '/api/laporan/qc/' + produk + '/' + so + '/' + hasil + '/' + tgl_awal + '/' + tgl_akhir,
                    'headers': {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    }
                },
                buttons: [{
                        extend: 'excel',
                        title: 'Laporan QC Outgoing',
                        text: '<i class="far fa-file-excel"></i> Export',
                        className: "btn btn-info"
                    },
                    {
                        extend: 'print',
                        title: 'Laporan QC Outgoing',
                        text: '<i class="fas fa-print"></i> Cetak',
                        className: "btn btn-primary"
                    },
                ],
                columns: [{
                        data: 'DT_RowIndex',
                        className: 'nowrap-text align-center'
                    },
                    {
                        data: 'so',
                        className: 'nowrap-text align-center'
                    },
                    {
                        data: 'produk'
                    },
                    {
                        data: 'noseri',
                        className: 'nowrap-text align-center'
                    },
                    {
                        data: 'tgl_uji',
                        className: 'nowrap-text align-center'
                    },
                    {
                        data: 'status',
                        className: 'nowrap-text align-center'
                    },
                ],
            });
        }
    });
</script>
@endsection