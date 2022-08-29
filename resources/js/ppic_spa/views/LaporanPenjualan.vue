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
                                    <v-select :options="customerOptions" :clearable="false"></v-select>
                                </div>
                                <p class="help">Distributor / Customer boleh dikosongi</p>
                            </div>
                        </div>
                        <div class="columns is-desktop">
                            <div class="column"><label for="" class="label">Penjualan</label></div>
                            <div class="column">
                                <div class="columns is-desktop">
                                    <div class="column"><label for="" class="checkbox"><input type="checkbox"
                                                value="ekatalog" @change="penjualanSelect"> E-Katalog</label></div>
                                    <div class="column"><label for="" class="checkbox"><input type="checkbox"
                                                value="spa" @change="penjualanSelect"> SPA</label></div>
                                    <div class="column"><label for="" class="checkbox"><input type="checkbox"
                                                value="spb" @change="penjualanSelect"> SPB</label></div>
                                </div>
                            </div>
                        </div>
                        <div class="columns is-desktop">
                            <div class="column"><label for="" class="label">Tanggal Awal PO</label></div>
                            <div class="column">
                                <div class="control">
                                    <input type="date" class="input" v-model="tanggalAwalPO" :max="datemax">
                                </div>
                            </div>
                        </div>
                        <div class="columns is-desktop">
                            <div class="column"><label for="" class="label">Tanggal Akhir PO</label></div>
                            <div class="column">
                                <div class="control">
                                    <input type="date" class="input" v-model="tanggalAkhirPO" :max="datemax">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="card-header-title">Laporan Penjualan</div>
            </div>
        </div>
    </div>
</template>
<script>
    import axios from 'axios';
    import moment from "moment";
    import vSelect from 'vue-select';
    export default {
        components: {
            vSelect
        },
        data() {
            return {
                customerList: [],
                tanggalAwalPO: '',
                tanggalAkhirPO: '',
            }
        },
        methods: {
            async loadData() {
                await axios.get("/api/customer/select").then((response) => {
                    this.customerList = response.data;
                });
            },
            penjualanSelect(event) {
                console.log(event.target.value);
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
            }
        }
    }

</script>
