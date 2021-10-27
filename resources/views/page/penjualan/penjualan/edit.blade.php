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
</style>
@stop

@section('content')
<div class="row">
    <div class="col-12">
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
                                            <select name="customer_id" id="customer_id" class="form-control custom-select @error('customer_id') is-invalid @enderror">
                                                <option value=""></option>
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
                                            <input type="text" class="form-control col-form-label" name="alamat" id="alamat" readonly />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="" class="col-form-label col-5" style="text-align: right">Telepon</label>
                                        <div class="col-5">
                                            <input type="text" class="form-control col-form-label" name="telepon" id="telepon" readonly />
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
                                        <label for="" class="col-form-label col-5" style="text-align: right">Sales Order</label>
                                        <div class="col-4">
                                            <input type="text" class="form-control col-form-label @error('no_soakn') is-invalid @enderror" id="no_so_akn" name="no_so_akn" />
                                            <div class="invalid-feedback" id="msgno_so_akn">
                                                @if($errors->has('no_so_akn'))
                                                {{ $errors->first('no_so_akn')}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="" class="col-form-label col-5" style="text-align: right">No Paket</label>
                                        <div class="col-5 input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="no_paket">AK1</span>
                                            </div>
                                            <input type="text" class="form-control col-form-label @error('no_paket') is-invalid @enderror" name="no_paket" id="no_paket" />
                                            <div class="invalid-feedback" id="msgno_paket">
                                            @if($errors->has('no_paket'))
                                                {{ $errors->first('no_paket')}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
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
                                    <label for="" class="col-form-label col-5" style="text-align: right">Nomor PO</label>
                                    <div class="col-4">
                                        <input type="text" class="form-control col-form-label @error('no_po_akn') is-invalid @enderror" id="no_po_akn" name="no_po_akn" />
                                        <div class="invalid-feedback" id="msgno_po_akn">
                                        @if($errors->has('no_po_akn'))
                                                {{ $errors->first('no_po_akn')}}
                                                @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-5" style="text-align: right">Tanggal PO</label>
                                    <div class="col-4">
                                        <input type="date" class="form-control col-form-label @error('tanggal_po_akn') is-invalid @enderror" id="tanggal_po_akn" name="tanggal_po_akn" />
                                        <div class="invalid-feedback" id="msgtanggal_po_akn">
                                        @if($errors->has('no_po_akn'))
                                                {{ $errors->first('no_po_akn')}}
                                                @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-5" style="text-align: right">Delivery Order</label>
                                    <div class="col-5 col-form-label">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="do_akn" id="yes" value="yes" />
                                            <label class="form-check-label" for="yes">Tersedia</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="do_akn" id="no" value="no" />
                                            <label class="form-check-label" for="no">Tidak tersedia</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row hide" id="do_detail_no_akn">
                                    <label for="" class="col-form-label col-5" style="text-align: right">Nomor DO</label>
                                    <div class="col-4">
                                        <input type="text" class="form-control col-form-label @error('no_do_akn') is-invalid @enderror" id="no_do_akn" name="no_do_akn" />
                                        <div class="invalid-feedback" id="msgno_do_akn">
                                        @if($errors->has('no_do_akn'))
                                                {{ $errors->first('no_do_akn')}}
                                                @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row hide" id="do_detail_tgl_akn">
                                    <label for="" class="col-form-label col-5" style="text-align: right">Tanggal DO</label>
                                    <div class="col-4">
                                        <input type="date" class="form-control col-form-label @error('tanggal_do_akn') is-invalid @enderror" id="tanggal_do_akn" name="tanggal_do_akn" />
                                        <div class="invalid-feedback" id="msgtanggal_do_akn">
                                        @if($errors->has('tanggal_do_akn'))
                                                {{ $errors->first('tanggal_do_akn')}}
                                                @endif
                                        </div>
                                    </div>
                                </div>

                                    
                                    <div class="form-group row">
                                        <label for="" class="col-form-label col-5" style="text-align: right">Status</label>
                                        <div class="col-5 col-form-label">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="status_akn" id="status_akn1" value="ekatalog" />
                                                <label class="form-check-label" for="status_akn1">Sepakat</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="status_akn" id="status_akn2" value="spa" />
                                                <label class="form-check-label" for="status_akn2">Negosiasi</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="status_akn" id="status_akn3" value="spb" />
                                                <label class="form-check-label" for="status_akn3">Batal</label>
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
                                        <label for="" class="col-form-label col-5" style="text-align: right">Batas Kontrak</label>
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
                                            <textarea class="form-control col-form-label" v-model="keterangan"></textarea>
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
                                    <label for="" class="col-form-label col-5" style="text-align: right">Tanggal Pemesanan</label>
                                    <div class="col-4">
                                        <input type="date" class="form-control col-form-label @error('nontanggal_pemesanan') is-invalid @enderror" id="nontanggal_pemesanan" name="nontanggal_pemesanan" />
                                        <div class="invalid-feedback" id="msgnontanggal_pemesanan">
                                        @if($errors->has('nontanggal_pemesanan'))
                                                {{ $errors->first('nontanggal_pemesanan')}}
                                                @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-5" style="text-align: right">Sales Order</label>
                                    <div class="col-4">
                                        <input type="text" class="form-control col-form-label @error('no_so') is-invalid @enderror" id="no_so" name="no_so" />
                                        <div class="invalid-feedback" id="msgno_so">
                                        @if($errors->has('no_so'))
                                                {{ $errors->first('no_so')}}
                                                @endif
                                        </div>
                                    </div>
                                </div>
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
                                                        <th>No</th>
                                                        <th>Nama Paket</th>
                                                        <th>Variasi</th>
                                                        <th>Jumlah</th>
                                                        <th>Ketersediaan</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td>
                                                            <a id="removerowproduk"><i class="fas fa-times" style="color: red"></i></a>
                                                        </td>
                                                    </tr>
                                                </tbody>
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
                                                        <th>No</th>
                                                        <th>Nama Part</th>
                                                        <th>Jumlah</th>
                                                        <th>Ketersediaan</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td>
                                                            <a id="removerowpart"><i class="fas fa-times" style="color: red"></i></a>
                                                        </td>
                                                    </tr>
                                                </tbody>
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
                            <a href="{{route('penjualan.penjualan.show')}}">
                                <button class="btn btn-danger">
                                    Batal
                                </button>
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
@stop

@section('adminlte_js')
<script>
    $(function() {
        var jenis = "{{jenis}}";
        jenis_penjualan(jenis);
        $('#customer_id').on('keyup change', function() {
            if ($(this).val() != "") {
                $('#msgcustomer_id').text("");
                $('#customer_id').removeClass('is-invalid');
                var value = getCustomer($(this).val());
                $('#alamat').val(value.alamat);
                $('#telepon').val(value.telepon);
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

        function jenis_penjualan(jenis_penjualan){
            if (jenis_penjualan == "ekatalog") {
                $("#datapart").addClass("hide");
                $("#dataproduk").removeClass("hide");
                $("#nonakn").addClass("hide");
                $("#akn").removeClass("hide");
            } else if (jenis_penjualan == "spa") {
                $("#datapart").addClass("hide");
                $("#dataproduk").removeClass("hide");
                $("#nonakn").removeClass("hide");
                $("#akn").addClass("hide");
            } else if (jenis_penjualan == "spb") {
                $("#datapart").removeClass("hide");
                $("#dataproduk").addClass("hide");
                $("#nonakn").removeClass("hide");
                $("#akn").addClass("hide");
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

        function numberRowsProduk($t) {
            var c = 0 - 1;
            $t.find("tr").each(function(ind, el) {
                $(el).find("td:eq(0)").html(++c);
                var j = c - 1;
            });
        }

        $('#addrowproduk').on('click', function() {
            $('#produktable tr:last').after(`<tr>
                <td></td>
                <td>
                    <div class="form-group">
                        <select name="penjualan_produk_id[]" id="penjualan_produk_id" class="form-control custom-select @error('penjualan_produk_id') is-invalid @enderror">
                            <option value=""></option>
                        </select>
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <select name="variasi[]" id="variasi" class="form-control custom-select @error('variasi') is-invalid @enderror">
                            <option value=""></option>
                        </select>
                    </div>
                </td>
                <td>
                    <div class="form-group d-flex justify-content-center">
                        <input type="number" class="form-control" id="produk_jumlah" name="produk_jumlah" style="width: 50%" />
                    </div>
                </td>
                <td>
                    <span class="badge" id="produk_ketersediaan"></span>
                </td>
                <td>
                    <a id="removerowproduk"><i class="fas fa-minus" style="color: red"></i></a>
                </td>
            </tr>`);
            numberRowsProduk($("#produktable"));
        });

        $('#produktable').on('click', '#removerowproduk', function(e) {
            $(this).closest('tr').remove();
            numberRowsProduk($("#produktable"));
        });

        function numberRowsPart($t) {
            var c = 0 - 1;
            $t.find("tr").each(function(ind, el) {
                $(el).find("td:eq(0)").html(++c);
                var j = c - 1;
            });
        }

        $('#addrowpart').on('click', function() {
            $('#parttable tr:last').after(`
            <tr>
                <td></td>
                <td>
                    <div class="form-group">
                        <select class="select2 form-control custom-select" name="part_id" id="part_id">
                            <option value=""></option>
                        </select>
                    </div>
                </td>
                <td>
                    <div class="form-group d-flex justify-content-center">
                        <input type="number" class="form-control" id="part_jumlah" style="width: 50%" />
                    </div>
                </td>
                <td>
                    <span class="badge" id="part_ketersediaan"></span>
                </td>
                <td>
                    <a id="removerowpart"><i class="fas fa-minus" style="color: red"></i></a>
                </td>
                </tr>`);
            numberRowsPart($("#parttable"));
        });

        $('#parttable').on('click', '#removerowpart', function(e) {
            $(this).closest('tr').remove();
            numberRowsPart($("#parttable"));
        });

    });
</script>
@stop