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
                @if(Auth::user()->divisi_id == "26" || Auth::user()->divisi_id == "8")
                <li class="breadcrumb-item"><a href="{{route('penjualan.dashboard')}}">Beranda</a></li>
                <li class="breadcrumb-item active">Customer</li>
                @elseif(Auth::user()->divisi_id == "2")
                <li class="breadcrumb-item"><a href="{{route('direksi.dashboard')}}">Beranda</a></li>
                <li class="breadcrumb-item active">Customer</li>
                @endif
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop

@section('adminlte_css')
<style>
    .align-center {
        text-align: center;
    }

    .nowrap-text {
        white-space: nowrap;
    }

    .filter {
        margin: 5px;
    }

    .minimizechar {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 25ch;
    }

    .dropdown-toggle:hover {
        color: #4682B4;
    }

    .dropdown-toggle:active {
        color: #C0C0C0;
    }

    .yellow-bg {
        background-color: #ffe680;
        color: #997a00;
    }

    @media screen and (min-width: 1440px) {
        section {
            font-size: 14px;
        }

        .dropdown-item {
            font-size: 14px;
        }
    }

    @media screen and (max-width: 1439px) {
        section {
            font-size: 12px;
        }

        .dropdown-item {
            font-size: 12px;
        }
    }
</style>
@stop

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                @if(Session::has('error') || count($errors) > 0 )
                <div class="alert alert-danger alert-dismissible fade show col-12" role="alert">
                    <strong>{{Session::get('error')}}</strong> Periksa
                    kembali data yang diinput
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @elseif(Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show col-12" role="alert">
                    <strong>{{Session::get('success')}}</strong>,
                    Terima kasih
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row" style="margin-bottom:10px;">
                                    <div class="col-12">
                                        @if(Auth::user()->divisi->id == "26")
                                        <span class="float-right filter">
                                            <a href="{{route('penjualan.customer.create')}}"><button class="btn btn-outline-info">
                                                    <i class="fas fa-plus"></i> Tambah
                                                </button></a>
                                        </span>
                                        @endif
                                        <span class="dropdown float-right filter">
                                            <button class="btn btn-outline-secondary dropdown-toggle " type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="filterpenjualan">
                                                <i class="fas fa-filter"></i> Filter
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="filterpenjualan">
                                                <form class="px-4" style="white-space:nowrap;" id="filter">
                                                    <div class="dropdown-header">
                                                        Status
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input type="radio" class="form-check-input" id="dropdownStatus1" value="2" name='filter' />
                                                            <label class="form-check-label" for="dropdownStatus1">
                                                                Jawa
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input type="radio" class="form-check-input" id="dropdownStatus2" value="1" name='filter' />
                                                            <label class="form-check-label" for="dropdownStatus2">
                                                                Luar Jawa
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input type="radio" class="form-check-input" id="dropdownStatus3" value="0" name='filter' />
                                                            <label class="form-check-label" for="dropdownStatus3">
                                                                Semua
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
                                            <table class="table table-hover" id="showtable" style="width:100%;">
                                                <thead style="text-align:center;">
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nama</th>
                                                        <th>Alamat</th>
                                                        <th>Provinsi</th>
                                                        <th>Email</th>
                                                        <th>Telp</th>
                                                        <th>NPWP</th>
                                                        <th>Keterangan</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="editmodal" role="dialog" aria-labelledby="editmodal" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content" style="margin: 10px">
                                <div class="modal-header yellow-bg">
                                    <h4 class="modal-title"><b>Ubah</b></h4>
                                </div>
                                <div class="modal-body" id="edit">

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
        $(document).on('submit', '#form-customer-update', function(e) {
            e.preventDefault();
            var action = $(this).attr('data-attr');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: action,
                data: $('#form-customer-update').serialize(),
                success: function(response) {
                    if (response['data'] == "success") {
                        swal.fire(
                            'Berhasil',
                            'Berhasil melakukan edit data',
                            'success'
                        );
                        $("#editmodal").modal('hide');
                        $('#showtable').DataTable().ajax.reload();
                    } else if (response['data'] == "error") {
                        swal.fire(
                            'Gagal',
                            'Gagal melakukan edit data',
                            'error'
                        );
                    }
                },
                error: function(xhr, status, error) {
                    alert($('#form-customer-update').serialize());
                }
            });
            return false;
        });

        var showtable = $('#showtable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                'url': '/api/penjualan/customer/data/0',
                'headers': {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                }
            },
            language: {
                processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
            },
            columns: [{
                    data: 'DT_RowIndex',
                    className: 'align-center nowrap-text',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'nama',
                    className: 'nowrap-text',
                },
                {
                    data: 'alamat',
                    className: 'minimizechar',
                    orderable: false,
                },
                {
                    data: "prov",
                    className: 'align-center nowrap-text',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'email',
                    className: 'align-center nowrap-text',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'telp',
                    className: 'align-center nowrap-text',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'npwp',
                    className: 'align-center nowrap-text',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'ket',
                    className: 'minimizechar',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'button',
                    className: 'align-center nowrap-text',
                    orderable: false,
                    searchable: false
                }
            ]
        });
        $(document).on('click', '.editmodal', function(event) {
            event.preventDefault();
            var href = $(this).attr('data-attr');
            var id = $(this).data('id');
            $.ajax({
                url: "/api/customer/update_modal/" + id,
                beforeSend: function() {
                    $('#loader').show();
                },
                // return the result
                success: function(result) {

                    $('#editmodal').modal("show");
                    $('#edit').html(result).show();
                    console.log(id);
                    // $("#editform").attr("action", href);
                    select_data();
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
            var id = $('#form-customer-update').attr('data-id');
            if ($(this).val() == "") {
                $("#msgnama_customer").text("Nama tidak boleh kosong");
                $('#nama_customer').addClass('is-invalid');
            } else if ($(this).val() != "") {
                $.ajax({
                    type: 'GET',
                    dataType: 'json',
                    url: '/api/customer/nama/' + id + '/' + $(this).val(),
                    success: function(data) {
                        if (data.data >= 1) {
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
            }
        })
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
                    $('#msgnpwp').text("Masukkan NPWP dengan benar");
                    $('#npwp').addClass("is-invalid");
                    $("#btnsimpan").attr('disabled', true);
                } else {
                    $("#msgnpwp").text("");
                    $('#npwp').removeClass('is-invalid');
                    if ($('#telepon').val() != "" && $('#nama_customer').val() != "" && $('#alamat').val() != "") {
                        $("#btnsimpan").removeAttr("disabled");
                    } else {
                        $("#btnsimpan").attr("disabled", true);
                    }
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

        function select_data() {
            $('.provinsi').select2({
                ajax: {
                    minimumResultsForSearch: 20,
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
        }

        $('#filter').submit(function() {
            var values = [];
            $("input:checked").each(function() {
                values.push($(this).val());
            });
            if (values != 0) {
                var x = values;

            } else {
                var x = ['kosong']
            }
            console.log(x);
            $('#showtable').DataTable().ajax.url(' /penjualan/customer/data/' + x).load();
            return false;
        });
    })
</script>
@stop