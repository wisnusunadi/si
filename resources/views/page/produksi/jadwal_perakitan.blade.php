@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
<style>
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

    .active {
        box-shadow: 12px 4px 8px 0 rgba(0, 0, 0, 0.2), 12px 6px 20px 0 rgba(0, 0, 0, 0.19);
    }
</style>
<link rel="stylesheet" href="{{ asset('vendor/fullcalendar/main.css') }}">
<script src="{{ asset('vendor/fullcalendar/main.js') }}"></script>
<link rel="stylesheet" href="{{ asset('vendor/apexcharts/dist/apexcharts.css') }}">
<script src="{{ asset('vendor/apexcharts/dist/apexcharts.min.js') }}"></script>
<input type="hidden" name="" id="auth" value="{{ Auth::user()->divisi_id }}">

<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Jadwal Perakitan</h1>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>

<div class="ml-3">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <nav>
                        <div class="nav nav-tabs topnav" id="nav-tab" role="tablist">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                                aria-controls="home" aria-selected="true">Kalender</a>
                            <a id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile"
                                aria-selected="false">Tabel</a>
                            {{-- <a id="produk-tab" data-toggle="tab" href="#produk" role="tab" aria-controls="produk"
                                aria-selected="false">Produk</a> --}}
                        </div>
                    </nav>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div id='calendar'></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <div class="col-md-12">
                <input type="hidden" name="userid" id="userid" value="{{ Auth::user()->id }}">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-layer-group"></i> Perakitan Bulan
                            {{ Carbon\Carbon::now()->isoFormat('MMMM') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-bordered" id="table_produk_perakitan">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th colspan="2" class="text-center">Tanggal</th>
                                            <th rowspan="2">Produk</th>
                                            <th rowspan="2">Jumlah Rakit</th>
                                            <th rowspan="2">Aksi</th>
                                        </tr>
                                        <tr>
                                            <th>Tgl Mulai</th>
                                            <th>Tgl Selesai</th>
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
        <div class="tab-pane fade" id="produk" role="tabpanel" aria-labelledby="produk-tab">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title"><i class="fas fa-cog"></i> Perakitan Produk Bulan  {{ Carbon\Carbon::now()->isoFormat('MMMM') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Perakitan-->
<div class="modal fade modalRakit" id="" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm">
                                <label for="">Nomor BPPB</label>
                                <div class="card" style="background-color: #C8E1A7">
                                    <div class="card-body">
                                        <span id="bppb">89798797856456</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Nama Produk</label>
                                <div class="card" style="background-color: #F89F81">
                                    <div class="card-body">
                                        <span id="produk">Produk 1</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Kategori</label>
                                <div class="card" style="background-color: #FCF9C4">
                                    <div class="card-body">
                                        <span id="kategori">Kategori 1</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm">
                                <label for="">Jumlah Rakit</label>
                                <div class="card" style="background-color: #FFCC83">
                                    <div class="card-body">
                                        <span id="jml">100 Unit</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Tanggal Mulai</label>
                                <div class="card" style="background-color: #FFE0B4">
                                    <div class="card-body">
                                        <span id="start">10-06-2021</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Tanggal Selesai</label>
                                <div class="card" style="background-color: #FFECB2">
                                    <div class="card-body">
                                        <span id="end">13-06-2021</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-striped scan-produk" id="scan">
                                    <thead>
                                        <tr>
                                            <th>Nomor Seri</th>
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
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" id="btnSave" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>
@stop

@section('adminlte_js')
<script>
    $(document).ready(function () {
        cal_jadwal();
        // $('#home-tab').on('click', function() {
        //     tab_jadwaldestroy();
        //     cal_jadwal();
        // })

        $('#profile-tab').on('click', function() {
            tab_jadwal();
        })

        $("#head-cb").on('click', function () {
            var isChecked = $("#head-cb").prop('checked')
            $('.cb-child').prop('checked', isChecked)
        });
    })

    // Charts
        // $(document).ready(function () {
        //     var options = {
        //     chart: {
        //         type: 'bar',
        //         height: 350,
        //     },
        //     plotOptions: {
        //         bar: {
        //         horizontal: true
        //         }
        //     },
        //     series: [{
        //         name: 'Jumlah',
        //         data: [30,40,45,50,49,60,70,91,125]
        //     }],
        //     xaxis: {
        //         categories: ['Produk 1', 'Produk 2', 'Produk 3', 'Produk 4', 'Produk 5', 'Produk 6', 'Produk 7', 'Produk 8', 'Produk 9'],
        //     }
        //     }

        //     var charts = new ApexCharts(document.querySelector(".chart"), options);

        //     charts.render();
        // });

    function modalRakit() {

        $('.modalRakit').modal('show');
        $("#head-cb").on('click', function () {
            var isChecked = $("#head-cb").prop('checked')
            $('.cb-child').prop('checked', isChecked)
        });
    }

    function transfer() {
        Swal.fire({
            title: "Apakah anda yakin?",
            text: "Data yang sudah di transfer tidak dapat diubah!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        });
    };

    function cal_jadwal() {
        var date = new Date()
        var d = date.getDate(),
            m = date.getMonth(),
            y = date.getFullYear()

        var Calendar = FullCalendar.Calendar;
        var calendarEl = document.getElementById('calendar');

        var calendar = new Calendar(calendarEl, {
            headerToolbar: {
                left: '',
                center: 'title',
                right: ''
            },
            locale: 'id',
            events: function (fetchInfo, successCallback, failureCallback) {
                $.ajax({
                    url: "/api/prd/ongoing-cal",
                    type: "post",
                    dataType: "json",
                    success: function (res) {
                        var events = [];
                        if (res != null) {
                            console.log(res);
                            $.each(res, function (i, item) {
                                events.push({
                                    start: item.tanggal_mulai,
                                    end: item.tanggal_selesai +
                                        'T23:59:59',
                                    title: item.produk.produk.nama +
                                        ' ' + item.produk.nama,
                                    backgroundColor: item.warna,
                                    borderColor: item.warna,
                                })
                            })
                        }
                        console.log('events', events);
                        successCallback(events);
                    }
                })
            }
        });

        calendar.render();
    }

    function tab_jadwaldestroy() {
        $('#table_produk_perakitan').DataTable().clear().destroy();
        $('.scan-produk').DataTable().clear().destroy();
    }

    function tab_jadwal() {
        $('#table_produk_perakitan').DataTable({
            destroy: true,
            processing: true,
            ajax: {
                url: "/api/prd/ongoing",
                type: "post",
            },
            columns: [{
                    data: "start"
                },
                {
                    data: "end"
                },
                {
                    data: "produk"
                },
                {
                    data: "jml"
                },
                {
                    data: "action"
                },
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            },
            "lengthChange": false,
            "ordering": false,
            "columnDefs": [{
                "targets": [4],
                "visible": document.getElementById('auth').value == '2' ? false : true
            }]
        });
        $('#table_produk_perakitan').css("width", "100%");
        var id = '';
        $(document).on('click', '.detailmodal', function () {
            id = $(this).data('id');
            console.log(id);
            var jml = $(this).data('jml');
            console.log(jml);

            $.ajax({
                url: "/api/prd/ongoing/h/" + id,
                dataType: "json",
                type: "get",
                success: function (res) {
                    $('span#bppb').text(res.no_bppb);
                    $('span#produk').text(res.produk);
                    $('span#kategori').text(res.kategori);
                    $('span#jml').text(res.jml);
                    $('span#start').text(res.start);
                    $('span#end').text(res.end);
                }
            })

            $('.modalRakit').modal('show');

            $('.scan-produk').DataTable().destroy();
            $('.scan-produk tbody').empty();

            var $table = $(".scan-produk");
            for (var i = 0; i < jml; i++) {
                var $row = $table.find("tbody").append("<tr></tr>").children("tr:eq(" + i + ")");
                for (var k = 0; k < 1; k++) {
                    $row.append(
                        '<td><input type="text" name="noseri[]" class="form-control noseri" style="text-transform:uppercase"><div class="invalid-feedback">Nomor seri ada yang sama.</div></td>'
                    );
                }
            }
            var scanProduk = $('.scan-produk').DataTable({
                ordering: false,
                "autoWidth": false,
                searching: false,
                "lengthChange": false,
                "pageLength": 10,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
                },
                columnDefs: [{
                    targets: [0],
                    width: "5%",
                }],
            });

            $(document).on('click', '#btnSave', function (e) {
                e.preventDefault();

                let arr = [];
                const data = scanProduk.$('.noseri').map(function () {
                    return $(this).val();
                }).get();

                data.forEach(function (item) {
                    if (item != '') {
                        arr.push(item);
                    }
                })

                const count = arr =>
                    arr.reduce((a, b) => ({
                        ...a,
                        [b]: (a[b] || 0) + 1
                    }), {})

                const duplicates = dict =>
                    Object.keys(dict).filter((a) => dict[a] > 1)

                if (duplicates(count(arr)).length > 0) {
                    $('.noseri').removeClass('is-invalid');
                    $('.noseri').filter(function () {
                        for (let index = 0; index < duplicates(count(arr))
                            .length; index++) {
                            if ($(this).val() == duplicates(count(arr))[index]) {
                                return true;
                            }
                        }
                    }).addClass('is-invalid');

                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Nomor seri ' + duplicates(count(arr)) +
                            ' ada yang sama.',
                    })
                } else {
                    $.ajax({
                        url: "/api/prd/cek-noseri",
                        type: "post",
                        data: {
                            noseri: arr,
                        },
                        success: function(res) {
                            if(res.error == true) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: res.msg,
                                });
                            } else {
                                // console.log('a');
                                $.ajax({
                                    url: "/api/prd/rakit-seri",
                                    type: "post",
                                    data: {
                                        "_token": "{{ csrf_token() }}",
                                        noseri: arr,
                                        userid: $('#userid').val(),
                                        jadwal_id: id,
                                    },
                                    success: function (res) {
                                        console.log(res);
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Berhasil',
                                            text: 'Data berhasil disimpan.',
                                        })
                                        $('.modalRakit').modal('hide');
                                        $('.scan-produk').DataTable().destroy();
                                        $('.scan-produk tbody').empty();
                                        $('#table_produk_perakitan').DataTable().ajax
                                            .reload();
                                        location.reload();
                                    }
                                })
                            }
                        }
                    })

                }
            })
        })
    }
</script>
@stop
