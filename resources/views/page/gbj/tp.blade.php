@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
<style>
    .foo {
  float: left;
  width: 20px;
  height: 20px;
  margin: 5px;
  border: 1px solid rgba(0, 0, 0, .2);
}

.green {
  background: #28A745;
}

.blue {
  background: #17A2B8;
}

.gambar-resize {
    width: 250px;
  height: 250px;
}
</style>
<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="col-lg-12">
                <ul class="nav nav-tabs" id="myTab" role="tabList">
                    <li class="nav-item" role="presentation">
                        <a href="#semua-produk" class="nav-link active" id="semua-produk-tab" data-toggle="tab"
                            role="tab" aria-controls="semua-produk" aria-selected="true">Semua Riwayat Transaksi</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#produk" class="nav-link" id="produk-tab" data-toggle="tab" role="tab"
                            aria-controls="produk" aria-selected="true">Per Produk</a>
                    </li>
                </ul>
                <div class="tab-content card" id="myTabContent">
                    <div class="tab-pane fade show active card-body" id="semua-produk" role="tabpanel"
                        aria-labelledby="semua-produk-tab">
                        <div class="row">
                            <div class="col-sm-8">
                                    <div class="row align-items-center">
                                        <div class="col-lg-9 col-xl-8">
                                            <div class="row align-items-center">
                                                <div class="col-md-4 my-2 my-md-0">
                                                    <div class="input-icon">
                                                        <input type="text" class="form-control" placeholder="Cari..." id="kt_datatable_search_query">
                                                        <span>
                                                            <i class="flaticon2-search-1 text-muted"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 my-2 my-md-0">
                                                    <div class="d-flex align-items-center">
                                                        <label class="mr-3 mb-0 d-none d-md-block" for="">Dari</label>
                                                        <select name="" id="" class="form-control">
                                                            <option value="">All</option>
                                                            <option value="">Divisi IT</option>
                                                            <option value="">Divisi QC</option>
                                                            <option value="">Divisi SO</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 my-2 my-md-0">
                                                    <div class="d-flex align-items-center">
                                                        <label class="mr-3 mb-0 d-none d-md-block" for="">Tanggal</label>
                                                        <input type="text" name="" id="datetimepicker1" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-xl-4 mt-5 mt-lg-0">
                                            <a href="#" class="btn btn-outline-primary">Search</a>
                                        </div>
                                    </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="card">
                                    <div class="card-body">
                                        <p class="card-text">Keterangan Kolom <b>Dari/Ke:</b></p>
                                        <p class="card-text"><div class="foo green"></div> : Dari</p>
                                        <p class="card-text"><div class="foo blue"></div> : Ke</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="table-responsive">
                                    {{-- Tanggal Masuk dan Tanggal Keluar --}}
                                    <table class="table table-hover pertanggal" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Tanggal Masuk</th>
                                                <th>Tanggal Keluar</th>
                                                <th>Dari/Ke</th>
                                                <th>Tujuan</th>
                                                <th>Nomor SO</th>
                                                <th>Produk</th>
                                                <th>Jumlah</th>
                                                <td>Aksi</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>10-09-2022</td>
                                                <td>10-09-2022</td>
                                                <td><span class="badge badge-success">Divisi IT</span></td>
                                                <td>Uji Coba Produk</td>
                                                <td>641311666541</td>
                                                <td>Ambulatory</td>
                                                <td>100 Unit</td>
                                                <td><button class="btn btn-info" onclick="detailtanggal()"><i
                                                            class="far fa-eye"></i> Detail</button></td>
                                            </tr>
                                            <tr>
                                                <td>10-09-2022</td>
                                                <td>10-09-2022</td>
                                                <td><span class="badge badge-info">Divisi IT</span></td>
                                                <td>Uji Coba Produk</td>
                                                <td>641311666541</td>
                                                <td>Ambulatory</td>
                                                <td>100 Unit</td>
                                                <td><button class="btn btn-info" onclick="detailtanggal()"><i
                                                            class="far fa-eye"></i> Detail</button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade card-body" id="produk" role="tabpanel" aria-labelledby="produk-tab">
                        <div class="row">
                            <div class="col-md-8 col-sm-8 col-xs-12">
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <form action="" method="GET" class="form-main">
                                    <div class="col-md-10 col-sm-10 col-xs-12">
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" placeholder="Cari"
                                                aria-label="Recipient's username" aria-describedby="button-addon2">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" type="button"
                                                    id="button-addon2"><i class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                {{-- <div class="card">
                                    <img class="card-img-top gambar-resize" src="https://images.unsplash.com/photo-1526930382372-67bf22c0fce2?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&amp;ixlib=rb-1.2.1&amp;auto=format&amp;fit=crop&amp;w=687&amp;q=80" alt="">
                                    <div class="card mt-4" style="background-color: #786FC4; color: white;">
                                        <div class="card-body">
                                            <h5 class="card-text pb-2"><b>Kode Produk</b></h5>
                                            <p class="card-text">5415313</p>
                                            <h5 class="card-text pb-2"><b>Nama Produk</b></h5>
                                            <p class="card-text">Ambulatory</p>
                                            <h5 class="card-text pb-2"><b>Deskripsi</b></h5>
                                            <p class="card-text">Produk Inovatif dan Kreatif</p>
                                            <h5 class="card-text pb-2"><b>Dimensi</b></h5>
                                            <div class="row">
                                                <div class="col-sm"><p>Panjang</p></div>
                                                <div class="col-sm"><p>Lebar</p></div>
                                                <div class="col-sm"><p>Tinggi</p></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm"><p>12 mm</p></div>
                                                <div class="col-sm"><p>13 mm</p></div>
                                                <div class="col-sm"><p>14 mm</p></div>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="card card-custom card-stretch gutter-b">
                                    <div class="card-body p-15 pb-20">
                                        <div class="row mb-17">
                                            <div class="col-xxl-5 mb-11 mb-xxl-0">
                                                <!--begin::Image-->
                                                <div class="card card-custom card-stretch">
                                                    <div class="card-body p-0 rounded px-10 py-15 d-flex align-items-center justify-content-center" style="background-color: #FFCC69;">
                                                        <img src="" class="mw-100 w-200px" style="transform: scale(1.6);">
                                                    </div>
                                                </div>
                                                <!--end::Image-->
                                            </div>
                                            <div class="col-xxl-7 pl-xxl-11">
                                                <h2 class="font-weight-bolder text-dark mb-7" style="font-size: 32px;">Apple Earbuds Amazing Headset</h2>
                                                <div class="font-size-h2 mb-7 text-dark-50">From 
                                                <span class="text-info font-weight-boldest ml-2">$299.00</span></div>
                                                <div class="line-height-xl">You also need to be able to accept that not every post is going to get your motor running. Some posts will feel like a chore, but if you have editorial control over what you write about, then choose topics you’d want to read – even if they relate to niche industries. The more excited you can be about your topic, the more excited your readers</div>
                                            </div>
                                        </div>
                                        <div class="row mb-6">
                                            <!--begin::Info-->
                                            <div class="col-6 col-md-4">
                                                <div class="mb-8 d-flex flex-column">
                                                    <span class="text-dark font-weight-bold mb-4">Brand</span>
                                                    <span class="text-muted font-weight-bolder font-size-lg">Nike Horizon</span>
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-4">
                                                <div class="mb-8 d-flex flex-column">
                                                    <span class="text-dark font-weight-bold mb-4">SKU</span>
                                                    <span class="text-muted font-weight-bolder font-size-lg">NF3535345</span>
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-4">
                                                <div class="mb-8 d-flex flex-column">
                                                    <span class="text-dark font-weight-bold mb-4">Color</span>
                                                    <span class="text-muted font-weight-bolder font-size-lg">Pure White</span>
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-4">
                                                <div class="mb-8 d-flex flex-column">
                                                    <span class="text-dark font-weight-bold mb-4">Collection</span>
                                                    <span class="text-muted font-weight-bolder font-size-lg">2020 Spring</span>
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-4">
                                                <div class="mb-8 d-flex flex-column">
                                                    <span class="text-dark font-weight-bold mb-4">In Stock</span>
                                                    <span class="text-muted font-weight-bolder font-size-lg">2770</span>
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-4">
                                                <div class="mb-8 d-flex flex-column">
                                                    <span class="text-dark font-weight-bold mb-4">Sold Items</span>
                                                    <span class="text-muted font-weight-bolder font-size-lg">280</span>
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-4">
                                                <div class="mb-8 d-flex flex-column">
                                                    <span class="text-dark font-weight-bold mb-4">Total Sales</span>
                                                    <span class="text-muted font-weight-bolder font-size-lg">$24,900</span>
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-4">
                                                <div class="mb-8 d-flex flex-column">
                                                    <span class="text-dark font-weight-bold mb-4">Net Profit</span>
                                                    <span class="text-muted font-weight-bolder font-size-lg">$3,750</span>
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-4">
                                                <div class="mb-8 d-flex flex-column">
                                                    <span class="text-dark font-weight-bold mb-4">Balance</span>
                                                    <span class="text-muted font-weight-bolder font-size-lg">$68,300</span>
                                                </div>
                                            </div>
                                            <!--end::Info-->
                                        </div>
                                        <!--begin::Buttons-->
                                        <div class="d-flex">
                                            <button type="button" class="btn btn-primary font-weight-bolder mr-6 px-6 font-size-sm">
                                            <span class="svg-icon">
                                                <!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Files/File-plus.svg-->
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                                        <path d="M5.85714286,2 L13.7364114,2 C14.0910962,2 14.4343066,2.12568431 14.7051108,2.35473959 L19.4686994,6.3839416 C19.8056532,6.66894833 20,7.08787823 20,7.52920201 L20,20.0833333 C20,21.8738751 19.9795521,22 18.1428571,22 L5.85714286,22 C4.02044787,22 4,21.8738751 4,20.0833333 L4,3.91666667 C4,2.12612489 4.02044787,2 5.85714286,2 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
                                                        <path d="M11,14 L9,14 C8.44771525,14 8,13.5522847 8,13 C8,12.4477153 8.44771525,12 9,12 L11,12 L11,10 C11,9.44771525 11.4477153,9 12,9 C12.5522847,9 13,9.44771525 13,10 L13,12 L15,12 C15.5522847,12 16,12.4477153 16,13 C16,13.5522847 15.5522847,14 15,14 L13,14 L13,16 C13,16.5522847 12.5522847,17 12,17 C11.4477153,17 11,16.5522847 11,16 L11,14 Z" fill="#000000"></path>
                                                    </g>
                                                </svg>
                                                <!--end::Svg Icon-->
                                            </span>New Stock</button>
                                            <button type="button" class="btn btn-light-primary font-weight-bolder px-8 font-size-sm">
                                            <span class="svg-icon">
                                                <!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Files/File-done.svg-->
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                                        <path d="M5.85714286,2 L13.7364114,2 C14.0910962,2 14.4343066,2.12568431 14.7051108,2.35473959 L19.4686994,6.3839416 C19.8056532,6.66894833 20,7.08787823 20,7.52920201 L20,20.0833333 C20,21.8738751 19.9795521,22 18.1428571,22 L5.85714286,22 C4.02044787,22 4,21.8738751 4,20.0833333 L4,3.91666667 C4,2.12612489 4.02044787,2 5.85714286,2 Z M10.875,15.75 C11.1145833,15.75 11.3541667,15.6541667 11.5458333,15.4625 L15.3791667,11.6291667 C15.7625,11.2458333 15.7625,10.6708333 15.3791667,10.2875 C14.9958333,9.90416667 14.4208333,9.90416667 14.0375,10.2875 L10.875,13.45 L9.62916667,12.2041667 C9.29375,11.8208333 8.67083333,11.8208333 8.2875,12.2041667 C7.90416667,12.5875 7.90416667,13.1625 8.2875,13.5458333 L10.2041667,15.4625 C10.3958333,15.6541667 10.6354167,15.75 10.875,15.75 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
                                                        <path d="M10.875,15.75 C10.6354167,15.75 10.3958333,15.6541667 10.2041667,15.4625 L8.2875,13.5458333 C7.90416667,13.1625 7.90416667,12.5875 8.2875,12.2041667 C8.67083333,11.8208333 9.29375,11.8208333 9.62916667,12.2041667 L10.875,13.45 L14.0375,10.2875 C14.4208333,9.90416667 14.9958333,9.90416667 15.3791667,10.2875 C15.7625,10.6708333 15.7625,11.2458333 15.3791667,11.6291667 L11.5458333,15.4625 C11.3541667,15.6541667 11.1145833,15.75 10.875,15.75 Z" fill="#000000"></path>
                                                    </g>
                                                </svg>
                                                <!--end::Svg Icon-->
                                            </span>Approve</button>
                                        </div>
                                        <!--end::Buttons-->
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-title">
                                        <div class="mb-7 mt-5 ml-3">
                                            <div class="row align-items-center">
												<div class="col-lg-9 col-xl-8">
													<div class="row align-items-center">
														<div class="col-md-4 my-2 my-md-0">
															<div class="input-icon">
																<input type="text" class="form-control" placeholder="Cari..." id="kt_datatable_search_query">
																<span>
																	<i class="flaticon2-search-1 text-muted"></i>
																</span>
															</div>
														</div>
														<div class="col-md-4 my-2 my-md-0">
															<div class="d-flex align-items-center">
																<label class="mr-3 mb-0 d-none d-md-block" for="">Dari</label>
                                                                <select name="" id="" class="form-control">
                                                                    <option value="">All</option>
                                                                    <option value="">Divisi IT</option>
                                                                    <option value="">Divisi QC</option>
                                                                    <option value="">Divisi SO</option>
                                                                </select>
															</div>
														</div>
														<div class="col-md-4 my-2 my-md-0">
															<div class="d-flex align-items-center">
																<label class="mr-3 mb-0 d-none d-md-block" for="">Tanggal</label>
                                                                <input type="text" name="" id="tanggalmasuk" class="form-control">
															</div>
														</div>
													</div>
												</div>
												<div class="col-lg-3 col-xl-4 mt-5 mt-lg-0">
													<a href="#" class="btn btn-outline-primary">Search</a>
												</div>
											</div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-7">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Tanggal Masuk</th>
                                                        <th>Tanggal Keluar</th>
                                                        <th>Dari/Ke</th>
                                                        <th>Tujuan</th>
                                                        <th>Jumlah</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td scope="row">10-04-2021</td>
                                                        <td>23-09-2021</td>
                                                        <td><span class="badge badge-primary">Divisi IT</span></td>
                                                        <td>Untuk Uji Coba</td>
                                                        <td>100 Unit</td>
                                                        <td><button type="button" class="btn btn-outline-info" onclick="detailProduk()"><i class="far fa-eye"> Detail</i></button></td>
                                                    </tr>
                                                    <tr>
                                                        <td scope="row">10-04-2021</td>
                                                        <td>23-09-2021</td>
                                                        <td><span class="badge badge-info">Divisi QC</span></td>
                                                        <td>Untuk Uji Coba</td>
                                                        <td>100 Unit</td>
                                                        <td><button type="button" class="btn btn-outline-info" onclick="detailProduk()"><i class="far fa-eye"> Detail</i></button></td>
                                                    </tr>
                                                </tbody>
                                            </table>
										</div>
                                    </div>
                                    <div class="card-footer clearfix">
                                        <ul class="pagination pagination-sm m-0 float-right">
                                          <li class="page-item"><a class="page-link" href="#">«</a></li>
                                          <li class="page-item"><a class="page-link" href="#">1</a></li>
                                          <li class="page-item"><a class="page-link" href="#">2</a></li>
                                          <li class="page-item"><a class="page-link" href="#">3</a></li>
                                          <li class="page-item"><a class="page-link" href="#">»</a></li>
                                        </ul>
                                      </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade modal-view-1" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Riwayat Produk Ambulator</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tanggal Masuk</th>
                            <th>Tanggal Keluar</th>
                            <th>Jumlah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td scope="row">10-11-2021</td>
                            <td>10-11-2021</td>
                            <td>100 Unit</td>
                            <td><button type="button" class="btn btn-info" onclick="detail()"><i class="far fa-eye"></i>
                                    Detail</button></td>
                        </tr>
                        <tr>
                            <td scope="row">10-11-2021</td>
                            <td>10-11-2021</td>
                            <td>100 Unit</td>
                            <td><button type="button" class="btn btn-info" onclick="detail()"><i class="far fa-eye"></i>
                                    Detail</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade modal-view-2" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <div class="row">
                        <div class="col">
                            <b>Produk</b>
                            <p>Ambulatory</p>
                        </div>
                        <div class="col">
                            <b>Tanggal Masuk</b>
                            <p>10-11-2021</p>
                        </div>
                        <div class="col">
                            <b>Tanggal Masuk</b>
                            <p>10-11-2021</p>
                        </div>
                    </div>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No Seri</th>
                            <th>Layout</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td scope="row">564654654</td>
                            <td>Layout 1</td>
                        </tr>
                        <tr>
                            <td scope="row">65464654</td>
                            <td>Layout 1</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Per Tanggal-->
<div class="modal fade" id="modal-per-tanggal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Produk Ambulatory</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No Seri</th>
                            <th>Layout</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td scope="row">65465465465</td>
                            <td>Layout 1</td>
                        </tr>
                        <tr>
                            <td scope="row">65465465465</td>
                            <td>Layout 2</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade modalDetail" id="" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Produk Ambulatory</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nomor Seri</th>
                            <th>Layout</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td scope="row">1</td>
                            <td>54131313151</td>
                            <td>Layout 1</td>
                        </tr>
                        <tr>
                            <td scope="row">2</td>
                            <td>54131313151</td>
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
        function preview() {
            $('.modal-view-1').modal('show')
        }

        function detail() {
            $('.modal-view-2').modal('show')
        }
        $('#datetimepicker1').daterangepicker({});
        $('#tanggalmasuk').daterangepicker({});


        function detailtanggal() {
            $('#modal-per-tanggal').modal('show');
        }

        function detailProduk() { 
            $('.modalDetail').modal('show');
        }

        $(document).ready(function () {
            $('.pertanggal').dataTable({
                bFilter: false
            });
        });
    </script>
    @stop
