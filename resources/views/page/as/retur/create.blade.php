@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0  text-dark">Retur</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    @if (Auth::user()->divisi_id == '26')
                        <li class="breadcrumb-item"><a href="{{ route('penjualan.dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('as.retur.show') }}">Retur</a></li>
                        <li class="breadcrumb-item active">Tambah Retur</li>
                    @endif
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@stop

@section('adminlte_css')
    <style>
        .hide {
            display: none !important;
        }

        .margin {
            margin: 5px;
        }

        .align-center {
            text-align: center;
        }

        .removeshadow{
            box-shadow:none;
        }

        @media screen and (min-width: 1220px) {

            body {
                font-size: 14px;
            }

            .btn {
                font-size: 14px;
            }

            .labelket {
                text-align: right;
            }

            .overflowy {
                max-height: 330px;
                overflow-y: scroll;
                box-shadow: none;
            }

            .cust {
                max-width: 40%;
            }

        }

        @media screen and (max-width: 1219px) {
            body {
                font-size: 12px;
            }

            .btn {
                font-size: 12px;
            }

            .labelket {
                text-align: right;
            }

            .overflowy {
                max-height: 330px;
                overflow-y: scroll;
                box-shadow: none;
            }

            .cust {
                max-width: 40%;
            }
        }

        @media screen and (max-width: 991px) {
            body {
                font-size: 12px;
            }

            .btn {
                font-size: 12px;
            }

            .labelket {
                text-align: left;
            }

            .margin-md {
                margin-top: 10px;
            }

            .align-md {
                text-align: center;
            }

            .overflowy {
                max-height: 455px;
                overflow-y: scroll;
                box-shadow: none;
            }
        }

    </style>
@stop

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header bg-info">
                        <h5 class="card-title"><i class="fas fa-plus"></i> Tambah Retur</h5>
                    </div>
                    <form method="POST" action="{{route('as.retur.store')}}" id="formtambahretur">
                        @csrf
                        <div class="card-body">
                            <div class="form-horizontal">
                                <h5>Referensi Transaksi</h5>
                                <div class="form-group row" id="no_retur_input">
                                    <label for="no_retur" class="col-lg-5 col-md-12 col-form-label labelket">No Retur</label>
                                    <div class="col-lg-2 col-md-6">
                                        <input name="no_retur" id="no_retur" class="form-control col-form-label no_retur  @error('no_retur') is-invalid @enderror">
                                        <div class="invalid-feedback" id="msgno_retur"></div>
                                    </div>
                                </div>
                                <div class="form-group row" id="tgl_retur_input">
                                    <label for="tgl_retur" class="col-lg-5 col-md-12 col-form-label labelket">Tanggal Retur</label>
                                    <div class="col-lg-2 col-md-6">
                                        <input type="date" name="tgl_retur" id="tgl_retur" class="form-control col-form-label tgl_retur  @error('tgl_retur') is-invalid @enderror">
                                        <div class="invalid-feedback" id="msgtgl_retur"></div>
                                    </div>
                                </div>
                                <hr class="my-4"/>
                                <h5>Referensi Transaksi</h5>
                                <div class="form-group row">
                                    <label for="ref_transaksi" class="col-lg-5 col-md-12 col-form-label labelket">Transaksi</label>
                                    <div class="col-lg-2 col-md-6 d-flex justify-content-between">
                                        <div class="form-check form-check-inline col-form-label">
                                            <input class="form-check-input" type="radio" name="ref_transaksi" id="ref_transaksi1" value="penjualan" />
                                            <label class="form-check-label" for="ref_transaksi1">Penjualan</label>
                                        </div>
                                        <div class="form-check form-check-inline col-form-label">
                                            <input class="form-check-input" type="radio" name="ref_transaksi" id="ref_transaksi2" value="retur" />
                                            <label class="form-check-label" for="ref_transaksi2">Retur</label>
                                        </div>
                                        <div class="invalid-feedback" id="msgref_transaksi"></div>
                                    </div>
                                </div>
                                <div class="form-group row hide" id="no_ref_retur_input">
                                    <label for="no_ref_retur" class="col-lg-5 col-md-12 col-form-label labelket">No Ref Retur</label>
                                    <div class="col-lg-3 col-md-12">
                                        <select name="no_ref_retur" id="no_ref_retur" class="form-control custom-select no_ref_retur  @error('no_ref_retur') is-invalid @enderror">
                                        </select>
                                        <div class="invalid-feedback" id="msgno_ref_retur"></div>
                                    </div>
                                </div>
                                <div class="form-group row hide" id="pilih_ref_penjualan_input">
                                    <label for="pilih_ref_penjualan" class="col-lg-5 col-md-12 col-form-label labelket">Cari Berdasarkan</label>
                                    <div class="col-lg-3 col-md-8 d-flex justify-content-between">
                                        <div class="form-check form-check-inline col-form-label">
                                            <input class="form-check-input" type="radio" name="pilih_ref_penjualan" id="pilih_ref_penjualan1" value="so" />
                                            <label class="form-check-label" for="pilih_ref_penjualan1">No SO</label>
                                        </div>
                                        <div class="form-check form-check-inline col-form-label">
                                            <input class="form-check-input" type="radio" name="pilih_ref_penjualan" id="pilih_ref_penjualan2" value="po" />
                                            <label class="form-check-label" for="pilih_ref_penjualan2">No PO</label>
                                        </div>
                                        <div class="form-check form-check-inline col-form-label">
                                            <input class="form-check-input" type="radio" name="pilih_ref_penjualan" id="pilih_ref_penjualan3" value="no_akn" />
                                            <label class="form-check-label" for="pilih_ref_penjualan3">No Paket (Ekatalog)</label>
                                        </div>
                                        <div class="invalid-feedback" id="msgpilih_ref_penjualan"></div>
                                    </div>
                                </div>
                                <div class="form-group row hide" id="no_ref_penjualan_input">
                                    <label for="no_ref_penjualan" class="col-lg-5 col-md-12 col-form-label labelket">No Ref Penjualan</label>
                                    <div class="col-lg-3 col-md-12">
                                        <select name="no_ref_penjualan" id="no_ref_penjualan" class="form-control custom-select no_ref_penjualan  @error('no_ref_penjualan') is-invalid @enderror">
                                        </select>
                                        <div class="invalid-feedback" id="msgno_ref_penjualan"></div>
                                    </div>
                                </div>
                                <div id="informasi_transaksi" class="hide">
                                    <hr class="my-4"/>
                                    <h5>Info Penjualan / Retur</h5>
                                    <div class="row row-cols-1 row-cols-md-2 g-4">
                                        <div class="col hide" id="info_customer">
                                            <div class="card removeshadow bg-light h-100">
                                                <div class="card-body">
                                                    <h6><b>Customer</b></h6>
                                                    <div class="row">
                                                        <div class="p-2" style="max-width: 70%">
                                                            <div class="margin">
                                                                <div><small class="text-muted">Nama Customer</small></div>
                                                                <div><b>PT EMIINDO JAYA BERSAMA</b></div>
                                                            </div>
                                                            <div class="margin">
                                                                <div><small class="text-muted">Alamat</small></div>
                                                                <div><b>Jl. Perintis Kemerdekaan No.8, RT.7/RW.8, Pulo Gadung, Kec. Pulo Gadung, Kota Jakarta Timur, Daerah Khusus Ibukota Jakarta 13260</b></div>
                                                            </div>
                                                        </div>
                                                        <div class="p-2">
                                                            <div class="margin">
                                                                <div><small class="text-muted">No Telp</small></div>
                                                                <div><b>0838-3490-1233</b></div>
                                                            </div>
                                                            <div class="margin">
                                                                <div><small class="text-muted">Provinsi</small></div>
                                                                <div><b>DKI JAKARTA</b></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col hide" id="info_penjualan">
                                            <div class="card removeshadow bg-light h-100">
                                                <div class="card-body">
                                                    <h6><b>Transaksi Penjualan</b></h6>
                                                    <div class="row d-flex justify-content-between">
                                                        <div class="p-2">
                                                            <div class="margin">
                                                                <div><small class="text-muted">No SO</small></div>
                                                                <div><b>PT EMIINDO JAYA BERSAMA</b></div>
                                                            </div>
                                                            <div class="margin">
                                                                <div><small class="text-muted">No AKN</small></div>
                                                                <div><b>AK1-P2207-28313</b></div>
                                                            </div>
                                                        </div>
                                                        <div class="p-2">
                                                            <div class="margin">
                                                                <div><small class="text-muted">No PO</small></div>
                                                                <div><b>MEMO PEMESANAN PO932631903</b></div>
                                                            </div>
                                                            <div class="margin">
                                                                <div><small class="text-muted">Tanggal PO</small></div>
                                                                <div><b>22 Januari 2022</b></div>
                                                            </div>
                                                        </div>
                                                        <div class="p-2">
                                                            <div class="margin">
                                                                <div><small class="text-muted">No DO</small></div>
                                                                <div><b>DO/2022/02/01/000832</b></div>
                                                            </div>
                                                            <div class="margin">
                                                                <div><small class="text-muted">Tanggal DO</small></div>
                                                                <div><b>01 Februari 2022</b></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col hide" id="info_retur">
                                            <div class="card removeshadow bg-light h-100">
                                                <div class="card-body">
                                                    <h6><b>Transaksi Retur</b></h6>
                                                    <div class="row d-flex justify-content-between">
                                                        <div class="p-2">
                                                            <div class="margin">
                                                                <div><small class="text-muted">No Retur</small></div>
                                                                <div><b>DO/2022/02/01/000832</b></div>
                                                            </div>
                                                            <div class="margin">
                                                                <div><small class="text-muted">Tanggal Retur</small></div>
                                                                <div><b>01 Februari 2022</b></div>
                                                            </div>
                                                        </div>
                                                        <div class="p-2">
                                                            <div class="margin">
                                                                <div><small class="text-muted">No SO</small></div>
                                                                <div><b>PT EMIINDO JAYA BERSAMA</b></div>
                                                            </div>
                                                            <div class="margin">
                                                                <div><small class="text-muted">No AKN</small></div>
                                                                <div><b>AK1-P2207-28313</b></div>
                                                            </div>
                                                        </div>
                                                        <div class="p-2">
                                                            <div class="margin">
                                                                <div><small class="text-muted">No PO</small></div>
                                                                <div><b>MEMO PEMESANAN PO932631903</b></div>
                                                            </div>
                                                            <div class="margin">
                                                                <div><small class="text-muted">Tanggal PO</small></div>
                                                                <div><b>22 Januari 2022</b></div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="barang_penjualan">
                                    <hr class="my-4">
                                    <h5>Produk</h5>
                                    <div class="form-group row">
                                        <div class="table-responsive">
                                            <table class="table table-hover" id="produktable" style="text-align:center;">
                                                <thead>
                                                    <tr>
                                                        <th width="7%">No</th>
                                                        <th width="30%">Nama Paket</th>
                                                        <th width="25%">Nama Produk</th>
                                                        <th width="30%">No Seri</th>
                                                        <th width="8%">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td><select name="paket_produk_id" id="paket_produk_id" class="form-control custom-select paket_produk_id  @error('paket_produk_id') is-invalid @enderror"></select></td>
                                                        <td><select name="produk_id" id="produk_id" class="form-control custom-select produk_id  @error('produk_id') is-invalid @enderror"></select></td>
                                                        <td><select name="no_seri" id="no_seri" class="form-control custom-select no_seri  @error('no_seri') is-invalid @enderror"></select></td>
                                                        <td><a><i class="fas fa-plus text-success"></i></a></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{asset('as.retur.show')}}" type="button" class="btn btn-danger">Batal</a>
                            <button type="submit" class="btn btn-info float-right" id="btnsubmit">Tambah</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('adminlte_js')
<script>
    $(function(){
        $('.no_ref_retur').select2();
        $('.no_ref_penjualan').select2();

        $('.paket_produk_id').select2();
        $('.produk_id').select2();
        $('.no_seri').select2();

        $('input[name="ref_transaksi"]').on('change', function(){
            var value = $(this).val();
            $('#informasi_transaksi').removeClass('hide');
            $('#info_customer').removeClass('hide');
            if(value == "retur"){
                $('#no_ref_retur_input').removeClass('hide');
                $('#pilih_ref_penjualan_input').addClass('hide');
                $('#no_ref_penjualan_input').addClass('hide');

                $('#info_penjualan').addClass('hide');
                $('#info_retur').removeClass('hide');
            }else if(value == "penjualan"){
                $('#no_ref_retur_input').addClass('hide');
                $('#pilih_ref_penjualan_input').removeClass('hide');
                $('#no_ref_penjualan_input').removeClass('hide');

                $('#info_penjualan').removeClass('hide');
                $('#info_retur').addClass('hide');
            }
        })
    })
</script>
@stop
