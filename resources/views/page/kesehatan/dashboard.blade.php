@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Dashboard</h1>
@stop

@section('adminlte_css')
<style lang="scss">
    .card {
        -webkit-box-shadow: none;
        -moz-box-shadow: none;
        box-shadow: none;
        }

    .modal-body{
        max-height: 80vh;
        overflow-y: auto;
    }

.foo {
            border-radius: 50%;
            float: left;
            width: 10px;
            height: 10px;
            align-items: center !important;
        }
        .alert-danger {
            color: #a94442;
            background-color: #f2dede;
            border-color: #ebccd1;
        }

        .alert-info {
            color: #0c5460;
            background-color: #d1ecf1;
            border-color: #bee5eb;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .bg-chart-light{
            background: rgba(192, 192, 192, 0.2);
        }

        .bg-chart-orange{
            background: rgb(236, 159, 5);
        }

        .bg-chart-yellow{
            background: rgb(255, 221, 0);
        }

        .bg-chart-green{
            background: rgb(11, 171, 100);
        }

        .bg-chart-blue{
            background: rgb(8, 126, 225);
        }
    #pengirimantable thead {
        text-align: center;
    }

    .nowrap {
        white-space: nowrap;
    }

    .align-center {
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
<div class="content">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                        <h6 class="float-right mb-3 text-muted"><i>{{Carbon::now()->isoFormat('dddd, D MMMM Y')}}</i></h6>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-4">
                            <div class="info-box blue-text">
                                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                                <div class="info-box-content">
                                <span class="info-box-text">Jumlah Karyawan</span>
                                <span class="info-box-number">1,410</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                        <div class="info-box green-text">
                            <span class="info-box-icon"><i class="fas fa-user-check"></i></span>
                            <div class="info-box-content">
                              <span class="info-box-text">Karyawan Masuk</span>
                              <span class="info-box-number">1,410</span>
                            </div>
                        </div>
                        </div>
                        <div class="col-4">
                        <div class="info-box red-text">
                            <span class="info-box-icon"><i class="fas fa-user-slash"></i></span>
                            <div class="info-box-content">
                              <span class="info-box-text">Karyawan Tidak Masuk</span>
                              <span class="info-box-number">1,410</span>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="row">
                <div class="col-8">
                    <div class="card">
                        <div class="card-body">
                            <h4>Berat Badan</h4>
                            <div style="min-height:350px; max-height: 350px;">
                                <canvas id="chart_berat_badan" style="max-height:100%"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card">
                        <div class="card-body">
                            <h4>Vaksin</h4>
                            <div style="min-height:350px; max-height: 350px;">
                                <canvas id="myChart" style="max-height:100%"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-12">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5>Karyawan Sakit</h5>
                            <div class="btn-group mb-3 float-right">
                                <button type="button" class="btn btn-outline-info">Pilih Tahun</button>
                                <button type="button" class="btn btn-outline-info dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu">
                                  <a class="dropdown-item" href="#">2022</a>
                                  <a class="dropdown-item" href="#">2021</a>
                                  <a class="dropdown-item" href="#">2020</a>
                                  <a class="dropdown-item" href="#">2019</a>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover" id="karyawan_sakit_table">
                                    <thead class="bg-secondary">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Divisi</th>
                                            <th>Frekuensi Sakit</th>
                                            <th>Terakhir Sakit</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('adminlte_js')
<script>
    $(document).ready(function() {
        $('#karyawan_sakit_table').DataTable();
        $.ajax({
            url: "/api/penjualan/chart",
            method: "GET",
            success: function(data) {

                var ctx = document.getElementById("chart_berat_badan");
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"],
                        datasets: [{
                                label: "Underfat",
                                backgroundColor: "#ffcc00",
                                data: [data.ekatalog_graph[1].count, data.ekatalog_graph[2].count, data.ekatalog_graph[3].count, data.ekatalog_graph[4].count, data.ekatalog_graph[5].count, data.ekatalog_graph[6].count, data.ekatalog_graph[7].count, data.ekatalog_graph[8].count, data.ekatalog_graph[9].count, data.ekatalog_graph[10].count, data.ekatalog_graph[11].count, data.ekatalog_graph[12].count],
                                borderColor: '#FFF6D4',
                            },
                            {
                                label: "Normal",
                                backgroundColor: '#456600',
                                data: [data.spa_graph[1].count, data.spa_graph[2].count, data.spa_graph[3].count, data.spa_graph[4].count, data.spa_graph[5].count, data.spa_graph[6].count, data.spa_graph[7].count, data.spa_graph[8].count, data.spa_graph[9].count, data.spa_graph[10].count, data.spa_graph[11].count, data.spa_graph[12].count],
                                borderColor: 'rgba(69, 102, 0, 0.2)',
                            },
                            {
                                label: "Overweight",
                                backgroundColor: "#dc3545",
                                data: [data.ekatalog_graph[1].count, data.ekatalog_graph[2].count, data.ekatalog_graph[3].count, data.ekatalog_graph[4].count, data.ekatalog_graph[5].count, data.ekatalog_graph[6].count, data.ekatalog_graph[7].count, data.ekatalog_graph[8].count, data.ekatalog_graph[9].count, data.ekatalog_graph[10].count, data.ekatalog_graph[11].count, data.ekatalog_graph[12].count],
                                borderColor: '#FFDADA',
                            },
                        ]
                    },
                    options: {
                        plugins: {
                            title: {
                                display: true,
                                text: 'Grafik Berat Badan'
                            }
                        },
                        scales: {
                            // y: { // defining min and max so hiding the dataset does not change scale range
                            //     min: 0,
                            //     max: 2,
                            //     stepSize: 1,
                            // }
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });
            }
        });

        $(document).ready(function() {
        $.ajax({
            url: "/kesehatan/vaksin/chart/data",
            method: "GET",
            success: function(data) {
                const ctx = $('#myChart');
                const myChart = new Chart(ctx, {
                    type: 'pie',
                data: {
                    labels: [
                        'Belum Vaksin',
                        'Vaksin 1',
                        'Vaksin 2',
                        'Vaksin 3',
                    ],
                    datasets: [{
                        label: 'Vaksinasi',
                        data: [10, data.tahap_1, data.tahap_2, data.tahap_3],
                        backgroundColor: [
                        '#EFEFEF',
                        'rgb(255, 221, 0)',
                        'rgb(11, 171, 100)',
                        'rgb(8, 126, 225)'
                        ],
                        hoverOffset: 4
                    }]
                }
                });
            }
        });
        });



    });
</script>
@stop
