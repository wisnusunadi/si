@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
<link rel="stylesheet" href="{{ asset('vendor/fullcalendar/main.css') }}">
<script src="{{ asset('vendor/fullcalendar/main.js') }}"></script>
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <div id='calendar'></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-layer-group"></i> Produk Perakitan Tahap 1
                </h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th>No</th>
                            <th>Produk</th>
                            <th>Jumlah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td scope="row">1</td>
                            <td>Produk 1</td>
                            <td>100 Unit</td>
                            <td><button class="btn btn-outline-info" onclick="modalRakit()"><i class="far fa-edit"></i>Rakit Produk</button></td>
                        </tr>
                        <tr>
                            <td scope="row">2</td>
                            <td>Produk 2</td>
                            <td>200 Unit</td>
                            <td><button class="btn btn-outline-info" onclick="modalRakit()"><i class="far fa-edit"></i>Rakit Produk</button></td>
                        </tr>
                        <tr>
                            <td scope="row">3</td>
                            <td>Produk 3</td>
                            <td>300 Unit</td>
                            <td><button class="btn btn-outline-info" onclick="modalRakit()"><i class="far fa-edit"></i>Rakit Produk</button></td>
                        </tr>
                    </tbody>
                </table>
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
                                    <div class="card nomor-so">
                                        <div class="card-body">
                                            89798797856456
                                        </div>
                                      </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Nama Produk</label>
                                <div class="card nomor-akn">
                                    <div class="card-body">
                                        Produk 1
                                    </div>
                                  </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm">
                                <label for="">Kategori</label>
                                <div class="card nomor-po">
                                    <div class="card-body">
                                        Kategori 1
                                    </div>
                                  </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Jumlah Produk</label>
                                <div class="card nomor-po">
                                    <div class="card-body">
                                        100 Unit
                                    </div>
                                  </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped scan-produk">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="head-cb"></th>
                                    <th>Nomor Seri</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="checkbox" class="cb-child" value="1"></td>
                                    <td><input type="text" name="" id="" class="form-control"></td>
                                        <td>
                                          <button class="btn btn-success"><i class="fas fa-plus"></i></button>&nbsp;
                                          <button class="btn btn-danger"><i class="fas fa-minus"></i></button>
                                      </td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" class="cb-child" value="2"></td>
                                    <td><input type="text" name="" id="" class="form-control"></td>
                                        <td>
                                          <button class="btn btn-success"><i class="fas fa-plus"></i></button>&nbsp;
                                          <button class="btn btn-danger"><i class="fas fa-minus"></i></button>
                                      </td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" class="cb-child" value="3"></td>
                                    <td><input type="text" name="" id="" class="form-control"></td>
                                        <td>
                                          <button class="btn btn-success"><i class="fas fa-plus"></i></button>&nbsp;
                                          <button class="btn btn-danger"><i class="fas fa-minus"></i></button>
                                      </td>
                                </tr>
                            </tbody>
                        </table>
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
                    title: 'Perakitan Tahap 1',
                    start: new Date(y, m, 1),
                    end: new Date(y, m, 3),
                    backgroundColor: '#FF0000', //red
                    borderColor: '#FF0000' //red
                },
                {
                    title: 'Perakitan Tahap 2',
                    start: new Date(y, m, d - 5, 15, 25),
                    end: new Date(y, m, d - 2), 
                    backgroundColor: '#AF0404', //yellow
                    borderColor: '#AF0404   ' //yellow
                },
                {
                    title: 'Perakitan Tahap 3',
                    start: new Date(y, m, d, 10, 30),
                    end: new Date(y, m, d + 3, 14, 0),
                    allDay: false,
                    backgroundColor: '#414141', //Blue
                    borderColor: '#414141' //Blue
                },
                {
                    title: 'Perakitan Tahap 4',
                    start: new Date(y, m, d, 10, 30),
                    end: new Date(y, m, d + 5, 14, 0),
                    allDay: false,
                    backgroundColor: '#252525', //Blue
                    borderColor: '#252525' //Blue
                },
            ],
        });

        calendar.render();
    })
    function modalRakit() { 
        $('.modalRakit').modal('show');
        $("#head-cb").on('click', function () {
            var isChecked = $("#head-cb").prop('checked')
            $('.cb-child').prop('checked', isChecked)
        });
    }
</script>
@stop
