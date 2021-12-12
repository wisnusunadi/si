@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
<style>
    .nomor-so{
        background-color: #717FE1;
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 18px
    }
    .nomor-akn{
        background-color: #DF7458;
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 18px
    }
    .nomor-po{
        background-color: #85D296;
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 18px
    }
    .instansi{
        background-color: #36425E;
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 18px
    }
</style>
<input type="hidden" name="" id="auth" value="{{ Auth::user()->divisi_id }}">
<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="col-lg-12">
                <!-- Card -->
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-8">
                                <h3 class="card-title">Daftar SO Produksi</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered" id="gudang-barang">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nomor SO</th>
                                    <th>Customer</th>
                                    <th>Batas Transfer</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- <tr>
                                    <td>2</td>
                                    <td>8457938475938475</td>
                                    <td>Rumah Sakit Dr. Soetomo</td>
                                    <td>10 Oktober 2021</td>
                                    <td><span class="badge badge-primary">Pengecekan di QC</span></td>
                                    <td>
                                        <button class="btn btn-outline-info viewProduk"><i class="far fa-eye"></i>&nbsp;Detail</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>8457938475938475</td>
                                    <td>Rumah Sakit Dr. Soetomo</td>
                                    <td>10 Oktober 2021</td>
                                    <td><span class="badge badge-warning">Pengecekan di Gudang</span></td>
                                    <td>
                                        <button class="btn btn-outline-info viewProduk"><i class="far fa-eye"></i>&nbsp;Detail</button>
                                    </td>
                                </tr> --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="viewProdukModal" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row row-cols-2">
                                    {{-- col --}}
                                    <div class="col"> <label for="">Nomor SO</label>
                                        <div class="card nomor-so">
                                            <div class="card-body">
                                                <span id="so">89798797856456</span>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- col --}}
                                    <div class="col"> <label for="">Nomor AKN</label>
                                        <div class="card nomor-akn">
                                            <div class="card-body">
                                                <span id="akn">89798797856456</span>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- col --}}
                                    <div class="col"> <label for="">Nomor PO</label>
                                        <div class="card nomor-po">
                                            <div class="card-body">
                                                <span id="po">89798797856456</span>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- col --}}
                                    <div class="col"> <label for="">Customer</label>
                                        <div class="card instansi">
                                            <div class="card-body">
                                                <span id="instansi">RS. Dr. Soetomo</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped" id="view-produk">
                                    <thead>
                                        <tr>
                                            <th>Nama Produk</th>
                                            <th>Jumlah</th>
                                            {{-- <th>Tipe</th> --}}
                                            <th>Merk</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- <tr>
                                            <td>AMBULATORY BLOOD PRESSURE MONITOR</td>
                                            <td>100</td>
                                            <td>ABPM50</td>
                                            <td>ELITECH</td>
                                            <td><span class="badge badge-success">Sudah di cek</span></td>
                                        </tr>
                                        <tr>
                                            <td>AMBULATORY BLOOD PRESSURE MONITOR</td>
                                            <td>100</td>
                                            <td>RGB</td>
                                            <td>ELITECH</td>
                                            <td><span class="badge badge-danger">Belum di cek</span></td>
                                        </tr> --}}
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
@stop

@section('adminlte_js')
<script>
    $(document).ready(function () {
        // $('.addProduk').click(function (e) {
        //     $('#addProdukModal').modal('show');
        // });
        // $('.viewProduk').click(function (e) {
        //     $('#viewProdukModal').modal('show');
        // });
        $('#gudang-barang').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: '/api/tfp/data-so',
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'so', name: 'so'},
                { data: 'nama_customer', name: 'nama_customer'},
                { data: 'batas_out', name: 'batas_out'},
                { data: 'status_prd', name: 'status_prd'},
                { data: 'button_prd', name: 'button_prd'},
            ],
            "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
                }
        });
    });

    $(document).on('click', '.detailproduk', function() {
        var x = $(this).data('value');
        console.log(x);
        var id = $(this).data('id');
        console.log(id);

        $.ajax({
                url: "/api/tfp/header-so/" +id+"/"+x,
                success: function(res) {
                    console.log(res);
                    $('span#so').text(res.so);
                    $('span#po').text(res.po);
                    $('span#akn').text(res.akn);
                    $('span#instansi').text(res.customer);
                }
        });

        $('#view-produk').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: "/api/tfp/detail-so/" +id+"/"+x,
            },
            columns: [
                { data: 'produk', name: 'produk'},
                { data: 'qty', name: 'qty'},
                // { data: 'tipe', name: 'tipe'},
                { data: 'merk', name: 'merk'},
                { data: 'status_prd', name: 'status_prd'},
            ],
            "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
                }
        });

        $('#viewProdukModal').modal('show');
    })


    $('.add-produk').DataTable({
        'columnDefs': [{
            'targets': 0,
            'checkboxes': {
                'selectRow': true
            }
        }],
        'select': {
            'style': 'multi'
        },
        'order': [
            [1, 'asc']
        ],
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
        }
    });


</script>
@stop
