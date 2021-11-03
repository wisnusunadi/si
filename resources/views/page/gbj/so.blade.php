@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="col-lg-12">
                <!-- Card -->
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-8">
                                <h3 class="card-title">Daftar SO Gudang Barang Jadi</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered" id="gudang-barang">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nomor SO</th>
                                    <th>Customer</th>
                                    <th>Batas Pengeluaran</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>2</td>
                                    <td>8457938475938475</td>
                                    <td>Rumah Sakit Dr. Soetomo</td>
                                    <td>10 Oktober 2021</td>
                                    {{-- Menggunakan Perkondisian Jika Data Sudah Dicek Maka Tampil Seperti ini --}}
                                    <td><span class="badge badge-primary">Sudah Dicek</span></td>
                                    <td>
                                        <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                            <div class="dropdown-menu">
                                                        <button type="button" class="dropdown-item addProduk" id="">
                                                            <i class="fas fa-plus"></i>&nbsp;Siapkan Produk
                                                          </button>
                                                        <button type="button" class="dropdown-item viewProduk" id="">
                                                            <i class="far fa-eye"></i>&nbsp;Detail
                                                          </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>8457938475938475</td>
                                    <td>Rumah Sakit Dr. Soetomo</td>
                                    <td>10 Oktober 2021</td>
                                    {{-- Menggunakan Perkondisian Jika Data Belum Dicek Maka Tampil Seperti ini --}}
                                    <td><span class="badge badge-danger">Belum Dicek</span></td>
                                    <td>
                                        <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                            <div class="dropdown-menu">
                                                        <button type="button" class="dropdown-item addProduk" id="">
                                                            <i class="fas fa-plus"></i>&nbsp;Add Produk
                                                          </button>
                                                        <button type="button" class="dropdown-item viewProduk" id="">
                                                            <i class="far fa-eye"></i>&nbsp;View
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
<div class="modal fade" id="addProdukModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                {{-- Tambahkan DataTable --}}
                <table class="table table-striped" id="add-produk">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Nama Produk</th>
                            <th>Tipe</th>
                            <th>Merk</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td>AMBULATORY BLOOD PRESSURE MONITOR</td>
                            <td>ABPM50</td>
                            <td>ELITECH</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>AMBULATORY BLOOD PRESSURE MONITOR</td>
                            <td>RGB</td>
                            <td>ELITECH</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                <button type="button" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="viewProdukModal"role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Produk SO 8457938475938475</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <table class="table table-striped" id="view-produk">
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th>Tipe</th>
                            <th>Merk</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>AMBULATORY BLOOD PRESSURE MONITOR</td>
                            <td>ABPM50</td>
                            <td>ELITECH</td>
                        </tr>
                        <tr>
                            <td>AMBULATORY BLOOD PRESSURE MONITOR</td>
                            <td>RGB</td>
                            <td>ELITECH</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@stop

@section('adminlte_js')
<script>
    $(document).ready(function () {
        $('.addProduk').click(function (e) { 
            $('#addProdukModal').modal('show');
        });
        $('.viewProduk').click(function (e) { 
            $('#viewProdukModal').modal('show');
        });
    });

    $('#add-produk').DataTable({
            'columnDefs': [
        {
            'targets': 0,
            'checkboxes': {
                'selectRow': true
            }
        }
    ],
    'select': {
        'style': 'multi'
    },
    'order': [[1, 'asc']]
    });
</script>
@stop