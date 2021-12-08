@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-8">
                                <h3 class="card-title">Produk Gudang Barang Jadi</h3>
                            </div>
                            <div class="col-4 d-flex justify-content-end">
                                <div class="row">
                                    <div class="col-lg-12">
                                        @if (Auth::user()->divisi->id != 2)
                                        <span class="float-right">
                                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-create" id="create">
                                                <i class="fas fa-plus"></i>&nbsp;Tambah
                                            </button>
                                        </span>
                                        @endif
                                        <span class="dropdown float-right" id="semuaprodukfilter" style="margin-right: 5px">
                                            <button class="btn btn-outline-info dropdown-toggle" type="button" id="semuaprodukfilter" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-filter"></i>&nbsp;
                                                Filter
                                            </button>
                                            <div class="dropdown-menu p-3 text-nowrap" aria-labelledby="semuaprodukfilter">
                                                <div class="dropdown-header">Kelompok Produk</div>
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="alkes" value="Alat Kesehatan" />
                                                        <label class="form-check-label" for="sp_kelompok">
                                                            Alat Kesehatan
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="sarkes" value="Sarana Kesehatan" />
                                                        <label class="form-check-label" for="sp_kelompok">
                                                            Sarana Kesehatan
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered" id="gudang-barang">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Produk</th>
                                    <th>Nama Produk</th>
                                    <th>Stok Gudang</th>
                                    <th>Stok</th>
                                    <th>Kelompok</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Create --}}
<!-- Button trigger modal -->

<!-- Modal -->
<div class="modal fade" id="modal-create" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Produk GBJ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" id="produkForm" name="produkForm" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="id">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Produk</label>
                                <input type="hidden" name="produk_id" id="produk_idd">
                                <select name="produk_id" id="produk_id" class="form-control produk-add">
                                    <option value="">Buku</option>
                                    <option value="">Bolpoin</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="">Nama Produk</label>
                            <input type="text" name="nama" id="nama" class="form-control @error('title') is-invalid @enderror" placeholder="Nama Produk">
                            @error('title')
                            <span class="invalid-feedback">Silahkan isi Nama Produk</span>
                            @enderror
                        </div>
                        <div class="col">
                            <label for="">Satuan</label>
                            <select name="satuan_id" id="satuan_id" class="form-control">
                                <option value="">mm</option>
                                <option value="">unit</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" cols="5" rows="5"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="">Dimensi</label>
                        <div class="d-flex justify-content-between">
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" name="dim_p" id="dim_p" placeholder="Panjang">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">mm</div>
                                </div>
                            </div>&nbsp;
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" name="dim_l" id="dim_l" placeholder="Lebar">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">mm</div>
                                </div>
                            </div>&nbsp;
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" name="dim_t" id="dim_t" placeholder="Tinggi">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">mm</div>
                                </div>
                            </div>&nbsp;
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="custom-file">
                            <input type="file" name="gambar" class="custom-file-input gambar" id="inputGroupFile02" />
                            <label class="custom-file-label" for="inputGroupFile02">Pilih File</label>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                <button type="submit" class="btn btn-primary" id="Submitmodalcreate">Kirim</button>
            </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Edit --}}
<!-- Button trigger modal -->

<!-- Modal -->
<div class="modal fade modal-edit" id="" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Produk Sterilisator</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="">Produk</label>
                            <select name="" class="form-control produk-add">
                                <option value="">Buku</option>
                                <option value="">Bolpoin</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="">Nama Produk</label>
                        <input type="text" class="form-control" placeholder="Nama Produk">
                    </div>
                    <div class="col">
                        <label for="">Satuan</label>
                        <select name="" id="" class="form-control satuan-edit">
                            <option value="">mm</option>
                            <option value="">unit</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Deskripsi</label>
                    <textarea class="form-control" id="" cols="5" rows="5"></textarea>
                </div>
                <div class="form-group">
                    <label for="">Dimensi</label>
                    <div class="d-flex justify-content-between">
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" placeholder="Panjang">
                            <div class="input-group-prepend">
                                <div class="input-group-text">mm</div>
                            </div>
                        </div>&nbsp;
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" placeholder="Lebar">
                            <div class="input-group-prepend">
                                <div class="input-group-text">mm</div>
                            </div>
                        </div>&nbsp;
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" placeholder="Tinggi">
                            <div class="input-group-prepend">
                                <div class="input-group-text">mm</div>
                            </div>
                        </div>&nbsp;
                    </div>
                </div>
                <div class="form-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="inputGroupFile02" />
                        <label class="custom-file-label" for="inputGroupFile02">Pilih File</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                <button type="button" class="btn btn-primary">Kirim</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal View --}}

<!-- Modal -->
<div class="modal fade " id="modal-view" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="header_data">Produk Sterilisator</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6">
                        <div class="card">
                            <img class="card-img-top" id="img_prd" src="https://images.unsplash.com/photo-1636096111790-01540e4b36fd?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=687&q=80" alt="">
                        </div>
                    </div>
                    <div class="col-6">
                        <p><b>Nama Produk</b></p>
                        <p id="nama">STERILISATOR KERING</p>
                        <p><b>Deskripsi Produk</b></p>
                        <p id="deskripsi">Inovasi Produk Terbaru dari industri kami</p>
                        <p><b>Dimensi</b></p>
                        <div class="row">
                            <div class="col-sm">Panjang</div>
                            <div class="col-sm">Lebar</div>
                            <div class="col-sm">Tinggi</div>
                        </div>
                        <div class="row">
                            <div class="col-sm"><span id="panjang">1</span></div>
                            <div class="col-sm"><span id="lebar">122</span></div>
                            <div class="col-sm"><span id="tinggi">12</span></div>
                        </div>
                        <p><b>Produk</b></p>
                        <p id="produk">Buku</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Daftar Stok-->
<div class="modal fade daftar-stok" id="" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Daftar Stok Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" id="noseriForm" name="noseriForm">
                    <table class="table scan-produk">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="head-cb"></th>
                                <th>No. Seri</th>
                                <th>Layout</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        {{-- <tr>
                        <td><input type="checkbox" class="cb-child" value="1"></td>
                        <td>5474598674958698645</td>
                        <td>
                            <select name="" id="" class="form-control">
                                <option value="1">Layout 1</option>
                                <option value="2">Layout 2</option>
                            </select>
                            </td> <td>
                                <button class="btn btn-info viewStock"><i class="far fa-eye"></i> View</button>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" class="cb-child" value="2"></td>
                        <td>5474598674958698645</td>
                        <td>
                            <select name="" id="" class="form-control">
                                <option value="1">Layout 1</option>
                                <option value="2">Layout 2</option>
                            </select>
                            </td> <td>
                                <button class="btn btn-info viewStock"><i class="far fa-eye"></i> View</button>
                        </td>
                    </tr> --}}
                    </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="ubahSeri">Simpan</button>
                <button type="button" class="btn btn-success" data-toggle="modal" data-target=".edit-stok">Ubah Layout</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Detail-->
<div class="modal fade modalViewStock" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <div class="row">
                        <div class="col">
                            <b>Produk</b>
                            <p>Ambulatory</p>
                        </div>
                        <div class="col">
                            <b>Nomor SO</b>
                            <p>8457938475938475</p>
                        </div>
                    </div>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table class="table view-produk">
                    <thead>
                        <tr>
                            <th>Tanggal Masuk</th>
                            <th>Dari</th>
                            <th>Tujuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- <tr>
                            <td scope="row">10-04-2022</td>
                            <td>Divisi IT</td>
                            <td>Uji Coba</td>
                        </tr>
                        <tr>
                            <td scope="row">10-04-2022</td>
                            <td>Divisi IT</td>
                            <td>Uji Coba</td>
                        </tr> --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal Edit --}}
<div class="modal fade edit-stok" id="" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
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
                    <select name="" id="change_layout" class="form-control">
                        {{-- <option value="1">Layout 1</option>
                      <option value="2">Layout 2</option> --}}
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
<style>
    img {
        width: 100%;
    }
</style>
@stop

@section('adminlte_js')
{{-- <script src="{{ asset('native/js/gbj/produk.js') }}"></script> --}}
<script>
    $('.scan-produk').DataTable({
        "ordering": false,
        "autoWidth": false,
        searching: false,
        "lengthChange": false,
        "columnDefs": [{
            "width": "5%",
            "targets": 0
        }, ]
    });
    $('#inputGroupFile02').on('change', function() {
        //get the file name
        var fileName = $(this).val();
        //replace the "Choose a file" label
        $(this).next('.custom-file-label').html(fileName);
    });
    $('.editProduk').click(function(e) {
        $('.modal-edit').modal('show');
        $('.produk-edit ').select2();
        $('.satuan-edit').select2();
        $('.layout-edit').select2();
    });
    $('.viewProduk').click(function(e) {
        $('.modal-view').modal('show');
    });
    $(document).ready(function() {
        $('.produk-add ').select2();
        $('.layout-add').select2();

        $("#head-cb").on('click', function() {
            var isChecked = $("#head-cb").prop('checked')
            $('.cb-child').prop('checked', isChecked)
        });
    });

    function ubahData() {
        let checkbox_terpilih = $('.scan-produk tbody .cb-child:checked');
        let layout = $('#change_layout').val();
        $.each(checkbox_terpilih, function(index, elm) {
            let b = $(checkbox_terpilih).parent().next().next().children().val(layout);
        });
        $('.edit-stok').modal('hide');
    }

    $('.stokProduct').click(function(e) {
        $('.daftar-stok').modal('show');
    });

    $('.editStok').click(function(e) {
        $('.edit-stok').modal('show');
    });

    $('.viewStock').click(function(e) {
        $('.modalViewStock').modal('show');
    });
    $('#satuan_id').select2();
</script>

{{-- data --}}
<script type="text/javascript">
    // initial
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#alkes').click(function() {
        if ($(this).prop('checked') == true) {
            datatable.column(5).search($(this).val()).draw();
        } else {
            datatable.column(5).search('').draw();
        }
    })

    $('#sarkes').click(function() {
        if ($(this).prop('checked') == true) {
            datatable.column(5).search($(this).val()).draw();
        } else {
            datatable.column(5).search('').draw();
        }
    })

    // load data
    console.log("asdasfasdadsasd")
    axios.get('/gbj/data').then(response => console.log(response.data))
    var datatable = $('#gudang-barang').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/gbj/data',
        },
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            },
            {
                data: 'kode_produk',
                name: 'kode_produk'
            },
            {
                data: 'nama_produk',
                name: 'nama_produk'
            },
            {
                data: 'jumlah'
            },
            {
                data: 'jumlah'
            },
            {
                data: 'kelompok'
            },
            {
                data: 'action'
            }
        ]
    });

    // load produk
    $.ajax({
        url: '/api/gbj/sel-product',
        type: 'GET',
        dataType: 'json',
        success: function(res) {
            if (res) {
                console.log(res);
                $("#produk_id").empty();
                $("#produk_id").append('<option value="">Pilih Item</option>');
                $.each(res, function(key, value) {
                    $("#produk_id").append('<option value="' + value.id + '">' + value.product.nama + ' ' + value.nama + '</option');
                });
            } else {
                $("#produk_id").empty();
            }
        }
    });

    // load satuan
    $.ajax({
        url: '/api/gbj/sel-satuan',
        type: 'GET',
        dataType: 'json',
        success: function(res) {
            if (res) {
                console.log(res);
                $("#satuan_id").empty();
                $("#satuan_id").append('<option value="">Pilih Item</option>');
                $.each(res, function(key, value) {
                    $("#satuan_id").append('<option value="' + value.id + '">' + value.nama + '</option');
                });
            } else {
                $("#satuan_id").empty();
            }
        }
    });

    // load modal create edit
    $('#create').click(function(e) {
        $('#Submitmodalcreate').val('create-product');
        $('#produkForm').trigger("reset");
        $('#exampleModalLabel').html('Tambah Produk');
        $('#modal-create').modal('show');
    });

    // load modal edit
    $(document).on('click', '.editmodal', function() {
        var id = $(this).data('id');
        console.log(id);
        // ajax
        $.ajax({
            type: "POST",
            url: '/api/gbj/get',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(res) {
                console.log(res);
                $('#exampleModalLabel').html('Edit Produk ' + '<b>' + res.data[0].nama + '</b>');
                $('#Submitmodalcreate').val('edit-product');
                $('#modal-create').modal('show');
                $('#id').val(res.data[0].id);
                $('#nama').val(res.data[0].nama);
                $('textarea#deskripsi').val(res.data[0].deskripsi);
                $('#stok').val(res.data[0].stok);
                $('#dim_p').val(res.data[0].dim_p);
                $('#dim_l').val(res.data[0].dim_l);
                $('#dim_t').val(res.data[0].dim_t);
                $('#satuan_id').val(res.data[0].satuan_id);
                $('#satuan_id').select2().trigger('change');
                $('#produk_id').val(res.data[0].produk_id);
                $('#produk_idd').val(res.data[0].produk_id);
                $('#produk_id').select2().trigger('change');
                $('#produk_id').select2({
                    disabled: 'readonly'
                });
                // var newOption = $('<option selected="selected"></option>').val(res.data[0].produk_id).text(res.nama_produk[0].product.tipe + ' ' + res.nama_produk[0].nama);
                // $('#produk_id').append(newOption).trigger('change');
                $('#inputGroupFile02').val(res.data[0].gambar);
            }
        });
    });

    // detail
    $(document).on('click', '.detailmodal', function() {
        var id = $(this).data('id');
        console.log(id);
        // ajax
        $.ajax({
            type: "POST",
            url: '/api/gbj/get',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(res) {
                console.log(res);

                $('#header_data').html('Detail Produk ' + '<b>' + res.data[0].nama + '</b>');
                $('p#nama').text(res.data[0].nama);
                $('p#deskripsi').text(res.data[0].deskripsi);
                $('span#panjang').text(res.data[0].dim_p);
                $('span#lebar').text(res.data[0].dim_l);
                $('span#tinggi').text(res.data[0].dim_t);
                $('p#produk').text(res.nama_produk[0].product.nama + ' ' + res.nama_produk[0].nama);
                $('img#img_prd').attr("src", "http://localhost:8000/upload/gbj/" + res.data[0].gambar);
                $('#modal-view').modal('show');
            }
        });
    });

    // proses submit
    $('body').on('submit', '#produkForm', function(e) {
        e.preventDefault();
        var actionType = $('#Submitmodalcreate').val();
        $('#Submitmodalcreate').html('Sending..');
        var formData = new FormData(this);
        $.ajax({
            type: 'POST',
            url: "/api/gbj/create",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: (data) => {
                $('#produkForm').trigger('reset');
                $('#modal-create').modal('hide');
                $('#Submitmodalcreate').html('Kirim');
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Your data has been saved',
                    showConfirmButton: false,
                    timer: 1500
                });
                $('.datatable').DataTable().ajax.reload();
                location.reload();
            }
        });
    });
    // var ii = 0;
    function select_layout() {
        $.ajax({
            url: '/api/gbj/sel-layout',
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                // ii++;
                console.log(res);
                $.each(res, function(key, value) {
                    // $("#change_layout").append('<option value="'+value.id+'">'+value.ruang+'</option');
                    $("#layout_id").append('<option value="' + value.id + '">' + value.ruang + '</option');
                });
            }
        });
    }

    // modal noseri
    $(document).on('click', '.stokmodal', function() {
        var id = $(this).data('id');
        console.log(id);
        var i = 0;
        var b = '';

        $.ajax({
            url: '/api/gbj/noseri/' + id,
            dataType: 'json',
            success: function(data) {
                console.log(data);
                $.each(data, function(key, value) {
                    i++;
                    b = "<tr ><td><input type='checkbox' class='cb-child' value=" + value.id + "></td><td>" + value.noseri + "<input type='hidden' name='noseri[]' value=" + value.noseri + "><input type='hidden' name='gdg_brg_jadi_id' value=" + value.gdg_barang_jadi_id + "></td><td><select name='layout_id[]' id='layout_id' class='form-control'>" + select_layout() + "</select></td><td><button class='btn btn-info viewStock' data-id='" + value.id + "'><i class='far fa-eye'></i> View</button></td></tr>";
                    $(".scan-produk").append(b);
                })

            }
        });
        $('.daftar-stok').modal('show');
    });

    // modal history
    $(document).on('focus', '.viewStock', function() {
        var id = $(this).data('id');
        console.log(id);
        var i = 0;

        $.ajax({
            url: '/api/gbj/history/' + id,
            dataType: 'json',
            success: function(data) {
                console.log(data);
                var a = '';

                $.each(data, function(key, value) {
                    a = "<tr><td>" + new Date(value.created_at).toLocaleDateString() + "</td><td>" + value.from.nama + "</td><td>" + value.to.nama + "</td></tr>";
                });
                $(".view-produk tbody").html(a);
            }
        })
        $('.modalViewStock').modal('show');
    });

    // load layout
    $.ajax({
        url: '/api/gbj/sel-layout',
        type: 'GET',
        dataType: 'json',
        success: function(res) {
            console.log(res);
            $("#change_layout").empty();
            $.each(res, function(key, value) {
                $("#change_layout").append('<option value="' + value.id + '">' + value.ruang + '</option');
                // $("#layout_id").append('<option value="'+value.id+'">'+value.ruang+'</option');
            });
        }
    });

    // modal ubah layout
    $(document).on('click', '.editStok', function() {
        $('.edit-stok').modal('show');
    });

    $(document).ready(function() {
        $('#ubahSeri').on('click', function() {
            // console.log('ok');

            const cekid = [];
            const noseri = [];
            const layout = [];

            $('.cb-child').each(function() {
                if ($(this).is(":checked")) {
                    cekid.push($(this).val());
                }
            });

            $('input[name^="gdg_brg_jadi_id"]').each(function() {
                noseri.push($(this).val());
            });

            $('select[name^="layout_id"]').each(function() {
                layout.push($(this).val());
            });
            // console.log(cekid);

            $.ajax({
                url: '/api/gbj/noseri/' + noseri,
                type: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    cekid: cekid,
                    // noseri : noseri,
                    layout: layout,
                },
                success: function(res) {
                    console.log(res);
                }
            })
        })
    })
</script>
@stop