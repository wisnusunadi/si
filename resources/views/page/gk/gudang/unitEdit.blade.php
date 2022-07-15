@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
<style>
    .nomor-so {
        background-color: #717FE1;
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 18px
    }

    .nomor-akn {
        background-color: #DF7458;
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 18px
    }

    .nomor-po {
        background-color: #85D296;
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 18px
    }

    .instansi {
        background-color: #36425E;
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 18px
    }
    .sudah_ditransfer {
        float: left;
        width: auto;
        padding: 5px;
        margin-top: 5px;
        border: 1px solid #AEE1FC;
        background-color: #AEE1FC;
        color: #5170FD;
        font-size: 14px;
        border-radius: 6px;
    }

    .belum_diterima {
        float: left;
        width: auto;
        padding: 5px;
        margin-top: 5px;
        border: 1px solid #FFE2E5;
        background-color: #FFE2E5;
        color: #F7616B;
        font-size: 14px;
        border-radius: 6px;
    }

    .sudah_diterima {
        float: left;
        width: auto;
        padding: 5px;
        margin-top: 5px;
        border: 1px solid #C9F7F5;
        background-color: #C9F7F5;
        color: #1CC7CD;
        font-size: 14px;
        border-radius: 6px;
    }

</style>
<input type="hidden" name="" id="auth" value="{{ Auth::user()->divisi_id }}">

<div class="row">
    <div class="col-sm-1">
        {{-- <a href="javascript:;" onclick = "history.back()">Redirect back to Page 1</a> --}}
        {{-- <a href="javascript:history.back()"><i class="fas fa-arrow-left"></i></a> --}}
        <button class="btn btn-secondary btnBack" type="button"><i class="fas fa-arrow-left"></i></button>
    </div>
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    @foreach ($header1 as $h)
                    <div class="col-sm">
                        <div class="row">
                            {{-- col --}}
                            <input type="hidden" name="" id="id" value="{{ $h->gbj_id }}">
                            <div class="col"> <label for="">Kode Unit</label>
                                <div class="card nomor-so">
                                    <div class="card-body">
                                        -
                                    </div>
                                </div>
                            </div>
                            {{-- col --}}
                            <div class="col"> <label for="">Nama Unit</label>
                                <div class="card nomor-akn">
                                    <div class="card-body">
                                        {{ $h->nama }} {{ $h->variasi}}
                                    </div>
                                </div>
                            </div>
                            {{-- col --}}
                            <div class="col"> <label for="">Jumlah</label>
                                <div class="card nomor-po">
                                    <div class="card-body">
                                        {{ $h->jml }} {{ $h->satuan}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table_edit_sparepart">
                    <thead class="thead-light">
                        <tr>
                            <th>No Seri</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>WESRUF9835734958</td>
                            <td><span class="badge badge-warning">Sudah Ditransfer</span></td>
                            <td><button class="btn btn-outline-success lihatData"><i class="fa fa-eye"></i></button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nomor Seri -->
<div class="modal fade seeDetail" id="" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col"> <label for="">No Seri</label>
                                <div class="card nomor-so">
                                    <div class="card-body" id="nose">
                                        89798797856456
                                    </div>
                                </div>
                            </div>
                            {{-- col --}}
                            <div class="col"> <label for="">Status</label>
                                <div class="card nomor-akn">
                                    <div class="card-body">
                                        <p class="card-text" id="in">Sudah Ditransfer</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered tableUnit">
                            <thead>
                                <tr>
                                    <th colspan="2" class="text-center">Tanggal</th>
                                    <th colspan="2" class="text-center">Tujuan</th>
                                    <th rowspan="2">Kerusakan</th>
                                    <th rowspan="2">Perbaikan</th>
                                    <th rowspan="2">Aksi</th>
                                </tr>
                                <tr>
                                    <th>Tanggal Masuk</th>
                                    <th>Tanggal Keluar</th>
                                    <th>Dari</th>
                                    <th>Ke</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>22-05-2022</td>
                                    <td>23-05-2022</td>
                                    <td>Gudang Penjualan</td>
                                    <td>After Sales</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><button class="btn btn-outline-info editData"><i class="fa fa-edit"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Edit --}}
<div class="modal fade editDetail" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdrop" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <label for="">No Seri</label>
                        <div class="card nomor-so">
                            <div class="card-body" id="nose">89798797856456</div>
                        </div>
                    </div>
                    <div class="col">
                        <label for="">Tanggal Masuk & Keluar</label>
                        <div class="card-group">
                            <div class="card nomor-po">
                                <div class="card-body" id="tgl_in">23-05-2022</div>
                            </div>
                            <div class="card nomor-po">
                                <div class="card-body" id="tgl_out">24-05-2022</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="">Dari</label>
                        <div class="card nomor-akn">
                            <div class="card-body" id="from">Gudang Penjualan</div>
                        </div>
                    </div>
                    <div class="col">
                        <label for="">Ke</label>
                        <div class="card instansi">
                            <div class="card-body" id="to">After Sales</div>
                        </div>
                    </div>
                </div>
            </div>
          <div class="card-body">
            <label for="">Kerusakan</label>
            <textarea name="" id="kerusakan" cols="30" rows="5" class="form-control"></textarea>
            <label for="">Perbaikan</label>
            <textarea name="" id="perbaikan" cols="30" rows="5" class="form-control"></textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
        <button type="button" class="btn btn-primary buttonSimpan">Simpan</button>
      </div>
    </div>
  </div>
</div>

@stop
@section('adminlte_js')
<script>
    // disable button back browser
    window.history.pushState(null, "", window.location.href);
    window.onpopstate = function() {
        window.history.pushState(null, "", window.location.href);
    };

    $(document).on('click', '.lihatData', function () {
        $('.seeDetail').modal('show');
    })

    $(document).on('click', '.editData', function () {
        $('.editDetail').modal('show');
    });

    $(document).on('click', '.buttonSimpan', function () {
        let nomorseri = $('#nose').text();
        let tgl_in = $('#tgl_in').text();
        let tgl_out = new Date();
        let kerusakan = $('#kerusakan').val();
        let perbaikan = $('#perbaikan').val();
        let data = {
            nomorseri: nomorseri.trim(),
            tgl_in: tgl_in.trim(),
            tgl_out: tgl_out,
            kerusakan: kerusakan,
            perbaikan: perbaikan
        }
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Data berhasil disimpan!',
            showConfirmButton: false,
            timer: 1500
        })
        console.log(data);
    });
</script>
@stop
