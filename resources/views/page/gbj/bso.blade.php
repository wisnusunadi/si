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
                                    <th>Nomor PO</th>
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
                                            <th>Produk</th>
                                            <th>Produk</th>
                                            {{-- <th><input type="checkbox" id="head-cb-produk"></th> --}}
                                            <th>Produk</th>
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
                {{-- <button type="button" class="btn btn-info" id="rancang">Rancang</button> --}}
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
                <h5 class="modal-title">Scan Produk <span id="namaproduk"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                 <input type="text" name="" id="dpp" class="dpp" hidden>
                 <input type="text" name="" id="jml" class="jml" hidden>
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

<div class="modal fade modal-scan-edit" id="modal-scan-edit" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
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
    let prd1 = {};

    $(document).ready(function () {
        $('#head-cb').prop('checked', false);
        $('#head-cb-produk').prop('checked', false);
        $('#head-cb-produk-edit').prop('checked', false);
        $('#head-cb-edit').prop('checked', false);
        $("#head-cb").on('click', function () {
            var isChecked = $("#head-cb").prop('checked')
            // $('.cb-child').prop('checked', isChecked)
            $('.scan-produk').DataTable()
                .column(1)
                .nodes()
                .to$()
                .find('input[type=checkbox]')
                .prop('checked', isChecked);

        });

        $("#head-cb-edit").on('click', function () {
            var isChecked = $("#head-cb").prop('checked')
            // $('.cb-child-edit').prop('checked', isChecked)
            $('.scan-produk-edit').DataTable()
                .column(1)
                .nodes()
                .to$()
                .find('input[type=checkbox]')
                .prop('checked', isChecked);
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
            destroy: true,
            ajax: {
                url: '/api/tfp/data-so',
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'so', name: 'so'},
                {data: 'no_po'},
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
            scrollY: 400,
            destroy: true,
            autoWidth: false,
            processing: true,
            serverSide: true,
            "ordering": false,
            bPaginate: false,
            ajax: {
                url: "/api/tfp/detail-so/" +id+"/"+x,
            },
            columns: [
                {data: 'detail_pesanan_id'},
                {data: 'paket'},
                // {data: 'checkbox'},
                { data: 'produk', name: 'produk'},
                { data: 'qty', name: 'qty'},
                { data: 'merk', name: 'merk'},
                { data: 'action', name: 'action'},
            ],
            "order": [
                [1, 'asc']
            ],
            "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last=null;

            api.column(0, {page:'current'} ).data().each( function ( group, i ) {

                if (last !== group) {
                    var rowData = api.row(i).data();
                    $(rows).eq(i).before(
                    '<tr class="table-dark text-bold"><td style="display:none;">'+group+'</td><td colspan="5">' + rowData.paket + '</td></tr>'
                );
                    last = group;
                }
            });
            },
            columnDefs: [
                {"targets": [0], "visible": false},
                {"targets": [1], "visible": false},
            ],
            "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
        }
        })
        // testing
        $.ajax({
            type: "get",
            url: "/api/tfp/detail-so/" +id+"/"+x,
            success: function (response) {
                console.log(response);
            }
        });
        $('#addProdukModal').modal('show');
    });

    function make_temp_array(prd1){
        let result = {};
        console.log("func",prd1)
        for (const dpp in prd1){
            for (let i = 0; i < prd1[dpp].noseri.length; i++){
                result[prd1[dpp].noseri[i]] = dpp
            }
        }
        console.log("res", result)
        return result;
    }

    var prd = '';
    var jml = '';
    var dpp = '';
    $(document).on('click', '.detailmodal', function(e) {
        let gh = $(this).parent().prev().prev().prev()[0].textContent
        let ghh = gh.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
        $('#namaproduk').html('<b>'+ghh+'</b>')
        // console.log(ghh);
        var tr = $(this).closest('tr');
        prd = tr.find('#gdg_brg_jadi_id').val();
        // console.log(prd);
        jml = $(this).parent().prev().prev().text();
        dpp = $(this).data('dpp');
        // dpp = $(this).parent().prev()
        // console.log(jml);

        let temp_array = make_temp_array(prd1)

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
                { data(data) {
                    // console.log("colm", data.ids, temp_array[data.ids])
                    if (temp_array[data.ids] !== undefined){
                        if (temp_array[data.ids] == dpp)
                            return `<input type="checkbox" class="cb-child" name="noseri_id[][]"  value="${data.ids}" checked>`
                        else
                        return '<span class="badge badge-info">Sudah Digunakan</span>'
                    }
                    else
                       return  `<input type="checkbox" class="cb-child" name="noseri_id[][]"  value="${data.ids}">`
                }},
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

    var t = 0;

    let dataTampungSeri = [];
    let textDataSeri = [];
    $('.scan-produk').on('click', '.cb-child', function(e) {
        if ($(this).is(':checked')) {
            dataTampungSeri.push($(this).val());
            textDataSeri.push($(this).parent().prev().text())
        } else {
            let index = dataTampungSeri.findIndex(x => x.noseri == $(this).val());
        }
    });
    $(document).on('click', '#simpan', function(e) {
        console.log(jml);
        console.log(dpp);
        console.log(prd);
        let a = $('.scan-produk').DataTable().column(1).nodes().to$().find('input[type=checkbox]:checked').map(function() {
            return $(this).val();
        }).get();

        if (a.length > jml) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Batas Maksimal '+jml+' Barang!',
            })
        } else {
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Noseri Berhasil Disimpan',
                showConfirmButton: false,
                timer: 1500
            })

            prd1[dpp] = {"jumlah": jml, "prd": prd, "noseri": dataTampungSeri};
            $('.modal-scan').modal('hide');
            if(prd1[dpp].noseri.length == 0) {
                delete prd1[dpp]
            }

        }
        console.log(prd1);
        // console.log("a", a);
        // console.log("dataTampungSeri", dataTampungSeri);
        // console.log(textDataSeri);
    })

    $(document).on('click', '#rancang', function(e) {
        e.preventDefault();

        $('.cb-child-prd').each(function() {
            if($(this).is(":checked")) {
            } else {
                delete prd1[$(this).val()]
            }
        })

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya Masukkan Draft'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire(
                        'Success!',
                        'Data Tersimpan ke Rancangan',
                        'success'
                    )
                    $.ajax({
                        url: "/api/tfp/byso",
                        type: "post",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            pesanan_id: id,
                            userid: $('#userid').val(),
                            data: prd1,
                        },
                        success: function(res) {
                           console.log(res);
                           location.reload();
                        }
                    })
                }
            })
    })

    $(document).on('click', '#okk', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Transfer it'
            }).then((result) => {
                if (result.isConfirmed) {
                    // $(this).attr('disabled', true);
                    $.ajax({
                        url: "/api/tfp/byso-final",
                        type: "post",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            pesanan_id: id,
                            userid: $('#userid').val(),
                            data: prd1,
                        },
                        success: function(res) {
                            console.log(res);
                            // Swal.fire(
                            //     'Success!',
                            //     'Data Terkirim ke QC',
                            //     'success'
                            // ).then(function() {
                            //     location.reload();
                            // })
                        }
                    })

                }
            });
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
        so = $(this).data('so');
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
        $('#modal-scan-edit').modal('show');
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
                        text: 'Batas Maksimal '+jml+' Barang!',
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
                    $('#modal-scan-edit').modal('hide');
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
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Draft it'
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).prop('disabled', true);
                    Swal.fire({
                        title: 'Please wait',
                        text: 'Data is transferring...',
                        allowOutsideClick: false,
                        showConfirmButton: false
                    });
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
                            Swal.fire(
                            'Success!',
                            'Data Rancangan Terkirim ke QC',
                            'success'
                        ).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                            })
                        }
                    })
                }
            })
    })
    $(document).on('click', '.detailmodal', function () {
        let jml = $(this).data('jml');
        let dpp = $(this).data('dpp');
        $('#jml').val(jml);
        $('#dpp').val(dpp);
    });

</script>
@stop
