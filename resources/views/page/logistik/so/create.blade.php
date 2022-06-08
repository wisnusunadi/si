<form action="/api/logistik/so/create/{{$prd_array}}/{{$part_array}}/{{$jenis}}" method="POST" id="form-logistik-create">
    @method('PUT')
    @csrf
    <div class="content">
        <div class="row d-flex justify-content-center">
        {{-- <div class="d-flex justify-content-center"> --}}
            <div class="col-lg-11">
                {{-- <div class="d-flex justify-content-center"> --}}
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-horizontal">
                                        <h5>Data Pengiriman</h5>
                                        <div class="form-group row">
                                            <label class="col-form-label col-lg-5 col-md-12 labelket" for="no_invoice">No Surat Jalan</label>
                                            <div class="col-lg-6 col-md-12">
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        {{-- <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</button> --}}

                                                        {{-- <span class="input-group-text"> --}}
                                                            <select class="form-control jenis_sj" name="jenis_sj" id="jenis_sj">
                                                                @if($jenis != "SPB")
                                                                <option value="SPA-" selected>SPA-</option>
                                                                @elseif($jenis == "SPB")
                                                                <option value="B." selected>B.</option>
                                                                @endif
                                                                <option value="NBT">NBT</option>
                                                            </select>
                                                            {{-- @if($jenis != "SPB")
                                                            SPA-
                                                            @else
                                                            B.
                                                            @endif --}}
                                                        {{-- </span> --}}
                                                    </div>
                                                    <input type="text" class="form-control col-form-label" name="no_invoice" id="no_invoice">
                                                    <div class="invalid-feedback" id="msgnoinvoice"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-lg-5 col-md-12 labelket" for="tgl_kirim">Tanggal Pengiriman</label>
                                            <div class="col-lg-4 col-md-6">
                                                <input type="date" class="form-control col-form-label" name="tgl_kirim" id="tgl_kirim">
                                                <div class="invalid-feedback" id="msgtgl_kirim"></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="" class="col-form-label col-lg-5 col-md-12 labelket">Pengiriman</label>
                                            <div class="col-lg-5 col-md-12 col-form-label">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="pengiriman" id="pengiriman1" value="ekspedisi" />
                                                    <label class="form-check-label" for="pengiriman1">Ekspedisi</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="pengiriman" id="pengiriman2" value="nonekspedisi" />
                                                    <label class="form-check-label" for="pengiriman2">Non Ekspedisi</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row hide" id="ekspedisi">
                                            {{-- <div class="card col-lg-12 hide" id="ekspedisi"> --}}
                                                {{-- <div class="card-body">
                                                    <h6><b>Ekspedisi</b></h6> --}}
                                                    {{-- <div class="form-group row"> --}}
                                                        <label class="col-form-label col-lg-5 col-md-12 labelket" for="ekspedisi_id">Jasa Pengiriman</label>
                                                        <div class="col-lg-7 col-md-12">
                                                            <select class="select2 select-info form-control ekspedisi_id" name="ekspedisi_id" id="ekspedisi_id" style="width: 100%;">

                                                            </select>
                                                            <div class="invalid-feedback" id="msgekspedisi_id"></div>
                                                        </div>
                                                    {{-- </div> --}}
                                                {{-- </div> --}}
                                            {{-- </div> --}}
                                        </div>
                                        <div class="form-group row hide" id="nonekspedisi">
                                            {{-- <div class="card col-12 hide" id="nonekspedisi"> --}}
                                                {{-- <div class="card-body">
                                                    <h6><b>Non Ekspedisi</b></h6> --}}
                                                    {{-- <div class="form-group row"> --}}
                                                        <label class="col-form-label col-lg-5 col-md-12 labelket" for="nama_pengirim">Nama Pengirim</label>
                                                        <div class="col-lg-7 col-md-12">
                                                            <textarea type="text" class="form-control col-form-label" name="nama_pengirim" id="nama_pengirim"></textarea>
                                                            <div class="invalid-feedback" id="msgnama_pengirim"></div>
                                                        </div>
                                                    {{-- </div> --}}
                                                {{-- </div> --}}
                                            {{-- </div> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5>Data Barang</h5>
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table table-hover align-center" style="width:100%;" id="detailpesanan">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Nama Barang</th>
                                                            <th>Jumlah</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
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
                            <button type="submit" class="btn btn-info float-right" id="btnsimpan" disabled>Simpan</button>
                        </div>
                    </div>
                {{-- </div> --}}
            </div>
        {{-- </div> --}}
        </div>
    </div>
</form>
