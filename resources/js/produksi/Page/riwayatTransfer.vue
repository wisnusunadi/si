<template>
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Riwayat Pengiriman</h1>
            </div><!-- /.col -->
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-4">
                        <label for="">Tanggal Pengiriman</label>
                        <input type="text" class="form-control">
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table">
                            <thead class="thead-light">
                                <th>Tanggal Pengiriman</th>
                                <th>Waktu Pengiriman</th>
                                <th>Nomor BPPB</th>
                                <th>Produk</th>
                                <th>Jumlah</th>
                                <th>Aksi</th>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import axios from 'axios'
export default {
    data() {
        return {
            loading: false,
            dataRiwayat: null
        }
    },
    methods: {
        async loadData(){
            try {
                this.loading = true
                await axios.post('/ap/prd/history/pengiriman').then(res => {
                    this.dataRiwayat = res.data;
                })
                this.loading = false
            } catch (error) {
                console.log(error);
            }
        }
    },
    mounted() {
        this.loadData();
    }
}
</script>
