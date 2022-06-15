@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
<div class="content-header">
    <input type="hidden" name="" id="authid" value="{{ Auth::user()->divisi_id }}">

    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Produk Gudang Barang Jadi</h1>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-8">
                                <div class="row">
                                @if (Auth::user()->divisi->id != 2)
                                    <span class="float-left mr-1">
                                        <button type="button" class="btn btn-success" id="downloadTemplate">
                                            <i class="fas fa-download"></i>&nbsp;Template
                                        </button>
                                    </span>

                                    <span class="float-left mr-1">
                                        <button type="button" class="btn btn-outline-success" id="importTemplate">
                                            <i class="fas fa-file-import"></i>&nbsp;Import
                                        </button>
                                    </span>
                                @endif
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="row">
                                    <div class="col-lg-12">
                                        @if (Auth::user()->divisi->id != 2)
                                        <span class="float-right">
                                            <button type="button" class="btn btn-info" data-toggle="modal"
                                                data-target="#modal-create" id="create">
                                                <i class="fas fa-plus"></i>&nbsp;Tambah
                                            </button>
                                        </span>
                                        @endif

                                        <span class="float-right mr-1">
                                            <button class="btn btn-outline-info dropdown-toggle" type="button"
                                                id="semuaprodukfilter" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false"><i class="fas fa-filter"></i>&nbsp;
                                                Filter
                                            </button>
                                            <div class="dropdown-menu p-3 text-nowrap"
                                                aria-labelledby="semuaprodukfilter">
                                                <div class="dropdown-header">Kelompok Produk</div>
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="alkes"
                                                            value="Alat Kesehatan" />
                                                        <label class="form-check-label" for="sp_kelompok">
                                                            Alat Kesehatan
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="sarkes"
                                                            value="Sarana Kesehatan" />
                                                        <label class="form-check-label" for="sp_kelompok">
                                                            Sarana Kesehatan
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="water"
                                                            value="Water Treatment" />
                                                        <label class="form-check-label" for="sp_kelompok">
                                                            Water Treatment
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
                        <table class="table table-bordered" id="gudang-barang" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Produk</th>
                                    <th>Merk</th>
                                    <th>Nama Produk</th>
                                    <th>Stok Gudang</th>
                                    <th>Stok Penjualan</th>
                                    <th>Kelompok</th>
                                    <th>Aksi</th>
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
                    <input type="hidden" name="userid" id="userid" value="{{ Auth::user()->id }}">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Produk</label>
                                <input type="hidden" name="produk_id" id="produk_idd">
                                <select name="produk_id" id="produk_id" class="form-control produk-add">

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="">Nama Variasi</label>
                            <input type="text" name="nama" id="nama"
                                class="form-control @error('title') is-invalid @enderror"
                                placeholder="Nama Variasi Produk">
                            @error('title')
                            <span class="invalid-feedback">Silahkan isi Nama Produk</span>
                            @enderror
                        </div>
                        <div class="col">
                            <label for="">Satuan</label>
                            <select name="satuan_id" id="satuan_id" class="form-control satuan_id">
                                <option selected></option>
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
                            <img class="card-img-top" id="img_prd"
                                src="https://images.unsplash.com/photo-1636096111790-01540e4b36fd?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=687&q=80"
                                alt="">
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
                <h5 class="modal-title">Daftar Stok <span id="nm_produk"><b></b></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <input type="text" name="" class="seri_id" hidden>
                        <input type="text" class="created_by" name="" id="" value="{{ Auth::user()->id }}" hidden>
                        <div class="row">
                            <div class="col">
                                <label for="">Tambah Data No Seri</label>
                                <input type="text" class="form-control number" id="jumlah_noseri"
                                    placeholder="Jumlah No Seri">
                            </div>
                            <div class="col">
                                <label for="">Dari</label>
                                <select name="" id="" class="form-control dari"></select>
                            </div>
                        </div>
                        <button class=" btn btn-primary tambah_noseri mt-2">Tambah</button>
                    </div>
                </div>
                <form action="" id="noseriForm" name="noseriForm">
                    {{-- <table class="table scan-produk">
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

                    </table> --}}
                    <div class="card card-primary card-outline card-outline-tabs">
                        <div class="card-header p-0 border-bottom-0">
                            <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill"
                                        href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home"
                                        aria-selected="true">Belum Digunakan</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-four-profile-tab" data-toggle="pill"
                                        href="#custom-tabs-four-profile" role="tab"
                                        aria-controls="custom-tabs-four-profile" aria-selected="false">Sudah
                                        Digunakan</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-four-wait-approved-tab" data-toggle="pill"
                                        href="#custom-tabs-four-wait-approved" role="tab"
                                        aria-controls="custom-tabs-four-wait-approved" aria-selected="false">Menunggu Persetujuan</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-four-tabContent">
                                <div class="tab-pane fade active show" id="custom-tabs-four-home" role="tabpanel"
                                    aria-labelledby="custom-tabs-four-home-tab">
                                    <form action="" id="noseriForm" name="noseriForm">
                                        <input type="hidden" name="action_by" id="actionby" value="{{ Auth::user()->id }}">
                                        <table class="table scan-produk">
                                            <thead>
                                                <tr>
                                                    <th><input type="checkbox" id="head-cb"></th>
                                                    <th>No. Seri</th>
                                                    <th>Nomor</th>
                                                    <th>Layout</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                </div>
                                <div class="tab-pane fade" id="custom-tabs-four-profile" role="tabpanel"
                                    aria-labelledby="custom-tabs-four-profile-tab">
                                    <table class="table scan-produk1">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="head-cb1"></th>
                                                <th>No. Seri</th>
                                                <th>Nomor</th>
                                                <th>Digunakan</th>
                                                <th>Layout</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="custom-tabs-four-wait-approved" role="tabpanel"
                                aria-labelledby="custom-tabs-four-wait-approved-tab">
                                <table class="table scan-produk2">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>No. Seri Lama</th>
                                            <th>No. Seri Baru</th>
                                            <th>Permintaan</th>
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
                <button type="button" class="btn btn-primary simpanSeri" id="simpanSeriBelumDigunakan">Simpan</button>
                <button type="button" class="btn btn-success" data-toggle="modal" data-target=".edit-stok">Ubah
                    Layout</button>
                <button type="button" class="btn btn-danger hapusSeri">Hapus</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Detail-->
<div class="modal fade modalViewStock" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <div class="row">
                        <div class="col">
                            <b>Produk</b>
                            <p id="namaa">Ambulatory</p>
                        </div>

                    </div>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table class="table view_produk">
                    <thead>
                        <tr>
                            <th>Tanggal Masuk</th>
                            <th>Dari</th>
                        </tr>
                    </thead>
                    <tbody>
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

<div class="modal fade import-seri" id="" role="dialog" aria-labelledby="modelTitleId">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Unggah File</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form name="formImport" id="formImport" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Noseri</label>
                        <input type="file" name="file_csv" id="template_noseri" class="form-control" accept=".xlsx">
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-outline-info"><i class="fas fa-eye"> Preview</i></button>
                    </div>
            </form>
        </div>
        <form name="formStoreImport" id="formStoreImport" method="post" enctype="multipart/form-data">
            <div class="modal-footer" id="csv_data_file">

            </div>
            <div class="modal-footer justify-content-between" id="footer-btn">
                <p id="bodyNoseri">Noseri Yang Terdaftar:
                    <br>
                    <span id="existNoseri"></span>
                </p>
                <button type="submit" class="btn btn-default float-right btnImport"><i class="fas fa-upload">
                        Unggah</i></button>
            </div>
        </form>
    </div>
</div>
</div>

<div class="modal fade notice" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h5 class="">Sebelum Mengunggah Noseri</h5>
                 <p>Pastikan Noseri Sudah Unik atau Tidak Ada Warna Dalam Template</p>
                <button type="button" class="btn btn-primary btn-lg btnNext"> OK</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade tambah_seri" id="" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data No Seri</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table tambah_noseri_tableee">
                    <thead>
                        <tr>
                            <th>No Seri</th>
                            <th>Layout</th>
                        </tr>
                    </thead>
                    <tbody class="tambah_noseri_table">
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                <button type="button" class="btn btn-primary" id="save_data">Simpan</button>
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
    let layout = [];
    $("#head-cb").prop('checked', false);
    $('#inputGroupFile02').on('change', function () {
        //get the file name
        var fileName = $(this).val();
        //replace the "Choose a file" label
        $(this).next('.custom-file-label').html(fileName);
    });
    $('.editProduk').click(function (e) {
        $('.modal-edit').modal('show');
        $('.produk-edit ').select2();
        $('.satuan-edit').select2();
        $('.layout-edit').select2();
    });
    $('.viewProduk').click(function (e) {
        $('.modal-view').modal('show');
    });
    $(document).ready(function () {
        $('.produk-add').select2({
            placeholder: "Choose ...",
            allowClear: true
        });
        $('.layout-add').select2();
        $("#head-cb").on('click', function () {
            var isChecked = $("#head-cb").prop('checked')
            // $('.cb-child').prop('checked', isChecked)
            $('.scan-produk').DataTable()
                .column(0)
                .nodes()
                .to$()
                .find('input[type=checkbox]')
                .prop('checked', isChecked);

            if (isChecked) {
                $('.scan-produk').DataTable()
                .column(1)
                .nodes()
                .to$()
                .find('input[type=text]')
                .prop('disabled', false);
            } else {
                $('.scan-produk').DataTable()
                .column(1)
                .nodes()
                .to$()
                .find('input[type=text]')
                .prop('disabled', true);
            }

        });

        $("#head-cb1").on('click', function () {
            var isChecked = $("#head-cb1").prop('checked')
            // $('.cb-child').prop('checked', isChecked)
            $('.scan-produk1').DataTable()
                .column(0)
                .nodes()
                .to$()
                .find('input[type=checkbox]')
                .prop('checked', isChecked);

            if (isChecked) {
                $('.scan-produk1').DataTable()
                .column(1)
                .nodes()
                .to$()
                .find('input[type=text]')
                .prop('disabled', false);
            } else {
                $('.scan-produk1').DataTable()
                .column(1)
                .nodes()
                .to$()
                .find('input[type=text]')
                .prop('disabled', true);
            }
        });

        $.ajax({
            url: '/api/gbj/sel-divisi',
            type: 'GET',
            dataType: 'json',
            success: function (res) {
                // ii++;
                console.log(res);
                $.each(res, function (key, value) {
                    $('.dari').append(
                        '<option value="' + value.id + '">' + value.nama + '</option>'
                    );
                });
            }
        });
    });

    function ubahData() {
        let checkbox_terpilih = $('.scan-produk').DataTable().column(0).nodes()
            .to$().find('input[type=checkbox]:checked');
        let layout = $('#change_layout').val();
        $.each(checkbox_terpilih, function (index, elm) {
            let b = $(checkbox_terpilih).parent().next().next().children().val(layout);
        });
        $('.edit-stok').modal('hide');
    }

    $('.stokProduct').click(function (e) {
        $('.daftar-stok').modal('show');
    });

    $('.editStok').click(function (e) {
        $('.edit-stok').modal('show');
    });

    $('.viewStock').click(function (e) {
        $('.modalViewStock').modal('show');
    });
    $('.satuan_id').select2({
        placeholder: "Choose...",
        allowClear: true
    });

    // hidden hapus
    $('#custom-tabs-four-profile-tab').click(function () {
        $('.simpanSeri').prop('disabled', false);
        $('.hapusSeri').prop('disabled', true);
        $('.simpanSeri').removeAttr('id');
        $('.simpanSeri').attr('id', 'simpanSeriSudahDigunakan');
    });

    $('#custom-tabs-four-home-tab').click(function () {
        $('.simpanSeri').prop('disabled', false);
        $('.hapusSeri').prop('disabled', false);
        $('.simpanSeri').removeAttr('id');
        $('.simpanSeri').attr('id', 'simpanSeriBelumDigunakan');
    });

    $('#custom-tabs-four-wait-approved-tab').click(function () {
        $('.hapusSeri').prop('disabled', true);
        $('.simpanSeri').prop('disabled', true);
    });

</script>

{{-- data --}}
<script type="text/javascript">
    $('#footer-btn').hide()
    $('#bodyNoseri').hide()
    var authid = $('#authid').val();
    // initial
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#downloadTemplate').click(function () {
        // console.log('download');
        window.location = window.location.origin + '/api/v2/gbj/template_noseri'
    })

    $('#importTemplate').click(function () {
        $('.notice').modal('show')
    })

    $('.btnNext').click(function () {
        $('.notice').modal('hide')
        $('#template_noseri').val('');
        $('.import-seri').modal('show');
    })

    $('.import-seri').on('hidden.bs.modal', function () {
        $('#template_noseri').val('');
        $('#footer-btn').hide()
        $('#csv_data_file').empty()

    })

    // load data
    var datatable = $('#gudang-barang').DataTable({
        processing: true,
        ajax: {
            'type': 'POST',
            'datatype': 'JSON',
            'url': '/api/gbj/data',
            'headers': {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
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
                data: "merk"
            },
            {
                data: 'nama_produk',
                name: 'nama_produk'
            },
            {
                data: 'jumlah'
            },
            {
                data: 'jumlah1'
            },
            {
                data: 'kelompok'
            },
            // {
            //     data: 'action'
            // }
            {
                data: function (data) {
                    if (authid != 2) {
                        // console.log(data)
                        // return
                        return `<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr=""  data-id=` +
                            data.id + `>
                            <button class="btn btn-outline-success btn-sm" type="button" >
                            <i class="far fa-edit"></i>&nbsp;Edit
                            </button>
                        </a>

                        <a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr=""  data-id=` +
                            data.id + `>
                            <button class="btn btn-outline-info btn-sm" type="button" >
                            <i class="far fa-eye"></i>&nbsp;Detail
                            </button>
                        </a>

                        <a data-toggle="modal" data-target="#stokmodal" class="stokmodal" data-attr=""  data-id=` +
                            data.id + `>
                            <button class="btn btn-outline-warning btn-sm" type="button" >
                            <i class="far fa-eye"></i>&nbsp;Daftar Stok
                            </button>
                        </a>`
                    } else {
                        return `<a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr=""  data-id=` +
                            data.id + `>
                                <button class="btn btn-outline-info btn-sm" type="button" >
                                <i class="far fa-eye"></i>&nbsp;Detail
                                </button>
                            </a>`;
                    }

                }
            }
        ],
        language: {
            search: "Cari:",
            processing: `<span class='fa-stack fa-md'>\n\
                            <i class='fa fa-spinner fa-spin fa-stack-2x fa-fw'></i>\n\
                       </span>&emsp;Mohon Tunggu ...`,
        }
    });

    $('#alkes').click(function () {
        if ($(this).prop('checked') == true) {
            datatable.column(6).search($(this).val()).draw();
        } else {
            datatable.column(6).search('').draw();
        }
    })

    $('#sarkes').click(function () {
        if ($(this).prop('checked') == true) {
            datatable.column(6).search($(this).val()).draw();
        } else {
            datatable.column(6).search('').draw();
        }
    })

    $('#water').click(function () {
        if ($(this).prop('checked') == true) {
            datatable.column(6).search($(this).val()).draw();
        } else {
            datatable.column(6).search('').draw();
        }
    })


    // load produk
    $.ajax({
        url: '/api/gbj/sel-product',
        type: 'GET',
        dataType: 'json',
        success: function (res) {
            if (res) {
                console.log(res);
                $("#produk_id").empty();
                $("#produk_id").append('<option selected></option>');
                $.each(res, function (key, value) {
                    $("#produk_id").append('<option value="' + value.id + '">' + value.nama +
                        '</option');
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
        success: function (res) {
            if (res) {
                console.log(res);
                $(".satuan_id").empty();
                $(".satuan_id").append('<option selected></option>');
                $.each(res, function (key, value) {
                    $(".satuan_id").append('<option value="' + value.id + '">' + value.nama +
                        '</option');
                });
            } else {
                $(".satuan_id").empty();
            }
        }
    });

    // load modal create edit
    $('#create').click(function (e) {
        $('#Submitmodalcreate').val('create-product');
        $('#produkForm').trigger("reset");
        $('#exampleModalLabel').html('Tambah Variasi Produk');
        $('#modal-create').modal('show');
    });

    // load modal edit
    $(document).on('click', '.editmodal', function () {
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
            success: function (res) {
                console.log(res);
                $('#exampleModalLabel').html('Edit Produk ' + '<b>' + res.nama_produk[0].nama +
                    '</b>' + ' variasi ' + '<b>' + res.data[0].nama + '</b>');
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
                $('#inputGroupFile02').val(res.data[0].gambar);
            }
        });
    });

    // detail
    $(document).on('click', '.detailmodal', function () {
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
            success: function (res) {
                console.log(res);

                $('#header_data').html('Detail Produk ' + '<b>' + res.nama_produk[0].nama + '</b>' +
                    ' variasi ' + '<b>' + res.data[0].nama + '</b>');
                $('p#nama').text(res.data[0].nama);
                $('p#deskripsi').text(res.data[0].deskripsi);
                $('span#panjang').text(res.data[0].dim_p);
                $('span#lebar').text(res.data[0].dim_l);
                $('span#tinggi').text(res.data[0].dim_t);
                $('p#produk').text(res.nama_produk[0].product.nama + ' ' + res.nama_produk[0].nama);
                $('img#img_prd').attr("src", "http://localhost:8000/upload/gbj/" + res.data[0]
                    .gambar);
                $('#modal-view').modal('show');
            }
        });
    });

    // proses submit
    $('body').on('submit', '#produkForm', function (e) {
        e.preventDefault();
        var actionType = $('#Submitmodalcreate').val();
        $('#Submitmodalcreate').html('Sending..');
        var formData = new FormData(this);
        console.log(formData.get('produk_id'));
        $.ajax({
            type: 'POST',
            url: "/api/gbj/create",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: (data) => {
                if (data.error == true) {
                    $('#produkForm').trigger('reset');
                    // $('#modal-create').modal('hide');
                    $('#Submitmodalcreate').html('Kirim');
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: data.msg,
                    })
                } else {
                    $('#produkForm').trigger('reset');
                    $('#modal-create').modal('hide');
                    $('#Submitmodalcreate').html('Kirim');
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Data Berhasil Disimpan',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $('.datatable').DataTable().ajax.reload();
                    location.reload();
                }
            }
        });
    });

    // $('body').on('submit', '#formImport', function (e) {
    $('#formImport').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            url: "/api/v2/gbj/import-noseri",
            method: "post",
            data: new FormData(this),
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                $('#csv_data_file').html(data.data);
                if (data.error == true) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: data.msg,
                    }).then((result) => {
                        if (result.value) {
                            $('#bodyNoseri').show()
                            $('#existNoseri').html(data.noseri)
                            $('.btnImport').removeClass('btn-default')
                            $('.btnImport').addClass('btn-danger')
                            $('.btnImport').prop('disabled', true);
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: data.msg,
                    }).then((result) => {
                        if (result.value) {
                            $('#bodyNoseri').hide()
                            if ($('.btnImport').hasClass('btn-default')) {
                                $('.btnImport').removeClass('btn-default')
                            } else {
                                $('.btnImport').removeClass('btn-danger')
                            }
                            $('.btnImport').addClass('btn-success')
                            $('.btnImport').prop('disabled', false);
                        }
                    });
                }
                $('#footer-btn').show()
            }
        })
    })

    $('#formStoreImport').on('submit', function(e){
        e.preventDefault();
        $.ajax({
            url: "/api/v2/gbj/store-importseri",
            method: "post",
            data: new FormData(this),
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: data.msg,
                }).then((res) => {
                    location.reload()
                })
            }
        })
    })

    function select_layout() {
        $.ajax({
            url: '/api/gbj/sel-layout',
            type: 'GET',
            dataType: 'json',
            success: function (res) {
                $.each(res, function (key, value) {
                    $("#layout_id").append('<option value="' + value.id + '">' + value.ruang +
                        '</option');
                });
            }
        });
    }

    $('.hapusSeri').click(function(e){
        const cekid = [];
        const layout = [];
        let a = $('.scan-produk').DataTable().column(0).nodes()
            .to$().find('input[type=checkbox]:checked');
        $(a).each(function (index, elm) {
            cekid.push($(elm).val());
            layout.push($(elm).parent().next().children().val());
        });
        // console.log(layout);

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/api/v2/gbj/delete-noseri',
                    type: 'post',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        noseriid: cekid,
                        actionby: $('#actionby').val()
                    },
                    dataType: 'json',
                    success: function (res) {
                        if (res.error == true) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: res.msg,
                            })
                        } else {
                            Swal.fire(
                                'Deleted!',
                                res.msg,
                                'success'
                            ).then(function () {
                                location.reload();
                            })
                        }
                    }
                });
                }
            })
    })


    var title = $(this).parent().prev().prev().prev().prev().html();
    // modal noseri
    $(document).on('click', '.stokmodal', function () {
        var id = $(this).data('id');
        $('.seri_id').val(id);
        console.log(id);
        $('span#nm_produk').text($(this).parent().prev().prev().prev().prev().html());

        $('.scan-produk').DataTable({
            destroy: true,
            "ordering": false,
            "autoWidth": false,
            "lengthChange": false,
            processing: true,
            serverSide: false,
            ajax: {
                url: '/api/gbj/noseri/' + id,
            },
            columns: [{
                    data: 'ids'
                },
                {
                    data: 'seri'
                },
                {data: 'nomor'},
                {
                    data: 'Layout'
                },
                {
                    data: 'aksi'
                }
            ],
            "aoColumnDefs": [
                { "bSearchable": true, "bVisible": false, "aTargets": [ 2 ] },
                // { "bVisible": false, "aTargets": [ 3 ] }
            ]
        });

        $('.scan-produk1').DataTable({
            destroy: true,
            "ordering": false,
            "autoWidth": false,
            "lengthChange": false,
            processing: true,
            serverSide: false,
            ajax: {
                url: '/api/gbj/noseri-done/' + id,
            },
            columns: [{
                    data: 'ids'
                },
                {
                    data: 'seri'
                },
                {data: 'nomor'},
                {
                    data: 'used'
                },
                {
                    data: 'Layout'
                }
            ],
            "aoColumnDefs": [
                { "bSearchable": true, "bVisible": false, "aTargets": [ 2 ] },
                // { "bVisible": false, "aTargets": [ 3 ] }
            ]
        });

        $('.scan-produk2').DataTable({
            destroy: true,
            "ordering": false,
            "autoWidth": false,
            "lengthChange": false,
            processing: true,
            serverSide: false,
            pageLength: 5 ,
            ajax: {
                url: '/api/v2/gbj/list-waiting-noseri',
                type: 'post',
                data: {
                    gbjid: id
                },
            },
            columns: [{
                    data: 'DT_RowIndex'
                },
                {data: 'noseri_lama'},

                {
                    data: 'noseri'
                },
                {
                    data: 'action'
                },
            ],
        });

        $('.daftar-stok').modal('show');
    });

    $('.scan-produk').on('click', '.cb-child', function () {
        if($(this).is(':checked')){
            $(this).parent().next().children().attr('disabled', false);
        }else{
            $(this).parent().next().children().attr('disabled', true);
        }
    })

    $('.scan-produk1').on('click', '.cb-child1', function () {
        if($(this).is(':checked')){
            $(this).parent().next().children().attr('disabled', false);
        }else{
            $(this).parent().next().children().attr('disabled', true);
        }
    })

    // modal history
    $(document).on('click', '.viewStock', function () {
        var id = $(this).parent().prev().prev().text();
        let judul_detail = $('#nm_produk').text();
        $('p#namaa').text(judul_detail);
        console.log(id);
        var i = 0;
        $('.view_produk').DataTable().destroy();
        $('view_produk tbody').empty();
        $('.view_produk').DataTable({
            destroy: true,
            "ordering": false,
            "autoWidth": false,
            "lengthChange": false,
            processing: true,
            ajax: {
                url: '/api/gbj/history/' + id,
            },
            columns: [{
                    data: 'tanggal'
                },
                {
                    data: 'dari'
                },
            ],
        });

        $('.modalViewStock').modal('show');
    });

    // load layout
    $.ajax({
        url: '/api/gbj/sel-layout',
        type: 'GET',
        dataType: 'json',
        success: function (res) {
            console.log(res);
            $("#change_layout").empty();
            $.each(res, function (key, value) {
                layout.push([value.id, value.ruang]);
                $("#change_layout").append('<option value="' + value.id + '">' + value.ruang +
                    '</option');
            });
        }
    });

    // modal ubah layout
    $(document).on('click', '.editStok', function () {
        $('.edit-stok').modal('show');
    });

    $(document).ready(function () {
        $('#ubahSeri').on('click', function () {
            // console.log('test');
            const cekid = [];
            const layout = [];
            let a = $('.scan-produk').DataTable().column(0).nodes()
                .to$().find('input[type=checkbox]:checked');
            $(a).each(function (index, elm) {
                cekid.push($(elm).val());
                layout.push($(elm).parent().next().next().children().val());
            });
            // console.log(cekid);

            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Merubah Layout",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Terima!'
            }).then((result) => {
                if (result.value) {
                    Swal.fire(
                        'Sukses!',
                        'Layout berhasil dirubah',
                        'success'
                    )
                    $.ajax({
                        url: '/api/gbj/ubahseri',
                        type: 'post',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            cekid: cekid,
                            layout: layout,
                        },
                        success: function (res) {
                            console.log(res);
                        }
                    })
                    location.reload();
                }
            })

        })
    })

    $(document).on('click', '.tambah_noseri', function () {
        $('.tambah_noseri_tableee').DataTable().destroy();
        $('.tambah_noseri_table').empty();
        let jumlah = $('#jumlah_noseri').val();
        let table =
            '<tr><td><input type="text" name="" id="" class="form-control no_seri" style="text-transform:uppercase"><div class="invalid-feedback">Nomor seri ada yang sama.</div></td><td><select name="" id="" class="form-control layout_seri"></select></td></tr>';
        for (let i = 0; i < jumlah; i++) {
            $('.tambah_noseri_table').append(table);
        }
        $.each(layout, function (index, value) {
            $('.layout_seri').append('<option value="' + value[0] + '">' + value[1] + '</option');
        });
        $('.tambah_noseri_tableee').DataTable({
            destroy: true,
            searching: false,
        });
        $('.tambah_seri').modal('show');
    });

    $(document).on('click', '#save_data', function () {
        var dataNoSeri = $('.tambah_noseri_tableee').DataTable();
        let datalength = $('#jumlah_noseri').val();
        let no_seri = $('.tambah_noseri_tableee').DataTable().column(0).nodes().to$().find('input[type=text]')
            .map(function (index, elm) {
                return $(elm).val();
            }).get();
        let layout = $('.tambah_noseri_tableee').DataTable().column(1).nodes().to$().find('select').map(
            function (index, elm) {
                return $(elm).val();
            }).get();
        let id = $('.seri_id').val();
        let dari = $('.dari').val();
        let created_by = $('.created_by').val();

        let arr = [];
        const data = dataNoSeri.$('.no_seri').map(function () {
            return $(this).val();
        }).get();

        data.forEach(function (item) {
            if (item != '') {
                arr.push(item);
            }
        });

        const count = arr =>
            arr.reduce((a, b) => ({
                ...a,
                [b]: (a[b] || 0) + 1
            }), {})

        const duplicates = dict =>
            Object.keys(dict).filter((a) => dict[a] > 1)

        if (arr.length != datalength || arr.length == 0) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Nomor seri tidak boleh kosong!',
            })
        } else if (duplicates(count(arr)).length > 0) {
            $('.no_seri').removeClass('is-invalid');
            $('.no_seri').filter(function () {
                for (let index = 0; index < duplicates(count(arr))
                    .length; index++) {
                    if ($(this).val() == duplicates(count(arr))[index]) {
                        return true;
                    }
                }
            }).addClass('is-invalid');

            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Nomor seri ' + duplicates(count(arr)) +
                    ' ada yang sama.',
            }).then((result) => {
                if (result.value) {
                    $(this).prop('disabled', false);
                }
            });
        } else {
            $.ajax({
                url: '/api/gbj/ceknoseri',
                type: 'post',
                data: {
                    noseri: no_seri
                },
                success: function (res) {
                    if (res.msg) {
                        $.ajax({
                            type: 'post',
                            url: '/api/gbj/addSeri',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                no_seri: no_seri,
                                layout: layout,
                                id: id,
                                dari: dari,
                                created_by: created_by,
                            },
                            success: function (res) {
                                console.log(res);
                                if (res.success) {
                                    Swal.fire(
                                        'Sukses!',
                                        'Data berhasil ditambahkan',
                                        'success'
                                    )
                                    setTimeout(() => {
                                        location.reload();
                                    }, 1000);
                                }
                            }
                        });
                    } else {
                        Swal.fire(
                            'Oops',
                            res.error,
                            'error'
                        )
                    }
                }
            })
        }
        console.log("noseri", arr.length != datalength);

    })

    $(document).on('click', '#simpanSeriSudahDigunakan', function () {
        let seri = {};
        const cekid = [];
        const serii = [];
        const ori = [];
        let a = $('.scan-produk1').DataTable().column(0).nodes()
            .to$().find('input[type=checkbox]:checked');
        $(a).each(function (index, elm) {
            cekid.push($(elm).val());
            serii.push($(elm).parent().next().children()[1].value)
        });

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, change it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/api/v2/gbj/edit-noseri',
                        type: 'post',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            data: cekid,
                            new: serii,
                            actionby: $('#actionby').val()
                        },
                        dataType: 'json',
                        success: function(res) {
                            if (res.error == true) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: res.msg,
                                })
                            } else {
                                Swal.fire(
                                    'Updated!',
                                    res.msg,
                                    'success'
                                ).then(function () {
                                    location.reload();
                                })
                            }
                        }
                    })
                }
            })
    });

    $(document).on('click', '#simpanSeriBelumDigunakan', function () {
        let seri = {};
        const cekid = [];
        const serii = [];
        const ori = [];
        let a = $('.scan-produk').DataTable().column(0).nodes()
            .to$().find('input[type=checkbox]:checked');
        $(a).each(function (index, elm) {
            cekid.push($(elm).val());
            serii.push($(elm).parent().next().children()[1].value)
        });

        Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, change it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/api/v2/gbj/edit-noseri',
                    type: 'post',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        data: cekid,
                        new: serii,
                        actionby: $('#actionby').val()
                    },
                    dataType: 'json',
                    success: function(res) {
                        if (res.error == true) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: res.msg,
                            })
                        } else {
                            Swal.fire(
                                'Updated!',
                                res.msg,
                                'success'
                            ).then(function () {
                                location.reload();
                            })
                        }
                    }
                })
            }
        })

    });

</script>
@stop
