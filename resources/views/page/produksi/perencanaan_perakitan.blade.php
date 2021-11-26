@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
<link rel="stylesheet" href="{{ asset('vendor/fullcalendar/main.css') }}">
<script src="{{ asset('vendor/fullcalendar/main.js') }}"></script>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div id='calendar'></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-layer-group"></i> Perencanaan Perakitan Bulan Desember
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <table class="table table-bordered table-produk-perakitan">
                            <thead class="thead-dark">
                                <tr>
                                    <th colspan="2" class="text-center">Tanggal</th>
                                    <th rowspan="2">Produk</th>
                                    <th rowspan="2">Jumlah</th>
                                </tr>
                                <tr>
                                    <th class="text-center">Tgl Mulai</th>
                                    <th class="text-center">Tgl Selesai</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- <tr>
                                    <td scope="row" class="text-center">18-11-2021</td>
                                    <td class="text-center">20-11-2021</td>
                                    <td>Produk 1</td>
                                    <td>100 Unit</td>
                                </tr>
                                <tr>
                                    <td scope="row" class="text-center">21-11-2021</td>
                                    <td class="text-center">23-11-2021</td>
                                    <td>Produk 2</td>
                                    <td>200 Unit</td>
                                </tr> --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('adminlte_js')
<script>
    $(function () {


        /* initialize the calendar
         -----------------------------------------------------------------*/
        //Date for the calendar events (dummy data)
        var date = new Date()
        var d = date.getDate(),
            m = date.getMonth(),
            y = date.getFullYear()

        var Calendar = FullCalendar.Calendar;
        var calendarEl = document.getElementById('calendar');

        // initialize the external events
        // -----------------------------------------------------------------

        var calendar = new Calendar(calendarEl, {
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            //Random default events
            // events: [
            //     {
            //         title: 'Perakitan Perakitan 1',
            //         start: new Date(y, m, d+31),
            //         end: new Date(y, m, d+33),
            //         backgroundColor: '#FF0000', //red
            //         borderColor: '#FF0000' //red
            //     },
            //     {
            //         title: 'Perakitan Perakitan 2',
            //         start: new Date(y, m, d + 35, 15, 25),
            //         end: new Date(y, m, d + 32),
            //         backgroundColor: '#AF0404', //yellow
            //         borderColor: '#AF0404   ' //yellow
            //     },
            //     {
            //         title: 'Perakitan Perakitan 3',
            //         start: new Date(y, m, d +32, 10, 30),
            //         end: new Date(y, m, d + 34, 14, 0),
            //         allDay: false,
            //         backgroundColor: '#414141', //Blue
            //         borderColor: '#414141' //Blue
            //     },
            //     {
            //         title: 'Perakitan Perakitan 4',
            //         start: new Date(y, m, +38, 10, 30),
            //         end: new Date(y, m, d + 48, 14, 0),
            //         allDay: false,
            //         backgroundColor: '#252525', //Blue
            //         borderColor: '#252525' //Blue
            //     },
            // ],
            events: function( fetchInfo, successCallback, failureCallback ) {
                $.ajax({
                    url: "/api/prd/plan-cal",
                    type: "post",
                    dataType: "json",
                    success: function(res) {
                        var events = [];
                        if (res != null) {
                            console.log(res);
                            $.each(res, function(i, item) {
                                events.push({
                                    start: item.tanggal_mulai,
                                    end: item.tanggal_selesai + 'T23:59:59',
                                    title: item.produk.produk.nama + '-' + item.produk.nama,
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
        var oTable = $('.table-produk-perakitan').DataTable({
            destroy: true,
            "paging": true,
            "lengthChange": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "/api/prd/plan",
                type: "post",
            },
            columns: [
                {data: "start"},
                {data: "end"},
                {data: "produk"},
                {data: "jml"},
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
        });
    })
</script>
@stop
