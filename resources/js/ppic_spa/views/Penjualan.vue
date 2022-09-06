<template>
    <div>
        <h1 class="title">Penjualan</h1>
        <div class="card mt-6">
            <div class="card-header">
                <div class="card-header-title">Penjualan</div>
            </div>
            <div class="card-content">
                <div class="content field columns is-desktop">
                    <div class="column">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No Urut</th>
                                    <th>Nomor SO</th>
                                    <th>Nomor AKN</th>
                                    <th>Nomor PO</th>
                                    <th>Tanggal Buat</th>
                                    <th>Tanggal Edit</th>
                                    <th>Tanggal Delivery</th>
                                    <th>Customer</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <h1>{{ penjualans }}</h1>
    </div>
</template>
<script>
import axios from 'axios';
export default {
    data() {
        return {
            penjualans: [],
        }
    },
    methods: {
        async getPenjualan() {
            try {
                await axios.post('/penjualan/penjualan/ekatalog/data/semua')
                .then(response => {
                    this.penjualans = response.data.data;
                })
                .catch(error => {
                    console.log(error);
                });
            } catch (error) {
                console.log(error);
            }
        }
    },
    mounted() {
        this.getPenjualan();
    }
}
</script>