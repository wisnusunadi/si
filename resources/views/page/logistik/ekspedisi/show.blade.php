@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Ekspedisi</h1>
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
                                    <a href="{{route('logistik.ekspedisi.create')}}"><button class="btn btn-outline-info">
                                            <i class="fas fa-plus"></i> Tambah
                                        </button></a>
                                </span>
                                <span class="dropdown float-right filter">
                                    <button class="btn btn-outline-secondary dropdown-toggle " type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="filterpenjualan">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="filterpenjualan">
                                        <form class="px-4 py-3" style="white-space:nowrap;" id="filter">
                                            <div class="dropdown-header">
                                                <label for="jenis_penjualan" class="text-muted">Jalur</label>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="darat" id="status1" name="jalur" />
                                                    <label class="form-check-label" for="status1">
                                                        Darat
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="laut" id="status2" name="jalur" />
                                                    <label class="form-check-label" for="status2">
                                                        Laut
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="udara" id="status3" name="jalur" />
                                                    <label class="form-check-label" for="status3">
                                                        Udara
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="lain" id="status4" name="jalur" />
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
                                                    <input type="radio" class="form-check-input" id="dropdownStatus3" value="0" name='jurusan' />
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
                                            <tr>
                                                <td>1</td>
                                                <td>JNE</td>
                                                <td>Jl Jaksa Agung Suprapto No. 15 Banyuurip, Surabaya</td>
                                                <td>jne-banyuurip@gmail.com</td>
                                                <td>03179026798</td>
                                                <td><span class="badge green-text">Darat</span></td>
                                                <td>Jawa Timur</td>
                                                <td>-</td>
                                                <td>
                                                    <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <a href="{{route('logistik.ekspedisi.detail', ['id' => '1'])}}">
                                                            <button class="dropdown-item" type="button">
                                                                <i class="fas fa-search"></i>
                                                                Detail
                                                            </button>
                                                        </a>
                                                        <a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr="" data-id="">
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
                                                <td>Safari Dharma Raya</td>
                                                <td>Jl Arjuno No. 68 Surabaya</td>
                                                <td>safarilkargosub@gmail.com</td>
                                                <td>03189821583</td>
                                                <td><span class="badge blue-text">Laut</span></td>
                                                <td>NTT, NTB</td>
                                                <td>-</td>
                                                <td>
                                                    <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <a href="{{route('logistik.ekspedisi.detail', ['id' => '1'])}}">
                                                            <button class="dropdown-item" type="button">
                                                                <i class="fas fa-search"></i>
                                                                Detail
                                                            </button>
                                                        </a>
                                                        <a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr="" data-id="">
                                                            <button class="dropdown-item" type="button">
                                                                <i class="fas fa-pencil-alt"></i>
                                                                Edit
                                                            </button>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>KAI8</td>
                                                <td>Jl Pasar Turi No. 9 Pasar Turi, Surabaya</td>
                                                <td>kai8pasarturi@gmail.com</td>
                                                <td>03189829089</td>
                                                <td><span class="badge purple-text">Lain</span></td>
                                                <td>Jawa Barat</td>
                                                <td>-</td>
                                                <td>
                                                    <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <a href="{{route('logistik.ekspedisi.detail', ['id' => '1'])}}">
                                                            <button class="dropdown-item" type="button">
                                                                <i class="fas fa-search"></i>
                                                                Detail
                                                            </button>
                                                        </a>
                                                        <a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr="" data-id="">
                                                            <button class="dropdown-item" type="button">
                                                                <i class="fas fa-pencil-alt"></i>
                                                                Edit
                                                            </button>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>Garuda Kargo</td>
                                                <td>Jl Pasar Turi No. 9 Pasar Turi, Surabaya</td>
                                                <td>kai8pasarturi@gmail.com</td>
                                                <td>03189829089</td>
                                                <td><span class="badge orange-text">Udara</span></td>
                                                <td>Padang</td>
                                                <td>-</td>
                                                <td>
                                                    <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <a href="{{route('logistik.ekspedisi.detail', ['id' => '1'])}}">
                                                            <button class="dropdown-item" type="button">
                                                                <i class="fas fa-search"></i>
                                                                Detail
                                                            </button>
                                                        </a>
                                                        <a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr="" data-id="">
                                                            <button class="dropdown-item" type="button">
                                                                <i class="fas fa-pencil-alt"></i>
                                                                Edit
                                                            </button>
                                                        </a>
                                                    </div>
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
</div>
@stop

@section('adminlte_js')
<script>
    $(function() {
        $('#showtable').DataTable();

        function eks() {
            var showtable = $('#showtable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    'url': '/api/customer/data/' + 0,

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
                        data: 'npwp',
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
        }



        $(document).on('click', '.editmodal', function(event) {
            event.preventDefault();
            var href = $(this).attr('data-attr');
            var id = $(this).data('id');
            $.ajax({
                url: "/logistik/ekspedisi/edit/1",
                beforeSend: function() {
                    $('#loader').show();
                },
                // return the result
                success: function(result) {

                    $('#editmodal').modal("show");
                    $('#edit').html(result).show();
                    console.log(id);
                    // $("#editform").attr("action", href);
                    provinsi();
                    kota_kabupaten();

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
            $(".kota_kabupaten").val(null).trigger('change');
            if ($(this).val() != "") {
                if ($(this).val() == "provinsi") {
                    $('#provinsi_select').removeClass('hide');
                    $('#kota_kabupaten_select').addClass('hide');
                } else if ($(this).val() == "kota_kabupaten") {
                    $('#provinsi_select').addClass('hide');
                    $('#kota_kabupaten_select').removeClass('hide');
                } else if ($(this).val() == "indonesia") {
                    $('#provinsi_select').addClass('hide');
                    $('#kota_kabupaten_select').addClass('hide');
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
        }

        function kota_kabupaten() {
            $('.kota_kabupaten').select2({
                ajax: {
                    multiple: true,
                    minimumResultsForSearch: 20,
                    placeholder: "Pilih Kota Kabupaten",
                    dataType: 'json',
                    theme: "bootstrap",
                    delay: 250,
                    type: 'GET',
                    url: '/api/kota_kabupaten/select',
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
                                    text: obj.text,
                                    children: $.map(obj.children, function(objs) {
                                        return {
                                            id: objs.id,
                                            text: objs.text
                                        }
                                    })
                                };
                            })
                        };
                    },
                }
            });
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
            $('#showtable').DataTable().ajax.url(' /api/customer/data/' + x).load();
            return false;
        });
    })
</script>
@stop