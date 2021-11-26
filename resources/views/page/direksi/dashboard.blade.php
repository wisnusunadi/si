@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Dashboard</h1>

@stop

@section('adminlte_css')
<style lang="scss">
    #pengirimantable thead {
        text-align: center;
    }

    #pengirimantable td:nth-child(5) {
        text-align: right;
        white-space: nowrap;
    }

    #pengirimantable td:nth-child(1),
    td:nth-child(4),
    td:nth-child(6) {
        text-align: center;
        white-space: nowrap;
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

    .removeshadow {
        box-shadow: none;
    }

    .orange {
        background-color: #ff6600;
        color: #FFFFFF;
    }

    .yellow {
        background-color: #ffb31a;
        color: #FFFFFF;
    }

    .blue {
        background-color: #00bfff;
        color: #FFFFFF;
    }

    .red {
        background-color: #b30000;
        color: #FFFFFF;
    }

    .purple {
        background-color: #7D6378;
        color: #FFFFFF;
    }

    .green {
        background-color: #456600;
        color: #FFFFFF;
    }

    .midnightblue {
        background-color: #191970;
        color: #FFFFFF;
    }

    .link {
        color: #FFFFFF;
        text-decoration: none;
        background-color: none;
    }

    .blue-bg {
        background-color: #5F7A90;
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

        .so-title {
            font-size: 14px;
        }
    }

    @media screen and (min-width: 1440px) {
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
                <div class="card removeshadow">
                    <div class="card-body blue-bg">
                        <h3 class="link">Penjualan</h3>
                        <div class="row">
                            <div class="col-lg-6 col-12">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <h4></h4>
                                                <div class="chart h-100">
                                                    <canvas id="myChart" style="min-height: 250px; height: 250px; max-height: 100%; max-width: 100%;"></canvas>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-12 align-center">
                                <div class="card">
                                    <div class="card-body">
                                        <h4><b>Sales Order</b></h4>
                                        <div class="row">
                                            <div class="col-8">
                                                <div class="row">
                                                    <div class="col-lg-6 col-6 py-2">
                                                        <div class="card h-100 purple">
                                                            <div class="card-body">
                                                                <h3 id="so_gudang">3</h3>
                                                                <p class="so-title">SO Belum Diproses Gudang</p>
                                                            </div>
                                                            <div class="card-footer align-center"><a href="#" id="belumdikirim" class="link">Lihat Laporan <i class="fas fa-arrow-circle-right"></i></a></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-6 py-2">
                                                        <div class="card h-100 yellow">
                                                            <div class="card-body ">
                                                                <h3 id="so_qc">3</h3>
                                                                <p class="so-title">SO Belum Diproses QC</p>
                                                            </div>
                                                            <div class="card-footer align-center"><a href="/qc/so/show" id="belumdikirim" class="link">Lihat Laporan <i class="fas fa-arrow-circle-right"></i></a></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-6 col-6 py-2">
                                                        <div class="card h-100 green">
                                                            <div class="card-body">
                                                                <h3 id="so_logistik">3</h3>
                                                                <p class="so-title">SO Belum Diproses Logistik</p>
                                                            </div>
                                                            <div class="card-footer align-center"><a href="/logistik/so/show" id="belumdikirim" class="link">Lihat Laporan <i class="fas fa-arrow-circle-right"></i></a></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-6 py-2">
                                                        <div class="card h-100 midnightblue">
                                                            <div class="card-body">
                                                                <h3 id="so_dc">3</h3>
                                                                <p class="so-title">SO Belum Diproses DC</p>
                                                            </div>
                                                            <div class="card-footer align-center"><a href="/dc/so/show" id="belumdikirim" class="link">Lihat Laporan <i class="fas fa-arrow-circle-right"></i></a></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="row">
                                                    <div class="col-lg-12 col-12 py-2">
                                                        <div class="card h-100 red">
                                                            <div class="card-body p-5">
                                                                <h3 id="so_dc">3</h3>
                                                                <p class="so-title">AKN Belum Memiliki SO</p>
                                                            </div>
                                                            <div class="card-footer align-center"><a href="/penjualan/penjualan/show" id="belumdikirim" class="link">Lihat Laporan <i class="fas fa-arrow-circle-right"></i></a></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
        $('#divisitable').DataTable({});
        var pengirimantable = $('#pengirimantable').DataTable({
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
                    data: 'so',
                    className: 'nowrap-text align-center',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'no_po',
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
                    data: 'batas_kontrak',
                    className: 'nowrap-text align-center',

                },
                {
                    data: 'button',
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
            var id = $(this).data("id");
            var label = $(this).data("target");

            $.ajax({
                url: href,
                beforeSend: function() {
                    $('#loader').show();
                },
                // return the result
                success: function(result) {
                    $('#detailmodal').modal("show");
                    $('#detail').html(result).show();
                    if (label == 'ekatalog') {
                        detailtabel_ekatalog(id);
                    } else if (label == 'spa') {
                        detailtabel_spa(id);
                    } else {
                        detailtabel_spb(id);
                    }

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

        function detailtabel_ekatalog(id) {
            $('#detailtabel').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    'url': '/api/ekatalog/paket/detail/' + id,
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
                        data: 'nama_produk',

                    },
                    {
                        data: 'harga',
                        render: $.fn.dataTable.render.number(',', '.', 2),
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'jumlah',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'total',
                        render: $.fn.dataTable.render.number(',', '.', 2),
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'button',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false
                    },
                ],
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api(),
                        data;
                    // converting to interger to find total
                    var intVal = function(i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                    };
                    // computing column Total of the complete result 
                    var jumlah_pesanan = api
                        .column(3)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    // computing column Total of the complete result 
                    var total_pesanan = api
                        .column(4)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    var num_for = $.fn.dataTable.render.number(',', '.', 2).display;
                    $(api.column(0).footer()).html('Total');
                    $(api.column(3).footer()).html('Total');
                    $(api.column(4).footer()).html(num_for(total_pesanan));
                },
            })
        }

    });
</script>
<script>
    $(document).ready(function() {
        $.ajax({
            url: "/api/penjualan/chart",
            method: "GET",
            success: function(data) {
                console.log(data.ekatalog_graph);
                var ctx = document.getElementById("myChart");
                var myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"],
                        datasets: [{
                                label: "E-Catalogue",
                                backgroundColor: "#7D6378",
                                data: [data.ekatalog_graph[1].count, data.ekatalog_graph[2].count, data.ekatalog_graph[3].count, data.ekatalog_graph[4].count, data.ekatalog_graph[5].count, data.ekatalog_graph[6].count, data.ekatalog_graph[7].count, data.ekatalog_graph[8].count, data.ekatalog_graph[9].count, data.ekatalog_graph[10].count, data.ekatalog_graph[11].count, data.ekatalog_graph[12].count],
                                borderColor: '#7D6378',
                            },
                            {
                                label: "SPA",
                                backgroundColor: "#EA8B1B",
                                data: [data.spa_graph[1].count, data.spa_graph[2].count, data.spa_graph[3].count, data.spa_graph[4].count, data.spa_graph[5].count, data.spa_graph[6].count, data.spa_graph[7].count, data.spa_graph[8].count, data.spa_graph[9].count, data.spa_graph[10].count, data.spa_graph[11].count, data.spa_graph[12].count],
                                borderColor: '#EA8B1B',
                            },
                            {
                                label: "SPB",
                                backgroundColor: "#5F7A90",
                                data: [data.spb_graph[1].count, data.spb_graph[2].count, data.spb_graph[3].count, data.spb_graph[4].count, data.spb_graph[5].count, data.spb_graph[6].count, data.spb_graph[7].count, data.spb_graph[8].count, data.spb_graph[9].count, data.spb_graph[10].count, data.spb_graph[11].count, data.spb_graph[12].count],
                                borderColor: '#5F7A90',
                            }
                        ]
                    },
                    options: {
                        animations: {
                            tension: {
                                duration: 4000,
                                easing: 'linear',
                                from: 1,
                                to: 0,
                                loop: true
                            }
                        },
                        plugins: {
                            title: {
                                display: true,
                                text: 'Grafik Penjualan Tahunan'
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
    });
</script>
<script>

</script>

@stop