@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0  text-dark">Data Panel</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Beranda</a></li>
                <li class="breadcrumb-item active">Data Panel</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('adminlte_css')
<style>
</style>
@stop

@section('content')
<section class="content">
    <div class="container-fluid"><h2>Data Panel</h2>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" id="btnCreate">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2Z"/>
              </svg>Tambah
          </button>

          <!-- Modal -->
          <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Tambah Panel</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <form method="post" name="formPanel" id="formPanel">

                <div class="modal-body">
                    <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" id="inputGroup-sizing-sm">Nama Panel</span>
                        </div>
                        <input type="text" name="nm_panel" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
                    </div>
                    <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroup-sizing-sm">Kode Panel</span>
                        </div>
                        <input type="text" name="kd_panel" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
                    </div>
                    <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroup-sizing-sm">Posisi Panel</span>
                        </div>
                        <input type="text" name="posisi" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
                    </div>
                    <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroup-sizing-sm">Lokasi Panel</span>
                        </div>
                        <input type="text" name="lokasi" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                  <button type="submit" class="btn btn-primary" id="btnSimpan">Simpan</button>
                </div>
                </form>
              </div>
            </div>
          </div>


          <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <div class="row"><div class="col-sm-12 col-md-6">
                    <div class="dt-buttons btn-group flex-wrap">
                        <span class="dt-down-arrow"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <table id="tablePanel" class="table table-striped" >
            <thead>
            <tr>
                <th >No</th>
                <th >Nama Panel</th>
                <th >Kode Panel</th>
                <th >Posisi</th>
                <th >Lokasi</th>
                <th >Aksi</th>
            </tr>
            </thead>
            <tbody>

        </tbody>

            </table>
        </div>
    </div>
</div>
            </div>
        {{-- ISI PAGE --}}
    </div>
</section>
@stop

@section('adminlte_js')
<script>
    let dataPanel = [];
    $.ajax({
        type:'get',
        url:'http://localhost:8000/listrik/ambilpanel',
        success:function(data) {
            dataPanel.push(data.data)
            ambilPanel(data.data)
            console.log(data.data);
        }
    });

    function ambilPanel(data){
        let table = $('#tablePanel').DataTable({
            data,
            columns: [
                {
                    data: null,
                    render: function (data, type, full, meta) {
                        return  meta.row + 1;
                    }
                },
                {data: 'nm_device'},
                {data: 'device_id'},
                {data: 'posisi'},
                {data: 'lokasi'},
                {
                    data: null,
                    render: function ( data, type, full, meta ) {
                        return '<button type="button" class="btn btn-warning btnEdit" data-id="'+full.device_id+'">Ubah</button>&nbsp;<button type="button" class="btn btn-danger btnHapus" data-id="'+full.device_id+'">Hapus</button>';
                    }
                }
            ],
        });
    }

    $('#btnCreate').click(function(){
        $('#exampleModalLabel').html('Tambah Panel')
        $('#formPanel').trigger('reset')
        $('#exampleModal').modal('show')
    })

    $(document).on('click', '.btnEdit', function () {
        $('#exampleModalLabel').html('Ubah Panel')
        $('#exampleModal').modal('show')
        // let id = $(this).data('id');
        // dataPanel.filter((item) => {
        //     if(item.device_id == id){
        //         console.log(item);
        //     }
        // })
    })

    $(document).on('click', '.btnHapus', function () {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire(
                    'Deleted!',
                    'Your file has been deleted.',
                    'success'
                )
            }
        })
    })

    $('body').on('submit', '#formPanel', function (e) {
        e.preventDefault()
        $('#btnSimpan').html('Sending..');
        var formData = new FormData(this);
        $.ajax({
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            url: "http://localhost:8000/listrik/panel",
            type: "POST",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                console.log(data);
            }
        })
        // Swal.fire({
        //     title: 'Are you sure?',
        //     text: "You won't be able to revert this!",
        //     icon: 'warning',
        //     showCancelButton: true,
        //     confirmButtonColor: '#3085d6',
        //     cancelButtonColor: '#d33',
        //     confirmButtonText: 'Yes, save it!'
        // }).then((result) => {
        //     if (result.isConfirmed) {
        //         Swal.fire(
        //             'Deleted!',
        //             'Your file has been deleted.',
        //             'success'
        //         )
        //     }
        // })
    });

</script>
@stop
