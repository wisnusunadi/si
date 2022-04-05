@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Laporan</h1>
@stop

@section('adminlte_css')
<style>
     td.dt-control {
        background: url("/assets/image/logo/plus.png") no-repeat center center;
        cursor: pointer;
        background-size: 15px 15px;
    }
    tr.shown td.dt-control {
        background: url("/assets/image/logo/minus.png") no-repeat center center;
        background-size: 15px 15px;
    }

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

    .childrowbg{
        background-color: #E8E8E8;
    }

    @media screen and (min-width: 1440px) {

        section {
            font-size: 14px;
        }

        #detailmodal {
            font-size: 14px;
        }

        .btn {
            font-size: 12px;
        }
    }

    @media screen and (max-width: 1439px) {

        label,
        .row {
            font-size: 12px;
        }

        h4 {
            font-size: 20px;
        }

        #detailmodal {
            font-size: 12px;
        }

        .btn {
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
                    <div class="card-header bg-secondary">
                        <div class="card-title">Pencarian</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <!-- <form method="POST" action="/api/qc/so/laporan/create"> -->
                                <div class="form-horizontal">
                                    <div class="form-group row">
                                        <label for="" class="col-form-label col-5" style="text-align: right">Cari Pengujian</label>
                                        <div class="col-5 col-form-label">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="cari" id="cari1" value="produk" />
                                                <label class="form-check-label" for="cari1">Produk</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="cari" id="cari2" value="part" />
                                                <label class="form-check-label" for="cari2">Part</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row hide" id="prt_id">
                                        <label for="" class="col-form-label col-5" style="text-align: right">Part</label>
                                        <div class="col-4">
                                            <select class="select2 select-info form-control part_id" name="part_id" id="part_id">
                                                <option value=""></option>
                                            </select>
                                            <div class="feedback" id="msgpart_id">
                                                <small class="text-muted">Part boleh dikosongi</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row hide" id="prd_id">
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
                                    <div class="form-group row hide" id="prd_uji">
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
                                        <label for="" class="col-form-label col-5" style="text-align: right">No SO</label>
                                        <div class="col-4">
                                            <input type="text" class="form-control no_so" id="no_so" name="no_so" placeholder="Masukkan Nomor SO">
                                            <div class="feedback" id="msgno_so">
                                                <small class="text-muted">No SO boleh dikosongi</small>
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
                            <a id="exportbutton" href=""><button class="btn btn-info" id="btnexport"><i class="far fa-file-excel fa-fw"></i> Export</button></a>
                            <table class="table table-hover" id="qctable" style="width:100%">
                                <thead style="text-align: center;">
                                    {{-- <tr>
                                        <th rowspan="2">No</th>
                                        <th rowspan="2">No SO</th>
                                        <th rowspan="2">Nama Produk</th>
                                        <th rowspan="2">No Seri</th>
                                        <th rowspan="2">Tanggal Uji</th>
                                        <th rowspan="2">Hasil</th>
                                        <th colspan="2">Jumlah</th>
                                    </tr>
                                    <tr>
                                        <th>OK</th>
                                        <th>NOK</th>
                                    </tr> --}}
                                    <tr>
                                        <th></th>
                                        <th>No SO</th>
                                        <th>No AKN</th>
                                        <th>No PO</th>
                                        <th>Tanggal PO</th>
                                        <th>Customer</th>
                                        <th>Instansi</th>
                                        <th>Alamat</th>
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
</section>
@endsection

@section('adminlte_js')
<script src="{{ asset('assets/rowgroup/dataTables.rowGroup.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('assets/rowgroup/rowGroup.bootstrap4.min.css') }}">

<script src="{{ asset('assets/button/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/button/jszip.min.js') }}"></script>
<script src="{{ asset('assets/button/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/button/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/button/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/button/buttons.print.min.js') }} "></script>
<link rel="stylesheet" href="{{ asset('assets/button/buttons.bootstrap4.min.css') }}">

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

        $('.part_id').on('keyup change', function() {
            if ($(this).val() != "") {
                if ($('input[type="radio"][name="cari"]').val() == 'part' && $('#tanggal_mulai').val() != "" && $('#tanggal_akhir').val() != "") {
                    $("#btncetak").removeAttr('disabled');
                } else {
                    $("#btncetak").attr('disabled', true);
                }
            } else {
                $("#btncetak").attr('disabled', true);
            }
        });

        $('.produk_id').on('keyup change', function() {
            if ($(this).val() != "") {
                $('input[type="radio"][name="hasil_uji"]').removeAttr('disabled');
                if ($('input[type="radio"][name="cari"]:checked').val() == 'produk' && $('input[type="radio"][name="hasil_uji"]').val() != '' && $('#tanggal_mulai').val() != "" && $('#tanggal_akhir').val() != "") {
                    $("#btncetak").removeAttr('disabled');
                } else {
                    $("#btncetak").attr('disabled', true);
                }
            } else {
                $("#btncetak").attr('disabled', true);
            }
        });

        $('input[type="radio"][name="cari"]').on('change', function() {
            if ($(this).val() != "") {
                if ($('input[type="radio"][name="cari"]:checked').val() == "produk") {
                    $('#prt_id').addClass('hide');
                    $('#prd_id').removeClass('hide');
                    $('#prd_uji').removeClass('hide');

                    if ($('input[type="radio"][name="cari"]:checked').val() == 'produk' && $('input[type="radio"][name="hasil_uji"]').val() != '' && $('#tanggal_mulai').val() != "" && $('#tanggal_akhir').val() != "") {
                        $("#btncetak").removeAttr('disabled');
                    } else {
                        $("#btncetak").attr('disabled', true);
                    }
                } else if ($('input[type="radio"][name="cari"]:checked').val() == "part") {
                    $('#tanggal_mulai').removeAttr('readonly');
                    $('#prt_id').removeClass('hide');
                    $('#prd_id').addClass('hide');
                    $('#prd_uji').addClass('hide');

                    if ($('input[type="radio"][name="cari"]:checked').val() == 'part' && $('#tanggal_mulai').val() != "" && $('#tanggal_akhir').val() != "") {
                        $("#btncetak").removeAttr('disabled');
                    } else {
                        $("#btncetak").attr('disabled', true);
                    }
                }
            } else {
                $("#btncetak").attr('disabled', true);
            }
        });

        $('input[type="radio"][name="hasil_uji"]').on('change', function() {
            if ($(this).val() != "") {
                $('#tanggal_mulai').removeAttr('readonly');
                if ($('input[type="radio"][name="cari"]:checked').val() == 'produk' && $('#tanggal_mulai').val() != "" && $('#tanggal_akhir').val() != "") {
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
                if ($('input[type="radio"][name="cari"]').val() != "" && $('input[type="radio"][name="hasil_uji"]').val() != '' && $('#tanggal_akhir').val() != "") {
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
                if ($('input[type="radio"][name="cari"]').val() != "" && $('input[type="radio"][name="hasil_uji"]').val() != '' && $('#tanggal_mulai').val() != "") {
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
                url: '/api/penjualan_produk/select',
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

        $('.part_id').select2({
            placeholder: "Pilih Part",
            ajax: {
                minimumResultsForSearch: 20,
                dataType: 'json',
                theme: "bootstrap",
                delay: 250,
                type: 'POST',
                url: '/api/gk/sel-m-spare',
                data: function(params) {
                    return {
                        term: params.term
                    }
                },
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
            $(".part_id").val(null).trigger("change");
            $(".no_so").val("");
            $('#prt_id').addClass('hide');
            $('#prd_id').addClass('hide');
            $('#prd_uji').addClass('hide');
            $('input[type="radio"][name="hasil_uji"]').prop('checked', false);
            $('input[type="radio"][name="cari"]').prop('checked', false);
            $('#tanggal_mulai').val('');
            $('#tanggal_mulai').attr('readonly', true);
            $('#tanggal_akhir').val('');
            $('#tanggal_akhir').attr('readonly', true);
            $('#lapform').addClass('hide');
        });

        $("#btncetak").on('click', function() {
            $("#btncetak").attr('disabled', true);
            $('#lapform').removeClass('hide');

            var hasil = "";
            var produk = "";
            if ($(".produk_id").val() != "") {
                produk = $(".produk_id").val();
            } else if ($(".produk_id").val() == "") {
                produk = "0";
            }

            if ($(".part_id").val() != "") {
                produk = $(".part_id").val();
            } else if ($(".part_id").val() == "") {
                produk = "0";
            }
            var so = "";
            if ($(".no_so").val() != "") {
                var sos = $(".no_so").val();
                so = sos.replaceAll("/", "_")
            } else {
                so = "0";
            }
            var jenis = $('input[type="radio"][name="cari"]:checked').val();
            if (jenis == "produk") {
                hasil = $('input[type="radio"][name="hasil_uji"]:checked').val();
            } else {
                hasil = "0";
            }

            var tgl_awal = $('#tanggal_mulai').val();
            var tgl_akhir = $('#tanggal_akhir').val();
            $('#exportbutton').attr('href', '/qc/so/laporan/export/'+jenis+'/'+produk+'/'+so+'/'+hasil+'/'+tgl_awal+'/'+tgl_akhir);
            table(jenis, produk, so, hasil, tgl_awal, tgl_akhir);
        });


        // function table(jenis, produk, so, hasil, tgl_awal, tgl_akhir) {
        //     $('#qctable').DataTable({
        //         destroy: true,
        //         processing: true,
        //         dom: 'Bfrtip',
        //         serverSide: false,
        //         language: {
        //             processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
        //         },
        //         ajax: {
        //             'type': 'POST',
        //             'datatype': 'JSON',
        //             'url': '/api/laporan/qc/' + jenis + '/' + produk + '/' + so + '/' + hasil + '/' + tgl_awal + '/' + tgl_akhir,
        //             'headers': {
        //                 'X-CSRF-TOKEN': '{{csrf_token()}}'
        //             }
        //         },
        //         buttons: [{
        //             extend: 'excel',
        //             title: 'Laporan QC Outgoing',
        //             text: '<i class="far fa-file-excel"></i> Export',
        //             className: "btn btn-info"
        //         }, ],
        //         columns: [{
        //                 data: 'DT_RowIndex',
        //                 className: 'nowrap-text align-center'
        //             },
        //             {
        //                 data: 'so',
        //                 className: 'nowrap-text align-center'
        //             },
        //             {
        //                 data: 'produk'
        //             },
        //             {
        //                 data: 'noseri',
        //                 className: 'nowrap-text align-center',
        //                 visible: jenis == "produk" ? true : false
        //             },
        //             {
        //                 data: 'tgl_uji',
        //                 className: 'nowrap-text align-center'
        //             },
        //             {
        //                 data: 'status',
        //                 className: 'nowrap-text align-center',
        //                 visible: jenis == "produk" ? true : false
        //             },
        //             {
        //                 data: 'jumlah_ok',
        //                 className: 'nowrap-text align-center',
        //                 visible: jenis == "part" ? true : false
        //             },
        //             {
        //                 data: 'jumlah_nok',
        //                 className: 'nowrap-text align-center',
        //                 visible: jenis == "part" ? true : false
        //             },
        //         ],
        //     });
        // }
        var showtable = "";

        function table(jenis, produk, so, hasil, tgl_awal, tgl_akhir) {
            showtable = $('#qctable').DataTable({
                destroy: true,
                processing: true,
                serverSide: false,
                language: {
                    processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                },
                ajax: {
                    'type': 'POST',
                    'datatype': 'JSON',
                    'url': '/api/laporan/qc_2/' + jenis + '/' + produk + '/' + so + '/' + hasil + '/' + tgl_awal + '/' + tgl_akhir,
                    'headers': {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    }
                },
                columns: [{
                        "className": 'dt-control',
                        "orderable": false,
                        "data": null,
                        "defaultContent": ''
                    },
                    {
                        data: 'so',
                        className: 'nowrap-text align-center'
                    },
                    {
                        data: 'no_paket',
                        className: 'nowrap-text align-center',
                    },
                    {
                        data: 'no_po',
                        className: 'nowrap-text align-center',
                    },
                    {
                        data: 'tgl_po',
                        className: 'nowrap-text align-center'
                    },
                    {
                        data: 'customer',
                        className: 'align-center',
                    },
                    {
                        data: 'instansi',
                        className: 'align-center',
                    },
                    {
                        data: 'alamat',
                        className: 'align-center',
                    },
                    {
                        data: 'status',
                        className: 'nowrap-text align-center',
                    },
                ],
            });
        }

        function dettable(id, jenis, produk, hasil, tgl_awal, tgl_akhir) {
            var groupColumn = 0;
            $('#dettable'+id).DataTable({
                destroy: true,
                processing: true,
                serverSide: false,
                language: {
                    processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                },
                ajax: {
                    'type': 'POST',
                    'datatype': 'JSON',
                    'url': '/api/laporan/qc/detail/' + id,
                    'data': {'jenis': jenis, 'produk': produk, 'hasil': hasil, 'tgl_awal': tgl_awal, 'tgl_akhir': tgl_akhir},
                    'headers': {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    }
                },
                columns: [
                    {
                        data: 'produk'
                    },
                    {
                        data: 'noseri',
                        className: 'nowrap-text align-center',
                        visible: jenis == "produk" ? true : false
                    },
                    {
                        data: 'tgl_uji',
                        className: 'nowrap-text align-center'
                    },
                    {
                        data: 'status',
                        className: 'nowrap-text align-center',
                        visible: jenis == "produk" ? true : false
                    },
                    {
                        data: 'jumlah_ok',
                        className: 'nowrap-text align-center',
                        visible: jenis == "part" ? true : false
                    },
                    {
                        data: 'jumlah_nok',
                        className: 'nowrap-text align-center',
                        visible: jenis == "part" ? true : false
                    },
                ],
                "fixedColumns": {
                    left: 0
                },
                "columnDefs": [{
                    "visible": false,
                    "targets": groupColumn
                }],
                "order": [
                    [groupColumn, 'asc']
                ],
                "drawCallback": function(settings) {
                    var api = this.api();
                    var rows = api.rows({
                        page: 'current'
                    }).nodes();
                    var last = null;

                    api.column(groupColumn, {
                        page: 'current'
                    }).data().each(function(group, i) {
                        if (last !== group) {
                            $(rows).eq(i).before(
                                '<tr class="group" style="background-color:steelblue; color:white;"><td colspan="6"><b>' + group + '</b></td></tr>'
                            );
                            last = group;
                        }
                    });
                }
            });
        }

        function format ( data ) {
            return `
            <div class="row childrowbg">
                <div class="col-12">
                    <div class="card shadow-none">
                        <div class="card-body">
                            <h5>Daftar Produk / Part</h5>
                            <div class="table-responsive">
                            <table class="table table-hover dettable" id="dettable`+data+`" width="100%">
                                <thead>
                                    <tr>
                                        <th rowspan="2">Produk</th>
                                        <th rowspan="2">No Seri</th>
                                        <th rowspan="2">Tanggal Uji</th>
                                        <th rowspan="2">Hasil</th>
                                        <th colspan="2">Jumlah</th>
                                    </tr>
                                    <tr>
                                        <th>OK</th>
                                        <th>NOK</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
        }

        $('#qctable tbody').on('click', 'td.dt-control', function () {
            var tr = $(this).closest('tr');
            var row = showtable.row( tr );

            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                // Open this row
                row.child( format(row.data().id), ['childrowbg', "childrow"+row.data().id]).show();
                tr.addClass('shown');
                var hasil = "";
                var produk = "";

                if ($(".produk_id").val() != "") {
                    produk = $(".produk_id").val();
                } else if ($(".produk_id").val() == "") {
                    produk = "0";

                }

                if ($(".part_id").val() != "") {
                    produk = $(".part_id").val();
                } else if ($(".part_id").val() == "") {
                    produk = "0";
                }

                var so = "";
                if ($(".no_so").val() != "") {
                    var sos = $(".no_so").val();
                    so = sos.replaceAll("/", "_")
                } else {
                    so = "0";
                }

                var jenis = $('input[type="radio"][name="cari"]:checked').val();
                if (jenis == "produk") {
                    hasil = $('input[type="radio"][name="hasil_uji"]:checked').val();
                } else {
                    hasil = "0";
                }

                var tgl_awal = $('#tanggal_mulai').val();
                var tgl_akhir = $('#tanggal_akhir').val();
                // sjtabledata(row.data().id, pengiriman, ekspedisi, tgl_awal, tgl_akhir);
                dettable(row.data().id, jenis, produk, hasil, tgl_awal, tgl_akhir);
            }
        });
    });
</script>
@endsection
