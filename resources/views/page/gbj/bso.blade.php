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
</style>
<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="col-lg-12">
                <!-- Card -->
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-8">
                                <h3 class="card-title">Transfer Produk Berdasarkan SO</h3>
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
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>8457938475938475</td>
                                    <td>Rumah Sakit Dr. Soetomo</td>
                                    <td>10 Oktober 2021</td>
                                    {{-- Menggunakan Perkondisian Jika Data Sudah Dirancang Maka Tampil Seperti ini --}}
                                    <td><span class="badge badge-info">Tersimpan ke rancangan</span></td>
                                    <td>
                                        <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                            <div class="dropdown-menu">
                                                <button type="button" class="dropdown-item addProduk" id="">
                                                    <i class="fas fa-plus"></i>&nbsp;Siapkan Produk
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>8457938475938475</td>
                                    <td>Rumah Sakit Dr. Soetomo</td>
                                    <td>10 Oktober 2021</td>
                                    {{-- Menggunakan Perkondisian Jika Produk Belum Keluar Maka Tampil Seperti ini --}}
                                    <td><span class="badge badge-danger">Produk belum disiapkan</span></td>
                                    <td>
                                        <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                            <div class="dropdown-menu">
                                                <button type="button" class="dropdown-item addProduk" id="">
                                                    <i class="fas fa-plus"></i>&nbsp;Siapkan Produk
                                                </button>
                                            </div>
                                        </div>
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

<!-- Modal Add-->
<div class="modal fade" id="addProdukModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Transfer Produk
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
                                <div class="row">
                                    <div class="col-sm">
                                        <label for="">Nomor SO</label>
                                            <div class="card nomor-so">
                                                <div class="card-body">
                                                    <span id="so">89798797856456</span>
                                                </div>
                                              </div>
                                    </div>
                                    <div class="col-sm">
                                        <label for="">Nomor AKN</label>
                                        <div class="card nomor-akn">
                                            <div class="card-body">
                                                <span id="akn">89798797856456</span>
                                            </div>
                                          </div>
                                    </div>
                                    <div class="col-sm">
                                        <label for="">Nomor PO</label>
                                        <div class="card nomor-po">
                                            <div class="card-body">
                                                <span id="po">89798797856456</span>
                                            </div>
                                          </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="" id="myForm" method="post">
                                <table class="table table-striped add-produk" id="addProduk">
                                    <thead>
                                        <tr>
                                            {{-- <th></th> --}}
                                            <th>Nama Produk</th>
                                            <th>Jumlah</th>
                                            <th>Tipe</th>
                                            <th>Merk</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            {{-- <td></td> --}}
                                            <td>AMBULATORY BLOOD PRESSURE MONITOR</td>
                                            <td>100 Unit</td>
                                            <td>ABPM50</td>
                                            <td>ELITECH</td>
                                            <td><button class="btn btn-primary" data-toggle="modal" data-target=".modal-scan"><i
                                                        class="fas fa-qrcode"></i> Scan Produk</button></td>
                                        </tr>
                                        <tr>
                                            {{-- <td></td> --}}
                                            <td>AMBULATORY BLOOD PRESSURE MONITOR</td>
                                            <td>100 Unit</td>
                                            <td>RGB</td>
                                            <td>ELITECH</td>
                                            <td><button class="btn btn-primary" data-toggle="modal" data-target=".modal-scan"><i
                                                        class="fas fa-qrcode"></i> Scan Produk</button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="btnTf">Transfer</button>
                <button type="button" class="btn btn-info" id="btnDraft">Rancang</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Scan Product --}}
<!-- Modal -->
<div class="modal fade modal-scan" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Scan Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-striped scan-produk">
                    <thead>
                        <tr>
                            <th>Nomor Seri</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>36541654654654564</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>78656562646545646</td>
                            <td></td>

                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </form>
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
        // let t = $('.add-produk').DataTable({
        //     'columnDefs': [{
        //         'targets': 0,
        //         'checkboxes': {
        //             'selectRow': true
        //         }
        //     }],
        //     'select': {
        //         'style': 'multi'
        //     },
        //     'order': [
        //         [1, 'asc']
        //     ],
        //     "oLanguage": {
        //     "sSearch": "Cari:"
        //     }
        // });

        $('.scan-produk').DataTable({
            'columnDefs': [{
                'targets': 1,
                'checkboxes': {
                    'selectRow': true
                }
            }],
            'select': {
                'style': 'multi'
            },
            'order': [
                [0, 'asc']
            ],
            "oLanguage": {
            "sSearch": "Masukkan Nomor Seri:"
            }
        });
    });

    let a = $('#gudang-barang').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/api/tfp/data-so',
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'so', name: 'so'},
                { data: 'nama_customer', name: 'nama_customer'},
                { data: 'tgl_kontrak', name: 'tgl_kontrak'},
                { data: 'status', name: 'status'},
                { data: 'button', name: 'button'},
            ],
            "columnDefs": [{
                "searchable": false,
                "orderable": false,
                "targets": 0
            }],
            // "order": [
            //     [3, 'desc']
            // ],
            "oLanguage": {
            "sSearch": "Cari:"
            }
    });

    a.on('order.dt search.dt', function () {
        a.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();


    $(document).on('click', '.editmodal', function(e) {
        var id = $(this).data('id');
        console.log(id);
        $.ajax({
            url: "/api/tfp/header-so/" +id,
            success: function(res) {
                console.log(res);
                $('span#so').text(res.so);
                $('span#po').text(res.no_po);
                $('span#akn').text(res.ekatalog.no_paket);
            }
        });
        $('#addProduk').DataTable().destroy();
        var mytable = $('#addProduk').DataTable({
            // retrieve: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "/api/tfp/detail-so/" +id,
                // data: {id: id},
                // type: "post",
                // dataType: "json",
            },
            columns: [
                // { data: 'ids', name: 'ids'},
                { data: 'produk', name: 'produk'},
                { data: 'qty', name: 'qty'},
                { data: 'tipe', name: 'tipe'},
                { data: 'merk', name: 'merk'},
                { data: 'action', name: 'action'},
            ],
            // 'columnDefs': [{
            //     'targets': 0,
            //     'checkboxes': {
            //         'selectRow': true
            //     }
            // }],
            'select': {
                'style': 'multi'
            },
            'order': [
                [1, 'asc']
            ],
            "oLanguage": {
            "sSearch": "Cari:"
            }
        })
        $('#addProdukModal').modal('show');

        $(document).on('click', '#btnDraft', function(e) {
            e.preventDefault();

            const ids = [];
            const qtyy = [];

            $('input[name^="gdg_brg_jadi_id"]').each(function() {
                ids.push($(this).val());
            });

            $('input[name^="qty"]').each(function() {
                qtyy.push($(this).val());
            });
            // $.each(jml, function(index, rowIdd) {
            //     console.log(index);
            //     // qtyy.push(rowId);
            // });

            $.ajax({
                url: "/api/tfp/byso",
                type: "post",
                data: {
                    "_token": "{{ csrf_token() }}",
                    pesanan_id: id,
                    gdg_brg_jadi_id: ids,
                    qty: qtyy,
                },
                success: function(res) {
                    console.log(res);
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: res.msg,
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
            })
        })
    });

    // $('#myForm').on('submit', function(e) {
    //     var form = this;
    //     var rowsel = mytable.column(0).checkboxes.selected();
    //     $.each(rowsel, function(index, rowId) {
    //         console.log(rowId);
    //     });
    //     e.preventDefault();
    // })



    $(document).on('click', '.detailmodal', function(e) {
        var id = $(this).data('id');
        console.log(id);
        $('#viewProdukModal').modal('show');
    });
</script>
@stop
