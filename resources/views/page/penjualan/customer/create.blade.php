@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Customer</h1>
@stop

@section('adminlte_css')
<style>

</style>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="content">
            <form @submit.prevent="handleSubmit">
                <div class="row d-flex justify-content-center">
                    <div class="col-8">
                        <h5>Info Customer</h5>
                        <div class="card">
                            <div class="card-body">
                                <div v-if="afterSubmit == 'error'">
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <strong>Gagal menambahkan!</strong> Periksa
                                        kembali data yang diinput
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                </div>
                                <div v-else-if="afterSubmit == 'success'">
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <strong>Berhasil menambahkan data</strong>,
                                        Terima kasih
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group row">
                                            <label for="nama_produk" class="col-4 col-form-label" style="text-align:right;">Nama Customer</label>
                                            <div class="col-6">
                                                <input type="text" class="form-control" placeholder="Masukkan Nama Customer" v-model="nama_customer" v-bind:class="{
                                                    'is-invalid': nama_customerer
                                                }" />
                                                <div class="invalid-feedback" v-if="msg.nama_customer">
                                                    {{ msg.nama_customer }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="npwp" class="col-4 col-form-label" style="text-align:right;">NPWP</label>
                                            <div class="col-5">
                                                <input type="text" class="form-control" value="" placeholder="Masukkan NPWP" v-model="npwp" v-bind:class="{
                                                    'is-invalid': npwper
                                                }" />
                                                <div class="invalid-feedback" v-if="msg.npwp">
                                                    {{ msg.npwp }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="alamat" class="col-4 col-form-label" style="text-align:right;">Alamat</label>
                                            <div class="col-8">
                                                <input type="text" class="form-control" placeholder="Masukkan Alamat" v-model="alamat" v-bind:class="{
                                                    'is-invalid': alamater
                                                }" />
                                                <div class="invalid-feedback" v-if="msg.alamat">
                                                    {{ msg.alamat }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="telepon" class="col-4 col-form-label" style="text-align:right;">No Telp</label>
                                            <div class="col-5">
                                                <input type="text" class="form-control" value="" placeholder="Masukkan Telepon" v-model="telepon" v-bind:class="{
                                                    'is-invalid': teleponer
                                                }" />
                                                <div class="invalid-feedback" v-if="msg.telepon">
                                                    {{ msg.telepon }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="telepon" class="col-4 col-form-label" style="text-align:right;">Keterangan</label>
                                            <div class="col-5">
                                                <textarea class="form-control" name="keterangan" id="keterangan" v-model="keterangan"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row d-flex justify-content-center">
                    <div class="col-8">
                        <router-link :to="{ name: 'show' }"><span class="float-left"><button type="button" class="btn btn-danger">
                                    Batal
                                </button></span></router-link>
                        <span class="float-right"><button type="submit" class="btn btn-info">
                                Tambah
                            </button></span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('adminlte_js')

@stop