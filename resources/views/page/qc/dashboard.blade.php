@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Dashboard</h1>

@stop

@section('adminlte_css')
<style lang="scss">
    table {
        white-space: nowrap;
        text-align: center;
    }

    #pengirimantable thead {
        text-align: center;
    }

    .urgent {
        color: #dc3545;
        font-weight: 600;
    }

    .warning {
        color: #FFC700;
        font-weight: 600;
    }

    .info {
        color: #4682B4
    }


    .fa-search:hover {
        color: #4682B4;
    }

    .fa-search:active {
        color: #C0C0C0;
    }

    .hide {
        display: none !important;
    }

    .active {
        box-shadow: 12px 4px 8px 0 rgba(0, 0, 0, 0.2), 12px 6px 20px 0 rgba(0, 0, 0, 0.19);
    }

    .red-text {
        background-color: #FFDADA;
        color: #dc3545;
    }

    .yellow-text {
        background-color: #FFF6D4;
        color: #FFC700;
    }

    .warning-bg {
        background-color: #FFC700;
        color: white;
    }

    .green-text {
        background-color: rgba(69, 102, 0, 0.2);
        color: #456600;
    }

    .margin-custom {
        margin: 5px;
    }

    .align-center {
        text-align: center;
    }

    .otg:hover {
        box-shadow: 12px 4px 8px 0 rgba(0, 0, 0, 0.2), 12px 6px 20px 0 rgba(0, 0, 0, 0.19);
    }



    @media screen and (max-width: 1440px) {
        #pengujianterbarutable {
            font-size: 12px;
        }

        #belumdiujitable {
            font-size: 12px;
        }

        #lewatbatasujitable {
            font-size: 12px;
        }

        h4 {
            font-size: 20px;
        }

        #detailmodal {
            font-size: 12px;
        }

        .so-title {
            font-size: 12px;
        }

        section {
            font-size: 12px;
        }
    }

    @media screen and (min-width: 1440px) {
        #pengujianterbarutable {
            font-size: 14px;
        }

        #belumdiujitable {
            font-size: 14px;
        }

        #lewatbatasujitable {
            font-size: 14px;
        }

        h4 {
            font-size: 20px;
        }

        #detailmodal {
            font-size: 14px;
        }

        section {
            font-size: 14px;
        }
    }
</style>
@stop

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-lg-6 col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4>Outgoing 2021</h4>
                                <div class="row">

                                    <div class="col-lg-4 col-6">
                                        <div class="small-box bg-success">
                                            <div class="inner">
                                                <h3>{{$terbaru}}</h3>
                                                <p>Pengujian Terbaru</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fas fa-tasks"></i>
                                            </div>
                                            <a class="small-box-footer active" id="pengujianterbaru">Detail <i class="fas fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-6">
                                        <div class="small-box warning-bg">
                                            <div class="inner">
                                                <h3>{{$hasil}}</h3>
                                                <p>Belum diuji</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fas fa-boxes"></i>
                                            </div>
                                            <a class="small-box-footer" id="belumdiuji">Detail <i class="fas fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-6">
                                        <div class="small-box bg-danger">
                                            <div class="inner">
                                                <h3>{{$lewat_batas}}</h3>
                                                <p>Lewat Batas Uji</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fas fa-exclamation-circle"></i>
                                            </div>
                                            <a class="small-box-footer" id="lewatbatasuji">Detail <i class="fas fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <table class="table table-hover " id="pengujianterbarutable" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th colspan=5>

                                                        <h5><b>Pengujian Terbaru</b></h5>

                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>No</th>
                                                    <th>No SO</th>
                                                    <th>Batas Pengujian</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- <tr>
                                                    <td>1</td>
                                                    <td>SOSPA102100001</td>
                                                    <td>
                                                        <div class="urgent">12-10-2021</div>
                                                        <small><i class="fas fa-clock" id="info"></i> 7 Hari Lagi</small>
                                                    </td>
                                                    <td><span class="badge red-text">Belum diuji</span></td>
                                                    <td><a href="{{route('qc.so.detail_ekatalog', ['id' => '1'])}}"><i class="fas fa-search"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>SOSPA102100002</td>
                                                    <td>
                                                        <div class="urgent">11-10-2021</div>
                                                        <small><i class="fas fa-clock" id="info"></i> 6 Hari Lagi</small>
                                                    </td>
                                                    <td><span class="badge red-text">Belum diuji</span></td>
                                                    <td><a href="{{route('qc.so.detail_ekatalog', ['id' => '1'])}}"><i class="fas fa-search"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td>SOSPA102100003</td>
                                                    <td>
                                                        <div class="urgent">11-10-2021</div>
                                                        <small><i class="fas fa-clock" id="info"></i> 6 Hari Lagi</small>
                                                    </td>
                                                    <td><span class="badge red-text">Belum diuji</span></td>
                                                    <td><a href="{{route('qc.so.detail_ekatalog', ['id' => '1'])}}"><i class="fas fa-search"></i></a></td>
                                                </tr>
                                            </tbody> -->
                                        </table>


                                        <table class="table table-hover hide" id="belumdiujitable" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th colspan=4>

                                                        <h5><b>Belum Diuji</b></h5>

                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>No</th>
                                                    <th>No SO</th>
                                                    <th>Batas Pengujian</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- <tr>
                                                    <td>1</td>
                                                    <td>SOSPA092100093</td>
                                                    <td>
                                                        <div class="urgent">31-10-2021</div>
                                                        <small><i class="fas fa-exclamation-circle" id="warning"></i> 2 Hari Lagi</small>
                                                    </td>
                                                    <td><a href="{{route('qc.so.detail_ekatalog', ['id' => '1'])}}"><i class="fas fa-search"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>SOSPA092100121</td>
                                                    <td>
                                                        <div class="urgent">01-11-2021</div>
                                                        <small><i class="fas fa-exclamation-circle" id="warning"></i> 3 Hari Lagi</small>
                                                    </td>
                                                    <td><a href="{{route('qc.so.detail_spa', ['id' => '1'])}}"><i class="fas fa-search"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td>SOSPA102100001</td>
                                                    <td>
                                                        <div class="urgent">12-10-2021</div>
                                                        <small><i class="fas fa-clock" id="info"></i> 6 Hari Lagi</small>
                                                    </td>
                                                    <td><a href="{{route('qc.so.detail_spa', ['id' => '1'])}}"><i class="fas fa-search"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td>4</td>
                                                    <td>SOSPA102100002</td>
                                                    <td>
                                                        <div class="urgent">11-10-2021</div>
                                                        <small><i class="fas fa-clock" id="info"></i> 7 Hari Lagi</small>
                                                    </td>
                                                    <td><a href="{{route('qc.so.detail_spa', ['id' => '1'])}}"><i class="fas fa-search"></i></a></td>
                                                </tr> -->
                                            </tbody>
                                        </table>

                                        <table class="table table-hover hide" id="lewatbatasujitable" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th colspan=5>
                                                        <h5><b>Lewat Batas Uji</b></h5>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>No</th>
                                                    <th>No SO</th>
                                                    <th>Batas Pengujian</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- <tr>
                                                    <td>1</td>
                                                    <td>SOSPA092100093</td>
                                                    <td>
                                                        <div class="urgent">31-10-2021</div>
                                                        <small class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Lewat 3 Hari</small>
                                                    </td>
                                                    <td><span class="badge yellow-text">Sebagian diuji</span></td>
                                                    <td><a href="{{route('qc.so.detail_spa', ['id' => '1'])}}"><i class="fas fa-search"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>SOSPA092100121</td>
                                                    <td>
                                                        <div class="urgent">01-11-2021</div>
                                                        <small class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Lewat 2 Hari</small>
                                                    </td>
                                                    <td><span class="badge yellow-text">Sebagian diuji</span></td>
                                                    <td><a href="{{route('qc.so.detail_spa', ['id' => '1'])}}"><i class="fas fa-search"></i></a></td>
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
        <div class="modal fade" id="detailmodal" tabindex="-1" role="dialog" aria-labelledby="editmodal" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content" style="margin: 10px">
                    <div class="modal-header bg-warning">
                        <h4>Detail</h4>
                    </div>
                    <div class="modal-body" id="detail">
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
        pengujianterbarutable();
        $('#pengujianterbaru').on('click', function() {
            belumdiujitable_destroy();
            lewatbatasujitable_destroy();
            pengujianterbarutable();
            $('#pengujianterbaru').addClass('active');
            $('#pengujianterbarutable').removeClass('hide');

            $('#belumdiuji').removeClass('active');
            $('#lewatbatasuji').removeClass('active');

            $('#belumdiujitable').addClass('hide');
            $('#lewatbatasujitable').addClass('hide');
        })

        $('#belumdiuji').on('click', function() {
            pengujianterbarutable_destroy();
            lewatbatasujitable_destroy();
            belumdiujitable();
            $('#belumdiuji').addClass('active');
            $('#belumdiujitable').removeClass('hide');

            $('#pengujianterbaru').removeClass('active');
            $('#lewatbatasuji').removeClass('active');

            $('#pengujianterbarutable').addClass('hide');
            $('#lewatbatasujitable').addClass('hide');
        })

        $('#lewatbatasuji').on('click', function() {
            lewatbatasujitable();
            pengujianterbarutable_destroy();
            belumdiujitable_destroy();
            $('#lewatbatasuji').addClass('active');
            $('#lewatbatasujitable').removeClass('hide');

            $('#belumdiuji').removeClass('active');
            $('#pengujianterbaru').removeClass('active');

            $('#belumdiujitable').addClass('hide');
            $('#pengujianterbarutable').addClass('hide');
        })

        function belumdiujitable() {
            $('#belumdiujitable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    'url': '/api/qc/dashboard/data/belum_uji',
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
                        data: 'so',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'batas',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'button',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false
                    }
                ]
            })
        }

        function belumdiujitable_destroy() {
            $('#belumdiujitable').DataTable().clear().destroy();
        }

        function pengujianterbarutable() {
            var pengujianterbarutable = $('#pengujianterbarutable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    'url': '/api/qc/dashboard/data/terbaru',
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
                        data: 'so',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'batas',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'status',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'button',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false
                    }
                ]
            })
        }

        function pengujianterbarutable_destroy() {
            $('#pengujianterbarutable').DataTable().clear().destroy();
        }

        function lewatbatasujitable() {
            $('#lewatbatasujitable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    'url': '/api/qc/dashboard/data/lewat_uji',
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
                        data: 'so',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'batas',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'status',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'button',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false
                    }
                ]
            })
        }

        function lewatbatasujitable_destroy() {
            $('#lewatbatasujitable').DataTable().clear().destroy();
        }
    })
</script>
<script>
    $(function() {
        $(document).on('click', '.detailmodal', function(event) {
            event.preventDefault();
            var href = $(this).attr('data-attr');
            $.ajax({
                url: "",
                beforeSend: function() {
                    $('#loader').show();
                },
                // return the result
                success: function(result) {
                    $('#detailmodal').modal("show");
                    $('#detail').html(result).show();
                    $("#detailform").attr("action", href);
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
    });
</script>
<script>
    // var ctx = document.getElementById("areaChart");
    // var tensi_sistolik_chart = new Chart(ctx, {
    //     type: 'line',
    //     data: {
    //         labels: [],
    //         datasets: [{
    //             label: 'Sistolik',
    //             data: [],
    //             borderWidth: 2,
    //             backgroundColor: 'transparent',
    //             borderColor: 'red',
    //         }]
    //     },
    //     options: {
    //         scales: {
    //             xAxes: [],
    //             yAxes: [{
    //                 ticks: {
    //                     beginAtZero: true
    //                 }
    //             }]
    //         }
    //     }
    // });
    var ctx = document.getElementById("myChart");
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
            datasets: [{
                label: '# of Votes',
                data: [12, 19, 3, 5, 2, 3],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
</script>

@stop
