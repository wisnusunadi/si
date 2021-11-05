@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Jasa Ekspedisi</h1>
@stop

@section('adminlte_css')
<style>

</style>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="content">
            <form action="" method="post">
                {{csrf_field()}}
                <div class="row d-flex justify-content-center">
                    <div class="col-8">
                        <h5>Data Ekspedisi</h5>
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
                                            <label for="nama_ekspedisi" class="col-4 col-form-label" style="text-align:right;">Nama Ekspedisi</label>
                                            <div class="col-6">
                                                <input type="text" class="form-control @error('nama_ekspedisi') is-invalid @enderror" placeholder="Masukkan Nama Ekspedisi" id="nama_ekspedisi" name="nama_ekspedisi" />
                                                <div class="invalid-feedback" id="msgnama_ekspedisi">
                                                    @if($errors->has('nama_ekspedisi'))
                                                    {{ $errors->first('nama_ekspedisi')}}
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
                                            <label for="" class="col-form-label col-4" style="text-align: right">Via</label>
                                            <div class="col-5 col-form-label">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="via" id="via4" value="lain" />
                                                    <label class="form-check-label" for="via4">Lain</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="via" id="via1" value="darat" />
                                                    <label class="form-check-label" for="via1">Darat</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="via" id="via2" value="laut" />
                                                    <label class="form-check-label" for="via2">Laut</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="via" id="via3" value="" />
                                                    <label class="form-check-label" for="via3">Udara</label>
                                                </div>

                                                <div class="invalid-feedback" id="msgvia">
                                                    @if($errors->has('via'))
                                                    {{ $errors->first('via')}}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="jurusan" class="col-4 col-form-label" style="text-align:right;">Jurusan</label>
                                            <div class="col-5">
                                                <textarea class="form-control" name="jurusan" id="jurusan"></textarea>
                                                <div class="invalid-feedback" id="msgjurusan">
                                                    @if($errors->has('jurusan'))
                                                    {{ $errors->first('jurusan')}}
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
                            <a type="button" class="btn btn-danger" href="{{route('logistik.ekspedisi.show')}}">
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
    </div>
</div>
@stop

@section('adminlte_js')
<script>
    $(function() {
        $('input[name="nama_ekspedisi"]').on('keyup change', function() {
            if ($(this).val() == "") {
                $("#msgnama_ekspedisi").text("Nama tidak boleh kosong");
                $('#nama_ekspedisi').addClass('is-invalid');
            } else if ($(this).val() != "") {
                // if (checkCustomer($('#nama_ekspedisi').val()) >= 1) {
                //     $("#msgnama_customer").text("Nama sudah terpakai");
                //     $('#nama_ekspedisi").addClass('is-invalid');
                //     $("#btntambah").attr("disabled", true);
                // } else {
                //     $("#msgnama_customer").text("");
                //     $('#nama_ekspedisi").removeClass('is-invalid');
                //     $("#btntambah").removeAttr("disabled");
                // }
                $("#msgnama_customer").val("");
                $('#nama_ekspedisi').removeClass('is-invalid');
                if ($('#telepon').val() != "" && $('#alamat').val() != "" && $('input[type="radio"][name="via"]').val() != "" && $('#jurusan').val() != "") {
                    $("#btntambah").removeAttr("disabled");
                } else {
                    $("#btntambah").attr("disabled", true);
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
                    if ($("#nama_ekspedisi").val() != "" && $("#alamat").val() != "" && $('input[type="radio"][name="via"]').val() != "" && $('#jurusan').val() != "") {
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
                if ($("#nama_ekspedisi").val() != "" && $("#telepon").val() != "" && $('input[type="radio"][name="via"]').val() != "" && $('#jurusan').val() != "") {
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
                    if ($("#nama_ekspedisi").val() != "" && $("#telepon").val() != "" && $("#alamat").val() != "" && $('input[type="radio"][name="via"]').val() != "" && $('#jurusan').val() != "") {
                        $("#btntambah").removeAttr('disabled');
                    }
                }
            } else {
                $('#msgemail').text("");
                $('#email').removeClass("is-invalid");
                if ($("#nama_ekspedisi").val() != "" && $("#telepon").val() != "" && $("#alamat").val() != "" && $('input[type="radio"][name="via"]').val() != "" && $('#jurusan').val() != "") {
                    $("#btntambah").removeAttr('disabled');
                }
            }
        })

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

    })
</script>
@stop