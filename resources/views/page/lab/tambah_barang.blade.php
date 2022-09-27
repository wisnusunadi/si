@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Tambah Brang Uji</h1>
@stop

@section('content')
<style>
.bc-success{
    background-color:rgba(40, 167, 69, 0.2);
}
</style>

<div class="container-fluid">

    <div class="container p-3 bg-white">

        <form action="store_tambahbarang" method="post" enctype="multipart/form-data">
        @csrf

        <!-- card informasi umum alat -->
        <div class="card border-primary border-top-w3 shadow">
            <div class="card-body">

                <div class="row">
                    <h3 class="card-title">Informasi Umum Alat Uji</h3>
                </div>

                @error('klasifikasi')
                    <div class="row">
                        <div class="col"></div>
                        <div class="col">
                            <div class="alert bc-danger text-danger border border-danger py-0 mb-0 mt-1">{{ $message }}</div>
                        </div>
                    </div>
                @enderror
                <div class="row mb-2">
                    <div class="col"><span class="float-right">Klasifikasi</span></div>
                    <div class="col">
                        <select class="form-control form-control-sm" name="klasifikasi" id="selectKlasifikasi">
                            <option value="" disabled hidden {{ (old('klasifikasi') == null ? 'SELECTED' : '') }}>Pilih Klasifikasi</option>
                            @foreach($klasifikasi as $k)
                            <option value="{{ $k->id_klasifikasi }}" {{ (old('klasifikasi') == $k->id_klasifikasi ? "SELECTED" : '') }}>{{ $k->nama_klasifikasi }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                @error('nama')
                    <div class="row">
                        <div class="col"></div>
                        <div class="col">
                            <div class="alert bc-danger text-danger border border-danger py-0 mb-0 mt-1">{{ $message }}</div>
                        </div>
                    </div>
                @enderror
                <div class="row mb-2">
                    <div class="col"><span class="float-right">Nama Alat Uji</span></div>
                    <div class="col">
                        <select class="form-control form-control-sm" name="nama" id="selectNama">
                            <option value="" disabled hidden {{ (old('nama') == null ? 'SELECTED' : '') }}>Pilih Nama Alat</option>
                            @foreach($nama as $n)
                            <option value="{{ $n->id_alatuji }}" {{ (old('nama') == $n->id_alatuji ? "SELECTED" : '') }}>{{ $n->nm_alatuji }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>
        </div>

        <!-- card informasi detail alat -->
        <div class="card border-primary border-top-w3 shadow">
            <div class="card-body">

                <div class="row">
                    <h3 class="card-title">Informasi Detail Alat Uji</h3>
                </div>

                @error('merk')
                    <div class="row">
                        <div class="col"></div>
                        <div class="col">
                            <div class="alert bc-danger text-danger border border-danger py-0 mb-0 mt-1">{{ $message }}</div>
                        </div>
                    </div>
                @enderror
                @error('merkbaru')
                    <div class="row">
                        <div class="col"></div>
                        <div class="col">
                            <div class="alert bc-danger text-danger border border-danger py-0 mb-0 mt-1">{{ $message }}</div>
                        </div>
                    </div>
                @enderror
                <div class="row mb-2">
                    <div class="col"><span class="float-right">Merk Alat Uji</span></div>
                    <div class="col">
                        <div class="row">
                            <div class="col-auto">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="checkmerk" id="merkada" value="ada" {{ old('checkmerk') == null ? 'checked' : (old('checkmerk') == 'ada' ? 'checked' : '') }}>
                                    <label class="form-check-label" for="merkada">Merk Ada</label>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="checkmerk" id="merktidak" value="tidak" {{ old('checkmerk') == 'tidak' ? 'checked' : ''}}>
                                    <label class="form-check-label" for="merktidak">Merk Tidak Ada</label>
                                </div>
                            </div>
                        </div>

                        <select class="form-control form-control-sm" name="merk" id="selectMerk">
                            <option value="" disabled hidden {{ (old('merk') == null ? 'SELECTED' : '') }} >Pilih merk</option>
                            <option value="0">Tidak di ketahui</option>
                            @foreach($merk as $sp)
                            <option value="{{ $sp->id_merk }}" {{ (old('merk') == $sp->id_merk ? "SELECTED" : '') }}>{{ $sp->nama_merk }}</option>
                            @endforeach
                        </select>

                        <input class="form-control mt-2" type="text" name="merkbaru" placeholder="Merk Baru" value="{{ old('merkbaru') }}" id="merkbaru">
                        <small><small class="text-muted">Nama Merk Case Sensitive</small></small>
                    </div>
                </div>

                @error('serial_number')
                    <div class="row">
                        <div class="col"></div>
                        <div class="col">
                            <div class="alert bc-danger text-danger border border-danger py-0 mb-0 mt-1">{{ $message }}</div>
                        </div>
                    </div>
                @enderror
                <div class="row mb-2">
                    <div class="col"><span class="float-right">Serial Number</span></div>
                    <div class="col">
                        <input class="form-control" type="text" name="serial_number" value="{{ old('serial_number') }}" id="">
                    </div>
                </div>

                @error('tanggal_masuk')
                    <div class="row">
                        <div class="col"></div>
                        <div class="col">
                            <div class="alert bc-danger text-danger border border-danger py-0 mb-0 mt-1">{{ $message }}</div>
                        </div>
                    </div>
                @enderror
                <div class="row mb-2">
                    <div class="col"><span class="float-right">Tanggal Masuk</span></div>
                    <div class="col">
                        <input class="form-control" type="date" name="tanggal_masuk" value="{{ old('tanggal_masuk') }}" id="">
                    </div>
                </div>

                @error('kondisi')
                    <div class="row">
                        <div class="col"></div>
                        <div class="col">
                            <div class="alert bc-danger text-danger border border-danger py-0 mb-0 mt-1">{{ $message }}</div>
                        </div>
                    </div>
                @enderror
                <div class="row mb-2">
                    <div class="col"><span class="float-right">Kondisi</span></div>
                    <div class="col">
                        <div class="row">
                            <div class="col-auto">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kondisi" id="ok" value="9" {{ old('kondisi') == null ? 'checked' : (old('kondisi') == 9 ? 'checked' : '') }}>
                                    <label class="form-check-label" for="ok">OK</label>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kondisi" id="not" value="10" {{ old('kondisi') == 10 ? 'checked' : ''}}>
                                    <label class="form-check-label" for="not">NOT OK</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @error('lokasi')
                    <div class="row">
                        <div class="col"></div>
                        <div class="col">
                            <div class="alert bc-danger text-danger border border-danger py-0 mb-0 mt-1">{{ $message }}</div>
                        </div>
                    </div>
                @enderror
                <div class="row mb-2">
                    <div class="col"><span class="float-right">Lokasi</span></div>
                    <div class="col">
                        <select class="form-control form-control-sm" name="lokasi" id="selectLokasi">
                            <option value="" disabled hidden {{ (old('lokasi') == null ? 'SELECTED' : '') }} >Pilih Lokasi Penyimpanan</option>
                            @foreach($lokasi as $l)
                            <option value="{{ $l->id }}" {{ (old('lokasi') == $l->id ? "SELECTED" : '') }} >{{ $l->ruang }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>
        </div>

        <!-- card dokumen penunjang -->
        <div class="card border-primary border-top-w3 shadow">
            <div class="card-body">
                
                <div class="row">
                    <h3 class="card-title">Dokumen Penunjang</h3>
                </div>

                @error('sert_kalibrasi')
                    <div class="row">
                        <div class="col"></div>
                        <div class="col">
                            <div class="alert bc-danger text-danger border border-danger py-0 mb-0 mt-1">{{ $message }}</div>
                        </div>
                    </div>
                @enderror
                <div class="row mb-2">
                    <div class="col"><span class="float-right">Sertifikat Kalibrasi</span></div>
                    <div class="col">
                        <div class="form-group">
                            <input type="file" name="sert_kalibrasi" class="form-control-file">
                            <small><small class="text-muted">Dokumen Berupa Gambar berukuran maksimal 2 MB</small></small>
                        </div>
                    </div>
                </div>

                <!-- <div class="row mb-2">
                    <div class="col"><span class="float-right">Barcode</span></div>
                    <div class="col">placeholder tempat barcode</div>
                </div> -->

            </div>
        </div>

        <div class="card-body">
            <div class="row float-right">
                <div class="col-auto">
                    <a href="/alatuji" class="btn btn-danger float-right">Batal</a>
                </div>
                <div class="col-auto">
                    <input type="submit" value="Simpan" class="btn btn-primary float-right">
                </div>
            </div>
        </div>

        </form>

    </div>

</div>
@stop
@section('adminlte_js')
<script>
    $(document).ready(function() {
        $('#selectKlasifikasi').select2();
        $('#selectNama').select2();
        $('#selectMerk').select2();
    });

    @if(old('checkmerk') == null OR old('checkmerk') == 'ada')
    $( "#merkbaru" ).prop( "disabled", true );
    $( "#selectMerk" ).prop( "disabled", false );
    @endif
    @if(old('checkmerk') == 'tidak')
    $( "#merkbaru" ).prop( "disabled", false );
    $( "#selectMerk" ).prop( "disabled", true );
    @endif

    $("input[name$='checkmerk']").click(function(){
        if($("#merkada").is(":checked"))
        {
            $( "#selectMerk" ).prop( "disabled", false );
            $( "#merkbaru" ).prop( "disabled", true );
        }
        if($("#merktidak").is(":checked"))
        {
            $( "#selectMerk" ).prop( "disabled", true );
            $( "#merkbaru" ).prop( "disabled", false );
        }
    });

    @if(session()->has('success'))
        Swal.fire({
            title: 'Berhasil',
            text: 'Data Berhasil di Masukkan',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    @endif
</script>

@endsection