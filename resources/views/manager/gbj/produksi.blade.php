@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
<div class="content-header">
    <input type="hidden" name="" id="authid" value="{{ Auth::user()->id }}">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Persetujuan Perubahan Produk Nomor Seri</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/gbj/dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Produk</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <ul class="nav nav-pills mb-5" id="pills-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="pills-edit-tab" data-toggle="pill" href="#pills-edit" role="tab"
                    aria-controls="pills-edit" aria-selected="true">Ubah Nomor Seri</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-hapus-tab" data-toggle="pill" href="#pills-hapus" role="tab"
                    aria-controls="pills-hapus" aria-selected="false">Hapus Nomor Seri</a>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-edit" role="tabpanel" aria-labelledby="pills-edit-tab">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Merk</th>
                            <th>Nama Produk</th>
                            <th>Kelompok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1.</td>
                            <td>Elitech</td>
                            <td>ASL300</td>
                            <td>Water Treatment</td>
                            <td><button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#exampleModal">
                                <i class="far fa-eye"></i> Detail
                              </button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade show" id="pills-hapus" role="tabpanel" aria-labelledby="pills-hapus-tab">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Merk</th>
                            <th>Nama Produk</th>
                            <th>Kelompok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1.</td>
                            <td>Elitech</td>
                            <td>ASL300</td>
                            <td>Water Treatment</td>
                            <td><button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#exampleModal2">
                                <i class="far fa-eye"></i> Detail
                              </button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
<div class="modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <table class="table table-bordered" id="editTable">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="head-cb"></th>
                        <th>Merk</th>
                        <th>Nama Produk</th>
                        <th>Tanggal Pengajuan</th>
                        <th>No Seri Lama</th>
                        <th>No Seri Baru</th>
                        <th>Diajukan Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="checkbox" name="cb-child" id=""></td>
                        <td>Elitech</td>
                        <td>Produk 1</td>
                        <td>12 Desember 2020</td>
                        <td>123456789</td>
                        <td>123456789</td>
                        <td>Admin</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary buttonSubmit" id="btnApproveEdit"><i class="fas fa-check"></i> Setujui</button>&nbsp;
            <button class="btn btn-danger buttonReject" id="btn-reject"><i class="fas fa-ban"></i> Reject</button>
        </div>
      </div>
    </div>
  </div>
</div>


    <!-- Modal Hapus -->
    <div class="modal" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">`
              <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered" id="hapusTable">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="head-cb"></th>
                            <th>Merk</th>
                            <th>Nama Produk</th>
                            <th>Tanggal Pengajuan</th>
                            <th>No Seri Lama</th>
                            <th>No Seri Baru</th>
                            <th>Diajukan Oleh</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="checkbox" name="cb-child" id=""></td>
                            <td>Elitech</td>
                            <td>Produk 1</td>
                            <td>12 Desember 2020</td>
                            <td>123456789</td>
                            <td>123456789</td>
                            <td>Admin</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary buttonSubmit" id="btnApproveEdit"><i class="fas fa-check"></i> Setujui</button>&nbsp;
                <button class="btn btn-danger buttonReject" id="btn-reject"><i class="fas fa-ban"></i> Reject</button>
            </div>
          </div>
        </div>
      </div>
    </div>
@stop

@section('adminlte_js')
<script>
    $("#head-cb").prop('checked', false);
    $("#head-cb1").prop('checked', false);
    $('.buttonSubmit').attr('id', 'btnApproveEdit');
    $('.buttonReject').attr('id', 'btnRejectEdit');
    var authid = $('#authid').val();
    // Datatable
    $('#editTable').DataTable({
        // processing: true,
        // ordering: false,
        // ajax: {
        //     'type': 'POST',
        //     'datatype': 'JSON',
        //     'url': '/api/v2/gbj/list-update-noseri',
        //     'headers': {
        //         'X-CSRF-TOKEN': '{{csrf_token()}}'
        //     }
        // },
        // columns: [
        //     {data: 'checkbox'},
        //     {data: 'merk'},
        //     {data: 'produk'},
        //     {data: 'lama'},
        //     {data: 'baru'},
        //     {data: 'requested'},
        // ],
        language: {
            search: "Cari:"
        }
    });
    $('#hapusTable').DataTable({
        // processing: true,
        // ordering: false,
        // ajax: {
        //     'type': 'POST',
        //     'datatype': 'JSON',
        //     'url': '/api/v2/gbj/list-approve-noseri',
        //     'headers': {
        //         'X-CSRF-TOKEN': '{{csrf_token()}}'
        //     }
        // },
        // columns: [
        //     {data: 'checkbox'},
        //     {data: 'merk'},
        //     {data: 'produk'},
        //     {data: 'noseri'},
        //     {data: 'requested'},
        // ],
        language: {
            search: "Cari:"
        }
    });

    $('#head-cb').click(function () {
        if ($(this).prop('checked') == true) {
            $('#editTable').DataTable()
                .column(0)
                .nodes()
                .to$()
                .find('input[type=checkbox]')
                .prop('checked', true);
        } else {
            $('#editTable').DataTable()
                .column(0)
                .nodes()
                .to$()
                .find('input[type=checkbox]')
                .prop('checked', false);
        }
    })

    $('#head-cb1').click(function () {
        if ($(this).prop('checked') == true) {
            $('#hapusTable').DataTable()
                .column(0)
                .nodes()
                .to$()
                .find('input[type=checkbox]')
                .prop('checked', true);
        } else {
            $('#hapusTable').DataTable()
                .column(0)
                .nodes()
                .to$()
                .find('input[type=checkbox]')
                .prop('checked', false);
        }
    });

    $('#pills-edit-tab').on('click', function () {
        $('.buttonSubmit').removeAttr('id');
        $('.buttonSubmit').attr('id', 'btnApproveEdit');
        $('.buttonReject').attr('id', 'btnRejectEdit');
    });

    $('#pills-hapus-tab').on('click', function () {
        $('.buttonSubmit').removeAttr('id');
        $('.buttonSubmit').attr('id', 'btnApproveHapus');
        $('.buttonReject').attr('id', 'btnRejectHapus');
    });

    $(document).on('click', '#btnApproveEdit', function () {
        let a = $('#editTable').DataTable().column(0).nodes().to$().find('input[type=checkbox]:checked').map(
            function () {
                return $(this).val();
            }).get();

        if (a.length == 0) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Minimal 1 Data Dipilih!',
            })
        } else {
            Swal.fire({
                title: 'Kamu Yakin?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, approve it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/api/v2/gbj/proses-update-noseri',
                        type: 'post',
                        data: {
                            is_acc: 'approved',
                            noseriid: a,
                            accby: authid,
                        },
                        success: function(res) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Approved',
                                text: res.msg,
                            }).then(() => {
                                location.reload()
                            });
                        }
                    })
                }
            })
        }

    })

    $(document).on('click', '#btnApproveHapus', function () {
        let a = $('#hapusTable').DataTable().column(0).nodes().to$().find('input[type=checkbox]:checked').map(
            function () {
                return $(this).val();
            }).get();
        console.log(a);

        if (a.length == 0) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Minimal 1 Data Dipilih untuk Dihapus!',
            })
        } else {
            Swal.fire({
                title: 'Kamu Yakin?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Approved it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/api/v2/gbj/proses-delete-noseri',
                        type: 'post',
                        data: {
                            is_acc: 'approved',
                            noseriid: a,
                            accby: authid,
                        },
                        success: function(res) {
                            console.log(res);
                            Swal.fire({
                                icon: 'success',
                                title: 'Approved',
                                text: res.msg,
                            }).then(() => {
                                location.reload()
                            });
                        }
                    })
                }
            })
        }
    });

    $(document).on('click', '#btnRejectHapus', function () {
        let a = $('#hapusTable').DataTable().column(0).nodes().to$().find('input[type=checkbox]:checked').map(
            function () {
                return $(this).val();
            }).get();
        // console.log(a);
        if (a.length == 0) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Minimal 1 Data Dipilih untuk Dihapus!',
            })
        } else {
            Swal.fire({
                title: 'Kamu Yakin?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, reject it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/api/v2/gbj/proses-delete-noseri',
                        type: 'post',
                        data: {
                            is_acc: 'rejected',
                            noseriid: a,
                            accby: authid,
                        },
                        success: function(res) {
                            console.log(res);
                            Swal.fire({
                                icon: 'success',
                                title: 'Rejected',
                                text: res.msg,
                            }).then(() => {
                                location.reload()
                            });
                        }
                    })
                }
            })
        }
    });

    $(document).on('click', '#btnRejectEdit', function () {
        let a = $('#editTable').DataTable().column(0).nodes().to$().find('input[type=checkbox]:checked').map(
            function () {
                return $(this).val();
            }).get();
        console.log(a.length);

        if (a.length == 0) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Minimal 1 Data Dipilih!',
            })
        } else {
            Swal.fire({
                title: 'Kamu Yakin?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, reject it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/api/v2/gbj/proses-update-noseri',
                        type: 'post',
                        data: {
                            is_acc: 'rejected',
                            noseriid: a,
                            accby: authid,
                        },
                        success: function(res) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Rejected',
                                text: res.msg,
                            }).then(() => {
                                location.reload()
                            });
                        }
                    })
                }
            })
        }
    });

</script>
@stop
