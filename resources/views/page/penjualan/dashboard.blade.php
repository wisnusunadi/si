@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Dashboard</h1>
@stop

@section('adminlte_css')
<style lang="scss">
    #pengirimantable thead
    {
        text-align:center;
    }

    #pengirimantable td:nth-child(5){
        text-align:right;
        white-space: nowrap;
    }

    #pengirimantable td:nth-child(1), td:nth-child(4), td:nth-child(6){
        text-align:center;
        white-space: nowrap;
    }

    #urgent{
        color:red;
    }

    #warning{
        color:#FFC700;
    }

    .fa-search:hover{
        color:#4682B4;
    }

    .fa-search:active{
        color:#C0C0C0;
    }
    @media screen and (min-width: 700px) {
        #pengirimantable{
            font-size:12px;
        }
        h4{
            font-size:20px;
        }
        #detailmodal{
            font-size:12px;
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
                            <h4>Penjualan 2021</h4>
                            </div>
                            <div class="chart">
                  <canvas id="areaChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
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
                            <h4>Batas Pengiriman</h4>
                            </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
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
                                    <tr>
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
                                        <td><hgroup>
                                                <p>04-11-2021</p>
                                                <small id="warning">7 Hari Lagi</small>
                                            </hgroup>
                                        </td>
                                        <td><a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr=""><i class="fas fa-search"></i></a></td>
                                    </tr>
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
        $(document).on('click', '.detailmodal', function(event) {
            event.preventDefault();
            var href = $(this).attr('data-attr');
            $.ajax({
                url: "{{route('penjualan.penjualan.detail', ['id' => 2])}}",
                beforeSend: function() {
                    $('#loader').show();
                },
                // return the result
                success: function(result) {
                    $('#detailmodal').modal("show");
                    $('#detail').html(result).show();
                    $("#detailform").attr("action", href);
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
    });
</script>
@stop