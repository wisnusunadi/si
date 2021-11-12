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

    #urgent {
        color: red;
    }

    #warning {
        color: #FFC700;
    }

    #info {
        color: #3a7bb0;
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
        #pengirimantable {
            font-size: 12px;
        }

        h4 {
            font-size: 20px;
        }

        #detailmodal {
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
                <div class="row">
                    <div class="col-lg-6 col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4>Penjualan 2021</h4>
                                <div class="row">

                                    <div class="col-lg-4 col-6">
                                        <div class="small-box bg-success">
                                            <div class="inner">
                                                <h3>3</h3>
                                                <p>SO milik Logistik</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fas fa-tasks"></i>
                                            </div>
                                            <a href="#" class="small-box-footer active" id="pengirimanso">Detail <i class="fas fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-6">
                                        <div class="small-box warning-bg">
                                            <div class="inner">
                                                <h3>4</h3>
                                                <p>Belum memiliki COO</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fas fa-boxes"></i>
                                            </div>
                                            <a href="#" class="small-box-footer" id="sotanpacoo">Detail <i class="fas fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-6">
                                        <div class="small-box bg-danger">
                                            <div class="inner">
                                                <h3>3</h3>
                                                <p>Lewat Batas Kontrak</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fas fa-exclamation-circle"></i>
                                            </div>
                                            <a href="#" class="small-box-footer" id="lewatbataskontrak">Detail <i class="fas fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <table class="table table-hover" id="pengirimansotable" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th colspan=5>

                                                        <h5><b>Daftar SO milik Logistik</b></h5>

                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>No</th>
                                                    <th>No SO</th>
                                                    <th>Batas Pengiriman</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>SOSPA102100001</td>
                                                    <td>
                                                        <div class="urgent">12-10-2021</div>
                                                        <small><i class="fas fa-clock" id="info"></i> 7 Hari Lagi</small>
                                                    </td>
                                                    <td><span class="badge red-text">Belum Dikirim</span></td>
                                                    <td><a href=""><i class="fas fa-search"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>SOSPA102100002</td>
                                                    <td>
                                                        <div class="urgent">11-10-2021</div>
                                                        <small><i class="fas fa-clock" id="info"></i> 6 Hari Lagi</small>
                                                    </td>
                                                    <td><span class="badge blue-text">Draft Pengiriman</span></td>
                                                    <td><a href=""><i class="fas fa-search"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td>SOSPA102100003</td>
                                                    <td>
                                                        <div class="urgent">11-10-2021</div>
                                                        <small><i class="fas fa-clock" id="info"></i> 6 Hari Lagi</small>
                                                    </td>
                                                    <td><span class="badge red-text">Belum Dikirim</span></td>
                                                    <td><a href=""><i class="fas fa-search"></i></a></td>
                                                </tr>
                                            </tbody>
                                        </table>


                                        <table class="table table-hover hide" id="sotanpacootable" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th colspan=4>

                                                        <h5><b>Daftar SO belum memiliki COO</b></h5>

                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>No</th>
                                                    <th>No SO</th>
                                                    <th>Batas Kontrak</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>SOSPA092100093</td>
                                                    <td>
                                                        <div class="urgent">31-10-2021</div>
                                                        <small><i class="fas fa-exclamation-circle" id="warning"></i> 2 Hari Lagi</small>
                                                    </td>
                                                    <td><a href=""><i class="fas fa-search"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>SOSPA092100121</td>
                                                    <td>
                                                        <div class="urgent">01-11-2021</div>
                                                        <small><i class="fas fa-exclamation-circle" id="warning"></i> 3 Hari Lagi</small>
                                                    </td>
                                                    <td><a href=""><i class="fas fa-search"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td>SOSPA102100001</td>
                                                    <td>
                                                        <div class="urgent">12-10-2021</div>
                                                        <small><i class="fas fa-clock" id="info"></i> 6 Hari Lagi</small>
                                                    </td>
                                                    <td><a href=""><i class="fas fa-search"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td>4</td>
                                                    <td>SOSPA102100002</td>
                                                    <td>
                                                        <div class="urgent">11-10-2021</div>
                                                        <small><i class="fas fa-clock" id="info"></i> 7 Hari Lagi</small>
                                                    </td>
                                                    <td><a href=""><i class="fas fa-search"></i></a></td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <table class="table table-hover hide" id="lewatbataskontraktable" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th colspan=5>
                                                        <h5><b>Lewat Batas Kontrak</b></h5>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>No</th>
                                                    <th>No SO</th>
                                                    <th>Batas Kontrak</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>SOSPA092100093</td>
                                                    <td>
                                                        <div class="urgent">31-10-2021</div>
                                                        <small class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Lewat 3 Hari</small>
                                                    </td>
                                                    <td><span class="badge yellow-text">Sebagian diproses</span></td>
                                                    <td><a href="{{route('dc.so.detail', ['id' => '1'])}}"><i class="fas fa-search"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>SOSPA092100121</td>
                                                    <td>
                                                        <div class="urgent">01-11-2021</div>
                                                        <small class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Lewat 2 Hari</small>
                                                    </td>
                                                    <td><span class="badge yellow-text">Sebagian diproses</span></td>
                                                    <td><a href="{{route('dc.so.detail', ['id' => '1'])}}"><i class="fas fa-search"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td>SOSPA092100311</td>
                                                    <td>
                                                        <div class="urgent">01-11-2021</div>
                                                        <small class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Lewat 2 Hari</small>
                                                    </td>
                                                    <td><span class="badge red-text">Belum diproses</span></td>
                                                    <td><a href="{{route('dc.so.detail', ['id' => '1'])}}"><i class="fas fa-search"></i></a></td>
                                                </tr>
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
        $('#pengirimanso').on('click', function() {
            $('#pengirimanso').addClass('active');
            $('#pengirimansotable').removeClass('hide');

            $('#sotanpacoo').removeClass('active');
            $('#lewatbataskontrak').removeClass('active');

            $('#sotanpacootable').addClass('hide');
            $('#lewatbataskontraktable').addClass('hide');
        })

        $('#sotanpacoo').on('click', function() {
            $('#sotanpacoo').addClass('active');
            $('#sotanpacootable').removeClass('hide');

            $('#pengirimanso').removeClass('active');
            $('#lewatbataskontrak').removeClass('active');

            $('#pengirimansotable').addClass('hide');
            $('#lewatbataskontraktable').addClass('hide');
        })

        $('#lewatbataskontrak').on('click', function() {
            $('#lewatbataskontrak').addClass('active');
            $('#lewatbataskontraktable').removeClass('hide');

            $('#sotanpacoo').removeClass('active');
            $('#pengirimanso').removeClass('active');

            $('#sotanpacootable').addClass('hide');
            $('#pengirimansotable').addClass('hide');
        })

        var pengirimantable = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                'url': '/api/ekatalog/pengiriman/data/',
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
                    className: 'nowrap-text align-center',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'DT_RowIndex',
                    className: 'nowrap-text align-center',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'DT_RowIndex',
                    className: 'nowrap-text align-center',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'DT_RowIndex',
                    className: 'nowrap-text align-center',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'batas_kontrak',
                    className: 'nowrap-text align-center',

                },
                {
                    data: 'DT_RowIndex',
                    className: 'nowrap-text align-center',
                    orderable: false,
                    searchable: false
                },
            ]
        })
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