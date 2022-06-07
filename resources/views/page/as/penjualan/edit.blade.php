<div class="row filter">
    <div class="col-12">
        <div class="card removeshadow">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <div class="info-box bg-maroon">
                            <span class="info-box-icon"><i class="fas fa-receipt"></i></span>
                            <div class="info-box-content">
                            <span class="info-box-text">No PO</span>
                            <span class="info-box-number">{{$data->Pesanan->no_po}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="info-box bg-olive">
                            <span class="info-box-icon"><i class="far fa-user"></i></span>
                            <div class="info-box-content">
                            <span class="info-box-text">Nama Customer</span>
                            <span class="info-box-number">@if(isset($data->Pesanan->Spa)) {{$data->Pesanan->Spa->Customer->nama}} @else {{$data->Pesanan->Spb->Customer->nama}} @endif</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="info-box bg-warning">
                            <span class="info-box-icon"><i class="fas fa-cogs"></i></span>
                            <div class="info-box-content">
                            <span class="info-box-text">Nama Sparepart</span>
                            <span class="info-box-number">{{$data->Sparepart->nama}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <form action="">
                    <div class="form-horizontal">
                        <div class="form-group" for="keterangan">
                            <label for="" class="col-form-label">Beri Komentar</label>
                            <textarea class="form-control col-form-label" name="edit_keterangan"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <button class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i> Batal</button>
                <button class="btn btn-warning float-right"><i class="fas fa-plus"></i> Tambahkan</button>
            </div>
        </div>
    </div>
</div>
