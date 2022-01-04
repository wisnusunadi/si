@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')

<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0  text-dark">Penjualan</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                @if(Auth::user()->divisi_id == "26")
                <li class="breadcrumb-item"><a href="{{route('penjualan.dashboard')}}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{route('penjualan.penjualan.show')}}">Penjualan</a></li>
                <li class="breadcrumb-item active">Tambah Penjualan</li>
                @endif
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->

@stop

@section('adminlte_css')
<style>
    .hide {
        display: none !important
    }

    .align-right {
        text-align: right;
    }

    .select2 {
        width: 100% !important;
    }

    legend {
        font-size: 14px;
    }

    filter {
        margin: 5px;
    }

    .blue-bg {
        background-color: #c8daea;
    }

    #penjualanform {
        top: 0;

    }

    @media screen and (min-width: 1440px) {

        section {
            font-size: 14px;
        }

        .btn {
            font-size: 14px;
        }
    }

    @media screen and (max-width: 1439px) {

        /* label,
        .row {
            font-size: 12px;
        }

        h4 {
            font-size: 20px;
        } */
        section {
            font-size: 12px;
        }

        .btn {
            font-size: 12px;
        }
    }
</style>
@stop

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center" id="penjualanform">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-info">
                        <div class="card-title">Form Tambah Data</div>
                    </div>
                    <div class="card-body">
                        @if(Session::has('error') || count($errors) > 0 )
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>{{Session::get('error')}}</strong> Periksa
                            kembali data yang diinput
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @elseif(Session::has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>{{Session::get('success')}}</strong>,
                            Terima kasih
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @endif
                        <form method="post" action="{{route('penjualan.penjualan.store')}}">
                            {{csrf_field()}}
                            <div class="row d-flex justify-content-center">
                                <div class="col-11">
                                    <h4>Info Customer</h4>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-horizontal">
                                                <div class="form-group row">
                                                    <label for="" class="col-form-label col-5" style="text-align: right">Nama Customer</label>
                                                    <div class="col-5">
                                                        <select name="customer_id" id="customer_id" class="form-control custom-select customer_id @error('customer_id') is-invalid @enderror">
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="" class="col-form-label col-5" style="text-align: right">Alamat</label>
                                                    <div class="col-7">
                                                        <input type="text" class="form-control col-form-label" name="alamat" id="alamat" readonly />
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="" class="col-form-label col-5" style="text-align: right">Telepon</label>
                                                    <div class="col-5">
                                                        <input type="text" class="form-control col-form-label" name="telepon" id="telepon" readonly />
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="" class="col-form-label col-5" style="text-align: right">Jenis Penjualan</label>
                                                    <div class="col-5 col-form-label">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="jenis_penjualan" id="jenis_penjualan1" value="ekatalog" />
                                                            <label class="form-check-label" for="jenis_penjualan1">E-Catalogue</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="jenis_penjualan" id="jenis_penjualan2" value="spa" />
                                                            <label class="form-check-label" for="jenis_penjualan2">SPA</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="jenis_penjualan" id="jenis_penjualan3" value="spb" />
                                                            <label class="form-check-label" for="jenis_penjualan3">SPB</label>
                                                        </div>
                                                        <div class="invalid-feedback" id="msgjenis_penjualan">
                                                            @if($errors->has('jenis_penjualan'))
                                                            {{ $errors->first('jenis_penjualan')}}
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center hide" id="akn">
                                <div class="col-11">
                                    <h4>Info AKN</h4>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-horizontal">
                                                <div class="form-group row">
                                                    <label for="" class="col-form-label col-5" style="text-align: right">Tanggal Pemesanan</label>
                                                    <div class="col-4">
                                                        <input type="date" class="form-control col-form-label @error('tanggal_pemesanan') is-invalid @enderror" name="tanggal_pemesanan" id="tanggal_pemesanan" />
                                                        <div class="invalid-feedback" id="msgtanggal_pemesanan">
                                                            @if($errors->has('tanggal_pemesanan'))
                                                            {{ $errors->first('tanggal_pemesanan')}}
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="" class="col-form-label col-5" style="text-align: right">Instansi</label>
                                                    <div class="col-7">
                                                        <input type="text" class="form-control col-form-label @error('instansi') is-invalid @enderror" name="instansi" id="instansi" />
                                                        <div class="invalid-feedback" id="msginstansi">
                                                            @if($errors->has('instansi'))
                                                            {{ $errors->first('instansi')}}
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="" class="col-form-label col-5" style="text-align: right">Alamat Instansi</label>
                                                    <div class="col-7">
                                                        <input type="text" class="form-control col-form-label @error('alamatinstansi') is-invalid @enderror" name="alamatinstansi" id="alamatinstansi" />
                                                        <div class="invalid-feedback" id="msgalamatinstansi">
                                                            @if($errors->has('alamatinstansi'))
                                                            {{ $errors->first('alamatinstansi')}}
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="" class="col-form-label col-5" style="text-align: right">Provinsi</label>
                                                    <div class="col-7">
                                                        <select name="provinsi" id="provinsi" class="form-control custom-select provinsi @error('provinsi') is-invalid @enderror" style="width: 100%;">
                                                        </select>
                                                        <div class="invalid-feedback" id="msgprovinsi">
                                                            @if($errors->has('provinsi'))
                                                            {{ $errors->first('provinsi')}}
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="" class="col-form-label col-5" style="text-align: right">Satuan Kerja</label>
                                                    <div class="col-7">
                                                        <input type="text" class="form-control col-form-label @error('satuan_kerja') is-invalid @enderror" name="satuan_kerja" id="satuan_kerja" />
                                                        <div class="invalid-feedback" id="msgsatuan_kerja">
                                                            @if($errors->has('satuan_kerja'))
                                                            {{ $errors->first('satuan_kerja')}}
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="" class="col-form-label col-5" style="text-align: right">Status</label>
                                                    <div class="col-5 col-form-label">
                                                        <!-- <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="status" id="satuan4" value="draft" />
                                                    <label class="form-check-label" for="satuan4">Draft</label>
                                                </div> -->
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="status" id="satuan1" value="sepakat" />
                                                            <label class="form-check-label" for="satuan1">Sepakat</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="status" id="satuan2" value="negosiasi" />
                                                            <label class="form-check-label" for="satuan2">Negosiasi</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="status" id="satuan3" value="batal" />
                                                            <label class="form-check-label" for="satuan3">Batal</label>
                                                        </div>
                                                        <div class="invalid-feedback" id="msgstatus">
                                                            @if($errors->has('status'))
                                                            {{ $errors->first('status')}}
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="" class="col-form-label col-5" style="text-align: right">No Paket</label>
                                                    <div class="col-5 input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="ket_no_paket">AK1-</span>
                                                        </div>
                                                        <input type="text" class="form-control col-form-label @error('nomor_paket') is-invalid @enderror" name="no_paket" id="no_paket" aria-label="ket_no_paket" />
                                                        <div class="invalid-feedback" id="msgno_paket">
                                                            @if($errors->has('no_paket'))
                                                            {{ $errors->first('no_paket')}}
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="" class="col-form-label col-5" style="text-align: right">Tanggal Delivery</label>
                                                    <div class="col-4">
                                                        <input type="date" class="form-control col-form-label @error('batas_kontrak') is-invalid @enderror" name="batas_kontrak" id="batas_kontrak" />
                                                        <div class="invalid-feedback" id="msgbatas_kontrak">
                                                            @if($errors->has('batas_kontrak'))
                                                            {{ $errors->first('batas_kontrak')}}
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="" class="col-form-label col-5" style="text-align: right">Deskripsi</label>
                                                    <div class="col-5">
                                                        <textarea class="form-control col-form-label @error('deskripsi') is-invalid @enderror" name="deskripsi" id="deskripsi"></textarea>
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
                                                        <textarea class="form-control col-form-label" name="keterangan"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center hide" id="nonakn">
                                <div class="col-11">
                                    <h4>Info Penjualan</h4>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-group row">
                                                <label for="" class="col-form-label col-5" style="text-align: right">Nomor PO</label>
                                                <div class="col-4">
                                                    <input type="text" class="form-control col-form-label @error('no_po') is-invalid @enderror" id="no_po" name="no_po" />
                                                    <div class="invalid-feedback" id="msgno_po">
                                                        @if($errors->has('no_po'))
                                                        {{ $errors->first('no_po')}}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="" class="col-form-label col-5" style="text-align: right">Tanggal PO</label>
                                                <div class="col-4">
                                                    <input type="date" class="form-control col-form-label @error('tanggal_po') is-invalid @enderror" id="tanggal_po" name="tanggal_po" />
                                                    <div class="invalid-feedback" id="msgtanggal_po">
                                                        @if($errors->has('tanggal_po'))
                                                        {{ $errors->first('tanggal_po')}}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
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
                                                    <div class="invalid-feedback" id="msgtanggal_do">
                                                        @if($errors->has('tanggal_do'))
                                                        {{ $errors->first('tanggal_do')}}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="keterangan" class="col-form-label col-5" style="text-align: right">Keterangan</label>
                                                <div class="col-5">
                                                    <textarea class="form-control col-form-label" id="nonketerangan" name="keterangan"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center hide" id="dataproduk">
                                <div class="col-11">
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
                                                                <tr>
                                                                    <td>1</td>
                                                                    <td>
                                                                        <div class="form-group">
                                                                            <select name="penjualan_produk_id[]" id="0" class="select2 form-control custom-select penjualan_produk_id @error('penjualan_produk_id') is-invalid @enderror" style="width:100%;">
                                                                                <option value=""></option>
                                                                            </select>

                                                                        </div>
                                                                        <div class="detail_produk" id="detail_produk0">
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-group d-flex justify-content-center">
                                                                            <div class="input-group">
                                                                                <input type="number" class="form-control produk_jumlah" aria-label="produk_satuan" name="produk_jumlah[]" id="produk_jumlah0" style="width:100%;">
                                                                                <div class="input-group-append">
                                                                                    <span class="input-group-text" id="produk_satuan">pcs</span>
                                                                                </div>
                                                                            </div>
                                                                            <small id="produk_ketersediaan"></small>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-group d-flex justify-content-center">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text">Rp</span>
                                                                            </div>
                                                                            <input type="text" class="form-control produk_harga" name="produk_harga[]" id="produk_harga0" placeholder="Masukkan Harga" style="width:100%;" />
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-group d-flex justify-content-center">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text">Rp</span>
                                                                            </div>
                                                                            <input type="text" class="form-control produk_subtotal" name="produk_subtotal[]" id="produk_subtotal0" placeholder="Masukkan Subtotal" style="width:100%;" readonly />
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <a id="removerowproduk"><i class="fas fa-minus" style="color: red"></i></a>
                                                                    </td>
                                                                </tr>
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
                                <div class="col-11">
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
                                                                            <select class="select2 form-control select-info custom-select part_id" name="part_id[]" id="part_id0" width="100%">
                                                                            </select>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-group d-flex justify-content-center">
                                                                            <div class="input-group">
                                                                                <input type="number" class="form-control part_jumlah" aria-label="produk_satuan" name="part_jumlah[]" id="part_jumlah0" style="width:100%;">
                                                                                <div class="input-group-append">
                                                                                    <span class="input-group-text" id="part_satuan">pcs</span>
                                                                                </div>
                                                                            </div>
                                                                            <small id="part_ketersediaan"></small>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-group d-flex justify-content-center">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text">Rp</span>
                                                                            </div>
                                                                            <input type="text" class="form-control part_harga" name="part_harga[]" id="part_harga0" placeholder="Masukkan Harga" style="width:100%;" />
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-group d-flex justify-content-center">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text">Rp</span>
                                                                            </div>
                                                                            <input type="text" class="form-control part_subtotal" name="part_subtotal[]" id="part_subtotal0" placeholder="Masukkan Subtotal" style="width:100%;" readonly />
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
                                <div class="col-11">
                                    <span>
                                        <a href="{{route('penjualan.penjualan.show')}}" type="button" class="btn btn-danger">
                                            Batal
                                        </a>
                                    </span>
                                    <span class="float-right">
                                        <button type="submit" class="btn btn-info" id="btntambah" disabled="true">
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
</section>
@stop

@section('adminlte_js')
<script>
    $(function() {
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();

        today = yyyy + '-' + mm + '-' + dd;
        $("#tanggal_pemesanan").attr("max", today);
        $("#batas_kontrak").attr("min", today);
        $("#tanggal_po").attr("max", today);
        $("#tanggal_do").attr("min", today);
        select_data();
        load_part();
        // $('#customer_id').on('keyup change', function() {
        //     if ($(this).val() != "") {
        //         $('#msgcustomer_id').text("");
        //         $('#customer_id').removeClass('is-invalid');
        //         // var value = getCustomer($(this).val());
        //         $('#alamat').val(value.alamat);
        //         $('#telepon').val(value.telepon);
        //     } else if ($(this).val() == "") {
        //         $('#msgcustomer_id').text("Silahkan Pilih Customer");
        //         $('#customer_id').addClass('is-invalid');
        //     }
        // });
        function reset_akn() {
            $("#tanggal_pemesanan").val("");
            $("#instansi").val("");
            $("#satuan_kerja").val("");
            $("#no_paket").val("");
            $('input[type="radio"][name="status"]').prop("checked", false);
            $("#batas_kontrak").val("");
            $("#deskripsi").val("");
            $('#btntambah').attr("disabled", true);
        }

        function reset_penjualan() {
            $("#no_po").val("");
            $("#tanggal_po").val("");
            $('input[type="radio"][name="do"]').prop("checked", false);
            $("#do_detail_no").addClass("hide");
            $("#do_detail_tgl").addClass("hide");
            $("#no_do").val("");
            $("#tanggal_do").val("");
            $('#btntambah').attr("disabled", true);
        }

        function checkvalidasi() {
            if ($('#tanggal_pemesanan').val() != "" && $("#instansi").val() !== "" && $("#alamatinstansi").val() !== "" && $(".provinsi").val() !== "" && $("#satuan_kerja").val() != "" && ($("#no_paket").val() != "" && check_no_paket($("#no_paket").val()) <= 0) && $("#status").val() != "" && $("#batas_kontrak").val() != "" && $("#deskripsi").val() != "") {
                $('#btntambah').removeAttr("disabled");
            } else {
                $('#btntambah').attr("disabled", true);
            }
        }

        function checkvalidasinonakn() {
            if ($('input[type="radio"][name="do"]:checked').val() == "yes") {
                if ($("#no_po").val() != "" && $("#tanggal_po").val() != "" && $("#no_do").val() != "" && $("#tanggal_do").val() != "") {
                    $('#btntambah').removeAttr("disabled");
                } else {
                    $('#btntambah').attr("disabled", true);
                }
            } else if ($('input[type="radio"][name="do"]:checked').val() == "no") {
                if ($("#no_po").val() != "" && $("#tanggal_po").val() != "") {
                    $('#btntambah').removeAttr("disabled");
                } else {
                    $('#btntambah').attr("disabled", true);
                }
            } else {
                $('#btntambah').attr("disabled", true);
            }
        }


        $('#tanggal_pemesanan').on('keyup change', function() {
            if ($(this).val() != "") {
                $("#batas_kontrak").attr("min", $(this).val());
                $("#msgtanggal_pemesanan").text("");
                $("#tanggal_pemesanan").removeClass('is-invalid');
                checkvalidasi();
            } else if ($(this).val() == "") {
                $("#msgtanggal_pemesanan").text("Isi Tanggal Pemesanan");
                $("#tanggal_pemesanan").addClass('is-invalid');
                $('#btntambah').attr("disabled", true);
            }
        });
        $('input[type="radio"][name="jenis_penjualan"]').on('change', function() {
            reset_akn();
            reset_penjualan();

            if ($(this).val() == "ekatalog") {

                $("#datapart").addClass("hide");
                $("#dataproduk").removeClass("hide");
                $("#nonakn").addClass("hide");
                $("#akn").removeClass("hide");
                $(".os-content-arrange").remove();
            } else if ($(this).val() == "spa") {
                $("#datapart").addClass("hide");
                $("#dataproduk").removeClass("hide");
                $("#nonakn").removeClass("hide");
                $("#akn").addClass("hide");
                $(".os-content-arrange").remove();
            } else if ($(this).val() == "spb") {
                // $("#datapart").addClass("hide");
                // $("#dataproduk").removeClass("hide");
                $("#datapart").removeClass("hide");
                $("#dataproduk").addClass("hide");
                $("#nonakn").removeClass("hide");
                $("#akn").addClass("hide");
                $(".os-content-arrange").remove();
            }
        });

        $('input[type="radio"][name="do"]').on('change', function() {
            $('#btntambah').attr("disabled", true);
            $("#no_do").val("");
            $("#tanggal_do").val("");
            if ($(this).val() == "yes") {
                $("#do_detail_no").removeClass("hide");
                $("#do_detail_tgl").removeClass("hide");
                checkvalidasinonakn();
            } else if ($(this).val() == "no") {
                $("#do_detail_no").addClass("hide");
                $("#do_detail_tgl").addClass("hide");
                checkvalidasinonakn();
            }
        });

        $('#batas_kontrak').on('keyup change', function() {
            if ($(this).val() != "") {
                $("#msgbatas_kontrak").text("");
                $("#batas_kontrak").removeClass('is-invalid');
                // if ($("#tanggal_pemesanan").val() != "" && $("#instansi").val() != "" && $("#satuan_kerja").val() != "" && $("#no_paket").val() != "" && $("#status").val() != "" && $("#deskripsi").val() != "") {
                //     $('#btntambah').removeAttr("disabled");
                // } else {
                //     $('#btntambah').attr("disabled", true);
                // }
                checkvalidasi();
            } else if ($(this).val() == "") {
                $("#msgbatas_kontrak").text("Tanggal Delivery Harus diisi");
                $("#batas_kontrak").addClass('is-invalid');
                $('#btntambah').attr("disabled", true);
            }
        });
        $('#instansi').on('keyup change', function() {
            if ($(this).val() != "") {
                $("#msginstansi").text("");
                $("#instansi").removeClass('is-invalid');
                // if ($("#tanggal_pemesanan").val() != "" && $("#satuan_kerja").val() != "" && $("#no_paket").val() != "" && $("#status").val() != "" && $("#batas_kontrak").val() != "" && $("#deskripsi").val() != "") {
                //     $('#btntambah').removeAttr("disabled");
                // } else {
                //     $('#btntambah').attr("disabled", true);
                // }
                checkvalidasi();
            } else if ($(this).val() == "") {
                $("#msginstansi").text("Instansi Harus diisi");
                $("#instansi").addClass('is-invalid');
                $('#btntambah').attr("disabled", true);
            }
        });

        $('#alamatinstansi').on('keyup change', function() {
            if ($(this).val() != "") {
                $("#msgalamatinstansi").text("");
                $("#alamatinstansi").removeClass('is-invalid');
                // if ($("#tanggal_pemesanan").val() != "" && $("#satuan_kerja").val() != "" && $("#no_paket").val() != "" && $("#status").val() != "" && $("#batas_kontrak").val() != "" && $("#deskripsi").val() != "") {
                //     $('#btntambah').removeAttr("disabled");
                // } else {
                //     $('#btntambah').attr("disabled", true);
                // }
                checkvalidasi();
            } else if ($(this).val() == "") {
                $("#msgalamatinstansi").text("Alamat Instansi Harus diisi");
                $("#alamatinstansi").addClass('is-invalid');
                $('#btntambah').attr("disabled", true);
            }
        });
        $('input[type="radio"][name="status"]').on('change', function() {
            if ($(this).val() != "") {
                if ($(this).val() == "draft") {
                    $("#produktable tbody").empty();
                    $('#produktable tbody').append(trproduktable());
                    numberRowsProduk($("#produktable"));
                    $("#totalhargaprd").text("Rp. 0");
                    $("#dataproduk").addClass("hide");
                } else {
                    $("#dataproduk").removeClass("hide");
                }

                checkvalidasi();
                // if ($("#tanggal_pemesanan").val() != "" && $("#satuan_kerja").val() != "" && $("#no_paket").val() != "" && $("#instansi").val() != "" && $("#batas_kontrak").val() != "" && $("#deskripsi").val() != "") {
                //     $('#btntambah').removeAttr("disabled");
                // } else {
                //     $('#btntambah').attr("disabled", true);
                // }
            } else {
                $("#msgstatus").text("Status Harus dipilih");
                $("#status").addClass('is-invalid');
                $('#btntambah').attr("disabled", true);
            }
        });

        $('#satuan_kerja').on('keyup', function() {
            if ($(this).val() != "") {
                $("#msgsatuan_kerja").text("");
                $("#satuan_kerja").removeClass('is-invalid');

                checkvalidasi();
                // if ($("#tanggal_pemesanan").val() != "" && $("#instansi").val() != "" && $("#no_paket").val() != "" && $("#status").val() != "" && $("#batas_kontrak").val() != "" && $("#deskripsi").val() != "") {
                //     $('#btntambah').removeAttr("disabled");
                // } else {
                //     $('#btntambah').attr("disabled", true);
                // }
            } else if ($(this).val() == "") {
                $("#msgsatuan_kerja").text("Satuan Kerja Harus diisi");
                $("#satuan_kerja").addClass('is-invalid');
                $('#btntambah').attr("disabled", true);
            }
        });

        $('#deskripsi').on('keyup', function() {
            if ($(this).val() != "") {
                $("#msgdeskripsi").text("");
                $("#deskripsi").removeClass('is-invalid');
                // if ($("#tanggal_pemesanan").val() != "" && $("#instansi").val() != "" && $("#satuan_kerja").val() != "" && $("#no_paket").val() != "" && $("#status").val() != "" && $("#batas_kontrak").val() != "") {
                //     $('#btntambah').removeAttr("disabled");
                // } else {
                //     $('#btntambah').attr("disabled", true);
                // }

                checkvalidasi();
            } else if ($(this).val() == "") {
                $("#msgdeskripsi").text("Deskripsi harus diisi");
                $("#deskripsi").addClass('is-invalid');
                $('#btntambah').attr("disabled", true);
            }
        });



        $('#no_paket').on('keyup change', function() {
            if ($(this).val() != "") {
                var values = $(this).val();
                console.log(check_no_paket(values));
                if (check_no_paket(values) > 0) {
                    $("#msgno_paket").text("No Paket tidak boleh sama");
                    $("#no_paket").addClass('is-invalid');
                    $('#btntambah').attr("disabled", true);
                } else {
                    $("#msgno_paket").text("");
                    $("#no_paket").removeClass('is-invalid');
                    checkvalidasi();
                }
                // $.ajax({
                //     type: 'GET',
                //     dataType: 'json',
                //     url: '/api/penjualan/check_no_paket/' + '0/' + values,
                //     success: function(data) {
                //         if (data.data > 0) {
                //             $("#msgno_paket").text("No Paket telah digunakan");
                //             $("#no_paket").addClass('is-invalid');
                //             $('#btntambah').attr("disabled", true);
                //         } else {
                //             $("#msgno_paket").text("");
                //             $("#no_paket").removeClass('is-invalid');
                //             checkvalidasi();
                //         }
                //     },
                //     error: function(data) {
                //         return values;
                //     }
                // });

                // if ($("#tanggal_pemesanan").val() != "" && $("#instansi").val() != "" && $("#satuan_kerja").val() != "" && $("#status").val() != "" && $("#batas_kontrak").val() != "" && $("#deskripsi").val() != "") {
                //     $('#btntambah').removeAttr("disabled");
                // } else {
                //     $('#btntambah').attr("disabled", true);
                // }


            } else if ($(this).val() == "") {
                $("#msgno_paket").text("No Paket harus diisi");
                $("#no_paket").addClass('is-invalid');
                $('#btntambah').attr("disabled", true);
            }
        });
        $('#no_po').on('keyup change', function() {
            if ($(this).val() != "") {
                $("#msgno_po").text("");
                $("#no_po").removeClass('is-invalid');
                checkvalidasinonakn();
                // if ($('input[type="radio"][name="do"]:checked').val() == "yes") {
                //     if ($("#tanggal_po").val() != "" && $("#no_do").val() != "" && $("#tanggal_do").val() != "") {
                //         $('#btntambah').removeAttr("disabled");
                //     } else {
                //         $('#btntambah').attr("disabled", true);
                //     }
                // } else {

                //     if ($("#tanggal_po").val() != "") {
                //         $('#btntambah').removeAttr("disabled");
                //     } else {

                //         $('#btntambah').attr("disabled", true);
                //     }
                // }
            } else if ($(this).val() == "") {
                $('#btntambah').attr("disabled", true);
                $("#msgno_po").text("Nomor PO Harus diisi");
                $("#no_po").addClass('is-invalid');
            }
        });

        $('#tanggal_po').on('keyup change', function() {
            if ($(this).val() != "") {
                $("#msgtanggal_po").text("");
                $("#tanggal_po").removeClass('is-invalid');
                $("#tanggal_do").attr("min", $(this).val());
                checkvalidasinonakn();
                // if ($('input[type="radio"][name="do"]:checked').val() == "yes") {
                //     if ($("#no_po").val() != "" && $("#no_do").val() != "" && $("#tanggal_do").val() != "") {
                //         $('#btntambah').removeAttr("disabled");
                //     } else {
                //         $('#btntambah').attr("disabled", true);
                //     }
                // } else {
                //     if ($("#no_po").val() != "") {

                //         $('#btntambah').removeAttr("disabled");
                //     } else {

                //         $('#btntambah').attr("disabled", true);
                //     }
                // }
            } else if ($(this).val() == "") {
                $('#btntambah').attr("disabled", true);
                $("#msgtanggal_po").text("Tanggal PO Harus diisi");
                $("#tanggal_po").addClass('is-invalid');
            }
        });

        $('#no_do').on('keyup change', function() {
            if ($(this).val() != "") {
                $("#msgno_do").text("");
                $("#no_do").removeClass('is-invalid');
                checkvalidasinonakn();
                // if ($("#tanggal_po").val() != "" && $("#tanggal_do").val() != "") {
                //     $('#btntambah').removeAttr("disabled");
                // } else {
                //     $('#btntambah').attr("disabled", true);
                // }

            } else if ($(this).val() == "") {
                $('#btntambah').attr("disabled", true);
                $("#msgno_do").text("Nomor Do Harus diisi");
                $("#no_do").addClass('is-invalid');
            }
        });

        $('#tanggal_do').on('keyup change', function() {
            if ($(this).val() != "") {
                $("#msgtanggal_do").text("");
                $("#tanggal_do").removeClass('is-invalid');
                checkvalidasinonakn();
                // if ($("#no_po").val() != "" && $("#no_do").val() != "") {
                //     $('#btntambah').removeAttr("disabled");
                // } else {
                //     $('#btntambah').attr("disabled", true);
                // }
            } else if ($(this).val() == "") {
                $('#btntambah').attr("disabled", true);
                $("#msgtanggal_do").text("Tanggal DO Harus diisi");
                $("#tanggal_do").addClass('is-invalid');
            }
        });

        $('.customer_id').select2({
            ajax: {
                minimumResultsForSearch: 20,
                placeholder: "Pilih Produk",
                dataType: 'json',
                theme: "bootstrap",
                delay: 250,
                type: 'GET',
                url: '/api/customer/select/',
                data: function(params) {
                    return {
                        term: params.term
                    }
                },
                processResults: function(data) {
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
                    $('#alamat').val(data[0].alamat);
                    $('#telepon').val(data[0].telp);
                }
            });
        });

        $('#produktable').on('keyup change', '.variasi', function() {
            var name = $(this).attr('name');
            var jumlah = $(this).closest('tr').find('.produk_jumlah').val();
            var ppid = $(this).closest('tr').find('.penjualan_produk_id').attr('id');
            val = $('select[name="' + name + '"]').val();
            id = $('select[name="' + name + '"]').attr('data-id');
            vals = $('select[name="' + name + '"]').select2('data')[0];
            var kebutuhan = jumlah * vals.jumlah;
            if (cek_stok(vals.id) < kebutuhan) {
                var jumlah_kekurangan = 0;
                if (cek_stok(vals.id) < 0) {
                    jumlah_kekurangan = kebutuhan;
                } else {
                    jumlah_kekurangan = Math.abs(cek_stok(vals.id) - kebutuhan);
                }
                $('select[name="variasi[' + ppid + '][' + id + ']"]').addClass('is-invalid');
                $('span[name="ketstok[' + ppid + '][' + id + ']"]').text('Jumlah Kurang ' + jumlah_kekurangan + ' dari Permintaan');
            } else if (cek_stok(vals.id) >= kebutuhan) {
                $('select[name="variasi[' + ppid + '][' + id + ']"]').removeClass('is-invalid');
                $('span[name="ketstok[' + ppid + '][' + id + ']"]').text('');
            }
        })

        function cek_stok(id) {
            var jumlah = 0;
            $.ajax({
                type: 'GET',
                dataType: 'json',
                async: false,
                url: '/api/produk/variasi_stok/' + id,
                success: function(data) {
                    jumlah = data;
                },
                error: function(data) {
                    jumlah = data;
                }
            });
            return jumlah;
        }

        function check_no_paket(values) {
            var hasil = "";
            $.ajax({
                type: 'GET',
                dataType: 'json',
                async: false,
                url: '/api/penjualan/check_no_paket/' + '0/' + values,
                success: function(data) {
                    hasil = data;
                },
                error: function(data) {
                    hasil = data;
                }
            });
            return hasil;
        }

        function formatmoney(bilangan) {
            var number_string = bilangan.toString(),
                sisa = number_string.length % 3,
                rupiah = number_string.substr(0, sisa),
                ribuan = number_string.substr(sisa).match(/\d{3}/g);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
            return rupiah;
        }

        function replaceAll(string, search, replace) {
            return string.split(search).join(replace);
        }

        function select_data() {
            // $('.penjualan_produk_id').on('change', function() {
            //     for (i = 0; i < 3; ++i) {
            //         $("#produktable ").append('<tr><td>Detail Paket</td></tr>');
            //     }
            // });

            $('.penjualan_produk_id').select2({
                placeholder: "Pilih Produk",
                ajax: {
                    minimumResultsForSearch: 20,
                    dataType: 'json',
                    theme: "bootstrap",
                    delay: 250,
                    type: 'GET',
                    url: '/api/penjualan_produk/select/',
                    data: function(params) {
                        return {
                            term: params.term
                        }
                    },
                    processResults: function(data) {

                        //console.log(data);
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
            });
            // .change(function() {
            //     var index = $(this).attr('id');
            //     var id = $(this).val();
            //     $.ajax({
            //         url: '/api/penjualan_produk/select/' + id,
            //         type: 'GET',
            //         dataType: 'json',
            //         success: function(res) {
            //             $('#produk_harga' + index).val(formatmoney(res[0].harga));
            //             var jumlah_pesan = $('#produk_jumlah' + index).val();
            //             if (jumlah_pesan == "") {
            //                 jumlah_pesan = 0;
            //             }
            //             console.log('subtotal' + formatmoney((res[0].harga) * jumlah_pesan));
            //             $('#produk_subtotal' + index).val(formatmoney((res[0].harga) * jumlah_pesan));
            //             var tes = $('#detail_produk' + index);
            //             tes.empty();
            //             var datas = "";
            //             tes.append(`<fieldset><legend><b>Detail Produk</b></legend>`);
            //             for (var x = 0; x < res[0].produk.length; x++) {
            //                 var data = [];
            //                 tes.append(`<div>`);
            //                 tes.append(`<div class="card-body blue-bg">
            //                             <h6>` + res[0].produk[x].nama + `</h6>
            //                             <select class="form-control variasi" name="variasi[` + index + `][` + x + `]" style="width:100%;" id="variasi` + index + `` + x + `" data-attr="variasi` + x + `" data-id="` + x + `"></select>
            //                             <span class="invalid-feedback d-block ketstok" name="ketstok[` + index + `][` + x + `]" id="ketstok` + index + `` + x + `" data-attr="ketstok` + x + `" data-id="` + x + `"></span>
            //                           </div>`);
            //                 if (res[0].produk[x].gudang_barang_jadi.length <= 1) {
            //                     data.push({
            //                         id: res[0].produk[x].gudang_barang_jadi[0].id,
            //                         text: res[0].produk[x].nama,
            //                         jumlah: res[0].produk[x].pivot.jumlah,
            //                         qt: cek_stok(res[0].produk[x].gudang_barang_jadi[0].id)
            //                     });
            //                 } else {
            //                     for (var y = 0; y < res[0].produk[x].gudang_barang_jadi.length; y++) {
            //                         data.push({
            //                             id: res[0].produk[x].gudang_barang_jadi[y].id,
            //                             text: res[0].produk[x].gudang_barang_jadi[y].nama,
            //                             jumlah: res[0].produk[x].pivot.jumlah,
            //                             qt: cek_stok(res[0].produk[x].gudang_barang_jadi[y].id)
            //                         });
            //                     }
            //                 }
            //                 $(`select[name="variasi[` + index + `][` + x + `]"]`).select2({
            //                     placeholder: 'Pilih Variasi',
            //                     data: data,
            //                     templateResult: function(data) {
            //                         var $span = $(`<div><span class="col-form-label">` + data.text + `</span><span class="badge blue-text float-right col-form-label stok" data-id="` + data.qt + `">` + data.qt + `</span></div>`);
            //                         return $span;
            //                     },
            //                     templateSelection: function(data) {
            //                         var $span = $(`<div><span class="col-form-label">` + data.text + `</span><span class="badge blue-text float-right col-form-label stok" data-id="` + data.qt + `">` + data.qt + `</span></div>`);
            //                         return $span;
            //                     }
            //                 });

            //                 $(`select[name="variasi[` + index + `][` + x + `]"]`).trigger("change");
            //                 tes.append(`</div>`)
            //             }
            //             tes.append(`</fieldset>`);
            //             // tes.html(datas);
            //         }
            //     });
            // });
        }

        function load_part() {
            $('.part_id').select2({
                placeholder: "Pilih Part",
                ajax: {
                    minimumResultsForSearch: 20,
                    dataType: 'json',
                    theme: "bootstrap",
                    delay: 250,
                    type: 'POST',
                    url: '/api/gk/sel-m-spare',
                    data: function(params) {
                        return {
                            term: params.term
                        }
                    },
                    processResults: function(data) {

                        //console.log(data);
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
            });
        }

        function totalhargaprd() {
            var totalharga = 0;
            $('#produktable').find('tr .produk_subtotal').each(function() {
                var subtotal = replaceAll($(this).val(), '.', '');
                // console.log(subtotal);
                totalharga = parseInt(totalharga) + parseInt(subtotal);
                $("#totalhargaprd").text("Rp. " + totalharga.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1."));
            })
        }

        function totalhargapart() {
            var totalharga = 0;
            $('#parttable').find('tr .part_subtotal').each(function() {
                var subtotal = replaceAll($(this).val(), '.', '');
                totalharga = parseInt(totalharga) + parseInt(subtotal);
                $("#totalhargapart").text("Rp. " + totalharga.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1."));
            })
        }

        $("#produktable").on('keyup change', '.penjualan_produk_id', function() {
            // var jumlah = $(this).closest('tr').find('.produk_jumlah').val();
            // var harga = $(this).closest('tr').find('.produk_harga').val();
            // var subtotal = $(this).closest('tr').find('.produk_subtotal');
            // if (jumlah != "" && harga != "") {
            //     var hargacvrt = replaceAll(harga, '.', '');
            //     subtotal.val(formatmoney(jumlah * parseInt(hargacvrt)));
            //     totalhargaprd();
            // } else {
            //     subtotal.val(formatmoney("0"));
            //     totalhargaprd();
            // }



            var index = $(this).attr('id');
            var id = $(this).val();
            $.ajax({
                url: '/api/penjualan_produk/select/' + id,
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    $('#produk_harga' + index).val(formatmoney(res[0].harga));
                    var jumlah_pesan = $('#produk_jumlah' + index).val();
                    if (jumlah_pesan == "") {
                        jumlah_pesan = 0;
                    }
                    console.log('subtotal' + formatmoney((res[0].harga) * jumlah_pesan));
                    $('#produk_subtotal' + index).val(formatmoney((res[0].harga) * jumlah_pesan));
                    var tes = $('#detail_produk' + index);
                    tes.empty();
                    var datas = "";
                    tes.append(`<fieldset><legend><b>Detail Produk</b></legend>`);
                    for (var x = 0; x < res[0].produk.length; x++) {
                        var data = [];
                        tes.append(`<div>`);
                        tes.append(`<div class="card-body blue-bg">
                                        <h6>` + res[0].produk[x].nama + `</h6>
                                        <select class="form-control variasi" name="variasi[` + index + `][` + x + `]" style="width:100%;" id="variasi` + index + `` + x + `" data-attr="variasi` + x + `" data-id="` + x + `"></select>
                                        <span class="invalid-feedback d-block ketstok" name="ketstok[` + index + `][` + x + `]" id="ketstok` + index + `` + x + `" data-attr="ketstok` + x + `" data-id="` + x + `"></span>
                                      </div>`);
                        if (res[0].produk[x].gudang_barang_jadi.length <= 1) {
                            data.push({
                                id: res[0].produk[x].gudang_barang_jadi[0].id,
                                text: res[0].produk[x].nama,
                                jumlah: res[0].produk[x].pivot.jumlah,
                                qt: cek_stok(res[0].produk[x].gudang_barang_jadi[0].id)
                            });
                        } else {
                            for (var y = 0; y < res[0].produk[x].gudang_barang_jadi.length; y++) {
                                data.push({
                                    id: res[0].produk[x].gudang_barang_jadi[y].id,
                                    text: res[0].produk[x].gudang_barang_jadi[y].nama,
                                    jumlah: res[0].produk[x].pivot.jumlah,
                                    qt: cek_stok(res[0].produk[x].gudang_barang_jadi[y].id)
                                });
                            }
                        }
                        $(`select[name="variasi[` + index + `][` + x + `]"]`).select2({
                            placeholder: 'Pilih Variasi',
                            data: data,
                            templateResult: function(data) {
                                var $span = $(`<div><span class="col-form-label">` + data.text + `</span><span class="badge blue-text float-right col-form-label stok" data-id="` + data.qt + `">` + data.qt + `</span></div>`);
                                return $span;
                            },
                            templateSelection: function(data) {
                                var $span = $(`<div><span class="col-form-label">` + data.text + `</span><span class="badge blue-text float-right col-form-label stok" data-id="` + data.qt + `">` + data.qt + `</span></div>`);
                                return $span;
                            }
                        });

                        $(`select[name="variasi[` + index + `][` + x + `]"]`).trigger("change");
                        tes.append(`</div>`)
                    }
                    tes.append(`</fieldset>`);
                    // tes.html(datas);
                }
            });

        });
        $("#produktable").on('keyup change', '.produk_jumlah', function() {
            var jumlah = $(this).closest('tr').find('.produk_jumlah').val();
            var harga = $(this).closest('tr').find('.produk_harga').val();
            var subtotal = $(this).closest('tr').find('.produk_subtotal');
            var ketstok = $(this).closest('tr').find('.ketstok');
            var variasi = $(this).closest('tr').find('.variasi');
            var ppid = $(this).closest('tr').find('.penjualan_produk_id').attr('id');
            if (jumlah != "" && harga != "") {
                var hargacvrt = replaceAll(harga, '.', '');
                subtotal.val(formatmoney(jumlah * parseInt(hargacvrt)));
                totalhargaprd();
                for (var i = 0; i < variasi.length; i++) {
                    var variasires = $('select[name="variasi[' + ppid + '][' + i + ']"]').select2('data')[0];
                    var kebutuhan = jumlah * variasires.jumlah;

                    if (cek_stok(variasires.id) < kebutuhan) {
                        var jumlah_kekurangan = 0;
                        if (cek_stok(variasires.id) < 0) {
                            jumlah_kekurangan = kebutuhan;
                        } else {
                            jumlah_kekurangan = Math.abs(cek_stok(variasires.id) - kebutuhan);
                        }
                        $('select[name="variasi[' + ppid + '][' + i + ']"]').addClass('is-invalid');
                        $('span[name="ketstok[' + ppid + '][' + i + ']"]').text('Jumlah Kurang ' + jumlah_kekurangan + ' dari Permintaan');
                    } else if (cek_stok(variasires.id) >= kebutuhan) {
                        $('select[name="variasi[' + ppid + '][' + i + ']"]').removeClass('is-invalid');
                        $('span[name="ketstok[' + ppid + '][' + i + ']"]').text('');
                    }
                }
            } else {
                subtotal.val(formatmoney("0"));
                totalhargaprd();
                variasi.removeClass('is-invalid');
                ketstok.text('');
            }
        });
        $("#produktable").on('keyup change', '.produk_harga', function() {
            var result = $(this).val().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            $(this).val(result);
            var jumlah = $(this).closest('tr').find('.produk_jumlah').val();
            var harga = $(this).closest('tr').find('.produk_harga').val();
            var subtotal = $(this).closest('tr').find('.produk_subtotal');
            if (jumlah != "" && harga != "") {
                var hargacvrt = replaceAll(harga, '.', '');
                subtotal.val(formatmoney(jumlah * parseInt(hargacvrt)));
                totalhargaprd();
            } else {
                subtotal.val(formatmoney("0"));
                totalhargaprd();
            }
        });

        //PRODUK TABLE
        function numberRowsProduk($t) {
            var c = 0 - 2;
            $t.find("tr").each(function(ind, el) {
                $(el).find("td:eq(0)").html(++c);
                var j = c - 1;
                $(el).find('.penjualan_produk_id').attr('name', 'penjualan_produk_id[' + j + ']');
                $(el).find('.penjualan_produk_id').attr('id', j);
                var variasi = $(el).find('.variasi');
                for (var k = 0; k < variasi.length; k++) {
                    $(el).find('select[data-attr="variasi' + k + '"]').attr('name', 'variasi[' + j + '][' + k + ']');
                    $(el).find('select[data-attr="variasi' + k + '"]').attr('id', 'variasi' + j + '' + k);
                    $(el).find('span[data-attr="ketstok' + k + '"]').attr('name', 'ketstok[' + j + '][' + k + ']');
                    $(el).find('span[data-attr="ketstok' + k + '"]').attr('id', 'ketstok' + j + '' + k);
                }
                $(el).find('.detail_produk').attr('id', 'detail_produk' + j);
                $(el).find('.produk_harga').attr('id', 'produk_harga' + j);
                $(el).find('.produk_harga').attr('name', 'produk_harga[' + j + ']');
                $(el).find('.produk_jumlah').attr('id', 'produk_jumlah' + j);
                $(el).find('.produk_jumlah').attr('name', 'produk_jumlah[' + j + ']');
                $(el).find('.produk_subtotal').attr('id', 'produk_subtotal' + j);
                $(el).find('.produk_subtotal').attr('name', 'produk_subtotal[' + j + ']');
                $(el).find('.detail_jual').attr('id', 'detail_jual' + j);
                select_data();
            });
        }

        function trproduktable() {
            var data = `<tr>
                <td></td>
                <td>
                    <div class="form-group">
                        <select name="penjualan_produk_id[]" id="0" class="select2 form-control custom-select penjualan_produk_id @error('penjualan_produk_id') is-invalid @enderror" style="width:100%;">
                            <option value=""></option>
                        </select>
                        <div class="detailjual" id="tes0">
                        </div>
                    </div>
                    <div id="detail_produk" class="detail_produk"></div>
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
                        <div class="input-group-prepend">
                            <span class="input-group-text">Rp</span>
                        </div>
                        <input type="text" class="form-control produk_harga" name="produk_harga[]" id="produk_harga0" placeholder="Masukkan Harga" style="width:100%;"/>
                    </div>
                </td>
                <td>
                    <div class="form-group d-flex justify-content-center">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Rp</span>
                        </div>
                        <input type="text" class="form-control produk_subtotal" name="produk_subtotal[]" id="produk_subtotal0" placeholder="Masukkan Subtotal" style="width:100%;" readonly/>
                    </div>
                </td>
                <td>
                    <a id="removerowproduk"><i class="fas fa-minus" style="color: red;"></i></a>
                </td>
            </tr>`;
            return data;
        }

        $('#addrowproduk').on('click', function() {
            if ($('#produktable > tbody > tr').length <= 0) {
                $('#produktable tbody').append(trproduktable());
                numberRowsProduk($("#produktable"));
            } else {
                $('#produktable tbody tr:last').after(trproduktable());
                numberRowsProduk($("#produktable"));
            }
        });

        $('#produktable').on('click', '#removerowproduk', function(e) {
            $(this).closest('tr').remove();
            numberRowsProduk($("#produktable"));
            totalhargaprd();
            if ($('#produktable > tbody > tr').length <= 0) {
                $("#totalhargaprd").text("0");
            }
        });

        function numberRowsPart($t) {
            var c = 0 - 2;
            $t.find("tr").each(function(ind, el) {
                $(el).find("td:eq(0)").html(++c);
                var j = c - 1;
                $(el).find('.part_id').attr('name', 'part_id[' + j + ']');
                $(el).find('.part_id').attr('id', 'part_id' + j);
                $(el).find('.part_jumlah').attr('name', 'part_jumlah[' + j + ']');
                $(el).find('.part_jumlah').attr('id', 'part_jumlah' + j);
                $(el).find('.part_harga').attr('name', 'part_harga[' + j + ']');
                $(el).find('.part_harga').attr('id', 'part_harga' + j);
                $(el).find('.part_subtotal').attr('name', 'part_subtotal[' + j + ']');
                $(el).find('.part_subtotal').attr('id', 'part_subtotal' + j);
                load_part();
            });
        }

        $('#addrowpart').on('click', function() {
            $('#parttable tbody tr:last').after(`
            <tr>
                <td>1</td>
                <td>
                    <div class="form-group">
                        <select class="select2 form-control select-info custom-select part_id" name="part_id[]" id="part_id0" width="100%">
                        </select>
                    </div>
                </td>
                <td>
                    <div class="form-group d-flex justify-content-center">
                        <div class="input-group">
                            <input type="number" class="form-control part_jumlah" aria-label="produk_satuan" name="part_jumlah[]" id="part_jumlah0" style="width:100%;">
                            <div class="input-group-append">
                                <span class="input-group-text" id="part_satuan">pcs</span>
                            </div>
                        </div>
                        <small id="part_ketersediaan"></small>
                    </div>
                </td>
                <td>
                    <div class="form-group d-flex justify-content-center">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Rp</span>
                        </div>
                        <input type="text" class="form-control part_harga" name="part_harga[]" id="part_harga0" placeholder="Masukkan Harga" style="width:100%;" />
                    </div>
                </td>
                <td>
                    <div class="form-group d-flex justify-content-center">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Rp</span>
                        </div>
                        <input type="text" class="form-control part_subtotal" name="part_subtotal[]" id="part_subtotal0" placeholder="Masukkan Subtotal" style="width:100%;" readonly />
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
                var hargacvrt = replaceAll(harga, '.', '');
                subtotal.val(formatmoney(jumlah * parseInt(hargacvrt)));
                totalhargapart();
            } else {
                subtotal.val(formatmoney("0"));
                totalhargapart();
            }
        });

        $("#parttable").on('keyup change', '.part_harga', function() {
            var result = $(this).val().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            $(this).val(result);
            var jumlah = $(this).closest('tr').find('.part_jumlah').val();
            var harga = $(this).closest('tr').find('.part_harga').val();
            var subtotal = $(this).closest('tr').find('.part_subtotal');

            if (jumlah != "" && harga != "") {
                var hargacvrt = replaceAll(harga, '.', '');
                subtotal.val(formatmoney(jumlah * parseInt(hargacvrt)));
                totalhargapart();
            } else {
                subtotal.val(formatmoney("0"));
                totalhargapart();
            }
        });

        $('#parttable').on('click', '#removerowpart', function(e) {
            $(this).closest('tr').remove();
            numberRowsPart($("#parttable"));
            totalhargapart();
        });

        $('.provinsi').select2({
            ajax: {
                minimumResultsForSearch: 20,
                placeholder: "Pilih Produk",
                dataType: 'json',
                theme: "bootstrap",
                delay: 250,
                type: 'GET',
                url: '/api/provinsi/select',
                data: function(params) {
                    return {
                        term: params.term
                    }
                },
                processResults: function(data) {
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
            if ($(this).val() != "") {
                $("#msgprovinsi").text("");
                $("#provinsi").removeClass('is-invalid');
                // if ($("#tanggal_pemesanan").val() != "" && $("#instansi").val() != "" && $("#satuan_kerja").val() != "" && $("#no_paket").val() != "" && $("#status").val() != "" && $("#batas_kontrak").val() != "") {
                //     $('#btntambah').removeAttr("disabled");
                // } else {
                //     $('#btntambah').attr("disabled", true);
                // }

                checkvalidasi();
            } else if ($(this).val() == "") {
                $("#msgprovinsi").text("Provinsi harus diisi");
                $("#provinsi").addClass('is-invalid');
                $('#btntambah').attr("disabled", true);
            }
        });
    });
</script>
@stop