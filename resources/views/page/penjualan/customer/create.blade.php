@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0  text-dark">Customer</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                @if(Auth::user()->divisi_id == "26")
                <li class="breadcrumb-item"><a href="{{route('penjualan.dashboard')}}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{route('penjualan.customer.show')}}">Customer</a></li>
                <li class="breadcrumb-item active">Tambah Customer</li>
                @endif
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop

@section('adminlte_css')
<style>

</style>
@stop

@section('content')
<section class="content">
    <div class="container-fluid">
        <form action="{{route('penjualan.customer.store')}}" method="post">
            {{csrf_field()}}
            <div class="row d-flex justify-content-center">
                <div class="col-8">
                    <h5>Info Customer</h5>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group row">
                                        @if(session('error') || count($errors) > 0 )
                                        <div class="alert alert-danger alert-dismissible show fade col-12" role="alert">
                                            <strong>Gagal menambahkan!</strong> Periksa
                                            kembali data yang diinput
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        @elseif(session('success'))
                                        <div class="alert alert-success alert-dismissible show fade col-12" role="alert">
                                            <strong>Berhasil menambahkan data</strong>,
                                            Terima kasih
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="form-group row">
                                        <label for="nama_produk" class="col-4 col-form-label" style="text-align:right;">Nama Customer</label>
                                        <div class="col-6">
                                            <input type="text" class="form-control @error('nama_customer') is-invalid @enderror" placeholder="Masukkan Nama Customer" id="nama_customer" name="nama_customer" />
                                            <div class="invalid-feedback" id="msgnama_customer">
                                                @if($errors->has('nama_customer'))
                                                {{ $errors->first('nama_customer')}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="npwp" class="col-4 col-form-label" style="text-align:right;">NPWP</label>
                                        <div class="col-5">
                                            <input type="text" class="form-control @error('npwp') is-invalid @enderror" value="" placeholder="Masukkan NPWP" id="npwp" name="npwp" />
                                            <div class="invalid-feedback" id="msgnpwp">
                                                @if($errors->has('npwp'))
                                                {{ $errors->first('npwp')}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="alamat" class="col-4 col-form-label" style="text-align:right;">Alamat</label>
                                        <div class="col-8">
                                            <input type="text" class="form-control @error('alamat') is-invalid @enderror" placeholder="Masukkan Alamat" id="alamat" name="alamat" />
                                            <div class="invalid-feedback" id="msgalamat">
                                                @if($errors->has('alamat'))
                                                {{ $errors->first('alamat')}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="provinsi" class="col-4 col-form-label" style="text-align:right;">Provinsi</label>
                                        <div class="col-5">
                                            <select class="select-info form-control custom-select provinsi @error('alamat') is-invalid @enderror" name="provinsi" id="provinsi" width="100%">
                                            </select>
                                            <div class="invalid-feedback" id="msgprovinsi">
                                                @if($errors->has('provinsi'))
                                                {{ $errors->first('provinsi')}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="email" class="col-4 col-form-label" style="text-align:right;">Email</label>
                                        <div class="col-8">
                                            <input type="text" class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan Email" id="email" name="email" />
                                            <div class="invalid-feedback" id="msgemail">
                                                @if($errors->has('email'))
                                                {{ $errors->first('email')}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="telepon" class="col-4 col-form-label" style="text-align:right;">No Telp</label>
                                        <div class="col-5">
                                            <input type="text" class="form-control @error('telepon') is-invalid @enderror" value="" placeholder="Masukkan Telepon" id="telepon" name="telepon" />
                                            <div class="invalid-feedback" id="msgtelepon">
                                                @if($errors->has('telepon'))
                                                {{ $errors->first('telepon')}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="pic" class="col-4 col-form-label" style="text-align:right;">PIC</label>
                                        <div class="col-5">
                                            <input type="text" class="form-control @error('pic') is-invalid @enderror" placeholder="Nama PIC" id="pic" name="pic" />
                                            <div class="invalid-feedback" id="msgpic">
                                                @if($errors->has('pic'))
                                                {{ $errors->first('pic')}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="batas" class="col-form-label col-4" style="text-align: right">Batas Pembayaran</label>
                                        <div class="col-2 input-group">
                                            <input type="text" class="form-control col-form-label @error('batas') is-invalid @enderror" name="batas" id="batas" aria-label="batas" placeholder="Batas hari pembayaran" />
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="ket_no_paket">Hari</span>
                                            </div>
                                            <div class="invalid-feedback" id="msgno_batas">
                                                @if($errors->has('batas'))
                                                {{ $errors->first('batas')}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="telepon" class="col-4 col-form-label" style="text-align:right;">Keterangan</label>
                                        <div class="col-5">
                                            <textarea class="form-control" name="keterangan" id="keterangan"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row d-flex justify-content-center">
                <div class="col-8">

                    <span class="float-left">
                        <a type="button" class="btn btn-danger" href="{{route('penjualan.customer.show')}}">
                            Batal
                        </a>
                    </span>

                    <span class="float-right">
                        <button type="submit" class="btn btn-info" id="btntambah" disabled>
                            Tambah
                        </button>
                    </span>
                </div>
            </div>
        </form>
    </div>
</section>
@stop

@section('adminlte_js')
<script type="text/javascript" src="{{ asset('vendor/masking/masking.js') }}"></script>
<script>
    $(function() {
        $('#npwp').mask('00.000.000.0-000.000');

        function check_nama_cust(val) {
            var hasil = 0;
            $.ajax({
                type: 'GET',
                dataType: 'json',
                async: false,
                url: '/api/customer/nama/0/' + val,
                success: function(data) {
                    hasil = data;
                    // if (data.data >= 1) {
                    //     $("#msgnama_customer").text("Nama sudah terpakai");
                    //     $('#nama_customer').addClass('is-invalid');
                    //     $("#btnsimpan").attr("disabled", true);
                    // } else {
                    //     $("#msgnama_customer").text("");
                    //     $('#nama_customer').removeClass('is-invalid');
                    //     if ($('#telepon').val() != "" && $('#npwp').val() != "" && $('#alamat').val() != "" && $('.provinsi').val() != "") {
                    //         $("#btnsimpan").removeAttr("disabled");
                    //     } else {
                    //         $("#btnsimpan").attr("disabled", true);
                    //     }
                    // }
                }
            });
            return hasil;
        }

        $('input[name="nama_customer"]').on('keyup change', function() {
            var val = $(this).val();
            if ($(this).val() == "") {
                $("#msgnama_customer").text("Nama tidak boleh kosong");
                $('#nama_customer').addClass('is-invalid');
            } else if ($(this).val() != "") {
                if (check_nama_cust(val) >= 1) {
                    $("#msgnama_customer").text("Nama sudah terpakai");
                    $('#nama_customer').addClass('is-invalid');
                    $("#btnsimpan").attr("disabled", true);
                } else {
                    $("#msgnama_customer").text("");
                    $('#nama_customer').removeClass('is-invalid');
                    if ($('#telepon').val() != "" && $('#npwp').val() != "" && $('#alamat').val() != "" && $('.provinsi').val() != "") {
                        $("#btnsimpan").removeAttr("disabled");
                    } else {
                        $("#btnsimpan").attr("disabled", true);
                    }
                }
            }
        });

        $('input[name="telepon"]').on('keyup change', function() {
            if ($(this).val() == "") {
                $("#msgtelepon").text("Telepon tidak boleh kosong");
                $("#telepon").addClass('is-invalid');
                $("#btntambah").attr('disabled', true);
            } else if ($(this).val() != "") {
                if (!/^[0-9]+$/.test($(this).val())) {
                    $("#msgtelepon").text("Isi nomor telepon dengan angka");
                    $("#telepon").addClass('is-invalid');
                    $("#btntambah").attr('disabled', true);
                } else {
                    // if (checkTelepon(this.teleponer).value >= 1) {
                    //     this.msg["telepon"] = "Nomor Telepon sudah terpakai";
                    //     this.teleponer = true;
                    //     this.btndis = true;
                    // } else {
                    //     this.msg["telepon"] = "";
                    //     this.teleponer = false;
                    //     this.btndis = false;
                    // }
                    $("#msgtelepon").text("");
                    $("#telepon").removeClass('is-invalid');
                    $("#btntambah").removeAttr('disabled');
                    if (($("#nama_customer").val() != "" && check_nama_cust($("#nama_customer").val()) <= 0) && $("#npwp").val() != "" && $("#alamat").val() != "" && $('.provinsi').val() != "") {
                        $("#btntambah").removeAttr('disabled');
                    } else {
                        $("#btntambah").attr('disabled', true);
                    }
                }
            }
        })

        $('input[name="alamat"]').on('keyup change', function() {
            if ($(this).val() != "") {
                $('#msgalamat').text("");
                $('#alamat').removeClass("is-invalid");
                if (($("#nama_customer").val() != "" && check_nama_cust($("#nama_customer").val()) <= 0) && $("#npwp").val() != "" && $("#telepon").val() != "" && $('.provinsi').val() != "") {
                    $("#btntambah").removeAttr('disabled');
                } else {
                    $("#btntambah").attr('disabled', true);
                }
            } else {
                $('#msgalamat').text("Alamat tidak boleh kosong");
                $('#alamat').addClass("is-invalid");
                $("#btntambah").attr('disabled', true);
            }
        });

        $('input[name="npwp"]').on('keyup change', function() {
            if ($(this).val() == "") {
                $("#msgnpwp").text("NPWP tidak boleh kosong");
                $('#npwp').addClass('is-invalid');
            } else if ($(this).val() != "") {
                // if (checkCustomer($('#npwp').val()) >= 1) {
                //     $("#msgnpwp").text("Nama sudah terpakai");
                //     $('#npwp").addClass('is-invalid');
                //     $("#btntambah").attr("disabled", true);
                // } else {
                //     $("#msgnpwp").text("");
                //     $('#npwp").removeClass('is-invalid');
                //     $("#btntambah").removeAttr("disabled");
                // }
                if (!/^[0-9.-]+$/.test($(this).val())) {
                    $('#msgnpwp').text("Masukkan npwp dengan benar");
                    $('#npwp').addClass("is-invalid");
                    $("#btntambah").attr('disabled', true);
                } else {
                    $("#msgnpwp").text("");
                    $('#npwp').removeClass('is-invalid');
                    if ($('#telepon').val() != "" && ($("#nama_customer").val() != "" && check_nama_cust($("#nama_customer").val()) <= 0) && $('#alamat').val() != "" && $('.provinsi').val() != "") {
                        $("#btntambah").removeAttr("disabled");
                    } else {
                        $("#btntambah").attr("disabled", true);
                    }
                }
            }
        });

        $('input[name="email"]').on('keyup change', function() {
            var errorhandling = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
            if ($(this).val() != "") {
                if (!errorhandling.test($(this).val())) {
                    $('#msgemail').text("Masukkan email dengan benar");
                    $('#email').addClass("is-invalid");
                    $("#btntambah").attr('disabled', true);
                } else {
                    $('#msgemail').text("");
                    $('#email').removeClass("is-invalid");
                    if (($("#nama_customer").val() != "" && check_nama_cust($("#nama_customer").val()) <= 0) && $("#npwp").val() != "" && $("#telepon").val() != "" && $("#alamat").val() != "" && $('.provinsi').val() != "") {
                        $("#btntambah").removeAttr('disabled');
                    }
                }
            } else {
                $('#msgemail').text("");
                $('#email').removeClass("is-invalid");
                if (($("#nama_customer").val() != "" && check_nama_cust($("#nama_customer").val()) <= 0) && $("#npwp").val() != "" && $("#telepon").val() != "" && $("#alamat").val() != "" && $('.provinsi').val() != "") {
                    $("#btntambah").removeAttr('disabled');
                }
            }
        })

        $('.provinsi').select2({
            placeholder: "Pilih Provinsi",
            ajax: {
                minimumResultsForSearch: 20,
                dataType: 'json',
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
        }).change(

        )

    })
</script>
@stop
