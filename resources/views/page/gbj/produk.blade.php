@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-8">
                                <h3 class="card-title">Produk Gudang Barang Jadi</h3>
                            </div>
                            <div class="col-4 d-flex justify-content-end">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <span class="float-right">
                                                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-create">
                                                        <i class="fas fa-plus"></i>&nbsp;Tambah
                                                      </button>
                                        </span>
                                        <span class="dropdown float-right" id="semuaprodukfilter" style="margin-right: 5px">
                                            <button class="btn btn-outline-info dropdown-toggle" type="button"
                                                id="semuaprodukfilter" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false"><i class="fas fa-filter"></i>&nbsp;
                                                Filter
                                            </button>
                                            <div class="dropdown-menu p-3 text-nowrap" aria-labelledby="semuaprodukfilter">
                                                <div class="dropdown-header">Kelompok Produk</div>
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="sp_kelompok"
                                                            value="alat_kesehatan" />
                                                        <label class="form-check-label" for="sp_kelompok">
                                                            Alat Kesehatan
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="sp_kelompok"
                                                            value="sarana_kesehatan" />
                                                        <label class="form-check-label" for="sp_kelompok">
                                                            Sarana Kesehatan
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered" id="gudang-barang">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Produk</th>
                                    <th>Produk</th>
                                    <th>Stok</th>
                                    <th>Kelompok</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>ZTP80AS-UPGRADE</td>
                                    <td>STERILISATOR KERING</td>
                                    <td>100 Unit</td>
                                    <td>Alat Kesehatan</td>
                                    <td>
                                        <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                            <div class="dropdown-menu">
                                                    <button type="button" class="dropdown-item" data-toggle="modal" data-target="#modal-edit">
                                                        <i class="far fa-edit"></i>&nbsp;Edit
                                                      </button>
                                                      <button type="button" class="dropdown-item" data-toggle="modal" data-target="#modal-view">
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

{{-- Modal Create --}}
<!-- Button trigger modal -->
  
  <!-- Modal -->
  <div class="modal fade" id="modal-create" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add Produk GBJ</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <label for="">Nama Produk</label>
                <input type="text" class="form-control" placeholder="Nama Produk">
                </div>
                <div class="col">
                    <label for="">Stok</label>
                <input type="text" class="form-control" placeholder="Stok">
                </div>
            </div>
            <div class="form-group">
                <label for="">Deskripsi</label>
                <textarea class="form-control" id="" cols="5" rows="5"></textarea>
            </div>
            <div class="form-group">
                <label for="">Dimensi</label>
                <div class="d-flex justify-content-between">
                    <input type="text" class="form-control" placeholder="Panjang">&nbsp;
                    <input type="text" class="form-control" placeholder="Lebar">&nbsp;
                    <input type="text" class="form-control" placeholder="Tinggi">&nbsp;
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="">Produk</label>
                        <select name="" id="" class="form-control">
                            <option value="">Buku</option>
                            <option value="">Bolpoin</option>
                        </select>
                    </div>
                </div>
                <div class="col">
                    <label for="">Layout</label>
                    <select name="" id="" class="form-control">
                        <option value="">Buku</option>
                        <option value="">Bolpoin</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="inputGroupFile02"/>
                    <label class="custom-file-label" for="inputGroupFile02">Choose file</label>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>
  
{{-- Modal Edit --}}
<!-- Button trigger modal -->
  
  <!-- Modal -->
  <div class="modal fade" id="modal-edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edit Produk (Nama Produk)</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <label for="">Nama Produk</label>
                <input type="text" class="form-control" placeholder="Nama Produk">
                </div>
                <div class="col">
                    <label for="">Stok</label>
                <input type="text" class="form-control" placeholder="Stok">
                </div>
            </div>
            <div class="form-group">
                <label for="">Deskripsi</label>
                <textarea class="form-control" id="" cols="5" rows="5"></textarea>
            </div>
            <div class="form-group">
                <label for="">Dimensi</label>
                <div class="d-flex justify-content-between">
                    <input type="text" class="form-control" placeholder="Panjang">&nbsp;
                    <input type="text" class="form-control" placeholder="Lebar">&nbsp;
                    <input type="text" class="form-control" placeholder="Tinggi">&nbsp;
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="">Produk</label>
                        <select name="" id="" class="form-control">
                            <option value="">Buku</option>
                            <option value="">Bolpoin</option>
                        </select>
                    </div>
                </div>
                <div class="col">
                    <label for="">Layout</label>
                    <select name="" id="" class="form-control">
                        <option value="">Buku</option>
                        <option value="">Bolpoin</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="inputGroupFile02"/>
                    <label class="custom-file-label" for="inputGroupFile02">Choose file</label>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>
  
  {{-- Modal View --}}
  
  <!-- Modal -->
  <div class="modal fade" id="modal-view" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title">Modal title</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
              </div>
              <div class="modal-body">
                <div class="row">
                    <div class="col-6">
                        <img src="https://images.unsplash.com/photo-1615486510940-4e96763c7f6d?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1170&q=80" alt="">
                    </div>
                    <div class="col-6">
                        <p><b>Nama Produk</b></p>
                        <p>STERILISATOR KERING</p>
                        <p><b>Deskripsi Produk</b></p>
                        <p>Inovasi Produk Terbaru dari industri kami</p>
                        <p><b>Dimensi</b></p>
                        <div class="d-flex"><p>Panjang: 50</p>   
                        <p>Lebar: 50</p>   
                        <p>Tinggi: 50</p>   </div>
                        <p><b>Produk</b></p>
                        <p>Buku</p>
                        <p><b>Layout</b></p>
                        <p>Buku</p>
                    </div>
                </div>
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
{{-- <script src="{{ asset('native/js/gbj/produk.js') }}"></script> --}}
<script>
     $('#inputGroupFile02').on('change',function(){
                //get the file name
                var fileName = $(this).val();
                //replace the "Choose a file" label
                $(this).next('.custom-file-label').html(fileName);
            })
</script>
@stop