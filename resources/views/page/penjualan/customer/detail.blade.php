@extends('adminlte.page')

@section('title', 'ERP')


@section('adminlte_css')
<style>
    li.list-group-item {
        border: 0 none;
    }

    #historitabel {
        text-align: center;
    }

    .align-center {
        text-align: center;
    }

    .margin-all {
        margin: 5px;
    }

    .margin-side {
        margin-left: 5px;
        margin-right: 5px;
    }

    #profileImage {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: #4682B4;
        font-size: 22px;
        color: #fff;
        text-align: center;
        line-height: 100px;
        margin-top: 10px;
        margin-bottom: 20px;
    }

    .center {
        display: block;
        margin-left: auto;
        margin-right: auto;
        width: 50%;
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

@section('content_header')
<h1 class="m-0 text-dark">Customer</h1>
@stop

@section('content')
<section class="section">
    <div class="container-fluid">
        <div class="row">
            <div class="col-4">
                <h5>Info</h5>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 align-center">
                                <div id="profileImage" class="center margin-all"></div>
                                <div class="margin-all">
                                    <h5><b>{{$customer->nama}}</b></h5>
                                </div>
                                <div class="margin-all"><b>{{$customer->alamat}}</b></div>
                                <div class="margin-all"><b>{{$customer->Provinsi->nama}}</b></div>
                                <div class="margin-all">
                                    <span class="margin-side"><i class="fas fa-phone text-muted margin-side"></i> <b>{{$customer->telp}}</b></span>
                                    <span class="margin-side"><i class="fas fa-envelope text-muted margin-side"></i><b>@if(!empty($customer->email)) {{$customer->email}} @else - @endif</b></span>
                                </div>
                                <div class="margin-all"><a class="text-muted margin-side">NPWP :</a><b>{{$customer->npwp}}</b></div>
                                <div class="margin-all"><a class="text-muted">{{$customer->ket}}</a></div>
                            </div>
                        </div>
                        <!-- <ul class="list-group">
                            <li class="list-group-item">
                                <span style="font-size: 24px"><b>Info</b></span>
                            </li>
                            <li class="list-group-item">
                                <a>Nama</a>
                                <b class="float-right" id="nama_customer">{{$customer->nama}}</b>
                            </li>
                            <li class="list-group-item">
                                <a>Alamat</a>
                                <b class="float-right" id="alamat">{{$customer->alamat}}</b>
                            </li>
                            <li class="list-group-item">
                                <a>Telepon</a>
                                <b class="float-right" id="telepon">{{$customer->telp}}</b>
                            </li>
                            <li class="list-group-item">
                                <a>NPWP</a>
                                <b class="float-right" id="npwp">{{$customer->npwp}}</b>
                            </li>
                            <li class="list-group-item">
                                <a>Keterangan</a>
                                <b class="float-right" id="keterangan">{{$customer->ket}}</b>
                            </li>
                        </ul> -->
                    </div>
                </div>
            </div>
            <div class="col-8">
                <h5>Histori Penjualan</h5>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-center" id="showtable" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No SO</th>
                                        <th>No PO</th>
                                        <th>Tanggal PO</th>
                                        <th>Jenis</th>
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
@stop

@section('adminlte_js')
<script>
    $(function() {
        var showtable = $('#showtable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                'url': '/api/customer/detail/' + '{{$customer->id}}',
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
                    data: 'so'
                },
                {
                    data: 'nopo',
                },
                {
                    data: 'tglpo',
                },
                {
                    data: 'jenis',
                },
                {
                    data: 'status',

                }
            ]
        });
        var cust = <?php echo json_encode($customer->nama); ?>;
        var cust = cust.replace('.', '').replace('PT ', '').replace('CV ', '').replace('& ', '').replace('(', '').replace(')', '');
        var init = cust.split(" ");
        var initial = "";
        for (var i = 0; i < init.length; i++) {
            initial = initial + init[i].charAt(0);
        }
        var profileImage = $('#profileImage').text(initial);
    });
</script>
@stop