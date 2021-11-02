@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Penjualan</h1>
@stop

@section('adminlte_css')
<style>
    .hide {
        display: none !important;
    }

    .margin-xs {
        margin-left: 10px;
    }
</style>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card hide" id="ekatalog">
            <div class="card-body">
                <h4 class="margin-xs">Data Penjualan <small class="text-muted">(SOEKAT4918401)</small></h4>
                <div class="row margin-xs">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-5">
                                <div>
                                    <b>Info Customer</b>
                                </div>
                                <div id="nama_distributor">{{$ekatalog->customer->nama}}</div>
                                <div id="instansi">{{$ekatalog->instansi}}</div>
                                <div id="satuan_kerja">{{$ekatalog->satuan}}</div>
                                <div id="alamat">{{$ekatalog->customer->alamat}}</div>
                            </div>
                            <div class="col-3">
                                <div>
                                    <b>Info AKN</b>
                                </div>
                                <div id="no_paket">{{$ekatalog->no_paket}}</div>
                                <div id="tanggal_pemesanan">{{$ekatalog->tgl_buat}}</div>
                                <div id="batas_kontrak">{{$ekatalog->tgl_kontrak}}</div>
                                <div class="badge red-text" id="status">{{$ekatalog->status}}</div>
                            </div>
                            <div class="col-3">
                                <div>
                                    <b>PO & DO</b>
                                </div>
                                <div id="no_po">No PO</div>
                                <div id="tanggal_po">Tanggal PO</div>
                                <div id="no_do">No DO</div>
                                <div id="tanggal_do">Tanggal DO</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card hide" id="nonekatalog">
            <div class="card-body">
                <div class="row margin-xs">
                    <h4>Data Penjualan <small class="text-muted">(SOEKAT4918401)</small></h4>
                </div>
                <div class="row margin-xs">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-5">
                                <div>
                                    <small><b>Info Customer</b></small>
                                </div>
                                <div id="nama_customer">Nama Distributor</div>
                                <div id="alamat">Alamat</div>
                                <div id="provinsi">Provinsi</div>
                                <div id="telepon">Telepon</div>
                            </div>
                            <div class="col-4">
                                <div>
                                    <small><b>PO & DO</b></small>
                                </div>
                                <div id="no_po">No PO</div>
                                <div id="tanggal_po">Tanggal PO</div>
                                <div id="no_do">No DO</div>
                                <div id="tanggal_do">Tanggal DO</div>
                            </div>
                            <div class="col-3">
                                <div>
                                    <small><b>Status</b></small>
                                </div>
                                <div id="status" class="badge orange-text">Gudang</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header bg-warning">
                <div class="card-title">Form Ubah Data</div>
            </div>
            <div class="card-body">
                @if(session()->has('error') || count($errors) > 0 )
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Gagal mengubah data!</strong> Periksa
                    kembali data yang diinput
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @elseif(session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Berhasil mengubah data</strong>,
                    Terima kasih
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
                <div class="content">
                    <form>
                        <div class="row d-flex justify-content-center">
                            <div class="col-10">
                                <h4>Info Customer</h4>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-horizontal">
                                            <div class="form-group row">
                                                <label for="" class="col-form-label col-5" style="text-align: right">Nama Customer</label>
                                                <div class="col-5">
                                                    <select name="customer_id" id="customer_id" class="form-control customer_id custom-select @error('customer_id') is-invalid @enderror">
                                                        <option value="{{$ekatalog->customer_id}}">{{$ekatalog->customer->nama}}</option>
                                                    </select>
                                                    <div class="invalid-feedback" id="msgcustomer_id">
                                                        @if($errors->has('customer_id'))
                                                        {{ $errors->first('customer_id')}}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="" class="col-form-label col-5" style="text-align: right">Alamat</label>
                                                <div class="col-7">
                                                    <input type="text" class="form-control col-form-label" name="alamat" id="alamat_customer" readonly value="{{$ekatalog->customer->alamat}}" />
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="" class="col-form-label col-5" style="text-align: right">Telepon</label>
                                                <div class="col-5">
                                                    <input type="text" class="form-control col-form-label" name="telepon" id="telepon_customer" readonly value="{{$ekatalog->customer->telp}}" />
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center hide" id="akn">
                            <div class="col-10">
                                <h4>Info AKN</h4>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-horizontal">
                                            <div class="form-group row">
                                                <label for="" class="col-form-label col-5" style="text-align: right">Status</label>
                                                <div class="col-5 col-form-label">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="status_akn" id="status_akn" value="sepakat" />
                                                        <label class="form-check-label" for="status_akn1">Sepakat</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="status_akn" id="status_akn" value="negosiasi" />
                                                        <label class="form-check-label" for="status_akn2">Negosiasi</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="status_akn" id="status_akn" value="batal" />
                                                        <label class="form-check-label" for="status_akn3">Batal</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="" class="col-form-label col-5" style="text-align: right">Instansi</label>
                                                <div class="col-7">
                                                    <input type="text" class="form-control col-form-label @error('instansi') is-invalid @enderror" name="instansi" id="instansi" value="{{$ekatalog->instansi}}" />
                                                    <div class="invalid-feedback" id="msginstansi">
                                                        @if($errors->has('instansi'))
                                                        {{ $errors->first('instansi')}}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="" class="col-form-label col-5" style="text-align: right">Satuan Kerja</label>
                                                <div class="col-7">
                                                    <input type="text" class="form-control col-form-label @error('satuan_kerja') is-invalid @enderror" name="satuan_kerja" id="satuan_kerja" value="{{$ekatalog->satuan}}" />
                                                    <div class=" invalid-feedback" id="msgsatuan_kerja">
                                                        @if($errors->has('satuan_kerja'))
                                                        {{ $errors->first('satuan_kerja')}}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="" class="col-form-label col-5" style="text-align: right">Deskripsi</label>
                                                <div class="col-5">
                                                    <textarea class="form-control col-form-label @error('deskripsi') is-invalid @enderror" name="deskripsi" id="deskripsi">{{$ekatalog->deskripsi}}</textarea>
                                                    <div class="invalid-feedback" id="msgdeskripsi">
                                                        @if($errors->has('deskripsi'))
                                                        {{ $errors->first('deskripsi')}}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="keterangan" class="col-form-label col-5" style="text-align: right">Keterangan</label>
                                                <div class="col-5">
                                                    <textarea class="form-control col-form-label" v-model="keterangan">{{$ekatalog->ket}}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center hide" id="nonakn">
                            <div class="col-10">
                                <h4>Info Penjualan</h4>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <label for="" class="col-form-label col-5" style="text-align: right">Delivery Order</label>
                                            <div class="col-5 col-form-label">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="do" id="yes" value="yes" />
                                                    <label class="form-check-label" for="yes">Tersedia</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="do" id="no" value="no" />
                                                    <label class="form-check-label" for="no">Tidak tersedia</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row hide" id="do_detail_no">
                                            <label for="" class="col-form-label col-5" style="text-align: right">Nomor DO</label>
                                            <div class="col-4">
                                                <input type="text" class="form-control col-form-label @error('no_do') is-invalid @enderror" id="no_do" name="no_do" />
                                                <div class="invalid-feedback" id="msgno_do">
                                                    @if($errors->has('no_do'))
                                                    {{ $errors->first('no_do')}}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row hide" id="do_detail_tgl">
                                            <label for="" class="col-form-label col-5" style="text-align: right">Tanggal DO</label>
                                            <div class="col-4">
                                                <input type="date" class="form-control col-form-label @error('tanggal_do') is-invalid @enderror" id="tanggal_do" name="tanggal_do" />
                                                <div class="invalid-feedback" id="msgtanggal_po">
                                                    @if($errors->has('tanggal_po'))
                                                    {{ $errors->first('tanggal_po')}}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="keterangan" class="col-form-label col-5" style="text-align: right">Keterangan</label>
                                            <div class="col-5">
                                                <textarea class="form-control col-form-label" id="nonketerangan"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center hide" id="dataproduk">
                            <div class="col-10">
                                <h4>Data Produk</h4>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="table-responsive">
                                                    <table class="table" style="text-align: center;" id="produktable">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="7">
                                                                    <button type="button" class="btn btn-primary float-right" id="addrowproduk">
                                                                        <i class="fas fa-plus"></i>
                                                                        Produk
                                                                    </button>
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <th width="5%">No</th>
                                                                <th width="35%">Nama Paket</th>
                                                                <th width="15%">Jumlah</th>
                                                                <th width="20%">Harga</th>
                                                                <th width="20%">Subtotal</th>
                                                                <th width="5%">Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($ekatalog as $d)
                                                            <tr>
                                                                <td>1</td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <select name="penjualan_produk_id[]" id="penjualan_produk_id" class="select2 form-control custom-select penjualan_produk_id @error('penjualan_produk_id') is-invalid @enderror" style="width:100%;">
                                                                            <option value=""></option>
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group d-flex justify-content-center">
                                                                        <div class="input-group">
                                                                            <input type="number" class="form-control produk_jumlah" aria-label="produk_satuan" name="produk_jumlah[]" id="produk_jumlah" style="width:100%;" value="">
                                                                            <div class="input-group-append">
                                                                                <span class="input-group-text" id="produk_satuan">pcs</span>
                                                                            </div>
                                                                        </div>
                                                                        <small id="produk_ketersediaan"></small>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group d-flex justify-content-center">
                                                                        <input type="number" class="form-control produk_harga" id="produk_harga" name="produk_harga[]" style="width:100%;" />
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group d-flex justify-content-center">
                                                                        <input type="number" class="form-control produk_subtotal" id="produk_subtotal" name="produk_subtotal[]" style="width:100%;" readonly />
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <a id="removerowproduk"><i class="fas fa-minus" style="color: red"></i></a>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th colspan="4" style="text-align:right;">Total Harga</th>
                                                                <th id="totalhargaprd" class="align-right">Rp. 0</th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center hide" id="datapart">
                            <div class="col-10">
                                <h4>Data Part</h4>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="table-responsive">
                                                    <table class="table" style="text-align: center;" id="parttable">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="7">
                                                                    <button type="button" class="btn btn-primary float-right" id="addrowpart">
                                                                        <i class="fas fa-plus"></i>
                                                                        Part
                                                                    </button>
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <th width="5%">No</th>
                                                                <th width="35%">Nama Part</th>
                                                                <th width="15%">Jumlah</th>
                                                                <th width="20%">Harga</th>
                                                                <th width="20%">Subtotal</th>
                                                                <th width="5%">Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1</td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <select class="select2 form-control select-info custom-select part_id" name="part_id" id="part_id" width="100%">
                                                                            <option value=""></option>
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group d-flex justify-content-center">
                                                                        <div class="input-group">
                                                                            <input type="number" class="form-control part_jumlah" aria-label="produk_satuan" name="part_jumlah[]" id="part_jumlah" style="width:100%;">
                                                                            <div class="input-group-append">
                                                                                <span class="input-group-text" id="part_satuan">pcs</span>
                                                                            </div>
                                                                        </div>
                                                                        <small id="part_ketersediaan"></small>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group d-flex justify-content-center">
                                                                        <input type="number" class="form-control part_harga" id="part_harga" name="part_harga[]" style="width:100%;" />
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group d-flex justify-content-center">
                                                                        <input type="number" class="form-control part_subtotal" id="part_subtotal" name="part_subtotal[]" style="width:100%;" readonly />
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <a id="removerowpart"><i class="fas fa-minus" style="color: red"></i></a>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th colspan="4" style="text-align:right;">Total Harga</th>
                                                                <th id="totalhargapart" class="align-right">Rp. 0</th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center">
                            <div class="col-10">
                                <span>
                                    <a href="{{route('penjualan.penjualan.show')}}" type="button" class="btn btn-danger">
                                        Batal
                                    </a>
                                </span>
                                <span class="float-right">
                                    <button type="submit" class="btn btn-warning" id="btnsimpan">
                                        Simpan
                                    </button>
                                </span>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('adminlte_js')
<script>
    $(function() {
        var jenis = "{{$jenis}}";
        jenis_penjualan(jenis);

        $('input[name="status_akn"][value={{$ekatalog->status}}]').attr('checked', 'checked');
        $('#customer_id').on('keyup change', function() {
            if ($(this).val() != "") {
                $('#msgcustomer_id').text("");
                $('#customer_id').removeClass('is-invalid');
                //var value = getCustomer($(this).val());
                // $('#alamat').val(value.alamat);
                // $('#telepon').val(value.telepon);
            } else if ($(this).val() == "") {
                $('#msgcustomer_id').text("Silahkan Pilih Customer");
                $('#customer_id').addClass('is-invalid');
            }
        });

        $('#tanggal_pemesanan').on('keyup', function() {
            if ($(this).val() != "") {
                $("#msgtanggal_pemesanan").text("");
                $("#tanggal_pemesanan").removeClass('is-invalid');
            } else if ($(this).val() == "") {
                $("#msgtanggal_pemesanan").text("Isi Tanggal Pemesanan");
                $("#tanggal_pemesanan").addClass('is-invalid');
            }
        });

        function jenis_penjualan(jenis_penjualan) {
            if (jenis_penjualan == "ekatalog") {
                $("#datapart").addClass("hide");
                $("#dataproduk").removeClass("hide");
                $("#nonakn").addClass("hide");
                $("#akn").removeClass("hide");
                $("#nonekatalog").addClass("hide");
                $("#ekatalog").removeClass("hide");
            } else if (jenis_penjualan == "spa") {
                $("#datapart").addClass("hide");
                $("#dataproduk").removeClass("hide");
                $("#nonakn").removeClass("hide");
                $("#akn").addClass("hide");

                $("#nonekatalog").removeClass("hide");
                $("#ekatalog").addClass("hide");
            } else if (jenis_penjualan == "spb") {
                $("#datapart").removeClass("hide");
                $("#dataproduk").addClass("hide");
                $("#nonakn").removeClass("hide");
                $("#akn").addClass("hide");
                $("#nonekatalog").removeClass("hide");
                $("#ekatalog").addClass("hide");
            }
        }

        $('input[type="radio"][name="do_akn"]').on('change', function() {
            if ($(this).val() == "yes") {
                $("#do_detail_no_akn").removeClass("hide");
                $("#do_detail_tgl_akn").removeClass("hide");
            } else if ($(this).val() == "no") {
                $("#do_detail_no_akn").addClass("hide");
                $("#do_detail_tgl_akn").addClass("hide");
            }
        });

        $('input[type="radio"][name="do"]').on('change', function() {
            if ($(this).val() == "yes") {
                $("#do_detail_no").removeClass("hide");
                $("#do_detail_tgl").removeClass("hide");
            } else if ($(this).val() == "no") {
                $("#do_detail_no").addClass("hide");
                $("#do_detail_tgl").addClass("hide");
            }
        });

        $('#batas_kontrak').on('keyup', function() {
            if ($(this).val() != "") {
                $("#msgbatas_kontrak").text("");
                $("#batas_kontrak").removeClass('is-invalid');
            } else if ($(this).val() == "") {
                $("#msgbatas_kontrak").text("Batas Kontrak Harus diisi");
                $("#batas_kontrak").addClass('is-invalid');
            }
        });
        $('#instansi').on('keyup', function() {
            if ($(this).val() != "") {
                $("#msginstansi").text("");
                $("#instansi").removeClass('is-invalid');
            } else if ($(this).val() == "") {
                $("#msginstansi").text("Instansi Harus diisi");
                $("#instansi").addClass('is-invalid');
            }
        });
        $('#deskripsi').on('keyup', function() {
            if ($(this).val() != "") {
                $("#msgdeskripsi").text("");
                $("#deskripsi").removeClass('is-invalid');
            } else if ($(this).val() == "") {
                $("#msgdeskripsi").text("Deskripsi harus diisi");
                $("#deskripsi").addClass('is-invalid');
            }
        });
        $('input[name="no_paket"]').on('keyup', function() {
            if ($(this).val() != "") {
                $("#msgno_paket").text("");
                $('input[name="no_paket"]').removeClass('is-invalid');
            } else if ($(this).val() == "") {
                $("#msgno_paket").text("No Paket harus diisi");
                $('input[name="no_paket"]').addClass('is-invalid');
            }
        });
        $('#nontanggal_pemesanan').on('keyup', function() {
            if ($(this).val() != "") {
                $("#msgnontanggal_pemesanan").text("");
                $("#nontanggal_pemesanan").removeClass('is-invalid');
            } else if ($(this).val() == "") {
                $("#msgnontanggal_pemesanan").text("Isi Tanggal Pemesanan");
                $("#nontanggal_pemesanan").addClass('is-invalid');
            }
        });
        $('#no_po_akn').on('keyup', function() {
            if ($(this).val() != "") {
                $("#msgno_po_akn").text("");
                $("#no_po_akn").removeClass('is-invalid');
            } else if ($(this).val() == "") {
                $("#msgno_po_akn").text("Nomor PO Harus diisi");
                $("#no_po_akn").addClass('is-invalid');
            }
        });
        $('#tanggal_po_akn').on('keyup', function() {
            if ($(this).val() != "") {
                $("#msgtanggal_po_akn").text("");
                $("#tanggal_po_akn").removeClass('is-invalid');
            } else if ($(this).val() == "") {
                $("#msgtanggal_po_akn").text("Tanggal PO Harus diisi");
                $("#tanggal_po_akn").addClass('is-invalid');
            }
        });

        $('#no_po').on('keyup', function() {
            if ($(this).val() != "") {
                $("#msgno_po").text("");
                $("#no_po").removeClass('is-invalid');
            } else if ($(this).val() == "") {
                $("#msgno_po").text("Nomor PO Harus diisi");
                $("#no_po").addClass('is-invalid');
            }
        });
        $('#tanggal_po').on('keyup', function() {
            if ($(this).val() != "") {
                $("#msgtanggal_po").text("");
                $("#tanggal_po").removeClass('is-invalid');
            } else if ($(this).val() == "") {
                $("#msgtanggal_po").text("Tanggal PO Harus diisi");
                $("#tanggal_po").addClass('is-invalid');
            }
        });

        function totalhargaprd() {
            var totalharga = 0;
            $('#produktable').find('tr .produk_subtotal').each(function() {
                var subtotal = $(this).val();
                totalharga = parseInt(totalharga) + parseInt(subtotal);
                $("#totalhargaprd").text("Rp. " + totalharga.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1."));
            })
        }

        function totalhargapart() {
            var totalharga = 0;
            $('#parttable').find('tr .part_subtotal').each(function() {
                var subtotal = $(this).val();
                totalharga = parseInt(totalharga) + parseInt(subtotal);
                $("#totalhargapart").text("Rp. " + totalharga.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1."));
            })
        }

        function numberRowsProduk($t) {
            var c = 0 - 2;
            $t.find("tr").each(function(ind, el) {
                $(el).find("td:eq(0)").html(++c);
                var j = c - 1;
                $(el).find('.penjualan_produk_id').attr('name', 'penjualan_produk_id[' + j + ']');
                $(el).find('.penjualan_produk_id').attr('id', 'penjualan_produk_id' + j);
                $(el).find('.variasi').attr('name', 'variasi[' + j + ']');
                $(el).find('.variasi').attr('id', 'variasi' + j);
                $(el).find('input[id="produk_jumlah"]').attr('name', 'produk_jumlah[' + j + ']');
                $('.produk_id').select2();
            });
        }

        $("#produktable").on('keyup change', '.produk_jumlah', function() {
            var jumlah = $(this).closest('tr').find('.produk_jumlah').val();
            var harga = $(this).closest('tr').find('.produk_harga').val();
            var subtotal = $(this).closest('tr').find('.produk_subtotal');

            if (jumlah != "" && harga != "") {
                subtotal.val(jumlah * harga);
                totalhargaprd();
            }
        });

        $("#produktable").on('keyup change', '.produk_harga', function() {
            var jumlah = $(this).closest('tr').find('.produk_jumlah').val();
            var harga = $(this).closest('tr').find('.produk_harga').val();
            var subtotal = $(this).closest('tr').find('.produk_subtotal');
            if (jumlah != "" && harga != "") {
                subtotal.val(jumlah * harga);
                totalhargaprd();
            }
        });

        $('#addrowproduk').on('click', function() {
            $('#produktable tbody tr:last').after(`<tr>
                <td></td>
                <td>
                    <div class="form-group">
                        <select name="penjualan_produk_id[]" id="penjualan_produk_id" class="select2 form-control custom-select penjualan_produk_id @error('penjualan_produk_id') is-invalid @enderror" style="width:100%;">
                            <option value=""></option>
                        </select>
                    </div>
                </td>
                <td>
                    <div class="form-group d-flex justify-content-center">
                        <div class="input-group">
                            <input type="number" class="form-control produk_jumlah" aria-label="produk_satuan" name="produk_jumlah[]" id="produk_jumlah" style="width:100%;">
                            <div class="input-group-append">
                                <span class="input-group-text" id="produk_satuan">pcs</span>
                            </div>
                        </div>
                        <small id="produk_ketersediaan"></small>
                    </div>
                </td>
                <td>
                    <div class="form-group d-flex justify-content-center">
                        <input type="number" class="form-control produk_harga" id="produk_harga" name="produk_harga[]" style="width:100%;" />
                    </div>
                </td>
                <td>
                    <div class="form-group d-flex justify-content-center">
                        <input type="number" class="form-control produk_subtotal" id="produk_subtotal" name="produk_subtotal[]" style="width:100%;" />
                    </div>
                </td>
                <td>
                    <a id="removerowproduk"><i class="fas fa-minus" style="color: red;"></i></a>
                </td>
            </tr>`);
            numberRowsProduk($("#produktable"));
        });

        $('#produktable').on('click', '#removerowproduk', function(e) {
            $(this).closest('tr').remove();
            numberRowsProduk($("#produktable"));
            totalhargaprd();
        });

        function numberRowsPart($t) {
            var c = 0 - 2;
            $t.find("tr").each(function(ind, el) {
                $(el).find("td:eq(0)").html(++c);
                var j = c - 1;
                $(el).find('.part_id').attr('name', 'part_id[' + j + ']');
                $(el).find('.part_id').attr('id', 'part_id' + j);
                $(el).find('input[id="part_jumlah"]').attr('name', 'part_jumlah[' + j + ']');
                // $('.produk_id').select2();
            });
        }

        $('#addrowpart').on('click', function() {
            $('#parttable tbody tr:last').after(`
            <tr>
                <td></td>
                <td>
                    <div class="form-group">
                        <select class="select2 form-control select-info custom-select part_id" name="part_id" id="part_id" width="100%">
                            <option value=""></option>
                        </select>
                    </div>
                </td>
                <td>
                    <div class="form-group d-flex justify-content-center">
                        <div class="input-group">
                            <input type="number" class="form-control part_jumlah" aria-label="produk_satuan" name="part_jumlah[]" id="part_jumlah" style="width:100%;">
                            <div class="input-group-append">
                                <span class="input-group-text" id="part_satuan">pcs</span>
                            </div>
                        </div>
                        <small id="part_ketersediaan"></small>
                    </div>
                </td>
                <td>
                    <div class="form-group d-flex justify-content-center">
                        <input type="number" class="form-control part_harga" id="part_harga" name="part_harga[]" style="width:100%;" />
                    </div>
                </td>
                <td>
                    <div class="form-group d-flex justify-content-center">
                        <input type="number" class="form-control part_subtotal" id="part_subtotal" name="part_subtotal[]" style="width:100%;" />
                    </div>
                </td>
                <td>
                    <a id="removerowpart"><i class="fas fa-minus" style="color: red"></i></a>
                </td>
            </tr>`);
            numberRowsPart($("#parttable"));
        });

        $("#parttable").on('keyup change', '.part_jumlah', function() {
            var jumlah = $(this).closest('tr').find('.part_jumlah').val();
            var harga = $(this).closest('tr').find('.part_harga').val();
            var subtotal = $(this).closest('tr').find('.part_subtotal');

            if (jumlah != "" && harga != "") {
                subtotal.val(jumlah * harga);
                totalhargapart();
            }
        });

        $("#parttable").on('keyup change', '.part_harga', function() {
            var jumlah = $(this).closest('tr').find('.part_jumlah').val();
            var harga = $(this).closest('tr').find('.part_harga').val();
            var subtotal = $(this).closest('tr').find('.part_subtotal');
            if (jumlah != "" && harga != "") {
                subtotal.val(jumlah * harga);
                totalhargapart();
            }

        });

        $('#parttable').on('click', '#removerowpart', function(e) {
            $(this).closest('tr').remove();
            numberRowsPart($("#parttable"));
            totalhargapart();
        });


        $('#customer_id').select2({
            ajax: {
                minimumResultsForSearch: 20,
                placeholder: "Pilih Customer",
                dataType: 'json',
                theme: "bootstrap",
                delay: 250,
                type: 'GET',
                url: '/api/customer/select',
                data: function(params) {
                    return {
                        term: params.term
                    }
                },
                processResults: function(data) {
                    console.log(data);
                    return {
                        results: $.map(data, function(obj) {
                            return {
                                id: obj.id,
                                text: obj.nama
                            };
                        })
                    };
                },
            }
        }).change(function() {
            var id = $(this).val();
            $.ajax({
                url: '/api/customer/select/' + id,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    $('#alamat_customer').val(data[0].alamat);
                    $('#telepon_customer').val(data[0].telp);
                }
            });
        });

    });
</script>
@stop