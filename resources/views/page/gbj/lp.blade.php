@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Produk dari luar perakitan</div>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Masuk</th>
                                    <th>Produk</th>
                                    <th>Dari</th>
                                    <th>Tujuan</th>
                                    <th>Jumlah</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td scope="row">1</td>
                                    <td>10-04-2022</td>
                                    <td>AMBULATORY BLOOD PRESSURE MONITOR</td>
                                    <td>Divisi IT</td>
                                    <td>Sebagai Sampel Produk</td>
                                    <td>10 Unit</td>
                                    <td>
                                        <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                            <div class="dropdown-menu">
                                                <button type="button" class="dropdown-item terimaProduk" onclick="eksekusi()">
                                                    <i class="fas fa-check"></i>&nbsp;Terima
                                                </button>
                                                <button type="button" class="dropdown-item detailProduk" onclick="tolakProduk()">
                                                    <i class="fas fa-times"></i>&nbsp;Tolak
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td scope="row">2</td>
                                    <td>10-04-2022</td>
                                    <td>AMBULATORY BLOOD PRESSURE MONITOR</td>
                                    <td>Divisi IT</td>
                                    <td>Sebagai Sampel Produk</td>
                                    <td>10</td>
                                    <td>
                                        <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                            <div class="dropdown-menu">
                                                <button type="button" class="dropdown-item terimaProduk" onclick="eksekusi()">
                                                    <i class="fas fa-check"></i>&nbsp;Terima
                                                </button>
                                                <button type="button" class="dropdown-item detailProduk" onclick="tolakProduk()">
                                                    <i class="fas fa-times"></i>&nbsp;Tolak
                                                </button>
                                            </div>
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

<!-- Modal -->
<div class="modal fade modal-eksekusi" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Produk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <table class="table add-produk">
                    <thead>
                        <tr>
                            <th>No Seri</th>
                            <th>Layout</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td scope="row">
                                <input type="text" class="form-control">
                            </td>
                            <td>
                                <select name="" id="" class="form-control">
                                    <option value="1">Layout 1</option>
                                    <option value="2">Layout 2</option>
                                </select>
                            </td>
                            <td>
                                <button class="btn btn-success addProduk"><i class="fas fa-plus"></i></button>&nbsp;<button class="btn btn-danger removeProduk"><i class="fas fa-times"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>
@stop

@section('adminlte_js')
<script>
    function tolakProduk() {
        Swal.fire({
            title: 'Apakah anda yakin menolak produk dari divisi IT?',
            showCancelButton: true,
            confirmButtonText: "Ya",
            cancelButtonText: "Tidak",
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                Swal.fire('Data Berhasil Diubah!', '', 'success')
            }
        });
    }

    function eksekusi() { 
        $('.modal-eksekusi').modal('show');
    }
    $(document).on("click",".addProduk", function () {
        $('.add-produk tbody').append('<tr><td scope="row"><input type="text" class="form-control"></td><td><select name="" id="" class="form-control"><option value="1">Layout 1</option><option value="2">Layout 2</option></select></td><td><button class="btn btn-success addProduk"><i class="fas fa-plus"></i></button>&nbsp;<button class="btn btn-danger removeProduk"><i class="fas fa-times"></i></button></td></tr>');
    });

    $(document).on("click",".removeProduk", function () {
        let check = $('.add-produk tbody tr');
        if (check.length > 1) {
            $(this).parent().parent().remove();
        }
    });
</script>
@stop
