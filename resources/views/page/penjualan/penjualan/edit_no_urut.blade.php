<form action="/api/penjualan/no_urut/update/{{$data->id}}" method="POST" id="form-no_urut-update">
    @method('PUT')
    @csrf
    <div class="content">
        <div class="row">
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="card-header">Info Ekatalog</div>
                    <div class="card-body">
                        <div class="margin">
                            <a class="text-muted">No SO</a>
                            <b class="float-right">@if ($data->Pesanan->so) {{$data->pesanan->so}} @else - @endif</b>
                        </div>
                        <div class="margin">
                            <a class="text-muted">No AKN</a>
                            <b class="float-right">{{$data->no_paket}}</b>
                        </div>
                        <div class="margin">
                            <a class="text-muted">No PO</a>
                            <b class="float-right">
                                @if ($data->Pesanan->no_po)
                                {{$data->Pesanan->no_po}}
                                @else
                                -
                                @endif</b>
                        </div>
                        <div class="margin">
                            <a class="text-muted">Status</a>
                            <b class="float-right" id="status">
                                @if($data->status == "sepakat")
                                <span class="badge green-text">{{ucfirst($data->status)}}</span>
                                @elseif($data->status == "negosiasi")
                                <span class="badge yellow-text">{{ucfirst($data->status)}}</span>
                                @elseif($data->status == "batal")
                                <span class="badge red-text">{{ucfirst($data->status)}}</span>
                                @elseif($data->status == "draft")
                                <span class="badge blue-text">{{ucfirst($data->status)}}</span>
                                @endif
                            </b>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-12">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header"><i class="fas fa-edit"></i> Ubah No Urut</div>
                        <div class="card-body">
                            <div class="form-horizontal">
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-lg-5 col-md-12">No Urut</label>
                                    <div class="co-lg-5 col-md-12">
                                        <input type="number" class="form-control col-form-label" name="no_urut" id="no_urut" value="{{$data->no_urut}}">
                                        <div class="invalid-feedback" id="msgno_urut"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 float-left">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                    </div>
                    <div class="col-6">
                        <button type="submit" class="btn btn-warning float-right" id="btnsimpan">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
