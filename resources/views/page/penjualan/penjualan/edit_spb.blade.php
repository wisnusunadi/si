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

    @media screen and (min-width: 1440px) {

        section {
            font-size: 14px;
        }

        .btn {
            font-size: 12px;
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
        @foreach($spb as $e)
        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="margin">Info Penjualan SPB</h5>
                                <div class="row">
                                    <div class="col-5">
                                        <div class="margin">
                                            <small>Info Customer</small>
                                        </div>
                                        <div id="nama_customer" class="margin"><b>{{$e->customer->nama}}</b></div>
                                        <div id="alamat" class="margin"><b>{{$e->customer->alamat}}</b></div>
                                        <div id="provinsi" class="margin"><b>{{$e->customer->provinsi->nama}}</b></div>
                                        <div id="telepon" class="margin"><b>{{$e->customer->telp}}</b></div>
                                    </div>
                                    <div class="col-1"></div>
                                    <div class="col-2">
                                        <div class="margin">
                                            <div><small class="text-muted">No PO</small></div>
                                            <div id="no_po">
                                                <b>
                                                    @if($e->Pesanan)
                                                    {{$e->Pesanan->no_po}}
                                                    @endif
                                                </b>
                                            </div>
                                        </div>
                                        <div class="margin">
                                            <div><small class="text-muted">Tanggal PO</small></div>
                                            <div id="no_po">
                                                <b>
                                                    @if($e->Pesanan)
                                                    @if(empty($e->Pesanan->tgl_po) || $e->Pesanan->tgl_po != "0000-00-00")
                                                    -
                                                    @else
                                                    {{$e->Pesanan->tgl_po}}
                                                    @endif
                                                    @endif
                                                </b>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-2">
                                        <div class="margin">
                                            <div><small class="text-muted">No DO</small></div>
                                            <div id="no_po">
                                                <b>
                                                    @if($e->Pesanan != "")
                                                    @if(!empty($e->Pesanan->no_do))
                                                    {{$e->Pesanan->no_do}}
                                                    @else
                                                    -
                                                    @endif
                                                    @endif
                                                </b>
                                            </div>
                                        </div>
                                        <div class="margin">
                                            <div><small class="text-muted">Tanggal DO</small></div>
                                            <div id="no_po">
                                                <b>
                                                    @if($e->Pesanan != "")
                                                    @if(!empty($e->Pesanan->tanggal_do))
                                                    {{$e->Pesanan->tanggal_do}}
                                                    @else
                                                    -
                                                    @endif
                                                    @endif
                                                </b>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="margin">
                                            <div><small class="text-muted">No SO</small></div>
                                            <div id="no_so">
                                                <b>
                                                    @if($e->Pesanan != "")
                                                    @if(!empty($e->Pesanan->so))
                                                    {{$e->Pesanan->so}}
                                                    @else
                                                    -
                                                    @endif
                                                    @endif
                                                </b>
                                            </div>
                                        </div>
                                        <div class="margin">
                                            <div><small class="text-muted">Status</small></div>

                                            @if($e->log == "penjualan")
                                            <div id="status" class="badge red-text ">Penjualan</div>
                                            @elseif($e->log == "po")
                                            <div id="status" class="badge purple-text ">PO</div>
                                            @elseif($e->log == "gudang")
                                            <div id="status" class="badge orange-text ">Gudang</div>
                                            @elseif($e->log == "qc")
                                            <div id="status" class="badge yellow-text ">QC</div>
                                            @elseif($e->log == "logistik")
                                            <div id="status" class="badge blue-text ">Logistik</div>
                                            @elseif($e->log == "pengiriman")
                                            <div id="status" class="badge green-text ">Pengiriman</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-10 col-md-12 col-sm-12">
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
                            <form method="post" action="{{route('penjualan.penjualan.update_spb', ['id' => $e->id])}}">
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
                                <div class="row d-flex justify-content-center">
                                    <div class="col-10">
                                        <h4>Info Penjualan</h4>
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="form-group row">
                                                    <label for="" class="col-form-label col-5" style="text-align: right">Delivery Order</label>
                                                    <div class="col-5 col-form-label">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="do" id="yes" value="yes" {{ empty($e->Pesanan->no_do)? "" : "checked" }} />
                                                            <label class="form-check-label" for="yes">Tersedia</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="do" id="no" value="no" {{ empty($e->Pesanan->no_do)? "checked" : "" }} />
                                                            <label class="form-check-label" for="no">Tidak tersedia</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row    @if(empty($e->Pesanan->no_do)) hide @endif " id="do_detail_no">
                                                    <label for="" class="col-form-label col-5" style="text-align: right">Nomor DO</label>
                                                    <div class="col-4">
                                                        <input type="text" class="form-control col-form-label @error('no_do') is-invalid @enderror" id="no_do" name="no_do" value="{{$e->Pesanan->no_do}}" />
                                                        <div class="invalid-feedback" id="msgno_do">
                                                            @if($errors->has('no_do'))
                                                            {{ $errors->first('no_do')}}
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row  @if(empty($e->Pesanan->tgl_do)) hide @endif " id="do_detail_tgl">
                                                    <label for="" class="col-form-label col-5" style="text-align: right">Tanggal DO</label>
                                                    <div class="col-4">
                                                        <input type="date" class="form-control col-form-label @error('tanggal_do') is-invalid @enderror" id="tanggal_do" name="tanggal_do" value="{{$e->Pesanan->tgl_do}}" />
                                                        <div class="invalid-feedback" id="msgtanggal_po">
                                                            @if($errors->has('tanggal_do'))
                                                            {{ $errors->first('tanggal_do')}}
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="form-group row">
                                                    <label for="keterangan" class="col-form-label col-5" style="text-align: right">Keterangan</label>
                                                    <div class="col-5">
                                                        <textarea class="form-control col-form-label" id="nonketerangan" name="keterangan">{{$e->Pesanan->ket}}</textarea>
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
                                                                    @foreach($e->Detailspb as $f)
                                                                    <tr>
                                                                        <td>{{$loop->iteration}}</td>
                                                                        <td>
                                                                            <div class="form-group">
                                                                                <select name="penjualan_produk_id[]" id="{{$loop->iteration-1}}" class="select2 form-control custom-select penjualan_produk_id @error('penjualan_produk_id') is-invalid @enderror" style="width:100%;">
                                                                                    <option value="{{$f->penjualan_produk_id}}" selected>{{$f->penjualanproduk->nama}}</option>
                                                                                </select>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-group d-flex justify-content-center">
                                                                                <div class="input-group">
                                                                                    <input type="number" class="form-control produk_jumlah" aria-label="produk_satuan" name="produk_jumlah[]" id="produk_jumlah{{$loop->iteration-1}}" style="width:100%;" value="{{$f->jumlah}}">
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
                                                                                <input type="text" class="form-control produk_harga" name="produk_harga[]" id="produk_harga0" placeholder="Masukkan Harga" style="width:100%;" value="{{number_format($f->harga,0,',','.')}}" />
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-group d-flex justify-content-center">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text">Rp</span>
                                                                                </div>
                                                                                <input type="text" class="form-control produk_subtotal" name=" produk_subtotal[]" id=" produk_subtotal0" placeholder="Masukkan Subtotal" style="width:100%;" value="{{number_format($f->harga*$f->jumlah,0,',','.')}}" readonly />
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
                                                                        <th id="totalhargaprd" class="align-right">Rp.
                                                                            <?php $x = 0;
                                                                            foreach ($e->Detailspb as $f) {
                                                                                $x += $f->harga * $f->jumlah;
                                                                            }
                                                                            ?>
                                                                            {{number_format($x,0,',','.')}}
                                                                        </th>
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
        @endforeach
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

        $('input[type="radio"][name="do"]').on('change', function() {
            if ($(this).val() == "yes") {
                $("#do_detail_no").removeClass("hide");
                $("#do_detail_tgl").removeClass("hide");
            } else if ($(this).val() == "no") {
                $("#do_detail_no").addClass("hide");
                $("#do_detail_tgl").addClass("hide");
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
                $("#totalhargaprd").text("Rp. " + totalharga.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1."));
            })
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

            if (jumlah != "" && harga != "") {
                var hargacvrt = replaceAll(harga, '.', '');
                subtotal.val(formatmoney(jumlah * parseInt(hargacvrt)));
                totalhargaprd();
            } else {
                subtotal.val(formatmoney("0"));
                totalhargaprd();
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
                $(el).find('.variasi').attr('name', 'variasi[' + j + ']');
                $(el).find('.variasi').attr('id', 'variasi' + j);
                $(el).find('.produk_harga').attr('id', 'produk_harga' + j);
                $(el).find('.produk_subtotal').attr('id', 'produk_subtotal' + j);
                $(el).find('input[id="produk_jumlah"]').attr('name', 'produk_jumlah[' + j + ']');
                select_data(j);
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
                        <input type="text" class="form-control produk_subtotal" name=" produk_subtotal[]" id=" produk_subtotal0" placeholder="Masukkan Subtotal" style="width:100%;" readonly/>
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
                    placeholder: "Pilih Produk",
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
                var index = $(this).attr('id');
                var id = $(this).val();
                console.log(index);
                console.log(id);
                $.ajax({
                    url: '/api/penjualan_produk/select/' + id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        $('#produk_harga' + index).val(formatmoney(data[0].harga));
                    }
                });
            });
        }
    });
</script>
@stop