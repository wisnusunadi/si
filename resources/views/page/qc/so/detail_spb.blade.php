@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
    <div class="d-flex bd-highlight">
        <div class="p-2 flex-grow-1 bd-highlight">
            <h1 class="text-dark">Sales Order</h1>
        </div>
        <div class="p-2 bd-highlight">
            <ol class="breadcrumb float-sm-right">
                @if (Auth::user()->divisi_id == '23')
                    <li class="breadcrumb-item"><a href="{{ route('qc.dashboard') }}">Beranda</a></li>
                @elseif(Auth::user()->divisi_id == '2')
                    <li class="breadcrumb-item"><a href="{{ route('direksi.dashboard') }}">Beranda</a></li>
                @endif
                <li class="breadcrumb-item"><a href="{{ route('qc.so.show') }}">Sales Order QC</a></li>
                <li class="breadcrumb-item active">Detail</li>
            </ol>
        </div>
    </div>
@stop

@section('adminlte_css')
    <style>
        .alert-danger {
            color: #a94442;
            background-color: #f2dede;
            border-color: #ebccd1;
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

        @media screen and (min-width: 993px) {

            body {
                font-size: 14px;
            }

            h4 {
                font-size: 24px;
            }

            #detailmodal {
                font-size: 14px;
            }

            .btn {
                font-size: 14px;
            }

            .cust {
                max-width: 40%;
            }
        }

        @media screen and (max-width: 992px) {

            body {
                font-size: 12px;
            }

            h4 {
                font-size: 22px;
            }

            #detailmodal {
                font-size: 12px;
            }

            .btn {
                font-size: 12px;
            }

            .collapsable {
                display: none;
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
                            <h4>Info Penjualan SPB</h4>
                            @foreach ($data as $d)
                                <div class="row d-flex justify-content-between">
                                    <div class="p-2 cust">
                                        <div class="margin">
                                            <div><small class="text-muted">Customer</small></div>
                                        </div>
                                        <div class="margin">
                                            <b id="distributor">{{ $d->customer->nama }}</b>
                                        </div>
                                        <div class="margin">
                                            <b id="no_akn">{{ $d->customer->alamat }}</b>
                                        </div>
                                        <div class="margin">
                                            <b id="no_akn">{{ $d->customer->provinsi->nama }}</b>
                                        </div>
                                        <div class="margin">
                                            <b id="distributor">{{ $d->customer->telp }}</b>
                                        </div>
                                    </div>
                                    <div class="p-2">
                                        <div class="margin">
                                            <div><small class="text-muted">No SO</small></div>
                                            <div><b id="no_so">{{ $d->pesanan->so }}</b></div>
                                        </div>
                                        <div class="margin">
                                            <div><small class="text-muted">Status Transfer</small></div>
                                            <div class="align-center">{!! $status !!}</div>
                                        </div>
                                    </div>
                                    <div class="p-2">
                                        <div class="margin">
                                            <div><small class="text-muted">No PO</small></div>
                                            <div><b id="no_so">{{ $d->pesanan->no_po }}</b></div>
                                        </div>
                                        <div class="margin">
                                            <div><small class="text-muted">Tanggal PO</small></div>
                                            <div><b id="no_so">{{ $d->pesanan->tgl_po }}</b></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @if ($d->ket != '')
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-triangle"></i> <strong>Catatan: </strong>{{ $d->ket }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-7">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table" style="text-align:center;" id="showtable">
                                    <thead>
                                        <tr>
                                            <th rowspan="2">No</th>
                                            <th rowspan="2">Nama Produk</th>
                                            <th rowspan="2">Jumlah</th>
                                            <th colspan="2" class="collapsable">Hasil</th>
                                            <th rowspan="2">Aksi</th>
                                        </tr>
                                        <tr>
                                            <th><i class="fas fa-check ok"></i></th>
                                            <th><i class="fas fa-times nok"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-5 hide" id="noseridetail">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" id="pills-home-tab" data-toggle="pill"
                                        data-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
                                        aria-selected="true"></a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="pills-profile-tab" data-toggle="pill"
                                        data-target="#pills-profile" type="button" role="tab"
                                        aria-controls="pills-profile" aria-selected="false">Riwayat
                                        Nomor Seri Gagal Uji</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                    aria-labelledby="pills-home-tab">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="d-flex bd-highlight">
                                                <div class="p-2 flex-grow-1 bd-highlight">
                                                    <span class="filter hide" id="filter_form">
                                                        <button class="btn btn-outline-secondary dropdown-toggle "
                                                            type="button" data-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false" id="filterpenjualan">
                                                            <i class="fas fa-filter"></i> Filter
                                                        </button>
                                                        <div class="dropdown-menu" aria-labelledby="filterpenjualan">
                                                            <form class="px-4" style="white-space:nowrap;">
                                                                <div class="dropdown-header">
                                                                    Status Pengujian
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="form-check">
                                                                        <input type="radio" class="form-check-input"
                                                                            id="dropdownStatus1" value="semua"
                                                                            name='filter' />
                                                                        <label class="form-check-label"
                                                                            for="dropdownStatus1">
                                                                            Semua
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="form-check">
                                                                        <input type="radio" class="form-check-input"
                                                                            id="dropdownStatus2" value="belum"
                                                                            name='filter' />
                                                                        <label class="form-check-label"
                                                                            for="dropdownStatus2">
                                                                            Belum di Uji
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="form-check">
                                                                        <input type="radio" class="form-check-input"
                                                                            id="dropdownStatus3" value="sudah"
                                                                            name='filter' />
                                                                        <label class="form-check-label"
                                                                            for="dropdownStatus3">
                                                                            Sudah di Uji
                                                                        </label>
                                                                    </div>
                                                                </div>

                                                                <div class="kalibrasi-hide hide">
                                                                    <div class="dropdown-header">
                                                                        Status Kalibrasi
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input type="radio" class="form-check-input"
                                                                                id="dropdownStatus1"
                                                                                value="semuaKalibrasi" name='filter' />
                                                                            <label class="form-check-label"
                                                                                for="dropdownStatus1">
                                                                                Semua
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input type="radio" class="form-check-input"
                                                                                id="dropdownStatus2"
                                                                                value="prosesKalibrasi" name='filter' />
                                                                            <label class="form-check-label"
                                                                                for="dropdownStatus2">
                                                                                Proses Kalibrasi
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input type="radio" class="form-check-input"
                                                                                id="dropdownStatus2"
                                                                                value="tidakLolosKalibrasi"
                                                                                name='filter' />
                                                                            <label class="form-check-label"
                                                                                for="dropdownStatus2">
                                                                                Tidak Lolos Kalibrasi
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input type="radio" class="form-check-input"
                                                                                id="dropdownStatus3"
                                                                                value="lolosKalibrasi" name='filter' />
                                                                            <label class="form-check-label"
                                                                                for="dropdownStatus3">
                                                                                Lolos Kalibrasi
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </span>
                                                </div>
                                                <div class="p-2 bd-highlight">
                                                    @if (Auth::user()->divisi_id == '23')
                                                        <span class="filter">
                                                            <a data-toggle="modal" data-target="#editmodal"
                                                                class="editmodal" data-attr="" data-id="">
                                                                <button class="btn btn-warning" id="cekbrg"
                                                                    disabled="true">
                                                                    <i class="fas fa-pencil-alt"></i> Cek Barang
                                                                </button>
                                                            </a>
                                                        </span>
                                                        <span class="filter">

                                                            <button class="btn btn-info btnKalibrasi hide"
                                                                disabled="true">
                                                                Kalibrasi
                                                            </button>

                                                        </span>
                                                    @endif
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                    <div class="row hide" id="produk_detail">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table" style="text-align:center; width:100%"
                                                    id="noseritable">
                                                    <thead>
                                                        <tr>
                                                            <th>
                                                                <div class="form-check cek_header">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="check_all" id="check_all"
                                                                        name="check_all" />
                                                                    <label class="form-check-label" for="check_all">
                                                                    </label>
                                                                </div>
                                                            </th>
                                                            <th>No Seri</th>
                                                            <th>Tanggal Uji</th>
                                                            <th>Hasil</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row hide" id="part_detail">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table" style="text-align:center; width:100%"
                                                    id="parttable">
                                                    <thead>
                                                        <tr>
                                                            <th rowspan="2">Tanggal Uji</th>
                                                            <th colspan="2">Jumlah</th>
                                                        </tr>
                                                        <tr>
                                                            <th><i class="fas fa-check-circle" style="color:green;"></i>
                                                            </th>
                                                            <th><i class="fas fa-times-circle" style="color:red;"></i>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                                    aria-labelledby="pills-profile-tab">
                                    <div class="scrollable">
                                        <table class="table" id="noseripengganti">
                                            <thead>
                                                <tr>
                                                    <th>No Seri</th>
                                                    <th>Tanggal Kirim</th>
                                                    <th>Pengirim</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>123</td>
                                                    <td>123</td>
                                                    <td>QC</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="editmodal" role="dialog" aria-labelledby="editmodal" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
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
                <div class="modal fade" id="modalKalibrasi" role="dialog" aria-labelledby="modelTitleId"
                    aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-info">
                                <h5 class="modal-title">Kalibrasi</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" id="kalibrasi">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('page/gbj/modalserireworks/detailnoseri')

@stop
@section('adminlte_js')
    <script>
        $(function() {
            var divisi = '{{ Auth::user()->divisi_id }}';
            var showtable = $('#showtable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    'type': 'POST',
                    'datatype': 'JSON',
                    'url': '/api/qc/so/detail/' + '{{ $id }}',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
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
                    className: 'nowrap-text align-center collapsable',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'jumlah_nok',
                    className: 'nowrap-text align-center collapsable',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'button',
                    className: 'nowrap-text align-center',
                    orderable: false,
                    searchable: false
                }]

            });
            var datajenis = "";
            var dataid = "";

            $('#showtable').on('click', '.noserishow', function() {
                idpesanan = '{{ $id }}';
                $(".cek_header").css("display", "block");
                $('input[type=radio][name=filter]').prop('checked', false);
                dataid = $(this).attr('data-id');
                var datacount = $(this).attr('data-count');
                datajenis = $(this).attr('data-jenis');
                $('.nosericheck').prop('checked', false);
                $('#cekbrg').prop('disabled', true);
                $('input[name ="check_all"]').prop('checked', false);
                let datakalibrasi = $(this).attr('data-kalibrasi');
                if (datakalibrasi == 'true') {
                    $('.btnKalibrasi').removeClass('hide');
                    $('.kalibrasi-hide').removeClass('hide');
                    $('#pills-home-tab').text('Pengujian dan Kalibrasi');
                } else {
                    $('.btnKalibrasi').addClass('hide');
                    $('.kalibrasi-hide').addClass('hide');
                    $('#pills-home-tab').text('Pengujian');
                }
                if (datajenis == "produk") {
                    $('#filter_form').removeClass('hide')
                    $('#produk_detail').removeClass('hide');
                    $('#part_detail').addClass('hide');
                    $('#noseritable').DataTable().ajax.url('/api/qc/so/seri/belum/' + dataid + '/' +
                        '{{ $id }}').load();
                    $('#pills-profile-tab').removeClass('hide');
                    $('#pills-home-tab').removeClass('hide');
                    $.ajax({
                        type: "GET",
                        url: "/api/qc/so/seri_riwayat_ganti/" + dataid + '/' +
                            '{{ $id }}',
                        success: function(data) {
                            $('#noseripengganti').DataTable().clear().draw();
                            $.each(data, function(index, value) {
                                $('#noseripengganti').DataTable().row.add([
                                    value.noseri,
                                    value.tgl_kirim,
                                    value.state == 'qc' ? 'Gagal Pengujian QC' :
                                    'Gagal Kalibrasi'
                                ]).draw(false);
                            });
                        }
                    });
                    // if (datacount == 0) {
                    //     $('#noseritable').DataTable().column(0).visible(false);
                    // } else {
                    //     if (divisi == "23") {
                    //         $('#noseritable').DataTable().column(0).visible(true);
                    //     } else {
                    //         $('#noseritable').DataTable().column(0).visible(false);
                    //     }
                    // }

                } else {
                    $('#filter_form').addClass('hide')
                    $('#produk_detail').addClass('hide');
                    $('#part_detail').removeClass('hide');
                    $('#pills-profile-tab').addClass('hide');
                    $('#pills-home-tab').addClass('hide');
                    listcekpart(dataid);
                    if (datacount == 0) {
                        $('#cekbrg').prop('disabled', true);
                    } else {
                        $('#cekbrg').prop('disabled', false);
                    }
                }

                $('#showtable').find('tr').removeClass('bgcolor');
                $(this).closest('tr').addClass('bgcolor');
                $('#noseridetail').removeClass('hide');
            });

            $(document).on('submit', '#form-pengujian-update', function(e) {
                $('#btnsimpan').attr('disabled', true);
                var showLoading = swal.fire({
                    title: 'Sedang Proses',
                    html: 'Loading...',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading()
                    }
                });
                if (datajenis == "produk") {
                    e.preventDefault();
                    var no_seri = $('#listnoseri').DataTable()
                    var data = no_seri.rows().data().toArray();

                    var tanggal_uji = $('input[type="date"][name="tanggal_uji"]').val();
                    var cek = $('input[type="radio"][name="cek"]:checked').val();

                    var action = $(this).attr('action');
                    // console.log(action)
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "PUT",
                        url: action,
                        data: {
                            tanggal_uji: tanggal_uji,
                            cek: cek,
                            noseri_id: data,
                        },
                        dataType: 'JSON',
                        beforeSend: function() {
                            swal.fire({
                                title: 'Sedang Proses',
                                html: 'Loading...',
                                allowOutsideClick: false,
                                showConfirmButton: false,
                                willOpen: () => {
                                    Swal.showLoading()
                                }
                            })
                        },
                        success: function(response) {
                            console.log(response);
                            if (response['data'] == "success") {
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
                            } else {
                                console.log(response['data']);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr);
                        }
                    });
                } else if (datajenis == "part") {
                    e.preventDefault();
                    var action = $(this).attr('action');
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "PUT",
                        url: action,
                        data: $('#form-pengujian-update').serialize(),
                        beforeSend: function() {
                            swal.fire({
                                title: 'Sedang Proses',
                                html: 'Loading...',
                                allowOutsideClick: false,
                                showConfirmButton: false,
                                willOpen: () => {
                                    Swal.showLoading()
                                }
                            })
                        },
                        success: function(response) {
                            console.log(response);
                            if (response['data'] == "success") {
                                swal.fire(
                                    'Berhasil',
                                    'Berhasil melakukan Penambahan Data Pengujian',
                                    'success'
                                );
                                $("#editmodal").modal('hide');
                                // $('#noseritable').DataTable().ajax.reload();
                                // $('#parttable').DataTable().ajax.reload();
                                // $('#showtable').DataTable().ajax.reload();

                                location.reload();
                            } else if (response['data'] == "error") {
                                swal.fire(
                                    'Gagal',
                                    'Gagal melakukan Penambahan Data Pengujian',
                                    'error'
                                );
                            } else {
                                console.log(response['data']);
                            }
                        },
                        error: function(xhr, status, error) {
                            alert($('#form-customer-update').serialize());
                        }
                    });
                }
                return false;
            });

            $(document).on('submit', '#form-kalibrasi-update', function(e) {
                $('#btnsimpanKalibrasi').attr('disabled', true);
                var showLoading = swal.fire({
                    title: 'Sedang Proses',
                    html: 'Loading...',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading()
                    }
                });
                if (datajenis == "produk") {
                    e.preventDefault();
                    var noseri_id = $('#listnoserikalibrasi').DataTable().rows().data().toArray();

                    var tanggal_kirim = $('input[type="date"][name="tanggal_kirim"]').val();
                    var pesanan_id = $('input[name="pesanan_id"]').val();
                    var detail_pesanan_produk_id = $('input[name="detail_pesanan_produk_id"]').val();

                    var action = $(this).attr('action');
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        url: action,
                        data: {
                            pesanan_id,
                            tanggal_kirim,
                            detail_pesanan_produk_id,
                            noseri_id,
                        },
                        dataType: 'JSON',
                        beforeSend: function() {
                            swal.fire({
                                title: 'Sedang Proses',
                                html: 'Loading...',
                                allowOutsideClick: false,
                                showConfirmButton: false,
                                willOpen: () => {
                                    Swal.showLoading()
                                }
                            })
                        },
                        success: function(response) {
                            console.log("sukses", response);
                            if (response.status == 200) {
                                swal.fire(
                                    'Berhasil',
                                    'Berhasil melakukan Penambahan Data Kalibrasi',
                                    'success'
                                );
                                $("#modalKalibrasi").modal('hide');
                                $('#noseritable').DataTable().ajax.reload();
                                $('#showtable').DataTable().ajax.reload();
                                location.reload();

                            } else if (response['data'] == "error") {
                                swal.fire(
                                    'Gagal',
                                    'Gagal melakukan Penambahan Data Kalibrasi',
                                    'error'
                                );
                            } else {
                                console.log(response['data']);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr);
                        }
                    });
                } else if (datajenis == "part") {
                    e.preventDefault();
                    var action = $(this).attr('action');
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        url: action,
                        data: $('#form-kalibrasi-update').serialize(),
                        beforeSend: function() {
                            swal.fire({
                                title: 'Sedang Proses',
                                html: 'Loading...',
                                allowOutsideClick: false,
                                showConfirmButton: false,
                                willOpen: () => {
                                    Swal.showLoading()
                                }
                            })
                        },
                        success: function(response) {
                            console.log("sukses", response);
                            if (response.status == 200) {
                                swal.fire(
                                    'Berhasil',
                                    'Berhasil melakukan Penambahan Data Kalibrasi',
                                    'success'
                                );
                                $("#modalKalibrasi").modal('hide');
                                // $('#noseritable').DataTable().ajax.reload();
                                // $('#parttable').DataTable().ajax.reload();
                                // $('#showtable').DataTable().ajax.reload();

                                location.reload();
                            } else if (response['data'] == "error") {
                                swal.fire(
                                    'Gagal',
                                    'Gagal melakukan Penambahan Data Kalibrasi',
                                    'error'
                                );
                            } else {
                                console.log(response['data']);
                            }
                        },
                        error: function(xhr, status, error) {
                            alert($('#form-customer-update').serialize());
                            swal.fire(
                                'Gagal',
                                'Gagal melakukan Penambahan Data Kalibrasi',
                                'error'
                            );
                        }
                    });
                }
                return false;
            });

            var noseritable = $('#noseritable').DataTable({
                destroy: true,
                processing: true,
                serverSide: false,
                autowidth: true,
                ajax: {
                    'type': 'post',
                    'datatype': 'JSON',
                    'url': '/api/qc/so/seri/semua/0/0',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
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
                    // visible: divisi == '23' ? true : false
                }, {
                    data: 'seri',
                    className: 'nowrap-text align-center',
                    orderable: true,
                    searchable: true
                }, {
                    data: 'tgl_uji',
                    className: 'nowrap-text align-center collapsable',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'status',
                    className: 'nowrap-text align-center',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'status_seri',
                    className: 'nowrap-text align-center',
                    searchable: false
                }]
            });

            function listnoseri(seri_id, produk_id, pesanan_id) {
                const table = $('#noseritable').DataTable();
                let dataChecked = []
                // cek apakah ada data yang dipilih dalam datatable didalam checkbox
                table.$('input[type="checkbox"]').each(function() {
                    if (this.checked) {
                        var rowIndex = table.row($(this).closest('tr')).index();
                        var data = table.row(rowIndex).data();
                        checkedAry.forEach(function(item) {
                            if (item == data.noseri_id) {
                                dataChecked.push(data);
                            }
                        });
                    }
                });

                $('#listnoseri').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: false,
                    autowidth: true,
                    data: dataChecked,
                    language: {
                        processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                    },
                    columns: [{
                        data: 'DT_RowIndex',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    }, {
                        data: 'seri',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'noseri_id',
                        className: 'nowrap-text align-center hide',
                        orderable: false,
                        searchable: false,
                    }, {
                        data: 'detail_pesanan_produk_id',
                        className: 'nowrap-text align-center hide',
                        orderable: false,
                        searchable: false,
                    }, ]
                })

            }

            function listnoserikalibrasi() {
                const table = $('#noseritable').DataTable();
                let dataChecked = []
                // cek apakah ada data yang dipilih dalam datatable didalam checkbox
                table.$('input[type="checkbox"]').each(function() {
                    if (this.checked) {
                        var rowIndex = table.row($(this).closest('tr')).index();
                        var data = table.row(rowIndex).data();
                        checkedKalibrasi.forEach(function(item) {
                            if (item == data.noseri_id) {
                                dataChecked.push(data);
                            }
                        });
                    }
                })

                $('#listnoserikalibrasi').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: false,
                    autowidth: true,
                    data: dataChecked,
                    language: {
                        processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                    },
                    columns: [{
                        data: null,
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false,
                        // nomor index
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    }, {
                        data: 'seri',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'noseri_id',
                        className: 'nowrap-text align-center hide',
                        orderable: false,
                        searchable: false,
                    }, {
                        data: 'detail_pesanan_produk_id',
                        className: 'nowrap-text align-center hide',
                        orderable: false,
                        searchable: false,
                    }, ]
                })

            }

            function listcekpart(part_id) {
                $('#parttable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: false,
                    ajax: {
                        'type': 'POST',
                        'datatype': 'JSON',
                        'url': '/api/qc/so/part/' + part_id,
                        'headers': {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    },
                    language: {
                        processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                    },
                    columns: [{
                        data: 'tanggal_uji',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'jumlah_ok',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false,
                    }, {
                        data: 'jumlah_nok',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false,
                    }, ]
                });
            }

            var checkedAry = [];
            var checkedKalibrasi = [];
            $('#noseritable').on('click', 'input[name="check_all"]', function() {
                var rows = $('#noseritable').DataTable().rows().nodes();
                // Check/uncheck checkboxes for all rows in the table
                if ($('input[name="check_all"]:checked').length > 0) {
                    if ($('input[type=radio][name=filter]:checked').val() != 'sudah') {
                        $('#cekbrg').prop('disabled', false);
                    }
                    $('.nosericheck', rows).prop('checked', true);
                    checkedAry = [];
                    checkedKalibrasi = [];
                    $.each($(".nosericheck:checked", rows), function() {
                        let statusSeri = $(this).closest('tr').find('td').eq(4);
                        if (statusSeri.text() != 'Belum Di Transfer') {
                            checkedAry.push($(this).closest('tr').find('.nosericheck').attr(
                                'data-id'));
                        }

                        let kalibrasi = $(this).closest('tr').find('.nosericheck').attr(
                            'data-kalibrasi');
                        if (kalibrasi) {
                            if (statusSeri.text() == 'Belum Di Transfer') {
                                checkedKalibrasi.push(kalibrasi);
                                checkedAry.push($(this).closest('tr').find('.nosericheck').attr(
                                    'data-id'));
                            }
                        }
                    });
                    if (checkedKalibrasi.length > 0) {
                        $('.btnKalibrasi').removeAttr('disabled');
                    } else {
                        $('.btnKalibrasi').attr('disabled', true);
                    }

                    if (checkedAry.length > 0) {
                        $('#cekbrg').removeAttr('disabled');
                    } else {
                        $('#cekbrg').attr('disabled', true);
                    }
                } else if ($('input[name="check_all"]:checked').length <= 0) {
                    $('.nosericheck', rows).prop('checked', false);
                    $('#cekbrg').prop('disabled', true);
                    $('.btnKalibrasi').attr('disabled', true);
                }
            });

            $('#noseritable').on('click', '.nosericheck', function() {
                var rows = $('#noseritable').DataTable().rows().nodes();
                $('#check_all').prop('checked', false);
                if ($('.nosericheck:checked', rows).length > 0) {
                    if ($('input[type=radio][name=filter]:checked').val() != 'sudah') {
                        $('#cekbrg').prop('disabled', false);
                    }
                    checkedAry = [];
                    checkedKalibrasi = [];
                    $.each($(".nosericheck:checked", rows), function() {
                        let statusSeri = $(this).closest('tr').find('td').eq(4);

                        if (statusSeri.text() != 'Belum Di Transfer') {
                            checkedAry.push($(this).closest('tr').find('.nosericheck').attr(
                                'data-id'));
                        }

                        let kalibrasi = $(this).closest('tr').find('.nosericheck').attr(
                            'data-kalibrasi');
                        if (kalibrasi) {
                            // jika status 'Belum Di Transfer' push data
                            if (statusSeri.text() == 'Belum Di Transfer') {
                                checkedKalibrasi.push(kalibrasi);
                                checkedAry.push($(this).closest('tr').find('.nosericheck').attr(
                                    'data-id'));
                            }
                        }
                    });

                    if (checkedKalibrasi.length > 0) {
                        $('.btnKalibrasi').removeAttr('disabled');
                    } else {
                        $('.btnKalibrasi').attr('disabled', true);
                    }

                    if (checkedAry.length > 0) {
                        $('#cekbrg').removeAttr('disabled');
                    } else {
                        $('#cekbrg').attr('disabled', true);
                    }
                } else if ($('.nosericheck:checked', rows).length <= 0) {
                    $('#cekbrg').prop('disabled', true);
                    $('.btnKalibrasi').attr('disabled', true);
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
                $("#tanggal_kirim").attr("max", today);
            }

            $(document).on('click', '.editmodal', function(event) {

                var href = "";
                event.preventDefault();
                if (datajenis == "produk") {
                    data = $(".nosericheck").data().value;
                } else {
                    data = dataid
                }
                // console.log(checkedAry);
                // console.log(data);
                // console.log(idpesanan);
                if (datajenis == "produk") {
                    href = "/qc/so/edit/" + datajenis + '/' + data + "/" + '{{ $id }}';
                } else {
                    href = "/qc/so/edit/" + datajenis + '/' + dataid + "/" + '{{ $id }}';
                }

                var produk_id = null

                if (datajenis == "produk") {
                    produk_id = data
                } else {
                    produk_id = dataid
                }

                var datatableOld = $('#showtable').DataTable();
                var rows = datatableOld.$('tr.bgcolor').closest('tr');
                var dataHeader = datatableOld.row(rows).data();
                var noSO = $('#no_so_pesanan').text();



                $('#edit').html(`
                <form method="PUT" action="/api/qc/so/create/${datajenis}/{{ $id }}/${produk_id}" id="form-pengujian-update">
                    @csrf
                    <div class="row d-flex justify-content-center">
                        <div class="col-lg-4 col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Info Produk</h5>
                                </div>
                                ${datajenis == "produk" ? `
                                                                                                                <div class="card-body">
                                                                                                                    <div class="margin">
                                                                                                                        <input type="hidden" name="user_idd" value="{{ Auth::user()->id }}">
                                                                                                                        <div><small class="text-muted">Nama Produk</small></div>
                                                                                                                        <div><b>${dataHeader?.nama_produk}</b></div>
                                                                                                                    </div>
                                                                                                                    <div class="margin">
                                                                                                                        <div><small class="text-muted">No SO</small></div>
                                                                                                                        <div><b>${noSO}</b></div>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                                ` : `
                                                                                                                <div class="card-body">
                                                                                                                    <div class="margin">
                                                                                                                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                                                                                                        <div><small class="text-muted">Nama Part</small></div>
                                                                                                                        <div><b>${dataHeader?.nama_produk}</b></div>
                                                                                                                    </div>
                                                                                                                    <div class="margin">
                                                                                                                        <div><small class="text-muted">No SO</small></div>
                                                                                                                        <div><b>${noSO}</b></div>
                                                                                                                    </div>
                                                                                                                    <div class="margin">
                                                                                                                        <div><small class="text-muted">Jumlah</small></div>
                                                                                                                        <div><b>${dataHeader?.jumlah}</b></div>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                                `}
                            </div>
                        </div>
                        <div class="col-lg-8 col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-horizontal">
                                        @if (session()->has('error'))
                                        <div class="alert alert-danger alert-dismissible fade show col-12" role="alert">
                                            <strong>Gagal menambahkan!</strong> Periksa
                                            kembali data yang diinput
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        @elseif (session()->has('success'))
                                        <div class="alert alert-success alert-dismissible fade show col-12" role="alert">
                                            <strong>Berhasil menambahkan data</strong>,
                                            Terima kasih
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        @endif
                                        <div class="form-group row">
                                            <label for="" class="col-form-label col-5" style="text-align: right">Tanggal Uji</label>
                                            <div class="col-5">
                                                <input type="date" class="form-control  col-form-label" name="tanggal_uji" id="tanggal_uji">
                                            </div>
                                        </div>
                                        ${datajenis == "produk" ? `
                                                                                                                            <div class="form-group row">
                                                                                                                                <label for="" class="col-form-label col-5" style="text-align: right">Hasil Cek</label>
                                                                                                                                <div class="col-5 col-form-label">
                                                                                                                                    <div class="form-check form-check-inline">
                                                                                                                                        <input class="form-check-input" type="radio" name="cek" id="yes" value="ok" />
                                                                                                                                        <label class="form-check-label" for="yes"><i class="fas fa-check-circle ok"></i> OK</label>
                                                                                                                                    </div>
                                                                                                                                    <div class="form-check form-check-inline">
                                                                                                                                        <input class="form-check-input" type="radio" name="cek" id="no" value="nok" />
                                                                                                                                        <label class="form-check-label" for="no"><i class="fas fa-times-circle nok"></i> Tidak OK</label>
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                            <h5>No Seri </h5>
                                                                                                                            <div class="form-group row">
                                                                                                                                <div class="table-responsive overflowy">
                                                                                                                                    <table class="table table-striped align-center" id="listnoseri" style="width:100%;">
                                                                                                                                        <thead>
                                                                                                                                            <tr>
                                                                                                                                                <th>No</th>
                                                                                                                                                <th>No Seri</th>
                                                                                                                                                <th>No Seri ID</th>
                                                                                                                                                <th>No Detail Produk ID</th>
                                                                                                                                            </tr>
                                                                                                                                        </thead>
                                                                                                                                        <tbody>
                                                                                                                                        </tbody>
                                                                                                                                    </table>
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                            ` : `
                                                                                                                            <div class="form-group row">
                                                                                                                                <label for="" class="col-form-label col-5" style="text-align: right">Jumlah OK</label>
                                                                                                                                <div class="col-3">
                                                                                                                                    <input type="number" class="form-control  col-form-label" name="jumlah_ok" id="jumlah_ok">
                                                                                                                                    <div class="invalid-feedback" id="msgjumlah_ok"></div>
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                            <div class="form-group row">
                                                                                                                                <label for="" class="col-form-label col-5" style="text-align: right">Jumlah NOK</label>
                                                                                                                                <div class="col-3">
                                                                                                                                    <input type="number" class="form-control  col-form-label" name="jumlah_nok" id="jumlah_nok">
                                                                                                                                    <div class="invalid-feedback" id="msgjumlah_nok"></div>
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                            `}
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <span class="float-left">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                                    </span>
                                    <span class="float-right">
                                        <button type="submit" class="btn btn-warning " id="btnsimpan" disabled>Simpan</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                `).show();
                $('#editmodal').modal("show");

                listnoseri(data)
                max_date();

            });

            $(document).on('click', '.btnKalibrasi', function(event) {
                var href = "";
                event.preventDefault();
                if (datajenis == "produk") {
                    data = $(".nosericheck").data().value;
                } else {
                    data = dataid
                }
                console.log(checkedKalibrasi);
                console.log(data);
                console.log(idpesanan);
                if (datajenis == "produk") {
                    href = "/qc/so/kalibrasi/" + datajenis + '/' + data + "/" + '{{ $id }}';
                } else {
                    href = "/qc/so/kalibrasi/" + datajenis + '/' + dataid + "/" + '{{ $id }}';
                }

                var datatableOld = $('#showtable').DataTable();
                var rows = datatableOld.$('tr.bgcolor').closest('tr');
                var dataHeader = datatableOld.row(rows).data();

                $('#modalKalibrasi').modal("show");
                $('#kalibrasi').html(`
                <form method="POST" action="/api/qc/kalibrasi/store" id="form-kalibrasi-update">
                    <div class="row d-flex justify-content-center">
                        <div class="col-lg-4 col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Info Produk</h5>
                                </div>
                               ${datajenis == 'produk' ? `
                                                                                                                    <div class="card-body">
                                                                                                                        <div class="margin">
                                                                                                                            <input type="hidden" name="user_idd" value="{{ Auth::user()->id }}">
                                                                                                                            <div><small class="text-muted">Nama Produk</small></div>
                                                                                                                            <div><b>${dataHeader.nama_produk}</b></div>
                                                                                                                        </div>
                                                                                                                        <div class="margin">
                                                                                                                            <div><small class="text-muted">No SO</small></div>
                                                                                                                            <div><b>{{ $d->pesanan->so }}</b></div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                    ` : `
                                                                                                                    <div class="card-body">
                                                                                                                        <div class="margin">
                                                                                                                            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                                                                                                            <div><small class="text-muted">Nama Part</small></div>
                                                                                                                            <div><b></b></div>
                                                                                                                        </div>
                                                                                                                        <div class="margin">
                                                                                                                            <div><small class="text-muted">No SO</small></div>
                                                                                                                            <div><b>{{ $d->pesanan->so }}</b></div>
                                                                                                                        </div>
                                                                                                                        <div class="margin">
                                                                                                                            <div><small class="text-muted">Jumlah</small></div>
                                                                                                                            <div><b></b></div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                    `}
                            </div>
                        </div>
                        <div class="col-lg-8 col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-horizontal">
                                        @if (session()->has('error'))
                                        <div class="alert alert-danger alert-dismissible fade show col-12" role="alert">
                                            <strong>Gagal menambahkan!</strong> Periksa
                                            kembali data yang diinput
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        @elseif (session()->has('success'))
                                        <div class="alert alert-success alert-dismissible fade show col-12" role="alert">
                                            <strong>Berhasil menambahkan data</strong>,
                                            Terima kasih
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        @endif
                                        <input type="hidden" name="pesanan_id" value="{{ $id }}">
                                        <input type="hidden" name="detail_pesanan_produk_id" value="${data}">
                                        <div class="form-group row">
                                            <label for="" class="col-form-label col-5" style="text-align: right">Tanggal Setor</label>
                                            <div class="col-5">
                                                <input type="date" class="form-control  col-form-label" name="tanggal_kirim" id="tanggal_kirim">
                                            </div>
                                        </div>
                                       ${datajenis == "produk" ? `
                                                                                                                            <h5>No Seri </h5>
                                                                                                                            <div class="form-group row">
                                                                                                                                <div class="table-responsive overflowy">
                                                                                                                                    <table class="table table-striped align-center" id="listnoserikalibrasi" style="width:100%;">
                                                                                                                                        <thead>
                                                                                                                                            <tr>
                                                                                                                                                <th>No</th>
                                                                                                                                                <th>No Seri</th>
                                                                                                                                                <th>No Seri ID</th>
                                                                                                                                                <th>No Detail Produk ID</th>
                                                                                                                                            </tr>
                                                                                                                                        </thead>
                                                                                                                                        <tbody></tbody>
                                                                                                                                    </table>
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                            ` : `
                                                                                                                            <div class="form-group row">
                                                                                                                                <label for="" class="col-form-label col-5" style="text-align: right">Jumlah OK</label>
                                                                                                                                <div class="col-3">
                                                                                                                                    <input type="number" class="form-control  col-form-label" name="jumlah_ok" id="jumlah_ok">
                                                                                                                                    <div class="invalid-feedback" id="msgjumlah_ok"></div>
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                            <div class="form-group row">
                                                                                                                                <label for="" class="col-form-label col-5" style="text-align: right">Jumlah NOK</label>
                                                                                                                                <div class="col-3">
                                                                                                                                    <input type="number" class="form-control  col-form-label" name="jumlah_nok" id="jumlah_nok">
                                                                                                                                    <div class="invalid-feedback" id="msgjumlah_nok"></div>
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                            `}
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <span class="float-left">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                                    </span>
                                    <span class="float-right">
                                        <button type="submit" class="btn btn-info " id="btnsimpanKalibrasi" disabled>Simpan</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                `).show();
                listnoserikalibrasi()
                max_date();
            });

            $(document).on('change', 'input[type="radio"][name="cek"]:checked', function(event) {
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

            const cekvalidasipart = () => {
                if (($("input[name='tanggal_uji']").val() != "") && ($("input[name='jumlah_ok']").val() != "" &&
                        !$("input[name='jumlah_ok']").hasClass(
                            'is-invalid')) && ($("input[name='jumlah_nok']").val() != "" && !$(
                        "input[name='jumlah_nok']").hasClass('is-invalid'))) {
                    $('#btnsimpan').removeAttr('disabled');
                } else {
                    $('#btnsimpan').attr('disabled', true);
                }
            }

            $(document).on('keyup change', 'input[type="date"][name="tanggal_uji"]', function(event) {
                if ($(this).val() != "") {
                    if (datajenis == "produk") {
                        if ($("input[name='cek'][type='radio']:checked")) {
                            $('#btnsimpan').removeAttr('disabled');
                        } else {
                            $('#btnsimpan').attr('disabled', true);
                        }
                    } else {
                        cekvalidasipart();
                    }
                } else {
                    $('#btnsimpan').attr('disabled', true);
                }
            });

            const isListNoseriKalibrasiEmpty = () => {
                const listnoserikalibrasi = $('#listnoserikalibrasi').DataTable();
                const test = listnoserikalibrasi.rows().count() > 0 ? false : true;
                return test;
            }

            $(document).on('keyup change', 'input[type="date"][name="tanggal_kirim"]', function(event) {
                if ($(this).val() != "") {
                    if (!isListNoseriKalibrasiEmpty()) {
                        $('#btnsimpanKalibrasi').removeAttr('disabled');
                    }
                } else {
                    $('#btnsimpanKalibrasi').attr('disabled', true);
                }
            });

            $(document).on('keyup change', 'input[type="number"][name="jumlah_ok"]', function(event) {
                if (datajenis == "part") {
                    var datatableOld = $('#showtable').DataTable();
                    var rows = datatableOld.$('tr.bgcolor').closest('tr');
                    var dataHeader = datatableOld.row(rows).data();
                    let jumlahttl = dataHeader.jumlah - (dataHeader.jumlah_ok + dataHeader.jumlah_nok);
                    let ok = $(this).val();
                    let nok = $("input[type='number'][name='jumlah_nok']").val();

                    if ($(this).val() !== "") {
                        if (ok > jumlahttl) {
                            $('input[type="number"][name="jumlah_ok"]').addClass('is-invalid');
                            $("#msgjumlah_ok").text("Data melebihi jumlah Part");
                        } else {
                            $('input[type="number"][name="jumlah_ok"]').removeClass('is-invalid');
                            $("#msgjumlah_ok").text("");

                            if (nok !== "") {
                                var jumlahcurr = parseInt(ok) + parseInt(nok);

                                if (jumlahcurr > jumlahttl) {
                                    $('input[type="number"][name="jumlah_ok"]').addClass('is-invalid');
                                    $("#msgjumlah_ok").text("Data melebihi jumlah Part");
                                } else {
                                    $('input[type="number"][name="jumlah_ok"]').removeClass('is-invalid');
                                    $("#msgjumlah_ok").text("");

                                    if ($("input[name='tanggal_uji']").val() !== "") {
                                        $('input[type="number"][name="jumlah_ok"]').removeClass(
                                            'is-invalid');
                                    }
                                }
                            } else {
                                if ($("input[name='tanggal_uji']").val() !== "") {
                                    $('input[type="number"][name="jumlah_ok"]').removeClass('is-invalid');
                                }
                            }
                        }
                    }
                    cekvalidasipart();
                }
            });

            $(document).on('keyup change', 'input[type="number"][name="jumlah_nok"]', function(event) {
                if (datajenis == "part") {
                    var datatableOld = $('#showtable').DataTable();
                    var rows = datatableOld.$('tr.bgcolor').closest('tr');
                    var dataHeader = datatableOld.row(rows).data();
                    let jumlahttl = dataHeader.jumlah - (dataHeader.jumlah_ok + dataHeader.jumlah_nok);
                    let nok = $(this).val();
                    let ok = $("input[type='number'][name='jumlah_ok']").val();

                    if ($(this).val() !== "") {
                        if (nok > jumlahttl) {
                            $('input[type="number"][name="jumlah_nok"]').addClass('is-invalid');
                            $("#msgjumlah_nok").text("Data melebihi jumlah Part");
                        } else {
                            $('input[type="number"][name="jumlah_nok"]').removeClass('is-invalid');
                            $("#msgjumlah_nok").text("");

                            if (ok !== "") {
                                var jumlahcurr = parseInt(ok) + parseInt(nok);

                                if (jumlahcurr > jumlahttl) {
                                    $('input[type="number"][name="jumlah_nok"]').addClass('is-invalid');
                                    $("#msgjumlah_nok").text("Data melebihi jumlah Part");
                                } else {
                                    $('input[type="number"][name="jumlah_nok"]').removeClass('is-invalid');
                                    $("#msgjumlah_nok").text("");

                                    if ($("input[name='tanggal_uji']").val() !== "") {
                                        $('input[type="number"][name="jumlah_nok"]').removeClass(
                                            'is-invalid');
                                    }
                                }
                            } else {
                                if ($("input[name='tanggal_uji']").val() !== "") {
                                    $('input[type="number"][name="jumlah_nok"]').removeClass('is-invalid');
                                }
                            }
                        }
                    }
                    cekvalidasipart();
                }
            });


            $('input[type=radio][name=filter]').change(function() {
                var stat = this.value;
                console.log('/api/qc/so/seri/' + stat + '/' + dataid + '/{{ $id }}');
                var dat = $('#noseritable').DataTable().ajax.url('/api/qc/so/seri/' + stat + '/' + dataid +
                    '/{{ $id }}').load();

            });

            $(document).on('click', '.buttonNoSeriDetail', function() {
                var table = $('#noseritable').DataTable();
                var data = table.row($(this).closest('tr')).data();
                var index = table.row($(this).closest('tr')).index();
                const dateIndo = (date) => {
                    const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni",
                        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
                    ];
                    const d = new Date(date);
                    return `${d.getDate()} ${monthNames[d.getMonth()]} ${d.getFullYear()}`;
                }

                $('#nomor-seri-reworks').html(data.noseri);
                $('#tgl-dibuat-reworks').html(dateIndo(data.created_at));
                $('#packer-reworks').html(data.packer);
                $('.tableprodukreworks').DataTable().clear().destroy();

                let dataJson = data.item;

                $('.tableprodukreworks').DataTable({
                    data: dataJson,
                    destroy: true,
                    processing: true,
                    serverSide: false,
                    ordering: false,
                    autoWidth: false,
                    columns: [{
                            data: null,
                            // buat index
                            render: function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return data.produk + ' ' + data.varian;
                            }
                        },
                        {
                            data: 'noseri',
                        }
                    ]
                });
                $('.modalDetailNoSeri').modal('show');
            });
        })
    </script>
@stop
