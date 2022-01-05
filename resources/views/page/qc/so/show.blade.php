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
                <li class="breadcrumb-item active">Sales Order QC</li>
                @elseif(Auth::user()->divisi_id == "2")
                <li class="breadcrumb-item"><a href="{{route('direksi.dashboard')}}">Beranda</a></li>
                <li class="breadcrumb-item active">Sales Order QC</li>
                @endif
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
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

    .filter {
        margin: 5px;
    }

    .info {
        color: #4682B4
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
                        <div class="row">
                            <div class="col-12">
                                <span class="float-right filter">
                                    <button class="btn btn-outline-secondary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                    <form id="filter">
                                        <div class="dropdown-menu">
                                            <div class="px-3 py-3">
                                                <div class="form-group">
                                                    <label for="jenis_penjualan">Jenis Penjualan</label>
                                                </div>
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="ekatalog" id="defaultCheck1" />
                                                        <label class="form-check-label" for="defaultCheck1">
                                                            E-Catalogue
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="spa" id="defaultCheck2" />
                                                        <label class="form-check-label" for="defaultCheck2">
                                                            SPA
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="spb" id="defaultCheck2" />
                                                        <label class="form-check-label" for="defaultCheck2">
                                                            SPB
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <span class="float-right">
                                                        <button class="btn btn-primary" type="submit">
                                                            Cari
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
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
                                            <th>No PO</th>
                                            <th>Batas Pengujian</th>
                                            <th>Customer</th>
                                            <th>Keterangan</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </thead>
                                        <tbody>
                                            <!-- <tr>
                                                <td>1</td>
                                                <td>SO/EKAT/X/02/98</td>
                                                <td>PO/ON/09/21/001</td>
                                                <td>31-10-2021</td>
                                                <td>CV. Cipta Jaya Mandiri</td>
                                                <td>-</td>
                                                <td><span class="badge green-text">Selesai</span></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>SO/EKAT/X/02/100</td>
                                                <td>PO/ON/09/21/002</td>
                                                <td>
                                                    <div class="urgent">31-10-2021</div>
                                                    <small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas Pengujian</small>
                                                </td>
                                                <td>CV. Cipta Jaya Mandiri</td>
                                                <td>-</td>
                                                <td><span class="badge yellow-text">Sedang Berlangsung</span></td>
                                                <td>
                                                    <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                                    </div>
                                                    <a href="">
                                                        <div><i class="fas fa-eye"></i></div><small>Detail</small>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>SO/SPA/XI/02/01</td>
                                                <td>PO/ON/09/21/003</td>
                                                <td>
                                                    <div class="warning">04-11-2021</div>
                                                    <small><i class="fa fa-exclamation-circle warning"></i> Batas Sisa 2 Hari</small>
                                                </td>
                                                <td>CV. Cipta Jaya Mandiri</td>
                                                <td>-</td>
                                                <td><span class="badge yellow-text">Sedang Berlangsung</span></td>
                                                <td><a href="">
                                                        <div><i class="fas fa-eye"></i></div><small>Detail</small>
                                                    </a></td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>SO/SPB/XI/02/01</td>
                                                <td>PO/ON/09/21/004</td>
                                                <td>
                                                    <div>21-09-2021</div>
                                                    <small>Batas sisa 6 Hari</small>
                                                </td>
                                                <td>PT. Emiindo Jaya Bersama</td>
                                                <td>-</td>
                                                <td><span class="badge red-text">Belum diuji</span></td>
                                                <td><a href="">
                                                        <div><i class="fas fa-eye"></i></div><small>Detail</small>
                                                    </a></td>
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
    </div>
</section>
@stop
@section('adminlte_js')
<script>
    $(function() {
        var showtable = $('#showtable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                'url': '/api/qc/so/data/semua',
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
                orderable: true,
                searchable: false
            }, {
                data: 'so',

            }, {
                data: 'no_po',

            }, {
                data: 'batas_uji',
                className: 'nowrap-text align-center',
                orderable: false,
                searchable: false,
            }, {
                data: 'nama_customer',

            }, {
                data: 'ket',
                className: 'nowrap-text align-center',
                orderable: false,
                searchable: false
            }, {
                data: 'status',
                className: 'nowrap-text align-center',
                orderable: false,
                searchable: false
            }, {
                data: 'button',
                className: 'nowrap-text align-center',
                orderable: false,
                searchable: false
            }]
        })
        $('#filter').submit(function() {
            var values = [];
            $("input:checked").each(function() {
                values.push($(this).val());
            });
            if (values != 0) {
                var x = values;

            } else {
                var x = ['semua']
            }
            console.log(x);
            $('#showtable').DataTable().ajax.url('/api/qc/so/data/' + x).load();
            return false;
        });
    })
</script>
@stop
