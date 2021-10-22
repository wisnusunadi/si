<form action="">
    <div v-if="edit.afterSubmit == 'error'">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Gagal menambahkan!</strong> Periksa
            kembali data yang diinput
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
    <div v-else-if="edit.afterSubmit == 'success'">
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
                    <input type="text" class="form-control" placeholder="Masukkan Nama Customer" v-model="edit.nama_customer" v-bind:class="{
                                                    'is-invalid':
                                                        edit.nama_customerer
                                                }" />
                    <div class="invalid-feedback" v-if="edit.msg.nama_customer">
                        {{ edit.msg.nama_customer }}
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="npwp" class="col-4 col-form-label" style="text-align:right;">NPWP</label>
                <div class="col-5">
                    <input type="text" class="form-control" value="" placeholder="Masukkan NPWP" v-model="edit.npwp" v-bind:class="{
                                                    'is-invalid': edit.npwper
                                                }" />
                    <div class="invalid-feedback" v-if="edit.msg.npwp">
                        {{ edit.msg.npwp }}
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="alamat" class="col-4 col-form-label" style="text-align:right;">Alamat</label>
                <div class="col-8">
                    <input type="text" class="form-control" placeholder="Masukkan Alamat" v-model="edit.alamat" v-bind:class="{
                                                    'is-invalid': edit.alamater
                                                }" />
                    <div class="invalid-feedback" v-if="edit.msg.alamat">
                        {{ edit.msg.alamat }}
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="telepon" class="col-4 col-form-label" style="text-align:right;">No Telp</label>
                <div class="col-5">
                    <input type="text" class="form-control" value="" placeholder="Masukkan Telepon" v-model="edit.telepon" v-bind:class="{
                                                    'is-invalid': edit.teleponer
                                                }" />
                    <div class="invalid-feedback" v-if="edit.msg.telepon">
                        {{ edit.msg.telepon }}
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="telepon" class="col-4 col-form-label" style="text-align:right;">Keterangan</label>
                <div class="col-5">
                    <textarea class="form-control" name="keterangan" id="keterangan" v-model="edit.keterangan"></textarea>
                </div>
            </div>
        </div>
    </div>


</form>