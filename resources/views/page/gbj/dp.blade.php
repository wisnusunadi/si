@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
<div class="card">
    <div class="card-header">
      <h3 class="card-title">Produk dari Perakitan </h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
      <table class="table table-bordered dalam-perakitan">
        <thead>
          <tr>
            <th>No</th>
            <th>Tanggal Masuk</th>
            <th>Produk</th>
            <th>Jumlah</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>

        </tbody>
      </table>
    </div>
  </div>
  <!-- Modal Detail-->
  <div class="modal fade terima-produk" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
          <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title"><b>Detail Produk <span id="title">AMBULATORY BLOOD PRESSURE MONITOR</span></b></h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
              </div>
              <div class="modal-body">
                <table class="table table-striped scan-produk">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="head-cb"></th>
                            <th>Nomor Seri</th>
                            <th>Layout</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                <button class="btn btn-info" data-toggle="modal" data-target="#ubah-layout">Ubah Layout</button>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                  <button type="button" class="btn btn-primary" id="simpanseri">Simpan</button>
              </div>
          </div>
      </div>
  </div>
  <!-- Modal Ubah Layout-->
  <div class="modal fade" id="ubah-layout" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title">Ubah Layout</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
              </div>
              <div class="modal-body">
                  <div class="form-group">
                      <label for="">Layout</label>
                      <select name="" id="change-layout" class="form-control">
                        @foreach ($layout as $l)
                            <option value="{{ $l->id }}">{{ $l->ruang }}</option>
                        @endforeach
                    </select>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                  <button type="button" class="btn btn-primary" onclick="ubahData()">Simpan</button>
              </div>
          </div>
      </div>
  </div>
  <!-- Modal -->
  <div class="modal fade detail-layout" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title"><b>Detail Produk <span id="titlee">AMBULATORY BLOOD PRESSURE MONITOR</span></b></h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
              </div>
              <div class="modal-body">
                  <table class="table table-seri">
                      <thead>
                          <tr>
                              <th>No Seri</th>
                              <th>Layout</th>
                          </tr>
                      </thead>
                      <tbody>
                          <tr>
                              <td>654165654</td>
                              <td>Layout 1</td>
                          </tr>
                          <tr>
                            <td>654165654</td>
                            <td>Layout 1</td>
                          </tr>
                      </tbody>
                  </table>
              </div>
          </div>
      </div>
  </div>
@stop

@section('adminlte_js')
<script>
    $('.dalam-perakitan').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/api/tfp/rakit',
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex'},
            { data: 'tgl_masuk'},
            { data: 'product'},
            { data: 'jumlah'},
            { data: 'action'}
        ],
        "oLanguage": {
        "sSearch": "Cari:"},
    });


    $(document).ready(function () {
        $('.terimaProduk').click(function (e) {
            $('.terima-produk').modal('show');
        });

        $("#head-cb").on('click', function () {
            var isChecked = $("#head-cb").prop('checked')
            $('.cb-child').prop('checked', isChecked)
        });
    });

    function ubahData() {
        let checkbox_terpilih = $('.scan-produk tbody .cb-child:checked');
        let layout = $('#change-layout').val();
        $.each(checkbox_terpilih, function (index, elm) {
            let b = $(checkbox_terpilih).parent().next().next().children().val(layout);
        });
        $('#ubah-layout').modal('hide');
    }

    $(document).on('click', '.editmodal', function() {
        var id = $(this).data('id');
        console.log(id);

        $.ajax({
            url: '/api/tfp/rakit-terima/' + id,
            success: function(res) {
                console.log(res);
                $('span#title').text(res.data[0].title);
            }
        })

        $('.scan-produk').DataTable().destroy();
        $('.scan-produk').DataTable({
            serverSide: false,
            autoWidth: false,
            processing: true,
            "ordering": false,
            stateSave: true,
            'bPaginate': true,
            ajax: {
                url: '/api/tfp/rakit-terima/' + id,
            },
            columns: [
                { data: 'checkbox'},
                { data: 'noserii'},
                { data: 'layout'},
            ],
                "oLanguage": {
            "sSearch": "Cari:"
            }
        });

        $.ajax({
            url: '/api/gbj/sel-layout',
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if(res) {
                    console.log(res);
                    $("#layout_id").empty();
                    $.each(res, function(key, value) {
                        $("#layout_id").append('<option value="'+value.id+'">'+value.ruang+'</option');
                    });
                } else {
                    $("#layout_id").empty();
                }
            }
        });

        openModalTerima();
    });

    $(document).on('click', '.detailmodal', function() {
        var id = $(this).data('id');
        console.log(id);

        $.ajax({
            url: '/api/tfp/rakit-noseri/' + id,
            success: function(res) {
                console.log(res.data[0].title);
                $('span#titlee').text(res.data[0].title);
            }
        })

        $('.table-seri').DataTable().destroy();
        $('.table-seri').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '/api/tfp/rakit-noseri/' + id,
            },
            columns: [
                { data: 'noserii'},
                { data: 'layout'},
            ],
            "oLanguage": {
            "sSearch": "Cari:"}
        });

        openModalView();
    });

    function openModalTerima() {
        $('.terima-produk').modal('show');
    }
    function openModalView() {
        $('.detail-layout').modal('show');
    }

    // submit
    $(document).on('click', '#simpanseri', function() {
        const ids = [];
        const lay = [];
        $('.cb-child').each(function() {
            if ($(this).is(":checked")) {
                ids.push($(this).val());
                lay.push($(this).parent().next().next().children().val());
            }
        })

        $.ajax({
            url: "/api/prd/terimaseri",
            type: "post",
            data: {
                "_token" : "{{ csrf_token() }}",
                seri: ids,
                layout: lay,
            },
            success: function(res) {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: res.msg,
                    showConfirmButton: false,
                    timer: 1500
                })
                location.reload();
            }
        })
    })
</script>
@stop
