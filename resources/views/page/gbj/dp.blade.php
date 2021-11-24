@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
<input type="hidden" name="" id="auth" value="{{ Auth::user()->divisi_id }}">
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Produk dari Perakitan </h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <table class="table table-bordered dalam-perakitan">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal Masuk</th>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>10-11-2021</td>
                    <td>AMBULATORY BLOOD PRESSURE MONITOR</td>
                    <td>100 Unit</td>
                    <td>
                        <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                            <div class="dropdown-menu">
                                <button type="button" class="dropdown-item terimaProduk" onclick="openModalTerima()">
                                    <i class="far fa-edit"></i>&nbsp;Terima
                                </button>
                                <button type="button" class="dropdown-item detailProduk" onclick="openModalView()">
                                    <i class="far fa-eye"></i>&nbsp;Detail
                                </button>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>11-11-2021</td>
                    <td>AMBULATORY BLOOD PRESSURE TESTING</td>
                    <td>100</td>
                    <td>
                        <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                            <div class="dropdown-menu">
                                <button type="button" class="dropdown-item terimaProduk" onclick="openModalTerima()">
                                    <i class="far fa-edit"></i>&nbsp;Terima
                                </button>
                                <button type="button" class="dropdown-item detailProduk" onclick="openModalView()">
                                    <i class="far fa-eye"></i>&nbsp;Detail
                                </button>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<!-- Modal Detail-->
<div class="modal fade terima-produk" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>Detail Produk <span id="title">AMBULATORY BLOOD PRESSURE MONITOR</span></b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-striped scan-produk">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="head-cb"></th>
                            <th>Nomor Seri</th>
                            <th>Layout</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="checkbox" class="cb-child" value="1"></td>
                            <td>36541654654654564</td>
                            <td><select name="" id="" class="form-control">
                                    <option value="1">Layout 1</option>
                                    <option value="2">Layout 2</option>
                                </select></td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" class="cb-child" value="2"></td>
                            <td>36541654654654564</td>
                            <td><select name="" id="" class="form-control">
                                    <option value="1">Layout 1</option>
                                    <option value="2">Layout 2</option>
                                </select></td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" class="cb-child" value="3"></td>
                            <td>36541654654654564</td>
                            <td><select name="" id="" class="form-control">
                                    <option value="1">Layout 1</option>
                                    <option value="2">Layout 2</option>
                                </select></td>
                        </tr>
                    </tbody>
                </table>
                <button class="btn btn-info" data-toggle="modal" data-target="#ubah-layout">Ubah Layout</button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ubah Layout-->
<div class="modal fade" id="ubah-layout" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah Layout</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Layout</label>
                    <select name="" id="change-layout" class="form-control">
                        <option value="1">Layout 1</option>
                        <option value="2">Layout 2</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                <button type="button" class="btn btn-primary" onclick="ubahData()">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade detail-layout" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>Detail Produk <span id="titlee">AMBULATORY BLOOD PRESSURE MONITOR</span></b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-seri">
                    <thead>
                        <tr>
                            <th>No Seri</th>
                            <th>Layout</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>654165654</td>
                            <td>Layout 1</td>
                        </tr>
                        <tr>
                            <td>654165654</td>
                            <td>Layout 1</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop

@section('adminlte_js')
<script>
    $('.table-seri').DataTable({
        "oLanguage": {
<<<<<<< HEAD
            "sSearch": "Cari:"
        }
    });
    $('.dalam-perakitan').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/api/tfp/rakit',
        },
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            },
            {
                data: 'tgl_masuk'
            },
            {
                data: 'product'
            },
            {
                data: 'jumlah'
            },
            {
                data: 'action'
            }
        ],
        "oLanguage": {
            "sSearch": "Cari:"
        },
        "columnDefs": [{
=======
        "sSearch": "Cari:"}
    });
    $('.dalam-perakitan').DataTable({
        "oLanguage": {
        "sSearch": "Cari:"},
        "columnDefs": [
        {
>>>>>>> 05f6ec0bc9795de2021471141f7ed12cf5f5cc51
            "targets": [4],
            "visible": document.getElementById('auth').value == '2' ? false : true
        }]
    });
    $('.scan-produk').DataTable({
        "oLanguage": {
            "sSearch": "Cari:"
        }
    });


    $(document).ready(function() {
        $('.terimaProduk').click(function(e) {
            $('.terima-produk').modal('show');
        });

        $("#head-cb").on('click', function() {
            var isChecked = $("#head-cb").prop('checked')
            $('.cb-child').prop('checked', isChecked)
        });
    });

    function ubahData() {
        let checkbox_terpilih = $('.scan-produk tbody .cb-child:checked');
        let layout = $('#change-layout').val();
        $.each(checkbox_terpilih, function(index, elm) {
            let b = $(checkbox_terpilih).parent().next().next().children().val(layout);
        });
        $('#ubah-layout').modal('hide');
    }

    $(document).on('click', '.editmodal', function() {
        var id = $(this).data('id');
        console.log(id);

        $.ajax({
            url: '/api/tfp/rakit-terima/' + id,
            success: function(res) {
                console.log(res.data[0].title);
                $('span#title').text(res.data[0].title);
            }
        })

        $('.scan-produk').DataTable().destroy();
        $('.scan-produk').DataTable({
            serverSide: true,
            ajax: {
                url: '/api/tfp/rakit-terima/' + id,
            },
            columns: [{
                    data: 'checkbox'
                },
                {
                    data: 'seri'
                },
                {
                    data: 'layout'
                },
            ],
            "oLanguage": {
                "sSearch": "Cari:"
            }
        });

        $.ajax({
            url: '/api/gbj/sel-layout',
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res) {
                    console.log(res);
                    $("#layout_id").empty();
                    // $("#divisi").append('<option value="">All</option>');
                    $.each(res, function(key, value) {
                        $("#layout_id").append('<option value="' + value.id + '">' + value.ruang + '</option');
                    });
                } else {
                    $("#layout_id").empty();
                }
            }
        });

        openModalTerima();
    });

    $(document).on('click', '.detailmodal', function() {
        var id = $(this).data('id');
        console.log(id);

        $.ajax({
            url: '/api/tfp/rakit-noseri/' + id,
            success: function(res) {
                console.log(res.data[0].title);
                $('span#titlee').text(res.data[0].title);
            }
        })

        $('.table-seri').DataTable().destroy();
        $('.table-seri').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/api/tfp/rakit-noseri/' + id,
            },
            columns: [{
                    data: 'seri'
                },
                {
                    data: 'layout'
                },
            ],
            "oLanguage": {
                "sSearch": "Cari:"
            }
        });

        openModalView();
    });

    function openModalTerima() {
        $('.terima-produk').modal('show');
    }

    function openModalView() {
        $('.detail-layout').modal('show');
    }
</script>
@stop