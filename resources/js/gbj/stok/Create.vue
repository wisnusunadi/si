<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="">
                        <div class="form-horizontal">
                            <div class="form-group row">
                                <label for="" class="col-sm-5 col-form-label" style="text-align:right;">Tanggal</label>
                                <div class="col-sm-7">
                                    <input type="date" class="form-control col-form-label @error('tanggal') is-invalid @enderror" v-model="tanggal_all" style="width:50%;" readonly value="">
                                </div>
                            </div>

                            <div class="form-group row">
                                <table class="table-hover table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Asal/Tujuan</th>
                                            <th>Produk</th>
                                            <th>Keterangan</th>
                                            <th>Jumlah</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>
                                                <div class="form-group row">
                                                <input type="date" class="form-control">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group row">
                                                <select name="" id="" class="select2 custom-select"></select>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group row">
                                                <select name="" id="" class="select2 custom-select"></select>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group row">
                                                    <textarea name="" id="" class="form-control"></textarea>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group row">
                                                    <input type="number" class="form-control">
                                                </div>
                                            </td>
                                            <td><div class="from-group">
                                                <i class="fas fa-plus" style="color:green;"></i>
                                                </div>    
                                            </td>
                                        </tr>
                                    </tbody>    
                                </table>    
                            </div>  
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>