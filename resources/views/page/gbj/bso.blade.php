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
<input type="hidden" name="" id="auth" value="{{ Auth::user()->divisi_id }}">
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Transfer Produk Berdasarkan SO</h1>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="col-lg-12">
                <!-- Card -->
                <div class="card">
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
                                                    <span id="so"></span>
                                                </div>
                                              </div>
                                    </div>
                                    <div class="col-sm">
                                        <label for="">Nomor AKN</label>
                                        <div class="card nomor-akn">
                                            <div class="card-body">
                                                <span id="akn"></span>
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
                                <table class="table table-striped add-produk" id="addProduk">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="head-cb-produk"></th>
                                            <th>Nama Produk</th>
                                            <th>Jumlah</th>
                                            <th>Merk</th>
                                            <th>Aksi</th>
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
                <button type="button" class="btn btn-success" id="okk">Transfer</button>
                <button type="button" class="btn btn-info" id="rancang">Rancang</button>
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
                <table class="table table-striped scan-produk" id="scan">
                    <thead>
                        <tr>
                            <th>Nomor Seri</th>
                            <th><input type="checkbox" id="head-cb"></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" id="simpan">Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>
@stop

@section('adminlte_js')
<script>
    var mytable = '';

    $(document).ready(function () {
        $("#head-cb").on('click', function () {
            var isChecked = $("#head-cb").prop('checked')
            $('.cb-child').prop('checked', isChecked)
        });

        $("#head-cb-produk").on('click', function () {
            var isChecked = $("#head-cb-produk").prop('checked')
            $('.cb-child-prd').prop('checked', isChecked)
        });

        let a = $('#gudang-barang').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            ajax: {
                url: '/api/tfp/data-so',
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'so', name: 'so'},
                { data: 'nama_customer', name: 'nama_customer'},
                { data: 'batas_out', name: 'batas_out'},
                { data: 'status', name: 'status'},
                { data: 'button', name: 'button'},
            ],
            "columnDefs": [{
                "searchable": false,
                "orderable": false,
                "targets": 0
            }],
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

    });

    var id = '';
    $(document).on('click', '.editmodal', function(e) {
        var x = $(this).data('value');
        console.log(x);
        id = $(this).data('id');
            console.log(id);
            $.ajax({
                url: "/api/tfp/header-so/" +id+"/"+x,
                success: function(res) {
                    console.log(res);
                    $('span#so').text(res.so);
                    $('span#po').text(res.po);
                    $('span#akn').text(res.akn);
                    // $('span#instansi').text(res.customer);
                }
        });

        $('#addProduk').DataTable({
            // retrieve: true,
            destroy: true,
            autoWidth: false,
            processing: true,
            serverSide: true,
            "ordering": false,
            ajax: {
                url: "/api/tfp/detail-so/" +id+"/"+x,
            },
            columns: [
                {data: 'checkbox'},
                { data: 'produk', name: 'produk'},
                { data: 'qty', name: 'qty'},
                { data: 'merk', name: 'merk'},
                { data: 'action', name: 'action'},
            ],
            "order": [
                [1, 'asc']
            ],
            "oLanguage": {
            "sSearch": "Cari:"
            }
        })
        $('#addProdukModal').modal('show');
    });

    var prd = '';
    var jml = '';
    $(document).on('click', '.detailmodal', function(e) {
        var tr = $(this).closest('tr');
        prd = tr.find('#gdg_brg_jadi_id').val();
        jml = $(this).data('jml');
        console.log(jml);
        console.log(prd);
        // $('.scan-produk').DataTable().destroy();
        mytable = $('.scan-produk').DataTable({
            processing: false,
            serverSide: false,
            autoWidth: false,
            destroy: true,
            stateSave: true,
            "ordering": false,
            ajax: {
                url: "/api/tfp/seri-so",
                data: {gdg_barang_jadi_id: prd},
                type: "post",
            },
            columns: [
                { data: 'seri', name: 'seri'},
                { data: 'checkbox', name: 'checkbox'},
            ],
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

        $('.modal-scan').modal('show');
    });

    const prd1 = {};
    var t = 0;
    $(document).on('click', '#simpan', function(e) {

        const ids = [];
        $('.cb-child').each(function() {
            if($(this).is(":checked")) {
                if ($('.cb-child').filter(':checked').length > jml) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Melebihi Batas Maksimal'
                    })
                } else {
                    ids.push($(this).val());
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Noseri Berhasil Disimpan',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    $('.modal-scan').modal('hide');
                }
            }

        })
        prd1[prd] = ids;

        console.log(prd1);

    })

    $(document).on('click', '#rancang', function(e) {
        e.preventDefault();

        const prdd = [];
        const qtyy = [];

        $('.cb-child-prd').each(function() {
            if($(this).is(":checked")) {
                prdd.push($(this).val());
            }
        })
        // console.log(prdd);

        $('input[name^="gdg_brg_jadi_id"]').each(function() {
            prdd.push($(this).val());
        });

        $('input[name^="qty"]').each(function() {
            qtyy.push($(this).val());
        });

        $.ajax({
            url: "/api/tfp/byso",
            type: "post",
            data: {
                "_token": "{{ csrf_token() }}",
                pesanan_id: id,
                gdg_brg_jadi_id: prdd,
                qty: qtyy,
                noseri_id: prd1,
            },
            success: function(res) {
                console.log(res);
                // Swal.fire({
                //     position: 'center',
                //     icon: 'success',
                //     title: res.msg,
                //     showConfirmButton: false,
                //     timer: 1500
                // })
                // location.reload();
            }
        })
    })


    $(document).on('click', '#okk', function(e) {
        e.preventDefault();

        const prdd = [];
        const qtyy = [];

        $('input[name^="gdg_brg_jadi_id"]').each(function() {
            prdd.push($(this).val());
        });

        $('input[name^="qty"]').each(function() {
            qtyy.push($(this).val());
        });

        $.ajax({
            url: "/api/tfp/byso-final",
            type: "post",
            data: {
                "_token": "{{ csrf_token() }}",
                pesanan_id: id,
                gdg_brg_jadi_id: prdd,
                qty: qtyy,
                noseri_id: prd1,
            },
            success: function(res) {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: res.msg,
                    showConfirmButton: false,
                    timer: 1500
                })
                location.reload();
            }
        })
    })
</script>
@stop
