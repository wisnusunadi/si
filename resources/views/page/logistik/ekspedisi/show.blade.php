@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0  text-dark">Ekspedisi</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                @if(Auth::user()->divisi_id == "15")
                <li class="breadcrumb-item"><a href="{{route('logistik.dashboard')}}">Beranda</a></li>
                @elseif(Auth::user()->divisi_id == "2")
                <li class="breadcrumb-item"><a href="{{route('direksi.dashboard')}}">Beranda</a></li>
                @endif
                <li class="breadcrumb-item active">Ekspedisi</li>

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

    .hide {
        display: none !important;
    }

    .laut-text {
        background-color: #E1EBF2;
        color: #5F7A90;
        padding: 6px;
        border-radius: 0.3rem;
    }

    .udara-text {
        background-color: #FFE6C9;
        color: #EA8B1B;
        padding: 6px;
        border-radius: 0.3rem;
    }

    .darat-text {
        background-color: rgba(69, 102, 0, 0.2);
        color: #456600;
        padding: 6px;
        border-radius: 0.3rem;
    }

    .lain-text {
        background-color: #E9DDE5;
        color: #7D6378;
        padding: 6px;
        border-radius: 0.3rem;
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
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row" style="margin-bottom:10px;">
                            <div class="col-12">
                                @if(Auth::user()->divisi_id == "15")
                                <span class="float-right filter">
                                    <a href="{{route('logistik.ekspedisi.create')}}"><button class="btn btn-outline-info">
                                            <i class="fas fa-plus"></i> Tambah
                                        </button></a>
                                </span>
                                @endif
                                <span class="dropdown float-right filter">
                                    <button class="btn btn-outline-secondary dropdown-toggle" data-flip="false" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="filterpenjualan">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="filterpenjualan" style="position:relative;">
                                        <form class="px-4 py-3" style="white-space:nowrap;" id="filter">
                                            <div class="dropdown-header">
                                                <label for="jenis_penjualan" class="text-muted">Jalur</label>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="darat" id="jalur" name="jalur" />
                                                    <label class="form-check-label" for="status1">
                                                        Darat
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="laut" id="jalur" name="jalur" />
                                                    <label class="form-check-label" for="status2">
                                                        Laut
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="udara" id="jalur" name="jalur" />
                                                    <label class="form-check-label" for="status3">
                                                        Udara
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="lain" id="jalur" name="jalur" />
                                                    <label class="form-check-label" for="status4">
                                                        Lain
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="dropdown-header">
                                                <label for="jenis_penjualan" class="text-muted">Jurusan</label>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input type="radio" class="form-check-input" id="dropdownStatus1" value="2" name='jurusan' />
                                                    <label class="form-check-label" for="dropdownStatus1">
                                                        Jawa
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input type="radio" class="form-check-input" id="dropdownStatus2" value="1" name='jurusan' />
                                                    <label class="form-check-label" for="dropdownStatus2">
                                                        Luar Jawa
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input type="radio" class="form-check-input" id="dropdownStatus3" value="semua" name='jurusan' />
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
                                    <table class="table table-hover" id="showtable" style="width:100%; text-align:center;">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>Alamat</th>
                                                <th>Email</th>
                                                <th>Telp</th>
                                                <th>Jalur</th>
                                                <th>Jurusan</th>
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
</section>
@stop

@section('adminlte_js')
<script>
    $(function() {

        var showtable = $('#showtable').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                'url': '/logistik/ekspedisi/data/semua/semua',
                'dataType': 'json',
                'type': 'POST',
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
            }, {
                data: 'nama',
                className: 'nowrap-text minimizechar',
                orderable: true,
                searchable: true

            }, {
                data: 'alamat',
                className: 'nowrap-text minimizechar',
            }, {
                data: 'email',
                orderable: false,
                searchable: false
            }, {
                data: 'telp',
                orderable: false,
                searchable: false

            }, {
                data: 'via',
            }, {
                data: 'jurusan',
                orderable: false,
                searchable: false

            }, {
                data: 'ket',
                className: 'nowrap-text minimizechar',
                orderable: false,
                searchable: false

            }, {
                data: 'button',
                orderable: false,
                searchable: false

            }]
        });

        function checkvalue(g) {
            for (var i = 0; i < g.length; i++) {
                $(":checkbox").filter(function() {
                    return this.value == g[i];
                }).prop("checked", "true");
            }
        }


        function checkvalueprovinsi(k) {
            if (k != 35) {
                $('#provinsi_select').removeClass('hide');
                $("input[name=jurusan][value='provinsi']").prop("checked", true);
            } else {
                $("input[name=jurusan][value='indonesia']").prop("checked", true);
                $('#provinsi_select').addClass('hide');
            }
        }

        $(document).on('click', '.editmodal', function(event) {
            var k = "provinsi";
            var g = $(this).data().value;
            var h = $(this).data().provinsi;
            var h_str = h.toString();
            var jurusan_arr = new Array();
            var prov_arr = new Array();
            jurusan_arr = g.split(",");
            prov_arr = h_str.split(",");

            //console.log(prov_arr);
            event.preventDefault();
            var href = $(this).attr('data-attr');
            var id = $(this).data('id');
            $.ajax({
                url: "/logistik/ekspedisi/edit/" + id,
                beforeSend: function() {
                    $('#loader').show();
                },
                // return the result
                success: function(result) {

                    $('#editmodal').modal("show");
                    $('#edit').html(result).show();

                    // $("#editform").attr("action", href);
                    checkvalue(jurusan_arr);
                    checkvalueprovinsi(prov_arr);
                    provinsi();

                    // $('.provinsi').val(['1']).trigger('change');
                    // $(".provinsi").select2().select2('1');
                    // provinsi_selected(prov_arr);
                    // $('.provinsi').val([prov_arr]);
                    // $('.provinsi').trigger('change');
                    // $('.provinsi').select2().select2('val', prov_arr);

                    // $('.provinsi').select2('val', prov_arr);

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


        $(document).on('keyup change', 'input[name="nama_ekspedisi"]', function() {
            if ($(this).val() == "") {
                $("#msgnama_ekspedisi").text("Nama tidak boleh kosong");
                $('#nama_ekspedisi').addClass('is-invalid');
            } else if ($(this).val() != "") {
                $("#msgnama_ekspedisi").val("");
                $('#nama_ekspedisi').removeClass('is-invalid');
                if ($('#telepon').val() != "" && $('#alamat').val() != "" && $('input[type="radio"][name="jalur"]').val() != "" && $('#jurusan').val() != "") {
                    $("#btnsimpan").removeAttr("disabled");
                } else {
                    $("#btnsimpan").attr("disabled", true);
                }
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
                    $("#msgtelepon").text("");
                    $("#telepon").removeClass('is-invalid');
                    $("#btnsimpan").removeAttr('disabled');
                    if ($("#nama_ekspedisi").val() != "" && $("#alamat").val() != "" && $('input[type="radio"][name="jalur"]').val() != "" && $('#jurusan').val() != "") {
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
                if ($("#nama_ekspedisi").val() != "" && $("#telepon").val() != "" && $('input[type="radio"][name="jalur"]').val() != "" && $('#jurusan').val() != "") {
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

        $(document).on('change', 'input[type="radio"][name="jurusan"]', function() {
            $(".provinsi").val(null).trigger('change');

            if ($(this).val() != "") {
                if ($(this).val() == "provinsi") {
                    $('#provinsi_select').removeClass('hide');
                } else if ($(this).val() == "indonesia") {
                    $('#provinsi_select').addClass('hide');
                }
                $('#msgjurusan').text("");
                $('#jurusan').removeClass("is-invalid");
                if ($("#nama_ekspedisi").val() != "" && $("#telepon").val() != "" && $('input[type="checkbox"][name="jalur"]').val() != "" && $('#alamat').val() != "") {
                    $("#btntambah").removeAttr('disabled');
                } else {
                    $("#btntambah").attr('disabled', true);
                }
            } else {
                $('#msgjurusan').text("jurusan tidak boleh kosong");
                $('#jurusan').addClass("is-invalid");
                $("#btntambah").attr('disabled', true);
            }
        });

        $(document).on('keyup change', 'input[type="checkbox"][name="jalur"]', function() {
            if ($('input[type="checkbox"][name="jalur"]').val() != "") {
                $('#msgjalur').text("");
                $('input[type="checkbox"][name="jalur"]').removeClass("is-invalid");
                if ($("#nama_ekspedisi").val() != "" && $("#telepon").val() != "" && $('#jurusan').val() != "" && $('#alamat').val() != "") {
                    $("#btntambah").removeAttr('disabled');
                } else {
                    $("#btntambah").attr('disabled', true);
                }
            } else {
                $('#msgjalur').text("Jalur tidak boleh kosong");
                $('#jalur').addClass("is-invalid");
                $("#btntambah").attr('disabled', true);
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
                    if ($("#nama_ekspedisi").val() != "" && $("#telepon").val() != "" && $("#alamat").val() != "" && $('input[type="radio"][name="jalur"]').val() != "" && $('#jurusan').val() != "") {
                        $("#btnsimpan").removeAttr('disabled');
                    }
                }
            } else {
                $('#msgemail').text("");
                $('#email').removeClass("is-invalid");
                if ($("#nama_ekspedisi").val() != "" && $("#telepon").val() != "" && $("#alamat").val() != "" && $('input[type="radio"][name="jalur"]').val() != "" && $('#jurusan').val() != "") {
                    $("#btnsimpan").removeAttr('disabled');
                }
            }
        })

        function provinsi() {
            $('.provinsi').select2({
                placeholder: "Pilih Provinsi",
                multiple: true,
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
            });
            $('.provinsi').val('1').trigger("change");
        }

        $('#filter').submit(function() {
            var jalur = [];
            var jurusan = [];

            $("input[name=jalur]:checked").each(function() {
                jalur.push($(this).val());
            });
            $("input[name=jurusan]:checked").each(function() {
                jurusan.push($(this).val());
            });
            if (jurusan != 0) {
                var y = jurusan;

            } else {
                var y = ['kosong']
            }
            if (jalur != 0) {
                var x = jalur;

            } else {
                var x = ['kosong']
            }
            console.log(x);
            console.log(y);
            $('#showtable').DataTable().ajax.url('/logistik/ekspedisi/data/' + x + '/' + y).load();

            return false;
        });
    })
</script>
@stop