@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0  text-dark">Laporan</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                @if(Auth::user()->divisi_id == "26" || Auth::user()->divisi_id == "8")
                <li class="breadcrumb-item"><a href="{{route('penjualan.dashboard')}}">Beranda</a></li>
                <li class="breadcrumb-item active">Laporan</li>
                @elseif(Auth::user()->divisi_id == "15")
                <li class="breadcrumb-item"><a href="{{route('logistik.dashboard')}}">Beranda</a></li>
                <li class="breadcrumb-item active">Laporan</li>
                @elseif(Auth::user()->divisi_id == "2")
                <li class="breadcrumb-item"><a href="{{route('direksi.dashboard')}}">Beranda</a></li>
                <li class="breadcrumb-item active">Laporan</li>
                @endif
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop

@section('adminlte_css')
<style>
    .filter {
        margin: 5px;
    }

    .hide {
        display: none !important;
    }

    .table {
        vertical-align: middle;
    }

    .tes {
        background-color: #ffff !important;
    }

    .nowraptext {
        white-space: nowrap;
    }


    @media screen and (min-width: 992px) {
        .labelket{
            text-align: right;
        }

        body {
            font-size: 14px;
        }

        #detailmodal {
            font-size: 14px;
        }

        .btn {
            font-size: 14px;
        }

        .overflowy {
            max-height: 550px;
            width: auto;
            overflow-y: scroll;
            box-shadow: none;
        }

        .dropdown-item {
            font-size: 14px;
        }
    }

    @media screen and (max-width: 991px) {
        .labelket{
            text-align: left;
        }

        body {
            font-size: 12px;
        }

        h4 {
            font-size: 18x;
        }

        #detailmodal {
            font-size: 12px;
        }

        .btn {
            font-size: 12px;
        }

        .overflowy {
            max-height: 450px;
            width: auto;
            overflow-y: scroll;
            box-shadow: none;
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
                    <div class="card-header bg-secondary">
                        <div class="card-title">Pencarian</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <form id="filter">
                                    <div class="form-horizontal">
                                        <div class="form-group row">
                                            <label for="" class="col-form-label col-lg-5 col-md-12 labelket">Distributor / Customer</label>
                                            <div class="col-lg-4 col-md-12">
                                                <select class="select2 select-info form-control customer_id" name="customer_id" id="customer_id">
                                                    <option value="semua">Semua Distributor</option>
                                                </select>
                                                <div class="feedback" id="msgcustomer_id">
                                                    <small class="text-muted">Distributor / Customer boleh dikosongi</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="penjualan" class="col-form-label col-lg-5 col-md-12 labelket">Penjualan</label>
                                            <div class="col-5 col-form-label">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox" id="penjualan" value="ekatalog" name="penjualan">
                                                    <label class="form-check-label" for="inlineCheckbox1">E-katalog</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox" id="penjualan" value="spa" name="penjualan">
                                                    <label class="form-check-label" for="inlineCheckbox1">SPA</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox" id="penjualan" value="spb" name="penjualan">
                                                    <label class="form-check-label" for="inlineCheckbox1">SPB</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="tanggal_mulai" class="col-form-label col-lg-5 col-md-12 labelket">Tanggal Awal</label>
                                            <div class="col-lg-2 col-md-12">
                                                <input type="date" class="form-control col-form-label @error('tanggal_mulai') is-invalid @enderror" id="tanggal_mulai" name="tanggal_mulai" readonly />
                                                <div class="invalid-feedback" id="msgtanggal_mulai">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="tanggal_akhir" class="col-form-label col-lg-5 col-md-12 labelket">Tanggal Akhir</label>
                                            <div class="col-lg-2 col-md-12">
                                                <input type="date" class="form-control col-form-label @error('tanggal_akhir') is-invalid @enderror" id="tanggal_akhir" name="tanggal_akhir" readonly />
                                                <div class="invalid-feedback" id="msgtanggal_akhir">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-5"></div>
                                            <div class="col-lg-4 col-md-12">
                                                <span class="float-right filter"><button type="submit" class="btn btn-success" id="btncetak" disabled>Cetak</button></span>
                                                <span class="float-right filter"><button type="button" class="btn btn-outline-danger" id="btnbatal">Batal</button></span>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row hide" id="semuaform">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5>Laporan Penjualan</h5>
                        <div class="table-responsive">
                            <a id="exportbutton" href="{{route('penjualan.penjualan.export',['jenis'=> 'semua','customer_id'=> 'semua','tgl_awal'=> '0','tgl_akhir'=>'0'])}}"><button class="btn btn-success">
                                    <i class="far fa-file-excel"></i> Export
                                </button>
                            </a>
                            <span class="float-right filter">
                            </span>
                            <table class="table table-hover" id="semuatable" style="width:100%">
                                <thead style="text-align: center;">
                                    <tr>
                                        <th>No PO</th>
                                        <th>No AKN</th>
                                        <th>Customer / Distributor</th>
                                        <th>Tanggal Pesan</th>
                                        <th>Batas Kontrak</th>
                                        <th>Tanggal PO</th>
                                        <th>Instansi</th>
                                        <th>Satuan</th>
                                        <th>Produk</th>
                                        <th>No Seri</th>
                                        <th>Jumlah</th>
                                        <th>Harga</th>
                                        <th>Subtotal</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>
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

        <div class="row hide" id="ekatalogform">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5>Laporan Penjualan Ekatalog</h5>
                        <div class="table-responsive">
                            <table class="table table-hover" id="ekatalogtable" style="width: 100%;">
                                <thead style="text-align: center;">
                                    <tr>
                                        <th>No AKN</th>
                                        <th>No SO</th>
                                        <th>No PO</th>
                                        <th>No SJ</th>
                                        <th>Customer / Distributor</th>
                                        <th>Batas Kontrak</th>
                                        <th>Tanggal Pengiriman</th>
                                        <th>Tanggal PO</th>
                                        <th>Instansi</th>
                                        <th>Satuan</th>
                                        <th>Produk</th>
                                        <th>No Seri</th>
                                        <th>Jumlah</th>
                                        <th>Harga</th>
                                        <th>Subtotal</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>
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

        <div class="row hide" id="spaform">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5>Laporan Penjualan SPA</h5>
                        <div class="table-responsive">
                            <table class="table table-hover" id="spatable" style="width:100%">
                                <thead style="text-align: center;">
                                    <tr>
                                        <th>No PO</th>
                                        <th>No SO</th>
                                        <th>Customer / Distributor</th>
                                        <th>Tanggal Pesan</th>
                                        <th>Tanggal PO</th>
                                        <th>Produk</th>
                                        <th>No Seri</th>
                                        <th>Jumlah</th>
                                        <th>Harga</th>
                                        <th>Subtotal</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>
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

        <div class="row hide" id="spbform">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5>Laporan Penjualan SPB</h5>
                        <div class="table-responsive">
                            <table class="table table-hover" id="spbtable" style="width:100%">
                                <thead style="text-align: center;">
                                    <tr>
                                        <th>No PO</th>
                                        <th>No SO</th>
                                        <th>Customer / Distributor</th>
                                        <th>Tanggal Pesan</th>
                                        <th>Tanggal PO</th>
                                        <th>Produk</th>
                                        <th>No Seri</th>
                                        <th>Jumlah</th>
                                        <th>Harga</th>
                                        <th>Subtotal</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>
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

<!-- <script src="{{ asset('assets/button/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/button/jszip.min.js') }}"></script>
<script src="{{ asset('assets/button/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/button/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/button/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/button/buttons.print.min.js') }} "></script>
<link rel="stylesheet" href="{{ asset('assets/button/buttons.bootstrap4.min.css') }}"> -->

<script>
    $(function() {
        $('#customer_id').append('<option value=""></option>');
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();

        today = yyyy + '-' + mm + '-' + dd;
        console.log(today);
        $("#tanggal_mulai").attr("max", today);
        $("#tanggal_akhir").attr("max", today);

        $('#semuatable').DataTable({
            destroy: true,
            processing: true,
            dom: 'Bfrtip',
            serverSide: false,
            language: {
                processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
            },
            ajax: {
                'url': '/api/laporan/penjualan/ekatalog,spa,spb/semua/0/0',
                'dataType': 'json',
                'type': 'POST',
                'headers': {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                }
            },
            // buttons: [{
            //     extend: 'excel',
            //     title: 'Laporan Penjualan',
            //     text: '<i class="far fa-file-excel"></i> Export',
            //     className: "btn btn-info"
            // }, ],
            columns: [{
                    data: 'kosong'
                },
                {
                    data: 'no_paket',
                    className: 'nowraptext',
                    searchable: true
                },
                {
                    data: 'nama_customer',
                    className: 'nowraptext'
                },
                {
                    data: 'tgl_kirim',
                    className: 'nowraptext'
                },
                {
                    data: 'tgl_kontrak',
                    className: 'nowraptext'
                },
                {
                    data: 'tgl_po',
                    className: 'nowraptext'
                },
                {
                    data: 'instansi'
                },
                {
                    data: 'satuan'
                },
                {
                    data: 'nama_produk'
                },
                {
                    data: 'no_seri',
                    className: 'nowraptext'
                },
                {
                    data: 'jumlah'
                },
                {
                    data: 'harga',
                    render: $.fn.dataTable.render.number(',', '.', 2),
                },
                {
                    data: 'subtotal',
                    render: $.fn.dataTable.render.number(',', '.', 2),
                },
                {
                    data: 'log'
                },
                {
                    data: 'ket'
                }
            ],
            rowGroup: {
                startRender: function(rows, group) {
                    var i = 0;
                    //console.log(group);
                    return $('<tr/>')
                        .append('<td class="tes" colspan="1"><p style="font-weight:50;">' + group + '</td>');
                },
                endRender: function(rows, group) {
                    var totalPenjualan = rows
                        .data()
                        .pluck('subtotal')
                        .reduce(function(a, b) {
                            return a + b * 1;
                        }, 0);
                    totalPenjualan = $.fn.dataTable.render.number(',', '.', 2).display(totalPenjualan);
                    return $('<tr/>')
                        .append('<td colspan="12">Total Penjualan Produk: ' + rows.count() + '</td>')
                        .append('<td colspan="3">' + totalPenjualan + '</td>');
                },
                dataSrc: function(row) {
                    return row.no_po;
                },
            }

        });
        $('.customer_id').on('keyup change', function() {
            if ($(this).val() != "") {
                $('input[type="checkbox"][name="penjualan"]').removeAttr('disabled');
                if ($('input[type="checkbox"][name="penjualan"]').val() != undefined && $('#tanggal_mulai').val() != "" && $('#tanggal_akhir').val() != "") {
                    $("#btncetak").removeAttr('disabled');
                } else {
                    $("#btncetak").attr('disabled', true);
                }
            } else {
                $("#btncetak").attr('disabled', true);
            }
        });
        $('input[type="checkbox"][name="penjualan"]').on('change', function() {
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
                if ($('input[type="checkbox"][name="penjualan"]').val() != undefined && $('#tanggal_akhir').val() != "") {
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
                if ($('#tanggal_mulai').val() != "" && $('input[type="checkbox"][name="penjualan"]').val() != undefined) {
                    $("#btncetak").removeAttr('disabled');
                } else {
                    $("#btncetak").attr('disabled', true);
                }
            } else {
                $("#btncetak").attr('disabled', true);
            }
        });

        $('.customer_id').select2({
            allowClear: false,
            placeholder: 'Pilih Data',
            ajax: {
                tags: [],
                dataType: 'json',
                delay: 250,
                type: 'GET',
                url: '/api/customer/select/',
                processResults: function(data) {
                    console.log(data);
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
            $(".customer_id").val('semua').trigger("change");
            $('input[type="checkbox"][name="penjualan"]').prop('checked', false);
            $('#tanggal_mulai').val('');
            $('#tanggal_mulai').attr('readonly', true);
            $('#tanggal_akhir').val('');
            $('#tanggal_akhir').attr('readonly', true);
            $('#semuaform').addClass('hide');

        });

        // $('#btncetak').on('click', function() {
        //     if ($('input[type="checkbox"][name="penjualan"]:checked').val() == "semua") {
        //         var customer_id = $('#customer_id').val();
        //         var tanggal_mulai = $('#tanggal_mulai').val();
        //         var tanggal_akhir = $('#tanggal_akhir').val();
        //         $('#semuatable').DataTable().ajax.url('/api/laporan/penjualan/semua/' + customer_id + '/' + tanggal_mulai + '/' + tanggal_akhir + '').load();
        //         $('#semuaform').removeClass('hide');
        //         $('#ekatalogform').addClass('hide');
        //         $('#spaform').addClass('hide');
        //         $('#spbform').addClass('hide');
        //     } else if ($('input[type="checkbox"][name="penjualan"]:checked').val() == "ekatalog") {
        //         var customer_id = $('#customer_id').val();
        //         var tanggal_mulai = $('#tanggal_mulai').val();
        //         var tanggal_akhir = $('#tanggal_akhir').val();
        //         $('#ekatalogtable').DataTable().ajax.url('/api/laporan/penjualan/ekatalog/' + customer_id + '/' + tanggal_mulai + '/' + tanggal_akhir + '').load();
        //         $('#semuaform').addClass('hide');
        //         $('#ekatalogform').removeClass('hide');
        //         $('#spaform').addClass('hide');
        //         $('#spbform').addClass('hide');
        //     } else if ($('input[type="checkbox"][name="penjualan"]:checked').val() == "spa") {
        //         var customer_id = $('#customer_id').val();
        //         var tanggal_mulai = $('#tanggal_mulai').val();
        //         var tanggal_akhir = $('#tanggal_akhir').val();
        //         $('#spatable').DataTable().ajax.url('/api/laporan/penjualan/spa/' + customer_id + '/' + tanggal_mulai + '/' + tanggal_akhir + '').load();
        //         $('#semuaform').addClass('hide');
        //         $('#ekatalogform').addClass('hide');
        //         $('#spaform').removeClass('hide');
        //         $('#spbform').addClass('hide');
        //     } else if ($('input[type="checkbox"][name="penjualan"]:checked').val() == "spb") {
        //         var customer_id = $('#customer_id').val();
        //         var tanggal_mulai = $('#tanggal_mulai').val();
        //         var tanggal_akhir = $('#tanggal_akhir').val();
        //         $('#spbtable').DataTable().ajax.url('/api/laporan/penjualan/spb/' + customer_id + '/' + tanggal_mulai + '/' + tanggal_akhir + '').load();
        //         $('#semuaform').addClass('hide');
        //         $('#ekatalogform').addClass('hide');
        //         $('#spaform').addClass('hide');
        //         $('#spbform').removeClass('hide');
        //     }
        // })
        $('#filter').submit(function() {
            var penjualan = [];
            var exportbutton = $('#exportbutton').val();
            var tanggal_mulai = $('#tanggal_mulai').val();
            var tanggal_akhir = $('#tanggal_akhir').val();
            var customer_id = $('#customer_id').val();

            $("input[name=penjualan]:checked").each(function() {
                penjualan.push($(this).val());
            });



            if (penjualan != 0) {
                var x = penjualan;
            } else {
                var x = ['kosong']
            }



            $('#semuaform').removeClass('hide');
            $('#semuatable').DataTable().ajax.url('/api/laporan/penjualan/' + x + '/' + customer_id + '/' + tanggal_mulai + '/' + tanggal_akhir).load();

            var link = '/penjualan/penjualan/export/' + x + '/' + customer_id + '/' + tanggal_mulai + '/' + tanggal_akhir;

            $('#exportbutton').attr({
                href: link
            });
            return false;
        });

        // $('#exportbutton').on('click', function(event) {
        //     console.log($(this).attr("href"));
        //     return false;
        // });
    });
</script>
@endsection
