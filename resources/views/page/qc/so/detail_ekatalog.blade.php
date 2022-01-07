@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0  text-dark">Sales Order</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                @if(Auth::user()->divisi_id == "23")
                <li class="breadcrumb-item"><a href="{{route('qc.dashboard')}}">Beranda</a></li>
                @elseif(Auth::user()->divisi_id == "2")
                <li class="breadcrumb-item"><a href="{{route('direksi.dashboard')}}">Beranda</a></li>
                @endif
                <li class="breadcrumb-item"><a href="{{route('qc.so.show')}}">Sales Order QC</a></li>
                <li class="breadcrumb-item active">Detail</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
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

    .fa-search:hover {
        color: #ADD8E6;
    }

    .fa-search:active {
        color: #808080;
    }

    .nowrap-text {
        white-space: nowrap;
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
                    <div class="card-body">
                        <h4>Info Penjualan Ekatalog</h4>
                        <?php $item = array(); ?>
                        @foreach($data as $d)
                        <div class="row">
                            <div class="col-5">
                                <div class="margin">
                                    <div><small class="text-muted">Distributor & Instansi</small></div>
                                </div>
                                <div class="margin">
                                    <b id="distributor">{{$d->customer->nama}}</b><small> (Distributor)</small>
                                </div>
                                <div class="margin">
                                    <div><b id="no_akn">{{$d->satuan}}</b></div>
                                </div>
                                <div class="margin">
                                    <div><b id="no_akn">@if($d->alamat) {{$d->alamat}} @else - @endif</b></div>
                                </div>
                                <div class="margin">
                                    <div><b id="no_akn">@if($d->provinsi_id) {{$d->Provinsi->nama}} @else - @endif</b></div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="margin">
                                    <div><small class="text-muted">No AKN</small></div>
                                    <div><b id="no_akn">{{$d->no_paket}}</b></div>
                                </div>
                                <div class="margin">
                                    <div><small class="text-muted">No SO</small></div>
                                    <div><b id="no_so">
                                            {{$d->pesanan->so}}</b></div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="margin">
                                    <div><small class="text-muted">No PO</small></div>
                                    <div><b id="no_so">{{$d->pesanan->no_po}}</b></div>
                                </div>
                                <div class="margin">
                                    <div><small class="text-muted">Batas Uji</small></div>
                                    <div class="urgent"><b>{!!$param!!}</b></div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="margin">
                                    <div><small class="text-muted">Status</small></div>
                                    <div>{!!$status!!}</div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-7">
                <div class="card">
                    <div class="card-body">
                        <!-- <div class="row">
                            <div class="col-12">
                                <span class="float-right filter">
                                    <button class="btn btn-outline-secondary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                    <form id="filter">
                                        <div class="dropdown-menu">
                                            <div class="px-3 py-3">
                                                <div class="form-group">
                                                    <label for="jenis_penjualan">Status</label>
                                                </div>
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="selesai" id="status1" name="status" />
                                                        <label class="form-check-label" for="status1">
                                                            Selesai Diperiksa
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="sebagian" id="status2" name="status" />
                                                        <label class="form-check-label" for="status2">
                                                            Sebagian Diperiksa
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="belum" id="status3" name="status" />
                                                        <label class="form-check-label" for="status3">
                                                            Belum Diperiksa
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
                                    </form>
                                </span>
                            </div>
                        </div> -->

                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table" style="text-align:center;" id="showtable">
                                        <thead>
                                            <tr>
                                                <th rowspan="2">No</th>
                                                <th rowspan="2">Nama Produk</th>
                                                <th rowspan="2">Jumlah</th>
                                                <th colspan="2">Hasil</th>
                                                <th rowspan="2">Aksi</th>
                                            </tr>
                                            <tr>
                                                <th><i class="fas fa-check ok"></i></th>
                                                <th><i class="fas fa-times nok"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- <tr>
                                        <td>1</td>
                                        <td>ELITECH MINI/MEDICAL COMPRESSOR NEBULIZER PROMIST 2</td>
                                        <td>2</td>
                                        <td>0</td>
                                        <td>0</td>
                                        <td><a type="button" class="noserishow" data-id="1"><i class="fas fa-search"></i></a></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>ELITECH ULTRASONIC POCKET DOPPLER</td>
                                        <td>5</td>
                                        <td>2</td>
                                        <td>0</td>
                                        <td><a type="button" class="noserishow" data-id="2"><i class="fas fa-search"></i></a></td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>MTB 2 MTR</td>
                                        <td>10</td>
                                        <td>5</td>
                                        <td>2</td>
                                        <td><a type="button" class="noserishow" data-id="3"><i class="fas fa-search"></i></a></td>
                                    </tr> -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-5 hide" id="noseridetail">
                <div class="card">
                    <div class="card-body">
                        @if(Auth::user()->divisi_id == "23")
                        <div class="row">
                            <div class="col-12">
                                <span class="float-right filter">
                                    <!-- <button class="btn btn-outline-info" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="cekbrg" disabled>
                                <i class="fas fa-clipboard-check"></i> Cek Barang
                            </button>
                            <div class="dropdown-menu">
                                <button class="dropdown-item" type="button"><i class="fas fa-check-circle ok"></i> Hasil OK</button>
                                <button class="dropdown-item" type="button"><i class="fas fa-times-circle nok"></i> Hasil Tidak OK</button>
                            </div> -->
                                    <!-- <button class="btn btn-outline-info" id="cekbrg" disabled>
                                <i class="fas fa-clipboard-check"></i> Cek Barang
                            </button> -->

                                    <a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr="" data-id="">
                                        <button class="btn btn-warning" id="cekbrg" disabled="true">
                                            <i class="fas fa-pencil-alt"></i> Cek Barang
                                        </button>
                                    </a>
                                </span>
                            </div>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table" style="text-align:center; width:100%" id="noseritable">
                                        <thead>
                                            @if(Auth::user()->divisi_id == "23")
                                            <th>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="check_all" id="check_all" name="check_all" />
                                                    <label class="form-check-label" for="check_all">
                                                    </label>
                                                </div>
                                            </th>
                                            @endif
                                            <th>No Seri</th>
                                            <th>Tanggal Uji</th>
                                            <th>Hasil</th>
                                        </thead>
                                        <tbody>
                                            <!-- <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input ok" type="checkbox" value="" id="" disabled />
                                            </div>
                                        </td>
                                        <td>1</td>
                                        <td>TD0015012021001</td>
                                        <td><i class="fas fa-check-circle ok"></i></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input yet nosericheck" id="" type="checkbox" />
                                            </div>
                                        </td>
                                        <td>2</td>
                                        <td>TD0015012021002</td>
                                        <td><i class="fas fa-question-circle warning"></i></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input not nosericheck" id="" type="checkbox" />
                                            </div>
                                        </td>
                                        <td>3</td>
                                        <td>TD0015012021003</td>
                                        <td><i class="fas fa-times-circle nok"></i></td>
                                        <td></td>
                                    </tr> -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="editmodal" role="dialog" aria-labelledby="editmodal" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content" style="margin: 10px">
                        <div class="modal-header bg-warning">
                            <h4 class="modal-title">Edit</h4>
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
    </div>
</section>
@stop
@section('adminlte_js')
<script>
    $(function() {
        var role = "{{Auth::user()->divisi_id}}"
        y = [];
        y = <?php echo json_encode($detail_id); ?>;

        var showtable = $('#showtable').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                'url': '/api/qc/so/detail/' + y,
                'type': 'POST',
                'datatype': 'JSON',
                'headers': {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                }
            },
            language: {
                processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
            },
            columns: [{
                data: 'DT_RowIndex',
                className: 'nowrap-text align-center',
                orderable: false,
                searchable: false
            }, {
                data: 'nama_produk',
                className: 'nowrap-text align-center',
                orderable: false,
                searchable: false
            }, {
                data: 'jumlah',
                className: 'nowrap-text align-center',
                orderable: false,
                searchable: false
            }, {
                data: 'jumlah_ok',
                className: 'nowrap-text align-center',
                orderable: false,
                searchable: false
            }, {
                data: 'jumlah_nok',
                className: 'nowrap-text align-center',
                orderable: false,
                searchable: false
            }, {
                data: 'button',
                className: 'nowrap-text align-center',
                orderable: false,
                searchable: false
            }]

        });

        $('#showtable').on('click', '.noserishow', function() {
            idtrf = '{{$d->pesanan->TFProduksi->id}}';
            idpesanan = '{{$d->pesanan->id}}';
            var data = $(this).attr('data-id');
            $('.nosericheck').prop('checked', false);
            $('#cekbrg').prop('disabled', true);
            $('input[name ="check_all"]').prop('checked', false);
            $('#noseritable').DataTable().ajax.url('/api/qc/so/seri/' + data + '/' + idtrf).load();
            $('#showtable').find('tr').removeClass('bgcolor');
            $(this).closest('tr').addClass('bgcolor');
            $('#noseridetail').removeClass('hide');

        });

        $(document).on('submit', '#form-pengujian-update', function(e) {
            e.preventDefault();
            var action = $(this).attr('action');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: action,
                data: $('#form-pengujian-update').serialize(),
                success: function(response) {
                    if (response['data'] != "error") {
                        // alert(response);
                        // console.log(response);
                        swal.fire(
                            'Berhasil',
                            'Berhasil melakukan Penambahan Data Pengujian',
                            'success'
                        );
                        $("#editmodal").modal('hide');
                        $('#noseritable').DataTable().ajax.reload();
                        $('#showtable').DataTable().ajax.reload();
                        location.reload();

                    } else if (response['data'] == "error") {
                        swal.fire(
                            'Gagal',
                            'Gagal melakukan Penambahan Data Pengujian',
                            'error'
                        );
                    }
                },
                error: function(xhr, status, error) {
                    alert($('#form-pengujian-update').serialize());
                }
            });
            return false;
        });

        var noseritable = $('#noseritable').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                'type': 'POST',
                'datatype': 'JSON',
                'url': '/api/qc/so/seri/0/0',
                'headers': {
                    'X-CSRF-TOKEN': '{{csrf_token()}}',
                }
            },
            language: {
                processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
            },
            columns: [{
                data: 'checkbox',
                className: 'nowrap-text align-center',
                orderable: false,
                searchable: false,
                visible: role == 23 ? true : false
            }, {
                data: 'seri',
                className: 'nowrap-text align-center',
                orderable: false,
                searchable: false
            }, {
                data: 'tgl_uji',
                className: 'nowrap-text align-center',
                orderable: false,
                searchable: false
            }, {
                data: 'status',
                className: 'nowrap-text align-center',
                orderable: false,
                searchable: false
            }]
        });

        function listnoseri(seri_id, produk_id, tfgbj_id) {

            $('#listnoseri').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    'type': 'POST',
                    'datatype': 'JSON',
                    'url': '/api/qc/so/seri/select/' + seri_id + '/' + produk_id + '/' + tfgbj_id,
                    'headers': {
                        'X-CSRF-TOKEN': '{{csrf_token()}}',

                    }
                },
                language: {
                    processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                },
                columns: [{
                    data: 'DT_RowIndex',
                    className: 'nowrap-text align-center',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'seri',
                    className: 'nowrap-text align-center',
                    orderable: false,
                    searchable: false
                }, ]
            });
        }
        var checkedAry = [];
        $('#noseritable').on('click', 'input[name="check_all"]', function() {
            if ($('input[name="check_all"]:checked').length > 0) {
                $('#cekbrg').prop('disabled', false);
                $('.nosericheck').prop('checked', true);
                checkedAry = []
                checkedAry.push('0');
                $('#btnedit').removeAttr('disabled');
            } else if ($('input[name="check_all"]:checked').length <= 0) {
                $('.nosericheck').prop('checked', false);
                $('#cekbrg').prop('disabled', true);
            }
        });

        $('#noseritable ').on('click', '.nosericheck', function() {
            $('#check_all').prop('checked', false);
            if ($('.nosericheck:checked').length > 0) {
                $('#cekbrg').prop('disabled', false);
                checkedAry = [];
                $.each($(".nosericheck:checked"), function() {
                    checkedAry.push($(this).closest('tr').find('.nosericheck').attr('data-id'));
                });
            } else if ($('.nosericheck:checked').length <= 0) {
                $('#cekbrg').prop('disabled', true);
            }
        });

        function max_date() {
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();
            today = yyyy + '-' + mm + '-' + dd;
            //console.log(today);
            $("#tanggal_uji").attr("max", today);
        }

        $(document).on('click', '.editmodal', function(event) {
            event.preventDefault();
            data = $(".nosericheck").data().value;
            console.log(checkedAry);
            console.log(data);
            console.log(idtrf);
            console.log(idpesanan);

            $.ajax({
                url: "/qc/so/edit/" + checkedAry + "/" + data + "/" + idtrf + "/" + idpesanan,
                beforeSend: function() {
                    $('#loader').show();
                },
                // return the result
                success: function(result) {

                    $('#editmodal').modal("show");
                    $('#edit').html(result).show();
                    listnoseri(checkedAry, data, idtrf);
                    max_date();
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

        $(document).on('change', 'input[type="radio"][name="cek"]', function(event) {
            if ($(this).val() != "") {
                if ($('#tanggal_uji').val() != "") {
                    $('#btnsimpan').removeAttr('disabled');
                } else {
                    $('#btnsimpan').attr('disabled', true);
                }
            } else {
                $('#btnsimpan').attr('disabled', true);
            }
        });
        $(document).on('change', 'input[type="date"][name="tanggal_uji"]', function(event) {
            if ($(this).val() != "") {

                if ($("input[name=cek][type='radio']").prop("checked")) {
                    $('#btnsimpan').removeAttr('disabled');
                } else {
                    $('#btnsimpan').attr('disabled', true);
                }
            } else {
                $('#btnsimpan').attr('disabled', true);
            }
        });

        // $(document).on('submit', '#edit_qc', function(e) {
        //     e.preventDefault();
        //     var action = $(this).attr('data-attr');
        //     $.ajax({
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         type: "POST",
        //         url: action,
        //         data: $('#edit_qc').serialize(),
        //         success: function(response) {
        //             console.log(response);
        //             alert(response.data);
        //         },
        //         error: function(xhr, status, error) {
        //             alert($('#edit_qc').serialize());
        //         }
        //     });
        //     return false;
        // });

        $('#filter').submit(function() {
            var values_spa = [];
            $("input:checked").each(function() {
                values_spa.push($(this).val());
            });
            if (values_spa != 0) {
                var x = values_spa;

            } else {
                var x = ['semua'];
            }

            console.log(x);
            //      $('#ekatalogtable').DataTable().ajax.url('/penjualan/penjualan/ekatalog/data/' + x).load();
            return false;

        });
    })
</script>
@stop