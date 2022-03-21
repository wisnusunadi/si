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
                        <date-picker language="ID" apply-button-label="use" :show-helper-buttons="true"
                            :switch-button-initial="true" :is-monday-first="true" :date-input="{inputClass: 'my_class'}" :calendar-time-input="{readonly: true,step: 30,inputClass: 'my_custom_class',}" ref="date"/>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table" id="myTable">
                            <thead class="thead-light">
                                <tr>
                                    <th>Tanggal Pengiriman</th>
                                    <th>Waktu Pengiriman</th>
                                    <th>Nomor BPPB</th>
                                    <th>Produk</th>
                                    <th>Jumlah</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in dataRiwayat" :key="index">
                                    <td>{{ item.day_kirim }}</td>
                                    <td>{{ item.time_kirim }}</td>
                                    <td>{{ item.bppb }}</td>
                                    <td>{{ item.produk }}</td>
                                    <td>{{ item.jml }}</td>
                                    <td><button class="btn btn-outline-secondary"><i class="far fa-eye"></i>
                                            Detail</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import axios from 'axios'
    import DatePicker, {
        CalendarDialog
    } from 'vue-time-date-range-picker'
    export default {
        data() {
            return {
                loading: false,
                dataRiwayat: null,
                date: null,
            }
        },
        components: {
            DatePicker,
            CalendarDialog
        },
        methods: {
            async loadData() {
                try {
                    this.loading = true
                    await axios.post('/api/prd/history/pengiriman').then(res => {
                        this.dataRiwayat = res.data.data;
                    })
                    this.loading = false
                } catch (error) {
                    console.log(error);
                }
            },
        },
        mounted() {
            this.loadData();
        },
        updated() {
            $('#myTable').DataTable();
        },
        computed: {
            tanggal(){
               return this.$refs.date.DatePicker;
            },
        },
    }

</script>
