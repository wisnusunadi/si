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

    .margin {
        margin: 5px;
    }

    .margin-top {
        margin-top: 5px;
    }

    .align-center {
        text-align: center;
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

    @media screen and (min-width: 1440px) {

        section {
            font-size: 14px;
        }

        .btn {
            font-size: 14px;
        }
    }

    @media screen and (max-width: 1439px) {

        label,
        .row {
            font-size: 12px;
        }

        h4 {
            font-size: 20px;
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
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card" id="ekatalog">
                    <div class="card-body">
                        <h4 class="margin">Data Penjualan </h4>
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-6">
                                        <div>
                                            <div class="margin">
                                                <small class="text-muted">Info Instansi</small>
                                            </div>
                                            <div id="instansi" class="margin"><b>{{$e->instansi}}</b></div>
                                            <div id="satuan_kerja" class="margin"><b>{{$e->satuan}}</b></div>
                                            <div id="alamat" class="margin"><b>{{$e->Provinsi->nama}}</b></div>
                                        </div>
                                        <div class="margin-top">
                                            <div class="margin">
                                                <small class="text-muted">Info Customer</small>
                                            </div>
                                            <div id="nama_distributor" class="margin"><b>{{$e->customer->nama}}</b></div>
                                            <div id="alamat" class="margin"><b>{{$e->customer->alamat}}</b></div>
                                            <div id="alamat" class="margin"><b>{{$e->Customer->Provinsi->nama}}</b></div>
                                            <div id="alamat" class="margin"><b>{{$e->Customer->telp}}</b></div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="margin">
                                            <div><small class="text-muted">No SO</small></div>
                                            <div>@if($e->Pesanan)
                                                @if(!empty($e->Pesanan->so))
                                                <b>{{$e->Pesanan->so}}</b>
                                                @else
                                                <b>-</b>
                                                @endif
                                                @endif
                                            </div>
                                        </div>
                                        <div class="margin">
                                            <div><small class="text-muted">No AKN</small></div>
                                            <div><b>{{$e->no_paket}}</b></div>
                                        </div>
                                        <div class="margin">
                                            <div><small class="text-muted">Tanggal Order</small>
                                            </div>
                                            <div><b>{{date('d-m-Y', strtotime($e->tgl_buat))}}</b></div>
                                        </div>
                                        <div class="margin">
                                            <div><small class="text-muted">Tanggal Batas Kontrak</small>
                                            </div>
                                            <div><b>{{date('d-m-Y', strtotime($e->tgl_kontrak))}}</b></div>
                                        </div>
                                        <div class="margin">
                                            <div><small class="text-muted">Status</small></div>
                                            @if($e->status == 'batal')
                                            <div class="badge red-text">Batal</div>
                                            @elseif($e->status == 'negosiasi')
                                            <div class="badge yellow-text">Negosiasi</div>
                                            @elseif($e->status == 'sepakat')
                                            <div class="margin-top"><b><span class="green-text">Sepakat</span></b></div>
                                            @elseif($e->status == 'negosiasi')
                                            <div class="badge blue-text">Draft</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="margin">
                                            <div><small class="text-muted">No PO</small></div>
                                            <div>@if($e->Pesanan)
                                                @if(!empty($e->Pesanan->no_po))
                                                <b>{{$e->Pesanan->no_po}}</b>
                                                @else
                                                <b>-</b>
                                                @endif
                                                @endif
                                            </div>
                                        </div>
                                        <div class="margin">
                                            <div><small class="text-muted">Tanggal PO</small>
                                            </div>
                                            <div><b>
                                                    @if($e->Pesanan)
                                                    @if(!empty($e->Pesanan->tgl_po))
                                                    {{date('d-m-Y', strtotime($e->Pesanan->tgl_po))}}
                                                    @else
                                                    -
                                                    @endif
                                                    @endif</b></div>
                                        </div>
                                        <div class="margin">
                                            <div><small class="text-muted">No DO</small></div>
                                            <div><b>@if($e->Pesanan)
                                                    @if(!empty($e->Pesanan->no_do))
                                                    {{$e->Pesanan->no_do}}
                                                    @else
                                                    -
                                                    @endif
                                                    @endif</b></div>
                                        </div>
                                        <div class="margin">
                                            <div><small class="text-muted">Tanggal DO</small>
                                            </div>
                                            <div><b>@if($e->Pesanan)
                                                    @if(!empty($e->Pesanan->tgl_do))
                                                    {{date('d-m-Y', strtotime($e->Pesanan->tgl_do))}}
                                                    @else
                                                    -
                                                    @endif
                                                    @endif</b></div>
                                        </div>
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
                            <form method="post" action="{{route('penjualan.penjualan.update_ekatalog', ['id' => $e->id])}}">
                                {{csrf_field()}}
                                {{method_field('PUT')}}
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
                                                                <option value="{{$e->customer_id}}" selected>{{$e->customer->nama}}</option>
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
                                                            <input type="text" class="form-control col-form-label" name="alamat" id="alamat_customer" readonly value="{{$e->customer->alamat}}" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="" class="col-form-label col-5" style="text-align: right">Telepon</label>
                                                        <div class="col-5">
                                                            <input type="text" class="form-control col-form-label" name="telepon" id="telepon_customer" readonly value="{{$e->customer->telp}}" />
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row d-flex justify-content-center" id="akn">
                                    <div class="col-10">
                                        <h4>Info AKN</h4>
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="form-horizontal">
                                                    <div class="form-group row">
                                                        <label for="" class="col-form-label col-5" style="text-align: right">Status</label>

                                                        <div class="col-5 col-form-label">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="status_akn" id="status_akn4" value="draft" />
                                                                <label class="form-check-label" for="status_akn4">Draft</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="status_akn" id="status_akn1" value="sepakat" />
                                                                <label class="form-check-label" for="status_akn1">Sepakat</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="status_akn" id="status_akn2" value="negosiasi" />
                                                                <label class="form-check-label" for="status_akn2">Negosiasi</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="status_akn" id="status_akn3" value="batal" />
                                                                <label class="form-check-label" for="status_akn3">Batal</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="" class="col-form-label col-5" style="text-align: right">Instansi</label>
                                                        <div class="col-7">
                                                            <input type="text" class="form-control col-form-label @error('instansi') is-invalid @enderror" name="instansi" id="instansi" value="{{$e->instansi}}" />
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
                                                            <input type="text" class="form-control col-form-label @error('alamatinstansi') is-invalid @enderror" name="alamatinstansi" id="alamatinstansi" value="{{$e->alamat}}" />
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
                                                                <option value="{{$e->provinsi_id}}" selected>{{$e->provinsi->nama}}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="" class="col-form-label col-5" style="text-align: right">Satuan Kerja</label>
                                                        <div class="col-7">
                                                            <input type="text" class="form-control col-form-label @error('satuan_kerja') is-invalid @enderror" name="satuan_kerja" id="satuan_kerja" value="{{$e->satuan}}" />
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
                                                            <textarea class="form-control col-form-label @error('deskripsi') is-invalid @enderror" name="deskripsi" id="deskripsi">{{$e->deskripsi}}</textarea>
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
                                                            <textarea class="form-control col-form-label" name="keterangan">{{$e->ket}}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row d-flex justify-content-center" id="dataproduk">
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

                                                                    <?php $produkpenjualan = 0; ?>
                                                                    @if(isset($e->pesanan))
                                                                    @if(isset($e->pesanan->detailpesanan))
                                                                    @foreach($e->pesanan->detailpesanan as $f)
                                                                    <tr>
                                                                        <td>{{$loop->iteration}}</td>
                                                                        <td>
                                                                            <div class="form-group">
                                                                                <select name="penjualan_produk_id[]" id="{{$loop->iteration-1}}" class="select2 form-control custom-select penjualan_produk_id @error('penjualan_produk_id') is-invalid @enderror" style="width:100%;">
                                                                                    <option value="{{$f->penjualan_produk_id}}" selected>{{$f->penjualanproduk->nama}}</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="detail_produk" id="detail_produk{{ $loop->iteration - 1 }}">
                                                                                <fieldset>
                                                                                    <legend><b>Detail Produk</b></legend>
                                                                                    <?php $variasi = 0; ?>
                                                                                    @foreach($f->DetailPesananProduk as $g)
                                                                                    <div>
                                                                                        <div class="card-body blue-bg">
                                                                                            <h6>{{$g->GudangBarangJadi->Produk->nama}}</h6>
                                                                                            <select class="form-control variasi" name="variasi[{{$produkpenjualan}}][{{$variasi}}]" id="variasi{{$produkpenjualan}}{{$variasi}}" style="width:100%;" data-attr="{{$produkpenjualan}}{{$variasi}}" data-id="{{$variasi}}">
                                                                                                <option value="{{$g->GudangBarangJadi->id}}">

                                                                                                    @if(!empty($g->GudangBarangJadi->nama))
                                                                                                    {{$g->GudangBarangJadi->Produk->nama}} {{$g->GudangBarangJadi->nama}}
                                                                                                    @else
                                                                                                    {{$g->GudangBarangJadi->Produk->nama}}
                                                                                                    @endif
                                                                                                </option>
                                                                                            </select>
                                                                                            <span class=" invalid-feedback d-block ketstok" name="ketstok[{{$produkpenjualan}}][{{$variasi}}]" id="ketstok{{$produkpenjualan}}{{$variasi}}" data-attr="{{$produkpenjualan}}{{$variasi}}" data-id="{{$variasi}}"></span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <?php $variasi = $variasi + 1; ?>
                                                                                    @endforeach
                                                                                </fieldset>
                                                                            </div>
                                                                            <div class="detailjual" id="tes0">
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-group d-flex justify-content-center">
                                                                                <div class="input-group">
                                                                                    <input type="number" class="form-control produk_jumlah" aria-label="produk_satuan" name="produk_jumlah[{{$produkpenjualan}}]" id="produk_jumlah{{$produkpenjualan}}" style="width:100%;" value="{{$f->jumlah}}">
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
                                                                                    <span class="input-group-text" id="prdhrg">Rp</span>
                                                                                </div>
                                                                                <input type="text" class="form-control produk_harga" name="produk_harga[{{$produkpenjualan}}]" id="produk_harga{{$produkpenjualan}}" placeholder="Masukkan Harga" style="width:100%;" aria-describedby="prdhrg" value="{{number_format($f->harga,0,',','.')}}" />
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-group d-flex justify-content-center">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text" id="prdsub">Rp</span>
                                                                                </div>
                                                                                <input type="text" class="form-control produk_subtotal" name=" produk_subtotal[{{$produkpenjualan}}]" id=" produk_subtotal{{$produkpenjualan}}" placeholder="Masukkan Subtotal" style="width:100%;" value="{{number_format($f->harga*$f->jumlah,0,',','.')}}" aria-describedby="prdsub" readonly />
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <a id="removerowproduk"><i class="fas fa-minus" style="color: red"></i></a>
                                                                        </td>
                                                                    </tr>
                                                                    <?php $produkpenjualan = $produkpenjualan + 1; ?>
                                                                    @endforeach
                                                                    @else
                                                                    <tr>
                                                                        <td>1</td>
                                                                        <td>
                                                                            <div class="form-group">
                                                                                <select name="penjualan_produk_id[0]" id="0" class="select2 form-control custom-select penjualan_produk_id @error('penjualan_produk_id') is-invalid @enderror" style="width:100%;">
                                                                                </select>
                                                                            </div>
                                                                            <div class="detail_produk" id="detail_produk0">
                                                                            </div>
                                                                            <div class="detailjual" id="tes0">
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
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text" id="prdhrg">Rp</span>
                                                                                </div>
                                                                                <input type="text" class="form-control produk_harga" name="produk_harga[0]" id="produk_harga0" placeholder="Masukkan Harga" style="width:100%;" aria-describedby="prdhrg" value="" />
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-group d-flex justify-content-center">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text" id="prdsub">Rp</span>
                                                                                </div>
                                                                                <input type="text" class="form-control produk_subtotal" name=" produk_subtotal[0]" id=" produk_subtotal0" placeholder="Masukkan Subtotal" style="width:100%;" value="" aria-describedby="prdsub" readonly />
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <a id="removerowproduk"><i class="fas fa-minus" style="color: red"></i></a>
                                                                        </td>
                                                                    </tr>
                                                                    @endif
                                                                    @else
                                                                    <tr>
                                                                        <td>1</td>
                                                                        <td>
                                                                            <div class="form-group">
                                                                                <select name="penjualan_produk_id[0]" id="0" class="select2 form-control custom-select penjualan_produk_id @error('penjualan_produk_id') is-invalid @enderror" style="width:100%;">
                                                                                </select>
                                                                            </div>
                                                                            <div class="detail_produk" id="detail_produk0">
                                                                            </div>
                                                                            <div class="detailjual" id="tes0">
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-group d-flex justify-content-center">
                                                                                <div class="input-group">
                                                                                    <input type="number" class="form-control produk_jumlah" aria-label="produk_satuan" name="produk_jumlah[0]" id="produk_jumlah0" style="width:100%;" value="">
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
                                                                                    <span class="input-group-text" id="prdhrg">Rp</span>
                                                                                </div>
                                                                                <input type="text" class="form-control produk_harga" name="produk_harga[0]" id="produk_harga0" placeholder="Masukkan Harga" style="width:100%;" aria-describedby="prdhrg" value="" />
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-group d-flex justify-content-center">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text" id="prdsub">Rp</span>
                                                                                </div>
                                                                                <input type="text" class="form-control produk_subtotal" name=" produk_subtotal[0]" id="produk_subtotal0" placeholder="Masukkan Subtotal" style="width:100%;" value="" aria-describedby="prdsub" readonly />
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <a id="removerowproduk"><i class="fas fa-minus" style="color: red"></i></a>
                                                                        </td>
                                                                    </tr>
                                                                    @endif
                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <th colspan="4" style="text-align:right;">Total Harga</th>
                                                                        <th id="totalhargaprd" class="align-right">Rp.
                                                                            @if(isset($e->pesanan))
                                                                            @if(isset($e->pesanan->detailpesanan))
                                                                            <?php $x = 0;
                                                                            foreach ($e->pesanan->detailpesanan as $f) {
                                                                                $x += $f->harga * $f->jumlah;
                                                                            }
                                                                            ?>
                                                                            {{number_format($x,0,',','.')}}
                                                                            @endif
                                                                            @endif
                                                                        </th>
                                                                    </tr>
                                                                </tfoot>


                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
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

    </div>
    </div>

    </div>
</section>
@stop

@section('adminlte_js')
<script>
    $(function() {


        loop();

        function loop() {
            for (i = 0; i < 20; i++) {
                select_data(i);
            }
        }


        $('input[name="status_akn"][value={{$e->status}}]').attr('checked', 'checked');
        $('#customer_id').on('keyup change', function() {
            if ($(this).val() != "") {
                $('#msgcustomer_id').text("");
                $('#customer_id').removeClass('is-invalid');
            } else if ($(this).val() == "") {
                $('#msgcustomer_id').text("Silahkan Pilih Customer");
                $('#customer_id').addClass('is-invalid');
            }
        });

        $('input[type="radio"][name="status_akn"]').on('change', function() {
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
            } else {
                $("#msgstatus").text("Status Harus dipilih");
                $("#status").addClass('is-invalid');
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

        $('input[type="radio"][name="do_akn"]').on('change', function() {
            if ($(this).val() == "yes") {
                $("#do_detail_no_akn").removeClass("hide");
                $("#do_detail_tgl_akn").removeClass("hide");
            } else if ($(this).val() == "no") {
                $("#do_detail_no_akn").addClass("hide");
                $("#do_detail_tgl_akn").addClass("hide");
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


        function totalhargaprd() {
            var totalharga = 0;
            $('#produktable').find('tr .produk_subtotal').each(function() {
                var subtotal = replaceAll($(this).val(), '.', '');
                totalharga = parseInt(totalharga) + parseInt(subtotal);
                console.log("subtotal " + subtotal);
                console.log("total harga " + totalharga)
                $("#totalhargaprd").text("Rp. " + totalharga.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1."));
            })
        }

        function numberRowsProduk($t) {
            var c = 0 - 2;
            $t.find("tr").each(function(ind, el) {
                $(el).find("td:eq(0)").html(++c);
                var j = c - 1;
                $(el).find('.penjualan_produk_id').attr('name', 'penjualan_produk_id[' + j + ']');
                $(el).find('.penjualan_produk_id').attr('id', j);
                var variasi = $(el).find('.variasi');
                for (var k = 0; k < variasi.length; k++) {
                    $(el).find('.variasi').attr('name', 'variasi[' + j + '][' + k + ']');
                    $(el).find('.variasi').attr('id', 'variasi' + j + '' + k);
                    $(el).find('.ketstok').attr('name', 'ketstok[' + j + '][' + k + ']');
                    $(el).find('.ketstok').attr('id', 'ketstok' + j + '' + k);
                }
                $(el).find('.detail_produk').attr('id', 'detail_produk' + j);
                $(el).find('.produk_harga').attr('id', 'produk_harga' + j);
                $(el).find('input[id="produk_jumlah"]').attr('name', 'produk_jumlah[' + j + ']');
                $(el).find('.detail_jual').attr('id', 'detail_jual' + j);
                select_data($(el).find('.penjualan_produk_id').attr('id'));
            });
        }

        $("#produktable").on('keyup change', '.penjualan_produk_id', function() {
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
                    if (variasires.qt < kebutuhan) {
                        $('select[name="variasi[' + ppid + '][' + i + ']"]').addClass('is-invalid');
                        $('span[name="ketstok[' + ppid + '][' + i + ']"]').text('Jumlah Kurang dari Permintaan');
                    } else if (variasires.qt >= kebutuhan) {
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

        $('#produktable').on('keyup change', '.variasi', function() {
            $(this).val();
            var name = $(this).attr('name');
            var jumlah = $(this).closest('tr').find('.produk_jumlah').val();
            var ppid = $(this).closest('tr').find('.penjualan_produk_id').attr('id');
            val = $('select[name="' + name + '"]').val();
            id = $('select[name="' + name + '"]').attr('data-id');
            vals = $('select[name="' + name + '"]').select2('data')[0];
            var kebutuhan = jumlah * vals.jumlah;
            if (vals.qt < kebutuhan) {
                $('select[name="variasi[' + ppid + '][' + id + ']"]').addClass('is-invalid');
                $('span[name="ketstok[' + ppid + '][' + id + ']"]').text('Jumlah Kurang dari Permintaan');
            } else if (vals.qt >= kebutuhan) {
                $('select[name="variasi[' + ppid + '][' + id + ']"]').removeClass('is-invalid');
                $('span[name="ketstok[' + ppid + '][' + id + ']"]').text('');
            }
        })


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

        function trproduktable() {
            var data = `<tr>
                <td></td>
                <td>
                    <div class="form-group">
                        <select name="penjualan_produk_id[]" id="0" class="select2 form-control custom-select penjualan_produk_id @error('penjualan_produk_id') is-invalid @enderror" style="width:100%;">
                        </select>
                    </div>
                    <div class="detail_produk" id="detail_produk0">
                    </div>
                    <div class="detailjual" id="tes0">
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
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="prdhrg">Rp</span>
                        </div>
                        <input type="text" class="form-control produk_harga" name="produk_harga[]" id="produk_harga0" placeholder="Masukkan Harga" style="width:100%;" aria-describedby="prdhrg" value="" />
                    </div>
                </td>
                <td>
                    <div class="form-group d-flex justify-content-center">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="prdsub">Rp</span>
                        </div>
                        <input type="text" class="form-control produk_subtotal" name=" produk_subtotal[]" id=" produk_subtotal0" placeholder="Masukkan Subtotal" style="width:100%;" value="" aria-describedby="prdsub" readonly />
                    </div>
                </td>
                <td>
                    <a id="removerowproduk"><i class="fas fa-minus" style="color: red"></i></a>
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
        })

        function select_data(i) {
            $('#' + i).select2({
                ajax: {
                    minimumResultsForSearch: 20,
                    dataType: 'json',
                    delay: 250,
                    type: 'GET',
                    url: '/api/penjualan_produk/select/',
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
            }).change(function(i) {
                var index = $(this).attr('id');
                var id = $(this).val();
                console.log(index);
                console.log(id);
                $.ajax({
                    url: '/api/penjualan_produk/select/' + id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(res) {
                        $('#produk_harga' + index).val(formatmoney(res[0].harga));
                        console.log(res);
                        var tes = $('#detail_produk' + index);
                        tes.empty();
                        var datas = "";
                        tes.append(`<fieldset><legend><b>Detail Produk</b></legend>`);
                        for (var x = 0; x < res[0].produk.length; x++) {
                            var data = [];
                            tes.append(`<div>`);
                            tes.append(`<div class="card-body blue-bg">
                                        <h6>` + res[0].produk[x].nama + `</h6>
                                        <select class="form-control variasi" name="variasi[` + index + `][` + x + `]" id="variasi` + index + `` + x + `" style="width:100%;" data-attr="` + index + `` + x + `" data-id="` + x + `"></select>
                                        <span class="invalid-feedback d-block ketstok" name="ketstok[` + index + `][` + x + `]" id="ketstok` + index + `` + x + `" data-attr="` + index + `` + x + `" data-id="` + x + `"></span>
                                      </div>`);
                            if (res[0].produk[x].gudang_barang_jadi.length <= 1) {
                                data.push({
                                    id: res[0].produk[x].gudang_barang_jadi[0].id,
                                    text: res[0].produk[x].nama,
                                    jumlah: res[0].produk[x].pivot.jumlah,
                                    qt: res[0].produk[x].gudang_barang_jadi[0].stok
                                });
                            } else {
                                for (var y = 0; y < res[0].produk[x].gudang_barang_jadi.length; y++) {
                                    data.push({
                                        id: res[0].produk[x].gudang_barang_jadi[y].id,
                                        text: res[0].produk[x].gudang_barang_jadi[y].nama,
                                        jumlah: res[0].produk[x].pivot.jumlah,
                                        qt: res[0].produk[x].gudang_barang_jadi[y].stok
                                    });
                                }
                            }
                            console.log(data);
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
                    }
                });
            });
        }
        load_variasi();

        function load_variasi() {
            produk = [];
            produk = <?php
                        $prd = array();
                        if (isset($e->Pesanan)) {
                            $p = array();
                            if (isset($e->Pesanan->DetailPesanan)) {
                                echo json_encode($e->Pesanan->DetailPesanan);
                            } else {
                                echo json_encode($prd);
                            }
                        } else {
                            echo json_encode($prd);
                        } ?>;
            if (produk.length > 0) {
                for (var w = 0; w < produk.length; w++) {
                    $.ajax({
                        url: '/api/penjualan_produk/select/' + produk[w]['penjualan_produk_id'],
                        type: 'GET',
                        dataType: 'json',
                        async: false,
                        success: function(res) {
                            for (var x = 0; x < res[0].produk.length; x++) {
                                var data = [];
                                if (res[0].produk[x].gudang_barang_jadi.length <= 1) {
                                    data.push({
                                        id: res[0].produk[x].gudang_barang_jadi[0].id,
                                        text: res[0].produk[x].nama,
                                        jumlah: res[0].produk[x].pivot.jumlah,
                                        qt: res[0].produk[x].gudang_barang_jadi[0].stok
                                    });
                                } else {
                                    for (var y = 0; y < res[0].produk[x].gudang_barang_jadi.length; y++) {
                                        data.push({
                                            id: res[0].produk[x].gudang_barang_jadi[y].id,
                                            text: res[0].produk[x].gudang_barang_jadi[y].nama,
                                            jumlah: res[0].produk[x].pivot.jumlah,
                                            qt: res[0].produk[x].gudang_barang_jadi[y].stok
                                        });
                                    }
                                }
                                $('select[name="variasi[' + w + '][' + x + ']"]').select2({
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

                                $('select[name="variasi[' + w + '][' + x + ']"]').trigger("change");
                            }
                        }
                    });
                }
            }

        }

    });
</script>
@stop