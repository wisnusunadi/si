@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro' rel='stylesheet' type='text/css'>
<style>
    .foo {
        float: left;
        width: 20px;
        height: 20px;
        margin: 5px;
        border: 1px solid rgba(0, 0, 0, .2);
    }

    .green {
        background: #28A745;
    }

    .blue {
        background: #17A2B8;
    }

    .topnav a {
        float: left;
        display: block;
        color: black;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
        font-size: 17px;
        border-bottom: 3px solid transparent;
    }

    .topnav a:hover {
        border-bottom: 3px solid red;
    }

    .topnav a.active {
        border-bottom: 3px solid red;
    }

    .active-link {
        box-shadow: 12px 4px 8px 0 rgba(0, 0, 0, 0.2), 12px 6px 20px 0 rgba(0, 0, 0, 0.19);
    }

    .nav-border {
        border-bottom: 2px solid black;
        content: "";
    }

    section {
        font-family: "Source Sans Pro"
    }

    img {
        /* Jika Gambar Disamping */
        width: 280px;
        /* Jika Gambar Diatas */
        /* width: 100px; */
    }
    #DataTables_Table_0_filter{
        display: none;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-5">
                @foreach ($d as $p)
                @if ($p->sparepart_id != null)
                <input type="hidden" name="" id="id" value="{{ $p->sparepart_id  }}">
                @else
                <input type="hidden" name="" id="id" value="{{ $p->gbj_id  }}">
                @endif
                @endforeach
                <div class="card mb-3">
                    <div class="row no-gutters">
                        <div class="col-md-4">
                            {{-- <img src="https://images.unsplash.com/photo-1526930382372-67bf22c0fce2?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&amp;ixlib=rb-1.2.1&amp;auto=format&amp;fit=crop&amp;w=687&amp;q=80"
                                alt="..."> --}}
                        </div>
                        <div class="col-md-8">
                            <div class="card-body ml-5">
                                <div class="card-title">
                                    <h2 class="text-bold" id="nama">-</h2>
                                    <h6 class="text-muted" id="kode">-</h6>
                                </div>
                                <h5 class="card-text text-bold pt-2">Deskripsi</h5>
                                <p class="card-text" id="desk">-</p>
                                <h5 class="card-text text-bold pt-1">Dimensi</h5>
                                <p class="text-bold" style="margin-bottom: 0">Panjang x Lebar x Tinggi</p>
                                <p><span class="panjang">-</span> x <span class="lebar">-</span> x <span
                                        class="tinggi">-</span></p>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- @endforeach --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-pie mr-1"></i>
                            Grafik Jumlah Terima / Transfer
                            <i></i>
                            (<span id="nama_p">Nama Produk</span>)
                            <i></i>
                            Per Tahun
                        </h3>
                        <div class="card-tools">
                                    <select name="tahun" id="tahun" class="form-control">
                                        <option value="" selected>Pilih Tahun</option>
                                        @foreach ($data as $k => $v)
                                            @if ($k != null)
                                                <option value="{{ $k }}">{{ $k }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="myChart" width="400" height="220"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-xl-7">

                <div class="card">
                    <div class="card-title">
                        <div class="ml-3 mr-3">
                            <div class="row align-items-center">
                                <div class="col-lg-9 col-xl-8">
                                    <div class="row align-items-center">
                                        <div class="col-md-4">
                                            <div class="input-icon">
                                                <input type="text" class="form-control" placeholder="Cari..."
                                                    id="kt_datatable_search_query">
                                                <span>
                                                    <i class="flaticon2-search-1 text-muted"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex align-items-center">
                                                <label class="mr-3 mb-0 d-none d-md-block" for="">Tanggal</label>
                                                <input type="text" name="" id="datetimepicker1" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-xl-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <p class="card-text">Keterangan Isi Kolom</p>
                                            <p class="card-text">
                                                <div class="foo green"></div> : Penerimaan
                                            </p>
                                            <p class="card-text">
                                                <div class="foo blue"></div> : Transfer
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-7">
                            <table class="table tableProdukView">
                                <thead>
                                    <tr>
                                        <th style="width: 150px">Tanggal</th>
                                        <th>Dari/Ke</th>
                                        <th>Tujuan</th>
                                        <th>Jumlah</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
{{-- Modal --}}
<div class="modal fade modalDetail" id="" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Produk <span id="produkk">Ambulatory</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-seri">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nomor Seri</th>
                            <th>Kerusakan</th>
                            <th>Perbaikan</th>
                            <th>Tingkat Kerusakan</th>
                            <th>Posisi Barang</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop
@section('adminlte_js')
<script>
     var id = $('#id').val();
     var getUrlParameter = function getUrlParameter(sParam) {
        var sPageURL = window.location.search.substring(1),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');

            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
            }
        }
        return false;
    };

     var value = getUrlParameter('jenis');
    //  console.log(value);

    // console.log(id);

    $.ajax({
        url: "/api/gk/transaksi/header/" + id,
        type: "get",
        success: function(res) {
            console.log(res);
            $('h2#nama').text(res.nama);
            $('span#nama_p').text(res.nama);
            $('span#produkk').text(res.nama);
            $('h6#kode').text(res.kode);
            $('p#desk').text(res.desk);
            $('span#panjang').text(res.panjang);
            $('span#lebar').text(res.lebar);
            $('span#tinggi').text(res.tinggi);
        }
    });



    var start_date;
    var end_date;
    var DateFilterFunction = (function (oSettings, aData, iDataIndex) {
        var dateStart = parseDateValue(start_date);
        var dateEnd = parseDateValue(end_date);

        var evalDate = parseDateValue(aData[0]);
        if ((isNaN(dateStart) && isNaN(dateEnd)) ||
            (isNaN(dateStart) && evalDate <= dateEnd) ||
            (dateStart <= evalDate && isNaN(dateEnd)) ||
            (dateStart <= evalDate && evalDate <= dateEnd)) {
            return true;
        }
        return false;
    });

    function parseDateValue(rawDate) {
        var dateArray = rawDate.split("-");
        var parsedDate = new Date(dateArray[2], parseInt(dateArray[1]) - 1, dateArray[
        0]);
        return parsedDate;
    }
    let table = $('.tableProdukView').DataTable({
        destroy: true,
        "lengthChange": false,
        destroy: true,
        processing: true,
        ajax: {
            url: "/api/gk/transaksi/history/" + id,
        },
        columns: [
            {data: 'tanggal'},
            {data: 'divisi'},
            {data: 'tujuan'},
            {data: 'jml'},
            {data: 'aksi'},
        ],
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
        },
        columnDefs:[{
            targets: [4],
            searching: false,
        }]
    });

    $('#kt_datatable_search_query').on('keyup', function() {
        table.search($(this).val()).draw();
    });

    $('#datetimepicker1').daterangepicker({
            autoUpdateInput: false
        });

        $('#datetimepicker1').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format(
                'DD-MM-YYYY'));
            start_date = picker.startDate.format('DD-MM-YYYY');
            end_date = picker.endDate.format('DD-MM-YYYY');
            $.fn.dataTableExt.afnFiltering.push(DateFilterFunction);
            table.draw();
        });

        $('#datetimepicker1').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
            start_date = '';
            end_date = '';
            $.fn.dataTable.ext.search.splice($.fn.dataTable.ext.search.indexOf(DateFilterFunction, 1));
            table.draw();
        });
    $('#nav-deskripsi-tab').click(function (e) {
        e.preventDefault();
        $('.is-active').addClass('font-weight-bold');
        $('.is-active').removeClass('font-weight-light');
        $('.is-disable').addClass('font-weight-light');
        $('.is-disable').removeClass('font-weight-bold');
    });
    $('#nav-dimensi-tab').click(function (e) {
        e.preventDefault();
        $('.is-active').removeClass('font-weight-bold');
        $('.is-active').addClass('font-weight-light');
        $('.is-disable').removeClass('font-weight-light');
        $('.is-disable').addClass('font-weight-bold');
    });
    // $('#tanggalmasuk').daterangepicker({});

    function detailProduk() {
        $('.modalDetail').modal('show');
    }

    $(document).on('click', '#btnDetail', function() {
        var idd = $(this).data('id');
        console.log(idd);
        // console.log('ok');
        $('.table-seri').DataTable({
            autoWidth: false,
            destroy: true,
            processing: true,
            ajax: {
                url: "/api/gk/transaksi/noseri/" + idd,
            },
            columns: [
                {data: 'DT_RowIndex'},
                {data: 'noser'},
                {data: 'rusak'},
                {data: 'repair'},
                {data: 'tingkat'},
                {data: 'layout'},
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
        });
        detailProduk();
    })

    const ctx = document.getElementById('myChart').getContext('2d');
    const myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        // labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
        labels: [],
        datasets: [
            {
            label: 'Terima',
            data: [],
            backgroundColor: [
                'rgba(255, 159, 64, 0.2)',
            ],
            borderWidth: 1
        },
        {
            label: 'Transfer',
            data: [],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
            ],
            borderWidth: 1
        },
    ]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
$('#tahun').change(function (e) {
    $.ajax({
        type: "post",
        url: "/api/gk/transaksi/grafik-trf",
        data: {
            id: id,
            tahun: this.value,
        },
        success: function (res) {
            console.log(res);
            if (res.masuk && res.data != null) {
                myChart.data.labels = res.masuk.map(r => res.masuk[0].bulan);
                myChart.data.datasets[0].data = res.masuk.map(r => res.masuk[0].jumlah);
                myChart.data.datasets[1].data = res.data.map(r => res.data[0].jumlah);
                myChart.update();
            }
            else if (res.masuk != null) {
                myChart.data.labels = res.masuk.map(r => res.masuk[0].bulan);
                myChart.data.datasets[0].data = res.masuk.map(r => res.masuk[0].jumlah);
                myChart.update();
            }else if (res.data != null) {
                myChart.data.labels = res.data.map(r => res.data[0].bulan);
                myChart.data.datasets[1].data = res.data.map(r => res.data[0].jumlah);
                myChart.update();
            }
        }
    });
});

</script>
@stop
