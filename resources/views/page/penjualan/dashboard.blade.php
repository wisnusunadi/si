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
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <h4></h4>
                                    <div class="chart">
                                        <canvas id="myChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <h4>Batas Pengiriman E-Catalogue</h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-hover" id="pengirimantable" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>No SO</th>
                                                    <th>No PO</th>
                                                    <th>Status</th>
                                                    <th>Batas Pengiriman</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- <tr>
                                                    <td>1</td>
                                                    <td>SOSPA102100001</td>
                                                    <td>PO/ON/SPA/10/21/001</td>
                                                    <td><span class="badge yellow-text">Logistik</span></td>
                                                    <td>
                                                        <hgroup>
                                                            <p>30-10-2021</p>
                                                            <small id="urgent">3 Hari Lagi</small>
                                                        </hgroup>
                                                    </td>
                                                    <td><a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr=""><i class="fas fa-search"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>SOSPA102100001</td>
                                                    <td>PO/ON/SPA/10/21/001</td>
                                                    <td><span class="badge orange-text">QC</span></td>
                                                    <td>
                                                        <hgroup>
                                                            <p>04-11-2021</p>
                                                            <small id="warning">7 Hari Lagi</small>
                                                        </hgroup>
                                                    </td>
                                                    <td><a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr=""><i class="fas fa-search"></i></a></td>
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
@stop
@section('adminlte_js')
<script>
    $(function() {
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
                                backgroundColor: "red",
                                data: [data.ekatalog_graph[1].count, data.ekatalog_graph[2].count, data.ekatalog_graph[3].count, data.ekatalog_graph[4].count, data.ekatalog_graph[5].count, data.ekatalog_graph[6].count, data.ekatalog_graph[7].count, data.ekatalog_graph[8].count, data.ekatalog_graph[9].count, data.ekatalog_graph[10].count, data.ekatalog_graph[11].count, data.ekatalog_graph[12].count],
                                borderColor: 'red',
                            },
                            {
                                label: "SPA",
                                backgroundColor: "blue",
                                data: [data.spa_graph[1].count, data.spa_graph[2].count, data.spa_graph[3].count, data.spa_graph[4].count, data.spa_graph[5].count, data.spa_graph[6].count, data.spa_graph[7].count, data.spa_graph[8].count, data.spa_graph[9].count, data.spa_graph[10].count, data.spa_graph[11].count, data.spa_graph[12].count],
                                borderColor: 'blue',
                            },
                            {
                                label: "SPB",
                                backgroundColor: "black",
                                data: [data.spb_graph[1].count, data.spb_graph[2].count, data.spb_graph[3].count, data.spb_graph[4].count, data.spb_graph[5].count, data.spb_graph[6].count, data.spb_graph[7].count, data.spb_graph[8].count, data.spb_graph[9].count, data.spb_graph[10].count, data.spb_graph[11].count, data.spb_graph[12].count],
                                borderColor: 'black',
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
                                text: 'Grafik Penjualan Bulanan'
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