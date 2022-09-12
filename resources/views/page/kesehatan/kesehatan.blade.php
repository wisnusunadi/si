@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0  text-dark">Kesehatan</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('kesehatan.dashboard') }}">Beranda</a></li>
                    <li class="breadcrumb-item active">Kesehatan Awal</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop
@section('adminlte_css')
<style>
    table { border-collapse: collapse; empty-cells: show; }

    td { position: relative; }

    .foo {
        border-radius: 50%;
        float: left;
        width: 10px;
        height: 10px;
        align-items: center !important;
    }

    tr.line-through td:not(:nth-last-child(-n+2)):before {
        content: " ";
        position: absolute;
        left: 0;
        top: 35%;
        border-bottom: 1px solid;
        width: 100%;
    }

    @media screen and (min-width: 1440px) {

        body {
            font-size: 14px;
        }

        #detailmodal {
            font-size: 14px;
        }

        .btn {
            font-size: 14px;
        }


    }

    @media screen and (max-width: 1439px) {
        body {
            font-size: 12px;
        }

        h4 {
            font-size: 20px;
        }

        #detailmodal {
            font-size: 12px;
        }

        .btn {
            font-size: 12px;
        }


    }



</style>
@stop


@section('content')
<div class="row">
  <div class="col-lg-12">
    @if(session()->has('success'))
    <div class="alert alert-success alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
      Data berhasil ditambahkan
    </div>
    @elseif(session()->has('error') || count($errors) > 0)
    <div class="alert alert-danger alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
      Data gagal ditambahkan
    </div>
    @endif
    <div class="card">
      <div class="card-body">
        <div class='table-responsive'>
          <table id="tabel" class="table table-hover styled-table table-striped">
            <thead style="text-align: center;">
              <tr>
                <th colspan="14">
                  <a href="/kesehatan/tambah" style="color: white;"><button type="button" class="btn btn-block btn-success btn-sm" style="width: 200px;"><i class="fas fa-plus"></i> &nbsp; Tambah</i></button></a>
                </th>
              </tr>
              <tr>
                <th>No</th>
                <th>Divisi</th>
                <th>Nama</th>
                <th>Umur</th>
                <th>Berat</th>
                <th>Tinggi</th>
                <th>Vaksin</th>
                <th>Buta warna</th>
                <th></th>
              </tr>
            </thead>
            <tbody style="text-align: center;">
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Modal Detail -->
<div class="modal fade  bd-example-modal-xl" id="berat_mod" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-xl" role="document">
    <div class="card-body">
      <form method="post" action="/kesehatan_harian_mingguan_tensi/aksi_ubah">
        {{ csrf_field() }}
        {{ method_field('PUT')}}
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel">
              <div class="data_detail_head"></div>
            </h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <div class="modal-body">
            <table class="table table-hover styled-table table-striped" width="100%" id="tabel_berat">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Tgl Cek</th>
                  <th>Berat</th>
                  <th>Lemak</th>
                  <th>Kandungan_air</th>
                  <th>Otot</th>
                  <th>Tulang</th>
                  <th>Kalori</th>
                  <th>Keterangan</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal Detail -->
<!-- Modal Detail -->
<div class="modal fade  bd-example-modal-lg" id="vaksin_mod" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="card-body">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">
            <div class="data_detail_head"></div>
          </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <table class="table table-hover styled-table table-striped" width="100%" id="tabel_detail">
            <thead>
              <tr>
                <th colspan="12">
                  <button type="button" class="btn btn-block btn-success btn-sm" style="width: 200px;" data-target="#tambah_mod" data-toggle="modal" data-dismiss="modal"><i class="fas fa-plus"></i> &nbsp;Tambah Data</i></button>
                </th>
              </tr>
              <tr>
                <th>No</th>
                <th>Tgl</th>
                <th>Dosis</th>
                <th>Tahap</th>
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
<!-- End Modal Detail -->
<!-- Modal Detail -->
<div class="modal fade  bd-example-modal-lg" id="tambah_mod" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">
          <div class="data_detail_head"></div>
        </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="data_detail">
          <div class="row">
            <div class="col-lg-12">
              <div class="col-lg-12">
                <form method="post" action="/kesehatan/vaksin/aksi_tambah/2">
                  @csrf
                  <div class="card">
                    <div class="card-header bg-success">
                      <div class="card-title"><i class="fas fa-plus-circle"></i>&nbsp;Tambah</div>
                    </div>
                    <div class="card-body">
                      <div class="col-lg-12">
                        <div class="row">
                          <div class="col-lg-12">
                            <div class="form-horizontal">
                              <input type="text" name="fk_karyawan_id" class="d-none form-control" id="fk_karyawan_id" readonly>
                              <table class="table table-bordered table-striped" id="tabel_vaksin">
                                <thead>
                                  <tr>
                                    <th>No</th>
                                    <th>Tgl Vaksin</th>
                                    <th>Dosis</th>
                                    <th>Vaksin ke </th>
                                    <th></th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td>1</td>
                                    <td>
                                      <input type="date" class="form-control date" name="date[]">
                                    </td>
                                    <td>
                                      <select class="form-control select2 dosis" name="dosis[]">
                                        <option value="">Pilih</option>
                                        <option value="Astrazeneca">Astrazeneca</option>
                                        <option value="Janssen">Janssen</option>
                                        <option value="Moderna">Moderna</option>
                                        <option value="Pfizer">Pfizer</option>
                                        <option value="Sinovac">Sinovac</option>
                                        <option value="Sinopharm">Sinopharm</option>

                                      </select>
                                    </td>
                                    <td>
                                      <select class="form-control select2 ket" name="ket[]">
                                        <option value="">Pilih</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                      </select>
                                    </td>
                                    <td>
                                      <button name="add" type="button" id="tambahitem_vaksin" class="btn btn-success"><i class="nav-icon fas fa-plus-circle"></i></button>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="card-footer">
                      <span class="float-right"><button class="btn btn-success rounded-pill" id="button_tambah" type="submit"><i class="fas fa-plus"></i>&nbsp;Tambah Data</button></span>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- End Modal Detail -->
<!-- Modal Detail -->
<div class="modal fade  bd-example-modal-lg" id="penyakit_mod" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="card-body">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">
            <div class="data_detail_head"></div>
          </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <table class="table table-hover styled-table table-striped" width="100%" id="tabel_detail_penyakit">
            <thead>
              <tr>
                <th colspan="12">
                  <button type="button" class="btn btn-block btn-success btn-sm" style="width: 200px;" data-target="#tambah_mod_penyakit" data-toggle="modal" data-dismiss="modal"><i class="fas fa-plus"></i> &nbsp;Tambah Data</i></button>
                </th>
              </tr>
              <tr>
                <th>No</th>
                <th>Nama Penyakit</th>
                <th>Jenis Penyakit</th>
                <th>Kriteria</th>
                <th>Keterangan</th>
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
<!-- End Modal Detail -->
<!-- Modal Detail -->
<div class="modal fade  bd-example-modal-xl" id="tambah_mod_penyakit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">
          <div class="data_detail_head"></div>
        </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="data_detail">
          <div class="row">
            <div class="col-lg-12">
              <div class="col-lg-12">
                <form method="post" action="/kesehatan/riwayat_penyakit/aksi_tambah">
                  @csrf
                  <div class="card">
                    <div class="card-header bg-success">
                      <div class="card-title"><i class="fas fa-plus-circle"></i>&nbsp;Tambah</div>
                    </div>
                    <div class="card-body">
                      <div class="col-lg-12">
                        <div class="row">
                          <div class="col-lg-12">
                            <div class="form-horizontal">
                              <input type="text" name="fk_karyawan_id" class="d-none form-control" id="fk_karyawan_id" readonly>
                              <table class="table table-bordered table-striped" id="tabel_penyakit">
                                <thead>
                                  <tr>
                                    <th>No</th>
                                    <th>Nama Penyakit</th>
                                    <th width="25%">Jenis Penyakit</th>
                                    <th>Keterangan</th>
                                    <th></th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td>1</td>
                                    <td>
                                      <input type="text" class="form-control d-none" name="fk_karyawan_id" id="id">
                                      <textarea type="text" class="form-control nama" name="nama[]"></textarea>
                                    </td>
                                    <td>
                                      <select class="form-control select2 jenis" style="width:100%" name="jenis[]">
                                        <option value="">Pilih jenis penyakit</option>
                                        <option value="Penyakit saat ini">Penyakit saat ini</option>
                                        <option value="Penyakit lama">Penyakit lama</option>
                                        <option value="Penyakit keluarga">Penyakit keluarga</option>
                                        <option value="Penyakit karena pekerjaan">Penyakit karena pekerjaan</option>
                                      </select>
                                    </td>
                                    <td>
                                      <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="kriteria[]" id="kriteria" value="1">
                                        <label class="form-check-label">Menular</label>
                                      </div>
                                      <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="kriteria[]" id="kriteria" value="0">
                                        <label class="form-check-label">Tidak menular</label>
                                      </div>
                                    </td>
                                    <td>
                                      <textarea type="text" class="form-control keterangan" name="keterangan[]" id="keterangan"></textarea>
                                    </td>
                                    <td>
                                      <button name="add" type="button" id="tambahitem_penyakit" class="btn btn-success"><i class="nav-icon fas fa-plus-circle"></i></button>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="card-footer">
                      <span class="float-right"><button class="btn btn-success rounded-pill" id="button_tambah" type="submit"><i class="fas fa-plus"></i>&nbsp;Tambah Data</button></span>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- End Modal Detail -->
@stop
@section('adminlte_js')
<script>
  $(function() {
    var tabel = $('#tabel').DataTable({
      processing: true,
      serverSide: true,
      language: {
        processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
      },
      ajax: {
        'url': '/kesehatan/data',
        'type': 'POST',
        'headers': {
          'X-CSRF-TOKEN': '{{csrf_token()}}'
        }
      },
      columns: [{
          data: 'DT_RowIndex',
          orderable: false,
          searchable: false
        },
        {
          data: 'divisi'
        },
        {
          data: 'nama',
          searchable: true
        },
        {
          data: 'umur'
        },
        {
          data: 'berat_kg'
        },
        {
          data: 'tinggi_cm'
        },
        {
          data: 'vaksin_detail'
        },
        {
          data: 'status_mata'
        },
        {
          data: 'detail'
        }
      ]
    });
    $('#tabel > tbody').on('click', '#vaksin_detail', function() {
      var rows = tabel.rows($(this).parents('tr')).data();
      $('.data_detail_head').html(
        'Riwayat Vaksin : ' + rows[0]['karyawan']['nama']
      );
      $('input[id="fk_karyawan_id"]').val(rows[0]['karyawan_id']);
      var y = $('#tabel_detail').DataTable({
        processing: true,
        destroy: true,
        serverSide: true,
        language: {
          processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
        },
        ajax: {
          'type': 'POST',
          'headers': {
            'X-CSRF-TOKEN': '{{csrf_token()}}'
          },
          'url': '/kesehatan/vaksin/' + rows[0]['karyawan_id'],
        },

        columns: [{
            data: 'DT_RowIndex',
            orderable: false,
            searchable: false
          },
          {
            data: 'tgl'
          },
          {
            data: 'dosis'
          },
          {
            data: 'tahap'
          },
        ],
      });
      $('#vaksin_mod').modal('show');
    })
    $('#tabel > tbody').on('click', '#berat', function() {
      var rows = tabel.rows($(this).parents('tr')).data();
      $('.data_detail_head').html(
        rows[0]['karyawan']['nama']
      );
      var x = rows[0]['karyawan_id'];
      var y = $('#tabel_berat').DataTable({
        processing: true,
        destroy: true,
        serverSide: true,
        language: {
          processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
        },
        ajax: {
          'type': 'POST',
          'headers': {
            'X-CSRF-TOKEN': '{{csrf_token()}}'
          },
          'url': '/kesehatan/bulanan/berat/detail/' + rows[0]['karyawan_id']
        },
        columns: [{
            data: 'DT_RowIndex',
            orderable: false,
            searchable: false
          },
          {
            data: 'tgl_cek'
          },
          {
            data: 'z'
          },
          {
            data: 'l'
          },
          {
            data: 'k'
          },
          {
            data: 'o'
          },
          {
            data: 't'
          },
          {
            data: 'ka'
          },
          {
            data: 'keterangan'
          },
        ],
      });
      $('#berat_mod').modal('show');
    })

    $('#tabel > tbody').on('click', '#penyakit', function() {
      var rows = tabel.rows($(this).parents('tr')).data();
      $('.data_detail_head').html(
        rows[0]['karyawan']['nama']
      );
      $('input[id="id"]').val(rows[0]['karyawan_id']);
      $('#tabel_detail_penyakit').DataTable({
        processing: true,
        destroy: true,
        serverSide: true,
        language: {
          processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
        },
        ajax: {
          'type': 'POST',
          'headers': {
            'X-CSRF-TOKEN': '{{csrf_token()}}'
          },
          'url': '/kesehatan/penyakit/' + rows[0]['karyawan_id']
        },

        columns: [{
            data: 'DT_RowIndex',
            orderable: false,
            searchable: false
          },
          {
            data: 'nama'
          },
          {
            data: 'jenis'
          },
          {
            data: 'kriteria_penyakit'
          },
          {
            data: 'keterangan'
          },
        ],
      });
      $('#penyakit_mod').modal('show');
    })
    $('#tambah_mod').on('hidden.bs.modal', function() {
      $('#tambah_mod form')[0].reset();
    });

    function numberRow_vaksin($t) {
      var c = 0 - 1;
      $t.find("tr").each(function(ind, el) {
        $(el).find("td:eq(0)").html(++c);
        var j = c - 1;
        $(el).find('.dosis').attr('name', 'dosis[' + j + ']');
        $(el).find('.date').attr('name', 'date[' + j + ']');
        $(el).find('.ket').attr('name', 'ket[' + j + ']');
        $('.dosis').select2();
        $('.ket').select2();
      });
    }
    $('#tambahitem_vaksin').click(function(e) {
      var data = `  <tr>
            <td>1</td>
                                                                <td>
                                                                <input type="date" class="form-control date" name="date[]">
                                                                </td>
                                                                <td>
                                                                    <select class="form-control select2 dosis" name="dosis[]">
                                                                        <option value="">Pilih</option>
                                                                        <option value="Astrazeneca">Astrazeneca</option>
                                                                        <option value="Sinovac">Sinovac</option>
                                                                        <option value="Moderna">Moderna</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <select class="form-control select2 ket" name="ket[]">
                                                                        <option value="">Pilih</option>
                                                                        <option value="1">1</option>
                                                                        <option value="2">2</option>
                                                                        <option value="3">3</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                <button type="button" class="btn btn-danger karyawan-img-small" style="border-radius:50%;" id="closetable_vaksin"><i class="fas fa-times-circle"></i></button>
                                                   </td>
                                                </tr>`;
      $('#tabel_vaksin tr:last').after(data);
      numberRow_vaksin($("#tabel_vaksin"));
    });
    $('#tabel_vaksin').on('click', '#closetable_vaksin', function(e) {
      $(this).closest('tr').remove();
      numberRow_vaksin($("#tabel_vaksin"));
    });

    function numberRow_penyakit($t) {
      var c = 0 - 1;
      $t.find("tr").each(function(ind, el) {
        $(el).find("td:eq(0)").html(++c);
        var j = c - 1;
        $(el).find('.nama').attr('name', 'nama[' + j + ']');
        $(el).find('.jenis').attr('name', 'jenis[' + j + ']');
        $(el).find('.keterangan').attr('name', 'keterangan[' + j + ']');
        $(el).find('input[type="radio"]').attr('name', 'kriteria[' + j + ']');
        $('.jenis').select2();
      });
    }

    $('#tambahitem_penyakit').click(function(e) {
      var data = `  <tr>
      <td>1</td>
                                    <td>
                                      <input type="text" class="form-control d-none" name="id" id="id">
                                      <textarea type="text" class="form-control nama" name="nama[]"></textarea>
                                    </td>
                                    <td>
                                      <select class="form-control select2 jenis" style="width:100%" name="jenis[]">
                                        <option value="">Pilih jenis penyakit</option>
                                        <option value="Penyakit saat ini">Penyakit saat ini</option>
                                        <option value="Penyakit lama">Penyakit lama</option>
                                        <option value="Penyakit keluarga">Penyakit keluarga</option>
                                        <option value="Penyakit karena pekerjaan">Penyakit karena pekerjaan</option>
                                      </select>
                                    </td>
                                    <td>
                                      <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="kriteria[]" id="kriteria" value="1">
                                        <label class="form-check-label">Menular</label>
                                      </div>
                                      <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="kriteria[]" id="kriteroa" value="0">
                                        <label class="form-check-label">Tidak menular</label>
                                      </div>
                                    </td>
                                    <td>
                                      <textarea type="text" class="form-control keterangan" name="keterangan[]" id="keterangan"></textarea>
                                    </td>
                                    <td>
                                    <button type="button" class="btn btn-danger karyawan-img-small" style="border-radius:50%;" id="closetable_penyakit"><i class="fas fa-times-circle"></i></button>
                                                  </td>
                                                </tr>`;
      $('#tabel_penyakit tr:last').after(data);
      numberRow_penyakit($("#tabel_penyakit"));
    });
    $('#tabel_penyakit').on('click', '#closetable_penyakit', function(e) {
      $(this).closest('tr').remove();
      numberRow_vaksin($("#tabel_penyakit"));
    });
    $('.select2').select2();
  });
</script>
@endsection
