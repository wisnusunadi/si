@extends('adminlte.page')
@section('title', 'ERP')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-lg-6 col-md-4 col-sm-4">
                <h1 class="m-0  text-dark">Tambah Karyawan Masuk</h1>
            </div><!-- /.col -->
            <div class="col-lg-6 col-md-8 col-sm-8">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('kesehatan.dashboard') }}">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="\karyawan\masuk">Karyawan Masuk</a></li>
                    <li class="breadcrumb-item active">Tambah Karyawan Masuk</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@stop

@section('adminlte_css')
    <style>
        .hide {
            display: none !important
        }

        .removeboxshadow {
            box-shadow: none;
            border: 1px;
        }

        .bg-color {
            background-color: #e8fafc;
        }

        @media screen and (min-width: 993px) {
            .labelket {
                text-align: right;
            }

            section {
                font-size: 14px;
            }

            .btn {
                font-size: 14px;
            }
        }

        @media screen and (max-width: 992px) {
            .labelket {
                text-align: left;
            }

            section {
                font-size: 12px;
            }

            .btn {
                font-size: 12px;
            }
        }

        div.ui-tooltip {
            max-width: 400px;
        }
    </style>
@stop
@section('content')
    <section class="content-header">
        <div class="container-fluid">
        </div>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-lg-12">
                <div class="col-lg-12">
                    <form action="/karyawan/masuk/aksi_tambah" method="post" id="formkaryawanmasuk">
                        {{ csrf_field() }}
                        <div class="card">
                            <div class="card-header card-primary card-outline">
                                <h6 class="card-title">Detail Umum</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-horizontal">
                                    <div class="form-group row">
                                        <label for="tanggal" class="col-sm-5 col-form-label" style="text-align:right;">Tgl
                                            Pemeriksaan</label>
                                        <div class="col-sm-2">
                                            <input type="date"
                                                class="form-control @error('tgl_cek') is-invalid @enderror" name="tgl"
                                                value="{{ old('tgl_cek') }}" max="{{ date('Y-m-d') }}"
                                                placeholder="Analisa pemeriksaan">
                                        </div>
                                        <span role="alert" id="no_seri-msg"></span>
                                    </div>
                                    <div class="form-group row">
                                        <label for="no_pemeriksaan" class="col-sm-5 col-form-label"
                                            style="text-align:right;">Nama</label>
                                        <div class="col-sm-7">
                                            <select type="text"
                                                class="form-control @error('karyawan_id') is-invalid @enderror select2"
                                                name="karyawan_id" id="karyawan_id" style="width:45%;">
                                                <option value="NULL">Pilih Karyawan</option>
                                                @foreach ($karyawan as $k)
                                                    <option value="{{ $k->id }}">{{ $k->nama }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('karyawan_id'))
                                                <div class="text-danger">
                                                    {{ $errors->first('karyawan_id') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="no_pemeriksaan" class="col-sm-5 col-form-label"
                                            style="text-align:right;">Pemeriksa</label>
                                        <div class="col-sm-7">
                                            <select type="text"
                                                class="form-control @error('pemeriksa_id') is-invalid @enderror select2"
                                                name="pemeriksa_id" id="pemeriksa_id" style="width:45%;">

                                                @foreach ($pengecek as $p)
                                                    <option value="{{ $p->id }}">{{ $p->nama }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('karyawan_id'))
                                                <div class="text-danger">
                                                    {{ $errors->first('karyawan_id') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header card-outline card-primary">
                                <h6 class="card-title">Keterangan Tidak Masuk</h6>
                            </div>
                            <div class="card-body">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-horizontal">
                                                <div class="form-group row">
                                                    <label for="kondisi" class="col-sm-5 col-form-label"
                                                        style="text-align:right;">Alasan tidak masuk</label>
                                                    <div class="col-sm-7 col-form-label">
                                                        <div class="icheck-success d-inline col-sm-4">
                                                            <input type="radio" name="alasan" value="Cuti">
                                                            <label for="no">
                                                                Cuti
                                                            </label>
                                                        </div>
                                                        <div class="icheck-warning d-inline col-sm-4">
                                                            <input type="radio" name="alasan" value="Ijin">
                                                            <label for="sample">
                                                                Ijin
                                                            </label>
                                                        </div>
                                                        <div class="icheck-warning d-inline col-sm-4">
                                                            <input type="radio" name="alasan" value="Sakit">
                                                            <label for="sample">
                                                                Sakit
                                                            </label>
                                                        </div>
                                                        <div class="icheck-warning d-inline col-sm-4">
                                                            <input type="radio" name="alasan" value="Perjalanan Dinas">
                                                            <label for="sample">
                                                                Perjalanan Dinas
                                                            </label>
                                                        </div>
                                                        <span class="invalid-feedback" role="alert"
                                                            id="kondisi-msg"></span>
                                                    </div>
                                                </div>

                                                <div id="ijin">
                                                    <div class="form-group row">
                                                        <label for="tanggal" class="col-sm-5 col-form-label"
                                                            style="text-align:right;">Catatan </label>
                                                        <div class="col-sm-7">
                                                            <textarea type="text" class="form-control @error('keterangan') is-invalid @enderror" id="keterangan"
                                                                value="{{ old('keterangan') }}" placeholder="Catatan" style="width:45%;" name="keterangan"></textarea>
                                                        </div>
                                                        <span role="alert" id="no_seri-msg"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <span class="float-left"><a class="btn btn-danger"
                                        href="/karyawan/masuk">Batal</a></span>
                                <span class="float-right"><button class="btn btn-primary" id="button_tambah"
                                        type="submit">Tambah Data</button></span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('adminlte_js')
    <script>
        $('.select2').select2();
        $(document).on('submit', '#formkaryawanmasuk', function(e) {
            e.preventDefault();
            var action = $(this).attr('action');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: action,
                data: $(this).serialize(),

                dataType: 'JSON',
                success: function(response) {
                    swal.fire(
                        'Berhasil',
                        'Data Berhasil Ditambahkan',
                        'success'
                    ).then(function() {
                        window.location.href = '/karyawan/masuk/tambah';
                    });
                    // console.log(response)
                },
                error: function(xhr, status, error, response) {
                    // console.log(response)
                    swal.fire(
                        'Gagal',
                        'Cek Form Kembali',
                        'error'
                    );
                }
            });
        });
    </script>

@stop
