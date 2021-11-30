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
                    <div class="card-header bg-secondary">
                        <div class="card-title">Pencarian</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <!-- <form method="POST" action="/api/laporan/create"> -->
                                <div class="form-horizontal">
                                    <div class="form-group row">
                                        <label for="pengiriman" class="col-form-label col-5" style="text-align: right">Pengiriman</label>
                                        <div class="col-5 col-form-label">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="pengiriman" id="jasa_pengiriman1" value="ekspedisi" />
                                                <label class="form-check-label" for="jasa_pengiriman1">Ekspedisi</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="pengiriman" id="jasa_pengiriman2" value="nonekspedisi" />
                                                <label class="form-check-label" for="jasa_pengiriman2">Non Ekspedisi</label>
                                            </div>
                                            <div class="feedback" id="msgjasa_pengiriman">
                                                <small class="text-muted">Jasa Pengiriman boleh dikosongi</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row hide" id="ekspedisi">
                                        <label for="" class="col-form-label col-5" style="text-align: right">Ekspedisi</label>
                                        <div class="col-5">
                                            <select class="select2 select-info form-control ekspedisi_id" name="ekspedisi_id" id="ekspedisi_id" style="width:100%;">
                                                <option value=""></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="tanggal_mulai" class="col-form-label col-5" style="text-align: right">Tanggal Mulai</label>
                                        <div class="col-2">
                                            <input type="date" class="form-control col-form-label @error('tanggal_mulai') is-invalid @enderror" id="tanggal_mulai" name="tanggal_mulai" />
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
                                            <span class="float-right filter"><button type="button" class="btn btn-success" id="btncetak" disabled>Cetak</button></span>
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
        <div class="row hide" id="showform">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5>Laporan Pengiriman</h5>
                        <div class="table-responsive">
                            <table class="table table-hover" id="showtable">
                                <thead style="text-align: center;">
                                    <tr>
                                        <th>No</th>
                                        <th>No SO</th>
                                        <th>No SJ</th>
                                        <th>No Invoice</th>
                                        <th>No Resi</th>
                                        <th>Customer</th>
                                        <th>Alamat</th>
                                        <th>Provinsi</th>
                                        <th>Telepon</th>
                                        <th>Jasa Ekspedisi</th>
                                        <th>Tanggal Kirim</th>
                                        <th>Tanggal Sampai</th>
                                        <th>Nama Produk</th>
                                        <th>Qty</th>
                                        <th>Ongkos Kirim</th>
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
<script>
    $(function() {
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();

        today = yyyy + '-' + mm + '-' + dd;
        console.log(today);
        $("#tanggal_mulai").attr("max", today);
        $("#tanggal_akhir").attr("max", today);

        ekspedisi_select();

        function table(pengiriman, ekspedisi, tgl_awal, tgl_akhir) {
            // console.log('/api/laporan/logistik/' + pengiriman + '/' + ekspedisi + '/' + tgl_awal + '/' + tgl_akhir);
            $('#showtable').DataTable({
                destroy: true,
                processing: true,
                dom: 'Bfrtip',
                serverSide: false,
                language: {
                    processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                },
                ajax: {
                    'url': '/api/laporan/logistik/' + pengiriman + '/' + ekspedisi + '/' + tgl_awal + '/' + tgl_akhir,
                    'headers': {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    }
                },
                buttons: [{
                        extend: 'excel',
                        title: 'Laporan Pengiriman',
                        text: '<i class="far fa-file-excel"></i> Export',
                        className: "btn btn-info"
                    },
                    {
                        extend: 'print',
                        title: 'Laporan Pengiriman',
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
                        data: 'sj'
                    },
                    {
                        data: 'invoice'
                    },
                    {
                        data: 'no_resi'
                    },
                    {
                        data: 'customer'
                    },
                    {
                        data: 'alamat',
                        className: 'nowrap-text align-center'
                    },
                    {
                        data: 'provinsi',
                        className: 'nowrap-text align-center'
                    },
                    {
                        data: 'telp',
                        className: 'nowrap-text align-center'
                    },
                    {
                        data: 'ekspedisi',
                        className: 'nowrap-text align-center'
                    },
                    {
                        data: 'tgl_kirim',
                        className: 'nowrap-text align-center'
                    },
                    {
                        data: 'tgl_selesai',
                        className: 'nowrap-text align-center'
                    },
                    {
                        data: 'produk',
                        className: 'nowrap-text align-center'
                    },
                    {
                        data: 'jumlah',
                        className: 'nowrap-text align-center'
                    },
                    {
                        data: 'ongkir',
                        className: 'nowrap-text align-center'
                    },
                    {
                        data: 'status',
                        className: 'nowrap-text align-center'
                    },
                ],
            });
        }

        function ekspedisi_select() {
            $('.ekspedisi_id').select2({
                placeholder: "Pilih Ekspedisi",
                ajax: {
                    minimumResultsForSearch: 20,
                    dataType: 'json',
                    delay: 250,
                    type: 'GET',
                    url: '/api/logistik/ekspedisi/select',
                    data: function(params) {
                        return {
                            term: params.term
                        }
                    },
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
            }).change(function() {
                var value = $(this).val();
                console.log(value);
            });
        }

        $('.ekspedisi_id').on('keyup change', function() {
            if ($(this).val() != "") {
                $('input[type="radio"][name="penjualan"]').removeAttr('disabled');
                if ($('input[type="radio"][name="penjualan"]').val() != undefined && $('#tanggal_mulai').val() != "" && $('#tanggal_akhir').val() != "") {
                    $("#btncetak").removeAttr('disabled');
                } else {
                    $("#btncetak").attr('disabled', true);
                }
            } else {
                $("#btncetak").attr('disabled', true);
            }
        });

        $('input[type="radio"][name="pengiriman"]').on('change', function() {
            if ($(this).val() == "ekspedisi") {
                $('#ekspedisi').removeClass('hide');
            } else {
                $('#ekspedisi').addClass('hide');
            }
        });

        $('#tanggal_mulai').on('keyup change', function() {
            $("#tanggal_akhir").val("");
            $("#btncetak").removeAttr('disabled');
            if ($(this).val() != "") {
                $('#tanggal_akhir').removeAttr('readonly');
                $("#tanggal_akhir").attr("min", $(this).val())
                if ($('#tanggal_akhir').val() != "") {
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
                if ($('#tanggal_mulai').val() != "") {
                    $("#btncetak").removeAttr('disabled');
                } else {
                    $("#btncetak").attr('disabled', true);
                }
            } else {
                $("#btncetak").attr('disabled', true);
            }
        });

        // $('.customer_id').select2({
        //     allowClear: true,
        //     placeholder: 'Pilih Data',
        //     ajax: {
        //         tags: [],
        //         dataType: 'json',
        //         delay: 250,
        //         type: 'GET',
        //         url: '/api/customer/select/',
        //         processResults: function(data) {
        //             console.log(data);
        //             return {
        //                 results: $.map(data, function(obj) {
        //                     return {
        //                         id: obj.id,
        //                         text: obj.nama
        //                     };
        //                 })
        //             };
        //         },
        //     }
        // });

        $("#btnbatal").on('click', function() {
            $("#btncetak").attr('disabled', true);
            $(".ekspedisi_id").val(null).trigger("change");
            $('#ekspedisi').addClass('hide');
            $('input[type="radio"][name="pengiriman"]').prop('checked', false);
            $('#tanggal_mulai').val('');
            $('#tanggal_akhir').val('');
            $('#tanggal_akhir').attr('readonly', true);
            $('#showform').addClass('hide');
        });

        $('#btncetak').on('click', function() {
            $('#showform').removeClass('hide');

            var ekspedisi = "0";
            console.log($(".ekspedisi_id").val());
            var pengiriman = "0";
            if ($('input[type="radio"][name="pengiriman"]:checked').length > 0) {
                pengiriman = $('input[type="radio"][name="pengiriman"]:checked').val();
                if (pengiriman == "ekspedisi") {
                    if ($(".ekspedisi_id").val() != "") {
                        ekspedisi = $(".ekspedisi_id").val();
                    } else {
                        ekspedisi = "0";
                    }
                }
            } else {
                pengiriman = "0";
            }

            var tgl_awal = $('#tanggal_mulai').val();
            var tgl_akhir = $('#tanggal_akhir').val();
            table(pengiriman, ekspedisi, tgl_awal, tgl_akhir);
        })
    });
</script>
@endsection