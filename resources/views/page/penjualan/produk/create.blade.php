@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Produk</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <form action="">
            <div class="row d-flex justify-content-center">
                <div class="col-11">
                    <h5>Info Umum Paket</h5>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group row">
                                        <label for="nama_produk" class="col-4 col-form-label" style="text-align: right">Nama Paket</label>
                                        <div class="col-6">
                                            <input type="text" class="form-control @error('nama_paket') is-invalid @enderror" name="nama_paket" id="nama_paket" placeholder="Masukkan Nama Paket" />
                                            <div class="invalid-feedback" id="msgnama_paket">
                                                @if($errors->has('nama_paket'))
                                                {{ $errors->first('nama_paket')}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="nama_produk" class="col-4 col-form-label" style="text-align: right">Harga</label>
                                        <div class="input-group col-5">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="text" class="form-control" name="harga" id="harga" placeholder="Masukkan Harga" />
                                            <div class="invalid-feedback" id="msgharga">
                                                @if($errors->has('harga'))
                                                {{ $errors->first('harga')}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row d-flex justify-content-center">
                <div class="col-11">
                    <h5>Detail Produk Paket</h5>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table" style="text-align: center;" id="createtable">
                                            <thead>
                                                <tr>
                                                    <th colspan="5">
                                                        <button type="button" class="btn btn-primary float-right" id="addrow">
                                                            <i class="fas fa-plus"></i>
                                                            Produk
                                                        </button>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Produk</th>
                                                    <th>Kelompok</th>
                                                    <th>Jumlah</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>
                                                        <div class="form-group">
                                                            <select class="select-info form-control custom-select produk_id" name="produk_id[]" id="produk_id[]">
                                                                <option value="">Tes</option>
                                                                <option value="">Tes</option>
                                                            </select>
                                                        </div>
                                                    </td>
                                                    <td><span class="badge" id="kelompok_produk"></span></td>
                                                    <td>
                                                        <div class="form-group d-flex justify-content-center">
                                                            <input type="number" class="form-control" name="jumlah[]" id="jumlah" style="width: 50%" />
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a id="removerow"><i class="fas fa-minus" style="color: red"></i></a>
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
                <div class="col-11">
                    <span>
                        <button class="btn btn-danger float-left">Batal</button>
                    </span>
                    <span class="float-right">
                        <button type="submit" class="btn btn-info float-right disabled" id="btntambah">Tambah</button>
                    </span>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('adminlte_js')
<script>
    $(function() {
        function numberRows($t) {
            var c = 0 - 2;
            $t.find("tr").each(function(ind, el) {
                $(el).find("td:eq(0)").html(++c);
                var j = c - 1;
                $(el).find('input[id="jumlah"]').attr('name', 'jumlah[' + j + ']');
                $(el).find('.produk_id').attr('name', 'produk_id[' + j + ']');
                $(el).find('.produk_id').attr('id', 'produk_id' + j);
                // $('.produk_id').select2();
            });
        }

        $('#addrow').on('click', function() {
            $('#createtable tr:last').after(`<tr>
            <td></td>
            <td>
                <div class="form-group">
                    <select class="select-info form-control custom-select produk_id" name="produk_id[]" id="produk_id">
                        <option value=""></option>
                    </select>
                </div>
            </td>
            <td><span class="badge" id="kelompok_produk"></span></td>
            <td>
                <div class="form-group d-flex justify-content-center">
                    <input type="number" class="form-control" name="jumlah[]" id="jumlah" style="width: 50%" />
                </div>
            </td>
            <td>
                <a id="removerow"><i class="fas fa-minus" style="color: red"></i></a>
            </td>
            </tr>`);
            numberRows($("#createtable"));
        });

        $('#createtable').on('click', '#removerow', function(e) {
            $(this).closest('tr').remove();
            numberRows($("#createtable"));
        });

        $('#harga').on('keyup change', function() {
            var result = $(this).val().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            $(this).val(result);

            if ($(this).val() != "") {
                $('#msgharga').text("");
                $('#harga').removeClass("is-invalid");
                console.log($("#createtable tbody").length);
                if ($('#nama_paket').val() != "" && $("#createtable tbody").length > 0) {
                    $('#btntambah').removeClass('disabled');
                } else {
                    $('#btntambah').addClass('disabled');
                }
            } else if ($(this).val() == "") {
                $('#msgharga').text("Harga Harus diisi");
                $('#harga').addClass("is-invalid");
                $('#btntambah').addClass('disabled');
            }
        });

        $('#nama_paket').on('keyup change', function() {
            if ($(this).val() != "") {
                $('#msgnama_paket').text("");
                $('#nama_paket').removeClass("is-invalid");
                console.log($("#createtable tbody").length);
                if ($('#harga').val() != "" && $("#createtable tbody").length > 0) {
                    $('#btntambah').removeClass('disabled');
                } else {
                    $('#btntambah').addClass('disabled');
                }
            } else if ($(this).val() == "") {
                $('#msgnama_paket').text("Nama Paket Harus diisi");
                $('#nama_paket').addClass("is-invalid");
                $('#btntambah').addClass('disabled');
            }
        });
    });
</script>
@endsection