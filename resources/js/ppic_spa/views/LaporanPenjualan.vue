<template>
    <div>
        <h1 class="title">Laporan Penjualan</h1>
        <div class="card">
            <div class="card-header">
                <div class="card-header-title">
                    Pencarian
                </div>
            </div>
            <div class="card-content">
                <div class="content">
                    <div class="field">
                        <div class="columns is-desktop">
                            <div class="column">
                                <label for="" class="label">Distributor / Customer</label>
                            </div>
                            <div class="column">
                                <div class="control">
                                    <v-select :options="customerOptions" v-model="selectCustomer" :clearable="false"></v-select>
                                </div>
                                <p class="help">Distributor / Customer boleh dikosongi</p>
                            </div>
                        </div>
                        <div class="columns is-desktop">
                            <div class="column"><label for="" class="label">Penjualan</label></div>
                            <div class="column">
                                <div class="columns is-desktop">
                                    <div class="column"><label for="" class="checkbox"><input type="checkbox" ref="checkboxEkatalog"
                                                value="ekatalog" @change="penjualanSelect"> E-Katalog</label></div>
                                    <div class="column"><label for="" class="checkbox"><input type="checkbox" ref="checkboxSPA"
                                                value="spa" @change="penjualanSelect"> SPA</label></div>
                                    <div class="column"><label for="" class="checkbox"><input type="checkbox" ref="checkboxSPB"
                                                value="spb" @change="penjualanSelect"> SPB</label></div>
                                </div>
                            </div>
                        </div>
                        <div class="columns is-desktop">
                            <div class="column"><label for="" class="label">Tanggal Awal PO</label></div>
                            <div class="column">
                                <div class="control">
                                    <input type="date" class="input" v-model="tanggalAwalPO" :max="datemax" :disabled="checkTypeSales">
                                </div>
                            </div>
                        </div>
                        <div class="columns is-desktop">
                            <div class="column"><label for="" class="label">Tanggal Akhir PO</label></div>
                            <div class="column">
                                <div class="control">
                                    <input type="date" class="input" v-model="tanggalAkhirPO" :min="datemin" :max="datemax" :disabled="checkTypeSales">
                                </div>
                            </div>
                        </div>
                        <div class="columns is-mobile">
                            <div class="column">
                            </div>
                            <div class="column">
                                <div class="buttons">
                                    <button class="button is-danger is-outlined" @click="batal">Batal</button>
                                    <button class="button is-primary" @click="printReport" :disabled="checkSend">Cetak</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-6">
            <div class="card-header">
                <div class="card-header-title">Laporan Penjualan</div>
            </div>
            <div class="card-content">
                <div class="content">
                    <div class="field">
                        <div class="columns is-desktop">
                            <div class="column"><button class="button is-primary">Export</button></div>
                        </div>
                        <div class="columns is-desktop">
                            <div class="column">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>No PO</th>
                                            <th>No AKN</th>
                                            <th>No SO</th>
                                            <th>Customer / Distributor</th>
                                            <th>Tanggal Pesan</th>
                                            <th>Batas Kontrak</th>
                                            <th>Tanggal PO</th>
                                            <th>Instansi</th>
                                            <th>Satuan</th>
                                            <th>Produk</th>
                                            <th>No Seri</th>
                                            <th>Jumlah</th>
                                            <th>Keterangan</th>
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
</template>
<script>
    import axios from 'axios';
    import moment from "moment";
    import vSelect from 'vue-select';
    import $ from "jquery";
    import '/assets/rowgroup/dataTables.rowGroup.min.js';
    export default {
        components: {
            vSelect
        },
        data() {
            return {
                customerList: [],
                selectCustomer: null,
                typeSales: [],
                tanggalAwalPO: null,
                tanggalAkhirPO: null,
                reports: [],
            }
        },
        methods: {
            async loadData() {
                this.$store.commit('setIsLoading', true);
                try {
                    await axios.get("/api/customer/select").then((response) => {
                    this.customerList = response.data;
                });
                } catch (error) {
                   console.log(error);
                   this.$swal("Error", "Terjadi kesalahan saat memuat data", "error"); 
                }
                this.$store.commit('setIsLoading', false);
            },
            penjualanSelect(event) {
                if (event.target.checked) {
                    this.typeSales.push(event.target.value);
                } else {
                    this.typeSales.splice(this.typeSales.indexOf(event.target.value), 1);
                }
            },
            async printReport(){
                let customer = null;
                let typeSales = this.typeSales;
                let tanggalAwalPO = this.tanggalAwalPO;
                let tanggalAkhirPO =  this.tanggalAkhirPO;
                if (this.selectCustomer == null) {
                    customer = 'semua';
                }else{
                    customer = this.selectCustomer.value;
                }
                this.$store.commit('setIsLoading', true);
                try {
                    await axios.post("/api/laporan/penjualan/"
                    + typeSales + "/"
                    + customer + "/"
                    + tanggalAwalPO + "/"
                    + tanggalAkhirPO).then((response) => {
                        this.reports = response.data.data;
                        this.tableReports();
                        this.$store.commit('setIsLoading', false);
                        if(this.reports.length == 0){
                            this.$swal({
                                title: 'Data tidak ditemukan',
                                icon: 'warning',
                                timer: 2000,
                                timerProgressBar: true,
                                showConfirmButton: false,
                            });
                        }
                    }).catch((error) => {
                        console.log(error);
                    });
                } catch (error) {
                    console.log(error);
                    this.$swal({
                        title: 'Terjadi kesalahan',
                        icon: 'error',
                        timer: 2000,
                        timerProgressBar: true,
                        showConfirmButton: false,
                    });
                }
            },
            batal(){
                this.selectCustomer = null;
                this.typeSales = [];
                this.reports = [];
                this.tanggalAwalPO = null;
                this.tanggalAkhirPO = null;
                this.$refs.checkboxEkatalog.checked = false;
                this.$refs.checkboxSPA.checked = false;
                this.$refs.checkboxSPB.checked = false;
            },
            tableReports(){
                $('.table').DataTable({
                serverSide: false,
                destroy: true,
                processing: true,
                language: {
                    processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                },
                data: this.reports,
                columns: [
                    { data: 'no_po'},
                    { data: 'no_paket'},
                    { data: 'no_so'},
                    { data: 'nama_customer'},
                    { data: 'tgl_kirim'},
                    { data: 'tgl_kontrak'},
                    { data: 'tgl_po'},
                    { data: 'instansi'},
                    { data: 'satuan'},
                    { data: 'nama_produk'},
                    { data: 'no_seri'},
                    { data: 'jumlah'},
                    { data: 'ket'},
                ],
                pagingType: "simple_numbers_no_ellipses",
                rowGroup: {
                startRender: function(rows, group) {
                    var i = 0;
                    // console.log(group);
                    return $('<tr/>')
                        .append('<td colspan="13"><p style="font-weight:50;">' + group + '</td>');
                },
                endRender: function(rows, group) {
                    var totalPenjualan = rows
                        .data()
                        .pluck('jumlah')
                        .reduce(function(a, b) {
                            return a + b * 1;
                        }, 0);
                    totalPenjualan = $.fn.dataTable.render.number(',', '.', 2).display(totalPenjualan);
                    return $('<tr/>')
                        .append('<td colspan="13">Total Penjualan Produk: ' + rows.count() + '</td>')
                },
                dataSrc: function(row) {
                    return row.no_po;
                },
            }
            });
            }
        },
        mounted() {
            this.loadData();
        },
        computed: {
            customerOptions() {
                return this.customerList.map(customer => ({
                    value: customer.id,
                    label: customer.nama
                }));
            },
            datemax() {
                let date = new Date();
                return moment(date).format('YYYY-MM-DD');
            },
            datemin(){
                return this.tanggalAwalPO;
            },
            checkTypeSales(){
                if(this.typeSales.length > 0){
                    return false;
                }else{
                    return true;
                }
            },
            checkSend(){
                if(this.tanggalAwalPO != null && this.tanggalAkhirPO != null && this.typeSales.length > 0){
                    return false;
                }else{
                    return true;
                }
            },
            reportsgroupPO(){
                if(this.reports.length > 0){
                    return this.reports.reduce((r, a) => {
                    r[a.no_po] = [...r[a.no_po] || [], a];
                    return r;
                }, {});
                }

                // headerReports: [
                //     {text: 'No PO', value: 'no_po'},
                //     {text: 'No AKN', value: 'no_paket'},
                //     {text: 'No SO', value: 'no_so'},
                //     {text: 'Customer / Distributor', value: 'nama_customer'},
                //     {text: 'Tanggal Pesan', value: 'tgl_kirim'},
                //     {text: 'Batas Kontrak', value: 'tgl_kontrak'},
                //     {text: 'Tanggal PO', value: 'tgl_po'},
                //     {text: 'Instansi', value: 'instansi'},
                //     {text: 'Satuan', value: 'satuan'},
                //     {text: 'Produk', value: 'nama_produk'},
                //     {text: 'No Seri', value: 'no_seri'},
                //     {text: 'Jumlah', value: 'jumlah'},
                //     {text: 'Keterangan', value: 'ket'}
                // ],
            },
        },
        updated(){
            
        },
    }

</script>

<style scoped>
</style>
