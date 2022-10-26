@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-lg-6 col-md-4 col-sm-4">

            </div><!-- /.col -->
            <div class="col-lg-6 col-md-8 col-sm-8">
                <ol class="breadcrumb float-sm-right">
                    @if (Auth::user()->divisi_id == '26')
                        <li class="breadcrumb-item"><a href="{{ route('penjualan.dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Ubah Password</li>
                    @endif
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
    <section class="content">
        <div class="container-fluid">
            <form id="update_pwd">
                <input type="text" name="user_id" value={{ Auth::user()->id }}>
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-10 co-md-12">
                        <div class="row">
                            <div class="col-lg-12 col-md-12">

                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="card card-outline card-info">
                                            <div class="card-header">
                                                <h6 class="card-title">Ubah Password</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group row">
                                                    <label for="nama_produk"
                                                        class="col-lg-4 col-md-12 col-form-label labelket">Password
                                                        Lama</label>
                                                    <div class="col-lg-6 col-md-12">
                                                        <input type="text"
                                                            class="form-control col-form-label @error('pwd_lama') is-invalid @enderror"
                                                            placeholder="Password Lama" id="pwd_lama" name="pwd_lama" />
                                                        <div class="invalid-feedback" id="msgpwd_lama">
                                                            @if ($errors->has('pwd_lama'))
                                                                {{ $errors->first('pwd_lama') }}
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="pwd_baru"
                                                        class="col-lg-4 col-md-12 col-form-label labelket">Password
                                                        Baru</label>
                                                    <div class="col-lg-5 col-md-12">
                                                        <input type="text"
                                                            class="form-control col-form-label @error('pwd_baru') is-invalid @enderror"
                                                            value="" placeholder="Password Baru" id="pwd_baru"
                                                            name="pwd_baru" />
                                                        <div class="invalid-feedback" id="msgpwd_baru">
                                                            @if ($errors->has('pwd_baru'))
                                                                {{ $errors->first('pwd_baru') }}
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="konfirmasi_pwd_baru"
                                                        class="col-lg-4 col-md-12 col-form-label labelket">Konfirmasi
                                                        Password
                                                        Baru</label>
                                                    <div class="col-lg-5 col-md-12">
                                                        <input type="text"
                                                            class="form-control col-form-label @error('konfirmasi_pwd_baru') is-invalid @enderror"
                                                            value="" placeholder="Konfirmasi Password Baru"
                                                            id="konfirmasi_pwd_baru" name="konfirmasi_pwd_baru" />
                                                        <div class="invalid-feedback" id="msgkonfirmasi_pwd_baru">
                                                            @if ($errors->has('konfirmasi_pwd_baru'))
                                                                {{ $errors->first('konfirmasi_pwd_baru') }}
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>




                                </div>
                                <div class="form-group row">
                                    <span class="float-left col-6">
                                        <a type="button" class="btn btn-danger" href="/home">
                                            Batal
                                        </a>
                                    </span>

                                    <span class="col-6">
                                        <button type="submit" class="btn btn-info float-right" id="btntambah">
                                            Tambah
                                        </button>
                                    </span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@stop

@section('adminlte_js')

    <script>
        $(function() {

            $(document).on('submit', '#update_pwd', function(e) {
                swal.fire(
                    'Gagal',
                    'Lengkapi form',
                    'warning'
                );
            });
        });
    </script>
@stop
