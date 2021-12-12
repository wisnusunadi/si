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
                                {{-- <tr>
                                    <td>1</td>
                                    <td>8457938475938475</td>
                                    <td>Rumah Sakit Dr. Soetomo</td>
                                    <td>10 Oktober 2021</td>
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
                                </tr> --}}
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
                                            {{-- <th></th> --}}
                                            <th>Nama Produk</th>
                                            <th>Jumlah</th>
                                            {{-- <th>Tipe</th> --}}
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
                <button type="button" class="btn btn-success" id="ok">Transfer</button>
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
                <table class="table table-striped scan-produk">
                    <thead>
                        <tr>
                            <th>Nomor Seri</th>
                            <th><input type="checkbox" id="head-cb"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>36541654654654564</td>
                            <td><input type="checkbox" class="cb-child" value="2"></td>
                        </tr>
                        <tr>
                            <td>78656562646545646</td>
                            <td><input type="checkbox" class="cb-child" value="2"></td>
                        </tr>
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
        // $('#addProduk').DataTable().destroy();
        $('#addProduk').DataTable({
            // retrieve: true,
            destroy: true,
            autoWidth: false,
            processing: true,
            serverSide: true,
            ajax: {
                url: "/api/tfp/detail-so/" +id+"/"+x,
                // data: {id: id},
                // type: "post",
                // dataType: "json",
            },
            columns: [
                // { data: 'ids', name: 'ids'},
                { data: 'produk', name: 'produk'},
                { data: 'qty', name: 'qty'},
                // { data: 'tipe', name: 'tipe'},
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

    var prd = '';
        $(document).on('click', '.detailmodal', function(e) {
        var tr = $(this).closest('tr');
        prd = tr.find('#gdg_brg_jadi_id').val();
        var jml = $(this).data('jml');
        console.log(jml);
        console.log(prd);
        // $('.scan-produk').DataTable().destroy();
        mytable = $('.scan-produk').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            destroy: true,
            ajax: {
                url: "/api/tfp/seri-so",
                data: {gdg_barang_jadi_id: prd},
                type: "post",
                // dataType: "json",
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

        $('#viewProdukModal').modal('show');
    });

    const prd1 = {};
    var t = 0;
    $(document).on('click', '#simpan', function(e) {
        // console.log('ok');

        const ids = [];
        $('.cb-child').each(function() {
            if ($(this).is(":checked")) {
                ids.push($(this).val());
            }
        })
        prd1[prd] = ids;

        console.log(prd1);
        t++;
    })

    $(document).on('click', '#rancang', function(e) {
            e.preventDefault();

            const prdd = [];
            const qtyy = [];
            // const noseri = [];

            $('input[name^="gdg_brg_jadi_id"]').each(function() {
                prdd.push($(this).val());
            });

            $('input[name^="qty"]').each(function() {
                qtyy.push($(this).val());
            });

            // console.log(ids.length);
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

</script>
@stop
