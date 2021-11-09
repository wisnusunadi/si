@extends('adminlte.page')

@section('title', 'ERP')

@section('adminlte_css')
<style>
    li.list-group-item {
        border: 0 none;
    }

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

    #pengirimanhref:hover {}
</style>
@stop

@section('content_header')
<h1 class="m-0 text-dark">Pengiriman</h1>
@stop

@section('content')
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
                                        <label for="jenis_penjualan">Status</label>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="selesai" id="status1" name="status" />
                                            <label class="form-check-label" for="status1">
                                                Sudah Dikirim
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="sebagian" id="status2" name="status" />
                                            <label class="form-check-label" for="status2">
                                                Sebagian Dikirim
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="belum" id="status3" name="status" />
                                            <label class="form-check-label" for="status3">
                                                Belum Dikirim
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
                            <table class="table" id="showtable" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No SO</th>
                                        <th>No SJ</th>
                                        <th>Ekspedisi</th>
                                        <th>No Resi</th>
                                        <th>Tanggal Kirim</th>
                                        <th>Nama Customer</th>
                                        <th>Alamat</th>
                                        <th>Telepon</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>SO-SPA10210001</td>
                                        <td>SJ/10/20/2001</td>
                                        <td>J&T</td>
                                        <td><i class="text-muted">Belum Tersedia</i></td>
                                        <td>09-10-2021</td>
                                        <td>RS Nurul Ikhsan</td>
                                        <td>Jl. Jakarta No 18A-20A, Garut, Jawa Barat</td>
                                        <td>081119494950</td>
                                        <td><span class="badge blue-text">Dalam Pengiriman</span></td>
                                        <td>
                                            <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a href="{{route('logistik.pengiriman.detail', ['id' => '1'])}}">
                                                    <button class="dropdown-item" type="button">
                                                        <i class="fas fa-search"></i>
                                                        Detail
                                                    </button>
                                                </a>
                                                <a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr="{{route('logistik.pengiriman.edit', ['id' => '1', 'status' => 'dalam_pengiriman'])}}" data-id="">
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
                                        <td>SO-EKAT08210005</td>
                                        <td>SJ/08/21/0986</td>
                                        <td>Safari Dharma Raya</td>
                                        <td>02-08-2021</td>
                                        <td>Bapak Hutapea</td>
                                        <td>Jl. Moh. Hatta No 73, Medan, Sumatera Utara</td>
                                        <td>082139754850</td>
                                        <td><a href="" id="pengirimanhref">
                                                <div class="btn btn-sm btn-outline-primary btn-circle"><i class="fas fa-paper-plane"></i></div>
                                                <div><small class="text-muted">Pengiriman</small></div>
                                            </a>
                                        </td>
                                        <td>
                                            <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a href="{{route('logistik.pengiriman.detail', ['id' => '1'])}}">
                                                    <button class="dropdown-item" type="button">
                                                        <i class="fas fa-search"></i>
                                                        Detail
                                                    </button>
                                                </a>
                                                <a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr="{{route('logistik.pengiriman.edit', ['id' => '1', 'status' => 'draft_pengiriman'])}}" data-id="">
                                                    <button class="dropdown-item" type="button">
                                                        <i class="fas fa-pencil-alt"></i>
                                                        Edit
                                                    </button>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>SO-SPB08210005</td>
                                        <td>SJ/01/20/1927</td>
                                        <td>Si Cepat</td>
                                        <td>02-08-2021</td>
                                        <td>Pemerintah Kab Badung</td>
                                        <td>Jl. Bougenvil No 45, Badung, Bali</td>
                                        <td>082139754850</td>
                                        <td><a href="" id="pengirimanhref">
                                                <div class="btn btn-sm btn-outline-primary btn-circle"><i class="fas fa-paper-plane"></i></div>
                                                <div><small class="text-muted">Pengiriman</small></div>
                                            </a>
                                        </td>
                                        <td>
                                            <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a href="{{route('logistik.pengiriman.detail', ['id' => '1'])}}">
                                                    <button class="dropdown-item" type="button">
                                                        <i class="fas fa-search"></i>
                                                        Detail
                                                    </button>
                                                </a>
                                                <a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr="" data-id="">
                                                    <button class="dropdown-item" type="button">
                                                        <i class="fas fa-pencil-alt"></i>
                                                        Edit
                                                    </button>
                                                </a>
                                            </div>
                                        </td>
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
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="margin: 10px">
                <div class="modal-header bg-info">
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
@stop

@section('adminlte_js')
<script>
    $(function() {
        $('#showtable').DataTable();
        $(document).on('click', '.editmodal', function(event) {
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
                    $('#editmodal').modal("show");
                    $('#edit').html(result).show();
                    console.log(id);
                    ekspedisi_select();
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

        function gg() {
            var showtable = $('#showtable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    'url': '/api/customer/detail/' + '1',
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    }
                },
                language: {
                    processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'DT_RowIndex'
                    },
                    {
                        data: 'DT_RowIndex',

                    },
                    {
                        data: 'DT_RowIndex',

                    },
                    {
                        data: 'DT_RowIndex',

                    }
                ]
            });
        }
    });
</script>
@stop