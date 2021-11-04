@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
<div class="card">
    <div class="card-header">
      <h3 class="card-title">Produk dari Produksi </h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
      <table class="table table-bordered">
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
            <tr>
                <td>1</td>
                <td>10-11-2021</td>
                <td>AMBULATORY BLOOD PRESSURE MONITOR</td>
                <td>100</td>
                <td><div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton"
                    aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v"></i>
                    <div class="dropdown-menu">
                        <button type="button" class="dropdown-item eksekusi">
                            <i class="far fa-edit"></i>&nbsp;Eksekusi
                          </button>
                          <button type="button" class="dropdown-item detailProduk">
                            <i class="far fa-eye"></i>&nbsp;Detail
                          </button>
                    </div>
                </div></td>
            </tr>
            <tr>
                <td>2</td>
                <td>11-11-2021</td>
                <td>AMBULATORY BLOOD PRESSURE TESTING</td>
                <td>100</td>
                <td><div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton"
                    aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v"></i>
                    <div class="dropdown-menu">
                        <button type="button" class="dropdown-item eksekusi">
                            <i class="far fa-edit"></i>&nbsp;Eksekusi
                          </button>
                          <button type="button" class="dropdown-item detailProduk">
                            <i class="far fa-eye"></i>&nbsp;Detail
                          </button>
                    </div>
                </div></td>
            </tr>
        </tbody>
      </table>
    </div>
  </div>
  <!-- Modal Detail-->
  <div class="modal fade detail-produk" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title">Produk AMBULATORY</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
              </div>
              <div class="modal-body">
                <table class="table table-striped scan-produk">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Nomor Seri</th>
                            <th>Layout</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td>36541654654654564</td>
                            <td><select name="" id="" class="form-control">
                                <option value="">Layout 1</option>
                                <option value="">Layout 1</option>
                                </select></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>36541654654654564</td>
                            <td><select name="" id="" class="form-control">
                                <option value="">Layout 1</option>
                                <option value="">Layout 1</option>
                                </select></td>
                        </tr>
                    </tbody>
                </table>
                <button class="btn btn-info">Ubah Layout</button>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary">Save</button>
              </div>
          </div>
      </div>
  </div>
@stop

@section('adminlte_js')
<script>
    $('.scan-produk').DataTable({
            'columnDefs': [{
                'targets': 0,
                'checkboxes': {
                    'selectRow': true
                }
            }],
            'select': {
                'style': 'multi'
            },
            'order': [
                [1, 'asc']
            ]
    });
    $(document).ready(function () {
        $('.detailProduk').click(function (e) { 
            $('.detail-produk').modal('show');
        });
    });
</script>
@stop
