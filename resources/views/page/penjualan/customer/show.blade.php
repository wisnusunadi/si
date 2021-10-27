@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Customer</h1>
@stop

@section('adminlte_css')
<style>
    #customertable td:nth-child(1),
    td:nth-child(2),
    td:nth-child(4),
    td:nth-child(5),
    td:nth-child(6),
    td:nth-child(8) {
        text-align: center;
    }

    .filter {
        margin: 5px;
    }
</style>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row" style="margin-bottom:10px;">
                            <div class="col-12">
                                <span class="float-right filter">
                                    <a href="{{route('penjualan.customer.create')}}"><button class="btn btn-outline-info">
                                            <i class="fas fa-plus"></i> Tambah
                                        </button></a>
                                </span>
                                <span class="dropdown float-right filter">
                                    <button class="btn btn-outline-secondary dropdown-toggle " type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="filterpenjualan">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="filterpenjualan">
                                        <form class="px-4" style="white-space:nowrap;">
                                            <div class="dropdown-header">
                                                Status
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="dropdownStatus" value="ekatalog" />
                                                    <label class="form-check-label" for="dropdownStatus">
                                                        E-Catalogue
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="dropdownStatus" />
                                                    <label class="form-check-label" for="dropdownStatus" value="spa">
                                                        SPA
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="dropdownStatus" value="spb" />
                                                    <label class="form-check-label" for="dropdownStatus">
                                                        SPB
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary float-right">
                                                    Cari
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-hover" id="customertable">
                                        <thead style="text-align:center;">
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>Alamat</th>
                                                <th>Email</th>
                                                <th>Telp</th>
                                                <th>NPWP</th>
                                                <th>Keterangan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>PT Dakai</td>
                                                <td>
                                                    Jl. Tambak Osowilangun A7 Benowo
                                                </td>
                                                <td></td>
                                                <td>08181828384</td>
                                                <td></td>
                                                <td>-</td>
                                                <td>
                                                    <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </div>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <a href="{{route('penjualan.customer.detail', ['id' => 1])}}">
                                                            <button class="dropdown-item" type="button">
                                                                <i class="fas fa-search"></i>
                                                                Detail
                                                            </button>
                                                        </a>
                                                        <a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr="">
                                                            <button class="dropdown-item" type="button">
                                                                <i class="fas fa-pencil-alt"></i>
                                                                Edit
                                                            </button>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>PT Dakai</td>
                                                <td>
                                                    Jl. Tambak Osowilangun A7 Benowo
                                                </td>
                                                <td></td>
                                                <td>08181828384</td>
                                                <td></td>
                                                <td>-</td>
                                                <td>
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>PT Dakai</td>
                                                <td>
                                                    Jl. Tambak Osowilangun A7 Benowo
                                                </td>
                                                <td></td>
                                                <td>08181828384</td>
                                                <td></td>
                                                <td>-</td>
                                                <td>
                                                    <i class="fas fa-ellipsis-v"></i>
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

            <div class="modal fade" id="editmodal" tabindex="-1" role="dialog" aria-labelledby="editmodal" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content" style="margin: 10px">
                        <div class="modal-header bg-warning">
                            <h4>Edit</h4>
                        </div>
                        <div class="modal-body" id="edit">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('adminlte_js')
<script>
    $(function() {
        $(document).on('click', '.editmodal', function(event) {
            event.preventDefault();
            var href = $(this).attr('data-attr');
            $.ajax({
                url: "{{route('penjualan.customer.edit')}}",
                beforeSend: function() {
                    $('#loader').show();
                },
                // return the result
                success: function(result) {
                    $('#editmodal').modal("show");
                    $('#edit').html(result).show();
                    $("#editform").attr("action", href);
                },
                complete: function() {
                    $('#loader').hide();
                },
                error: function(jqXHR, testStatus, error) {
                    console.log(error);
                    alert("Page " + href + " cannot open. Error:" + error);
                    $('#loader').hide();
                },
                timeout: 8000
            })
        });


        $(document).on('keyup change', 'input[name="nama_customer"]', function() {
            if ($(this).val() == "") {
                $("#msgnama_customer").text("Nama tidak boleh kosong");
                $('#nama_customer').addClass('is-invalid');
            } else if ($(this).val() != "") {
                // if (checkCustomer($('#nama_customer').val()) >= 1) {
                //     $("#msgnama_customer").text("Nama sudah terpakai");
                //     $('#nama_customer").addClass('is-invalid');
                //     $("#btnsimpan").attr("disabled", true);
                // } else {
                //     $("#msgnama_customer").text("");
                //     $('#nama_customer").removeClass('is-invalid');
                //     $("#btnsimpan").removeAttr("disabled");
                // }
                $("#msgnama_customer").val("");
                $('#nama_customer').removeClass('is-invalid');
                if ($('#telepon').val() != "" && $('#npwp').val() != "" && $('#alamat').val() != "") {
                    $("#btnsimpan").removeAttr("disabled");
                } else {
                    $("#btnsimpan").attr("disabled", true);
                }
            }
        })


        $('input[name="nama_customer"]').on('keyup change', function() {

        });

        $(document).on('keyup change', 'input[name="telepon"]', function() {
            if ($(this).val() == "") {
                $("#msgtelepon").text("Telepon tidak boleh kosong");
                $("#telepon").addClass('is-invalid');
                $("#btnsimpan").attr('disabled', true);
            } else if ($(this).val() != "") {
                if (!/^[0-9]+$/.test($(this).val())) {
                    $("#msgtelepon").text("Isi nomor telepon dengan angka");
                    $("#telepon").addClass('is-invalid');
                    $("#btnsimpan").attr('disabled', true);
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
                    $("#btnsimpan").removeAttr('disabled');
                    if ($("#nama_customer").val() != "" && $("#npwp").val() != "" && $("#alamat").val() != "") {
                        $("#btnsimpan").removeAttr('disabled');
                    } else {
                        $("#btnsimpan").attr('disabled', true);
                    }
                }
            }
        })

        $(document).on('keyup change', 'input[name="alamat"]', function() {
            if ($(this).val() != "") {
                $('#msgalamat').text("");
                $('#alamat').removeClass("is-invalid");
                if ($("#nama_customer").val() != "" && $("#npwp").val() != "" && $("#telepon").val() != "") {
                    $("#btnsimpan").removeAttr('disabled');
                } else {
                    $("#btnsimpan").attr('disabled', true);
                }
            } else {
                $('#msgalamat').text("Alamat tidak boleh kosong");
                $('#alamat').addClass("is-invalid");
                $("#btnsimpan").attr('disabled', true);
            }
        });

        $(document).on('keyup change', 'input[name="npwp"]', function() {
            if ($(this).val() == "") {
                $("#msgnpwp").text("Nama tidak boleh kosong");
                $('#npwp').addClass('is-invalid');
            } else if ($(this).val() != "") {
                // if (checkCustomer($('#npwp').val()) >= 1) {
                //     $("#msgnpwp").text("Nama sudah terpakai");
                //     $('#npwp").addClass('is-invalid');
                //     $("#btnsimpan").attr("disabled", true);
                // } else {
                //     $("#msgnpwp").text("");
                //     $('#npwp").removeClass('is-invalid');
                //     $("#btnsimpan").removeAttr("disabled");
                // }
                $("#msgnpwp").val("");
                $('#npwp').removeClass('is-invalid');
                if ($('#telepon').val() != "" && $('#nama_customer').val() != "" && $('#alamat').val() != "") {
                    $("#btnsimpan").removeAttr("disabled");
                } else {
                    $("#btnsimpan").attr("disabled", true);
                }
            }
        });

        $(document).on('keyup change', 'input[name="email"]', function() {
            var errorhandling = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
            if ($(this).val() != "") {
                if (!errorhandling.test($(this).val())) {
                    $('#msgemail').text("Masukkan email dengan benar");
                    $('#email').addClass("is-invalid");
                    $("#btnsimpan").attr('disabled', true);
                } else {
                    $('#msgemail').text("");
                    $('#email').removeClass("is-invalid");
                    if ($("#nama_customer").val() != "" && $("#npwp").val() != "" && $("#telepon").val() != "" && $("#alamat").val() != "") {
                        $("#btnsimpan").removeAttr('disabled');
                    }
                }
            } else {
                $('#msgemail').text("");
                $('#email').removeClass("is-invalid");
                if ($("#nama_customer").val() != "" && $("#npwp").val() != "" && $("#telepon").val() != "" && $("#alamat").val() != "") {
                    $("#btnsimpan").removeAttr('disabled');
                }
            }
        })
    })
</script>
@stop