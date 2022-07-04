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
                                    <div class="col-lg-2 col-md-8">
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
                                <div class="form-group row" id="keterangan_input">
                                    <label for="keterangan" class="col-lg-5 col-md-12 col-form-label labelket">Keterangan Retur</label>
                                    <div class="col-lg-4 col-md-12">
                                        <textarea name="keterangan" id="keterangan" class="form-control col-form-label keterangan  @error('keterangan') is-invalid @enderror"></textarea>
                                        <div class="invalid-feedback" id="msgketerangan"></div>
                                    </div>
                                </div>
                                <hr class="my-4"/>
                                <h5>Referensi Transaksi</h5>
                                <div class="form-group row">
                                    <label for="ref_transaksi" class="col-lg-5 col-md-12 col-form-label labelket">Transaksi</label>
                                    <div class="col-lg-2 col-md-6 d-flex justify-content-between">
                                        <div class="form-check form-check-inline col-form-label">
                                            <input class="form-check-input" type="radio" name="ref_transaksi" id="ref_transaksi1" value="tersedia" />
                                            <label class="form-check-label" for="ref_transaksi1">Tersedia</label>
                                        </div>
                                        <div class="form-check form-check-inline col-form-label">
                                            <input class="form-check-input" type="radio" name="ref_transaksi" id="ref_transaksi2" value="tidak_tersedia" />
                                            <label class="form-check-label" for="ref_transaksi2">Tidak Tersedia</label>
                                        </div>
                                        <div class="invalid-feedback" id="msgref_transaksi"></div>
                                    </div>
                                </div>
                                <div class="form-group row hide" id="no_ref_tidak_tersedia_input">
                                    <label for="no_ref_tidak_tersedia" class="col-lg-5 col-md-12 col-form-label labelket">No Referensi</label>
                                    <div class="col-lg-3 col-md-12">
                                        <input type="text" class="form-control  col-form-label" id="no_ref_tidak_tersedia" name="no_ref_tidak_tersedia">
                                        <div class="invalid-feedback" id="msgno_ref_tidak_tersedia"></div>
                                    </div>
                                </div>
                                <div class="form-group row hide" id="customer_tidak_tersedia_input">
                                    <label for="customer_tidak_tersedia" class="col-lg-5 col-md-12 col-form-label labelket">Nama Customer</label>
                                    <div class="col-lg-3 col-md-12">
                                        <input type="text" class="form-control  col-form-label" id="customer_tidak_tersedia" name="customer_tidak_tersedia">
                                        <div class="invalid-feedback" id="msgcustomer_tidak_tersedia"></div>
                                    </div>
                                </div>
                                <div class="form-group row hide" id="alamat_tidak_tersedia_input">
                                    <label for="alamat_tidak_tersedia" class="col-lg-5 col-md-12 col-form-label labelket">Alamat Customer</label>
                                    <div class="col-lg-3 col-md-12">
                                        <textarea class="form-control col-form-label" id="alamat_tidak_tersedia" name="alamat_tidak_tersedia"></textarea>
                                        <div class="invalid-feedback" id="msgalamat_tidak_tersedia"></div>
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
                                    <h5>Info Penjualan</h5>
                                    <div class="row row-cols-1 row-cols-md-2 g-4">
                                        <div class="col" id="info_customer">
                                            <div class="card removeshadow bg-light h-100">
                                                <div class="card-body">
                                                    <h6><b>Customer</b></h6>
                                                    <div class="row">
                                                        <div class="p-2" style="min-width:60%; max-width: 70%">
                                                            <div class="margin">
                                                                <div><small class="text-muted">Nama Customer</small></div>
                                                                <div><b id="nama_customer">-</b></div>
                                                            </div>
                                                            <div class="margin">
                                                                <div><small class="text-muted">Alamat</small></div>
                                                                <div><b id="alamat_customer">-</b></div>
                                                            </div>
                                                        </div>
                                                        <div class="p-2">
                                                            <div class="margin">
                                                                <div><small class="text-muted">No Telp</small></div>
                                                                <div><b id="telp_customer">-</b></div>
                                                            </div>
                                                            <div class="margin">
                                                                <div><small class="text-muted">Provinsi</small></div>
                                                                <div><b id="provinsi_customer">-</b></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col" id="info_penjualan">
                                            <div class="card removeshadow bg-light h-100">
                                                <div class="card-body">
                                                    <h6><b>Transaksi Penjualan</b></h6>
                                                    <div class="row d-flex justify-content-between">
                                                        <div class="p-2">
                                                            <div class="margin">
                                                                <div><small class="text-muted">No SO</small></div>
                                                                <div><b id="no_so">-</b></div>
                                                            </div>
                                                            <div class="margin">
                                                                <div><small class="text-muted">No AKN</small></div>
                                                                <div><b id="no_paket">-</b></div>
                                                            </div>
                                                        </div>
                                                        <div class="p-2">
                                                            <div class="margin">
                                                                <div><small class="text-muted">No PO</small></div>
                                                                <div><b id="no_po">-</b></div>
                                                            </div>
                                                            <div class="margin">
                                                                <div><small class="text-muted">Tanggal PO</small></div>
                                                                <div><b id="tgl_po">-</b></div>
                                                            </div>
                                                        </div>
                                                        <div class="p-2">
                                                            <div class="margin">
                                                                <div><small class="text-muted">No DO</small></div>
                                                                <div><b id="no_do">-</b></div>
                                                            </div>
                                                            <div class="margin">
                                                                <div><small class="text-muted">Tanggal DO</small></div>
                                                                <div><b id="tgl_do">-</b></div>
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
                                                                <div><b>-</b></div>
                                                            </div>
                                                            <div class="margin">
                                                                <div><small class="text-muted">Tanggal Retur</small></div>
                                                                <div><b>-</b></div>
                                                            </div>
                                                        </div>
                                                        <div class="p-2">
                                                            <div class="margin">
                                                                <div><small class="text-muted">No SO</small></div>
                                                                <div><b>-</b></div>
                                                            </div>
                                                            <div class="margin">
                                                                <div><small class="text-muted">No AKN</small></div>
                                                                <div><b>-</b></div>
                                                            </div>
                                                        </div>
                                                        <div class="p-2">
                                                            <div class="margin">
                                                                <div><small class="text-muted">No PO</small></div>
                                                                <div><b>-</b></div>
                                                            </div>
                                                            <div class="margin">
                                                                <div><small class="text-muted">Tanggal PO</small></div>
                                                                <div><b>-</b></div>
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
                                                        <td><select name="paket_produk_id[0]" id="paket_produk_id0" class="form-control custom-select paket_produk_id  @error('paket_produk_id') is-invalid @enderror"></select></td>
                                                        <td><select name="produk_id[0]" id="produk_id0" class="form-control custom-select produk_id  @error('produk_id') is-invalid @enderror"></select></td>
                                                        <td><select name="no_seri_select[0]" id="no_seri_select0" class="form-control custom-select no_seri @error('no_seri') is-invalid @enderror" multiple="true"></select>
                                                            <input type="text" class="form-control no_seri_input hide" id="no_seri_input0" name="no_seri_input[0]"/></td>
                                                        <td><a href="#" id="tambah_paket_produk"><i class="fas fa-plus text-success"></i></a></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{route('as.retur.show')}}" type="button" class="btn btn-danger">Batal</a>
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
        $('.no_ref_penjualan').select2();
        $('.paket_produk_id').select2();
        $('.produk_id').select2();
        $('.no_seri').select2();

        $('input[type="radio"][name="ref_transaksi"]').on('change', function(){
            format_informasi_ref_penjualan();
            var value = $(this).val();
            $('input[name="pilih_ref_penjualan"]').prop('checked', false);
            $('.no_ref_penjualan').empty();

            $('.paket_produk_id').empty();
            $('.produk_id').empty();
            $('.no_seri').empty();

            if(value == "tidak_tersedia"){
                $('#informasi_transaksi').addClass('hide');

                $('#no_ref_tidak_tersedia_input').removeClass('hide');
                $('#customer_tidak_tersedia_input').removeClass('hide');
                $('#alamat_tidak_tersedia_input').removeClass('hide');

                $('#pilih_ref_penjualan_input').addClass('hide');
                $('#no_ref_penjualan_input').addClass('hide');

                $('#produktable tr').find('.no_seri_input').removeClass('hide');
                $('#produktable tr').find('.no_seri').next(".select2-container").hide();
                produk_penjualan_tidak_tersedia();
            }
            else if(value == "tersedia"){
                $('#informasi_transaksi').removeClass('hide');

                $('#no_ref_tidak_tersedia_input').addClass('hide');
                $('#customer_tidak_tersedia_input').addClass('hide');
                $('#alamat_tidak_tersedia_input').addClass('hide');

                $('#pilih_ref_penjualan_input').removeClass('hide');
                $('#no_ref_penjualan_input').removeClass('hide');

                $('#produktable tr').find('.no_seri_input').addClass('hide');
                $('#produktable tr').find('.no_seri').next(".select2-container").show();
            }
        });

        $('input[name="pilih_ref_penjualan"]').on('change', function(){
            $('.no_ref_penjualan').empty();
            format_informasi_ref_penjualan();
            no_ref_penjualan($(this).val());
        });

        function numberRows($t) {
            var c = 0 - 1;
            var referensi = $('input[name="ref_transaksi"]:checked').val();
            $t.find("tr").each(function(ind, el) {
                $(el).find("td:eq(0)").html(++c);
                var j = c - 1;
                $(el).find('.paket_produk_id').attr('name', 'paket_produk_id[' + j + ']');
                $(el).find('.paket_produk_id').attr('id', 'paket_produk_id' + j);

                $(el).find('.produk_id').attr('name', 'produk_id[' + j + ']');
                $(el).find('.produk_id').attr('id', 'produk_id' + j);

                $(el).find('.no_seri').attr('name', 'no_seri_select[' + j + ']');
                $(el).find('.no_seri').attr('id', 'no_seri_select' + j);

                $(el).find('.no_seri_input').attr('name', 'no_seri_input[' + j + ']');
                $(el).find('.no_seri_input').attr('id', 'no_seri_input' + j);

                $('.paket_produk_id').select2();
                $('.produk_id').select2();
                $('.no_seri').select2();

                if(referensi == "tersedia"){
                    produk_penjualan_tersedia($(".no_ref_penjualan").val());
                    $('.no_seri_input').addClass('hide');
                    $('.no_seri').next(".select2-container").show();

                } else if(referensi == "tidak_tersedia"){
                    produk_penjualan_tidak_tersedia();
                    $('.no_seri_input').removeClass('hide');
                    $('.no_seri').next(".select2-container").hide();
                }
            });
        }

        $(document).on('click', '#produktable #tambah_paket_produk', function(){
            $('#produktable tr:last').after(`<tr>
                    <td>1</td>
                    <td><select name="paket_produk_id[0]" id="paket_produk_id0" class="form-control custom-select paket_produk_id  @error('paket_produk_id') is-invalid @enderror"></select></td>
                    <td><select name="produk_id[0]" id="produk_id0" class="form-control custom-select produk_id  @error('produk_id') is-invalid @enderror"></select></td>
                    <td><select name="no_seri_select[0]" id="no_seri_select0" class="form-control custom-select no_seri @error('no_seri') is-invalid @enderror" multiple="true"></select>
                        <input type="text" class="form-control no_seri_input hide" id="no_seri_input0" name="no_seri_input[0]"/></td>
                    <td><a id="remove_paket_produk"><i class="fas fa-minus" style="color: red"></i></a></td>
                </tr>`);
                numberRows($("#produktable"));
        });

        $('#produktable').on('click', '#remove_paket_produk', function(e) {
            $(this).closest('tr').remove();
            numberRows($("#produktable"));
        });

        function no_ref_penjualan(jenis) {
            $('.no_ref_penjualan').select2({
                ajax: {
                    minimumResultsForSearch: 20,
                    placeholder: "Pilih Produk",
                    dataType: 'json',
                    theme: "bootstrap",
                    delay: 250,
                    type: 'GET',
                    url: '/api/as/list/so_selesai/'+jenis,
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
            });
        }

        function produk_penjualan(id)
        {
            $('.paket_produk_id').select2({
                ajax: {
                    minimumResultsForSearch: 20,
                    placeholder: "Pilih Paket Produk",
                    dataType: 'json',
                    theme: "bootstrap",
                    delay: 250,
                    type: 'GET',
                    url: '/api/as/list/so_selesai_paket/'+ id,
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
                                    text: obj.penjualan_produk.nama
                                };
                            })
                        };
                    },
                }
            })
        }

        function format_informasi_ref_penjualan(){
            $('#nama_customer').text("-");
            $('#alamat_customer').text("-");
            $('#telp_customer').text("-");
            $('#provinsi_customer').text("-");

            $('#no_so').text("-");
            $('#no_po').text("-");
            $('#tgl_po').text("-");
            $('#no_paket').text("-");
            $('#no_do').text("-");
            $('#tgl_do').text("-");
        }

        function informasi_ref_penjualan(id){
            $.ajax({
                type: "GET",
                url: '/api/as/detail/so_retur/' + id,
                dataType: 'json',
                success: function(data) {
                    $('#nama_customer').text(data.customer.nama);
                    $('#alamat_customer').text(data.customer.alamat);
                    $('#provinsi_customer').text(data.customer.provinsi.nama);

                    $('#no_so').text(data.pesanan.so);

                    $('#no_po').text(data.pesanan.no_po);
                    $('#tgl_po').text(data.pesanan.tgl_po);
                    if(data.no_paket != undefined){
                        $('#no_paket').text(data.no_paket);
                    }
                    else{
                        $('#no_paket').text("-");
                    }

                    if(data.customer.telp != null){
                        $('#telp_customer').text(data.customer.telp);
                    }
                    else{
                        $('#telp_customer').text("-");
                    }

                    if(data.no_do != null){
                        $('#no_do').text(data.no_do);
                    }
                    else{
                        $('#no_do').text("-");
                    }

                    if(data.tgl_do != null){
                        $('#tgl_do').text(data.tgl_do);
                    }
                    else{
                        $('#tgl_do').text("-");
                    }
                },
                error: function(data) {
                    alert('Error occured');
                }
            });
        }

        function produk_penjualan_tersedia(id)
        {
            $('.paket_produk_id').empty();
            $('.paket_produk_id').select2({
                ajax: {
                    minimumResultsForSearch: 20,
                    placeholder: "Pilih Paket Produk",
                    dataType: 'json',
                    theme: "bootstrap",
                    delay: 250,
                    type: 'GET',
                    url: '/api/as/list/so_selesai_paket/'+ id,
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
                                    text: obj.penjualan_produk.nama
                                };
                            })
                        };
                    },
                }
            })
        }

        function produk_penjualan_tidak_tersedia(){
            var prm;
            // $('.paket_produk_id').empty();
            $('.paket_produk_id').select2({
                ajax: {
                    minimumResultsForSearch: 20,
                    placeholder: "Pilih Paket Produk",
                    dataType: 'json',
                    theme: "bootstrap",
                    delay: 250,
                    type: 'GET',
                    url: '/api/penjualan_produk/select_param/'+prm,
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
            })
        }

        function produk_gudang_tersedia(column, id){
            $('#'+column).empty();
            $('#'+column).select2({
                ajax: {
                    minimumResultsForSearch: 20,
                    placeholder: "Pilih Produk",
                    dataType: 'json',
                    theme: "bootstrap",
                    delay: 250,
                    type: 'GET',
                    url: '/api/as/list/so_selesai_paket_produk/'+ id,
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
                                    text: obj.gudang_barang_jadi.produk.nama +" "+ obj.gudang_barang_jadi.nama
                                };
                            })
                        };
                    },
                }
            })
        }

        function produk_gudang_tidak_tersedia(column, id){
            $('#'+column).select2({
                ajax: {
                    minimumResultsForSearch: 20,
                    placeholder: "Pilih Produk",
                    dataType: 'json',
                    theme: "bootstrap",
                    delay: 250,
                    type: 'GET',
                    url: '/api/penjualan_produk/select/' + id,
                    data: function(params) {
                        return {
                            term: params.term
                        }
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data[0].produk, function(obj) {
                                return {
                                    id: obj.id,
                                    text: obj.nama
                                };
                            })
                        };
                    },
                }
            })
        }

        $(document).on('keyup change', '.no_ref_penjualan', function(){
            var val = $(this).val();
            if(val != ""){

                informasi_ref_penjualan(val);
                produk_penjualan_tersedia(val);
            }
        });

        $(document).on('keyup change', '#produktable  .paket_produk_id', function(){
            var val = $(this).val();
            var column = $(this).closest('tr').find('.produk_id').attr('id');
            if(val != ""){
                $(column).empty();
                if($('input[name="ref_transaksi"]:checked').val() == "tidak_tersedia"){
                    produk_gudang_tidak_tersedia(column, val);
                } else if($('input[name="ref_transaksi"]:checked').val() == "tersedia"){
                    produk_gudang_tersedia(column, val);
                }
            }
        });
    })
</script>
@stop
