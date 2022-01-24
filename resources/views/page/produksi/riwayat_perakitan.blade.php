@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
<style>
    #DataTables_Table_0_filter{
        display: none;
    }
</style>
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Riwayat Perakitan</h1>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<section class="content">
    <div class="container-fluid">
        <div class="row ml-1">
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="">Pilih Produk</label>
                    <select name="" id="produk_select" class="form-control produk_select" multiple>
                        <option value="" selected="selected">All Produk</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="">Tanggal Perakitan</label>
                    <input type="text" name="" id="" class="form-control daterange">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="">Tanggal Pengiriman</label>
                    <input type="text" name="" id="" class="form-control daterange2">
                </div>
            </div>
            <div class="col-sm-3"></div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row text-center">
                            <div class="col-6">
                                <h3 class="font-weight-bold" id="h_rakit">{{ $rakit }}</h3>
                                <h4 class="font-weight-normal text-muted">Perakitan</h4>
                            </div>
                            <div class="col-6">
                                <h3 class="font-weight-bold" id="h_unit">{{ $unit }}</h3>
                                <h4 class="font-weight-normal text-muted">Unit Dirakit</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                        <table class="table table-history">
                            <thead class="thead-light">
                                <tr>
                                    <th>Waktu</th>
                                    <th>Tanggal & Waktu Perakitan</th>
                                    <th>Waktu1</th>
                                    <th>Tanggal & Waktu Pengiriman</th>
                                    <th>Nomor BPPB</th>
                                    <th>Produk</th>
                                    <th>Jumlah</th>
                                    <th>Aksi</th>
                                    <th>filter_rakit</th>
                                    <th>filter_kirim</th>
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

<!-- Modal -->
<div class="modal fade modal_id" id="modal_id" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm">
                                <label for="">Tanggal & Waktu Perakitan</label>
                                <div class="card-group">
                                    <div class="card" style="background-color: #C8E1A7">
                                        <div class="card-body">
                                            <p class="card-text" id="d_rakit">Senin 10-04-2021</p>
                                        </div>
                                    </div>
                                    <div class="card" style="background-color: #C8E1A7">
                                        <div class="card-body">
                                            <p class="card-text" id="t_rakit">08.00 WIB</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Tanggal & Waktu Pengiriman</label>
                                <div class="card-group">
                                    <div class="card" style="background-color: #FFECB2">
                                        <div class="card-body">
                                            <p class="card-text" id="d_kirim">Selasa 11-04-2021</p>
                                        </div>
                                    </div>
                                    <div class="card" style="background-color: #FFECB2">
                                        <div class="card-body">
                                            <p class="card-text" id="t_kirim">09.00 WIB</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm">
                                <label for="">Nomor BPPB</label>
                                <div class="card" style="background-color: #F89F81">
                                    <div class="card-body" id="bppb">
                                        516546546546546
                                    </div>
                                  </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Nama Produk</label>
                                <div class="card" style="background-color: #FCF9C4">
                                    <div class="card-body" id="produk">
                                        Produk 1
                                    </div>
                                  </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Jumlah</label>
                                <div class="card" style="background-color: #FFCC83">
                                    <div class="card-body" id="jml">
                                        100 Unit
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
    </div>
</div>
@stop

@section('adminlte_js')
<script>
        // Tanggal Perakitan
    var start_date;
    var end_date;
    var DateFilterFunction = (function (oSettings, aData, iDataIndex) {
        var dateStart = parseDateValue(start_date);
        var dateEnd = parseDateValue(end_date);

        var evalDate = parseDateValue(aData[8]);
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

    // Tanggal Pengiriman
    var start_date2;
    var end_date2;
    var DateFilterFunction2 = (function (oSettings, aData, iDataIndex) {
        var dateStart = parseDateValue2(start_date2);
        var dateEnd = parseDateValue2(end_date2);

        var evalDate = parseDateValue2(aData[9]);
        if ((isNaN(dateStart) && isNaN(dateEnd)) ||
            (isNaN(dateStart) && evalDate <= dateEnd) ||
            (dateStart <= evalDate && isNaN(dateEnd)) ||
            (dateStart <= evalDate && evalDate <= dateEnd)) {
            return true;
        }
        return false;
    });

    function parseDateValue2(rawDate) {
        var dateArray = rawDate.split("-");
        var parsedDate = new Date(dateArray[2], parseInt(dateArray[1]) - 1, dateArray[
        0]);
        return parsedDate;
    }
    $('.produk_select').select2();
    $.ajax({
        type: "get",
        url: "/api/prd/product_his_rakit",
        success: function (response) {
            $.each(response, function (a,b) {
                 $('#produk_select').append('<option value="'+b+'">'+b+'</option>');
            });
        }
    });
    $(document).on('click', '.detail', function() {
        var id = $(this).data('id');
        var time = $(this).data('tf');
        var rakit = $(this).data('rakit');
        console.log($(this).parent());
        $('p#t_rakit').text($(this).parent().prev().prev().prev().prev().prev().html());
        $('p#t_kirim').text($(this).parent().prev().prev().prev().prev().html());
        $('div#bppb').text($(this).parent().prev().prev().prev().html());
        $('div#produk').text($(this).parent().prev().prev().html());
        $('div#jml').text($(this).parent().prev().html());
        $.ajax({
            url: "/api/prd/history/header/" + id,
            type: "get",
            success: function(res) {
                console.log(res);
                $('p#d_rakit').text(res[0].day_rakit);
                $('p#d_kirim').text(res[0].day_kirim);
                $('p#t_rakit').text($(this).parent().prev().prev().prev().prev().prev().html());
                $('p#t_kirim').text($(this).parent().prev().prev().prev().prev().html());
            }
        });

        $('#modal_id').modal('show');

        $('.scan-produk').DataTable({
            destroy: true,
            "ordering":false,
            "autoWidth": false,
            "lengthChange": false,
            "pageLength": 10,
            processing: true,
            ajax: {
                url: "/api/prd/historySeri/" + id + "/" + time + "/" + rakit,
            },
            columns: [
                {data: 'no_seri'}
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
        });
    });
    // var groupCol = [0,2];
    var table = $('.table-history').DataTable({
        // "columnDefs": [
        //     { "visible": false, "targets": 0 },
        //     { "visible": false, "targets": 2 },
        //     { "visible": false, "targets": 8 },
        //     { "visible": false, "targets": 9 }
        // ],
        destroy: true,
        "lengthChange": false,
        "ordering": false,
        "bPaginate": false,
        "info": false,
        "responsive": true,
        "order": [[ 0, 'asc' ], [2, 'asc']],
        ajax: {
            url: "/api/prd/ajax_his_rakit",
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
        },
        columns: [
            {data: 'day_rakit'},
            {data: 'time_rakit'},
            {data: 'day_kirim'},
            {data: 'time_kirim'},
            {data: 'bppb'},
            {data: 'produk'},
            {data: 'jml'},
            {data: 'action'},
            {data: 'day_rakit_filter'},
            {data: 'day_kirim_filter'},
        ],
        // "drawCallback": function ( settings ) {
        //     var api = this.api();
        //     var rows = api.rows( {page:'current'} ).nodes();
        //     var last=null;

        //     api.column(groupCol, {page:'current'} ).data().each( function ( group, i, $currTable ) {
        //         // console.log(group);
        //         if (last !== group) {
        //             var rowData = api.row(i).data();


        //             $(rows).eq(i).before(
        //                 // console.log(rows[i].children[2].textContent)
        //             '<tr class="table-dark text-bold"><td colspan="1">' + group + '</td><td colspan="5">'+rows[i].children[2].textContent+'</td></tr>'
        //         );
        //             last = group;
        //         }
        //     });
        // },
        autoWidth: false,
        processing: true,
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
        },
    }).rowGrouping({
        bExpandableGrouping: true,
        asExpandedGroups: [0, 2],
        iGroupingColumnIndex: 2,
        sGroupBy: "letter",
        bHideGroupingColumn: false
    });

    $('#produk_select').change(function() {
        var search = [];

        $.each($('#produk_select option:selected'), function () {
            search.push($(this).val());
        });
        search = search.join('|');
        table.column(5).search(search, true, false).draw();
    });

    // Tanggal Perakitan
    $('.daterange').daterangepicker({
            autoUpdateInput: false
        });

        $('.daterange').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format(
                'DD-MM-YYYY'));
            start_date = picker.startDate.format('DD-MM-YYYY');
            end_date = picker.endDate.format('DD-MM-YYYY');
            $.fn.dataTableExt.afnFiltering.push(DateFilterFunction);
            table.draw();
        });

        $('.daterange').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
            start_date = '';
            end_date = '';
            $.fn.dataTable.ext.search.splice($.fn.dataTable.ext.search.indexOf(DateFilterFunction, 1));
            table.draw();
        });

    // Tanggal Pengiriman
    $('.daterange2').daterangepicker({
            autoUpdateInput: false
        });

        $('.daterange2').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format(
                'DD-MM-YYYY'));
            start_date2 = picker.startDate.format('DD-MM-YYYY');
            end_date2 = picker.endDate.format('DD-MM-YYYY');
            $.fn.dataTableExt.afnFiltering.push(DateFilterFunction2);
            table.draw();
        });

        $('.daterange2').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
            start_date2 = '';
            end_date2 = '';
            $.fn.dataTable.ext.search.splice($.fn.dataTable.ext.search.indexOf(DateFilterFunction2, 1));
            table.draw();
        });
</script>
@stop
