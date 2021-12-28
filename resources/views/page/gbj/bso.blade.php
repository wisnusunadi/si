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
<input type="hidden" name="userid" id="userid" value="{{ Auth::user()->id }}">
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
                                                <span id="po"></span>
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

<div class="modal fade" id="editProdukModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
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
                                                    <span id="so-edit"></span>
                                                </div>
                                              </div>
                                    </div>
                                    <div class="col-sm">
                                        <label for="">Nomor AKN</label>
                                        <div class="card nomor-akn">
                                            <div class="card-body">
                                                <span id="akn-edit"></span>
                                            </div>
                                          </div>
                                    </div>
                                    <div class="col-sm">
                                        <label for="">Nomor PO</label>
                                        <div class="card nomor-po">
                                            <div class="card-body">
                                                <span id="po-edit"></span>
                                            </div>
                                          </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped add-produk" id="editProduk">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="head-cb-produk-edit"></th>
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
                <button type="button" class="btn btn-success" id="okk-edit">Transfer</button>
                <button type="button" class="btn btn-info" id="rancang-edit">Rancang</button>
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

<div class="modal fade modal-scan-edit" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Scan Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-striped scan-produk-edit" id="scan-edit">
                    <thead>
                        <tr>
                            <th>Nomor Seri</th>
                            <th><input type="checkbox" id="head-cb-edit"></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" id="simpan-edit">Simpan</button>
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
           if ($(this).is(':checked')) {
                var isChecked = $("#head-cb-produk").prop('checked')
                $('.cb-child-prd').prop('checked', isChecked)
                $('.cb-child-prd').parent().next().next().next().next().children().find('button').removeClass('disabled').attr('disabled', false);
           } else {
                $('.cb-child-prd').prop('checked', false)
                $('.cb-child-prd').parent().next().next().next().next().children().find('button').removeClass('disabled').attr('disabled', true);
           }
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
            "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
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

    // add
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
            }
        });

        $('#addProduk').DataTable({
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
            "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
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
            },
            "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
        }
        });

        $('.modal-scan').modal('show');
    });

    const prd1 = {};
    var t = 0;
    $(document).on('click', '#simpan', function(e) {
        $('.cb-child-prd').each(function() {
            if($(this).is(":checked")) {
                if (!prd1[$(this).val()])
                    prd1[$(this).val()] = {"jumlah": $(this).parent().next().next().children().val(), "noseri": []};
            } else {
                delete prd1[$(this).val()]
            }
        })

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
        prd1[prd].noseri = ids;
    })

    $(document).on('click', '#rancang', function(e) {
        e.preventDefault();

        $('.cb-child-prd').each(function() {
            if($(this).is(":checked")) {
            } else {
                delete prd1[$(this).val()]
            }
        })

        $.ajax({
            url: "/api/tfp/byso",
            type: "post",
            data: {
                "_token": "{{ csrf_token() }}",
                pesanan_id: id,
                data: prd1,
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

        $('.cb-child-prd').each(function() {
            if($(this).is(":checked")) {
            } else {
                delete prd1[$(this).val()]
            }
        })
        $.ajax({
            url: "/api/tfp/byso-final",
            type: "post",
            data: {
                "_token": "{{ csrf_token() }}",
                pesanan_id: id,
                data: prd1,
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
    });

    $(document).on('click', '.cb-child-prd', function () {
        if ($(this).is(":checked")) {
            $(this).parent().next().next().next().next().children().find('button').removeClass('disabled').attr('disabled', false);
        } else {
            $(this).parent().next().next().next().next().children().find('button').addClass('disabled').attr('disabled', true);
        }
    })

    // edit
    $(document).on('click', '.ubahmodal', function(e) {
        var x = $(this).data('value');
        console.log(x);

        id = $(this).data('id');
        console.log(id);
        $.ajax({
            url: "/api/tfp/header-so/" +id+"/"+x,
            success: function(res) {
                console.log(res);
                $('span#so-edit').text(res.so);
                $('span#po-edit').text(res.po);
                $('span#akn-edit').text(res.akn);
            }
        });

        $('#editProduk').DataTable({
            destroy: true,
            autoWidth: false,
            processing: true,
            serverSide: true,
            "ordering": false,
            ajax: {
                url: "/api/tfp/edit-so/" +id+"/"+x,
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
            "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
        }
        })
        $('#editProdukModal').modal('show');
    })

    $(document).on('click', '.serimodal', function(e) {
        var tr = $(this).closest('tr');
        prd = tr.find('#gdg_brg_jadi_id').val();
        jml = $(this).data('jml');

        $('.scan-produk-edit').DataTable({
            processing: false,
            serverSide: false,
            autoWidth: false,
            destroy: true,
            stateSave: true,
            "ordering": false,
            ajax: {
                url: "/api/tfp/seri-edit-so",
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
            },
                "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
        });
        $('.modal-scan-edit').modal('show');
    })

    $(document).on('click', '.cb-child-edit', function() {
        if ($(this).is(':checked')) {
            console.log($(this).val());
            $.ajax({
                url: "/api/tfp/updateChecked",
                type: "post",
                data: {id: $(this).val()},
                success: function(res) {
                    console.log('Checked');
                }
            })
        } else {
            console.log('uncheck');
            $.ajax({
                url: "/api/tfp/updateCheck",
                type: "post",
                data: {id: $(this).val()},
                success: function(res) {
                    console.log('Unchecked');
                }
            })
        }
    })
    var editPrd = {};
    $(document).on('click', '#simpan-edit', function() {
        $('.cb-prd-edit').each(function() {
            if($(this).is(":checked")) {
                if (!editPrd[$(this).val()])
                editPrd[$(this).val()] = {"jumlah": $(this).parent().next().next().children().val(), "noseri": []};
            } else {
                delete editPrd[$(this).val()]
            }
        })

        const ids = [];
        $('.cb-child-edit').each(function() {
            if($(this).is(":checked")) {
                if ($('.cb-child-edit').filter(':checked').length > jml) {
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
                    $('.modal-scan-edit').modal('hide');
                }
            }

        })
        editPrd[prd].noseri = ids;
        console.log(editPrd);
    })

    $(document).on('click', '#rancang-edit', function(e) {
        e.preventDefault();
        $('.cb-prd-edit').each(function() {
            if($(this).is(":checked")) {
            } else {
                delete editPrd[$(this).val()]
            }
        })
        console.log(editPrd);
        $.ajax({
            url: "/api/tfp/updateRancangSO",
            type: "post",
            data: {
                "_token": "{{ csrf_token() }}",
                pesanan_id: id,
                userid: $('#userid').val(),
                data: editPrd,
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
                location.reload();
            }
        })
    })

    $(document).on('click', '#okk-edit', function(e) {
        e.preventDefault();
        $('.cb-prd-edit').each(function() {
            if($(this).is(":checked")) {
            } else {
                delete editPrd[$(this).val()]
            }
        })
        console.log(editPrd);
        $.ajax({
            url: "/api/tfp/updateFinalSO",
            type: "post",
            data: {
                "_token": "{{ csrf_token() }}",
                pesanan_id: id,
                userid: $('#userid').val(),
                data: editPrd,
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
                location.reload();
            }
        })
    })

</script>
@stop
