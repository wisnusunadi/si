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
                    <i class="fas fa-layer-group"></i> Perakitan Bulan November
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <table class="table table_produk_perakitan">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Tgl Mulai</th>
                                    <th>Tgl Selesai</th>
                                    <th>Produk</th>
                                    <th>Jumlah Rakit</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td scope="row">16-06-2021 <br><span class="badge badge-primary">Baru</span></td>
                                    <td>18-06-2021 <br> <span class="badge badge-warning">Kurang 5 Hari</span></td>
                                    <td>Produk 1</td>
                                    <td>100 Unit <br> <span class="badge badge-dark">Kurang 50 Unit</span></td>
                                    <td>
                                        <button class="btn btn-outline-info" onclick="modalRakit()"><i class="far fa-edit"></i> Rakit Produk</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td scope="row">18-06-2021 <br><span class="badge badge-info">Revisi</span></td>
                                    <td>21-06-2021 <br> <span class="badge badge-danger">Lebih 10 Hari</span></td>
                                    <td>Produk 2</td>
                                    <td>200 Unit</td>
                                    <td>
                                        <button class="btn btn-outline-info" onclick="modalRakit()"><i class="far fa-edit"></i> Rakit Produk</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td scope="row">20-06-2021</td>
                                    <td>25-06-2021</td>
                                    <td>Produk 3</td>
                                    <td>300 Unit</td>
                                    <td>
                                        <button class="btn btn-outline-info" onclick="modalRakit()"><i class="far fa-edit"></i> Rakit Produk</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade modalRakit" id="" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
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
                                            89798797856456
                                        </div>
                                      </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Nama Produk</label>
                                <div class="card" style="background-color: #F89F81">
                                    <div class="card-body">
                                        Produk 1
                                    </div>
                                  </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Kategori</label>
                                <div class="card" style="background-color: #FCF9C4">
                                    <div class="card-body">
                                        Kategori 1
                                    </div>
                                  </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm">
                                <label for="">Jumlah Rakit</label>
                                <div class="card" style="background-color: #FFCC83">
                                    <div class="card-body">
                                        100 Unit
                                    </div>
                                  </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Tanggal Mulai</label>
                                <div class="card" style="background-color: #FFE0B4">
                                    <div class="card-body">
                                        10-06-2021
                                    </div>
                                  </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Tanggal Selesai</label>
                                <div class="card" style="background-color: #FFECB2">
                                    <div class="card-body">
                                        13-06-2021
                                    </div>
                                  </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-striped scan-produk">
                                    <thead>
                                        <tr>
                                            <th>Nomor Seri</th>
                                            <th>Nomor Seri</th>
                                            <th>Nomor Seri</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="text" name="" id="" class="form-control"></td>
                                            <td><input type="text" name="" id="" class="form-control"></td>
                                            <td><input type="text" name="" id="" class="form-control"></td>
                                        </tr>
                                        <tr>
                                            <td><input type="text" name="" id="" class="form-control"></td>
                                            <td><input type="text" name="" id="" class="form-control"></td>
                                            <td><input type="text" name="" id="" class="form-control"></td>
                                        </tr>
                                        <tr>
                                            <td><input type="text" name="" id="" class="form-control"></td>
                                            <td><input type="text" name="" id="" class="form-control"></td>
                                            <td><input type="text" name="" id="" class="form-control"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary">Simpan</button>
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
            events: [
                {
                    title: 'Perakitan Produk 1',
                    start: new Date(y, m, 1),
                    end: new Date(y, m, 3),
                    backgroundColor: '#FF0000', //red
                    borderColor: '#FF0000' //red
                },
                {
                    title: 'Perakitan Produk 2',
                    start: new Date(y, m, d - 5, 15, 25),
                    end: new Date(y, m, d - 2), 
                    backgroundColor: '#AF0404', //yellow
                    borderColor: '#AF0404   ' //yellow
                },
                {
                    title: 'Perakitan Produk 3',
                    start: new Date(y, m, d, 10, 30),
                    end: new Date(y, m, d + 3, 14, 0),
                    allDay: false,
                    backgroundColor: '#414141', //Blue
                    borderColor: '#414141' //Blue
                },
                {
                    title: 'Perakitan Produk 4',
                    start: new Date(y, m, d, 10, 30),
                    end: new Date(y, m, d + 5, 14, 0),
                    allDay: false,
                    backgroundColor: '#252525', //Blue
                    borderColor: '#252525' //Blue
                },
            ],
        });

        calendar.render();

        $('.table_produk_perakitan').DataTable({
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
        },
        "lengthChange": false,
        "ordering": false,
        });

        $('.scan-produk').DataTable({
            "ordering":false,
            "autoWidth": false,
            searching: false,
            "lengthChange": false,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
        });
    })
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
    
</script>
@stop
