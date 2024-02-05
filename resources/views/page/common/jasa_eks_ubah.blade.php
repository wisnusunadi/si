@extends('adminlte.page')

@section('title', 'Beta Version')

@section('content_header')
<h1 class="m-0 text-dark">Dashboard</h1>
@stop

@section('content')
<div class="row">
  <div class="col-lg-12">
    <div class="col-lg-12">
      <form method="post" action="/jasa_eks/aksi_ubah/{{$jasa->id}}">
        {{ csrf_field() }}
        {{method_field('PUT')}}
        <div class="card">
          <div class="card-header bg-success">
            <div class="card-title"><i class="fas fa-plus-circle"></i>&nbsp;Ubah</div>
          </div>
          <div class="card-body">
            <div class="col-lg-12">
              <div class="row">
                <div class="col-lg-12">
                  <div class="form-horizontal">
                    <div class="form-group row">
                      <label for="no_pemeriksaan" class="col-sm-4 col-form-label " style="text-align:right;">Nama</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" name="nama" id="nama" value="{{$jasa->nama}}" style="width:45%;" readonly>
                      </div>
                      <span role="alert" id="no_pemeriksaan-msg"></span>
                    </div>
                    <div class="form-group row">
                      <label for="keterangan" class="col-sm-4 col-form-label" style="text-align:right;">Pilih Pengiriman</label>
                      <div class="col-sm-4">
                        <select class="form-control @error('via') is-invalid @enderror" name="via">
                          <option value="{{$jasa->via}}">{{$jasa->via}}</option>
                          <option value="Via Darat">Via Darat</option>
                          <option value="Via Laut">Via Laut</option>
                          <option value="Via Udara">Via Udara</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="keterangan" class="col-sm-4 col-form-label" style="text-align:right;">Alamat</label>
                      <div class="col-sm-8">
                        <textarea class="form-control @error('alamat') is-invalid @enderror" name="alamat" id="alamat" style="width:70%;">{{$jasa->alamat}}</textarea>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="no_pemeriksaan" class="col-sm-4 col-form-label" style="text-align:right;">Daerah</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control @error('jur') is-invalid @enderror" name="jur" id="jur" value="{{$jasa->jur}}" style="width:45%;">
                      </div>
                      <span role="alert" id="no_pemeriksaan-msg"></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="no_pemeriksaan" class="col-sm-4 col-form-label" style="text-align:right;">Telpon</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control @error('telp') is-invalid @enderror" name="telp" id="telp" value="{{$jasa->telp}}" style="width:45%;">
                    </div>
                    <span role="alert" id="no_pemeriksaan-msg"></span>
                  </div>
                  <div class="form-group row">
                    <label for="keterangan" class="col-sm-4 col-form-label" style="text-align:right;">Keterangan</label>
                    <div class="col-sm-8">
                      <textarea class="form-control @error('ket') is-invalid @enderror" name="ket" id="ket" style="width:70%;">{{$jasa->ket}}</textarea>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <span class="float-left"><button class="btn btn-danger rounded-pill"><i class="fas fa-times"></i>&nbsp;Batal</button></span>
            <span class="float-right"><button class="btn btn-success rounded-pill" id="button_tambah"><i class="fas fa-plus"></i>&nbsp;Ubah Data</button></span>
          </div>
        </div>
      </form>
    </div>
  </div>
  @stop