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
                <table class="table table-bordered" id="editTable" style="width: 100%">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="head-cb"></th>
                            <th>Merk</th>
                            <th>Nama Produk</th>
                            <th>No Seri Lama</th>
                            <th>No Seri Baru</th>
                            <th>Diajukan Oleh</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade show" id="pills-hapus" role="tabpanel" aria-labelledby="pills-hapus-tab">
                <table class="table table-bordered" id="hapusTable" style="width: 100%">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="head-cb1"></th>
                            <th>Merk</th>
                            <th>Nama Produk</th>
                            <th>No Seri</th>
                            <th>Diajukan Oleh</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <div class="d-flex justify-content-end">
            <button class="btn btn-primary buttonSubmit" id="btnApproveEdit">Setujui</button>
            <button class="btn btn-danger buttonReject" id="btn-reject">Reject</button>
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
        processing: true,
        ordering: false,
        ajax: {
            'type': 'POST',
            'datatype': 'JSON',
            'url': '/api/v2/gbj/list-update-noseri',
            'headers': {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
        },
        columns: [
            {data: 'checkbox'},
            {data: 'merk'},
            {data: 'produk'},
            {data: 'lama'},
            {data: 'baru'},
            {data: 'requested'},
        ],
        language: {
            search: "Cari:"
        }
    });
    $('#hapusTable').DataTable({
        processing: true,
        ordering: false,
        ajax: {
            'type': 'POST',
            'datatype': 'JSON',
            'url': '/api/v2/gbj/list-approve-noseri',
            'headers': {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
        },
        columns: [
            {data: 'checkbox'},
            {data: 'merk'},
            {data: 'produk'},
            {data: 'noseri'},
            {data: 'requested'},
        ],
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
