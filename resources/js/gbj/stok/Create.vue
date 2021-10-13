<template>
    <div class="row" id="create">
        <div class="col-lg-12">
            <div class="card">
                <form @submit.prevent="validate">
                <div class="card-body">
                    
                        <div class="form-horizontal">
                            <div class="form-group row">
                                <label for="" class="col-sm-5 col-form-label" style="text-align:right;">Tanggal</label>
                                <div class="col-sm-7">
                                    <input type="date" class="form-control col-form-label" v-model="tanggal_all" style="width:50%;" @change="changeTanggal($event)">
                                </div>
                            </div>

                            <div class="form-group row">
                                <table class="table-hover table">
                                    <thead>
                                        <tr><th colspan="8"><span class="float-right"><button type="button" @click="addRow" class="btn btn-info btn-sm"><i class="fas fa-plus"></i> Tambah</button></span></th></tr>
                                        <tr>
                                            <th rowspan="2">No</th>
                                            <th rowspan="2">Tanggal</th>
                                            <th rowspan="2">Asal / Tujuan</th>
                                            <th rowspan="2">Produk</th>
                                            <th rowspan="2">Keterangan</th>
                                            <th colspan="2">Jumlah</th>
                                            <th rowspan="2">Aksi</th>
                                        </tr>
                                        <tr>
                                            <th>Masuk</th>
                                            <th>Keluar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for='(row, index) in rows'>
                                            <td>{{index + 1}}</td>
                                            <td>
                                                <div class="form-group row">
                                                    <input type="date" v-model="row.tanggal" class="form-control" v-bind:class="{'is-invalid' : row.tanggalerror}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group row">
                                                    <select class="custom-select form-control" v-model="row.divisi_id" v-bind:class="{'is-invalid' : row.divisiiderror}"></select>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group row">
                                                    <select class="custom-select" v-model="row.produk_id" v-bind:class="{'is-invalid' : row.produkiderror}"></select>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group row">
                                                    <textarea class="form-control" v-model="row.keterangan" v-bind:class="{'is-invalid' : row.tanggalerror}"></textarea>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group row">
                                                    <input type="number" min="0" class="form-control" v-model="row.jumlah_masuk" @keyup="changejummasuk($event, row)">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group row">
                                                    <input type="number" min="0" class="form-control" v-model="row.jumlah_keluar" @keyup="changejumkeluar($event, row)">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="from-group">
                                                    <a @click="removeRow(index)"><i class="fas fa-minus" style="color:red;"></i></a>
                                                </div>    
                                            </td>
                                        </tr>
                                    </tbody>    
                                </table>    
                            </div>  
                        </div>
                </div>
                <div class="card-footer">
                    <span>
                        <button type="button" class="btn btn-block btn-danger" style="width:200px;float:left;"> Batal</button>
                    </span>
                    <span>
                        <button type="submit" class="btn btn-block btn-info" style="width:200px;float:right;"> Tambah</button>
                    </span>
                </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
export default {
    data(){
        return{
        tanggal_all: '',
        rows: [{
            tanggal: '',
            divisi_id: '',
            produk_id: '',
            keterangan: '',
            jumlah_masuk: 0,
            jumlah_keluar: 0,
            tanggalerror: false,
            divisiiderror: false,
            produkiderror: false,
            keteranganerror: false,
        }]}
    },
    methods: {
        addRow: function(){
            this.rows.push({
            tanggal: this.tanggal_all,
            divisi_id: '',
            produk_id: '',
            keterangan: '',
            jumlah_masuk: '',
            jumlah_keluar: '',
        });
        },
        removeRow: function(index){
            //console.log(row);
            this.rows.splice(index, 1);
        },
        changeTanggal: function(event){
            this.rows.tanggal = event.target.value;
        },
        changejummasuk: function(event, rows){
            if(event.target.value >= 0){
                rows.jumlah_keluar = 0;
            }
        },
        changejumkeluar: function(event, rows){
            if(event.target.value >= 0){
                rows.jumlah_masuk = 0;
            }
        },
    },
    mounted() {
        
    }
}
</script>