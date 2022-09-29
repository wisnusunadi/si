@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0  text-dark">Data Monitoring Listrik</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Beranda</a></li>
                <li class="breadcrumb-item active">Monitoring Listrik</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('adminlte_css')
<style>
    td.dt-control {
        background: url("/assets/image/logo/plus.png") no-repeat center center;
        cursor: pointer;
        background-size: 15px 15px;
    }
    tr.shown td.dt-control {
        background: url("/assets/image/logo/minus.png") no-repeat center center;
        background-size: 15px 15px;
    }

.bc-success{
    background-color:rgba(40, 167, 69, 0.2) !important;
}

.bc-danger{
    background-color: rgba(220, 53, 69, 0.2) !important;
}

.bc-warning{
    background-color:rgba(255, 193, 7, 0.2) !important;
}

.bc-grey{
    background-color: rgb(185, 185, 185) !important;
}

.bc-primary{
    background-color: rgba(0, 123, 255, 0.2) !important;
}

</style>

@stop

@section('content')
<section class="content">
<div class="container-fluid bg-white text-dark">
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active bg-white text-dark ml-2" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab"><h3>Data Record Listrik</h3>
          <ul class="nav nav-pills mb-3" id="pills-tab-1" role="tablist">
                <li class="nav-item ml-2">
                    <a class="nav-link active" id="pills-home-tab-1" data-toggle="pill" href="#pills-home-1" role="tab" aria-controls="pills-home-1" aria-selected="true">Real time</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-home-tab-2" data-toggle="pill" href="#pills-home-2" role="tab" aria-controls="pills-home-2" aria-selected="false">Non Real Time</a>
                </li>
          </ul>
        </div>
    </div>


    <div class="tab-content" id="pills-tab-1Content">
        <div class="tab-pane fade show active" id="pills-home-1" role="tabpanel" aria-labelledby="pills-home-1">

            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="ambil" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Pilih Panel
                </button>
                <div class="dropdown-menu" id="dropdownPanel" aria-labelledby="dropdownMenuButton">

                </div>
            </div>


                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="bc-primary text-primary px-auto py-2 col-3 " role="alert">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar3" viewBox="0 0 16 16">
                                        <path d="M14 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM1 3.857C1 3.384 1.448 3 2 3h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H2c-.552 0-1-.384-1-.857V3.857z"/>
                                        <path d="M6.5 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                                      </svg>
                                    Waktu
                                </div>
                                <div class="bc-success text-success px-auto py-2 ml-auto col-2" role="alert">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lightning-fill" viewBox="0 0 16 16">
                                        <path d="M5.52.359A.5.5 0 0 1 6 0h4a.5.5 0 0 1 .474.658L8.694 6H12.5a.5.5 0 0 1 .395.807l-7 9a.5.5 0 0 1-.873-.454L6.823 9.5H3.5a.5.5 0 0 1-.48-.641l2.5-8.5z"/>
                                      </svg>
                                    Nama
                                </div>
                                <div class="bc-warning text-warning px-auto py-2 ml-auto col-1" id="posisi" role="alert">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M8 1a3 3 0 1 0 0 6 3 3 0 0 0 0-6zM4 4a4 4 0 1 1 4.5 3.969V13.5a.5.5 0 0 1-1 0V7.97A4 4 0 0 1 4 3.999zm2.493 8.574a.5.5 0 0 1-.411.575c-.712.118-1.28.295-1.655.493a1.319 1.319 0 0 0-.37.265.301.301 0 0 0-.057.09V14l.002.008a.147.147 0 0 0 .016.033.617.617 0 0 0 .145.15c.165.13.435.27.813.395.751.25 1.82.414 3.024.414s2.273-.163 3.024-.414c.378-.126.648-.265.813-.395a.619.619 0 0 0 .146-.15.148.148 0 0 0 .015-.033L12 14v-.004a.301.301 0 0 0-.057-.09 1.318 1.318 0 0 0-.37-.264c-.376-.198-.943-.375-1.655-.493a.5.5 0 1 1 .164-.986c.77.127 1.452.328 1.957.594C12.5 13 13 13.4 13 14c0 .426-.26.752-.544.977-.29.228-.68.413-1.116.558-.878.293-2.059.465-3.34.465-1.281 0-2.462-.172-3.34-.465-.436-.145-.826-.33-1.116-.558C3.26 14.752 3 14.426 3 14c0-.599.5-1 .961-1.243.505-.266 1.187-.467 1.957-.594a.5.5 0 0 1 .575.411z"/>
                                      </svg>
                                    Posisi
                                </div>
                                <div class="bg-info text-info px-auto py-2 ml-auto col-2" role="alert">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-joystick" viewBox="0 0 16 16">
                                        <path d="M10 2a2 2 0 0 1-1.5 1.937v5.087c.863.083 1.5.377 1.5.726 0 .414-.895.75-2 .75s-2-.336-2-.75c0-.35.637-.643 1.5-.726V3.937A2 2 0 1 1 10 2z"/>
                                        <path d="M0 9.665v1.717a1 1 0 0 0 .553.894l6.553 3.277a2 2 0 0 0 1.788 0l6.553-3.277a1 1 0 0 0 .553-.894V9.665c0-.1-.06-.19-.152-.23L9.5 6.715v.993l5.227 2.178a.125.125 0 0 1 .001.23l-5.94 2.546a2 2 0 0 1-1.576 0l-5.94-2.546a.125.125 0 0 1 .001-.23L6.5 7.708l-.013-.988L.152 9.435a.25.25 0 0 0-.152.23z"/>
                                      </svg>
                                    Lokasi
                                </div>


                            </div>
                            <div class="row">
                                <div class="col-3" id="c">
                                    <div class="bg-secondary">Current A</div>
                                    <div>Current B</div>
                                    <div class="bg-secondary">Current C</div>
                                    <div>Current N</div>
                                    <div class="bg-secondary">Current G</div>
                                    <div>Current Avg</div>
                                </div>
                                <div class="col-3" id="vll">
                                    <div class="bg-secondary">Voltage A-B</div>
                                    <div>Voltage B-C</div>
                                    <div class="bg-secondary">Voltage C-A</div>
                                    <div>Voltage L-l Avg</div>
                                </div>
                                <div class="col-3">
                                    <div class="bg-secondary">Voltage A-N</div>
                                    <div>Voltage B-N</div>
                                    <div class="bg-secondary">Voltage C-N</div>
                                    <div>Voltage L-N Avg</div>
                                </div>
                                <div class="col-3">
                                    <div class="bg-secondary">Active Power A</div>
                                    <div>Active Power B</div>
                                    <div class="bg-secondary">Active Power C</div>
                                    <div>Active Power Total</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3">
                                    <div class="bg-secondary">Rective Power A</div>
                                    <div>Rective Power B</div>
                                    <div class="bg-secondary">Rective Power C</div>
                                    <div>Rective Power Total</div>
                                    <div class="bg-secondary">Frequency</div>
                                </div>
                                <div class="col-3">
                                    <div class="bg-secondary">Apparent Power A</div>
                                    <div>Apparent Power B</div>
                                    <div class="bg-secondary">Apparent Power C</div>
                                    <div>Apparent Power Total</div>
                                </div>
                                <div class="col-3">
                                    <div class="bg-secondary">Power Factor A</div>
                                    <div>Power Factor B</div>
                                    <div class="bg-secondary">Power Factor C</div>
                                    <div>Power Factor Total</div>
                                </div>
                                <div class="col-3">
                                    <div class="bg-secondary">Displacement Power Factor A</div>
                                    <div>Displacement Power Factor B</div>
                                    <div class="bg-secondary">Displacement Power Factor C</div>
                                    <div>Displacement Power Factor Total</div>
                                </div>
                            </div>

                        </div>


       </div>
        <div class="tab-pane fade show" id="pills-home-2" role="tabpanel" aria-labelledby="pills-home-2">
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Waktu
                </button>
                   <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                      <a class="dropdown-item filter_waktu" id="15m_filter"  data-value="15m">15 Menit</a>
                      <a class="dropdown-item filter_waktu" id="1j_filter"  data-value="1j">1 Jam</a>
                      <a class="dropdown-item filter_waktu" id="1h_filter"  data-value="1h">1 Hari</a>
                      <a class="dropdown-item filter_waktu" id="1b_filter"  data-value="1b">1 Bulan</a>
                   </div>

                   <div class="container" id="15m" text-align: center>
                    <div class="table-responsive">
                    <table class="table" id="non_real">
                        <thead class="thead-light">
                          <tr>
                            <th rowspan="2" width="20%" style="vertical-align : middle;text-align:center;">Date Time</th>
                            <th colspan="8" style="text-align: center">Avarage/Total</th>
                            <th rowspan="2" width="14%"style="vertical-align : middle;text-align:center;">Frequency</th>
                          </tr>
                          <tr>
                            <th scope="col" width="7%" style="vertical-align : middle;text-align:center;">Current(avg)</th>
                            <th scope="col" width="7%" style="vertical-align : middle;text-align:center;">Volatage L-L(avg)</th>
                            <th scope="col" width="7%" style="vertical-align : middle;text-align:center;">Volatage L-N (avg)</th>
                            <th scope="col" width="7%" style="text-align: center">Active Power (total) </th>
                            <th scope="col" width="7%" style="text-align: center">Reactive Power (total)</th>
                            <th scope="col" width="7%" style="text-align: center">Apparent Power (total)</th>
                            <th scope="col" width="7%" style="text-align: center">Power Factor (total)</th>
                            <th scope="col" width="7%" style="text-align: center">Displacement Power Factor (total)</th>
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

{{-- ---------------- --}}
</div>

</section>
@stop

@section('adminlte_js')
<script>
    $(document).ready(function () {
        getpanel();
    }(jQuery));
    function getpanel() {
        $.ajax({
        type:'get',
        url:'http://localhost:8000/listrik/ambilpanel',
        success:function(data) {
            let x = data.data.length;

            for(a = 1; a<=x; a++){
                $('#dropdownPanel').append(
                    '<a class="dropdown-item" href="#">'+data.data[a-1].device_id+'</a>'
                );
            }
        }
        });
    }

    //  var non_real = $('#non_real').DataTable({
    //    destroy: true,
    //        processing: true
    //        ajax: {
    //            'url': 'http://192.168.13.162:8000/listrik/data/15m',
    //            "dataType": "json"
    //        },
    //        language: {
    //            processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
    //        },
    //        columns: [{
    //                data: 'device_id',
    //            }
    //        ]
    //  });
    // function ambilPanel(data){
    //     let table = $('#non_real').DataTable({
    //         data,
    //         columns: [
    //             {
    //                 data: null,
    //                 render: function (data, type, full, meta) {
    //                     return  meta.row + 1;
    //                 }
    //             },
    //             {data: 'date_time'},
    //             {data: 'Current_Avg'},
    //             {data: 'Voltage_L_L_Avg'},
    //             {data: 'Voltage_L_N_Avg'},
    //             {data: 'Active_Power_Total'},
    //             {data: 'Reactive_Power_Total'},
    //             {data: 'Apparent_Power_Total'},
    //             {data: 'Power_Factor_Total'},
    //             {data: 'Displacement_Power_Factor_Total'},
    //             {data: 'Frequency'},
    //             {
    //                 data: null,
    //                 render: function ( data, type, full, meta )
    //             }
    //         ],
    //     });
    // }



      $('#15m').show();
      $("#15m_filter").addClass('active');

    hide();
      function hide(){
        $("#1j_filter").removeClass('active');
        $("#1h_filter").removeClass('active');
        $("#1b_filter").removeClass('active');

    }

     $(".filter_waktu").click(function(){
        var value = $(this).attr('data-value');
        console.log(value);
        $.ajax({
            type:'get',
            url:'http://localhost:8000/listrik/data/' + value,
            success:function(data) {
                // dataPanel.push(data.data)
                // ambilPanel(data.data)
                $('#non_real').DataTable().ajax.url('http://localhost:8000/listrik/data/' + value).load();
            }
        });
        $("#15m_filter").removeClass('active');

        hide();

        $('#'+value+"_filter").addClass('active');

    });

    $(function() {
        $('#non_real').DataTable({
            processing: true,
            serverSide: false,
            language: {
                processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
            },
            ajax: {
                'dataType': 'json',

                'url': 'http://localhost:8000/listrik/data/15m',
            },
            columns: [{
                    data: 'date_time',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'Current_Avg'
                },
                {
                    data: 'Voltage_L_L_Avg'
                },
                {
                    data: 'Voltage_L_N_Avg'
                },
                {
                    data: 'Active_Power_Total'
                },
                {
                    data: 'Reactive_Power_Total'
                },
                {
                    data: 'Apparent_Power_Total'
                },
                {
                    data: 'Power_Factor_Total'
                },
                {
                    data: 'Displacement_Power_Factor_Total'
                },
                {
                    data: 'Frequency'
                }
            ],
        });
    });


    function format(d) {
    // `d` is the original data object for the row
    return (
        '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">' +
        '<tr>' +
        '<td>Full name:</td>' +
        '<td>' +
        d.name +
        '</td>' +
        '</tr>' +
        '<tr>' +
        '<td>Extension number:</td>' +
        '<td>' +
        d.extn +
        '</td>' +
        '</tr>' +
        '<tr>' +
        '<td>Extra info:</td>' +
        '<td>And any further details here (images etc)...</td>' +
        '</tr>' +
        '</table>'
    );
}

$(document).ready(function () {
    var table = $('#example').DataTable({
        ajax: '../ajax/data/objects.txt',
        columns: [
            {
                className: 'dt-control',
                orderable: false,
                data: null,
                defaultContent: '',
            },
            { data: 'name' },
            { data: 'position' },
            { data: 'office' },
            { data: 'salary' },
        ],
        order: [[1, 'asc']],
    });

    // Add event listener for opening and closing details
    $('#example tbody').on('click', 'td.dt-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row(tr);

        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        } else {
            // Open this row
            row.child(format(row.data())).show();
            tr.addClass('shown');
        }
    });
});
</script>
@stop
